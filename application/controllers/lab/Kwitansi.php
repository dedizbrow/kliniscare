<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kwitansi extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		isAllowed("lab::kwitansi");
		$this->load->library("datatables");
		$this->lang->load(conf('path_module_lab') . 'pemeriksaan', $this->session->userdata('site_lang'));
		$this->load->model(conf('path_module_lab') . "Pasien_model", "pasien");
		$this->load->model(conf('path_module_lab') . "Kwitansi_model", "kwitansi");
		$this->load->model(conf('path_module_lab') . "Pemeriksaan_model", "pemeriksaan");
		$this->load->model(conf('path_module_lab') . "Report_model", "report");
		$this->load->model("/admin/Other_setting_model", "other_set");
		$this->asset_path = '../../assets';
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . "Cetak Kwitansi";
		$this->data["page_title"] = lang('page_title');
		$this->data['js_control'] = conf('path_module_lab') . "kwitansi.js";
		$this->data['datatable'] = true;
		$gets = $this->input->get();
		$this->data['status'] = "";
		if (isset($gets['status'])) $this->data['status'] = htmlentities($gets['status']);
		$this->template->load(get_template(), conf('path_module_lab') . 'kwitansi/data', $this->data);
	}
	public function test()
	{
		$this->data["web_title"] = lang('app_name_short') . "Cetak Kwitansi";
		$this->data["page_title"] = lang('page_title');
		$this->data['js_control'] = conf('path_module_lab') . "kwitansi.js";
		$this->data['datatable'] = true;
		$gets = $this->input->get();
		$this->data['status'] = "";
		if (isset($gets['status'])) $this->data['status'] = htmlentities($gets['status']);
		$this->template->load(get_template(), conf('path_module_lab') . 'kwitansi/print', $this->data);
	}
	public function print()
	{
		$this->data["web_title"] = lang('app_name_short') . "Print Kwitansi";
		$this->data["page_title"] = lang('page_title');
		$clinic_id = getClinic()->id;
		$gets = $this->input->get();
		if ($clinic_id == 'allclinic' && isset($gets['clinic_id'])) $clinic_id = $gets['clinic_id'];

		$page_title = "Kwitansi ";
		$setting_doc = $this->pemeriksaan->_get_setting('doc-footer-text');
		$paper_size = (isset($gets['size'])) ? $gets['size'] : 'A5';
		$orientation = (isset($gets['orientation'])) ? $gets['orientation'] : 'L';

		if (!isset($gets['viewid']) || !isset($gets['tn'])) {
			die("No Document can be print");
		}
		// update 2021-08-17
		$doc_setting = $this->other_set->get_setting_doc_requirements(array("clinic_id" => $clinic_id));
		$this->data['doc_setting'] = array_group_by("code", $doc_setting, true);
		$vid = htmlentities($gets['viewid']);
		$periksa = $this->kwitansi->_search((int) htmlentities($vid), htmlentities((int) trim($gets['tn'])));
		if (empty($periksa)) die("Data Not exist");
		$dt_periksa = $periksa[0];
		$start_date = date("Y-m-d");
		$end_date = date("Y-m-d");
		$last_tarif = $this->report->load_last_tarif($dt_periksa->id_provider, $dt_periksa->id_jenis, $start_date, $end_date);
		$last_date = (sizeof($last_tarif) > 0) ? $last_tarif[0]->start_date : '';
		$tarif = $this->report->load_tarif($dt_periksa->id_provider, $dt_periksa->id_jenis, $start_date, $end_date, $last_date);
		$this->data['desc_terima_dari'] = $dt_periksa->nama_pasien . " (RegNo. " . $dt_periksa->no_test . "/" . $dt_periksa->id_pasien . ")";
		$this->data['desc_pembayaran'] = "Pemeriksaan Pasien a/n " . $dt_periksa->nama_pasien . ", tanggal " . dateIndo($dt_periksa->update_hasil_at);
		$this->data['ttd_kwitansi'] = './assets/img/' . conf('path_module_lab') . 'ttd-suket.png';
		$this->data['ttd_hasil'] = $this->data['doc_setting']->img_ttd_validator->path; // utk di label hasil
		// $this->data['logo_ttd']='./assets/img/'.conf('path_module_lab').'ttd-suket.png';
		$this->data['logo_ttd_suket'] = $this->data['doc_setting']->img_ttd_validator->path;
		// echo '<pre>';
		// print_r($dt_periksa);
		// print_r($last_tarif);
		// print_r($tarif);
		// echo '</pre>';
		// die();

		$this->data['biaya'] = $tarif[0]->nominal;
		$this->data['biaya_terbilang'] = terbilang($this->data['biaya']);
		$this->data['data_periksa'] = $dt_periksa;
		$dt_profile =  $this->other_set->get_com_profile(array("clinic_id" => $clinic_id));
		$data_profile = $dt_profile[0];
		$this->data['doc_setting_profile'] = $data_profile;
		// $detail=$this->pemeriksaan->_search_detail($vid);
		// $this->data['detail_periksa']=$detail;
		$mpdf = new \Mpdf\Mpdf(['format' => $paper_size, 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

		$this->data['page_title'] = $page_title;
		$mpdf->SetAuthor(conf('lab_nama_klinik_id'));
		$mpdf->SetCreator(conf('lab_nama_klinik_id'));
		$mpdf->SetTitle($page_title);
		$mpdf->SetSubject($page_title);
		$mpdf->shrink_tables_to_fit = 1;
		//$mpdf->SetProtection(['print'],'','--YM^21..');
		$margin_left = 0;
		$margin_right = 0;
		$margin_top = 18;
		$margin_bottom = 0;
		$margin_header_top = 0;
		$margin_footer_bottom = 0;
		$mpdf->addPage($orientation, '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);

		$mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url($this->data['doc_setting']->img_doc_header_kwitansi->path) . '"></div>', '', true);
		//<img src="'.base_url('/assets/img/ym-doc-footer.jpg').'">
		// $mpdf->SetHTMLFooter('<div class="page-footer-pdf"><div class="page-footer-text">'.$setting_doc->content.'</div></div>'); 
		// $mpdf->SetHTMLFooter('<div class="page-header-pdfs"><img src="'.base_url('/assets/img/'.conf('path_module_lab').'img-doc-footer.png').'"></div>','',false);
		$mpdf->defaultfooterline = 30;
		if(isset($this->data['doc_setting']->img_doc_background_kwitansi) && $this->data['doc_setting']->img_doc_background_kwitansi->path!=""){
			$mpdf->SetDefaultBodyCSS('background', "url(".base_url($this->data['doc_setting']->img_doc_background_kwitansi->path).") no-repeat center center");
			$mpdf->SetDefaultBodyCSS('background-image-resize', 1);
		}
		$html = $this->load->view(conf('path_module_lab') . 'kwitansi/print', $this->data, true);
		$mpdf->WriteHTML($html);
		$mpdf->Output($page_title . ".pdf", 'I');
	}
	public function search_notes_select2_()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->pemeriksaan->_search_notes_select2($key);
		echo json_encode($search);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$provider_id = ($this->data['C_PV_GROUP'] != "pusat") ? $this->data['C_PV_ID'] : '';

		$data = $this->kwitansi->_load_dt($posted, $provider_id);
		echo json_encode($data);
	}
	public function search__($id = '')
	{
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		header('Content-Type: application/json');
		$search = $this->pemeriksaan->_search(array("id" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
	private function getNewTestNo($string = '')
	{
		$created = FALSE;
		do {
			$no_test = date("y-") . strtoupper(substr(str_shuffle("0123456789aAbBcCdDeEfFgGhHiIjJkKlKmMnNqQrRsStTvVwWxXyYzZ" . $string), 0, 6));
			$check = $this->pemeriksaan->checkNoTest(($no_test));
			if (empty($check)) {
				$created = TRUE;
				return $no_test;
			}
		} while ($created == TRUE);
	}
	public function confirm_bayar()
	{
		$method = $this->input->method(true);
		if ($method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->input_stream();
		if (!isset($posted['id'])) sendError("Missing ID");
		if (!isset($posted['tn'])) sendError("Missing ID");
		$save = $this->kwitansi->confirm_bayar($posted['id'], $posted['tn']);
		if ($save > 0) {
			$link = base_url(conf('path_module_lab') . 'kwitansi/print/?viewid=' . $posted['id'] . '&tn=' . strtolower($posted['tn'])) . '&pdf=true';
			sendJSON(array("message" => "Success", "link" => $link));
		}
		sendError("Confirm gagal");
	}
	public function save__()
	{
		header('Content-Type: application/json');
		//isAllowed("c-privilege^create-update-user");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = $value;
		}
		if (!isset($jam_sampling) || $jam_sampling == "") return sendError("Pastikan sudah set jam sampling");
		if (!preg_match('/(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/', $jam_sampling)) {
			return sendError("Format Jam Salah");
		}
		//$hasil=(gettype($hasil)=='string') ? [$hasil] : $hasil;
		//$nilai_rujukan=(gettype($nilai_rujukan)=='string') ? [$nilai_rujukan] : $nilai_rujukan;
		//$metode=(gettype($metode)=='string') ? [$metode] : $metode;
		if (!isset($posted['ref_id']) || $posted['ref_id'] == "") {
			if (!isset($_id_pasien) || $_id_pasien == "") return sendError("Pastikan telah mengisi data pasien");
			if (!isset($nama_lengkap) && $nama_lengkap == "") return sendError("Pastikan telah mengisi data pasien");
			$test_no = $this->pemeriksaan->get_new_no_pemeriksaan();
			$provider = (isset($provider) && $provider != 'none') ? $provider : $this->data['C_PV_ID'];
			if (!isset($provider) || $provider == "") return sendError("Pastikan sudah memilih provider");
			if (!isset($dokter) || $dokter == "") return sendError("Pastikan sudah memilih dokter");
			if (!isset($tgl_sampling) || $tgl_sampling == "") return sendError("Pastikan sudah memilih tanggal sampling");

			if (!isset($jenis_sample) || $jenis_sample == "") return sendError("Pastikan sudah memilih jenis sample");
			//$id_periksa=(gettype($id_periksa)=='string') ? [$id_periksa] : $id_periksa;
			//if(!isset($id_periksa) || ($id_periksa=="" || empty($id_periksa))) return sendError("Jenis pemeriksaan belum diisi");
			// add new user
			$save = $this->pemeriksaan->_save(array(
				"no_test" => $test_no,
				"id_pasien" => $_id_pasien,
				"nama_pasien" => $nama_lengkap,
				"id_provider" => htmlentities(trim($provider)),
				"id_dokter" => htmlentities(trim($dokter)),
				"tgl_periksa" => date("Y-m-d"),
				"tgl_sampling" => htmlentities(trim($tgl_sampling)) . " " . $jam_sampling,
				"jenis_sample" => htmlentities(trim($jenis_sample)),
				//"keluhan"=>htmlentities(trim($keluhan)),
				"jenis_pemeriksaan" => htmlentities(trim($jenis_pemeriksaan)),
			), array(), "no_test");
			if ($save == 'exist') {
				sendError(lang('msg_record_exist'));
			} else {
				// $data_detail=[];
				// foreach($nama_pemeriksaan as $i=>$v){
				// 	array_push($data_detail,array(
				// 	"id_pemeriksaan"=>$save,
				// 	"id_jenis"=>htmlentities(trim($jenis_pemeriksaan)),
				// 	"id_sampling"=>$jenis_sample,
				// 	"hasil"=>htmlentities(trim($hasil_periksa[$i])),
				// 	"nilai_rujukan"=>htmlentities(trim($nilai_rujukan[$i])),
				// 	"metode"=>htmlentities(trim($metode[$i]))
				// 	));
				// }
				// // save detail pemeriksaan
				// $save=$this->pemeriksaan->_save_detail($data_detail,array(),"id_pemeriksaan");
				//if($save==0) sendError(lang('msg_insert_failed'));
				sendSuccess(lang('msg_insert_success'));
			}
		} else {
			if (!isset($dokter) || $dokter == "") return sendError("Pastikan sudah memilih dokter");
			//if(!isset($tgl_sampling) || $tgl_sampling=="") return sendError("Pastikan sudah memilih tanggal sampling");
			if (!isset($tgl_sampling) || $tgl_sampling == "") return sendError("Tanggal sampling belum di set");
			if (!isset($jenis_sample) || $jenis_sample == "") return sendError("Pastikan sudah memilih jenis sample");
			if (!isset($masa_berlaku) || $masa_berlaku == "" || $masa_berlaku == 0) return sendError("Masa berlaku surat belum ditentukan");
			if (!isset($masa_berlaku_opt) || $masa_berlaku_opt == "") return sendError("Masa berlaku surat belum ditentukan");
			if (isset($note_updates)) {
				$id_notes = (gettype($id_notes) == 'string') ? [$id_notes] : $id_notes;
				$join_notes = implode(",", $id_notes);
				$s = $this->pemeriksaan->_save(array("id_notes" => $join_notes), array("id" => $ref_id), "no_test");
			}
			$set_data = array(
				"hasil" => $hasil,
				"update_hasil_at" => date("Y-m-d H:i"),
				"id_dokter" => htmlentities(trim($dokter)),
				"status" => ($hasil != "") ? "SELESAI" : "",
				//"tgl_periksa"=>htmlentities(trim($tgl_sampling)),
				"jenis_sample" => htmlentities(trim($jenis_sample)),
				"tgl_sampling" => htmlentities(trim($tgl_sampling)) . " " . $jam_sampling,
				"masa_berlaku" => (int) htmlentities(trim($masa_berlaku)),
				"masa_berlaku_opt" => htmlentities(trim($masa_berlaku_opt))
				//"keluhan"=>htmlentities(trim($keluhan))
			);
			$s = $this->pemeriksaan->_save($set_data, array("id" => $ref_id), "no_test");
			$data_detail = [];
			$save = 0;
			if (isset($nama_pemeriksaan)) {
				foreach ($nama_pemeriksaan as $i => $v) {
					array_push($data_detail, array(
						"id_pemeriksaan" => $ref_id,
						"nama_pemeriksaan" => $v,
						"id_jenis" => htmlentities(trim($id_jenis_pemeriksaan)),
						"id_sampling" => $jenis_sample,
						"hasil" => htmlentities(trim($hasil_periksa[$i])),
						"nilai_rujukan" => htmlentities(trim($nilai_rujukan[$i])),
						"metode" => htmlentities(trim($metode[$i]))
					));
				}
				$dl = $this->pemeriksaan->_delete_detail_pemeriksaan(array("id_pemeriksaan" => $ref_id));
				// save detail pemeriksaan
				$save = $this->pemeriksaan->_save_detail($data_detail, array(), "id_pemeriksaan");
			}
			// if(!isset($id_details)) sendError("Error");  
			// $id_details=(gettype($id_details)=='string') ? [$id_details] : $id_details;
			// foreach($id_details as $i=>$v){
			// 	$detail=array(
			// 		"hasil"=>htmlentities(trim($hasil[$i])),
			// 		"nilai_rujukan"=>htmlentities(trim($nilai_rujukan[$i])),
			// 		"metode"=>htmlentities(trim($metode[$i]))
			// 	);
			// 	$sv=$this->pemeriksaan->_save_detail($detail,array("id"=>$id_details[$i]),"id_pemeriksaan");
			// }
			// $s=$this->pemeriksaan->_save(array("status"=>"SELESAI"),array("id"=>$ref_id),"no_test");
			if ($s > 0 || $save > 0) {
				sendSuccess(lang('msg_update_success'));
			} else {
				sendError("Gagal. Silakan coba lagi");
			}
		}
	}
	public function cancel__($id = '')
	{
		header('Content-Type: application/json');
		//isAllowed("c-privilege^delete work type");
		$method = $this->input->method(true);
		if ($method != "PUT") sendError(lang('msg_method_put_required'), [], 405);
		$result = $this->pemeriksaan->_cancel(array("status" => "CANCEL"), array('id' => htmlentities(trim($id))));
		if ($result == 1) {
			sendSuccess(lang('msg_cancel_success'), []);
		} else {
			sendError(lang('msg_cancel_failed'));
		}
	}
	public function cancel_with_remark($id = '')
	{
		header('Content-Type: application/json');
		//isAllowed("c-privilege^delete work type");
		$method = $this->input->method(true);
		if ($method != "PUT") sendError(lang('msg_method_put_required'), [], 405);
		$result = $this->pemeriksaan->_cancel(array(
			"status" => "CANCEL",
			// "deleted"=>"2",
			// "deleted_at"=>date("Y-m-d H:i:s"),
			// "deleted_by"=>$this->data['C_NAME'],
		), array('id' => htmlentities(trim($id))));
		if ($result == 1) {
			sendSuccess(lang('msg_cancel_success'), []);
		} else {
			sendError(lang('msg_cancel_failed'));
		}
	}
	/* JENIS SAMPLING */
	public function search_jenis_sample__($id = '')
	{
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		header('Content-Type: application/json');
		$search = $this->pemeriksaan->_search_jenis_sample(array("id" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
	public function load_dt_jenis_sample()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$data = $this->pemeriksaan->_load_dt_jenis_sample($posted);
		echo json_encode($data);
	}
	public function save_jenis_sample__()
	{
		header('Content-Type: application/json');
		//isAllowed("c-privilege^create-update-user");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		if ($nama_sampling == "") return sendError("Nama Sample Wajib diisi");
		if (!isset($posted['_id']) || $posted['_id'] == "") {
			// add new user
			$save = $this->pemeriksaan->_save_jenis_sample(array(
				"nama_sampling" => $nama_sampling,
				"nama_sampling_en" => $nama_sampling_en,
			), array(), "nama_sampling");
			if ($save == 'exist') {
				sendError(lang('msg_record_exist'));
			} else {
				if ($save == 0) sendError(lang('msg_insert_failed'));
				sendSuccess(lang('msg_insert_success'));
			}
		} else {
			// update existing user
			$data = array(
				"nama_sampling" => $nama_sampling,
				"nama_sampling_en" => $nama_sampling_en,
			);
			$save = $this->pemeriksaan->_save_jenis_sample($data, array("id" => htmlentities(trim($posted['_id']))), "nama_sampling");
			if ($save === "exist") {
				sendError(lang('msg_record_exist'));
			} else {
				if ($save > 0)
					sendSuccess(lang('msg_update_success'));
				sendError(lang('msg_update_failed'));
			}
		}
	}
	public function delete_jenis_sample__($id = '')
	{
		header('Content-Type: application/json');
		//isAllowed("c-privilege^delete work type");
		$method = $this->input->method(true);
		if ($method != "DELETE") sendError(lang('msg_method_delete_required'), [], 405);
		$result = $this->pemeriksaan->_delete_jenis_sample(array('id' => htmlentities(trim($id))));
		if ($result == 1) {
			sendSuccess(lang('msg_delete_success'), []);
		} else {
			sendError(lang('msg_delete_failed'));
		}
	}
	/* JENIS NOTES */
	public function search_notes__($id = '')
	{
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		header('Content-Type: application/json');
		$search = $this->pemeriksaan->_search_notes(array("id" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
	public function load_dt_notes()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$data = $this->pemeriksaan->_load_dt_notes($posted);
		echo json_encode($data);
	}
	public function save_notes__()
	{
		header('Content-Type: application/json');
		//isAllowed("c-privilege^create-update-user");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		if ($notes == "") return sendError("Notes Wajib diisi");
		if (!isset($posted['_id']) || $posted['_id'] == "") {
			// add new user
			$save = $this->pemeriksaan->_save_notes(array(
				"notes" => $notes,
				"english" => $english
			), array(), "notes");
			if ($save == 'exist') {
				sendError(lang('msg_record_exist'));
			} else {
				if ($save == 0) sendError(lang('msg_insert_failed'));
				sendSuccess(lang('msg_insert_success'));
			}
		} else {
			// update existing user
			$data = array(
				"notes" => $notes,
				"english" => $english
			);
			$save = $this->pemeriksaan->_save_notes($data, array("id" => htmlentities(trim($posted['_id']))), "notes");
			if ($save === "exist") {
				sendError(lang('msg_record_exist'));
			} else {
				if ($save > 0)
					sendSuccess(lang('msg_update_success'));
				sendError(lang('msg_update_failed'));
			}
		}
	}
	public function delete_notes__($id = '')
	{
		header('Content-Type: application/json');
		//isAllowed("c-privilege^delete work type");
		$method = $this->input->method(true);
		if ($method != "DELETE") sendError(lang('msg_method_delete_required'), [], 405);
		$result = $this->pemeriksaan->_delete_notes(array('id' => htmlentities(trim($id))));
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

/* End of file Kwitansi.php */
