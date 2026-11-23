<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_user');
    }

    public function index()
    {
        $data['title'] = 'Data User';
        $data['rows']  = $this->M_user->get_all();
        $this->render('users/index', $data);
    }

    public function add()
    {
        $data['title'] = 'Tambah User';
        $data['row']   = NULL;
        $this->render('users/form', $data);
    }

    public function edit($id = NULL)
    {
        $data['row'] = $this->M_user->get_by_id((int) $id);
        if (!$data['row']) show_404();
        $data['title'] = 'Edit User';
        $this->render('users/form', $data);
    }

    public function save()
    {
        $this->only_post();
        $id   = (int) $this->input->post('id_user');
        $back = $id ? 'users/edit/' . $id : 'users/add';

        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|alpha_dash|min_length[3]|max_length[50]');
        $this->form_validation->set_rules('id_level_user', 'Level', 'required|integer');
        $this->form_validation->set_rules('password', 'Password', ($id ? '' : 'required|') . 'min_length[6]');
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors(' ', '<br>'));
            redirect($back);
        }

        $d = array(
            'nama_lengkap'  => $this->input->post('nama_lengkap', TRUE),
            'username'      => $this->input->post('username', TRUE),
            'id_level_user' => (int) $this->input->post('id_level_user'),
            'id_dosen'      => $this->input->post('id_dosen') ? (int) $this->input->post('id_dosen') : NULL,
            'nim'           => $this->input->post('nim', TRUE) ?: NULL,
        );
        if ($this->M_user->username_exists($d['username'], $id ?: NULL)) {
            $this->session->set_flashdata('error', 'Username sudah digunakan.');
            redirect($back);
        }
        if ($this->input->post('password')) {
            $d['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        }

        if ($id) {
            $this->M_user->update($id, $d);
            $this->session->set_flashdata('success', 'User berhasil diperbarui.');
        } else {
            $this->M_user->insert($d);
            $this->session->set_flashdata('success', 'User berhasil ditambahkan.');
        }
        redirect('users');
    }

    public function delete($id = NULL)
    {
        $this->only_post();
        if ((int) $id === (int) $this->session->userdata('id_user')) {
            $this->session->set_flashdata('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
        } else {
            $this->M_user->delete((int) $id);
            $this->session->set_flashdata('success', 'User berhasil dihapus.');
        }
        redirect('users');
    }
}
