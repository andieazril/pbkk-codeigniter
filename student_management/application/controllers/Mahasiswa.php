<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa extends CI_Controller {
    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');

        if (!$this->session->userdata('logged_in'))
        {
            redirect('login');
        }
    }

    public function index()
    {
        $this->load->model('Mahasiswa_model');

        $data['mahasiswa'] = $this->Mahasiswa_model->get_all();
        $data['title'] = 'Data Mahasiswa';

        $this->load->view('templates/header', $data);
        $this->load->view('mahasiswa', $data);
        $this->load->view('templates/footer');
    }

    public function tambah()
    {
        $this->load->model('Mahasiswa_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nim', 'NIM', 'required');
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('jurusan', 'Jurusan', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $data['title'] = 'Tambah Mahasiswa';

            $this->load->view('templates/header', $data);
            $this->load->view('tambah_mahasiswa');
            $this->load->view('templates/footer');
        }
        else
        {
            $foto = '';

            if (!empty($_FILES['foto']['name']))
            {
                $config['upload_path'] = FCPATH . 'assets/uploads/mahasiswa/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('foto'))
                {
                    $data['title'] = 'Tambah Mahasiswa';
                    $data['error'] = $this->upload->display_errors();

                    $this->load->view('templates/header', $data);
                    $this->load->view('tambah_mahasiswa');
                    $this->load->view('templates/footer');
                    return;
                }

                $foto = $this->upload->data('file_name');
            }

            $data = array(
                'nim' => $this->input->post('nim'),
                'nama' => $this->input->post('nama'),
                'jurusan' => $this->input->post('jurusan'),
                'foto' => $foto
            );

            $this->Mahasiswa_model->insert($data);

            redirect('mahasiswa');
        }
    }

    public function edit($id)
    {
        $this->load->model('Mahasiswa_model');
        $this->load->library('form_validation');

        $data['mahasiswa'] = $this->Mahasiswa_model->get_by_id($id);

        if (!$data['mahasiswa'])
        {
            show_404();
        }

        $this->form_validation->set_rules('nim', 'NIM', 'required');
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('jurusan', 'Jurusan', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $data['title'] = 'Edit Mahasiswa';

            $this->load->view('templates/header', $data);
            $this->load->view('edit_mahasiswa', $data);
            $this->load->view('templates/footer');
        }
        else
        {
            $foto = $data['mahasiswa']->foto;

            if (!empty($_FILES['foto']['name']))
            {
                $config['upload_path'] = FCPATH . 'assets/uploads/mahasiswa/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('foto'))
                {
                    $data['title'] = 'Edit Mahasiswa';
                    $data['error'] = $this->upload->display_errors();

                    $this->load->view('templates/header', $data);
                    $this->load->view('edit_mahasiswa', $data);
                    $this->load->view('templates/footer');
                    return;
                }

                $foto = $this->upload->data('file_name');

                if ($data['mahasiswa']->foto != '')
                {
                    unlink(FCPATH . 'assets/uploads/mahasiswa/' . $data['mahasiswa']->foto);
                }
            }

            $update = array(
                'nim' => $this->input->post('nim'),
                'nama' => $this->input->post('nama'),
                'jurusan' => $this->input->post('jurusan'),
                'foto' => $foto
            );

            $this->Mahasiswa_model->update($id, $update);

            redirect('mahasiswa');
        }
    }

    public function hapus($id)
    {
        $this->load->model('Mahasiswa_model');

        $mahasiswa = $this->Mahasiswa_model->get_by_id($id);

        if ($mahasiswa && $mahasiswa->foto != '')
        {
            unlink(FCPATH . 'assets/uploads/mahasiswa/' . $mahasiswa->foto);
        }

        $this->Mahasiswa_model->delete($id);

        redirect('mahasiswa');
    }
}