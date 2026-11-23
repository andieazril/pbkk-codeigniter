<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Kartu Rencana Studi
 *  - Mahasiswa: mengisi KRS miliknya sendiri pada tahun akademik aktif
 *  - Admin    : dapat mengelola KRS mahasiswa mana pun
 */
class Krs extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_krs', 'M_mahasiswa', 'M_tahunakademik'));
    }

    private function nim_dipilih()
    {
        return $this->is_mhs() ? $this->my_nim() : (string) $this->input->get_post('nim', TRUE);
    }

    private function back($nim)
    {
        return 'krs' . ($this->is_mhs() ? '' : '?nim=' . rawurlencode($nim));
    }

    public function index()
    {
        $nim = $this->nim_dipilih();
        $ta  = tahun_akademik_aktif();
        $data['title']    = 'Kartu Rencana Studi';
        $data['subtitle'] = $ta ? 'Semester ' . label_ta($ta) : 'Tahun akademik aktif belum diatur';
        $data['ta']       = $ta;
        $data['opsi_mhs'] = $this->is_mhs() ? array() : $this->opsi_mahasiswa();
        $data['nim']      = $nim;
        $data['mhs']      = NULL;

        if ($nim && $ta && $this->boleh_akses_nim($nim)) {
            $mhs = $this->M_mahasiswa->get_detail($nim);
            if ($mhs) {
                $id_ta = $ta['id_tahun_akademik'];
                $ips   = $this->M_krs->ips_sebelumnya($nim, $id_ta);
                $data['mhs']       = $mhs;
                $data['smt']       = $this->M_mahasiswa->semester_ke($mhs->angkatan, $ta);
                $data['krs']       = $this->M_krs->get_krs($nim, $id_ta);
                $data['total_sks'] = $this->M_krs->total_sks($nim, $id_ta);
                $data['ips_lalu']  = $ips;
                $data['maks_sks']  = maks_sks($ips);
                $data['status']    = $this->M_krs->status($nim, $id_ta);
                $data['tersedia']  = $this->M_krs->jadwal_tersedia($mhs, $id_ta);
                $data['terkunci']  = $this->terkunci($mhs, $data['status']);
            }
        }
        $this->render('krs/index', $data);
    }

    /** mahasiswa tidak bisa mengubah KRS yang sudah disetujui / bila tidak aktif */
    private function terkunci($mhs, $status)
    {
        if ($mhs->status !== 'Aktif') return 'Status mahasiswa ' . $mhs->status . ', tidak dapat mengisi KRS.';
        if ($this->is_mhs() && $status === 'Disetujui') return 'KRS sudah disetujui dosen wali. Hubungi dosen wali untuk perubahan.';
        return FALSE;
    }

    private function cek_mhs($nim)
    {
        $ta = tahun_akademik_aktif();
        if (!$ta) show_error('Tahun akademik aktif belum diatur.', 400);
        if (!$nim || !$this->boleh_akses_nim($nim)) show_error('Akses ditolak.', 403, 'Akses Ditolak');
        $mhs = $this->M_mahasiswa->get_detail($nim);
        if (!$mhs) show_404();
        if ($msg = $this->terkunci($mhs, $this->M_krs->status($nim, $ta['id_tahun_akademik']))) {
            $this->session->set_flashdata('warning', $msg);
            redirect($this->back($nim));
        }
        return array($mhs, $ta);
    }

    public function ambil()
    {
        $this->only_post();
        list($mhs, $ta) = $this->cek_mhs($this->nim_dipilih());
        $ok = $this->M_krs->ambil($mhs, $this->input->post('id_jadwal'), $ta['id_tahun_akademik']);
        if ($ok === TRUE) {
            $this->session->set_flashdata('success', 'Mata kuliah berhasil ditambahkan ke KRS.');
        } else {
            $this->session->set_flashdata('error', $ok);
        }
        redirect($this->back($mhs->nim));
    }

    public function hapus($id_krs = NULL)
    {
        $this->only_post();
        list($mhs, $ta) = $this->cek_mhs($this->nim_dipilih());
        $row = $this->db->select('k.*, jd.id_tahun_akademik')->from('tbl_krs k')
            ->join('tbl_jadwal jd', 'jd.id_jadwal = k.id_jadwal')
            ->where('k.id_krs', (int) $id_krs)->where('k.nim', $mhs->nim)->get()->row();
        if (!$row || (int) $row->id_tahun_akademik !== (int) $ta['id_tahun_akademik']) {
            $this->session->set_flashdata('error', 'Data KRS tidak ditemukan.');
        } elseif ($row->nilai_huruf !== NULL) {
            $this->session->set_flashdata('error', 'Mata kuliah yang sudah dinilai tidak dapat dihapus.');
        } else {
            $this->M_krs->delete($row->id_krs);
            $this->M_krs->set_status($mhs->nim, $ta['id_tahun_akademik'], 'Diajukan');
            $this->session->set_flashdata('success', 'Mata kuliah dihapus dari KRS.');
        }
        redirect($this->back($mhs->nim));
    }

    public function cetak()
    {
        $nim = $this->nim_dipilih();
        $ta  = tahun_akademik_aktif();
        if (!$nim || !$ta || !$this->boleh_akses_nim($nim)) show_error('Akses ditolak.', 403, 'Akses Ditolak');
        $mhs = $this->M_mahasiswa->get_detail($nim);
        if (!$mhs) show_404();
        $this->load->view('krs/cetak', array(
            'mhs'    => $mhs,
            'ta'     => $ta,
            'smt'    => $this->M_mahasiswa->semester_ke($mhs->angkatan, $ta),
            'krs'    => $this->M_krs->get_krs($nim, $ta['id_tahun_akademik']),
            'status' => $this->M_krs->status($nim, $ta['id_tahun_akademik']),
        ));
    }
}
