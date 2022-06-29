<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{
	var $data;
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('auth', $this->session->userdata('site_lang'));
		$this->load->model("/admin/Administrative_model", "admin");
		$this->load->model('admin/Klinik_model', 'klinik');
		$this->data = array();
	}
	public function index()
	{
		$data['company_logo'] = base_url(conf('company_logo'));
		if ($this->input->get('redirect')) $data['redirect'] = $this->input->get('redirect');
		$this->load->view('admin/auth/signup', $data);
	}
	public function save_user()
	{
		header('Content-Type: application/json');

		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		$posted = modify_post($posted); // update 2021-09-27
		$email = htmlentities(trim($posted['email']));
		$password = htmlentities(trim($posted['password']));
		$accessibility_base = "c-register,c-igd,c-rawatjalan,c-rawatinap,c-kir,c-farmasi,c-billing,c-lab-pasien,c-bpjs,c-keuangan,c-master,c-privilege";
		$accessibility_menu = "c-antrianperiksa,c-datapasien,c-c-antrianperiksa,c-pemeriksaan-igd,c-koreksitindakan,c-resep_dokter,c-pembelian,c-penjualan,c-obat,c-hutang,c-piutang,lab::pasien,lab::pemeriksaan,lab::report,lab::kwitansi,lab::tarif,lab::admin,c-pemasukan,c-pengeluaran,c-dokter,c-jadwaldokter,c-karyawan,c-ruangan,c-poli,c-l-poli,c-perujuk,c-diagnose,c-asuransi,c-supplier,c-rekanan,c-privilege,c-other_setting";
		$actions_code_base = "c-privilege^create-update-user";
		$actions_code_menu = "c-datapasien^update,c-datapasien^delete,c-datapasien^import,c-datapasien^export,c-obat^create,c-obat^update,c-obat^delete,c-pengeluaran^create,c-pengeluaran^update,c-pengeluaran^delete,c-dokter^create,c-dokter^update,c-dokter^delete,c-jadwaldokter^create,c-jadwaldokter^update,c-jadwaldokter^delete,c-karyawan^create,c-karyawan^update,c-karyawan^delete,c-ruangan^create,c-ruangan^update,c-ruangan^delete,c-poli^create,c-poli^update,c-poli^delete,c-l-poli^create,c-l-poli^update,c-l-poli^delete,c-perujuk^create,c-perujuk^update,c-perujuk^delete,c-diagnose^create,c-diagnose^update,c-diagnose^delete,c-asuransi^create,c-asuransi^update,c-asuransi^delete,c-supplier^create,c-supplier^update,c-supplier^delete,c-rekanan^create,c-rekanan^update,c-rekanan^delete,c-privilege^create,c-privilege^update,c-privilege^activate-user";
		$level = "c-spadmin";

		if (!isEmailValid($email))
			sendError("E-mail tidak valid");
		if (!isset($posted['user_id']) || $posted['user_id'] == "") {
			// add new user
			if (!isMatch($password, htmlentities(trim($posted['repassword']))))
				sendError("Password tidak cocok");
			$data_klinik = array(
				"clinic_code"       => "",
				"clinic_name" 		=> htmlentities(trim($posted['clinic_name'])),
				"reg_by"			=> "Self Register",
			);
			$id_clinic = $this->klinik->_save_get_clinic_id($data_klinik);

			$save = $this->admin->save_user(array(
				"uid" => mt_rand(10100000, 99999999),
				"name" => htmlentities(trim($posted['name'])),
				"uname" => htmlentities(trim($posted['username'])),
				"email" => htmlentities(trim($posted['email'])),
				"no_telp" => htmlentities(trim($posted['no_telp'])),
				"passwd" => hashPasswd($posted['password']),
				"accessibility_base" => $accessibility_base,
				"actions_code_base" => $actions_code_base,
				"accessibility" => $accessibility_menu,
				"actions_code" => $actions_code_menu,
				"level" => $level,
				"clinic_id" => $id_clinic,
				"created_by" => "Self Register",
				"template" => conf('ctc_default_template')
			), array(), "uname");
			if ($save == 'exist') {
				sendError("Username sudah digunakan");
			} else {
				if ($save == 0) sendError("Penambahan data gagal");
				sendSuccess("Pendaftaran berhasil");
			}
		}
	}
}
