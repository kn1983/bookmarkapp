<?php

class Slim_Controllers_Bookmarks
{

    protected $data;

    protected $db;

    public function __construct(Slim_Database_Dbal $db) 
    {
        $this->db = $db;
    }

    public function getBookmark() 
    {
        return 'Returns a bookmark';
    }

    public function getBookmarks() 
    {
        return 'Returns all bookmarks';
    }

    public function setBookmark() 
    {
        return 'Inserts a bookmark';
    }

    public function updateUser($id) 
    {
        return 'Updating a bookmark';
    }

    public function deleteUser($id) 
    {
        return 'Deleting a bookmark';
    }

    public function setId($id)
    {
        $this->data['id'] = $id;
    }
}