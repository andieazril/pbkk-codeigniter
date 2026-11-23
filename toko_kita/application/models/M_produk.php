<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_produk extends MY_Model
{
    protected $table    = 'tbl_produk';
    protected $pk       = 'id_produk';
    protected $order_by = 'id_produk DESC';

    private function base()
    {
        return $this->db->select('p.*, k.nama_kategori, k.slug AS slug_kategori')
            ->from('tbl_produk p')->join('tbl_kategori k', 'k.id_kategori = p.id_kategori', 'left');
    }

    /** daftar untuk admin */
    public function get_all($filter = array())
    {
        $this->base();
        if (!empty($filter['id_kategori'])) $this->db->where('p.id_kategori', $filter['id_kategori']);
        if (!empty($filter['stok']) && $filter['stok'] === 'habis') $this->db->where('p.stok', 0);
        if (!empty($filter['stok']) && $filter['stok'] === 'menipis') $this->db->where('p.stok >', 0)->where('p.stok <=', 5);
        return $this->db->order_by('p.id_produk', 'DESC')->get()->result();
    }

    /** filter katalog toko */
    private function katalog_filter($f)
    {
        $this->db->where('p.is_aktif', 'y');
        if (!empty($f['kategori'])) $this->db->where('k.slug', $f['kategori']);
        if (!empty($f['q'])) {
            $this->db->group_start()->like('p.nama_produk', $f['q'])->or_like('p.deskripsi', $f['q'])->group_end();
        }
    }

    public function katalog($f, $limit, $offset)
    {
        $this->base(); $this->katalog_filter($f);
        $sort = isset($f['urut']) ? $f['urut'] : '';
        switch ($sort) {
            case 'murah':   $this->db->order_by('p.harga', 'ASC'); break;
            case 'mahal':   $this->db->order_by('p.harga', 'DESC'); break;
            case 'terlaris':$this->db->order_by('p.terjual', 'DESC'); break;
            default:        $this->db->order_by('p.created_at', 'DESC');
        }
        return $this->db->order_by('p.id_produk', 'DESC')->limit($limit, $offset)->get()->result();
    }

    public function katalog_count($f)
    {
        $this->db->from('tbl_produk p')->join('tbl_kategori k', 'k.id_kategori = p.id_kategori', 'left');
        $this->katalog_filter($f);
        return $this->db->count_all_results();
    }

    public function by_slug($slug)
    {
        return $this->base()->where('p.slug', $slug)->where('p.is_aktif', 'y')->get()->row();
    }

    public function terlaris($n = 4)
    {
        return $this->base()->where('p.is_aktif', 'y')->order_by('p.terjual', 'DESC')->limit($n)->get()->result();
    }

    public function terbaru($n = 8)
    {
        return $this->base()->where('p.is_aktif', 'y')->order_by('p.created_at', 'DESC')->order_by('p.id_produk', 'DESC')->limit($n)->get()->result();
    }

    public function terkait($produk, $n = 4)
    {
        return $this->base()->where('p.is_aktif', 'y')->where('p.id_kategori', $produk->id_kategori)
            ->where('p.id_produk !=', $produk->id_produk)->order_by('p.terjual', 'DESC')->limit($n)->get()->result();
    }

    public function slug_unik($nama, $except_id = NULL)
    {
        $base = slugify($nama); $slug = $base; $i = 2;
        while (TRUE) {
            $this->db->where('slug', $slug);
            if ($except_id) $this->db->where('id_produk !=', $except_id);
            if ($this->db->count_all_results($this->table) == 0) return $slug;
            $slug = $base . '-' . $i++;
        }
    }
}
