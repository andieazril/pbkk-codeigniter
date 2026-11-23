<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pelanggan extends MY_Model
{
    protected $table    = 'tbl_pelanggan';
    protected $pk       = 'id_pelanggan';
    protected $order_by = 'id_pelanggan DESC';

    public function get_all()
    {
        return $this->db->select("pl.*, o.kota, (SELECT COUNT(*) FROM tbl_pesanan ps WHERE ps.id_pelanggan = pl.id_pelanggan) AS jml_pesanan,
                (SELECT COALESCE(SUM(ps.total),0) FROM tbl_pesanan ps WHERE ps.id_pelanggan = pl.id_pelanggan AND ps.status IN ('Diproses','Dikirim','Selesai')) AS total_belanja", FALSE)
            ->from('tbl_pelanggan pl')->join('tbl_ongkir o', 'o.id_ongkir = pl.id_ongkir', 'left')
            ->order_by('pl.id_pelanggan', 'DESC')->get()->result();
    }

    public function get_by_email($email)
    {
        return $this->db->get_where($this->table, array('email' => strtolower(trim($email))))->row();
    }

    public function email_exists($email, $except_id = NULL)
    {
        $this->db->where('email', strtolower(trim($email)));
        if ($except_id) $this->db->where('id_pelanggan !=', $except_id);
        return $this->db->count_all_results($this->table) > 0;
    }
}
