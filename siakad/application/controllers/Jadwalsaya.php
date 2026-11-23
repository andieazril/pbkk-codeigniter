<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Jadwal mengajar untuk user level Dosen */
class Jadwalsaya extends MY_Controller
{
    public function index()
    {
        $this->load->model(array('M_jadwal', 'M_tahunakademik'));
        $ta = $this->input->get('id_tahun_akademik', TRUE) ?: tahun_akademik_aktif('id_tahun_akademik');
        $data['title']  = 'Jadwal Mengajar';
        $data['ta']     = $ta;
        $data['ta_opt'] = $this->M_tahunakademik->dropdown();
        $data['rows']   = ($ta && $this->my_id_dosen()) ? $this->M_jadwal->get_filter($ta, NULL, $this->my_id_dosen()) : array();
        $this->render('jadwalsaya/index', $data);
    }
}
