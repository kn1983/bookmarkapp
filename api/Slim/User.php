<?php

class Slim_User 
{

    protected $id;

    protected $type;

    protected $username;

    protected $username_clean;

    protected $password;

    protected $email;

    protected $form_salt;

    protected $data;

    protected $message;

    public function __construct($id = false) 
    {
        $this->id = $id;

        $this->message = array(
            'status'  => false,
            'error'   => false, 
        );
    }

    /**
     * Returns an array user information
     */
    public function getUser() 
    {
        global $app;

        $sql = "SELECT id, username
                FROM users
                WHERE id = '{$this->id}' 
                LIMIT 1";
        $result = $app->db->sql_query($sql);
        $this->data = $app->db->sql_fetchrow($result);

        return $this->data;
    }

    /**
     * Insert a user
     */
    public function setUser() 
    {
        global $app;

        $validation = new Slim_Forms_FormValidator();
        $passwordHash = new Slim_Security_PasswordHash();       

        $data = array(
            'username'  => $app->request()->post('username'),
            'password'  => $app->request()->post('password'),
            'email'     => $app->request()->post('email'),
        );

        $error = $validation->validateData($data, array(
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
                'type'              => $app->config('usergroup.new'),
                'username'          => $data['username'],
                'username_clean'    => strtolower($data['username']),
                'password'          => $passwordHash->HashPassword($data['password']),
                'email'             => strtolower($data['email']),
                'form_salt'         => uniqid(),
                'last_visit'        => time(),          
            );

            $sql = "INSERT INTO users " . $app->db->sql_build_array('INSERT', $sql_ary);
            $app->db->sql_query($sql);

            // Log in the user and redirect to the startpage or maybe change to user page or something later?
            $user_id = mysql_insert_id();
            $app->session->sessionCreate($user_id);

            $message['registration'] = true;
            return $message;
        }
        else
        {
            $message['error'] = $error;
            return $message;
        }               
    }

    /**
     * User getters
     */
    public function getId() {
        return $this->id;
    }

    public function getType() {
        return $this->type;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getUsernameClean() {
        return strtolower($this->username);
    }

    public function getEmail() {
        return strtolower($this->email);
    }

    public function getFormSalt() {

        return $this->form_salt;
    }

    /**
     * User setters
     */
    public function setId($id) {
        $this->id = $id;
    }

    public function setType($type) {
        $this->id = $type;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setFormSalt($form_salt) {
        $this->form_salt = $form_salt;
    }          
}