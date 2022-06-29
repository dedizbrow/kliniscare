<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Klinik extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('klinik', $this->session->userdata('site_lang'));
		$this->load->model('admin/Klinik_model', 'klinik');
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		isAllowed('ctc::clinic', false, 'strict');
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Klinik Terdaftar";
		$this->data["page_title"] = "Klinik Terdaftar";
		$this->data['js_control'] = "admin/klinik.js";
		$this->data['skip_select_clinic'] = true;
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;
		$this->data['enabled_menus']=$this->klinik->load_base_menu();
		
		$this->template->load(get_template(), 'admin/klinik/index', $this->data);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$data = $this->klinik->_load_dt($posted);
		echo json_encode($data);
	}

	public function search__($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->klinik->_search(array("rc_id" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
	public function save__()
	{
		header('Content-Type: application/json');
		isAllowed("ctc::clinic^create");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = (gettype($value)!='array') ? htmlentities(trim($value)) : $value;
		}
		if(!isset($enabled_menus)) return sendError("Pilih setidaknya satu menu untuk user");

		if ($clinic_code == "") return sendError("Kode klinik wajib diisi");
		if ($clinic_name == "") return sendError("Nama klinik wajib diisi");
		if (!isset($account_type)) return sendError("Pilih tipe akun");
		if ($license_duration == "" || $license_duration == 0) return sendError("Set Durasi lisensi");

		if (!isset($posted['_id']) || $posted['_id'] == "") {
			$save = $this->klinik->_save(array(
				"clinic_code"       => $clinic_code,
				"clinic_name" 		=> $clinic_name,
				"account_type" 		=> $account_type,
				"license_duration" 		=> $license_duration,
				"license_type" 		=> $license_type,
				"phone" 		=> $phone,
				"email" 		=> $email,
				"remarks" 		=> $remarks,
				"reg_by"			=> "system",
				"enabled_menus"=>implode(",",$enabled_menus)
			), array(), "clinic_code");
			if ($save == 'exist') {
				sendError('Sudah terdaftar');
			} else {
				echo json_encode(array("message" => "Penambahan berhasil"));
			}
		} else {
			isAllowed("ctc::clinic^update");
			$rc_id = htmlentities(trim($posted['_id']));
			$data = array(
				"clinic_code"       => $clinic_code,
				"clinic_name" 		=> $clinic_name,
				"account_type" 		=> $account_type,
				"license_duration" 		=> $license_duration,
				"license_type" 		=> $license_type,
				"phone" 		=> $phone,
				"email" 		=> $email,
				"remarks" 		=> $remarks,
				"enabled_menus"=>implode(",",$enabled_menus)
			);
			$where = ["rc_id" => $rc_id];
			$save = $this->klinik->_save($data, $where, "clinic_code");
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
	public function enable_disable_clinic()
	{
		requiredMethod('POST');
		isAllowed("ctc::clinic^activate-clinic");
		$posted = $this->input->post();
		$save = $this->klinik->enable_disable_clinic(array("is_active" => $posted['status']), array("rc_id" => $posted['id']));
		if ($save > 0) sendSuccess(lang("msg_update_success"));
		sendError(lang("msg_update_failed"));
	}
	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
}
