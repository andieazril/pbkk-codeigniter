<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function index()
    {
        $this->load->library('session');
        $this->load->model('User_model');

        if ($this->session->userdata('logged_in'))
        {
            redirect('dashboard');
        }

        if ($this->input->post())
        {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->User_model->login($username, $password);

            if ($user)
            {
                $this->session->set_userdata('logged_in', TRUE);
                $this->session->set_userdata('username', $user->username);

                redirect('dashboard');
            }
            else
            {
                $data['error'] = 'Username atau password salah.';
            }
        }

        $data['title'] = 'Login';

        $this->load->view('login', $data);
    }

    public function logout()
    {
        $this->load->library('session');

        $this->session->sess_destroy();

        redirect('login');
    }
}