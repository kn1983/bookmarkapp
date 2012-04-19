<?php

namespace Classes;

/**
* Database Abstraction Layer
* @package dbal
*/
class Db
{
	public $db_connect_id;
	public $query_result;
	public $transaction = false;
	public $sql_time = 0;
	public $num_queries = array();
	public $open_queries = array();

	public $curtime = 0;
	public $query_hold = '';
	public $html_hold = '';

	public $persistency = false;
	public $user = '';
	public $server = '';
	public $dbname = '';

	// If set to true no error messages will be shown. Should be false during development only.
	public $return_on_error = false;

	// Set to true if error triggered
	public $sql_error_triggered = false;

	// Holding the last sql query on sql error
	public $sql_error_sql = '';
	// Holding the error information - only populated if sql_error_triggered is set
	public $sql_error_returned = array();

	// Holding transaction count
	public $transactions = 0;

	// Supports multi inserts?
	public $multi_insert = false;

	/**
	* Current sql layer
	*/
	public $sql_layer = '';

	/**
	* Wildcards for matching any (%) or exactly one (_) character within LIKE expressions
	*/
	public $any_char;
	public $one_char;

	/**
	* Constructor
	*/
	public function __construct()
	{
		$this->num_queries = array(
			'cached'		=> 0,
			'normal'		=> 0,
			'total'			=> 0,
		);

		// Do not change this please! This variable is used to easy the use of it - and is hardcoded.
		$this->any_char = chr(0) . '%';
		$this->one_char = chr(0) . '_';

		// Connect to the database
		$this->db_connect_id = $this->sql_connect();
	}

	/**
	* Establish a database connection
	*/
	public function sql_connect()
	{
		$this->db_connect_id = mysql_connect(Config::DB_HOST, Config::DB_USER, Config::DB_PW);

		if ($this->db_connect_id)
		{
			if (mysql_select_db(Config::DB_NAME, $this->db_connect_id))
			{

				mysql_query("SET NAMES 'utf8'", $this->db_connect_id);

				$modes = array('STRICT_ALL_TABLES','STRICT_TRANS_TABLES' );

				$mode = implode(',', $modes);

				mysql_query("SET SESSION sql_mode='{$mode}'", $this->db_connect_id);

				return $this->db_connect_id;
			}
		}

		return $this->sql_error('');
	}

	/**
	* Return number of sql queries and cached sql queries used
	*/
	public function sql_num_queries($cached = false)
	{
		return ($cached) ? $this->num_queries['cached'] : $this->num_queries['normal'];
	}

	/**
	* Add to query count
	*/
	public function sql_add_num_queries($cached = false)
	{
		$this->num_queries['cached'] += ($cached !== false) ? 1 : 0;
		$this->num_queries['normal'] += ($cached !== false) ? 0 : 1;
		$this->num_queries['total'] += 1;
	}

	/**
	* Fetch all rows
	*/
	public function sql_fetchrowset($query_id = false)
	{
		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if ($query_id !== false)
		{
			$result = array();
			while ($row = $this->sql_fetchrow($query_id))
			{
				$result[] = $row;
			}

			return $result;
		}

		return false;
	}

	/**
	* Fetch field
	* if rownum is false, the current row is used, else it is pointing to the row (zero-based)
	*/
	public function sql_fetchfield($field, $rownum = false, $query_id = false)
	{
		global $cache;

		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if ($query_id !== false)
		{
			if ($rownum !== false)
			{
				$this->sql_rowseek($rownum, $query_id);
			}

			if (!is_object($query_id) && isset($cache->sql_rowset[$query_id]))
			{
				return $cache->sql_fetchfield($query_id, $field);
			}

			$row = $this->sql_fetchrow($query_id);
			return (isset($row[$field])) ? $row[$field] : false;
		}

		return false;
	}

	/**
	* Correctly adjust LIKE expression for special characters
	* Some DBMS are handling them in a different way
	*
	* @param string $expression The expression to use. Every wildcard is escaped, except $this->any_char and $this->one_char
	* @return string LIKE expression including the keyword!
	*/
	public function sql_like_expression($expression)
	{
		$expression = utf8_str_replace(array('_', '%'), array("\_", "\%"), $expression);
		$expression = utf8_str_replace(array(chr(0) . "\_", chr(0) . "\%"), array('_', '%'), $expression);

		return $this->_sql_like_expression('LIKE \'' . $this->sql_escape($expression) . '\'');
	}

	/**
	* SQL Transaction
	* @access private
	*/
	public function sql_transaction($status = 'begin')
	{
		switch ($status)
		{
			case 'begin':
				// If we are within a transaction we will not open another one, but enclose the current one to not loose data (prevening auto commit)
				if ($this->transaction)
				{
					$this->transactions++;
					return true;
				}

				$result = $this->_sql_transaction('begin');

				if (!$result)
				{
					$this->sql_error();
				}

				$this->transaction = true;
			break;

			case 'commit':
				// If there was a previously opened transaction we do not commit yet... but count back the number of inner transactions
				if ($this->transaction && $this->transactions)
				{
					$this->transactions--;
					return true;
				}

				// Check if there is a transaction (no transaction can happen if there was an error, with a combined rollback and error returning enabled)
				// This implies we have transaction always set for autocommit db's
				if (!$this->transaction)
				{
					return false;
				}

				$result = $this->_sql_transaction('commit');

				if (!$result)
				{
					$this->sql_error();
				}

				$this->transaction = false;
				$this->transactions = 0;
			break;

			case 'rollback':
				$result = $this->_sql_transaction('rollback');
				$this->transaction = false;
				$this->transactions = 0;
			break;

			default:
				$result = $this->_sql_transaction($status);
			break;
		}

		return $result;
	}

	/**
	* Build sql statement from array for insert/update/select statements
	*
	* Idea for this from Ikonboard
	* Possible query values: INSERT, INSERT_SELECT, UPDATE, SELECT
	*
	*/
	public function sql_build_array($query, $assoc_ary = false)
	{
		if (!is_array($assoc_ary))
		{
			return false;
		}

		$fields = $values = array();

		if ($query == 'INSERT' || $query == 'INSERT_SELECT')
		{
			foreach ($assoc_ary as $key => $var)
			{
				$fields[] = $key;

				if (is_array($var) && is_string($var[0]))
				{
					// This is used for INSERT_SELECT(s)
					$values[] = $var[0];
				}
				else
				{
					$values[] = $this->_sql_validate_value($var);
				}
			}

			$query = ($query == 'INSERT') ? ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')' : ' (' . implode(', ', $fields) . ') SELECT ' . implode(', ', $values) . ' ';
		}
		else if ($query == 'UPDATE' || $query == 'SELECT')
		{
			$values = array();
			foreach ($assoc_ary as $key => $var)
			{
				$values[] = "$key = " . $this->_sql_validate_value($var);
			}
			$query = implode(($query == 'UPDATE') ? ', ' : ' AND ', $values);
		}

		return $query;
	}

	/**
	* Build IN or NOT IN sql comparison string, uses <> or = on single element
	* arrays to improve comparison speed
	*
	* @access public
	* @param	string	$field				name of the sql column that shall be compared
	* @param	array	$array				array of values that are allowed (IN) or not allowed (NOT IN)
	* @param	bool	$negate				true for NOT IN (), false for IN () (default)
	* @param	bool	$allow_empty_set	If true, allow $array to be empty, this function will return 1=1 or 1=0 then. Default to false.
	*/
	public function sql_in_set($field, $array, $negate = false, $allow_empty_set = false)
	{
		if (!sizeof($array))
		{
			if (!$allow_empty_set)
			{
				// Print the backtrace to help identifying the location of the problematic code
				$this->sql_error('No values specified for SQL IN comparison');
			}
			else
			{
				// NOT IN () actually means everything so use a tautology
				if ($negate)
				{
					return '1=1';
				}
				// IN () actually means nothing so use a contradiction
				else
				{
					return '1=0';
				}
			}
		}

		if (!is_array($array))
		{
			$array = array($array);
		}

		if (sizeof($array) == 1)
		{
			@reset($array);
			$var = current($array);

			return $field . ($negate ? ' <> ' : ' = ') . $this->_sql_validate_value($var);
		}
		else
		{
			return $field . ($negate ? ' NOT IN ' : ' IN ') . '(' . implode(', ', array_map(array($this, '_sql_validate_value'), $array)) . ')';
		}
	}

	/**
	* Run more than one insert statement.
	*
	* @param string $table table name to run the statements on
	* @param array &$sql_ary multi-dimensional array holding the statement data.
	*
	* @return bool false if no statements were executed.
	* @access public
	*/
	public function sql_multi_insert($table, &$sql_ary)
	{
		if (!sizeof($sql_ary))
		{
			return false;
		}

		if ($this->multi_insert)
		{
			$ary = array();
			foreach ($sql_ary as $id => $_sql_ary)
			{
				// If by accident the sql array is only one-dimensional we build a normal insert statement
				if (!is_array($_sql_ary))
				{
					return $this->sql_query('INSERT INTO ' . $table . ' ' . $this->sql_build_array('INSERT', $sql_ary));
				}

				$values = array();
				foreach ($_sql_ary as $key => $var)
				{
					$values[] = $this->_sql_validate_value($var);
				}
				$ary[] = '(' . implode(', ', $values) . ')';
			}

			return $this->sql_query('INSERT INTO ' . $table . ' ' . ' (' . implode(', ', array_keys($sql_ary[0])) . ') VALUES ' . implode(', ', $ary));
		}
		else
		{
			foreach ($sql_ary as $ary)
			{
				if (!is_array($ary))
				{
					return false;
				}

				$result = $this->sql_query('INSERT INTO ' . $table . ' ' . $this->sql_build_array('INSERT', $ary));

				if (!$result)
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	* Build sql statement from array for select and select distinct statements
	*
	* Possible query values: SELECT, SELECT_DISTINCT
	*/
	public function sql_build_query($query, $array)
	{
		$sql = '';
		switch ($query)
		{
			case 'SELECT':
			case 'SELECT_DISTINCT';

				$sql = str_replace('_', ' ', $query) . ' ' . $array['SELECT'] . ' FROM ';

				// Build table array. We also build an alias array for later checks.
				$table_array = $aliases = array();
				$used_multi_alias = false;

				foreach ($array['FROM'] as $table_name => $alias)
				{
					if (is_array($alias))
					{
						$used_multi_alias = true;

						foreach ($alias as $multi_alias)
						{
							$table_array[] = $table_name . ' ' . $multi_alias;
							$aliases[] = $multi_alias;
						}
					}
					else
					{
						$table_array[] = $table_name . ' ' . $alias;
						$aliases[] = $alias;
					}
				}

				$sql .= $this->_sql_custom_build('FROM', implode(' CROSS JOIN ', $table_array));

				if (!empty($array['LEFT_JOIN']))
				{
					foreach ($array['LEFT_JOIN'] as $join)
					{
						$sql .= ' LEFT JOIN ' . key($join['FROM']) . ' ' . current($join['FROM']) . ' ON (' . $join['ON'] . ')';
					}
				}

				if (!empty($array['WHERE']))
				{
					$sql .= ' WHERE ' . $this->_sql_custom_build('WHERE', $array['WHERE']);
				}

				if (!empty($array['GROUP_BY']))
				{
					$sql .= ' GROUP BY ' . $array['GROUP_BY'];
				}

				if (!empty($array['ORDER_BY']))
				{
					$sql .= ' ORDER BY ' . $array['ORDER_BY'];
				}

			break;
		}

		return $sql;
	}

	/**
	* SQL Transaction
	* @access private
	*/
	public function _sql_transaction($status = 'begin')
	{
		switch ($status)
		{
			case 'begin':
				return mysql_query('BEGIN', $this->db_connect_id);
			break;

			case 'commit':
				return mysql_query('COMMIT', $this->db_connect_id);
			break;

			case 'rollback':
				return mysql_query('ROLLBACK', $this->db_connect_id);
			break;
		}

		return true;
	}

	/**
	* Base query method
	*
	* @param	string	$query		Contains the SQL query which shall be executed
	* @param	int		$cache_ttl	Either 0 to avoid caching or the time in seconds which the result shall be kept in cache
	* @return	mixed				When casted to bool the returned value returns true on success and false on failure
	*
	* @access	public
	*/
	public function sql_query($query = '', $cache_ttl = 0)
	{
		if ($query != '')
		{
			global $cache;

			$this->query_result = ($cache_ttl && method_exists($cache, 'sql_load')) ? $cache->sql_load($query) : false;
			$this->sql_add_num_queries($this->query_result);

			if ($this->query_result === false)
			{
				if (($this->query_result = mysql_query($query, $this->db_connect_id)) === false)
				{
					$this->sql_error($query);
				}

				if ($cache_ttl && method_exists($cache, 'sql_save'))
				{
					$this->open_queries[(int) $this->query_result] = $this->query_result;
					$cache->sql_save($query, $this->query_result, $cache_ttl);
				}
				else if (strpos($query, 'SELECT') === 0 && $this->query_result)
				{
					$this->open_queries[(int) $this->query_result] = $this->query_result;
				}
			}
		}
		else
		{
			return false;
		}

		return $this->query_result;
	}

	/**
	* Fetch current row
	*/
	public function sql_fetchrow($query_id = false)
	{
		global $cache;

		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if (isset($cache->sql_rowset[$query_id]))
		{
			return $cache->sql_fetchrow($query_id);
		}

		return ($query_id !== false) ? @mysql_fetch_assoc($query_id) : false;
	}

	/**
	* Seek to given row number
	* rownum is zero-based
	*/
	public function sql_rowseek($rownum, &$query_id)
	{
		global $cache;

		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if (isset($cache->sql_rowset[$query_id]))
		{
			return $cache->sql_rowseek($rownum, $query_id);
		}

		return ($query_id !== false) ? @mysql_data_seek($query_id, $rownum) : false;
	}

	/**
	* Build LIKE expression
	* @access private
	*/
	public function _sql_like_expression($expression)
	{
		return $expression;
	}

	/**
	* Build db-specific query data
	* @access private
	*/
	public function _sql_custom_build($stage, $data)
	{
		switch ($stage)
		{
			case 'FROM':
				$data = '(' . $data . ')';
			break;
		}

		return $data;
	}

	/**
	* Validate values in queries
	*/
	public function _sql_validate_value($var)
	{
		if (is_null($var))
		{
			return 'NULL';
		}
		else if (is_string($var))
		{
			return "'" . $this->sql_escape($var) . "'";
		}
		else
		{
			return (is_bool($var)) ? intval($var) : $var;
		}
	}

	/**
	* Escape string used in sql query
	*/
	public function sql_escape($msg)
	{
		if (!$this->db_connect_id)
		{
			return mysql_real_escape_string($msg);
		}

		return mysql_real_escape_string($msg, $this->db_connect_id);
	}		

	/**
	* This will display an error message if a query goes wrong
	*/
	public function sql_error($sql = '')
	{

		$this->sql_error_triggered = true;
		$this->sql_error_sql = $sql;

		// Get the error message
		$this->sql_error_returned = mysql_error();

		if (!$this->return_on_error)
		{
			$message = 'SQL ERROR [ ' . $this->sql_layer . ' ]<br /><br />' . $this->sql_error_returned;

			if ($this->transaction)
			{
				$this->sql_transaction('rollback');
			}

			trigger_error($message, E_USER_ERROR);
		}

		if ($this->transaction)
		{
			$this->sql_transaction('rollback');
		}

		return $this->sql_error_returned;
	}

	/**
	* Close the database connection
	*/
	public function __destruct()
	{
		mysql_close($this->db_connect_id);
	}	
}

?>