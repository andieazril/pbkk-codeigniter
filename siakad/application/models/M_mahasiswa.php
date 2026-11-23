<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_mahasiswa extends MY_Model
{
    protected $table    = 'tbl_mahasiswa';
    protected $pk       = 'nim';
    protected $order_by = 'nim ASC';

    private function base()
    {
        return $this->db->select('m.*, p.nama_prodi, p.jenjang, p.fakultas, p.ketua_prodi, p.nidn_kaprodi, d.nama_dosen AS dosen_wali, d.nidn AS nidn_wali')
            ->from('tbl_mahasiswa m')
            ->join('tbl_prodi p', 'p.kd_prodi = m.kd_prodi', 'left')
            ->join('tbl_dosen d', 'd.id_dosen = m.id_dosen_wali', 'left');
    }

    public function get_all($filter = array())
    {
        $this->base();
        foreach (array('kd_prodi', 'angkatan', 'status', 'id_dosen_wali') as $f) {
            if (!empty($filter[$f])) $this->db->where('m.' . $f, $filter[$f]);
        }
        return $this->db->order_by('m.nim')->get()->result();
    }

    public function get_detail($nim)
    {
        return $this->base()->where('m.nim', $nim)->get()->row();
    }

    public function angkatan_list()
    {
        $out = array();
        foreach ($this->db->distinct()->select('angkatan')->order_by('angkatan', 'DESC')->get($this->table)->result() as $r) {
            $out[$r->angkatan] = $r->angkatan;
        }
        return $out;
    }

    /** semester ke-n mahasiswa pada tahun akademik tertentu */
    public function semester_ke($angkatan, $ta)
    {
        if (!$ta) return '-';
        $ta = (array) $ta;
        $tahun_awal = (int) substr($ta['tahun_akademik'], 0, 4);
        return max(1, ($tahun_awal - (int) $angkatan) * 2 + ($ta['semester'] === 'Genap' ? 2 : 1));
    }
}
