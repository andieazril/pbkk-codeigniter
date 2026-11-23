<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Toko extends Admin_Controller
{
    private $fields = array(
        'nama_toko' => 'Nama Toko', 'slogan' => 'Slogan', 'alamat' => 'Alamat', 'kota' => 'Kota Asal Pengiriman',
        'telpon' => 'Telepon', 'whatsapp' => 'WhatsApp (format 62xxx)', 'email' => 'Email', 'deskripsi' => 'Deskripsi Toko',
    );

    public function index()
    {
        $data['title']  = 'Info Toko';
        $data['row']    = $this->db->get_where('tbl_toko', array('id_toko' => 1))->row();
        $data['fields'] = $this->fields;
        $this->render('admin/toko', $data);
    }

    public function save()
    {
        $this->only_post();
        $this->form_validation->set_rules('nama_toko', 'Nama Toko', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('whatsapp', 'WhatsApp', 'trim|numeric');
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors(' ', '<br>'));
            redirect('admin/toko');
        }
        $d = array();
        foreach (array_keys($this->fields) as $f) $d[$f] = $this->input->post($f, TRUE);
        if ($this->db->where('id_toko', 1)->count_all_results('tbl_toko')) {
            $this->db->where('id_toko', 1)->update('tbl_toko', $d);
        } else {
            $this->db->insert('tbl_toko', $d + array('id_toko' => 1));
        }
        $this->session->set_flashdata('success', 'Info toko berhasil disimpan.');
        redirect('admin/toko');
    }
}
