<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Keranjang belanja berbasis session.
 * Disimpan sebagai array id_produk => qty, harga & stok selalu dibaca ulang dari database.
 */
class Keranjang_lib
{
    private $CI;
    private $key = 'keranjang';

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    private function raw()
    {
        $k = $this->CI->session->userdata($this->key);
        return is_array($k) ? $k : array();
    }

    private function save($items)
    {
        $this->CI->session->set_userdata($this->key, $items);
    }

    private function produk($id)
    {
        return $this->CI->db->get_where('tbl_produk', array('id_produk' => (int) $id, 'is_aktif' => 'y'))->row();
    }

    /** @return true|string pesan error */
    public function tambah($id, $qty = 1)
    {
        $p = $this->produk($id);
        if (!$p) return 'Produk tidak ditemukan.';
        $qty   = max(1, (int) $qty);
        $items = $this->raw();
        $baru  = (isset($items[$p->id_produk]) ? $items[$p->id_produk] : 0) + $qty;
        if ($p->stok < 1) return 'Stok ' . $p->nama_produk . ' habis.';
        if ($baru > $p->stok) return 'Stok ' . $p->nama_produk . ' tersisa ' . $p->stok . '.';
        $items[$p->id_produk] = $baru;
        $this->save($items);
        return TRUE;
    }

    /** @return true|string */
    public function ubah($id, $qty)
    {
        $items = $this->raw();
        if (!isset($items[(int) $id])) return 'Produk tidak ada di keranjang.';
        $qty = (int) $qty;
        if ($qty < 1) return $this->hapus($id);
        $p = $this->produk($id);
        if (!$p) { $this->hapus($id); return 'Produk sudah tidak tersedia.'; }
        if ($qty > $p->stok) return 'Stok ' . $p->nama_produk . ' tersisa ' . $p->stok . '.';
        $items[(int) $id] = $qty;
        $this->save($items);
        return TRUE;
    }

    public function hapus($id)
    {
        $items = $this->raw();
        unset($items[(int) $id]);
        $this->save($items);
        return TRUE;
    }

    public function kosongkan()
    {
        $this->CI->session->unset_userdata($this->key);
    }

    public function count()
    {
        return array_sum($this->raw());
    }

    /**
     * Isi keranjang lengkap dengan data produk terbaru.
     * Produk yang sudah tidak aktif otomatis dikeluarkan.
     */
    public function items()
    {
        $items = $this->raw();
        if (!$items) return array();
        $rows = $this->CI->db->where_in('id_produk', array_keys($items))->where('is_aktif', 'y')->get('tbl_produk')->result();
        $out = array();
        foreach ($rows as $p) {
            $p->qty      = $items[$p->id_produk];
            $p->subtotal = $p->qty * $p->harga;
            $p->berat_total = $p->qty * $p->berat;
            $p->stok_kurang = $p->qty > $p->stok;
            $out[] = $p;
        }
        if (count($out) !== count($items)) {
            $valid = array();
            foreach ($out as $p) $valid[$p->id_produk] = $p->qty;
            $this->save($valid);
        }
        return $out;
    }

    public function ringkasan($items = NULL)
    {
        $items = $items === NULL ? $this->items() : $items;
        $r = array('subtotal' => 0, 'berat' => 0, 'qty' => 0, 'stok_kurang' => FALSE);
        foreach ($items as $i) {
            $r['subtotal'] += $i->subtotal;
            $r['berat']    += $i->berat_total;
            $r['qty']      += $i->qty;
            $r['stok_kurang'] = $r['stok_kurang'] || $i->stok_kurang;
        }
        return $r;
    }
}
