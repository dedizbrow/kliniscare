<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pemeriksaan extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		$this->load->library("datatables");
		$this->lang->load(conf('path_module_lab') . 'pemeriksaan', $this->session->userdata('site_lang'));
		$this->load->model(conf('path_module_lab') . "Pasien_model", "pasien");
		$this->load->model(conf('path_module_lab') . "Pemeriksaan_model", "pemeriksaan");
		$this->load->model(conf('path_module_lab') . "Report_model", "report");
		$this->load->model("/admin/Other_setting_model", "other_set");
		$this->asset_path = '../../assets';
		$this->data['access_code_pemeriksaan'] = "lab::pemeriksaan";
	}
	public function index()
	{
		isAllowed($this->data['access_code_pemeriksaan']);
		$this->data["web_title"] = lang('app_name_short') . "Data Pemeriksaan";
		$this->data["page_title"] = lang('page_title');
		$this->data['js_control'] = conf('path_module_lab') . "pemeriksaan/data.js";
		$this->data['datatable'] = true;
		$gets = $this->input->get();
		$this->data['status'] = "";
		if (isset($gets['status'])) $this->data['status'] = htmlentities($gets['status']);
		$this->template->load(get_template(), conf('path_module_lab') . 'pemeriksaan/data', $this->data);
	}
	public function form($act = '')
	{
		isAllowed($this->data['access_code_pemeriksaan']);
		$this->data["web_title"] = lang('app_name_short') . "Data Pemeriksaan > New";
		$this->data["page_title"] = lang('page_title');
		$this->data['js_control'] = conf('path_module_lab') . "pemeriksaan/form.js";
		$this->data['datatable'] = true;
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$is_edit = ($act == 'edit' || isset($gets['edit'])) ? TRUE : FALSE;
		$is_without_header = (isset($gets['with_header']) && $gets['with_header'] == 'false') ? TRUE : FALSE;

		$is_without_sign = (isset($gets['with_sign']) && $gets['with_sign'] == 'false') ? TRUE : FALSE;
		if (isset($gets['viewid']) && isset($gets['tn'])) {
			$this->data['skip_select_clinic'] = true;
			$vid = htmlentities($gets['viewid']);
			$periksa = $this->pemeriksaan->_search((int) htmlentities($vid), htmlentities((int) trim($gets['tn'])));
			if (empty($periksa)) die("Data Not exist");
			$dt_periksa = $periksa[0];
			$this->data['clinic_id'] = $dt_periksa->clinic_id;
			$doc_setting = $this->other_set->get_setting_doc_requirements(array("clinic_id" => $dt_periksa->clinic_id));

			$this->data['doc_setting'] = array_group_by("code", $doc_setting, true);
			
			$this->data['data_periksa'] = $dt_periksa;
			$start_date = $dt_periksa->tgl_periksa;
			$end_date = $dt_periksa->tgl_periksa;
			$last_tarif = $this->report->load_last_tarif($dt_periksa->id_provider, $dt_periksa->id_jenis, date("Y-m-d"), date("Y-m-d"));
			$last_date = (sizeof($last_tarif) > 0) ? $last_tarif[0]->start_date : '';
			$tarif = $this->report->load_tarif($dt_periksa->id_provider, $dt_periksa->id_jenis, $start_date, $end_date, $last_date);
			$biaya = (!empty($tarif)) ? $tarif[0]->nominal : ((!empty($last_tarif)) ? $last_tarif[0]->nominal : 0);
			$this->data['biaya_pemeriksaan'] = $biaya;
			// looking for detail
			$this->data['category'] = $dt_periksa->category;
			if ($this->data['category'] == 'umum') {
				$item_periksa = $this->pemeriksaan->_search_item_jenis_pemeriksaan(array("jenis_id" => $dt_periksa->id_jenis));
				$subitem_periksa = $this->pemeriksaan->_search_subitem_jenis_pemeriksaan(array("jenis_id" => $dt_periksa->id_jenis));
				$item_hasil_periksa = $this->pemeriksaan->_searchItemHasilPeriksa(array("id_periksa"=>$dt_periksa->id,"jenis_id" => $dt_periksa->id_jenis));
				$subitem_hasil_periksa = $this->pemeriksaan->_searchSubItemHasilPeriksa(array("id_periksa"=>$dt_periksa->id,"jenis_id" => $dt_periksa->id_jenis));
				if (!empty($item_hasil_periksa)) $item_periksa = $item_hasil_periksa;
				if (!empty($subitem_hasil_periksa)) $subitem_periksa = $subitem_hasil_periksa;
				// echo "<pre>";
				// print_r($item_periksa);
				// print_r($subitem_periksa);
				// echo "</pre>";
				// die();
				$group_sub = groupBy($subitem_periksa, "item_id");
				$new_item_periksa = [];
				foreach ($item_periksa as $k => $dt_item) {
					$item = (object) $dt_item;
					$id = $item->sub_id;
					$item->hasil = (isset($item->hasil)) ? $item->hasil : "";
					if (isset($group_sub[$id])) {
						// contains sub
						$item->sub = $group_sub[$id];
					}
					array_push($new_item_periksa, $item);
				}
				// print_r($new_item_periksa);
				$detail = $new_item_periksa;
				// echo "</pre>";
			} else {
				$detail = $this->pemeriksaan->_search_detail($vid);
			}
			// die();
			$this->data['detail_periksa'] = $detail;
			$exp_idnotes = explode(",", $dt_periksa->id_notes);
			$notes = ($dt_periksa->id_notes != "") ? $this->pemeriksaan->_search_notes($exp_idnotes) : [];

			$this->data['list_hasil'] = $this->pemeriksaan->_get_list_hasil($dt_periksa->id_jenis);

			$list_notes = [];
			if (!empty($notes)) {
				for ($i = 0; $i < sizeof($exp_idnotes); $i++) {
					$idn = $exp_idnotes[$i];
					foreach ($notes as $k => $nl) {
						if ($nl->id == $idn) array_push($list_notes, $nl);
					}
				}
			}
			$this->data['data_notes'] = $list_notes;
			$this->data['status'] = "";
			if (isset($gets['status'])) $this->data['status'] = htmlentities($gets['status']);
			if (!isset($gets['pdf'])) {
				if (isset($gets['print-view'])) {
					$this->load->view(conf('path_module_lab') . 'pemeriksaan/print', $this->data);
				} else {
					if ($is_edit && $this->data['C_PV_GROUP'] == 'pusat') $this->data['editable'] = true;
					$this->template->load(get_template(), conf('path_module_lab') . 'pemeriksaan/view', $this->data);
				}
			} else {

				$this->load->library('ciqrcode');
				$uniq_code = createUniqCode($dt_periksa);
				$this->data['code'] = $uniq_code;
				// $vars = array(
				// 		'${tanggal}' => dateIndo($dt_periksa->update_hasil_at),
				// 		'${tgl_hasil}' => dateIndo($dt_periksa->update_hasil_at),
				// 		'${tanggal_hasil}' => dateIndo($dt_periksa->update_hasil_at),
				// 		'${tgl_update_hasil}' => dateIndo($dt_periksa->update_hasil_at),
				// 		'${nama_dokter}' => $dt_periksa->nama_dokter,
				// 		'${dokter}'        => $dt_periksa->nama_dokter,
				// 		'${nama_pasien}'    => $dt_periksa->nama_pasien,
				// 		'${pasien}'        => $dt_periksa->nama_pasien,
				// 		'${tgl_periksa}' => dateIndo($dt_periksa->tgl_periksa),
				// 	);

				$this->data['image_qr'] = generateQRCode(base_url('validation?id=' . base64_encode($uniq_code)), 2, null, 'hsl_' . $uniq_code);
				$dt_profile =  $this->other_set->get_com_profile(array("clinic_id" => $dt_periksa->clinic_id));
				$data_profile = $dt_profile[0];
				$this->data['doc_setting_profile'] = $data_profile;

				// $this->data['text_city_of_klinik'] = (isset($this->data['doc_setting']->text_city_of_klinik)) ? $this->data['doc_setting']->text_city_of_klinik->content : conf('lab_kota_praktek');
				$dt_profile =  $this->other_set->get_com_profile(array("clinic_id" => $dt_periksa->clinic_id));
				$data_profile = $dt_profile[0];
				$this->data['doc_setting_profile'] = $data_profile;

				if ($is_without_sign === FALSE) {
					// $this->data['ttd_hasil']='./assets/img/'.conf('path_module_lab').'sign-hasil.png'; // utk di label hasil
					$this->data['ttd_hasil'] = $this->data['doc_setting']->img_ttd_validator->path; // utk di label hasil
					// $this->data['logo_ttd']='./assets/img/'.conf('path_module_lab').'ttd-suket.png';
					$this->data['logo_ttd_suket'] = $this->data['doc_setting']->img_ttd_validator->path;
				}
				$mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'margin_footer' => 0]);
				$mpdf->showImageErrors = true;
				//$mpdf->SetTitle('');
				$day_berlaku = (isset($dt_periksa->masa_berlaku)) ? $dt_periksa->masa_berlaku : 0;
				$day_berlaku_opt = (isset($dt_periksa->masa_berlaku_opt)) ? $dt_periksa->masa_berlaku_opt : 'day';
				$this->data['masa_berlaku_desc'] = "";
				if ($day_berlaku >= 0) {
					$date_start = $dt_periksa->update_hasil_at;
					$day_month_indo = ($day_berlaku_opt == 'day') ? 'hari' : 'bulan';
					$day_month_english = ($day_berlaku_opt == 'day') ? 'days' : 'months';
					$this->data['masa_berlaku'] = "$day_berlaku $day_berlaku_opt";
					$this->data['masa_berlaku_indo'] = "$day_berlaku $day_month_indo";
					$this->data['masa_berlaku_english'] = "$day_berlaku $day_month_english";
					$end_date = dateIndo(date("Y-m-d", strtotime("$date_start +$day_berlaku $day_berlaku_opt")));
					$end_date_en = date("F d, Y", strtotime("$date_start +$day_berlaku $day_berlaku_opt"));
					if($day_berlaku>0){
					$this->data['masa_berlaku_desc'] = "<span>Masa berlaku surat ini s/d  tanggal $end_date</span>
					<br><i>The validity period of this letter is until $end_date_en </i>
					";
					}
				}
				$this->data['valid_doc_info'] = "<span>Scan QRCode dan klik link didalamnya untuk memastikan bahwa dokumen ini benar dikeluarkan oleh " . conf('lab_nama_klinik_id') . "</span>
				<br><i>Scan the QR Code and click the link in it to ensure that this document was issued by " . conf('lab_nama_klinik_en') . "</i>
				";
				$this->data['info_reg_kemenkes'] = "<span>" . conf('lab_nama_klinik_id') . " terdaftar sebagai Pemeriksa Antigen Covid 19 di Kementerian Kesehatan Republik Indonesia</span>
				<br><i>" . conf('lab_nama_klinik_en') . " is registered as a Covid 19 Antigen Examiner at the Ministry of Health of the Republic of Indonesia </i>
				";
				$page_title = $dt_periksa->jenis_pemeriksaan . "_" . $dt_periksa->id_pasien . "_" . $dt_periksa->nama_pasien;
				$this->data['page_title'] = $page_title;
				$mpdf->SetAuthor(conf('lab_nama_klinik_id'));
				$mpdf->SetCreator(conf('lab_nama_klinik_id'));
				$mpdf->SetTitle($page_title);
				$mpdf->SetSubject('HASIL PEMERIKSAAN - ' . strtoupper($dt_periksa->jenis_pemeriksaan));
				$mpdf->SetProtection(['print'], '', '--YM^21..');
				$margin_left = 0;
				$margin_right = 0;
				$margin_top = 30;
				$margin_bottom = 20;
				$margin_header_top = 0;
				$margin_footer_bottom = 0;
				$mpdf->addPage('P', '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);
				//$this->data['detail_periksa']=$this->pemeriksaan->_get_detail_hasil($dt_periksa->id_jenis,$dt_periksa->hasil);
				$html = $this->load->view(conf('path_module_lab') . 'pemeriksaan/print', $this->data, true);

				if (!$is_without_header)
					$mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="./' . $this->data['doc_setting']->img_doc_header->path . '"></div>', '', true);
				//<img src="'.base_url('/assets/img/ym-doc-footer.jpg').'">
				//$mpdf->SetHTMLFooter('<div class="page-footer-pdf"><div class="page-footer-text">'.$setting_doc->content.'</div></div>'); 
				// $mpdf->SetHTMLFooter('<div class="page-footer-pdf"><div class="page-footer-text">'.base_url('/assets/img/ym-doc-footer.png').'</div></div>'); 
				// $mpdf->Image(base_url('/assets/img/ym-doc-footer.png'),0,259,210,38,'png','',true,true,true);
				// $mpdf->Image(base_url('/assets/img/ym-doc-footer.png'), 0,1000,210, 38, 'png', '', true,true, true, false);
				if(isset($this->data['doc_setting']->img_doc_background_body) && $this->data['doc_setting']->img_doc_background_body->path!=""){
					$mpdf->SetDefaultBodyCSS('background', "url(".base_url($this->data['doc_setting']->img_doc_background_body->path).") no-repeat center center");
					$mpdf->SetDefaultBodyCSS('background-image-resize', 5);
				}
				if (!$is_without_header)
					$mpdf->SetHTMLFooter('<div class="page-header-pdfs"><img src="./' . $this->data['doc_setting']->img_doc_footer->path . '"></div>', '', false);

				$mpdf->defaultfooterline = 30;
				$mpdf->WriteHTML($html);
				// echo $html;	
				$mpdf->Output($page_title . ".pdf", 'I'); // opens in browser
				// $mpdf->Output($dt_periksa->no_test.".pdf",'D');
			}
		} else {
			$this->data['status'] = "";
			if (isset($gets['status'])) $this->data['status'] = htmlentities($gets['status']);
			$this->template->load(get_template(), conf('path_module_lab') . 'pemeriksaan/form', $this->data);
		}
	}
	public function search_notes_select2_()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		if ($gets['clinic_id'] == "allclinic") sendError("Klinik belum dipilih", []);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->pemeriksaan->_search_notes_select2($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function load_dt()
	{
		isAllowed($this->data['access_code_pemeriksaan']);
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		// print_r($this->data);
		// die();
		$provider_id = ($this->data['C_PV_GROUP'] != "pusat") ? $this->data['C_PV_ID'] : '';

		$data = $this->pemeriksaan->_load_dt($posted, $provider_id);
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
	public function save__()
	{
		isAllowed($this->data['access_code_pemeriksaan']);
		header('Content-Type: application/json');
		//isAllowed("c-privilege^create-update-user");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		foreach ($posted as $key => $value) {
			$$key = $value;
		}
		
		if (!isset($jam_sampling) || $jam_sampling == "") return sendError("Pastikan sudah set jam sampling");
		if (!preg_match('/(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/', $jam_sampling)) {
			return sendError("Format Jam Salah");
		}
		if(!isset($asuransi) || $asuransi==null) $asuransi="";
		if(!isset($perujuk) || $asuransi==null) $perujuk="";
		if($asuransi!="" && $no_asuransi=="") return sendError("No Asuransi belum diisi");
		if($perujuk!="" && $nama_tenaga_perujuk=="") return sendError("Nama perujuk belum diisi");
		//$hasil=(gettype($hasil)=='string') ? [$hasil] : $hasil;
		//$nilai_rujukan=(gettype($nilai_rujukan)=='string') ? [$nilai_rujukan] : $nilai_rujukan;
		//$metode=(gettype($metode)=='string') ? [$metode] : $metode;
		if (!isset($posted['ref_id']) || $posted['ref_id'] == "") {
			if ($posted['clinic_id'] == "allclinic") return sendError("Klinik belum dipilih");
			if (!isset($_id_pasien) || $_id_pasien == "") return sendError("Pastikan telah mengisi data pasien");
			if (!isset($nama_lengkap) && $nama_lengkap == "") return sendError("Pastikan telah mengisi data pasien");
			$test_no = $this->pemeriksaan->get_new_no_pemeriksaan($clinic_id);
			$provider = (isset($provider) && $provider != 'none') ? $provider : $this->data['C_PV_ID'];
			if (!isset($provider) || $provider == "") return sendError("Pastikan sudah memilih provider");
			if (!isset($dokter) || $dokter == "") return sendError("Pastikan sudah memilih dokter");
			if (!isset($tgl_sampling) || $tgl_sampling == "") return sendError("Pastikan sudah memilih tanggal sampling");

			if (!isset($jenis_sample) || $jenis_sample == "") return sendError("Pastikan sudah memilih jenis sample");
			//$id_periksa=(gettype($id_periksa)=='string') ? [$id_periksa] : $id_periksa;
			//if(!isset($id_periksa) || ($id_periksa=="" || empty($id_periksa))) return sendError("Jenis pemeriksaan belum diisi");
			// add new user
			$check_exist = $this->pemeriksaan->check_notest(array("clinic_id" => $clinic_id, "no_test" => $test_no));
			if ($check_exist == 'exist') {
				sendError(lang('msg_record_exist'));
			} //else
			$save = $this->pemeriksaan->_save(array(
				"clinic_id" => $clinic_id,
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
				"biaya" => (isset($biaya)) ? $biaya : 0,
				"created_by"=>$this->data['C_UID'],
				"asuransi" => $asuransi,
				"no_asuransi"   => $no_asuransi,
				"perujuk"       => $perujuk,
				"nama_tenaga_perujuk"   => $nama_tenaga_perujuk,
			), array());
			sendSuccess(lang('msg_insert_success'));
		} else {
			if (!isset($dokter) || $dokter == "") return sendError("Pastikan sudah memilih dokter");
			//if(!isset($tgl_sampling) || $tgl_sampling=="") return sendError("Pastikan sudah memilih tanggal sampling");
			if (!isset($tgl_sampling) || $tgl_sampling == "") return sendError("Tanggal sampling belum di set");
			if (!isset($jenis_sample) || $jenis_sample == "") return sendError("Pastikan sudah memilih jenis sample");
			// if (!isset($masa_berlaku) || $masa_berlaku == "" || $masa_berlaku == 0) return sendError("Masa berlaku surat belum ditentukan");
			if (!isset($masa_berlaku_opt) || $masa_berlaku_opt == "") return sendError("Masa berlaku surat belum ditentukan");
			if (isset($note_updates)) {
				$id_notes = (gettype($id_notes) == 'string') ? [$id_notes] : $id_notes;
				$join_notes = implode(",", $id_notes);
				$s = $this->pemeriksaan->_save(array("id_notes" => $join_notes), array("id" => $ref_id), "no_test");
			}
			// updating process
			if ($category == "covid") {
				$set_data = array(
					"hasil" => $hasil,
					"update_hasil_at" => date("Y-m-d H:i"),
					"id_dokter" => htmlentities(trim($dokter)),
					"status" => ($hasil != "") ? "SELESAI" : "",
					//"tgl_periksa"=>htmlentities(trim($tgl_sampling)),
					"jenis_sample" => htmlentities(trim($jenis_sample)),
					"tgl_sampling" => htmlentities(trim($tgl_sampling)) . " " . $jam_sampling,
					"masa_berlaku" => (int) htmlentities(trim($masa_berlaku)),
					"masa_berlaku_opt" => htmlentities(trim($masa_berlaku_opt)),
					"biaya" => (isset($biaya)) ? $biaya : 0,
					"update_hasil_by"=>$this->data['C_UID'],
					"asuransi" => $asuransi,
					"no_asuransi"   => $no_asuransi,
					"perujuk"       => $perujuk,
					"nama_tenaga_perujuk"   => $nama_tenaga_perujuk,
					//"keluhan"=>htmlentities(trim($keluhan))
				);
				$s = $this->pemeriksaan->_save($set_data, array("id" => $ref_id));
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
			} else { // end update covid
				// umum

				// $check=$this->jenis_pemeriksaan->checkJenis(array("jenis"=>$jenis_pemeriksaan,"clinic_id"=>$clinic_id));
				// if($check=='exist') return sendError("Jenis Pemeriksaan ini sudah tersedia");
				// $save_jenis=$this->jenis_pemeriksaan->saveJenisPemeriksaanNew(array(
				// 	"jenis"=>$jenis_pemeriksaan,
				// 	"clinic_id"=>$clinic_id,
				// 	"category"=>$kategori,
				// 	"metode"=>$metode,
				// 	"created_by"=>$this->data['C_UID']
				// ));
				$i = 0;
				$hsl = "";
				$this->pemeriksaan->deleteItemHasilPeriksa(array("id_periksa" => $ref_id));
				$this->pemeriksaan->deleteSubItemHasilPeriksa(array("id_periksa" => $ref_id));
				foreach ($posted['item_periksa_umum'] as $k => $arr_item) {
					$item = (object) $arr_item;
					if ($i == 0) $hsl = $item->hasil;
					// save item pemeriksaan level 1
					$save_item = $this->pemeriksaan->saveItemHasilPeriksa(array(
						"id_periksa" => $ref_id,
						"jenis_id" => $id_jenis_pemeriksaan,
						"item" => $item->name,
						"hasil" => $item->hasil,
						"nilai_rujukan" => $item->rujukan,
						"satuan" => $item->satuan,
					));
					// save subitem pemeriksaan - level 2
					if (isset($item->sub)) {
						foreach ($item->sub as $k2 => $arr_subitem) {
							$subitem = (object) $arr_subitem;
							$save_subitem = $this->pemeriksaan->saveSubItemHasilPeriksa(array(
								"id_periksa" => $ref_id,
								"jenis_id" => $id_jenis_pemeriksaan,
								"item_id" => $save_item,
								"item" => $subitem->name,
								"hasil" => $subitem->hasil,
								"nilai_rujukan" => $subitem->rujukan,
								"satuan" => $subitem->satuan,
							));
						}
					}
					$i++;
				}
				$set_data = array(
					"hasil" => strtoupper($hsl),
					"update_hasil_at" => date("Y-m-d H:i"),
					"id_dokter" => htmlentities(trim($dokter)),
					"status" => ($hsl != "") ? "SELESAI" : "",
					//"tgl_periksa"=>htmlentities(trim($tgl_sampling)),
					"jenis_sample" => htmlentities(trim($jenis_sample)),
					"tgl_sampling" => htmlentities(trim($tgl_sampling)) . " " . $jam_sampling,
					"masa_berlaku" => (int) htmlentities(trim($masa_berlaku)),
					"masa_berlaku_opt" => htmlentities(trim($masa_berlaku_opt)),
					"biaya" => (isset($biaya)) ? $biaya : 0,
					"update_hasil_by"=>$this->data['C_UID']
					//"keluhan"=>htmlentities(trim($keluhan))
				);
				$s = $this->pemeriksaan->_save($set_data, array("id" => $ref_id));
			}
			if ($s > 0 || $save > 0) {
				// save to billing
				if($hasil!=""){
					$save_billing=$this->pemeriksaan->save_billing($clinic_id,$ref_id,$biaya,0,$this->data['C_UID']);
				}
				sendSuccess(lang('msg_update_success'));
			} else {
				sendError("Gagal. Silakan coba lagi");
			}
		}
	}
	public function cancel__($id = '')
	{
		header('Content-Type: application/json');
		isAllowed($this->data['access_code_pemeriksaan']);
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
		isAllowed($this->data['access_code_pemeriksaan']);
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
		$gets = modify_post($gets);
		$id = ($id != '') ? $id : $gets['id'];
		header('Content-Type: application/json');
		$search = $this->pemeriksaan->_search_jenis_sample(array("id" => $id, "clinic_id" => $gets['clinic_id']));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
	public function load_dt_jenis_sample()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->pemeriksaan->_load_dt_jenis_sample($posted);
		echo json_encode($data);
	}
	public function save_jenis_sample__()
	{
		header('Content-Type: application/json');
		//isAllowed("c-privilege^create-update-user");
		isAllowed($this->data['access_code_pemeriksaan']);
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		$posted = modify_post($posted);
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		if ($nama_sampling == "") return sendError("Nama Sample Wajib diisi");
		if (!isset($posted['_id']) || $posted['_id'] == "") {
			// add new user
			$save = $this->pemeriksaan->_save_jenis_sample(array(
				"clinic_id" => $clinic_id,
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
				"clinic_id" => $clinic_id,
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
		isAllowed($this->data['access_code_pemeriksaan']);
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
		$gets = modify_post($gets);
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
		$posted = modify_post($posted);
		$data = $this->pemeriksaan->_load_dt_notes($posted);
		echo json_encode($data);
	}
	public function save_notes__()
	{
		header('Content-Type: application/json');
		isAllowed($this->data['access_code_pemeriksaan']);
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		$posted = modify_post($posted);
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		if ($notes == "") return sendError("Notes Wajib diisi");
		if (!isset($posted['_id']) || $posted['_id'] == "") {
			// add new 
			$save = $this->pemeriksaan->_save_notes(array(
				"clinic_id" => $clinic_id,
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
			// update existing
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
		isAllowed($this->data['access_code_pemeriksaan']);
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
	public function reset_hasil_pemeriksaan()
	{
		header('Content-Type: application/json');
		isAllowed($this->data['access_code_pemeriksaan']);
		$method = $this->input->method(true);
		if ($method != "DELETE") sendError(lang('msg_method_delete_required'), [], 405);
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		if (!isset($posted['pid'])) return sendError("ID Salah");
		$del = $this->pemeriksaan->_reset_item_hasil_periksa($posted['pid']);
		if ($del > 0) sendSuccess("Berhasil di reset");
		sendError("Hasil belum diupdate, tidak perlu reset. silahkan update");
	}
}

/* End of file Pemeriksaan.php */
