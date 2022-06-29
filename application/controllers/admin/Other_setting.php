<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
 Note: 
 2021-08-17
	- Setting untuk image dokumen diambil dari table c_doc_requirements

 0000-00-00
	- Setting company detail diambil dari table tb_company_detail
*/
class Other_setting extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('other_setting', $this->session->userdata('site_lang'));
		$this->load->helper('Authentication');
		$this->load->library("datatables");
		$this->data = isAuthorized();
		$this->load->model("/admin/Other_setting_model", "other_set");
		$this->data['error_larangan_update']="Maaf. untuk saat ini anda tidak diizinkan melakukan setting pada form ini. silahkan kirimkan dokumen pendukung ke admin ";
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Other setting";
		$this->data["page_title"] = "Other setting";
		$this->data['js_control'] = "admin/other_setting.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;
		isAllowed("c-other_setting");
		$clinic_id=getClinic()->id;
		$gets=$this->input->get();
		if($clinic_id=='allclinic' && isset($gets['clinic_id'])) $clinic_id=$gets['clinic_id'];
		$dtcompro = $this->other_set->get_com_profile(array("clinic_id"=>$clinic_id));
		$this->data['company'] = (!empty($dtcompro)) ? $dtcompro[0] : [];
		$doc_setting = $this->other_set->get_setting_doc_requirements(array("clinic_id"=>$clinic_id));
		$this->data['doc_setting'] = (!empty($doc_setting)) ? array_group_by("code", $doc_setting, true) : [];
		// echo '<pre>';
		// print_r($doc_setting);
		// echo '</pre>';
		$this->template->load(get_template(), 'admin/other_setting/index', $this->data);
	}
	public function save__()
	{
		isAllowed("c-other_setting");
		if($this->data['C_USRNAME']!=conf('super_admin_id')) sendError($this->data['error_larangan_update']);
		header('Content-Type: application/json');
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		$posted=modify_post($posted);
		if($posted['clinic_id']=="allclinic") sendError("Klinik belum dipilih");
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		$data = array(
			"clinic_id"	=> $clinic_id,
			"nama"			=> $nama,
			"kabupaten"	=> $kabupaten,
			"provinsi"	=> $provinsi,
			"alamat"		=> $alamat,
			"no_izin"		=> $no_izin,
			"telp"			=> $telp,
			"email"			=> $email,
		);
		$where=[];
		$check=$this->other_set->check_clinic($clinic_id);
		if($check!==null) $where=array("id"=>$check->id);
		$this->other_set->_save_company($data,$where);

		$dta = array(
			"message" => "Data Berhasil di Update",
			"action" => "call_print"
		);
		echo json_encode($dta);
	}
	public function update_img($code = '')
	{
		if($this->data['C_USRNAME']!=conf('super_admin_id')) sendError($this->data['error_larangan_update']);
		isAllowed("c-other_setting");
		ini_set('max_execution_time', 0);
		header('Content-Type: application/json');
		if ($code == '') sendError("Missing code");
		$this->load->helper('uploadfile');
		$posted = $this->input->post();
		$posted=modify_post($posted);
		$clinic_id=$posted['clinic_id'];
		if($clinic_id=="allclinic") return sendError("Klinik belum dipilih");
		$paths = 'assets/img/docs';
		$fname = $clinic_id."__".str_replace(" ", "_", $code);
		$title=ucwords(str_replace("_"," ",$code));
		switch($code){
			case 'img_doc_footer':
				$title="Gambar untuk Footer Dokumen";
			break;
			case 'img_doc_header':
				$title="Gambar untuk Header Dokumen";
			break;
			case 'img_ttd_validator':
				$title="Gambar TTD validator";
			break;
			case 'img_doc_header_kwitansi':
				$title="Gambar untuk Header Kwitansi";
			break;
			case 'img_doc_logo':
				$title="Gambar untuk Logo";
			break;
		}
		$search_img = $this->other_set->get_setting_doc_requirements(array("code" => $code,"clinic_id"=>$clinic_id));
		$where_save=[];
		if (!empty($search_img)) {
			$img = $search_img[0]->path;
			if (file_exists($img)) unlink($img);
			$where_save=array("code"=>$code,"clinic_id"=>$clinic_id);
		}
		$uploaded = (object) single_upload($paths, "file", $fname, ["gif", "png", "jpg", "jpeg"]);
		$upd_file = $paths . "/" . $uploaded->file_name;
		$ext_file = $uploaded->file_ext;
		$inputFileName = $upd_file;
		//if(strpos($posted['url_target'],"http://")==false) die(json_encode(["message"=>"Invalid Target"]));
		$save = $this->other_set->save_setting_doc_requirements(array(
			"clinic_id" => $clinic_id,
			"code" => $code,
			"title"=>$title,
			"path" => $inputFileName,
			"content" => $ext_file
		), $where_save, "code");
		echo json_encode(array("message" => "Complete. " . $save));
	}
	public function update_city()
	{
		if($this->data['C_USRNAME']!=conf('super_admin_id')) sendError($this->data['error_larangan_update']);
		isAllowed("c-other_setting");
		header('Content-Type: application/json');
		$posted = $this->input->post();
		$save = $this->other_set->save_setting_doc_requirements(array(
			"code" => 'text_city_of_klinik',
			"path" => '',
			"content" => ucwords(htmlentities($posted['city']))
		), [], "code");
		echo json_encode(array("message" => "Complete. " . $save));
	}
	public function load_others_setting()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$data = $this->admin->_load_dt_others_setting($posted);
		echo json_encode($data);
	}
	public function get_setting($id)
	{
		header('Content-Type: application/json');
		$data = $this->admin->_get_setting($id);
		echo json_encode($data);
	}
	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
}
