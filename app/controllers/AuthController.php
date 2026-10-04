<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->model('UsersModel');
        $this->call->library('form_validation');
    }

    /**
     * Show the login form
     */
    public function login()
    {
        // Already logged in? Skip straight to product management.
        if (!empty($_SESSION['auth_user_id'])) {
            redirect('products');
            exit;
        }

        $data['page_title'] = 'Login';
        $data['error']      = $_SESSION['auth_message'] ?? null;
        unset($_SESSION['auth_message']);

        $this->call->view('login', $data);
    }

    /**
     * Handle login form submission
     */
    public function authenticate()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        $error = null;

        if ($this->form_validation->validate($rules)) {

            $username = trim($this->io->post('username'));
            $password = (string) $this->io->post('password');

            $user = $this->UsersModel->find_by('username', $username);

            if (!$user) {
                $user = $this->UsersModel->find_by('email', $username);
            }

            if (!$user || empty($user['is_active'])) {
                $error = 'Invalid username/email or password.';
            } elseif (!password_verify($password, $user['password'])) {
                $error = 'Invalid username/email or password.';
            } else {
                // Success — regenerate the session id to prevent session fixation
                session_regenerate_id(true);

                $_SESSION['auth_user_id']  = $user['id'];
                $_SESSION['auth_username'] = $user['username'];
                $_SESSION['auth_role']     = $user['role'];

                redirect('products');
                exit;
            }
        } else {
            $error = 'Please enter both username and password.';
        }

        $data['page_title'] = 'Login';
        $data['error']      = $error;

        $this->call->view('login', $data);
    }

    /**
     * Log out and destroy the authenticated session
     */
    public function logout()
    {
        unset($_SESSION['auth_user_id'], $_SESSION['auth_username'], $_SESSION['auth_role']);
        session_regenerate_id(true);

        redirect('login');
    }
}
