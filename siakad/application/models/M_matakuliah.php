<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_matakuliah extends MY_Model
{
    protected $table    = 'tbl_matakuliah';
    protected $pk       = 'kd_mk';
    protected $order_by = 'kd_prodi, semester, kd_mk';

    public function get_all()
    {
        return $this->db->select('m.*, p.nama_prodi')
            ->from('tbl_matakuliah m')
            ->join('tbl_prodi p', 'p.kd_prodi = m.kd_prodi', 'left')
            ->order_by('m.kd_prodi, m.semester, m.kd_mk')->get()->result();
    }
}
