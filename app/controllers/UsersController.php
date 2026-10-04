<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->model('UsersModel');
        $this->call->library('form_validation');
    }

    /**
     * List active (non-deleted) users
     */
    public function index()
    {
        $data['page_title'] = 'User Management';
        $data['users']      = $this->UsersModel->all();
        $data['flash']      = $this->get_flash();

        $this->call->view('users', $data);
    }

    /**
     * Show the create-user form
     */
    public function create()
    {
        $data['page_title'] = 'Add User';
        $data['mode']       = 'create';
        $data['user']       = null;
        $data['errors']     = [];

        $this->call->view('user_form', $data);
    }

    /**
     * Handle create-user form submission
     */
    public function store()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,moderator,user]',
        ];

        if ($this->form_validation->validate($rules)) {

            $username = trim($this->io->post('username'));
            $email    = trim($this->io->post('email'));

            if ($this->UsersModel->exists(['username' => $username])) {
                $data['errors'] = ['That username is already taken.'];
            } elseif ($this->UsersModel->exists(['email' => $email])) {
                $data['errors'] = ['That email is already registered.'];
            } else {
                $this->UsersModel->insert([
                    'username'  => $username,
                    'email'     => $email,
                    'password'  => password_hash($this->io->post('password'), PASSWORD_DEFAULT),
                    'role'      => $this->io->post('role'),
                    'is_active' => 1,
                ]);

                $this->set_flash('User created successfully.');
                redirect('users');
            }
        } else {
            $data['errors'] = $this->form_validation->get_errors();
        }

        $data['page_title'] = 'Add User';
        $data['mode']       = 'create';
        $data['user']       = $this->io->post();

        $this->call->view('user_form', $data);
    }

    /**
     * Show the edit-user form
     */
    public function edit($id)
    {
        $user = $this->UsersModel->find((int) $id);

        if (!$user) {
            redirect('users');
        }

        $data['page_title'] = 'Edit User';
        $data['mode']       = 'edit';
        $data['user']       = $user;
        $data['errors']     = [];

        $this->call->view('user_form', $data);
    }

    /**
     * Handle edit-user form submission
     */
    public function update($id)
    {
        $id   = (int) $id;
        $user = $this->UsersModel->find($id);

        if (!$user) {
            redirect('users');
        }

        $rules = [
            'username' => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email',
            'role'     => 'required|in_list[admin,moderator,user]',
        ];

        $data['errors'] = [];

        if ($this->form_validation->validate($rules)) {

            $username = trim($this->io->post('username'));
            $email    = trim($this->io->post('email'));

            $username_taken = $this->UsersModel->find_by('username', $username);
            $email_taken    = $this->UsersModel->find_by('email', $email);

            if ($username_taken && (int) $username_taken['id'] !== $id) {
                $data['errors'][] = 'That username is already taken.';
            } elseif ($email_taken && (int) $email_taken['id'] !== $id) {
                $data['errors'][] = 'That email is already registered.';
            } else {
                $update = [
                    'username'  => $username,
                    'email'     => $email,
                    'role'      => $this->io->post('role'),
                    'is_active' => !empty($_POST['is_active']) ? 1 : 0,
                ];

                // Only touch the password if a new one was provided
                $new_password = trim((string) $this->io->post('password'));
                if ($new_password !== '') {
                    if (strlen($new_password) < 6) {
                        $data['errors'][] = 'Password must be at least 6 characters.';
                    } else {
                        $update['password'] = password_hash($new_password, PASSWORD_DEFAULT);
                    }
                }

                if (empty($data['errors'])) {
                    $this->UsersModel->update($id, $update);
                    $this->set_flash('User updated successfully.');
                    redirect('users');
                }
            }
        } else {
            $data['errors'] = $this->form_validation->get_errors();
        }

        $data['page_title'] = 'Edit User';
        $data['mode']       = 'edit';
        $data['user']       = array_merge($user, $this->io->post() ?: []);

        $this->call->view('user_form', $data);
    }

    /**
     * Soft delete a user (sets deleted_at, row is kept in the database)
     */
    public function delete($id)
    {
        $id = (int) $id;

        if ($this->UsersModel->find($id)) {
            $this->UsersModel->soft_delete($id);
            $this->set_flash('User moved to trash.');
        }

        redirect('users');
    }

    /**
     * List soft-deleted users
     */
    public function trashed()
    {
        $data['page_title'] = 'Trashed Users';
        $data['users']      = $this->UsersModel->trashed();
        $data['flash']      = $this->get_flash();

        $this->call->view('users_trashed', $data);
    }

    /**
     * Restore a soft-deleted user
     */
    public function restore($id)
    {
        $this->UsersModel->restore((int) $id);
        $this->set_flash('User restored successfully.');
        redirect('users/trashed');
    }

    /**
     * Permanently delete a user (bypasses soft delete entirely)
     */
    public function force_delete($id)
    {
        $this->UsersModel->delete((int) $id);
        $this->set_flash('User permanently deleted.');
        redirect('users/trashed');
    }

    /**
     * Small session-based flash message helper
     */
    private function set_flash($message)
    {
        $_SESSION['flash_message'] = $message;
    }

    private function get_flash()
    {
        $message = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);
        return $message;
    }
}
