<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Crud extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// :: panggil file bahasa/language, diperlukan jika nantinya akan menggunaan multi bahasa pada sistem
		// :: jika belum ada maka buat pada folder language 
		$this->lang->load('master-data/crud', $this->session->userdata('site_lang'));
		// :: load model yang berkaitan dengan controller ini
		$this->load->model("master-data/Crud_model", "crud");

		$this->load->helper('Authentication');
		// :: enable pemanggilan function isAuthorized() dibawah ini jika halaman ini wajib diakses setelah login
		// :: function ada di helper Authentication
		$this->data = isAuthorized();
		// :: jika hanya user tertentu (user dengan priviledge tertentu), panggil function isAllowed(access_code)
		//isAllowed("c-invoice^update");
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | crud";
		$this->data["page_title"] = "Sample Crud";
		$this->data["page_title_small"] = "text small dibawah page title";
		$this->data['js_control'] = "master-data/crud/index.js"; // file javascript utk control halaman ini, /assets/pages/crud.js
		$this->data['datatable'] = true; // set true jika halaman menggunakan datatable
		$this->data['chartjs'] = false; // set true jika halaman menggunakan chartjs 

		$this->template->load(get_template(), 'master-data/crud/index', $this->data);
	}
	// :: contoh load data database
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$data = $this->crud->_load_dt($posted);
		echo json_encode($data);
	}
	public function search__($id = '')
	{
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		header('Content-Type: application/json');
		//$search=$this->crud->_search(array("a.id"=>$id));
		$id = htmlentities(trim($id));
		$search = $this->crud->_search(array("id" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
	public function select2_()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->pasien->_search_select2($key);
		echo json_encode($search);
	}
	public function save__()
	{
		header('Content-Type: application/json');
		// :: enable is Allowed dengan access code tertentu jika hanya user tertentu yg diizinkan untuk insert/update
		//isAllowed("c-privilege^create-update-user");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) { // buat variable semua data yg di post
			$$key = htmlentities(trim($value));
		}
		// :: validasi sendiri
		if ($first_name == "") return sendError("First Name wajib diisi"); // jika menggunakan language ambil dari variablenya
		if ($last_name == "") return sendError("Last Name Wajib diisi");
		if (!isset($gender)) return sendError("Jenis kelamin wajib dipilih");
		// jika ada email, panggil function isEmailValid(email)
		if (!isset($posted['_id']) || $posted['_id'] == "") {
			// add new user
			$save = $this->crud->_save(array(
				"identity_no" => $identity_no,
				"first_name" => $first_name,
				"last_name" => $last_name,
				"gender" => $gender,
				"created_by" => $this->data['C_UID'],
			), array(), "identity_no");
			if ($save == 'exist') {
				sendError('No Identitas Sudah terdaftar');
			} else {

				echo json_encode(array("message" => "Registrasi berhasil"));
			}
		} else {
			// update data pasien
			$id_crud = htmlentities(trim($posted['_id']));
			$data = array(
				"identity_no" => $identity_no,
				"first_name" => $first_name,
				"last_name" => $last_name,
				"gender" => $gender,
			);
			$where = ["id" => $id_crud];
			$save = $this->crud->_save($data, $where, "identity_no");
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
		//isAllowed("c-privilege^delete work type");
		$method = $this->input->method(true);
		if ($method != "DELETE") sendError(lang('msg_method_delete_required'), [], 405);
		$result = $this->crud->_delete(array('id' => htmlentities(trim($id))));
		if ($result == 1) {
			sendSuccess(lang('msg_delete_success'), []);
		} else {
			sendError(lang('msg_delete_failed'));
		}
	}
	// :: selalu load function ini di setiap controller untuk penerapan bahasa, karena saat ini belum dibuat autoload bahasa terkait di javascriptnya
	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
}

/* End of file Crud.php */
