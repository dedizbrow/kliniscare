<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Pasien extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		$this->load->library("datatables");
		$this->lang->load(conf('path_module_lab') . 'pasien', $this->session->userdata('site_lang'));
		$this->load->model(conf('path_module_lab') . "Pasien_model", "pasien");
		$this->load->model(conf('path_module_lab') . "Pemeriksaan_model", "pemeriksaan");
		$this->load->model(conf('path_module_lab') . "Jenispemeriksaan_model", "jenis");

		isAllowed("lab::pasien");
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . "Data Pasien";
		$this->data["page_title"] = lang('page_title');
		$this->data['js_control'] = conf('path_module_lab') . "data-pasien.js";
		$this->data['datatable'] = true;
		$gets = $this->input->get();
		$this->data['status'] = "";
		if (isset($gets['import'])) $this->data['import_id'] = $gets['import'];
		if (isset($gets['status'])) $this->data['status'] = htmlentities($gets['status']);
		$this->template->load(get_template(), conf('path_module_lab') . 'data-pasien', $this->data);
	}

	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted=modify_post($posted);
		$provider_id = ($this->data['C_PV_GROUP'] == 'pusat') ? 'pusat' : $this->data['C_PV_ID'];
		$posted['PV_GROUP'] = $this->data['C_PV_GROUP'];
		$data = $this->pasien->_load_dt($posted, $provider_id, $this->data['C_PV_ID']);
		echo json_encode($data);
	}

	public function search__($id = '')
	{
		$gets = $this->input->get();
		$gets=modify_post($gets);
		$id = ($id != '') ? $id : $gets['id'];
		header('Content-Type: application/json');
		//$search=$this->pasien->_search(array("a.id"=>$id));
		$id = htmlentities(trim($id));
		$search = $this->pasien->_search($id);
		$data_periksa = $this->pemeriksaan->_search_last_pasien_periksa($id,$gets['clinic_id']);
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0], "periksa" => $data_periksa));
	}
	public function select2_()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets=modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$provider = (isset($gets['provider'])) ? $gets['provider'] : '';
		if ($provider == 'none') $provider = $this->data['C_PV_ID'];
		//if($provider=='' && $this->data['C_PV_GROUP']=='pusat') return sendError("Pilih provider");
		$search = $this->pasien->_search_select2($key, $provider,$gets['clinic_id']);
		echo json_encode($search);
	}
	public function save__()
	{
		header('Content-Type: application/json');
		//isAllowed("c-privilege^create-update-user");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		$posted=modify_post($posted);
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		if($clinic_id=='allclinic') return sendError("Klinik belum dipilih");
		if ($no_identitas == "") return sendError("No identitas Wajib diisi");
		if ($kewarganegaraan == "") return sendError("Kewarganegaraan Wajib diisi");
		if ($nama_lengkap == "") return sendError("Nama Pasien Wajib diisi");
		if (!isset($jenis_kelamin)) return sendError("Jenis kelamin wajib dipilih");
		if ($tempat_lahir == "") return sendError("Tempat Lahir Wajib diisi");
		if ($tgl_lahir == "") return sendError("Tgl Lahir Wajib diisi");
		if ($alamat == "") return sendError("Alamat Wajib diisi");
		if ($tgl_sampling == "") return sendError("Tgl. Sampling Wajib diisi");
		if(!isset($asuransi) || $asuransi==null) $asuransi="";
		if(!isset($perujuk) || $asuransi==null) $perujuk="";
		if($asuransi!="" && $no_asuransi=="") return sendError("No Asuransi belum diisi");
		if($perujuk!="" && $nama_tenaga_perujuk=="") return sendError("Nama perujuk belum diisi");
		$provider_id = (isset($posted['provider_id']) && $this->data['C_PV_GROUP'] == 'pusat' && $posted['provider_id'] != "") ? $posted['provider_id'] : $this->data['C_PV_ID'];
		if (!isset($posted['_id']) || $posted['_id'] == "") {
			// add new user
			$new_nomor_rm = $this->pasien->get_new_no_pasien($clinic_id);
			$save = $this->pasien->_save(array(
				"clinic_id" => $clinic_id,
				"provider_id" => $provider_id,
				"create_at" => date("Y-m-d"),
				"no_identitas" => $no_identitas,
				"kewarganegaraan" => $kewarganegaraan,
				"nama_lengkap" => $nama_lengkap,
				"nomor_rm" => $new_nomor_rm,
				"jenis_kelamin" => $jenis_kelamin,
				"tempat_lahir" => ucwords($tempat_lahir),
				"tgl_lahir" => $tgl_lahir,
				"alamat" => $alamat,
				"no_hp" => $no_hp,
				"email" => $email,
				"creator_id" => $this->data['C_UID'],
				"verified" => 1,
				"reg_pemeriksaan" => $jenis_pemeriksaan,
				"asuransi_utama" => $asuransi,
				"no_asuransi"   => $no_asuransi,
				// "perujuk"       => $perujuk,
				// "nama_tenaga_perujuk"   => $nama_tenaga_perujuk,
			), []);
			if ($save === 'exist') {
				sendError('No identitas Sudah terdaftar');
			} else {
				if ($save == 0) sendError(lang('msg_insert_failed'));
				$test_no = $this->pemeriksaan->get_new_no_pemeriksaan($clinic_id);
				$save_to_pemeriksaan = $this->pemeriksaan->_save(array(
					"clinic_id" => $clinic_id,
					"no_test" => $test_no,
					"id_pasien" => $save,
					"nama_pasien" => $nama_lengkap,
					"id_provider" => $provider_id,
					//"id_dokter"=>htmlentities(trim($dokter)),
					"tgl_periksa" => date("Y-m-d"),
					"tgl_sampling" => $tgl_sampling,
					//"jenis_sample"=>htmlentities(trim($jenis_sample)),
					//"keluhan"=>htmlentities(trim($keluhan)),
					"jenis_pemeriksaan" => $jenis_pemeriksaan,
					"created_by"=>$this->data['C_UID'],
					"asuransi" => $asuransi,
					"no_asuransi"   => $no_asuransi,
					// "perujuk"       => $perujuk,
					// "nama_tenaga_perujuk"   => $nama_tenaga_perujuk,
				), []);
				// $save is last inserted id
				//$search_last=$this->pasien->_search(array("a.id"=>$save));
				$search_last = $this->pasien->_search($save);
				if (empty($search_last)) return sendError("Registrasi success tapi tidak dapat melakukan cek ulang data. silakan refresh dan check data di data pasien");
				$search_last = $search_last[0];
				$data_periksa = $this->pemeriksaan->_search_last_pasien_periksa($save,$clinic_id);
				if (sizeof($data_periksa) > 0)
					$search_last->tgl_sampling = date("d M Y", strtotime($data_periksa[0]->tgl_sampling));
				$search_last->tgl_periksa = date("d M Y");
				$search_last->tgl_reg = date("d M Y");
				$search_last->jenis_kelamin = ucwords($search_last->jenis_kelamin);
				$dta = array(
					"message" => "Registrasi Berhasil",
					"action" => "call_print",
					"data" => $search_last
				);
				echo json_encode($dta);
			}
		} else {
			// update data pasien
			$id_pasien = htmlentities(trim($posted['_id']));
			$data = array(
				"clinic_id" => $clinic_id,
				"no_identitas" => $no_identitas,
				"kewarganegaraan" => $kewarganegaraan,
				"nama_lengkap" => $nama_lengkap,
				"jenis_kelamin" => $jenis_kelamin,
				"tempat_lahir" => ucfirst($tempat_lahir),
				"tgl_lahir" => $tgl_lahir,
				"alamat" => $alamat,
				"no_hp" => $no_hp,
				"email" => $email,
				"asuransi_utama" => $asuransi,
				"no_asuransi"   => $no_asuransi,
				// "perujuk"       => $perujuk,
				// "nama_tenaga_perujuk"   => $nama_tenaga_perujuk,
			);
			if (isset($jenis_pemeriksaan) && $jenis_pemeriksaan != "") {
				$data["reg_pemeriksaan"] = $jenis_pemeriksaan;
			}
			// check detail
			if (isset($provider_id)) {
				$data['provider_id'] = $provider_id;
			}
			$where = ["id_pasien" => $id_pasien];
			if ($this->data['C_PV_GROUP'] != 'pusat') {
				$where["provider_id"] = $this->data['C_PV_ID'];
			}
			$save = $this->pasien->_save($data, $where);
			if ($save === "exist") return sendError("Data No identitas telah tersedia");
			$chk = $this->pemeriksaan->check_last_assign_detail($id_pasien,$clinic_id);
			if (sizeof($chk) > 0 && ($chk[0]->status == 'SELESAI')) {
				$dta = array(
					"message" => "Data Berhasil di Update",
					//		"action"=>"call_print",
					//"data"=>$search_last
				);
				echo json_encode($dta);
				return;
			} else {
				// continue allow to update
				if ($current_jenis_pemeriksaan != $jenis_pemeriksaan && $chk[0]->update_hasil_at != null && $chk[0]->status != 'SELESAI') {
					$dta = array(
						"message" => "Data Pasien berhasil diubah. namun jenis pemeriksaan silahkan update di pemeriksaan jika perlu",
						//		"action"=>"call_print",
						//"data"=>$search_last
					);
					echo json_encode($dta);
					return;
				}
			}
			//sendError("Tidak dapat mengubah jenis pemeriksaan karena detail hasil sudah proses update. Jika diperlukan silakan batalkan di pemeriksaan dan buat ulang pemeriksaan");

			if ($save === "exist") {
				sendError("Data No identitas telah tersedia");
			} else {
				//if($save>0){
				$update_periksa = 0;
				if (isset($jenis_pemeriksaan) && $jenis_pemeriksaan != "") {
					$update_periksa = ["jenis_pemeriksaan" => $jenis_pemeriksaan, "tgl_sampling" => $tgl_sampling];
					if (isset($provider_id)) $update_periksa["id_provider"] = $provider_id;
					$update_periksa = $this->pemeriksaan->_update_last_pemeriksaan_pasien($update_periksa, array("id_pasien" => $id_pasien,"clinic_id"=>$clinic_id));
				}
				//sendSuccess("Update success");
				//$search_last=$this->pasien->_search(array("a.id"=>htmlentities(trim($posted['_id']))));
				$search_last = $this->pasien->_search($id_pasien);
				if (empty($search_last)) return sendSuccess("Data Berhasil di Update");
				$search_last = $search_last[0];
				$data_periksa = $this->pemeriksaan->_search_last_pasien_periksa($save,$clinic_id);
				$search_last->tgl_periksa = date("d M Y");
				if (sizeof($data_periksa) > 0) {
					$search_last->tgl_sampling = date("d M Y", strtotime($data_periksa[0]->tgl_sampling));
					$search_last->tgl_periksa = date("d M Y", strtotime($data_periksa[0]->tgl_periksa));
				}

				$search_last->jenis_kelamin = ucwords($search_last->jenis_kelamin);
				$dta = array(
					"message" => "Data Berhasil di Update",
					"action" => "call_print",
					"data" => $search_last
				);
				echo json_encode($dta);

				//}else{
				//sendError("Tidak ada perubahan data");
				//}
			}
		}
	}
	public function confirm_register($id = '')
	{
		header('Content-Type: application/json');
		//isAllowed("c-privilege^delete work type");
		$method = $this->input->method(true);
		if ($method != "PUT") sendError(lang('msg_method_delete_required'), [], 405);

		$update = ["verified" => 1, "verified_at" => date("Y-m-d h:i:s"), "verified_by" => $this->data['C_UID']];
		$where = ['id' => htmlentities(trim($id))];
		if ($this->data['C_PV_GROUP'] != "pusat") {
			$where["provider_id"] = $this->data['C_PV_ID'];
		}
		$result = $this->pasien->_confirm_register($update, $where);
		if ($result > 0) {
			// need to auto register to pemeriksaan
			//$search_pasien=$this->pasien->_search(array("a.id"=>$id));
			$search_pasien = $this->pasien->_search(htmlentities(trim($id)));
			$dt_pasien = $search_pasien[0];
			$test_no = $this->pemeriksaan->get_new_no_pemeriksaan();
			$save_to_pemeriksaan = $this->pemeriksaan->_save(array(
				"no_test" => $test_no,
				"id_pasien" => $id,
				"nama_pasien" => $dt_pasien->nama_lengkap,
				"id_provider" => $dt_pasien->provider_id,
				//"id_dokter"=>htmlentities(trim($dokter)),
				"tgl_periksa" => date("Y-m-d"),
				//"jenis_sample"=>htmlentities(trim($jenis_sample)),
				//"keluhan"=>htmlentities(trim($keluhan)),
				"jenis_pemeriksaan" => $dt_pasien->reg_pemeriksaan,
				"created_by"=>$this->data['C_UID']
			), array(), "no_test");
			sendSuccess("Success", []);
		} else {
			sendError("Proses gagal");
		}
	}
	public function delete__($id = '')
	{
		header('Content-Type: application/json');
		//isAllowed("c-privilege^delete work type");
		$method = $this->input->method(true);
		if ($method != "DELETE") sendError(lang('msg_method_delete_required'), [], 405);
		//$result=$this->pasien->_delete($data_set,array('id'=>htmlentities(trim($id))));
		$result = $this->pasien->_delete(htmlentities(trim($id)), $this->data['C_NAME']);
		if ($result == 1) {
			sendSuccess(lang('msg_delete_success'), []);
		} else {
			sendError(lang('msg_delete_failed'));
		}
	}
	private function validateDate($date, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	public function import()
	{
		ini_set('max_execution_time', 0);
		header('Content-Type: application/json');
		$this->load->helper('uploadfile');
		$rand = rand(1000, 9999);
		$import_id = "import:" . $this->data['C_UID'] . "-" . date("Ymdhis") . "-" . $rand;
		$posted = $this->input->post();
		$paths = 'files/imported/';
		$uploaded = (object) single_upload($paths, "file", "import_data_pasien__" . date("Ymdhis") . "-" . $rand, ["xls", "xlsx"]);
		$upd_file = $paths . "/" . $uploaded->file_name;
		$ext_file = $uploaded->file_ext;
		$inputFileName = $upd_file;
		$list_jenis_pemeriksaan = $this->jenis->_list_jenis_pemeriksaan();
		$arr_jenis = [];
		foreach ($list_jenis_pemeriksaan as $item) {
			$arr_jenis[$item->jenis] = $item;
		}
		$provider_id = (isset($posted['provider_id']) && $this->data['C_PV_GROUP'] == 'pusat' && $posted['provider_id'] != "") ? $posted['provider_id'] : $this->data['C_PV_ID'];
		// import in 
		$cols_range = range("B", "K");
		$start_rows = (int) $posted['start_row'];
		$cols_settings = [
			"B" => [
				"name" => "reg_pemeriksaan",
				"alias" => "Jenis Pemeriksaan",
				"format" => "string",
				"action" => "search_id",
				"options" => $arr_jenis
			],
			"C" => [
				"name" => "tgl_sampling",
				"alias" => "Tgl Sampling",
				"format" => "date"
			],
			"D" => [
				"name" => "no_identitas",
				"alias" => "No identitas",
				"format" => "string",
				"required" => true,
				"unique" => true
			],
			"E" => [
				"name" => "nama_lengkap",
				"alias" => "Nama Lengkap",
				"format" => "string",
				"required" => true
			],
			"F" => [
				"name" => "jenis_kelamin",
				"alias" => "Jenis Kelamin",
				"format" => "lowercase",
				"required" => true,
				"options" => ["laki-laki", "perempuan"]
			],
			"G" => [
				"name" => "tempat_lahir",
				"alias" => "Kota Kelahiran",
				"format" => "string"
			],
			"H" => [
				"name" => "tgl_lahir",
				"alias" => "Tgl Lahir",
				"format" => "date",
				"required" => true
			],
			"I" => [
				"name" => "alamat",
				"alias" => "Alamat",
				"format" => "string"
			],
			"J" => [
				"name" => "no_hp",
				"alias" => "no_hp",
				"format" => "string"
			],
			"K" => [
				"name" => "email",
				"alias" => "Email",
				"format" => "email"
			],
			"L" => [
				"name" => "kewarganegaraan",
				"alias" => "Kewarganegaraan",
				"format" => "string"
			]
		];
		/**  Create a new Reader of the type defined in $inputFileType  **/
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(ucfirst($ext_file));
		/**  Advise the Reader that we only want to load cell data  **/
		$reader->setReadDataOnly(FALSE);
		/**  Load $inputFileName to a Spreadsheet Object  **/
		try {
			$spreadsheet = $reader->load($inputFileName);
			$worksheet = $spreadsheet->getActiveSheet();
			$rows = [];
			$rows_error = [];
			$row_no = 1;
			foreach ($worksheet->getRowIterator() as $row) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
				$cells = [];
				$c = 0;
				$ignore = false;
				$is_error = false;
				$errors = [];
				if ($row_no >= $start_rows) {
					foreach ($cellIterator as $col => $cell) {
						$row_index = $row_no - 1;
						// search if col in setting, otherwise ignore it
						if (isset($cols_settings["$col"])) {
							$alias = (isset($cols_settings["$col"]['alias'])) ? $alias = $cols_settings["$col"]['alias'] : "Unknow Column";
							$format = (isset($cols_settings["$col"]['format'])) ? $cols_settings["$col"]['format'] : false;
							$action = (isset($cols_settings["$col"]['action'])) ? $cols_settings["$col"]['action'] : false;
							// check if unique
							$val = remove_prefix(trim($cell->getFormattedValue()), "'"); //
							// if unique cols is empty, then ignore it. assume no data to save
							if (isset($cols_settings["$col"]['unique']) && ($val == "" || $cell->getValue() == "")) $ignore = true;
							// check if required but not fill out then push to error list
							if (isset($cols_settings["$col"]['required']) && $val == "" && !$ignore) {
								$is_error = true;
								//array_push($errors,"$alias [$col] - Required"); 
								array_push($errors, "$alias");
							}
							if ($format != false && !$ignore) {
								if ($format == 'lowercase') $val = strtolower($val);
								if ($format == "date") {
									if (!$this->validateDate($val, "Y-m-d")) {
										// try to fix the date format
										$new_date = date("Y-m-d", strtotime($val));
										// check if valid
										if (!$this->validateDate($new_date, "Y-m-d")) {
											$is_error = true;
											//array_push($errors,"$alias [$col] - Invalid Format Date");
											if (!in_array("$alias", $errors)) array_push($errors, "$alias");
										} else {
											// if format valid, make sure year is sampe. to prevent change to 1970
											$getYear = date("Y", strtotime($new_date));
											if (!strpos($val, $getYear)) {
												$is_error = true;
												//array_push($errors,"$alias [$col] - Invalid Format Date");
												if (!in_array("$alias", $errors)) array_push($errors, "$alias");
											} else {
												$val = $new_date;
											}
										}
									}
								} // put another format here if required
								else
									if ($format == 'email' && $val != "" && $val != "-") {
									$val = strtolower($val);
									if (!isEmailValid($val)) {
										$is_error = true;
										//array_push($errors,"$alias [$col] '$val' - Invalid Format Email");
										if (!in_array("$alias", $errors)) array_push($errors, "$alias");
									}
								}
							}
							// check if any options, value must be inside the options
							if (isset($cols_settings["$col"]['options']) && !$ignore) {
								$options = $cols_settings["$col"]['options'];
								if (isset($action) && $action == "search_id") {
									if (array_key_exists($val, $options)) {
										// if found, replace value with object id
										$val = $options[$val]->id;
									} else {
										$is_error = true;
										//array_push($errors,"$alias [$col] - value '$val'. Required[".implode(", ",array_keys($options))."]");
										if (!in_array("$alias", $errors)) array_push($errors, "$alias");
									}
								} else {
									if (!in_array($val, $options)) {
										$is_error = true;
										//array_push($errors,"$alias [$col] - value '$val'. Required[".implode(", ",$options)."]");
										if (!in_array("$alias", $errors)) array_push($errors, "$alias");
									}
								}
							}
							$cells[$cols_settings["$col"]['name']] = $val;
							$c++;
						}
					} // end foreach column
					// all additional col value set below
					$cells['provider_id'] = $provider_id;
					$cells['creator_id'] = $this->data['C_NAME'];
					$cells['import_id'] = $import_id;
					$cells['row_no'] = $row_no;
				} else {
					$ignore = true;
				}
				//if(sizeof($cells)!=sizeof($cols_settings)-2) return sendError(lang('msg_error_column_not_match'));
				//$cells[$cols_settings[$c]]=$this->data['C_NAME'];
				$c++;
				//$cells[$cols_settings[$c]]=$import_id;
				if (!$ignore && !$is_error) {
					$rows[] = $cells;
				} else
					if (!$ignore && $is_error) {
					$cells["row"] = $row_no;
					$cells["error"] = implode(" | ", $errors);
					array_push($rows_error, "Row $row_no:<br>" . $cells['error']);
				}
				$row_no++;
			}
			if (!empty($rows_error)) {
				unlink($upd_file);
				return sendError("Data dalam sheet tidak valid!", $rows_error);
			} else {
				//continue to process data rows
				// need to loop all rows to save, because need to get pasien id
				$failed = [];
				$success = 0;
				foreach ($rows as $item) {
					$dta = $item;
					$new_nomor_rm = $this->pasien->get_new_no_pasien();
					$dta['nomor_rm'] = $new_nomor_rm;
					$dta['verified'] = 1;
					unset($dta['tgl_sampling']);
					unset($dta['row_no']);
					$save = $this->pasien->_save($dta, array());
					if ($save == 'exist') {
						array_push($failed, "Row " . $item['row_no'] . " | " . $dta['no_identitas'] . " | " . $dta['nama_lengkap']);
					} else {
						$success++;
						//if($save==0) sendError(lang('msg_insert_failed'));
						$test_no = $this->pemeriksaan->get_new_no_pemeriksaan();
						$save_to_pemeriksaan = $this->pemeriksaan->_save(array(
							"no_test" => $test_no,
							"id_pasien" => $save,
							"nama_pasien" => $dta['nama_lengkap'],
							"id_provider" => $dta['provider_id'],
							//"id_dokter"=>htmlentities(trim($dokter)),
							"tgl_periksa" => date("Y-m-d"),
							"tgl_sampling" => $item['tgl_sampling'],
							//"jenis_sample"=>htmlentities(trim($jenis_sample)),
							//"keluhan"=>htmlentities(trim($keluhan)),
							"jenis_pemeriksaan" => $dta['reg_pemeriksaan'],
							"import_id" => $import_id
						), array(), "no_test");
					}
				}
				$msg_dup = (sizeof($failed) > 0) ? sizeof($failed) . " sudah terdaftar" : "";
				$output = ["message" => "Import complete. $success data ditambahkan, " . $msg_dup, "duplicate" => $failed, "link" => base_url('pasien?import=' . $import_id)];
				echo json_encode($output);
				unlink($upd_file);
			}

			// if(!empty($rows)){
			// 	$save=$this->supplier->save_batch($rows);
			// 	if($save==0){
			// 		return sendError(lang('msg_no_data_imported'));
			// 	}else{
			// 		echo json_encode(array("message"=>lang('msg_import_success')." #".$save,"import_id"=>$import_id));
			// 	}
			// }else{
			// 	return sendError(lang('msg_insert_failed'));
			// }
		} catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
			echo "error excel file";
			die('Error loading file: ' . $e->getMessage());
		}
	}
	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
}

/* End of file Pasien.php */
