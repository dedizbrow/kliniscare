<?php
defined('BASEPATH') or exit('No direct script access allowed');
class KunjunganIGD extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('KunjunganIGD', $this->session->userdata('site_lang'));
		$this->load->model('KunjunganIGD_model', 'kunjungan');
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Kunjungan IGD";
		$this->data["page_title"] = "Pelayanan IGD";
		$this->data['js_control'] = "kunjunganIGD/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'kunjunganIGD/index', $this->data);
	}
	//pilih pasien
	public function load_dt_pasien()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->kunjungan->_load_dt_pasien($posted);
		echo json_encode($data);
	}

	public function search_pasien($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$gets = modify_post($gets);
		$search = $this->kunjungan->_search_pasien(array("id_pasien" => $id, "pasien.clinic_id" => $gets['clinic_id']));
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
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

		if ($nomor_rm == "") return sendError("Nomor Rekam Medis wajib diisi");
		if ($nama_lengkap == "") return sendError("Nama wajib diisi");
		if ($nama_lengkap_pjw == "") return sendError("Nama Penanggung Jawab wajib diisi");
		if ($no_identitas == "") return sendError("Nomor identitas wajib diisi");
		if (!isset($triase)) return sendError("triase wajib dipilih");
		// if (!isset($nadi)) return sendError("nadi wajib dipilih");
		// if (!isset($sianosis)) return sendError("sianosis wajib dipilih");
		// if (!isset($crt)) return sendError("crt wajib dipilih");
		// if (!isset($pendarahan)) return sendError("pendarahan wajib dipilih");
		// if (!isset($jalan_nafas)) return sendError("jalan_nafas wajib dipilih");
		// if (!isset($obstruksi)) return sendError("obstruksi wajib dipilih");
		// if (!isset($irama_nafas)) return sendError("irama_nafas wajib dipilih");
		if (!isset($dpjp)) return sendError("dpjp wajib dipilih");
		// if (!isset($trauma)) return sendError("Trauma wajib dipilih");
		// if (!isset($non_trauma)) return sendError("Non trauma wajib dipilih");


		if (!isset($posted['_id']) || $posted['_id'] == "") {
			$data_pasien = array(
				"clinic_id" 	=> $clinic_id,
				"nomor_rm"      => $nomor_rm,
				"create_at" 	=> date("Y-m-d"),
				"nama_lengkap"  => $nama_lengkap,
				"jenis_kelamin" => $jenis_kelamin,
				"tempat_lahir"  => $tempat_lahir,
				"tgl_lahir"     => $tgl_lahir,
				"umur"          => $umur,
				"status_nikah"  => $status_nikah,
				"agama"         => $agama,
				"gol_darah"     => $gol_darah,
				"identitas"     => $identitas,
				"no_identitas"  => $no_identitas,
				"provinsi"      => $provinsi,
				"kabupaten"     => $kabupaten,
				"kecamatan"     => $kecamatan,
				"alamat"        => $alamat,
				"no_hp"         => $no_hp,
				"no_telp"       => $no_telp,
				"pekerjaan"     => $pekerjaan,
				"perusahaan"    => $perusahaan,
				"ibu_kandung"   => $ibu_kandung,
				"asuransi_utama" => $asuransi_utama,
				"no_asuransi"   => $no_asuransi,
				"verified" 		=> 1,
				"creator_id"    => $this->data['C_UID']
			);
			$id_pasien = $this->kunjungan->_save_pasien($data_pasien);
		} else {
			$id_pasien = htmlentities(trim($posted['_id']));
		}
		if (!isset($jenis_kelamin_pjw) && isset($penanggung_jawab) && $penanggung_jawab == "sendiri") $jenis_kelamin_pjw = $jenis_kelamin;
		$invoice = $this->kunjungan->get_no_invoice($clinic_id);
		$data_pendaftar_igd = array(
			"clinic_id" 	=> $clinic_id,
			"fk_pasien"     => $id_pasien,
			"create_at" 	=> date("Y-m-d"),
			"dpjp"          => $dpjp,
			"perujuk"       => $perujuk,
			"nama_tenaga_perujuk"   => $nama_tenaga_perujuk,
			"alasan_datang" 		=> $alasan_datang,
			"keterangan"    		=> $keterangan,
			"nama_lengkap_pjw"      => $nama_lengkap_pjw,
			"jenis_kelamin_pjw"     => $jenis_kelamin_pjw,
			"tgl_lahir_pjw"         => $tgl_lahir_pjw,
			"identitas_pjw"         => $identitas_pjw,
			"no_identitas_pjw"      => $no_identitas_pjw,
			"no_hp_pjw"    => $no_hp_pjw,
			"no_telp_pjw"  => $no_telp_pjw,
			"alamat_pjw"   => $alamat_pjw,
			"no_invoice"   => $invoice,
			"status_rawat" => 1,
			"creator_id"   => $this->data['C_UID']
		);
		$id_pendaftaran = $this->kunjungan->_save_pendaftar_igd($data_pendaftar_igd);

		$data_triase = array(
			"clinic_id"				=> $clinic_id,
			"triase"                => $triase,
			"fk_pendaftaran"        => $id_pendaftaran,
			"nadi"                  => $nadi,
			"sianosis"              => $sianosis,
			"crt"                   => $crt,
			"pendarahan"            => $pendarahan,
			"jalan_nafas"           => $jalan_nafas,
			"obstruksi"             => $obstruksi,
			"irama_nafas"           => $irama_nafas,
			"trauma"                => $trauma,
			"non_trauma"            => $non_trauma,
			"sistole"               => $sistole,
			"diastole"              => $diastole,
			"derajat_nadi"          => $derajat_nadi,
			"suhu_tubuh"            => $suhu_tubuh,
			"pernafasan"            => $pernafasan,
			"anamnesa"              => $anamnesa,
			"e"                     => $e,
			"m"                     => $m,
			"v"                     => $v,
			"nyeri"                 => $nyeri,
			"creator_id"    		=> $this->data['C_UID']
		);
		$id_triase = $this->kunjungan->_save_triase($data_triase);

		if (isset($fk_diagnosa) && !empty($fk_diagnosa)) {
			$data_diagnosa = array();
			foreach ($fk_diagnosa as $key => $valuee) {
				$data_diagnosa[$key]['fk_triase'] = $id_triase;
				$data_diagnosa[$key]['fk_diagnosa'] = $valuee;
			}
			$this->kunjungan->_save_diagnosa($data_diagnosa);
		}
		echo json_encode(array("message" => "Penambahan berhasil"));
	}

	public function select2_perujuk()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->kunjungan->_search_select_perujuk($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function select2_dpjp()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->kunjungan->_search_select_dpjp($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function select2_diagnosa()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->kunjungan->_search_select_diagnosa($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
}
