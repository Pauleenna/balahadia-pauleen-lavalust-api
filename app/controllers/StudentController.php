<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['page_title'] = 'Student Home';
        $this->call->view('student_home', $data);
    }

    public function profile()
    {
        $student = [
            'student_id' => 'MCC2024-00045',
            'name'       => 'PAULEEN MARIANNE BALAHADIA',
            'course'     => 'BSIT',
            'year'       => '3RD YEAR',
            'section'    => 'F1',
            'email'      => 'pauleennnairam@gmail.com',
            // Optional extras — remove any you don't want
            'address'    => 'SOCORRO, ORIENTAL MINDORO',
            'contact'    => '09123456789',
            'hobbies'    => 'WATCHING, SLEEPING',
            'bio'        => 'MEMENTO MORI',
        ];

        $data['page_title'] = 'Student Profile';
        $data['student']    = $student;

        $this->call->view('student_profile', $data);
    }
     public function login()
{
    $_SESSION['student_access'] = true;
    redirect('student');
}

public function logout()
{
    unset($_SESSION['student_access']);
    redirect('student');
}
    
}