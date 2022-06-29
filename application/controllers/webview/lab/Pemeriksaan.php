<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pemeriksaan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->data=isAuthorizedPasien();
		if(!$this->data) redirect('webview/auth');
		$this->load->library("datatables");
		$this->lang->load(conf('path_module_lab').'pemeriksaan', $this->session->userdata('site_lang'));
		$this->load->model(conf('path_module_lab')."Pasien_model","pasien");
		$this->load->model(conf('path_module_lab')."Pemeriksaan_model","pemeriksaan");
		$this->load->model(conf('path_module_lab')."Jenispemeriksaan_model","jenis_pemeriksaan");
		$this->load->model(conf('path_module_lab')."Report_model","report");
		$this->load->model("/admin/Other_setting_model", "other_set");
		$this->asset_path='../../assets';
	}
	public function index()
	{
		$this->data["web_title"]=lang('app_name_short'). "Data Pemeriksaan";
		$this->data["page_title"]=lang('page_title');
		$this->data['js_control']="lab/pemeriksaan/history.js";
		$this->data['datatable']=false;
		$gets=$this->input->get();
		$this->data['status']="";
		if(isset($gets['status'])) $this->data['status']=htmlentities($gets['status']);
		$filter="";
		if(isset($gets['m'])){
			$getm=$gets['m'];
			$no=(int) preg_replace('/[^0-9]/', '', $getm);
			$dm=strtolower(preg_replace('/[^a-zA-Z]/', '', $getm));
			if($dm=="d"){
				$dm="DAY";
			}else
			if($dm=="m"){
				$dm="MONTH";
			} else
			if($dm=="y"){
				$dm="YEAR";
			}
			$filter="$no $dm";
			if($dm=="") $filter="14 DAY";
			$this->data['getm']=$gets['m'];
		}
		$recently_pemeriksaan=$this->pemeriksaan->_list_pemeriksaan_pasien($this->data['PSID'],$filter);
		// $history_pemeriksaan=$this->pemeriksaan->_list_pemeriksaan_pasien($this->data['PSID']);
		// echo '<pre>';
		// print_r($recently_pemeriksaan);
		// echo '</pre>';
		$this->data['recently_pemeriksaan']=$recently_pemeriksaan;
		$this->template->load(get_template('webview'),'webview/pemeriksaan/history',$this->data);
	}
	public function kwitansi()
	{
		$this->data["web_title"]=lang('app_name_short'). "Data Kwitansi";
		$this->data["page_title"]=lang('page_title');
		$this->data['js_control']="lab/pemeriksaan/kwitansi.js";
		$this->data['datatable']=false;
		$gets=$this->input->get();
		$this->data['status']="";
		if(isset($gets['status'])) $this->data['status']=htmlentities($gets['status']);
		$filter="";
		if(isset($gets['m'])){
			$getm=$gets['m'];
			$no=(int) preg_replace('/[^0-9]/', '', $getm);
			$dm=strtolower(preg_replace('/[^a-zA-Z]/', '', $getm));
			if($dm=="d"){
				$dm="DAY";
			}else
			if($dm=="m"){
				$dm="MONTH";
			} else
			if($dm=="y"){
				$dm="YEAR";
			}
			$filter="$no $dm";
			if($dm=="") $filter="14 DAY";
			$this->data['getm']=$gets['m'];
		}
		$recently_pemeriksaan=$this->pemeriksaan->_list_kwitansi_pasien($this->data['PSID'],$filter);
		// $history_pemeriksaan=$this->pemeriksaan->_list_pemeriksaan_pasien($this->data['PSID']);
		// echo '<pre>';
		// print_r($recently_pemeriksaan);
		// echo '</pre>';
		$this->data['recently_pemeriksaan']=$recently_pemeriksaan;
		$this->template->load(get_template('webview'),'webview/pemeriksaan/kwitansi',$this->data);
	}
	public function load_dt(){
		isAllowed($this->data['access_code_pemeriksaan']);
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted=$this->input->input_stream();
		// print_r($this->data);
		// die();
		$provider_id=($this->data['C_PV_GROUP']!="pusat") ? $this->data['C_PV_ID'] : '';
		
		$data=$this->pemeriksaan->_load_dt($posted,$provider_id);
		echo json_encode($data);
	}
	public function search__($id=''){
			$gets=$this->input->get();
			$id=($id!='') ? $id : $gets['id'];
			header('Content-Type: application/json');
			$search=$this->pemeriksaan->_search(array("id"=>$id));
			if(empty($search)) sendError(lang('msg_no_record'));
			echo json_encode(array("data"=>$search[0]));
	}
	public function search_jenis_pemeriksaan_select2_(){
		header('Content-Type: application/json');
		$gets=$this->input->get();
		$key=(isset($gets['search']) && $gets['search']!='') ? $gets['search'] : "";
		$search=$this->jenis_pemeriksaan->_search_select2($key,[]);
		
		echo json_encode($search);
	}
	public function search_jenis_pemeriksaan_select2_with_cost(){
		header('Content-Type: application/json');
		$gets=$this->input->get();
		$key=(isset($gets['search']) && $gets['search']!='') ? $gets['search'] : "";
		$search=$this->jenis_pemeriksaan->_search_select2_with_cost($key,[]);
		
		echo json_encode($search);
	}
	private function getNewTestNo($string=''){
		$created=FALSE;
		do{
			$no_test=date("y-").strtoupper(substr(str_shuffle("0123456789aAbBcCdDeEfFgGhHiIjJkKlKmMnNqQrRsStTvVwWxXyYzZ".$string), 0, 6));
			$check=$this->pemeriksaan->checkNoTest(($no_test));
			if(empty($check)){
				$created=TRUE;
				return $no_test;
			}
		} while($created==TRUE);
		
	}
	public function save__(){
		isAllowed($this->data['access_code_pemeriksaan']);
		header('Content-Type: application/json');
		//isAllowed("c-privilege^create-update-user");
		$method=$this->input->method(true);
		if($method!="POST" && $method!="PUT") sendError(lang('msg_method_post_put_required'), [],405);
		$posted=$this->input->post();
		foreach($posted as $key=>$value){ $$key=$value; }
		if(!isset($jam_sampling) || $jam_sampling=="") return sendError("Pastikan sudah set jam sampling");
		if(!preg_match('/(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/', $jam_sampling)){
			return sendError("Format Jam Salah");
		}
		//$hasil=(gettype($hasil)=='string') ? [$hasil] : $hasil;
		//$nilai_rujukan=(gettype($nilai_rujukan)=='string') ? [$nilai_rujukan] : $nilai_rujukan;
		//$metode=(gettype($metode)=='string') ? [$metode] : $metode;
		if(!isset($posted['ref_id']) || $posted['ref_id']==""){
			if(!isset($_id_pasien) || $_id_pasien=="") return sendError("Pastikan telah mengisi data pasien");
			if(!isset($nama_lengkap) && $nama_lengkap=="") return sendError("Pastikan telah mengisi data pasien");
			$test_no=$this->pemeriksaan->get_new_no_pemeriksaan();
			$provider=(isset($provider) && $provider!='none') ? $provider : $this->data['C_PV_ID'];  
			if(!isset($provider) || $provider=="") return sendError("Pastikan sudah memilih provider");
			if(!isset($dokter) || $dokter=="") return sendError("Pastikan sudah memilih dokter");
			if(!isset($tgl_sampling) || $tgl_sampling=="") return sendError("Pastikan sudah memilih tanggal sampling");
			
			if(!isset($jenis_sample) || $jenis_sample=="") return sendError("Pastikan sudah memilih jenis sample");
			//$id_periksa=(gettype($id_periksa)=='string') ? [$id_periksa] : $id_periksa;
			//if(!isset($id_periksa) || ($id_periksa=="" || empty($id_periksa))) return sendError("Jenis pemeriksaan belum diisi");
				// add new user
				$save=$this->pemeriksaan->_save(array(
						"no_test"=>$test_no,
						"id_pasien"=>$_id_pasien,
						"nama_pasien"=>$nama_lengkap,
						"id_provider"=>htmlentities(trim($provider)),
						"id_dokter"=>htmlentities(trim($dokter)),
						"tgl_periksa"=>date("Y-m-d"),
						"tgl_sampling"=>htmlentities(trim($tgl_sampling))." ".$jam_sampling,
						"jenis_sample"=>htmlentities(trim($jenis_sample)),
						//"keluhan"=>htmlentities(trim($keluhan)),
						"jenis_pemeriksaan"=>htmlentities(trim($jenis_pemeriksaan)),
						"biaya"=>(isset($biaya)) ? $biaya: 0
				),array(),"no_test");
				if($save=='exist'){
						sendError(lang('msg_record_exist'));  
				}else{
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
		}else{
				if(!isset($dokter) || $dokter=="") return sendError("Pastikan sudah memilih dokter");
				//if(!isset($tgl_sampling) || $tgl_sampling=="") return sendError("Pastikan sudah memilih tanggal sampling");
				if(!isset($tgl_sampling) || $tgl_sampling=="") return sendError("Tanggal sampling belum di set");
				if(!isset($jenis_sample) || $jenis_sample=="") return sendError("Pastikan sudah memilih jenis sample");
				if(!isset($masa_berlaku) || $masa_berlaku=="" || $masa_berlaku==0) return sendError("Masa berlaku surat belum ditentukan");  
				if(!isset($masa_berlaku_opt) || $masa_berlaku_opt=="") return sendError("Masa berlaku surat belum ditentukan");  
				if(isset($note_updates)){
					$id_notes=(gettype($id_notes)=='string') ? [$id_notes] : $id_notes;
					$join_notes=implode(",",$id_notes);
					$s=$this->pemeriksaan->_save(array("id_notes"=>$join_notes),array("id"=>$ref_id),"no_test");
				}
				$set_data=array(
				"hasil"=>$hasil,
				"update_hasil_at"=>date("Y-m-d H:i"),
				"id_dokter"=>htmlentities(trim($dokter)),
				"status"=>($hasil!="") ? "SELESAI" : "",
				//"tgl_periksa"=>htmlentities(trim($tgl_sampling)),
				"jenis_sample"=>htmlentities(trim($jenis_sample)),
				"tgl_sampling"=>htmlentities(trim($tgl_sampling))." ".$jam_sampling,
				"masa_berlaku"=>(int) htmlentities(trim($masa_berlaku)),
				"masa_berlaku_opt"=>htmlentities(trim($masa_berlaku_opt)),
				"biaya"=>(isset($biaya)) ? $biaya: 0
				//"keluhan"=>htmlentities(trim($keluhan))
				);
				$s=$this->pemeriksaan->_save($set_data,array("id"=>$ref_id),"no_test");				
				$data_detail=[];
				$save=0;
				if(isset($nama_pemeriksaan)){
					foreach($nama_pemeriksaan as $i=>$v){
						array_push($data_detail,array(
						"id_pemeriksaan"=>$ref_id,
						"nama_pemeriksaan"=>$v,
						"id_jenis"=>htmlentities(trim($id_jenis_pemeriksaan)),
						"id_sampling"=>$jenis_sample,
						"hasil"=>htmlentities(trim($hasil_periksa[$i])),
						"nilai_rujukan"=>htmlentities(trim($nilai_rujukan[$i])),
						"metode"=>htmlentities(trim($metode[$i]))
						));
					}
					$dl=$this->pemeriksaan->_delete_detail_pemeriksaan(array("id_pemeriksaan"=>$ref_id));
				// save detail pemeriksaan
					$save=$this->pemeriksaan->_save_detail($data_detail,array(),"id_pemeriksaan");
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
				if($s>0 || $save>0){
					sendSuccess(lang('msg_update_success'));
				}else{
					sendError("Gagal. Silakan coba lagi");
				}
				
		}
	}
	public function print()
	{
		// isAllowed($this->data['access_code_pemeriksaan']);
		$gets=$this->input->get();
		$is_edit=FALSE;
		$is_without_header=FALSE;
		
		// update 2021-08-17
		$doc_setting=$this->other_set->get_setting_doc_requirements();
		$this->data['doc_setting']=array_group_by("code",$doc_setting,true);
		$is_without_sign=FALSE;
		if(isset($gets['viewid']) && isset($gets['tn'])){
			$vid=htmlentities($gets['viewid']);
			$periksa=$this->pemeriksaan->_search((int) htmlentities($vid),htmlentities((int) trim($gets['tn'])));
			if(empty($periksa)) die("Data Not exist");
			$dt_periksa=$periksa[0];
			
			$this->data['data_periksa']=$dt_periksa;
			$start_date=$dt_periksa->tgl_periksa;$end_date=$dt_periksa->tgl_periksa;
			$last_tarif=$this->report->load_last_tarif($dt_periksa->id_provider,$dt_periksa->id_jenis,date("Y-m-d"),date("Y-m-d"));
			$last_date=(sizeof($last_tarif)>0) ? $last_tarif[0]->start_date : '';
			$tarif=$this->report->load_tarif($dt_periksa->id_provider,$dt_periksa->id_jenis,$start_date,$end_date,$last_date);
			$biaya=(empty($tarif)) ? $last_tarif[0]->nominal : $tarif[0]->nominal;
			$this->data['biaya_pemeriksaan']=$biaya;
			$detail=$this->pemeriksaan->_search_detail($vid);
			$this->data['detail_periksa']=$detail;
			$exp_idnotes=explode(",",$dt_periksa->id_notes);
			$notes=($dt_periksa->id_notes!="") ? $this->pemeriksaan->_search_notes($exp_idnotes) : [];
			$this->data['list_hasil']=$this->pemeriksaan->_get_list_hasil($dt_periksa->id_jenis);
			$list_notes=[];
			if(!empty($notes)){
				for($i=0;$i<sizeof($exp_idnotes);$i++){
					$idn=$exp_idnotes[$i];
					foreach($notes as $k=>$nl){
						if($nl->id==$idn) array_push($list_notes,$nl);
					}
				}
			}
			$this->data['data_notes']=$list_notes;
			$this->data['status']="";
			if(isset($gets['status'])) $this->data['status']=htmlentities($gets['status']);
			$this->load->library('ciqrcode');
			$setting_qr=$this->pemeriksaan->_get_setting('qrcontent-print');
			// $setting_doc=$this->pemeriksaan->_get_setting('doc-footer-text');
			$config['cacheable']    = false; //boolean, the default is true
			$config['cachedir']     = './assets/img/'; //string, the default is application/cache/
			$config['errorlog']     = './assets/img/'; //string, the default is application/logs/
			$config['imagedir']     = './assets/img/'.conf('path_module_lab'); //direktori penyimpanan qr code
			$config['quality']      = true; //boolean, the default is true
			$config['size']         = '1024'; //interger, the default is 1024
			$config['black']        = array(224,255,255); // array, default is array(255,255,255)
			$config['white']        = array(70,130,180); // array, default is array(0,0,0)
			$this->ciqrcode->initialize($config);
			$logopath = './assets/img/'.conf('path_module_lab').'logo-yuliarpan-medika-w.png';
			$name_qr='qr_mark';
			$image_name=$name_qr.'.png'; //buat name dari qr code sesuai dengan nip
			$fullpath = './assets/img/'.conf('path_module_lab').$image_name;
			// echo '<pre>';
			// print_r($dt_periksa);
			// echo '</pre>';
			// die();
			$uniq_code=createUniqCode($dt_periksa);
			// echo $uniq_code;
			// echo "<br>";
			// $extract=extractUniqCode($uniq_code);
			// print_r($extract);
			// die();
			$params['data'] = $setting_qr->content;
			$params['data'] = base_url(conf('path_module_lab').'checkdoc?no='.$uniq_code);
			$this->data['code']=$uniq_code;
			$params['level'] = 'H'; //H=High
			$params['size'] = (isset($setting_qr->size) && $setting_qr->size>0) ? $setting_qr->size :  3;
			$params['savename'] = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/images/
			$this->ciqrcode->generate($params);
			$QR = imagecreatefrompng($fullpath);
			// memulai menggambar logo dalam file qrcode
			$logo = imagecreatefromstring(file_get_contents($logopath));
			imagecolortransparent($logo , imagecolorallocatealpha($logo , 0, 0, 0, 127));
			imagealphablending($logo , false);
			imagesavealpha($logo , true);
			$QR_width = imagesx($QR);//get logo width
			$QR_height = imagesy($QR);//get logo width
			$logo_width = imagesx($logo);
			$logo_height = imagesy($logo);
			// Scale logo to fit in the QR Code
			$logo_qr_width = $QR_width/3.8;
			$scale = $logo_width/$logo_qr_width;
			$logo_qr_height = $QR_height/3.8;
			$dsx=($QR_width-$logo_qr_width)/2;
			$dsy=($QR_height-$logo_qr_height)/2;
			// imagecopyresampled($QR, '', $dsx, $dsy, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
				// Simpan kode QR lagi, dengan logo di atasnya
			imagepng($QR,$fullpath);
			
			if($setting_qr->content!=''){
				$this->data['image_qr']='./assets/img/'.conf('path_module_lab').$image_name;
			}
			$this->data['text_city_of_klinik']=(isset($this->data['doc_setting']->text_city_of_klinik)) ? $this->data['doc_setting']->text_city_of_klinik->content : conf('lab_kota_praktek');
			if($is_without_sign===FALSE){
				// $this->data['ttd_hasil']='./assets/img/'.conf('path_module_lab').'sign-hasil.png'; // utk di label hasil
				$this->data['ttd_hasil']=$this->data['doc_setting']->img_ttd_validator->path; // utk di label hasil
				// $this->data['logo_ttd']='./assets/img/'.conf('path_module_lab').'ttd-suket.png';
				$this->data['logo_ttd_suket']=$this->data['doc_setting']->img_ttd_validator->path;
			}
			$mpdf = new \Mpdf\Mpdf(['format' => 'A4','margin_footer'=>0]);
			$mpdf->showImageErrors = true;
			//$mpdf->SetTitle('');
			$day_berlaku=(isset($dt_periksa->masa_berlaku)) ? $dt_periksa->masa_berlaku : 0;
			$day_berlaku_opt=(isset($dt_periksa->masa_berlaku_opt)) ? $dt_periksa->masa_berlaku_opt : 'day';
			$this->data['masa_berlaku_desc']="";
			if($day_berlaku>0){
				$date_start=$dt_periksa->update_hasil_at;
				$day_month_indo=($day_berlaku_opt=='day') ? 'hari': 'bulan';
				$day_month_english=($day_berlaku_opt=='day') ? 'days': 'months';
				$this->data['masa_berlaku']="$day_berlaku $day_berlaku_opt";
				$this->data['masa_berlaku_indo']="$day_berlaku $day_month_indo";
				$this->data['masa_berlaku_english']="$day_berlaku $day_month_english";
				$end_date=dateIndo(date("Y-m-d",strtotime("$date_start +$day_berlaku $day_berlaku_opt")));
				$end_date_en=date("F d, Y",strtotime("$date_start +$day_berlaku $day_berlaku_opt"));
				$this->data['masa_berlaku_desc']="<span>Masa berlaku surat ini s/d  tanggal $end_date</span>
				<br><i>The validity period of this letter is until $end_date_en </i>
				";
			}
			$this->data['valid_doc_info']="<span>Scan QRCode dan klik link didalamnya untuk memastikan bahwa dokumen ini benar dikeluarkan oleh ".conf('lab_nama_klinik_id')."</span>
			<br><i>Scan the QR Code and click the link in it to ensure that this document was issued by ".conf('lab_nama_klinik_en')."</i>
			";
			$this->data['info_reg_kemenkes']="<span>".conf('lab_nama_klinik_id')." terdaftar sebagai Pemeriksa Antigen Covid 19 di Kementerian Kesehatan Republik Indonesia</span>
			<br><i>".conf('lab_nama_klinik_en')." is registered as a Covid 19 Antigen Examiner at the Ministry of Health of the Republic of Indonesia </i>
			";
			$page_title=$dt_periksa->jenis_pemeriksaan."_".$dt_periksa->id_pasien."_".$dt_periksa->nama_pasien;
			$this->data['page_title']=$page_title;
			$mpdf->SetAuthor(conf('lab_nama_klinik_id'));
			$mpdf->SetCreator(conf('lab_nama_klinik_id'));
			$mpdf->SetTitle($page_title);
			$mpdf->SetSubject('HASIL PEMERIKSAAN - '.strtoupper($dt_periksa->jenis_pemeriksaan));
			$mpdf->SetProtection(['print'],'','--YM^21..');
			$margin_left=0; $margin_right=0;$margin_top=45; $margin_bottom=20; $margin_header_top=0;$margin_footer_bottom=0;
			$mpdf->addPage('P','','','','',$margin_left,$margin_right,$margin_top,$margin_header_top,$margin_footer_bottom);
			//$this->data['detail_periksa']=$this->pemeriksaan->_get_detail_hasil($dt_periksa->id_jenis,$dt_periksa->hasil);
			$html = $this->load->view(conf('path_module_lab').'pemeriksaan/print',$this->data,true);
			
			if(!$is_without_header)
				$mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="./'.$this->data['doc_setting']->img_doc_header->path.'"></div>','',true);
			//<img src="'.base_url('/assets/img/ym-doc-footer.jpg').'">
			//$mpdf->SetHTMLFooter('<div class="page-footer-pdf"><div class="page-footer-text">'.$setting_doc->content.'</div></div>'); 
			// $mpdf->SetHTMLFooter('<div class="page-footer-pdf"><div class="page-footer-text">'.base_url('/assets/img/ym-doc-footer.png').'</div></div>'); 
			// $mpdf->Image(base_url('/assets/img/ym-doc-footer.png'),0,259,210,38,'png','',true,true,true);
			// $mpdf->Image(base_url('/assets/img/ym-doc-footer.png'), 0,1000,210, 38, 'png', '', true,true, true, false);
			
			if(!$is_without_header)
				$mpdf->SetHTMLFooter('<div class="page-header-pdfs"><img src="./'.$this->data['doc_setting']->img_doc_footer->path.'"></div>','',false);

			$mpdf->defaultfooterline=30;
			$mpdf->WriteHTML($html);
			$mpdf->Output($page_title.".pdf", 'I'); // opens in browser
			// $mpdf->Output($dt_periksa->no_test.".pdf",'D');
		}
	}
	public function registrasi(){
		// untuk registrasi pemeriksaan
		$data['company_logo']=base_url(conf('company_logo'));
		$this->data['js_control']="lab/pemeriksaan/registrasi.js"; // assets js is in /assets/webview/js
		$this->template->load(get_template('webview'), 'webview/pemeriksaan/registrasi', $this->data);
	}
	
	public function get_active_lang(){
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
	// proses register from webview
	public function save_self_register(){
		header('Content-Type: application/json');
		$method=$this->input->method(true);
		if($method!="POST" && $method!="PUT") sendError(lang('msg_method_post_put_required'), [],405);
		$posted=$this->input->post();
		foreach($posted as $key=>$value){
			$$key=htmlentities(trim($value));
		}
		if(!isset($tgl_periksa) || $tgl_periksa=="") return sendError("Pilih tanggal pemeriksaan");
		if(!isset($jenis_pemeriksaan) || $jenis_pemeriksaan=="") return sendError("Pilih jenis pemeriksaan");
		// if(!isset($kategori_pemeriksaan) || $kategori_pemeriksaan=="") return sendError("Pilih Kategori");
		if($nama_lengkap=="") return sendError("Nama Lengkap Wajib diisi");
		if($nik=="") return sendError("NIK Wajib diisi");
		if(!isset($jenis_kelamin)) return sendError("Jenis kelamin wajib dipilih");
		if($tempat_lahir=="") return sendError("Tempat Lahir Wajib diisi");
		if($tgl_lahir=="") return sendError("Tgl Lahir Wajib diisi");
		if($no_hp=="") return sendError("No HP wajib diisi");
		if($alamat=="") return sendError("Alamat Wajib diisi");
		if($jam_periksa=="" || strlen($jam_periksa)!=5 || $jam_periksa=="00:00") return sendError("Masukkan estimasi jam periksa anda");
		$new_no_anggota=$this->pasien->get_new_no_pasien();
		$save=$this->pasien->_save(array(
				// "provider_id"=>$provider,
				"no_identitas"=>$nik,
				"nama_lengkap"=>$nama_lengkap,
				"nomor_rm"=>$new_no_anggota,
				"jenis_kelamin"=>$jenis_kelamin,
				"tempat_lahir"=>$tempat_lahir,
				"tgl_lahir"=>$tgl_lahir,
				"alamat"=>$alamat,
				"no_hp"=>$no_hp,
				"email"=>$email,
				"self_register"=>1,
				"verified"=>0,
				"reg_pemeriksaan"=>$jenis_pemeriksaan,
				// "kat_pemeriksaan"=>$kategori_pemeriksaan,
				"akun_id_pasien"=>$this->data['PSID'],
				"reg_as"=>$reg_as,
		),array(),"no_identitas");
		$test_no=$this->pemeriksaan->get_new_no_pemeriksaan();
		if($save=='exist'){
				// NIK sudah terdaftar, lanjut simpan pemeriksaan
				// first search id pasien
				$search_id=$this->pasien->_searchid_by_nik($nik);
				$dt=$search_id[0];
				if($dt->akun_id_pasien!=$this->data['PSID']) sendError("NIK sudah terdaftar");
				$save_periksa=$this->pemeriksaan->_save(array(
						"no_test"=>$test_no,
						"id_pasien"=>$dt->id_pasien,
						"nama_pasien"=>strtoupper($nama_lengkap),
						"id_provider"=>1,
						"id_dokter"=>0,
						"tgl_periksa"=>$tgl_periksa,
						"plan_tgl_periksa"=>$tgl_periksa,
						"plan_jam_periksa"=>$jam_periksa,
						"kode_sales"=>$kode_sales,
						"tgl_sampling"=>"0000-00-00",
						"jenis_sample"=>"",
						"jenis_pemeriksaan"=>htmlentities(trim($jenis_pemeriksaan)),
						"biaya"=> 0,
						"self_register"=>1,
						"verified"=>0
				),array(),"no_test");
				sendSuccess("Registrasi berhasil. menunggu verifikasi admin");
		}else{
				if($save==0) sendError(lang('msg_insert_failed'));
				$save_periksa=$this->pemeriksaan->_save(array(
						"no_test"=>$test_no,
						"id_pasien"=>$save,
						"nama_pasien"=>strtoupper($nama_lengkap),
						"id_provider"=>1,
						"id_dokter"=>0,
						"tgl_periksa"=>$tgl_periksa,
						"plan_tgl_periksa"=>$tgl_periksa,
						"plan_jam_periksa"=>$jam_periksa,
						"kode_sales"=>$kode_sales,
						"tgl_sampling"=>"0000-00-00",
						"jenis_sample"=>"",
						"jenis_pemeriksaan"=>htmlentities(trim($jenis_pemeriksaan)),
						"biaya"=> 0,
						"self_register"=>1,
						"verified"=>0
				),array(),"no_test");
				sendSuccess("Registrasi berhasil. menunggu verifikasi admin");
		}
	}
}

/* End of file Pemeriksaan.php */
