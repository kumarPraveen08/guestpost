<?php

class Post {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Get all posts
    public function getPosts(){
        $this->db->query('SELECT *, 
                        posts.id as postId, 
                        users.id as userId
                        FROM posts 
                        INNER JOIN users 
                        ON posts.user_id = users.id
                        ORDER BY posts.created_at DESC');
        return $this->db->resultSet();
    }

    // Get single post by id
    public function getPost($id){
        $this->db->query('SELECT * FROM posts WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();

        if($row){
            return $row;
        } else {
            return false;
        }
    }

    // Add new post
    public function addPost($data){
        $this->db->query('INSERT INTO posts(title, body, user_id) VALUES(:title, :body, :user_id)');

        $this->db->bind(':title', $data['title']);
        $this->db->bind(':body', $data['body']);
        $this->db->bind(':user_id', $data['user_id']);

        $this->db->execute();
    }

    // Update post by ID
    public function updatePost($data){
        $this->db->query('UPDATE posts SET title = :title, body = :body, user_id = :user_id WHERE id = :id');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':body', $data['body']);
        $this->db->bind(':user_id', $data['user_id']);

        $this->db->execute();
    }

    // Delete post by ID
    public function deletePost($id){
        $this->db->query('DELETE FROM posts WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();
    }
}