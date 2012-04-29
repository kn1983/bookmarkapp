<?php

class Slim_Controllers_Users
{

    protected $data;

    protected $message;

    protected $request;

    protected $db;

    protected $session;

    protected $config;

    public function __construct(Slim_Database_Dbal $db) {
        $this->db = $db;
    }

    public function getUser() 
    {
        $sql = "SELECT id, username
                FROM users
                WHERE id = '{$this->data['id']}'
                AND id > 1 
                LIMIT 1";
        $result = $this->db->sql_query($sql);
        $this->data = $this->db->sql_fetchrow($result);

        return $this->data;
    }

    public function getUsers() 
    {
        $sql = "SELECT id, username 
                FROM users 
                WHERE id > 1";
        $result = $this->db->sql_query($sql);
        while ($row = $this->db->sql_fetchrow($result)) {
            $this->data[] = $row;
        }

        return $this->data;
    }

    public function setUser(Slim_Http_Request $request, Slim_Session $session, $config) 
    {

        // HACKAD KOD
        // $this->request = $request;
        $request = Slim::getInstance()->request();
        $body = $request->getBody();
        $user = json_decode($body);
        //SLUT PÅ HACKAD KOG


        $this->session = $session;
        $this->config  = $config; 

        $validation = new Slim_Forms_FormValidator();
        $passwordHash = new Slim_Security_PasswordHash();
        $message = array('status' => false, 'error' => false);

        
        //HACKAD KOD
        $this->data['username'] = $user->username;
        $this->data['password'] = $user->password;
        $this->data['email']    = $user->email;

        // $this->data['username'] = $this->request->post('username');
        // $this->data['password'] = $this->request->post('password');
        // $this->data['email']    = $this->request->post('email');

        //SLUT PÅ HACKAD KOD

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

        if (!sizeof($error)) {
            $sql_ary = array(
                'type'              => $this->config['usergroup.new'],
                'username'          => $this->data['username'],
                'username_clean'    => strtolower($this->data['username']),
                'password'          => $passwordHash->HashPassword($this->data['password']),
                'email'             => strtolower($this->data['email']),
                'form_salt'         => uniqid(),
                'last_visit'        => time(),          
            );

            $sql = "INSERT INTO users " . $this->db->sql_build_array('INSERT', $sql_ary);
            $this->db->sql_query($sql);

            $this->setId(mysql_insert_id());
            $this->session->sessionCreate($this->data['id']);
            $this->data = $this->getUser($this->data['id']);
            return $this->data;
        } else {
            $message['error'] = $error;
            return $message;
        }
    }

    public function updateUser() 
    {
        return 'Updating a user';
    }

    public function deleteUser() 
    {
        return 'Deleting a user';
    }

    public function setId($id)
    {
        $this->data['id'] = $id;
    }
}