<?php
defined('BASEPATH') or exit('No direct script access allowed');

class News_update extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('news_update', $this->session->userdata('site_lang'));
		$this->load->model('/admin/News_update_model', 'news_update');
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		isAllowed("ctc::news");
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Berita update";
		$this->data["page_title"] = "Berita update";
		// $this->data["page_title_small"] = "text small dibawah page title"; 
		$this->data['js_control'] = "admin/news_update.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), '/admin/news_update/index', $this->data);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$data = $this->news_update->_load_dt($posted);
		echo json_encode($data);
	}

	public function search__($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->news_update->_search(array("id" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
	public function save__()
	{
		header('Content-Type: application/json');
		isAllowed("ctc::news^create");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		if ($judul == "") return sendError("Judul wajib diisi");
		if ($keterangan == "") return sendError("Keterangan wajib diisi");
		if (!isset($posted['_id']) || $posted['_id'] == "") {
			$save = $this->news_update->_save(array(
				"judul"            => $judul,
				"keterangan"    => $keterangan,
				'creator_id'    => $this->data['C_UID']
			), array());
			if ($save == 'exist') {
				sendError('Sudah terdaftar');
			} else {

				echo json_encode(array("message" => "Penambahan berhasil"));
			}
		} else {
			isAllowed("ctc::news^update");
			$id = htmlentities(trim($posted['_id']));
			$data = array(
				"judul"            => $judul,
				"keterangan"    => $keterangan,
				'creator_id'    => $this->data['C_UID']
			);
			$where = ["id" => $id];
			$save = $this->news_update->_save($data, $where);
			if ($save === "exist") {
				sendError("Data telah tersedia");
			} else {
				$dta = array(
					"message" => "Data Berhasil di Update",
					"action" => "call_print"
				);
				echo json_encode($dta);
			}
		}
	}
	public function delete__($id = '')
	{
		header('Content-Type: application/json');
		isAllowed("ctc::news^delete");
		$method = $this->input->method(true);
		if ($method != "DELETE") sendError(lang('msg_method_delete_required'), [], 405);
		$result = $this->news_update->_delete(array('id' => htmlentities(trim($id))));
		if ($result == 1) {
			sendSuccess(lang('msg_delete_success'), []);
		} else {
			sendError(lang('msg_delete_failed'));
		}
	}

	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
}
