<?php

class Pages extends Controller {
    public function __construct(){
        
    }

    public function index(){
        // If logged in, redirect to posts
        if(isset($_SESSION['user_id'])){
            redirect('posts');
        }
        
        $data = [
            'title' => 'Home Page',
            'description' => 'this is homepage of the website'
        ];
        $this->view('pages/index', $data);
    }

    public function about(){
        $data = [
            'title' => 'About Page',
            'description' => 'this is about page of the website',
            'version' => VERSION,
        ];
        $this->view('pages/about', $data);
    }
}