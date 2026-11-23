<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kategori extends MY_Model
{
    protected $table    = 'tbl_kategori';
    protected $pk       = 'id_kategori';
    protected $order_by = 'id_kategori ASC';

    public function get_all()
    {
        return $this->db->select('k.*, (SELECT COUNT(*) FROM tbl_produk p WHERE p.id_kategori = k.id_kategori) AS jml_produk', FALSE)
            ->from('tbl_kategori k')->order_by('k.id_kategori')->get()->result();
    }

    public function dropdown()
    {
        $out = array();
        foreach ($this->db->order_by('nama_kategori')->get($this->table)->result() as $r) {
            $out[$r->id_kategori] = $r->nama_kategori;
        }
        return $out;
    }
}
