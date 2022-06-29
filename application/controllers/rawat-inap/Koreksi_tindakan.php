<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Koreksi_tindakan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('rawat-inap/koreksi_tindakan', $this->session->userdata('site_lang'));
		$this->load->model('rawat-inap/koreksi_tindakan_model', 'koreksi_tindakan');
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		isAllowed('c-koreksitindakan');
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Tindakan Rawat Inap";
		$this->data["page_title"] = "Tindakan Rawat Inap";
		$this->data['js_control'] = "rawat-inap/koreksi_tindakan/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'rawat-inap/koreksi_tindakan/index', $this->data);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->koreksi_tindakan->_load_dt($posted);
		echo json_encode($data);
	}
	public function search_pendaftaran($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->koreksi_tindakan->_search_pendaftaran(array("id_pendaftaran" => $id, "pend.clinic_id" => $gets['clinic_id']));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}

	public function save_pemeriksaan()
	{
		// header('Content-Type: application/json');
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = (gettype($value) == 'string') ? htmlentities(trim($value)) : $value;
		}
		$id_pendaftaran = htmlentities(trim($posted['id_pendaftaran']));
		$data_pemeriksaan = array(
			"id_pendaftaran"        => $id_pendaftaran,
			"anamnesa"              => $anamnesa,
			"pemeriksaan_umum"      => $pemeriksaan_umum,
			"alergi"                => $alergi,
			"sistole"               => $sistole,
			"diastole"              => $diastole,
			"tensi"                 => $tensi,
			"derajat_nadi"          => $derajat_nadi,
			"nafas"                 => $nafas,
			"suhu_tubuh"            => $suhu_tubuh,
			"saturasi"				=> $saturasi,
			"catatan_dokter"        => $catatan_dokter,
			"nyeri"                 => $nyeri,
			"creator_id"            => $this->data['C_UID']
		);

		$id_pemeriksaan = $this->koreksi_tindakan->_save($data_pemeriksaan);
		if (isset($fk_diagnosa) && !empty($fk_diagnosa)) {
			if ($fk_dokter == "") return sendError("Dokter pemberi diagnosa wajib diisi");
			$data_diagnosa = array();
			foreach ($fk_diagnosa as $key => $value) {
				$data_diagnosa[$key]['fk_pemeriksaan'] = $id_pemeriksaan;
				$data_diagnosa[$key]['fk_dokter'] = $fk_dokter;
				$data_diagnosa[$key]['fk_diagnosa'] = $value;
			}
			$this->koreksi_tindakan->_save_diagnosa($data_diagnosa);
		}


		if (isset($fk_tindakan) && !empty($fk_tindakan)) {
			if ($fk_dokter_tindakan == "") return sendError("Dokter pemberi tindakan wajib diisi");
			$data_tindakan = array();
			foreach ($fk_tindakan as $key => $value) {
				$data_tindakan[$key]['fk_pemeriksaan'] = $id_pemeriksaan;
				$data_tindakan[$key]['fk_dokter'] = $fk_dokter_tindakan;
				$data_tindakan[$key]['fk_tindakan'] = $value;
			}
			$this->koreksi_tindakan->_save_tindakan($data_tindakan);
		}
		if ($id_pemeriksaan > 0) sendSuccess("Pasien telah diperiksa");
		sendError("Penambahan gagal");
	}
	public function search_detail_tindakan($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$pemeriksaan = $this->koreksi_tindakan->_search_pemeriksaan($id);

		// $pem_diagnosa = $this->data_pasien->_search_pem_diagnosa(array("fk_pemeriksaan" => 9));
		if (empty($pemeriksaan)) sendError("Pasien belum memiliki riwayat Rekam Medis");
		echo json_encode(array("pemeriksaan" => $pemeriksaan));


		// header('Content-Type: application/json');
		// $gets = $this->input->get();
		// $id = ($id != '') ? $id : $gets['id'];
		// $id = htmlentities(trim($id));
		// if ($id == '' || $id == null) sendError("Missing ID");
		// $pemeriksaan = $this->koreksi_tindakan->_search_pemeriksaan(array("pem.id_pendaftaran" => $id));

		// // $pem_diagnosa = $this->data_pasien->_search_pem_diagnosa(array("fk_pemeriksaan" => 9));
		// if (empty($pemeriksaan)) sendError("Pasien belum memiliki riwayat Rekam Medis");
		// echo json_encode(array("pemeriksaan" => $pemeriksaan));
	}
	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
}
