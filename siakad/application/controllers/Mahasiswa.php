<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_mahasiswa', 'M_prodi', 'M_dosen', 'M_krs'));
    }

    public function index()
    {
        $filter = array();
        foreach (array('kd_prodi', 'angkatan', 'status', 'id_dosen_wali') as $f) {
            $filter[$f] = $this->input->get($f, TRUE);
        }
        $data['title']    = 'Data Mahasiswa';
        $data['filter']   = $filter;
        $data['rows']     = $this->M_mahasiswa->get_all($filter);
        $data['prodi']    = $this->M_prodi->dropdown();
        $data['angkatan'] = $this->M_mahasiswa->angkatan_list();
        $data['dosen']    = $this->M_dosen->dropdown();
        $this->render('mahasiswa/index', $data);
    }

    public function detail($nim = NULL)
    {
        $m = $this->M_mahasiswa->get_detail($nim);
        if (!$m) show_404();
        $transkrip = $this->M_krs->transkrip($nim);
        $data['title']    = 'Detail Mahasiswa';
        $data['mhs']      = $m;
        $data['ipk']      = hitung_ip($transkrip);
        $data['riwayat']  = array();
        foreach ($this->M_krs->ta_mahasiswa($nim) as $id_ta => $label) {
            $krs = $this->M_krs->get_krs($nim, $id_ta);
            $ip  = hitung_ip($krs);
            $data['riwayat'][] = (object) array('id_ta' => $id_ta, 'label' => $label, 'sks' => array_sum(array_map(function ($k) { return $k->sks; }, $krs)), 'ips' => $ip['ip']);
        }
        $this->render('mahasiswa/detail', $data);
    }

    public function add()
    {
        $data['title'] = 'Tambah Mahasiswa';
        $data['row']   = NULL;
        $data['prodi'] = $this->M_prodi->dropdown();
        $data['dosen'] = $this->M_dosen->dropdown();
        $this->render('mahasiswa/form', $data);
    }

    public function edit($nim = NULL)
    {
        $data['row'] = $this->M_mahasiswa->get_by_id($nim);
        if (!$data['row']) show_404();
        $data['title'] = 'Edit Mahasiswa';
        $data['prodi'] = $this->M_prodi->dropdown();
        $data['dosen'] = $this->M_dosen->dropdown();
        $this->render('mahasiswa/form', $data);
    }

    public function save()
    {
        $this->only_post();
        $old  = $this->input->post('_id', TRUE);
        $back = $old ? 'mahasiswa/edit/' . $old : 'mahasiswa/add';

        $this->form_validation->set_rules('nim', 'NIM', 'required|trim|alpha_numeric|max_length[20]');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('gender', 'Jenis Kelamin', 'required|in_list[L,P]');
        $this->form_validation->set_rules('kd_prodi', 'Program Studi', 'required');
        $this->form_validation->set_rules('angkatan', 'Angkatan', 'required|integer|exact_length[4]');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[Aktif,Cuti,Lulus,Keluar]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors(' ', '<br>'));
            redirect($back);
        }

        $fields = array('nim', 'nama', 'gender', 'tempat_lahir', 'tanggal_lahir', 'agama', 'alamat', 'telpon', 'email',
            'nama_ortu', 'kd_prodi', 'angkatan', 'id_dosen_wali', 'status');
        $data = array();
        foreach ($fields as $f) {
            $v = $this->input->post($f, TRUE);
            $data[$f] = ($v === '' && in_array($f, array('tanggal_lahir', 'id_dosen_wali'))) ? NULL : $v;
        }
        if ($old && $old !== $data['nim']) {
            $this->session->set_flashdata('error', 'NIM tidak dapat diubah.');
            redirect($back);
        }
        if (!$old && $this->M_mahasiswa->exists($data['nim'])) {
            $this->session->set_flashdata('error', 'NIM ' . html_escape($data['nim']) . ' sudah terdaftar.');
            redirect($back);
        }

        if (!empty($_FILES['foto']['name'])) {
            $this->load->library('upload', array(
                'upload_path'   => FCPATH . 'uploads/foto/',
                'allowed_types' => 'jpg|jpeg|png',
                'max_size'      => 1024,
                'file_name'     => 'mhs_' . $data['nim'],
                'overwrite'     => TRUE,
            ));
            if (!$this->upload->do_upload('foto')) {
                $this->session->set_flashdata('error', $this->upload->display_errors(' ', ' '));
                redirect($back);
            }
            $data['foto'] = $this->upload->data('file_name');
        }

        if ($old) {
            $this->M_mahasiswa->update($old, $data);
            $this->session->set_flashdata('success', 'Data mahasiswa berhasil diperbarui.');
        } else {
            $this->db->trans_start();
            $this->M_mahasiswa->insert($data);
            // akun login: username = NIM, password = NIM
            if ($this->input->post('buat_akun') && !$this->db->where('username', $data['nim'])->count_all_results('tbl_user')) {
                $this->db->insert('tbl_user', array(
                    'nama_lengkap'  => $data['nama'],
                    'username'      => $data['nim'],
                    'password'      => password_hash($data['nim'], PASSWORD_DEFAULT),
                    'id_level_user' => 3,
                    'nim'           => $data['nim'],
                ));
            }
            $this->db->trans_complete();
            $this->session->set_flashdata('success', 'Data mahasiswa berhasil ditambahkan.');
        }
        redirect('mahasiswa');
    }

    public function delete($nim = NULL)
    {
        $this->only_post();
        if ($this->db->where('nim', $nim)->count_all_results('tbl_krs') > 0) {
            $this->session->set_flashdata('error', 'Mahasiswa tidak dapat dihapus karena sudah memiliki KRS/nilai. Ubah statusnya menjadi Keluar.');
            redirect('mahasiswa');
        }
        $m = $this->M_mahasiswa->get_by_id($nim);
        $this->db->trans_start();
        $this->db->where('nim', $nim)->delete('tbl_user');
        $this->M_mahasiswa->delete($nim);
        $this->db->trans_complete();
        if ($m && $m->foto && is_file(FCPATH . 'uploads/foto/' . $m->foto)) {
            @unlink(FCPATH . 'uploads/foto/' . $m->foto);
        }
        $this->session->set_flashdata('success', 'Data mahasiswa berhasil dihapus.');
        redirect('mahasiswa');
    }
}
