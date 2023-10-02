<?php 

class Users extends Controller {
    public function __construct(){
        $this->userModel = $this->model('User');
    }

    public function index(){}

    public function register(){
        if($this->isLoggedIn()){
            redirect('posts');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Sanitize POST
            $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
            ];

            // Check empty name field
            if(empty($data['name'])){
                $data['name_err'] = 'Name is required';
            }

            // Check empty email field
            if(empty($data['email'])){
                $data['email_err'] = 'Email is required';
            }

            // Check email already exists
            if($data['email']){
                if($this->userModel->findUserByEmail($data['email'])){
                    $data['email_err'] = 'User already registered with the email';
                }
            }

            // Check empty password field
            if(empty($data['password'])){
                $data['password_err'] = 'Password is required';
            } else if(strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 character';
            }

            // Check empty confrim password field
            if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = 'Confirm Password is required';
            } else if($data['password'] !== $data['confirm_password']) {
                $data['confirm_password_err'] = 'Passwords do not match';
            }

            if(empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){

                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register new user
                if($this->userModel->register($data)){
                    // create session
                    flash('register_success', 'Account created successfully and you can login now');
                    redirect('users/login');
                }else{
                    die('Something went wrong');
                }
            }else{
                $this->view('users/register', $data);
            }
        }else{
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
            ];
            $this->view('users/register', $data);
        }
    }

    public function login(){

        if($this->isLoggedIn()){
            redirect('posts');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Sanitize POST
            $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
            ];

            // Check empty email field
            if(empty($data['email'])){
                $data['email_err'] = 'Email is required';
            }

            // Check empty password field
            if(empty($data['password'])){
                $data['password_err'] = 'Password is required';
            }

            // Check email valid
            if(!$this->userModel->findUserByEmail($data['email'])){
                $data['email_err'] = 'Email is NOT Valid';
            }

            if(empty($data['email_err']) && empty($data['password_err'])){
                $user = $this->userModel->login($data);
                if($user){
                    // Login message
                    flash('login_success', 'Login Successfully');

                    // Create user session and redirect
                    $this->createUserSession($user);
                } else {
                    $data['password_err'] = 'Password is NOT valid';
                    $this->view('users/login', $data);
                }
            } else {
                $this->view('users/login', $data);
            }

        }else{
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
            ];
            $this->view('users/login', $data);
        }
    }

    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        redirect('posts');
    }
    
    // Logout & Destroy Session
    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        session_destroy();
        redirect('users/login');
      }
  
    // Check Logged In
    public function isLoggedIn(){
        if(isset($_SESSION['user_id'])){
            return true;
        } else {
            return false;
        }
    }
}