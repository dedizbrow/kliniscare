<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jasa_apoteker extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('master-data/jasa_apoteker', $this->session->userdata('site_lang'));
		$this->load->model("master-data/Jasa_apoteker_model", "jasa_apoteker");

		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		// isAllowed("c-invoice^update");
	}

	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Jasa Apoteker";
		// $this->data["page_title_small"] = "text small dibawah page title";
		$this->data['js_control'] = "master-data/jasa_apoteker/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'master-data/jasa_apoteker/index', $this->data);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$data = $this->jasa_apoteker->_load_dt($posted);
		echo json_encode($data);
	}
	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
	public function save__()
	{
		header('Content-Type: application/json');
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		if ($nama == "") return sendError("Masukkan nama jasa");
		if ($nominal == "") return sendError("Masukkan nominal");
		if ($status == "") return sendError("Tentukan status");
		if (!isset($posted['_id']) || $posted['_id'] == "") {
			$save = $this->jasa_apoteker->_save(array(
				"nama" => $nama,
				"nominal" => $nominal,
				"status" => $status,
			), array(), "nama");
			if ($save == 'exist') {
				sendError('Jasa sudah terdaftar !');
			} else {
				echo json_encode(["message" => "Add jasa berhasil"]);
			}
		} else {
			$id = htmlentities(trim($posted['_id']));
			$data = array(
				"nama" => $nama,
				"nominal" => $nominal,
				"status" => $status,
			);
			$where = ["id" => $id];
			$save = $this->jasa_apoteker->_save($data, $where, "nama");
			if ($save === "exist") {
				sendError('Jasa sudah terdaftar !');
			} else {
				$dta = [
					"message" => "Add jasa berhasil",
					"action" => "call_print"
				];
				echo json_encode($dta);
			}
		}
	}
	public function delete__($id = '')
	{
		header('Content-Type: application/json');
		// isAllowed("c-privilege^delete work type");
		$method = $this->input->method(true);
		if ($method != "DELETE") sendError(lang('msg_method_delete_required'), [], 405);
		$result = $this->jasa_apoteker->_delete(array('id' => htmlentities(trim($id))));
		if ($result == 1) {
			sendSuccess(lang('msg_delete_success'), []);
		} else {
			sendError(lang('msg_delete_failed'));
		}
	}
	public function search__($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->jasa_apoteker->_search(array("id" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
}
