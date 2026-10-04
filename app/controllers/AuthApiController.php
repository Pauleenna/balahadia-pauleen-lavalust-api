<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthApiController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->library('api');
        $this->call->model('UsersModel');
    }

    /**
     * POST /api/login
     * Body: { "username": "...", "password": "..." }
     */
    public function login()
    {
        $this->api->require_method('POST');
        $this->api->rate_limit('login:' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'), 10, 60);

        $body     = $this->api->body();
        $username = trim((string) ($body['username'] ?? ''));
        $password = (string) ($body['password'] ?? '');

        if ($username === '' || $password === '') {
            $this->api->respond_error('Username and password are required.', 422);
        }

        $user = $this->UsersModel->find_by('username', $username)
            ?: $this->UsersModel->find_by('email', $username);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->api->respond_error('Invalid username or password.', 401);
        }

        if (empty($user['is_active'])) {
            $this->api->respond_error('This account is inactive.', 403);
        }

        $tokens = $this->api->issue_tokens([
            'id'   => $user['id'],
            'role' => $user['role'],
        ]);

        $this->api->respond([
            'message' => 'Login successful',
            'user'    => [
                'id'       => $user['id'],
                'username' => $user['username'],
                'email'    => $user['email'],
                'role'     => $user['role'],
            ],
            'tokens' => $tokens,
        ]);
    }

    /**
     * POST /api/refresh
     * Body: { "refresh_token": "..." }
     */
    public function refresh()
    {
        $this->api->require_method('POST');

        $body          = $this->api->body();
        $refresh_token = (string) ($body['refresh_token'] ?? '');

        if ($refresh_token === '') {
            $this->api->respond_error('refresh_token is required.', 422);
        }

        // This responds and exits internally (success or error).
        $this->api->refresh_access_token($refresh_token);
    }

    /**
     * POST /api/logout
     * Body: { "refresh_token": "..." }
     */
    public function logout()
    {
        $this->api->require_method('POST');

        $body          = $this->api->body();
        $refresh_token = (string) ($body['refresh_token'] ?? '');

        if ($refresh_token !== '') {
            $this->api->revoke_refresh_token($refresh_token);
        }

        $this->api->respond(['message' => 'Logged out successfully.']);
    }

    /**
     * POST /api/setup-admin
     * Body: { "setup_token": "...", "username": "...", "password": "..." }
     *
     * One-time bootstrap: creates the single admin account using ADMIN_EMAIL
     * from the environment. Refuses once an admin account already exists, and
     * refuses if ADMIN_SETUP_TOKEN isn't configured on the server at all —
     * so simply removing the env var on Render closes this permanently.
     */
    public function setup()
    {
        $this->api->require_method('POST');

        $configured_token = getenv('ADMIN_SETUP_TOKEN');
        $admin_email       = getenv('ADMIN_EMAIL');

        if (empty($configured_token) || empty($admin_email)) {
            $this->api->respond_error('Admin setup is not configured on this server.', 403);
        }

        $body        = $this->api->body();
        $setup_token = (string) ($body['setup_token'] ?? '');
        $username    = trim((string) ($body['username'] ?? ''));
        $password    = (string) ($body['password'] ?? '');

        if (!hash_equals($configured_token, $setup_token)) {
            $this->api->respond_error('Invalid setup token.', 403);
        }

        if ($this->UsersModel->find_by('role', 'admin')) {
            $this->api->respond_error('An admin account already exists. Setup is closed.', 409);
        }

        if ($username === '' || strlen($username) < 3) {
            $this->api->respond_error('username is required (min 3 characters).', 422);
        }

        if (strlen($password) < 8) {
            $this->api->respond_error('password is required (min 8 characters).', 422);
        }

        if ($this->UsersModel->find_by('username', $username)) {
            $this->api->respond_error('That username is already taken.', 409);
        }

        $id = $this->UsersModel->insert([
            'username'  => $username,
            'email'     => $admin_email,
            'password'  => password_hash($password, PASSWORD_DEFAULT),
            'role'      => 'admin',
            'is_active' => 1,
        ]);

        $this->api->respond([
            'message' => 'Admin account created. You can now log in at /api/login.',
            'user'    => [
                'id'       => $id,
                'username' => $username,
                'email'    => $admin_email,
                'role'     => 'admin',
            ],
        ], 201);
    }
}
