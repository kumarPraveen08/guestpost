<?php 

class User {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Find user by email
    public function findUserByEmail($email){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();

        if($row){
            return true;
        } else {
            return false;
        }
    }

    // Register new user
    public function register($data){
        $this->db->query('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');

        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Login user
    public function login($data){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $data['email']);
        $row = $this->db->single();

        // Check user password
        if(password_verify($data['password'], $row->password)){
            return $row;
        } else {
            return false;
        }
    }

    // Get user by ID
    public function getUser($id){
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();

        if($row){
            return $row;
        } else {
            return false;
        }
    }
}