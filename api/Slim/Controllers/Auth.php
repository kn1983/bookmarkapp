<?php

class Slim_Controllers_Auth
{
    protected $db;

    protected $data;

    protected $request;

    protected $config;

    protected $session;

    public function __construct(Slim_Database_Dbal $db) 
    {
        $this->db = $db;
    }

    public function getAuth(Slim_Http_Request $request, $config)
    {
        $this->request = $request;
        $this->config  = $config;

        $this->user_id    = $this->request->cookies($this->config['cookies.name'] . '_u');
        $this->session_id = $this->request->cookies($this->config['cookies.name'] . '_sid');         

        $sql = "SELECT u.id, u.type, u.username, u.form_salt
                FROM sessions AS s
                LEFT JOIN users AS u
                ON u.id = s.user_id
                WHERE s.sid = '{$this->session_id}' 
                AND s.user_id = '{$this->user_id}'
                LIMIT 1";
        $result = $this->db->sql_query($sql);
        $this->data = $this->db->sql_fetchrow($result);

        $this->data['logged_in'] = ($this->data['id'] != $this->config['user.guest'] && ($this->data['type'] == $this->config['usergroup.new'] || $this->data['type'] == $this->config['usergroup.normal'] || $this->data['user_type'] == $this->config['usergroup.admin'])) ? true : false;

        return $this->data;
    }

    public function setAuth(Slim_Http_Request $request, Slim_Session $session, $config)
    {
        $this->request = $request;
        $this->session = $session;
        $this->config  = $config;

        $validation = new Slim_Forms_FormValidator();
        $passwordHash = new Slim_Security_PasswordHash();
        $message = array('login' => false, 'error' => false);

        $this->data['username']  = $this->request->post('username');
        $this->data['password']  = $this->request->post('password');
        $this->data['autologin'] = $this->request->post('autologin');
    
        if (!$this->data['username'] || !$this->data['password']) {           
            $message['error'] = 'You need to enter a username and a password';
            return $message;
        }

        if (!$message['error'])
        {
            $username_clean = strtolower($this->data['username']);

            $sql = "SELECT id, username, password, email, type
                    FROM users
                    WHERE username_clean = '{$username_clean}'
                    AND type IN (" . $this->config['usergroup.new'] . ", " . $this->config['usergroup.normal'] . ", " . $this->config['usergroup.admin'] . ")";
            $result = $this->db->sql_query($sql);
            $row = $this->db->sql_fetchrow($result);

            if ($row['id']) {
                if ($passwordHash->CheckPassword($this->data['password'], $row['password']))
                {
                    $sql_ary = array(
                        'password'   => $passwordHash->HashPassword($this->data['password']),
                        'last_visit' => time(),                 
                    );

                    $sql = 'UPDATE users SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
                            WHERE id = ' . $row['id'];
                    $this->db->sql_query($sql);                          

                    $this->session->sessionCreate($row['id'], $this->data['autologin']);

                    $message['login'] = true;
                    return $message;
                } else {
                    $message['error'] = 'You have entered a wrong username or password';
                    return $message;             
                }
            } else {
                $message['error'] = 'You have entered a wrong username or password';
                return $message;                     
            }
        }
    }

    public function logout(Slim_Session $session) {
        $this->session = $session;

        $this->session->sessionKill();
        $this->session->sessionBegin();
        header('Location: index');
    }
}