<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Karyawan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('master-data/karyawan', $this->session->userdata('site_lang'));
		$this->load->model("master-data/Karyawan_model", "k");

		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		isAllowed('c-karyawan');
	}

	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Karyawan";
		$this->data["page_title"] = "Karyawan";
		// $this->data["page_title_small"]="text small dibawah page title";
		$this->data['js_control'] = "master-data/karyawan/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'master-data/karyawan/index', $this->data);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->k->_load_dt($posted);
		echo json_encode($data);
	}

	public function search__($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->k->_search(array("idKaryawan" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
	public function select2_bidang()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->k->_search_select_bidang($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function save__()
	{
		header('Content-Type: application/json');
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

		if ($namaKaryawan == "") return sendError("Nama wajib diisi");
		if ($bidang == "") return sendError("Bidang pekerjaan wajib diisi");
		if (!isEmailValid($email))
			sendError(lang('msg_invalid_email'));
		if ($kodeKaryawan == "") return sendError("Kode Wajib diisi");
		if (!isset($posted['_id']) || $posted['_id'] == "") {
			isAllowed("c-karyawan^create");
			$save = $this->k->_save(array(
				"kodeKaryawan" 	=> $kodeKaryawan,
				"namaKaryawan" 	=> $namaKaryawan,
				"alamat" 		=> $alamat,
				"notelp" 		=> $notelp,
				"email" 		=> $email,
				"bidang" 		=> $bidang,
				"clinic_id" 	=> $clinic_id,
				'creator_id'    => $this->data['C_UID']
			), array(), "kodeKaryawan", "clinic_id");
			if ($save == 'exist') {
				sendError('Karyawan Sudah terdaftar');
			} else {

				echo json_encode(array("message" => "Registrasi berhasil"));
			}
		} else {
			isAllowed("c-karyawan^update");
			$id_karyawan = htmlentities(trim($posted['_id']));
			$data = array(
				"kodeKaryawan" 	=> $kodeKaryawan,
				"namaKaryawan" 	=> $namaKaryawan,
				"alamat" 		=> $alamat,
				"notelp" 		=> $notelp,
				"email" 		=> $email,
				"bidang" 		=> $bidang,
				"clinic_id" 	=> $clinic_id,
				'creator_id'    => $this->data['C_UID']
			);
			$where = ["idKaryawan" => $id_karyawan];
			$save = $this->k->_save($data, $where, "kodeKaryawan", "clinic_id");
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
		isAllowed("c-karyawan^delete");
		$method = $this->input->method(true);
		if ($method != "DELETE") sendError(lang('msg_method_delete_required'), [], 405);
		$result = $this->k->_delete(array('idKaryawan' => htmlentities(trim($id))));
		if ($result == 1) {
			sendSuccess(lang('msg_delete_success'), []);
		} else {
			sendError(lang('msg_delete_failed'));
		}
	}
	public function save_bidang()
	{
		header('Content-Type: application/json');
		isAllowed("c-karyawan^create");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$postedtipe = $this->input->post();
		foreach ($postedtipe as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");
		if ($nama == "") return sendError("Nama wajib diisi");
		$savesatuan = $this->k->_save_bidang(array(
			"nama" 		=> $nama,
			"clinic_id" => $clinic_id,
		), array(), "nama", "clinic_id");
		if ($savesatuan == 'exist') {
			sendError('Sudah terdaftar');
		} else {

			echo json_encode(array("message" => "Penambahan berhasil"));
		}
	}
	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
	public function export_()
	{
		$sheet = new Spreadsheet();
		$posted = $this->input->post();
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : htmlentities(trim($posted['clinic_id']));
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");
		$allUsers = $this->k->karyawanlist($clinic_id);
		$heading = array('No.',  'Nama Karyawan', 'Kode Karyawan', 'Alamat', 'Nomor Telp', 'E-mail', 'Bidang');
		$sheet->getActiveSheet()->setTitle('Karyawan');
		$from = "A1";
		$to = "G1";
		$sheet->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
		for ($col = 'A'; $col !== 'H'; $col++) {
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
			$sheet->getActiveSheet()->setCellValue('B' . $row, $user->namaKaryawan);
			$sheet->getActiveSheet()->setCellValue('C' . $row, $user->kodeKaryawan);
			$sheet->getActiveSheet()->setCellValue('D' . $row, $user->alamat);
			$sheet->getActiveSheet()->setCellValue('E' . $row, $user->notelp);
			$sheet->getActiveSheet()->setCellValue('F' . $row, $user->email);
			$sheet->getActiveSheet()->setCellValue('G' . $row, $user->nama);
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

		$sheet->getActiveSheet()->getStyle("A$start_row:G$row")->applyFromArray($styleBorder);
		$sheet->getActiveSheet()->getStyle("A1:G1")->applyFromArray($styleHeader);
		$writer = new Xlsx($sheet);
		$filename = "Karyawan";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
		exit();
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
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

		$paths = 'files/imported/';
		$uploaded = (object) single_upload($paths, "file", "import_data_karyawan__" . date("Ymdhis") . "-" . $rand, ["xls", "xlsx"]);
		$upd_file = $paths . "/" . $uploaded->file_name;
		$ext_file = $uploaded->file_ext;
		$inputFileName = $upd_file;
		$list_bidang = $this->k->_list_bidang();
		$arr_bidang = [];
		foreach ($list_bidang as $item) {
			$arr_bidang[$item->nama] = $item;
		}

		$cols_range = range("B", "G");
		$start_rows = (int) $posted['start_row'];
		$cols_settings = [
			"B" => [
				"name" => "namaKaryawan",
				"alias" => "Nama Lengkap",
				"format" => "string",
				"required" => true

			],
			"C" => [
				"name" => "kodeKaryawan",
				"alias" => "Kode Karyawan",
				"format" => "string",
				"required" => true,
				"unique" => true
			],
			"D" => [
				"name" => "alamat",
				"alias" => "Alamat",
				"format" => "string"
			],
			"E" => [
				"name" => "notelp",
				"alias" => "Nomor telp",
				"format" => "string"

			],
			"F" => [
				"name" => "email",
				"alias" => "Email",
				"required" => true,
				"format" => "email"
			],
			"G" => [
				"name" => "bidang",
				"alias" => "Bidang ",
				"format" => "string",
				"action" => "search_id",
				"options" => $arr_bidang
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
										$val = $options[$val]->id;
									} else {
										$is_error = true;
										if (!in_array("$alias", $errors)) array_push($errors, "$alias");
									}
								} else {
									if (!in_array($val, $options)) {
										$is_error = true;
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
					$save = $this->k->_save($dta, array(), "kodeKaryawan", "clinic_id");
					if ($save == 'exist') {
						array_push($failed, "Row " . $item['row_no'] . " | " . $dta['kodeKaryawan'] . " | " . $dta['namaKaryawan']);
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
}
