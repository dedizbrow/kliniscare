<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Data_pasien extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('rawat-jalan/data_pasien', $this->session->userdata('site_lang'));
		$this->load->model('rawat-jalan/Data_pasien_model', 'data_pasien');
		$this->load->model("/admin/Other_setting_model", "other_set");
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		isAllowed("c-datapasien");
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Data pasien";
		$this->data["page_title"] = "Data pasien";
		$this->data['js_control'] = "rawat-jalan/data_pasien/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;
		$this->data['skip_select_clinic'] = false;
		$this->template->load(get_template(), 'rawat-jalan/data_pasien/index', $this->data);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted); // added clinic_id if not set
		$data = $this->data_pasien->_load_dt($posted);
		echo json_encode($data);
	}
	public function save__()
	{
		header('Content-Type: application/json');
		isAllowed("c-datapasien^update");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

		if ($nama_lengkap == "") return sendError("Nama wajib diisi");
		if (!isset($jenis_kelamin) || $jenis_kelamin == "") return sendError("Jenis Kelamin Wajib dipilih");
		// if (!isset($status_nikah) || $status_nikah == "") return sendError("Status Pernikahan Wajib dipilih");
		// if (!isset($agama) || $agama == "") return sendError("Agama Wajib dipilih");

		$id_pasien = htmlentities(trim($posted['_id']));
		$data = array(
			"clinic_id"		=> $clinic_id,
			"nomor_rm"      => $nomor_rm,
			"nama_lengkap"  => $nama_lengkap,
			"jenis_kelamin" => $jenis_kelamin,
			"tempat_lahir"  => $tempat_lahir,
			"tgl_lahir"     => $tgl_lahir,
			"umur"          => $umur,
			"status_nikah"  => (isset($status_nikah)) ? $status_nikah : "",
			"agama"         => (isset($agama)) ? $agama : "",
			"gol_darah"     => (isset($gol_darah)) ? $gol_darah : "",
			"identitas"     => $identitas,
			"no_identitas"  => $no_identitas,
			"kewarganegaraan"     => $kewarganegaraan,
			"email"     	=> $email,
			"provinsi"      => (isset($provinsi)) ? $provinsi : "",
			"kabupaten"     => (isset($kabupaten)) ? $kabupaten : "",
			"kecamatan"     => (isset($kecamatan)) ? $kecamatan : "",
			"alamat"        => $alamat,
			"no_hp"         => $no_hp,
			"no_telp"       => $no_telp,
			"pekerjaan"     => $pekerjaan,
			"perusahaan"    => $perusahaan,
			"ibu_kandung"   => $ibu_kandung,
			"asuransi_utama" => (isset($asuransi_utama)) ? $asuransi_utama : "",
			"no_asuransi"   => $no_asuransi,
			"creator_id"    => $this->data['C_UID']
		);
		$where = ["id_pasien" => $id_pasien];
		$save = $this->data_pasien->_save_update($data, $where, "nomor_rm", "clinic_id");
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

	public function search__($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->data_pasien->_search(array("id_pasien" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}

	public function search_rm($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$pemeriksaan = $this->data_pasien->_search_pemeriksaan($id);

		// $pem_diagnosa = $this->data_pasien->_search_pem_diagnosa(array("fk_pemeriksaan" => 9));
		if (empty($pemeriksaan)) sendError("Pasien belum memiliki riwayat Rekam Medis");
		echo json_encode(array("pemeriksaan" => $pemeriksaan));
	}
	public function search_lab($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$pemeriksaan = $this->data_pasien->_search_pemeriksaan_lab($id);

		// $pem_diagnosa = $this->data_pasien->_search_pem_diagnosa(array("fk_pemeriksaan" => 9));
		if (empty($pemeriksaan)) sendError("Pasien belum memiliki riwayat Laboraturium");
		echo json_encode(array("pemeriksaan" => $pemeriksaan));
	}
	public function delete__($id = '')
	{
		header('Content-Type: application/json');
		isAllowed("c-datapasien^delete");
		$method = $this->input->method(true);
		if ($method != "DELETE") sendError(lang('msg_method_delete_required'), [], 405);
		$result = $this->data_pasien->_delete(array('id_pasien' => htmlentities(trim($id))));
		if ($result == 1) {
			sendSuccess(lang('msg_delete_success'), []);
		} else {
			sendError(lang('msg_delete_failed'));
		}
	}

	public function export_()
	{
		$sheet = new Spreadsheet();
		$posted = $this->input->post();
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : htmlentities(trim($posted['clinic_id']));
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

		$allUsers = $this->data_pasien->pasien_list($clinic_id);
		$heading = array('No. ', 'No RM', 'Nama Lengkap', 'Jenis Kelamin', 'Tempat Lahir', 'Tgl lahir', 'Umur', 'Status Pernikahan', 'Agama', 'Gol darah', 'No Identitas', 'Alamat', 'Kecamatan', 'Kabupaten', 'Provinsi', 'No HP', 'Pekerjaan', 'Perusahaan', 'Nama Ibu', 'Asuransi', 'No Asuransi', 'Email', 'Kewarganegaraan');

		$sheet->getActiveSheet()->setTitle('Pasien');
		$from = "A1";
		$to = "W1";
		$sheet->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
		for ($col = 'A'; $col !== 'Y'; $col++) {
			$sheet->getActiveSheet()
				->getColumnDimension($col)
				->setAutoSize(true);
		}
		$rowNumberH = 1;
		$colH = 'A';
		foreach ($heading as $h) {
			$sheet->getActiveSheet()->setCellValue($colH . $rowNumberH, $h);
			$colH++;
		}
		$row = 2;
		$start_row = $row - 1;
		$no = 1;
		foreach ($allUsers as $user) {
			$sheet->getActiveSheet()->setCellValue('A' . $row, $no);
			$sheet->getActiveSheet()->setCellValue('B' . $row, $user->nomor_rm);
			$sheet->getActiveSheet()->setCellValue('C' . $row, $user->nama_lengkap);
			$sheet->getActiveSheet()->setCellValue('D' . $row, $user->jenis_kelamin);
			$sheet->getActiveSheet()->setCellValue('E' . $row, $user->tempat_lahir);
			$sheet->getActiveSheet()->setCellValue('F' . $row, $user->tgl_lahir);
			$sheet->getActiveSheet()->setCellValue('G' . $row, $user->umur);
			$sheet->getActiveSheet()->setCellValue('H' . $row, $user->nama_status_pernikahan);
			$sheet->getActiveSheet()->setCellValue('I' . $row, $user->nama_agama);
			$sheet->getActiveSheet()->setCellValue('J' . $row, $user->nama_gol_darah);
			$sheet->getActiveSheet()->setCellValue('K' . $row, $user->no_identitas);
			$sheet->getActiveSheet()->setCellValue('L' . $row, $user->alamat);
			$sheet->getActiveSheet()->setCellValue('M' . $row, $user->kecamatan);
			$sheet->getActiveSheet()->setCellValue('N' . $row, $user->kabupaten);
			$sheet->getActiveSheet()->setCellValue('O' . $row, $user->provinsi);
			$sheet->getActiveSheet()->setCellValue('P' . $row, $user->no_hp);
			$sheet->getActiveSheet()->setCellValue('Q' . $row, $user->pekerjaan);
			$sheet->getActiveSheet()->setCellValue('R' . $row, $user->perusahaan);
			$sheet->getActiveSheet()->setCellValue('S' . $row, $user->ibu_kandung);
			$sheet->getActiveSheet()->setCellValue('T' . $row, $user->nama_asuransi);
			$sheet->getActiveSheet()->setCellValue('U' . $row, $user->no_asuransi);
			$sheet->getActiveSheet()->setCellValue('V' . $row, $user->email);
			$sheet->getActiveSheet()->setCellValue('W' . $row, $user->kewarganegaraan);
			$row++;
			$no++;
		}
		$styleBorder = array(
			'borders' => array(
				'outline' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
				),
				'inside' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => ['rgb' => '808080'],
				),
			),

		);
		$styleHeader = array(
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => array('argb' => 'FFE7DECD')
			)
		);

		$sheet->getActiveSheet()->getStyle("A$start_row:W$row")->applyFromArray($styleBorder);
		$sheet->getActiveSheet()->getStyle("A1:W1")->applyFromArray($styleHeader);
		$writer = new Xlsx($sheet);
		$filename = "Pasien";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
		exit();
	}

	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}


	private function validateDate($date, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	public function import_()
	{
		ini_set('max_execution_time', 0);
		header('Content-Type: application/json');
		$this->load->helper('uploadfile');
		$rand = rand(1000, 9999);
		$import_id = "import:" . $this->data['C_UID'] . "-" . date("Ymdhis") . "-" . $rand;
		$posted = $this->input->post();
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : htmlentities(trim($posted['clinic_id']));;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");
		$paths = 'files/imported/';
		$uploaded = (object) single_upload($paths, "file", "import_data_pasien_klinik__" . date("Ymdhis") . "-" . $rand, ["xls", "xlsx"]);
		$upd_file = $paths . "/" . $uploaded->file_name;
		$ext_file = $uploaded->file_ext;
		$inputFileName = $upd_file;

		$list_status_nikah = $this->data_pasien->_list_status_nikah();
		$arr_status_nikah = [];
		foreach ($list_status_nikah as $item) {
			$arr_status_nikah[$item->nama_status_pernikahan] = $item;
		}

		$list_agama = $this->data_pasien->_list_agama();
		$arr_agama = [];
		foreach ($list_agama as $item1) {
			$arr_agama[$item1->nama_agama] = $item1;
		}

		$list_gol = $this->data_pasien->_list_gol();
		$arr_gol = [];
		foreach ($list_gol as $item2) {
			$arr_gol[$item2->nama_gol_darah] = $item2;
		}
		$list_asuransi = $this->data_pasien->_list_asuransi();
		$arr_asuransi = [];
		foreach ($list_asuransi as $item3) {
			$arr_asuransi[$item3->namaAsuransi] = $item3;
		}


		$cols_range = range("B", "V");
		$start_rows = (int) $posted['start_row'];
		$cols_settings = [
			"B" => [
				"name" => "nomor_rm",
				"alias" => "Nomor Rekam Mesia",
				"format" => "string",
				"required" => true,
				"unique" => true

			],
			"C" => [
				"name" => "nama_lengkap",
				"alias" => "Nama Pasien",
				"format" => "string",
				"required" => true
			],
			"D" => [
				"name" => "jenis_kelamin",
				"alias" => "Jenis Kelamin",
				"format" => "string",
				"required" => true,
				"options" => ["Laki-laki", "Perempuan"]
			],
			"E" => [

				"name" => "tempat_lahir",
				"alias" => "Tempat Lahir",
				"format" => "string"
			],
			"F" => [
				"name" => "tgl_lahir",
				"alias" => "Tgl Lahir",
				"format" => "string"
			],
			"G" => [
				"name" => "umur",
				"alias" => "Umur",
				"format" => "string"
			],
			"H" => [
				"name" => "status_nikah",
				"alias" => "Status Nikah",
				"format" => "string",
				"action" => "search_id",
				"options" => $arr_status_nikah
			],
			"I" => [
				"name" => "agama",
				"alias" => "Agama",
				"format" => "string",
				"action" => "search_id2",
				"options" => $arr_agama
			],
			"J" => [
				"name" => "gol_darah",
				"alias" => "Golongan Darah",
				"format" => "string",
				"action" => "search_id3",
				"options" => $arr_gol
			],
			"K" => [

				"name" => "identitas",
				"alias" => "Identitas",
				"format" => "string",
				"required" => true,
				"options" => ["KTP", "SIM", "Passport", "Lainnya"]
			],
			"L" => [
				"name" => "no_identitas",
				"alias" => "Nomor Identitas",
				"format" => "string",
				"required" => true
			],
			"M" => [
				"name" => "alamat",
				"alias" => "Alamat",
				"format" => "string"
			],
			"N" => [
				"name" => "no_hp",
				"alias" => "Nomor Handphone",
				"format" => "string"
			],
			"O" => [
				"name" => "no_telp",
				"alias" => "Nomor Telp",
				"format" => "string"
			],
			"P" => [
				"name" => "pekerjaan",
				"alias" => "Pekerjaan",
				"format" => "string"
			],
			"Q" => [
				"name" => "perusahaan",
				"alias" => "Perusahaan",
				"format" => "string"
			],
			"R" => [
				"name" => "ibu_kandung",
				"alias" => "Ibu Kandung",
				"format" => "string"
			],
			"S" => [
				"name" => "asuransi_utama",
				"alias" => "Asuransi",
				"format" => "string",
				"action" => "search_id4",
				"options" => $arr_asuransi
			],
			"T" => [
				"name" => "no_asuransi",
				"alias" => "Nomor Asuransi",
				"format" => "string"
			],
			"U" => [
				"name" => "email",
				"alias" => "Email",
				"format" => "email"
			],
			"V" => [
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
											if (!in_array("$alias", $errors)) array_push($errors, "$alias");
										} else {
											// if format valid, make sure year is sampe. to prevent change to 1970
											$getYear = date("Y", strtotime($new_date));
											if (!strpos($val, $getYear)) {
												$is_error = true;
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
										$val = $options[$val]->id_status_pernikahan;
									} else {
										$is_error = true;
										if (!in_array("$alias", $errors)) array_push($errors, "$alias");
									}
								} elseif (isset($action) && $action == "search_id2") {
									if (array_key_exists($val, $options)) {
										// if found, replace value with object id
										$val = $options[$val]->id_agama;
									} else {
										$is_error = true;
										if (!in_array("$alias", $errors)) array_push($errors, "$alias");
									}
								} elseif (isset($action) && $action == "search_id3") {
									if (array_key_exists($val, $options)) {
										// if found, replace value with object id
										$val = $options[$val]->id_gol_darah;
									} else {
										$is_error = true;
										if (!in_array("$alias", $errors)) array_push($errors, "$alias");
									}
								} elseif (isset($action) && $action == "search_id4") {
									if (array_key_exists($val, $options)) {
										// if found, replace value with object id
										$val = $options[$val]->idAsuransi;
									} else {
										$is_error = true;
										if (!in_array("$alias", $errors)) array_push($errors, "$alias");
									}
								} else {
									if (!in_array($val, $options)) {
										$is_error = true;
										$ignore;
										if (!in_array("$alias", $errors)) array_push($errors, "$alias");
									}
								}
							}
							$cells[$cols_settings["$col"]['name']] = $val;
							$c++;
						}
					} // end foreach column
					// all additional col value set below
					$cells['clinic_id'] = $clinic_id;
					$cells['creator_id'] = $this->data['C_UID'];
					$cells['import_id'] = $import_id;
					$cells['provinsi'] = '';
					$cells['kabupaten'] = '';
					$cells['kecamatan'] = '';
					$cells['create_at'] = date('Y-m-d');
					$cells['row_no'] = $row_no;
				} else {
					$ignore = true;
				}
				$c++;
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
				$failed = [];
				$success = 0;
				foreach ($rows as $item) {
					$dta = $item;
					unset($dta['row_no']);
					$save = $this->data_pasien->_save_import($dta, 'nomor_rm', 'clinic_id');
					if ($save == 'exist') {
						array_push($failed, "Row " . $item['row_no'] . " | " . $dta['nomor_rm'] . " | " . $dta['nama_lengkap']);
					} else {
						$success++;
					}
				}
				$msg_dup = (sizeof($failed) > 0) ? sizeof($failed) . " sudah terdaftar" : "";
				$output = ["message" => "Import complete. $success data ditambahkan, " . $msg_dup, "duplicate" => $failed];
				echo json_encode($output);
				unlink($upd_file);
			}
		} catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
			echo "error excel file";
			die('Error loading file: ' . $e->getMessage());
		}
	}
	public function print()
	{
		$clinic_id = getClinic()->id;
		$gets = $this->input->get();
		if ($clinic_id == 'allclinic' && isset($gets['clinic_id'])) $clinic_id = $gets['clinic_id'];
		$company_info = $this->other_set->get_com_profile(array("clinic_id" => $clinic_id));
		$this->data['company_info'] = $company_info[0];
		$pasien = $this->data_pasien->_search_pemeriksaan(htmlentities((int) trim($gets['viewid'])));
		if (empty($pasien)) die("Data Not exist");
		$dt_pasien = $pasien[0];

		$this->data['pasien'] = $dt_pasien;

		$page_title = "Print ";

		$doc_setting = $this->other_set->get_setting_doc_requirements(array("clinic_id" => $clinic_id));
		$this->data['doc_setting'] = array_group_by("code", $doc_setting, true);

		$mpdf = new \Mpdf\Mpdf(['format' => [80, 50], 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

		$this->data['page_title'] = "Print ";
		$mpdf->SetTitle($page_title);
		$mpdf->SetSubject($page_title);
		$mpdf->shrink_tables_to_fit = 1;
		$margin_left = 0;
		$margin_right = 0;
		$margin_top = 5;
		$margin_bottom = 0;
		$margin_header_top = 0;
		$margin_footer_bottom = 0;
		$mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url($this->data['doc_setting']->img_doc_header_kwitansi->path) . '"></div>', '', true);

		$mpdf->addPage('P', '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);
		$mpdf->defaultfooterline = 10;
		$html = $this->load->view('rawat-jalan/data_pasien/print-no-anggota', $this->data, true);
		$mpdf->WriteHTML($html);
		$mpdf->Output($page_title . ".pdf", 'I');
	}
}
