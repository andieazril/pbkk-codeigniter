<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Perwalian: dosen wali memeriksa & menyetujui KRS mahasiswa bimbingannya.
 * Admin dapat melihat & menyetujui semua.
 */
class Perwalian extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_krs', 'M_mahasiswa', 'M_dosen'));
    }

    public function index()
    {
        $ta = tahun_akademik_aktif();
        $filter = array('status' => 'Aktif');
        if ($this->is_dosen()) {
            $filter['id_dosen_wali'] = $this->my_id_dosen() ?: -1;
        } elseif ($this->input->get('id_dosen_wali')) {
            $filter['id_dosen_wali'] = (int) $this->input->get('id_dosen_wali');
        }
        $rows = $this->M_mahasiswa->get_all($filter);
        $ringkas = array('Diajukan' => 0, 'Disetujui' => 0, 'Kosong' => 0);
        foreach ($rows as $r) {
            $r->krs_status = $ta ? $this->M_krs->status($r->nim, $ta['id_tahun_akademik']) : NULL;
            $r->krs_sks    = $ta ? $this->M_krs->total_sks($r->nim, $ta['id_tahun_akademik']) : 0;
            $ringkas[$r->krs_status ?: 'Kosong']++;
        }
        $data['title']     = 'Perwalian';
        $data['subtitle']  = 'Persetujuan KRS ' . ($ta ? label_ta($ta) : '');
        $data['rows']      = $rows;
        $data['ringkas']   = $ringkas;
        $data['dosen_opt'] = $this->is_admin() ? $this->M_dosen->dropdown() : array();
        $data['id_dosen_wali'] = isset($filter['id_dosen_wali']) ? $filter['id_dosen_wali'] : '';
        $this->render('perwalian/index', $data);
    }

    public function detail($nim = NULL)
    {
        $ta = tahun_akademik_aktif();
        if (!$ta || !$this->boleh_akses_nim($nim)) show_error('Akses ditolak.', 403, 'Akses Ditolak');
        $mhs = $this->M_mahasiswa->get_detail($nim);
        if (!$mhs) show_404();
        $ipk = hitung_ip($this->M_krs->transkrip($nim));
        $ips = $this->M_krs->ips_sebelumnya($nim, $ta['id_tahun_akademik']);
        $data['title']    = 'Detail Perwalian';
        $data['subtitle'] = $mhs->nim . ' - ' . $mhs->nama;
        $data['mhs']      = $mhs;
        $data['ta']       = $ta;
        $data['krs']      = $this->M_krs->get_krs($nim, $ta['id_tahun_akademik']);
        $data['status']   = $this->M_krs->status($nim, $ta['id_tahun_akademik']);
        $data['ipk']      = $ipk;
        $data['ips_lalu'] = $ips;
        $data['maks_sks'] = maks_sks($ips);
        $this->render('perwalian/detail', $data);
    }

    private function ubah_status($nim, $status, $pesan)
    {
        $this->only_post();
        $ta = tahun_akademik_aktif();
        if (!$ta || !$this->boleh_akses_nim($nim)) show_error('Akses ditolak.', 403, 'Akses Ditolak');
        $this->M_krs->set_status($nim, $ta['id_tahun_akademik'], $status);
        $this->session->set_flashdata('success', $pesan);
        redirect('perwalian/detail/' . rawurlencode($nim));
    }

    public function setujui($nim = NULL)
    {
        $this->ubah_status($nim, 'Disetujui', 'KRS berhasil disetujui.');
    }

    public function batalkan($nim = NULL)
    {
        $this->ubah_status($nim, 'Diajukan', 'Persetujuan KRS dibatalkan. Mahasiswa dapat merevisi KRS.');
    }
}
