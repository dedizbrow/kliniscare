<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Antrian_pemeriksaan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('rawat-jalan/antrian_pemeriksaan', $this->session->userdata('site_lang'));
		$this->load->model('rawat-jalan/Antrian_pemeriksaan_model', 'antrian_pemeriksaan');
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		$this->data['sistem_antrian']=true;
		isAllowed('c-antrianperiksa');
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Antrian pemeriksaan";
		$this->data["page_title"] = "Antrian pemeriksaan";
		// $this->data["page_title_small"] = "text small dibawah page title";
		$this->data['js_control'] = "rawat-jalan/antrian_pemeriksaan/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'rawat-jalan/antrian_pemeriksaan/index', $this->data);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->antrian_pemeriksaan->_load_dt($posted);
		echo json_encode($data);
	}
	public function lewati($id = '')
	{
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		$this->antrian_pemeriksaan->ubah_status($id);
		echo json_encode(array("message" => "Ditambah ke <a href='antrian_ditunda')'>Antrian Ditunda</a>"));
	}

	public function search_pendaftaran($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->antrian_pemeriksaan->_search_pendaftaran(array("id_antrian" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
	public function save__()
	{
		// header('Content-Type: application/json');
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = (gettype($value) == 'string') ? htmlentities(trim($value)) : $value;
		}

		$id_pendaftaran = htmlentities(trim($posted['_id']));
		$id_antrian = htmlentities(trim($posted['_id_antrian']));
		$data_pemeriksaan = array(
			"id_pendaftaran"        => $id_pendaftaran,
			"kesadaran"             => $kesadaran,
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
			"bb"                    => $bb,
			"tb"                    => $tb,
			"catatan_dokter"        => $catatan_dokter,
			"nyeri"                 => $nyeri,
			"creator_id"            => $this->data['C_UID']
		);
		$id_pemeriksaan = $this->antrian_pemeriksaan->_save($data_pemeriksaan);

		if (isset($fk_diagnosa) && !empty($fk_diagnosa)) {
			if ($fk_dokter == "") return sendError("Dokter pemberi diagnosa wajib diisi");
			$data_diagnosa = array();
			foreach ($fk_diagnosa as $key => $value) {
				$data_diagnosa[$key]['fk_pemeriksaan'] = $id_pemeriksaan;
				$data_diagnosa[$key]['fk_dokter'] = $fk_dokter;
				$data_diagnosa[$key]['fk_diagnosa'] = $value;
			}
			$this->antrian_pemeriksaan->_save_diagnosa($data_diagnosa);
		}


		if (isset($fk_tindakan) && !empty($fk_tindakan)) {
			if ($fk_dokter_tindakan == "") return sendError("Dokter pemberi tindakan wajib diisi");
			$data_tindakan = array();
			foreach ($fk_tindakan as $key => $value) {
				$data_tindakan[$key]['fk_pemeriksaan'] = $id_pemeriksaan;
				$data_tindakan[$key]['fk_dokter'] = $fk_dokter_tindakan;
				$data_tindakan[$key]['fk_tindakan'] = $value;
			}
			$this->antrian_pemeriksaan->_save_tindakan($data_tindakan);
		}

		$this->antrian_pemeriksaan->ubah_status_afterpemeriksaan($id_antrian);
		if ($id_pemeriksaan > 0) sendSuccess("<a href='pasien_telah_diperiksa')'>Pasien telah diperiksa</a>");
		sendError("Penambahan gagal");
	}
	public function select2_dokter()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->antrian_pemeriksaan->_search_select_dokter($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function select2_diagnosa()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->antrian_pemeriksaan->_search_select_diagnosa($key, $gets['clinic_id']);
		echo json_encode($search);
	}

	public function select2_tindakan()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->antrian_pemeriksaan->_search_select_tindakan($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
}
