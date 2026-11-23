<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper Toko Online
 */

/**
 * Helper umum SIAKAD
 */

if (!function_exists('ci')) {
    function ci()
    {
        return get_instance();
    }
}

/**
 * Gabungkan class bawaan dengan class pada atribut tambahan
 */
if (!function_exists('_merge_class')) {
    function _merge_class($base, $extra)
    {
        if (preg_match('/class="([^"]*)"/', $extra, $m)) {
            $extra = str_replace($m[0], '', $extra);
            $base .= ' ' . $m[1];
        }
        return 'class="' . trim(implode(' ', array_unique(explode(' ', $base)))) . '" ' . $extra;
    }
}

/**
 * Membuat combobox (select) dinamis dari tabel database
 *
 * cmb_dinamis('kd_jurusan', 'tbl_jurusan', 'nama_jurusan', 'kd_jurusan', $selected)
 */
if (!function_exists('cmb_dinamis')) {
    function cmb_dinamis($name, $table, $field_label, $field_value, $selected = NULL, $extra = '', $where = NULL, $order = NULL, $placeholder = '-- Pilih --')
    {
        $db = ci()->db;
        if ($where) {
            $db->where($where);
        }
        $db->order_by($order ? $order : $field_label, 'ASC');
        $rows = $db->get($table)->result_array();

        $html  = '<select name="' . $name . '" id="' . $name . '" ' . _merge_class('form-control select2', $extra) . '>';
        $html .= '<option value="">' . html_escape($placeholder) . '</option>';
        foreach ($rows as $r) {
            $sel   = ((string) $selected === (string) $r[$field_value]) ? ' selected' : '';
            $html .= '<option value="' . html_escape($r[$field_value]) . '"' . $sel . '>' . html_escape($r[$field_label]) . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
}

/**
 * Combobox dari array statis
 */
if (!function_exists('cmb_array')) {
    function cmb_array($name, $options, $selected = NULL, $extra = '', $placeholder = '-- Pilih --')
    {
        $html  = '<select name="' . $name . '" id="' . $name . '" ' . _merge_class('form-control', $extra) . '>';
        $html .= '<option value="">' . html_escape($placeholder) . '</option>';
        foreach ($options as $value => $label) {
            $sel   = ((string) $selected === (string) $value) ? ' selected' : '';
            $html .= '<option value="' . html_escape($value) . '"' . $sel . '>' . html_escape($label) . '</option>';
        }
        return $html . '</select>';
    }
}

if (!function_exists('tgl_indo')) {
    function tgl_indo($tanggal)
    {
        if (empty($tanggal) || $tanggal === '0000-00-00') {
            return '-';
        }
        $bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
            'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $p = explode('-', substr($tanggal, 0, 10));
        return (int) $p[2] . ' ' . $bulan[(int) $p[1]] . ' ' . $p[0];
    }
}

/**
 * Menampilkan pesan flashdata
 */
if (!function_exists('show_alert')) {
    function show_alert()
    {
        $s    = ci()->session;
        $html = '';
        foreach (array('success' => 'check', 'error' => 'ban', 'warning' => 'warning') as $type => $icon) {
            if ($msg = $s->flashdata($type)) {
                $cls   = $type === 'error' ? 'danger' : $type;
                $html .= '<div class="alert alert-' . $cls . ' alert-dismissible">'
                    . '<button type="button" class="close" data-dismiss="alert">&times;</button>'
                    . '<i class="icon fa fa-' . $icon . '"></i> ' . $msg . '</div>';
            }
        }
        return $html;
    }
}

/**
 * Tombol aksi edit & hapus (hapus lewat form POST agar aman dari CSRF)
 */
if (!function_exists('btn_aksi')) {
    function btn_aksi($module, $id)
    {
        $ci = ci();
        return '<a href="' . site_url($module . '/edit/' . rawurlencode($id)) . '" class="btn btn-xs btn-warning" title="Edit"><i class="fa fa-pencil"></i></a> '
            . btn_hapus($module . '/delete/' . rawurlencode($id));
    }
}

if (!function_exists('btn_post')) {
    /** tombol kecil yang mengirim POST (dengan token CSRF) */
    function btn_post($uri, $label, $class = 'btn-default', $confirm = NULL)
    {
        $ci = ci();
        $on = $confirm ? ' onsubmit="return confirm(\'' . html_escape($confirm) . '\')"' : '';
        return '<form action="' . site_url($uri) . '" method="post" style="display:inline"' . $on . '>'
            . '<input type="hidden" name="' . $ci->security->get_csrf_token_name() . '" value="' . $ci->security->get_csrf_hash() . '">'
            . '<button type="submit" class="btn btn-xs ' . $class . '">' . $label . '</button></form>';
    }
}

if (!function_exists('btn_hapus')) {
    function btn_hapus($uri, $label = '<i class="fa fa-trash"></i>')
    {
        return btn_post($uri, $label, 'btn-danger', 'Yakin ingin menghapus data ini?');
    }
}

/**
 * Inisial nama untuk avatar, cth: "Budi Santoso" -> "BS"
 */
if (!function_exists('inisial')) {
    function inisial($nama)
    {
        $parts = preg_split('/\s+/', trim(preg_replace('/[^a-zA-Z\s]/', ' ', (string) $nama)));
        $out = '';
        foreach ($parts as $p) {
            if ($p !== '') $out .= strtoupper($p[0]);
            if (strlen($out) >= 2) break;
        }
        return $out ?: '?';
    }
}

/** Format rupiah: 150000 -> "Rp 150.000" */
if (!function_exists('rupiah')) {
    function rupiah($angka)
    {
        return 'Rp ' . number_format((float) $angka, 0, ',', '.');
    }
}

/** Data pengaturan toko */
if (!function_exists('info_toko')) {
    function info_toko($field = NULL)
    {
        static $row = NULL;
        if ($row === NULL) {
            $row = ci()->db->get_where('tbl_toko', array('id_toko' => 1))->row_array() ?: array();
        }
        if ($field === NULL) return $row;
        return isset($row[$field]) ? $row[$field] : '';
    }
}

/** Ubah teks menjadi slug URL: "Kaos Polos 30s" -> "kaos-polos-30s" */
if (!function_exists('slugify')) {
    function slugify($text)
    {
        $text = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $text), '-'));
        return $text ?: 'produk';
    }
}

/** URL gambar produk (placeholder bila kosong) */
if (!function_exists('gambar_produk')) {
    function gambar_produk($file)
    {
        if ($file && is_file(FCPATH . 'uploads/produk/' . $file)) {
            return base_url('uploads/produk/' . $file);
        }
        return base_url('assets/img/no-image.png');
    }
}

/**
 * Ongkos kirim: tarif per kg x berat (dibulatkan ke atas per kg, minimal 1 kg)
 */
if (!function_exists('hitung_ongkir')) {
    function hitung_ongkir($berat_gram, $tarif_per_kg)
    {
        $kg = max(1, (int) ceil($berat_gram / 1000));
        return $kg * (int) $tarif_per_kg;
    }
}

/** Daftar status pesanan beserta warna label */
if (!function_exists('status_pesanan')) {
    function status_pesanan()
    {
        return array(
            'Menunggu Pembayaran' => 'warning',
            'Menunggu Verifikasi' => 'info',
            'Diproses'            => 'primary',
            'Dikirim'             => 'primary',
            'Selesai'             => 'success',
            'Dibatalkan'          => 'danger',
        );
    }
}

if (!function_exists('label_status')) {
    function label_status($status)
    {
        $map = status_pesanan() + array('Menunggu' => 'warning', 'Diterima' => 'success', 'Ditolak' => 'danger');
        $cls = isset($map[$status]) ? $map[$status] : 'default';
        return '<span class="label label-' . $cls . '">' . html_escape($status) . '</span>';
    }
}

/** Persentase diskon dari harga coret */
if (!function_exists('diskon_persen')) {
    function diskon_persen($harga, $harga_coret)
    {
        if (!$harga_coret || $harga_coret <= $harga) return 0;
        return (int) round(($harga_coret - $harga) / $harga_coret * 100);
    }
}

/** Format tanggal + jam: 2026-11-02 10:15:00 -> "2 November 2026, 10:15" */
if (!function_exists('tgl_jam')) {
    function tgl_jam($datetime)
    {
        if (!$datetime) return '-';
        return tgl_indo(substr($datetime, 0, 10)) . ', ' . substr($datetime, 11, 5);
    }
}
