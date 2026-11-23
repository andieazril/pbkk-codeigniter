<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends MY_Controller
{
    public function index()
    {
        $this->load->model('M_user');
        $data['title'] = 'Profil Saya';
        $data['user']  = $this->M_user->get_by_username($this->session->userdata('username'));
        $this->render('profil/index', $data);
    }

    public function password()
    {
        $this->only_post();
        $this->load->model('M_user');
        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[6]');
        $this->form_validation->set_rules('konfirmasi', 'Konfirmasi Password', 'required|matches[password_baru]');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors(' ', ' '));
            redirect('profil');
        }
        $user = $this->M_user->get_by_id($this->session->userdata('id_user'));
        if (!password_verify($this->input->post('password_lama'), $user->password)) {
            $this->session->set_flashdata('error', 'Password lama salah.');
            redirect('profil');
        }
        $this->M_user->update($user->id_user, array('password' => password_hash($this->input->post('password_baru'), PASSWORD_DEFAULT)));
        $this->session->set_flashdata('success', 'Password berhasil diubah.');
        redirect('profil');
    }
}
