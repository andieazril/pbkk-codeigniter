<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_dosen extends MY_Model
{
    protected $table    = 'tbl_dosen';
    protected $pk       = 'id_dosen';
    protected $order_by = 'nama_dosen ASC';

    public function get_all()
    {
        return $this->db->select('d.*, p.nama_prodi')
            ->from('tbl_dosen d')
            ->join('tbl_prodi p', 'p.kd_prodi = d.kd_prodi', 'left')
            ->order_by('d.nama_dosen')->get()->result();
    }

    public function dropdown()
    {
        $out = array();
        foreach ($this->db->order_by('nama_dosen')->get($this->table)->result() as $r) {
            $out[$r->id_dosen] = $r->nama_dosen;
        }
        return $out;
    }
}
