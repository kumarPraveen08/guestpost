<?php

class Posts extends Controller {
    public function __construct() {
        
        if(!isset($_SESSION['user_id'])){
            redirect('users/login');
        }

        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }

    public function index(){
        $posts = $this->postModel->getPosts();

        $data = [
            'posts' => $posts
        ];
        $this->view('posts/index', $data);
    }

    public function show($id){
        $post = $this->postModel->getPost($id);

        if($post){
            $user = $this->userModel->getUser($post->user_id);
            $data = [
                'post' => $post,
                'user' => $user
            ];
            $this->view('posts/show', $data);
        } else {
            redirect('posts');
        }
    }

    public function add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'body_err' => '',
            ];
    
            // Check empty title
            if(empty($data['title'])){
                $data['title_err'] = 'Title is required';
            }
    
            // Check empty body
            if(empty($data['body'])){
                $data['body_err'] = 'Body is required';
            }
    
            if(empty($data['title_err']) && empty($data['body_err'])){
                // Create new post
                $this->postModel->addPost($data);

                // Message and redirect
                flash('post_message', 'Post created successfully');
                redirect('posts');
            } else {
                $this->view('posts/add', $data);
            }
        }else{
            $data = [
                'title' => '',
                'body' => '',
                'user_id' => '',
                'title_err' => '',
                'body_err' => '',
            ];
    
            $this->view('posts/add', $data);
        }
    }

    public function edit($id){

        // Check to see if user is owner of the post
        $post = $this->postModel->getPost($id);
        if($_SESSION['user_id'] !== $post->user_id){
            redirect('posts');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'body_err' => '',
            ];
    
            // Check empty title
            if(empty($data['title'])){
                $data['title_err'] = 'Title is required';
            }
    
            // Check empty body
            if(empty($data['body'])){
                $data['body_err'] = 'Body is required';
            }
    
            if(empty($data['title_err']) && empty($data['body_err'])){
                // Update post
                $this->postModel->updatePost($data);

                // Message and redirect
                flash('post_message', 'Post updated successfully');
                redirect('posts');
            } else {
                $this->view('posts/edit', $data);
            }
        }else{
            $data = [
                'id' => $id,
                'title' => $post->title,
                'body' => $post->body,
                'user_id' => $post->user_id,
                'title_err' => '',
                'body_err' => '',
            ];
    
            $this->view('posts/edit', $data);
        }
    }

    public function delete($id){

        // Check to see if user is owner of the post
        $post = $this->postModel->getPost($id);
        if($_SESSION['user_id'] !== $post->user_id){
            redirect('posts');
        } else {
            $this->postModel->deletePost($id);
            flash('post_message', 'Post deleted successfully');
            redirect('posts');
        }
    }
}