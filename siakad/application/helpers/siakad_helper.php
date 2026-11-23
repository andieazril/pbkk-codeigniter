<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
 * Mengambil data tahun akademik yang sedang aktif
 */
if (!function_exists('tahun_akademik_aktif')) {
    function tahun_akademik_aktif($field = NULL)
    {
        $row = ci()->db->get_where('tbl_tahun_akademik', array('is_aktif' => 'y'))->row_array();
        if ($field === NULL) {
            return $row;
        }
        return isset($row[$field]) ? $row[$field] : NULL;
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
 * Skala nilai huruf perguruan tinggi
 *  A >= 85 (4.00) | AB >= 80 (3.50) | B >= 70 (3.00) | BC >= 65 (2.50)
 *  C >= 55 (2.00) | D >= 45 (1.00)  | E < 45 (0.00)
 */
if (!function_exists('skala_nilai')) {
    function skala_nilai()
    {
        return array(
            array('A',  85, 4.00),
            array('AB', 80, 3.50),
            array('B',  70, 3.00),
            array('BC', 65, 2.50),
            array('C',  55, 2.00),
            array('D',  45, 1.00),
            array('E',   0, 0.00),
        );
    }
}

if (!function_exists('nilai_huruf')) {
    /** @return array(huruf, bobot) */
    function nilai_huruf($nilai)
    {
        foreach (skala_nilai() as $s) {
            if ((float) $nilai >= $s[1]) return array($s[0], $s[2]);
        }
        return array('E', 0.00);
    }
}

/**
 * Rumus nilai akhir: 30% tugas + 30% UTS + 40% UAS
 */
if (!function_exists('hitung_nilai_akhir')) {
    function hitung_nilai_akhir($tugas, $uts, $uas)
    {
        return round(((float) $tugas * 0.3) + ((float) $uts * 0.3) + ((float) $uas * 0.4), 2);
    }
}

/**
 * Hitung IP dari daftar baris yang memiliki ->sks dan ->bobot
 * @return array('sks' => total sks dinilai, 'mutu' => total sks*bobot, 'ip' => indeks prestasi)
 */
if (!function_exists('hitung_ip')) {
    function hitung_ip($rows)
    {
        $sks = 0; $mutu = 0;
        foreach ($rows as $r) {
            if ($r->bobot === NULL || $r->nilai_huruf === NULL) continue;
            $sks  += (int) $r->sks;
            $mutu += (int) $r->sks * (float) $r->bobot;
        }
        return array('sks' => $sks, 'mutu' => $mutu, 'ip' => $sks ? round($mutu / $sks, 2) : 0);
    }
}

/**
 * Batas maksimal SKS berdasarkan IP semester sebelumnya
 */
if (!function_exists('maks_sks')) {
    function maks_sks($ips_sebelumnya)
    {
        if ($ips_sebelumnya === NULL) return 20;   // mahasiswa baru
        if ($ips_sebelumnya >= 3.00) return 24;
        if ($ips_sebelumnya >= 2.50) return 21;
        if ($ips_sebelumnya >= 2.00) return 18;
        return 15;
    }
}

/** label semester, cth: "2025/2026 Ganjil" */
if (!function_exists('label_ta')) {
    function label_ta($ta)
    {
        if (!$ta) return '-';
        $ta = (array) $ta;
        return $ta['tahun_akademik'] . ' ' . $ta['semester'];
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

if (!function_exists('info_kampus')) {
    function info_kampus($field = NULL)
    {
        static $row = NULL;
        if ($row === NULL) {
            $row = ci()->db->get_where('tbl_kampus_info', array('id_kampus' => 1))->row_array();
        }
        if ($field === NULL) return $row;
        return isset($row[$field]) ? $row[$field] : '';
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
