<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Resep_dokter extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('farmasi/resep_dokter', $this->session->userdata('site_lang'));
		$this->load->model('farmasi/resep_dokter_model', 'resep_dokter');
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		isAllowed('c-resep_dokter');
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Resep dokter";
		$this->data["page_title"] = "Resep dokter";
		$this->data['js_control'] = "farmasi/resep_dokter/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'farmasi/resep_dokter/index', $this->data);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->resep_dokter->_load_dt($posted);
		echo json_encode($data);
	}
	public function search_($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$detail = $this->resep_dokter->_search_detail(array("id_pendaftaran" => $id));
		$search = $this->resep_dokter->_search(array("id_pendaftaran" => $id));
		if (empty($detail)) sendError('Tidak ada resep dokter');
		echo json_encode(array("detail" => $detail, "data" => $search[0]));
	}
	function simpan()
	{
		header('Content-Type: application/json');
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		$id_pendaftaran = htmlentities(trim($posted['_id']));
		if ($id_pendaftaran == '' || $id_pendaftaran == null) sendError("Missing ID");

		$this->resep_dokter->simpan_proses_resep_jual($id_pendaftaran);
		echo json_encode(array("message" => "Berhasil diproses"));
	}
	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
}
