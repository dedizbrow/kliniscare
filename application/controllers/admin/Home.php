<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('home', $this->session->userdata('site_lang'));
		$this->load->model('rawat-jalan/Data_pasien_model', 'data_pasien');
		$this->load->model('rawat-jalan/Pasien_telah_diperiksa_model', 'pasien_telah_diperiksa');
		$this->load->model('rawat-inap/Pemeriksaan_model', 'pemeriksaan');
		$this->load->model('farmasi/Penjualan_model', 'penjualan');
		$this->load->model('farmasi/Obat_model', 'obat');
		$this->load->model('/admin/News_update_model', 'news_update');
		// if(conf('enable_module_lab'))
		$this->load->model('lab/Summarylab_model', 'lab_summary');

		$this->load->helper('Authentication');
		$this->data = isAuthorized();
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Home";
		$this->data["page_title"] = "Homepage";
		$this->data["page_title_small"] = "";
		$this->data['js_control'] = "admin/home.js";
		// $this->data['skip_select_clinic'] = false;
		$this->data['datatable'] = false;
		$this->data['chartjs'] = true;
		$clinic_id = getClinic()->id;
		$gets = $this->input->get();
		if ($clinic_id == 'allclinic' && isset($gets['clinic_id'])) $clinic_id = $gets['clinic_id'];

		$this->data['num_patient'] = $this->data_pasien->_num_rows($clinic_id);
		$this->data['num_rawat_jalan'] = $this->pasien_telah_diperiksa->_num_rows($clinic_id, date('Y-m-d'));
		$this->data['num_rawat_inap'] = $this->pemeriksaan->_num_rows($clinic_id);
		$this->data['num_obat'] = $this->obat->_num_rows($clinic_id);
		$this->data['num_trans_obat'] = $this->obat->_num_rows_trans_($clinic_id);

		$this->data['news'] = $this->news_update->detail_news();
		$current_total = $this->penjualan->total_amount($clinic_id, date('Y-m'));
		$current_periode = date('F');
		$lastmonth0 = mktime(0, 0, 0, date("m") - 11, date("d"), date("Y"));
		$lastmonth1 = mktime(0, 0, 0, date("m") - 10, date("d"), date("Y"));
		$lastmonth2 = mktime(0, 0, 0, date("m") - 9, date("d"), date("Y"));
		$lastmonth3 = mktime(0, 0, 0, date("m") - 8, date("d"), date("Y"));
		$lastmonth4 = mktime(0, 0, 0, date("m") - 7, date("d"), date("Y"));
		$lastmonth5 = mktime(0, 0, 0, date("m") - 6, date("d"), date("Y"));
		$lastmonth6 = mktime(0, 0, 0, date("m") - 5, date("d"), date("Y"));
		$lastmonth7 = mktime(0, 0, 0, date("m") - 4, date("d"), date("Y"));
		$lastmonth8 = mktime(0, 0, 0, date("m") - 3, date("d"), date("Y"));
		$lastmonth9 = mktime(0, 0, 0, date("m") - 2, date("d"), date("Y"));
		$lastmonth10 = mktime(0, 0, 0, date("m") - 1, date("d"), date("Y"));
		// $lastmonth11 = mktime(0, 0, 0, date("m") - 12, date("d"), date("Y"));
		$last_periode0 = date('F', $lastmonth0);
		$last_periode1 = date('F', $lastmonth1);
		$last_periode2 = date('F', $lastmonth2);
		$last_periode3 = date('F', $lastmonth3);
		$last_periode4 = date('F', $lastmonth4);
		$last_periode5 = date('F', $lastmonth5);
		$last_periode6 = date('F', $lastmonth6);
		$last_periode7 = date('F', $lastmonth7);
		$last_periode8 = date('F', $lastmonth8);
		$last_periode9 = date('F', $lastmonth9);
		$last_periode10 = date('F', $lastmonth10);
		// $last_periode11 = date('F', $lastmonth11);
		$last_total6 = $this->penjualan->total_amount($clinic_id, date('Y-m', $lastmonth6));
		$last_total7 = $this->penjualan->total_amount($clinic_id, date('Y-m', $lastmonth7));
		$last_total8 = $this->penjualan->total_amount($clinic_id, date('Y-m', $lastmonth8));
		$last_total9 = $this->penjualan->total_amount($clinic_id, date('Y-m', $lastmonth9));
		$last_total10 = $this->penjualan->total_amount($clinic_id, date('Y-m', $lastmonth10));
		$this->data['chart_data_apotek'] = array(
			array("periode" => $last_periode6, "total" => $last_total6[0]->total),
			array("periode" => $last_periode7, "total" => $last_total7[0]->total),
			array("periode" => $last_periode8, "total" => $last_total8[0]->total),
			array("periode" => $last_periode9, "total" => $last_total9[0]->total),
			array("periode" => $last_periode10, "total" => $last_total10[0]->total),
			array("periode" => $current_periode, "total" => $current_total[0]->total)
		);
		$last_patient0 = $this->pasien_telah_diperiksa->_num_rows($clinic_id, date('Y-m', $lastmonth0));
		$last_patient1 = $this->pasien_telah_diperiksa->_num_rows($clinic_id, date('Y-m', $lastmonth1));
		$last_patient2 = $this->pasien_telah_diperiksa->_num_rows($clinic_id, date('Y-m', $lastmonth2));

		$last_patient3 = $this->pasien_telah_diperiksa->_num_rows($clinic_id, date('Y-m', $lastmonth3));
		$last_patient4 = $this->pasien_telah_diperiksa->_num_rows($clinic_id, date('Y-m', $lastmonth4));
		$last_patient5 = $this->pasien_telah_diperiksa->_num_rows($clinic_id, date('Y-m', $lastmonth5));

		$last_patient6 = $this->pasien_telah_diperiksa->_num_rows($clinic_id, date('Y-m', $lastmonth6));
		$last_patient7 = $this->pasien_telah_diperiksa->_num_rows($clinic_id, date('Y-m', $lastmonth7));
		$last_patient8 = $this->pasien_telah_diperiksa->_num_rows($clinic_id, date('Y-m', $lastmonth8));

		$last_patient9 = $this->pasien_telah_diperiksa->_num_rows($clinic_id, date('Y-m', $lastmonth9));
		$last_patient10 = $this->pasien_telah_diperiksa->_num_rows($clinic_id, date('Y-m', $lastmonth10));
		// $last_patient11 = $this->pasien_telah_diperiksa->_num_rows(date('Y-m', $lastmonth11));
		$current_patient = $this->pasien_telah_diperiksa->_num_rows($clinic_id, date('Y-m'));
		$this->data['chart_data_rawat_jalan'] = array(
			array("periode" => $last_periode0, "total" => $last_patient0),
			array("periode" => $last_periode1, "total" => $last_patient1),
			array("periode" => $last_periode2, "total" => $last_patient2),
			array("periode" => $last_periode3, "total" => $last_patient3),
			array("periode" => $last_periode4, "total" => $last_patient4),
			array("periode" => $last_periode5, "total" => $last_patient5),
			array("periode" => $last_periode6, "total" => $last_patient6),
			array("periode" => $last_periode7, "total" => $last_patient7),
			array("periode" => $last_periode8, "total" => $last_patient8),
			array("periode" => $last_periode9, "total" => $last_patient9),
			array("periode" => $last_periode10, "total" => $last_patient10),
			// array("periode" => $last_periode11, "total" => $last_patient11),
			array("periode" => $current_periode, "total" => $current_patient)
		);
		// $lab_pasien = $this->lab_summary->count_pasien($this->data['C_PV_ID']);
		// $this->data['lab_jumlah_pasien'] = $lab_pasien[0]->jumlah;
		$this->template->load(get_template(), 'admin/home', $this->data);
	}

	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->obat->_load_dt_obat_stok_min($posted);
		echo json_encode($data);
	}
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */
