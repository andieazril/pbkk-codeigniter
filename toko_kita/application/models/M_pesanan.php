<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pesanan extends MY_Model
{
    protected $table    = 'tbl_pesanan';
    protected $pk       = 'id_pesanan';
    protected $order_by = 'id_pesanan DESC';

    /** status yang dihitung sebagai penjualan sah */
    const STATUS_TERJUAL = array('Diproses', 'Dikirim', 'Selesai');

    /**
     * Buat pesanan dari isi keranjang (dalam transaksi).
     * Stok dicek ulang & dikurangi di sini agar tidak terjadi overselling.
     * @return array('ok' => bool, 'pesan' => string, 'kode' => string)
     */
    public function buat($id_pelanggan, $form, $ongkir_row)
    {
        $CI =& get_instance();
        $items = $CI->keranjang->items();
        if (!$items) return array('ok' => FALSE, 'pesan' => 'Keranjang belanja kosong.');

        $this->db->trans_begin();
        $subtotal = 0; $berat = 0; $detail = array();
        foreach ($items as $i) {
            // kunci baris produk agar stok tidak diperebutkan pesanan lain
            $p = $this->db->query('SELECT * FROM tbl_produk WHERE id_produk = ? FOR UPDATE', array($i->id_produk))->row();
            if (!$p || $p->is_aktif !== 'y' || $p->stok < $i->qty) {
                $this->db->trans_rollback();
                return array('ok' => FALSE, 'pesan' => 'Stok ' . ($p ? $p->nama_produk : 'produk') . ' tidak mencukupi (tersisa ' . ($p ? $p->stok : 0) . ').');
            }
            $subtotal += $p->harga * $i->qty;
            $berat    += $p->berat * $i->qty;
            $detail[]  = array('id_produk' => $p->id_produk, 'nama_produk' => $p->nama_produk, 'harga' => $p->harga,
                'qty' => $i->qty, 'berat' => $p->berat, 'subtotal' => $p->harga * $i->qty);
        }
        $ongkir = hitung_ongkir($berat, $ongkir_row->tarif_per_kg);

        $this->db->insert('tbl_pesanan', array(
            'kode_pesanan'  => 'TMP-' . uniqid(),
            'id_pelanggan'  => $id_pelanggan,
            'nama_penerima' => $form['nama_penerima'],
            'telpon'        => $form['telpon'],
            'alamat'        => $form['alamat'],
            'kota'          => $ongkir_row->kota,
            'kode_pos'      => $form['kode_pos'],
            'catatan'       => $form['catatan'],
            'total_berat'   => $berat,
            'subtotal'      => $subtotal,
            'ongkir'        => $ongkir,
            'total'         => $subtotal + $ongkir,
            'status'        => 'Menunggu Pembayaran',
        ));
        $id   = $this->db->insert_id();
        $kode = 'INV-' . date('Ymd') . '-' . str_pad($id, 4, '0', STR_PAD_LEFT);
        $this->db->where('id_pesanan', $id)->update('tbl_pesanan', array('kode_pesanan' => $kode));

        foreach ($detail as $d) {
            $this->db->insert('tbl_pesanan_detail', $d + array('id_pesanan' => $id));
            $this->db->set('stok', 'stok - ' . (int) $d['qty'], FALSE)->set('terjual', 'terjual + ' . (int) $d['qty'], FALSE)
                ->where('id_produk', $d['id_produk'])->update('tbl_produk');
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('ok' => FALSE, 'pesan' => 'Gagal menyimpan pesanan, silakan coba lagi.');
        }
        $this->db->trans_commit();
        $CI->keranjang->kosongkan();
        return array('ok' => TRUE, 'kode' => $kode, 'pesan' => 'Pesanan berhasil dibuat.');
    }

    public function by_kode($kode, $id_pelanggan = NULL)
    {
        $this->db->select('ps.*, pl.nama AS nama_pelanggan, pl.email')
            ->from('tbl_pesanan ps')->join('tbl_pelanggan pl', 'pl.id_pelanggan = ps.id_pelanggan', 'left')
            ->where('ps.kode_pesanan', $kode);
        if ($id_pelanggan) $this->db->where('ps.id_pelanggan', $id_pelanggan);
        return $this->db->get()->row();
    }

    public function detail($id_pesanan)
    {
        return $this->db->select('d.*, p.gambar, p.slug')->from('tbl_pesanan_detail d')
            ->join('tbl_produk p', 'p.id_produk = d.id_produk', 'left')
            ->where('d.id_pesanan', $id_pesanan)->get()->result();
    }

    public function pembayaran($id_pesanan)
    {
        return $this->db->select('b.*, r.nama_bank, r.no_rekening')->from('tbl_pembayaran b')
            ->join('tbl_rekening r', 'r.id_rekening = b.id_rekening', 'left')
            ->where('b.id_pesanan', $id_pesanan)->order_by('b.id_pembayaran', 'DESC')->get()->result();
    }

    public function milik_pelanggan($id_pelanggan)
    {
        return $this->db->select('ps.*, (SELECT COUNT(*) FROM tbl_pesanan_detail d WHERE d.id_pesanan = ps.id_pesanan) AS jml_item', FALSE)
            ->from('tbl_pesanan ps')->where('ps.id_pelanggan', $id_pelanggan)->order_by('ps.id_pesanan', 'DESC')->get()->result();
    }

    public function daftar_admin($filter = array())
    {
        $this->db->select('ps.*, pl.nama AS nama_pelanggan')->from('tbl_pesanan ps')
            ->join('tbl_pelanggan pl', 'pl.id_pelanggan = ps.id_pelanggan', 'left');
        if (!empty($filter['status'])) $this->db->where('ps.status', $filter['status']);
        if (!empty($filter['dari']))   $this->db->where('DATE(ps.created_at) >=', $filter['dari']);
        if (!empty($filter['sampai'])) $this->db->where('DATE(ps.created_at) <=', $filter['sampai']);
        return $this->db->order_by('ps.id_pesanan', 'DESC')->get()->result();
    }

    /**
     * Ubah status pesanan. Jika dibatalkan, stok dikembalikan.
     */
    public function set_status($pesanan, $status, $resi = NULL)
    {
        $this->db->trans_start();
        if ($status === 'Dibatalkan' && $pesanan->status !== 'Dibatalkan') {
            foreach ($this->detail($pesanan->id_pesanan) as $d) {
                $this->db->set('stok', 'stok + ' . (int) $d->qty, FALSE)->set('terjual', 'GREATEST(terjual - ' . (int) $d->qty . ', 0)', FALSE)
                    ->where('id_produk', $d->id_produk)->update('tbl_produk');
            }
        }
        $data = array('status' => $status);
        if ($resi !== NULL) $data['no_resi'] = $resi;
        $this->db->where('id_pesanan', $pesanan->id_pesanan)->update('tbl_pesanan', $data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function jumlah_per_status()
    {
        $out = array_fill_keys(array_keys(status_pesanan()), 0);
        foreach ($this->db->select('status, COUNT(*) n')->group_by('status')->get($this->table)->result() as $r) {
            $out[$r->status] = (int) $r->n;
        }
        return $out;
    }

    /** omzet & jumlah pesanan sah dalam rentang tanggal */
    public function ringkasan($dari, $sampai)
    {
        return $this->db->select('COUNT(*) AS jml, COALESCE(SUM(subtotal),0) AS penjualan, COALESCE(SUM(ongkir),0) AS ongkir, COALESCE(SUM(total),0) AS total', FALSE)
            ->where_in('status', self::STATUS_TERJUAL)
            ->where('DATE(created_at) >=', $dari)->where('DATE(created_at) <=', $sampai)
            ->get($this->table)->row();
    }

    /** penjualan harian untuk grafik */
    public function harian($dari, $sampai)
    {
        $rows = $this->db->select('DATE(created_at) AS tgl, COUNT(*) AS jml, SUM(subtotal) AS penjualan', FALSE)
            ->where_in('status', self::STATUS_TERJUAL)
            ->where('DATE(created_at) >=', $dari)->where('DATE(created_at) <=', $sampai)
            ->group_by('DATE(created_at)')->get($this->table)->result();
        $map = array();
        foreach ($rows as $r) $map[$r->tgl] = $r;
        $out = array();
        for ($t = strtotime($dari); $t <= strtotime($sampai); $t += 86400) {
            $d = date('Y-m-d', $t);
            $out[] = (object) array('tgl' => $d, 'jml' => isset($map[$d]) ? (int) $map[$d]->jml : 0, 'penjualan' => isset($map[$d]) ? (int) $map[$d]->penjualan : 0);
        }
        return $out;
    }

    /** produk terlaris dalam rentang tanggal */
    public function produk_terlaris($dari, $sampai, $n = 10)
    {
        return $this->db->select('d.id_produk, d.nama_produk, SUM(d.qty) AS qty, SUM(d.subtotal) AS omzet', FALSE)
            ->from('tbl_pesanan_detail d')->join('tbl_pesanan ps', 'ps.id_pesanan = d.id_pesanan')
            ->where_in('ps.status', self::STATUS_TERJUAL)
            ->where('DATE(ps.created_at) >=', $dari)->where('DATE(ps.created_at) <=', $sampai)
            ->group_by(array('d.id_produk', 'd.nama_produk'))->order_by('qty', 'DESC')->limit($n)->get()->result();
    }
}
