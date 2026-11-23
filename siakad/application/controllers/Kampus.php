<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kampus extends MY_Controller
{
    private $fields = array(
        'nama_kampus'      => 'Nama Perguruan Tinggi',
        'kode_pt'          => 'Kode PT',
        'alamat'           => 'Alamat',
        'telpon'           => 'Telepon',
        'email'            => 'Email',
        'website'          => 'Website',
        'pimpinan'         => 'Nama Pimpinan',
        'jabatan_pimpinan' => 'Jabatan Pimpinan (Rektor/Ketua/Direktur)',
        'nip_pimpinan'     => 'NIP Pimpinan',
    );

    public function index()
    {
        $data['title']  = 'Info Kampus';
        $data['row']    = $this->db->get_where('tbl_kampus_info', array('id_kampus' => 1))->row();
        $data['fields'] = $this->fields;
        $this->render('kampus/index', $data);
    }

    public function save()
    {
        $this->only_post();
        $this->form_validation->set_rules('nama_kampus', 'Nama Perguruan Tinggi', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors(' ', '<br>'));
            redirect('kampus');
        }
        $d = array();
        foreach (array_keys($this->fields) as $f) {
            $d[$f] = $this->input->post($f, TRUE);
        }
        if ($this->db->where('id_kampus', 1)->count_all_results('tbl_kampus_info')) {
            $this->db->where('id_kampus', 1)->update('tbl_kampus_info', $d);
        } else {
            $this->db->insert('tbl_kampus_info', $d + array('id_kampus' => 1));
        }
        $this->session->set_flashdata('success', 'Info kampus berhasil disimpan.');
        redirect('kampus');
    }
}
