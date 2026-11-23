<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_tahunakademik extends MY_Model
{
    protected $table    = 'tbl_tahun_akademik';
    protected $pk       = 'id_tahun_akademik';
    protected $order_by = 'tahun_akademik DESC, semester DESC';

    /** Ekspresi SQL untuk mengurutkan tahun akademik secara kronologis */
    public static function urutan_sql($alias = 't')
    {
        return "CONCAT($alias.tahun_akademik, IF($alias.semester = 'Ganjil', '1', '2'))";
    }

    /** Hanya boleh ada satu tahun akademik aktif */
    public function set_aktif($id)
    {
        $this->db->trans_start();
        $this->db->update($this->table, array('is_aktif' => 'n'));
        $this->db->where($this->pk, $id)->update($this->table, array('is_aktif' => 'y'));
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function dropdown()
    {
        $out = array();
        foreach ($this->get_all() as $r) {
            $out[$r->id_tahun_akademik] = $r->tahun_akademik . ' ' . $r->semester . ($r->is_aktif === 'y' ? ' (Aktif)' : '');
        }
        return $out;
    }
}
