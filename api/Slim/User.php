<?php

class Slim_User 
{

    protected $data;

    protected $message;

    protected $request;

    protected $db;

    protected $session;

    protected $config;

    public function __construct(Slim_Database_Dbal $db) 
    {
        $this->db = $db;
    }

    /**
     * Returns an array user information
     */
    public function getUser($id) 
    {

        $this->data['id'] = $id;

        $sql = "SELECT id, username
                FROM users
                WHERE id = '{$this->data['id']}' 
                LIMIT 1";
        $result = $this->db->sql_query($sql);
        $this->data = $this->db->sql_fetchrow($result);

        return $this->data;
    }

    /**
     * Insert a user
     */
    public function setUser(Slim_Http_Request $request, Slim_Session $session, $config = false) 
    {
        $this->request = $request;
        $this->session = $session;
        $this->data['type'] = $config; 

        $validation = new Slim_Forms_FormValidator();
        $passwordHash = new Slim_Security_PasswordHash();

        $message = array(
            'status'  => false,
            'error'   => false, 
        );

        $this->data['username'] = $this->request->post('username');
        $this->data['password'] = $this->request->post('password');
        $this->data['email']    = $this->request->post('email');

        $error = $validation->validateData($this->data, array(
            'password'      => array(
                array('string', 'password', 5, 60),
                array('password')),             
            'email'         => array(
                array('string', 'e-mail address', 6, 60),
                array('email')),
            'username'      => array(
                array('string', 'username', 2, 30),
                array('username', '')),                     
        ));

        if (!sizeof($error))
        {
            $sql_ary = array(
                'type'              => $this->data['type'],
                'username'          => $this->data['username'],
                'username_clean'    => strtolower($this->data['username']),
                'password'          => $passwordHash->HashPassword($this->data['password']),
                'email'             => strtolower($this->data['email']),
                'form_salt'         => uniqid(),
                'last_visit'        => time(),          
            );

            $sql = "INSERT INTO users " . $this->db->sql_build_array('INSERT', $sql_ary);
            $this->db->sql_query($sql);

            // Create a new session and log in the user
            $user_id = mysql_insert_id();
            $this->session->sessionCreate($user_id);

            $message['registration'] = true;
            return $message;
        }
        else
        {
            $message['error'] = $error;
            return $message;
        }               
    }         
}