<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentMiddleware extends Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Custom access condition — adjust the message/logic to make it your own
        if (!isset($_SESSION['student_access']) || $_SESSION['student_access'] !== true) {
            // Not authorized: set a flash-style message and redirect
            $_SESSION['access_message'] = '{{ YOUR_CUSTOM_DENIAL_MESSAGE, e.g. "Access denied: please log in to view this profile." }}';
            redirect('student');
            exit;
        }
    }
}