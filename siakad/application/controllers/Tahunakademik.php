<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tahunakademik extends Crud_Controller
{
    protected $model  = 'M_tahunakademik';
    protected $pk     = 'id_tahun_akademik';
    protected $title  = 'Tahun Akademik';
    protected $fields = array(
        'tahun_akademik' => array('label' => 'Tahun Akademik (cth: 2025/2026)', 'rules' => 'required|regex_match[/^\d{4}\/\d{4}$/]'),
        'semester'       => array('label' => 'Semester', 'type' => 'select', 'rules' => 'required|in_list[Ganjil,Genap]',
                                  'options' => array('Ganjil' => 'Ganjil', 'Genap' => 'Genap')),
        'is_aktif'       => array('label' => 'Status', 'type' => 'select', 'rules' => 'required|in_list[y,n]',
                                  'options' => array('y' => 'Aktif', 'n' => 'Tidak Aktif'), 'default' => 'n'),
    );
    protected $used_in = array('tbl_jadwal' => 'id_tahun_akademik');

    public function _row_actions($row)
    {
        if ($row->is_aktif === 'y') {
            return '<span class="label label-success">Aktif</span>';
        }
        return btn_post('tahunakademik/aktifkan/' . $row->id_tahun_akademik, '<i class="fa fa-check"></i> Aktifkan', 'btn-success', 'Aktifkan tahun akademik ini?');
    }

    /** jika diset aktif, tahun akademik lain otomatis dinonaktifkan */
    protected function after_save($id, $data)
    {
        if ($data['is_aktif'] === 'y') {
            $this->M_tahunakademik->set_aktif($id);
        }
    }

    public function aktifkan($id = NULL)
    {
        $this->only_post();
        $this->M_tahunakademik->set_aktif((int) $id);
        $this->session->set_flashdata('success', 'Tahun akademik berhasil diaktifkan.');
        redirect('tahunakademik');
    }
}
