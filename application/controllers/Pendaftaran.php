<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pendaftaran extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('pendaftaran', $this->session->userdata('site_lang'));
		$this->load->model('Pendaftaran_model', 'pendaftaran');
		$this->load->model("/admin/Other_setting_model", "other_set");
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Pendaftaran";
		$this->data["page_title"] = "Pendaftaran";
		$this->data['js_control'] = "pendaftaran/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;
		// $antrian = $this->pendaftaran->check_antrian();
		// $this->data['new_antrian'] = 1;

		// if (count($antrian) > 0) {
		//     $kode_antrian = $this->data['new_antrian'] = $antrian[0]['maks_antrian'] + 1;
		//     $this->data['new_antrian'] = str_pad($kode_antrian, 3, "0", STR_PAD_LEFT);
		// }

		$this->template->load(get_template(), 'pendaftaran/index', $this->data);
	}

	public function searchcode()
	{
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$clinic_id=(!isset($gets['clinic_id'])) ? getClinic()->id : $gets['clinic_id'];
		$this->data['kodeunik'] = $this->pendaftaran->searchcode($clinic_id);
		echo json_encode($this->data['kodeunik']);
	}
	public function save__()
	{
		header('Content-Type: application/json');
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		$posted = modify_post($posted);
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");
		if ($nomor_rm == "") return sendError("Nomor Rekam Medis wajib diisi");
		if ($nama_lengkap == "") return sendError("Nama wajib diisi");
		if ($nama_lengkap_pjw == "") return sendError("Nama Penanggung Jawab wajib diisi");
		if ($no_identitas == "") return sendError("Nomor identitas wajib diisi");
		if ($jenis_kunjungan == "") return sendError("Jenis kunjungan wajib diisi");
		if ($poli == "") return sendError("Poliklinik wajib diisi");
		if ($dpjp == "") return sendError("Dpjp wajib diisi");

		$ori_rm = $nomor_rm;
		$is_exist = false;
		if (!isset($posted['_id']) || $posted['_id'] == "") {
			// before saving, make sure RM not in use by other

			$is_rm_exist = $this->pendaftaran->isNomorRMExist($nomor_rm, $clinic_id);
			if ($is_rm_exist) {
				$is_exist = true;
				$nomor_rm = $this->pendaftaran->searchcode($clinic_id);
			}
			// echo $clinic_id;
			// die($nomor_rm);
			$data_pasien = array(
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
				"clinic_id" 	=> $clinic_id,
				"creator_id"    => $this->data['C_UID']
			);
			$id_pasien = $this->pendaftaran->_save_pasien($data_pasien);
		} else {
			$id_pasien = htmlentities(trim($posted['_id']));
		}
		// make sure no antrian not in use by other
		$is_antrian_exist = $this->pendaftaran->isAntrianExist($nomor_antrian, $clinic_id);
		if ($is_antrian_exist) {
			$is_exist = true;
			$nomor_antrian = $this->pendaftaran->cari_antrian(array("poli" => $poli, "clinic_id" => $clinic_id));

			$nomor_antrian = $poli_label . $nomor_antrian;
		}
		$data_antrian = array(
			"jenis_kunjungan" 	=> $jenis_kunjungan,
			"nomor_antrian"   	=> $nomor_antrian,
			"poli"         	    => $poli,
			"status"			=> 0,
			"clinic_id" 		=> $clinic_id,
			"creator_id"    	=> $this->data['C_UID']
		);
		$id_antrian = $this->pendaftaran->_save_antrian($data_antrian);
		$invoice = $this->pendaftaran->get_no_invoice($clinic_id);
		if (!isset($jenis_kelamin_pjw) && isset($penanggung_jawab) && $penanggung_jawab == "sendiri") $jenis_kelamin_pjw = $jenis_kelamin;
		$data_pendaftaran = array(
			"clinic_id" 	=> $clinic_id, // added 2021-09-27
			"fk_pasien" 	=> $id_pasien,
			"fk_antrian" 	=> $id_antrian,
			"create_at" 	=> date("Y-m-d"),
			"dpjp"          => $dpjp,
			"poli"          => $poli,
			"perujuk"       => $perujuk,
			"nama_tenaga_perujuk"   => $nama_tenaga_perujuk,
			"alasan_datang" 		=> $alasan_datang,
			"keterangan"    		=> $keterangan,
			"nama_lengkap_pjw"      => $nama_lengkap_pjw,
			"jenis_kelamin_pjw"     => $jenis_kelamin_pjw,
			"tempat_lahir_pjw"      => $tempat_lahir_pjw,
			"tgl_lahir_pjw"         => $tgl_lahir_pjw,
			"identitas_pjw"         => $identitas_pjw,
			"no_identitas_pjw"      => $no_identitas_pjw,
			"no_hp_pjw"    => $no_hp_pjw,
			"no_telp_pjw"  => $no_telp_pjw,
			"alamat_pjw"   => $alamat_pjw,
			"no_invoice"   => $invoice,
			"status_rawat" => 0,
			"creator_id"   => $this->data['C_UID']
		);
		$id_pendaftaran = $this->pendaftaran->_save($data_pendaftaran);
		if (isset($id_pendaftaran)) {
			$link = base_url('pendaftaran/print/?viewid=' . $id_pendaftaran) . '&pdf=true';
			$desc = ($is_exist === false) ? "Pendaftaran berhasil" : "Pendaftaran Berhasil. Nomor " . $nomor_rm;
			sendJSON(array("message" => $desc, "link" => $link));
		}

		echo json_encode(array("message" => "Penambahan berhasil"));
	}
	public function select2_perujuk()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->pendaftaran->_search_select_perujuk($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function select2_agama()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->pendaftaran->_search_select_agama($key);
		echo json_encode($search);
	}
	public function select2_status_nikah()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->pendaftaran->_search_select_status_nikah($key);
		echo json_encode($search);
	}
	public function select2_gol_darah()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->pendaftaran->_search_select_gol_darah($key);
		echo json_encode($search);
	}
	public function select2_asuransi()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->pendaftaran->_search_select_asuransi($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function select2_poli()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->pendaftaran->_search_select_poli($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function select2_dpjp()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->pendaftaran->_search_select_dpjp($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function select2_provinsi()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->pendaftaran->_search_select_provinsi($key);
		echo json_encode($search);
	}
	public function select2_kabupaten()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$keys = (isset($gets['id_provinsi']) && $gets['id_provinsi'] != '') ? $gets['id_provinsi'] : "";
		$search = $this->pendaftaran->_search_select_kabupaten($key, $keys);
		echo json_encode($search);
	}
	public function select2_kecamatan()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$keys = (isset($gets['id_kabupaten']) && $gets['id_kabupaten'] != '') ? $gets['id_kabupaten'] : "";
		$search = $this->pendaftaran->_search_select_kecamatan($key, $keys);
		echo json_encode($search);
	}

	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
	public function cari_no_antri($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$id = ($id != '') ? $id : $gets['id'];
		// $id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		// $search['nomor_antrian'] = 1;
		$search = $this->pendaftaran->cari_antrian(array("poli" => $id, "clinic_id" => $gets['clinic_id']));
		// print_r($search);
		// }
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("nomor_antrian" => $search));
	}
	public function print()
	{
		$clinic_id = getClinic()->id;
		$gets = $this->input->get();
		if ($clinic_id == 'allclinic' && isset($gets['clinic_id'])) $clinic_id = $gets['clinic_id'];
		$id = htmlentities($gets['viewid']);
		$company_info = $this->other_set->get_com_profile(array("clinic_id" => $clinic_id));
		$this->data['company_info'] = $company_info[0];
		$page_title = "Print Nomor Antrian";

		$nomor_antrian = $this->pendaftaran->get_no_antrian($id);
		$this->data['antrian_info'] = $nomor_antrian[0];

		$mpdf = new \Mpdf\Mpdf(['format' => [48, 190], 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

		$this->data['page_title'] = "Print Nomor Antrian";
		$mpdf->SetAuthor(conf('company_name'));
		$mpdf->SetCreator(conf('company_name'));
		$mpdf->SetTitle($page_title);
		$mpdf->SetSubject($page_title);
		$mpdf->shrink_tables_to_fit = 1;
		$margin_left = 0;
		$margin_right = 0;
		$margin_top = 1;
		$margin_bottom = 0;
		$margin_header_top = 0;
		$margin_footer_bottom = 0;
		$mpdf->addPage('P', '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);
		$mpdf->defaultfooterline = 10;
		$html = $this->load->view('pendaftaran/print-no-antri', $this->data, true);
		$mpdf->WriteHTML($html);
		$mpdf->Output($page_title . ".pdf", 'I');
	}
}
