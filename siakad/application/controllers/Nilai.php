<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nilai extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_jadwal', 'M_krs', 'M_tahunakademik'));
    }

    public function index()
    {
        $ta = $this->input->get('id_tahun_akademik', TRUE) ?: tahun_akademik_aktif('id_tahun_akademik');
        $data['title']    = 'Input Nilai';
        $data['subtitle'] = 'Pilih kelas mata kuliah';
        $data['ta']       = $ta;
        $data['ta_opt']   = $this->M_tahunakademik->dropdown();
        $data['rows']     = $ta ? $this->M_jadwal->get_filter($ta, NULL, $this->is_dosen() ? ($this->my_id_dosen() ?: -1) : NULL) : array();
        $this->render('nilai/index', $data);
    }

    private function jadwal_or_fail($id)
    {
        $jadwal = $this->M_jadwal->get_detail((int) $id);
        if (!$jadwal) show_404();
        if ($this->is_dosen() && (int) $jadwal->id_dosen !== $this->my_id_dosen()) {
            show_error('Anda bukan dosen pengampu kelas ini.', 403, 'Akses Ditolak');
        }
        return $jadwal;
    }

    public function input($id_jadwal = NULL)
    {
        $jadwal = $this->jadwal_or_fail($id_jadwal);
        $data['title']    = 'Input Nilai';
        $data['subtitle'] = $jadwal->kd_mk . ' ' . $jadwal->nama_mk . ' - Kelas ' . $jadwal->kelas;
        $data['jadwal']   = $jadwal;
        $data['rows']     = $this->M_krs->peserta($jadwal->id_jadwal);
        $data['belum_acc'] = $this->db->where(array('id_jadwal' => $jadwal->id_jadwal, 'status' => 'Diajukan'))->count_all_results('tbl_krs');
        $this->render('nilai/input', $data);
    }

    public function save()
    {
        $this->only_post();
        $jadwal = $this->jadwal_or_fail($this->input->post('id_jadwal'));
        $nilai  = (array) $this->input->post('nilai');

        $valid = array();
        foreach ($this->M_krs->peserta($jadwal->id_jadwal) as $p) {
            $valid[$p->id_krs] = TRUE;
        }
        $clamp = function ($v) {
            return max(0, min(100, (float) str_replace(',', '.', $v)));
        };
        $n = 0;
        $this->db->trans_start();
        foreach ($nilai as $id_krs => $v) {
            if (!isset($valid[$id_krs])) continue;
            // lewati baris yang dikosongkan seluruhnya
            if (trim($v['tugas'] ?? '') === '' && trim($v['uts'] ?? '') === '' && trim($v['uas'] ?? '') === '') continue;
            $this->M_krs->simpan_nilai((int) $id_krs, $clamp($v['tugas'] ?? 0), $clamp($v['uts'] ?? 0), $clamp($v['uas'] ?? 0));
            $n++;
        }
        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Nilai ' . $n . ' mahasiswa berhasil disimpan.');
        redirect('nilai/input/' . $jadwal->id_jadwal);
    }
}
