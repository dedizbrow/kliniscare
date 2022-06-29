<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Ruangan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('master-data/ruangan', $this->session->userdata('site_lang'));
		$this->load->model('master-data/Ruangan_model', 'ruangan');
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		isAllowed('c-ruangan');
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Ruangan";
		$this->data["page_title"] = "Ruangan";
		$this->data['js_control'] = "master-data/ruangan/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'master-data/ruangan/index', $this->data);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->ruangan->_load_dt($posted);
		echo json_encode($data);
	}
	public function search__($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->ruangan->_search(array("idRuangan" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}

	public function select2_kelas_ruangan()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->ruangan->_search_select_kelas_ruangan($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function select2_kategori_ruangan()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->ruangan->_search_select_kategori_ruangan($key, $gets['clinic_id']);
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

		if ($namaRuangan == "") return sendError("Nama wajib diisi");
		if ($nomor == "") return sendError("Nomor Wajib diisi");
		if ($nomor_ranjang == "") return sendError("Nomor Ranjang Wajib diisi");
		if ($tarif == "") return sendError("Nomor Wajib diisi");
		if ($idKelasruangan == "") return sendError("Kelas ruangan Wajib diisi");
		if ($idKategoriruangan == "") return sendError("Kategori ruangan Wajib diisi");
		if (!isset($posted['_id']) || $posted['_id'] == "") {
			isAllowed("c-ruangan^create");
			$save = $this->ruangan->_save(array(
				"namaRuangan" 		=> $namaRuangan,
				"clinic_id" 		=> $clinic_id,
				"idKelasruangan" 	=> $idKelasruangan,
				"idKategoriruangan" => $idKategoriruangan,
				"nomor" 			=> $nomor,
				"nomor_ranjang" 	=> $nomor_ranjang,
				"tarif" 			=> $tarif,
				"status" 			=> "tersedia",
				'creator_id'        => $this->data['C_UID']
			), array(), "namaRuangan", "nomor", "nomor_ranjang", "clinic_id");
			if ($save == 'exist') {
				sendError('Ruangan Sudah terdaftar');
			} else {
				echo json_encode(array("message" => "Registrasi berhasil"));
			}
		} else {
			isAllowed("c-ruangan^update");
			$id_ruangan = htmlentities(trim($posted['_id']));
			$data = array(
				"namaRuangan" 		=> $namaRuangan,
				"clinic_id" 		=> $clinic_id,
				"idKelasruangan" 	=> $idKelasruangan,
				"idKategoriruangan" => $idKategoriruangan,
				"nomor" 			=> $nomor,
				"nomor_ranjang" 	=> $nomor_ranjang,
				"tarif" 			=> $tarif,
				"status" 			=> "tersedia",
				'creator_id'        => $this->data['C_UID']
			);
			$where = ["idRuangan" => $id_ruangan];
			$save = $this->ruangan->_save($data, $where, "namaRuangan", "nomor", "nomor_ranjang", "clinic_id");
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
		isAllowed("c-ruangan^delete");
		$method = $this->input->method(true);
		if ($method != "DELETE") sendError(lang('msg_method_delete_required'), [], 405);
		$result = $this->ruangan->_delete(array('idRuangan' => htmlentities(trim($id))));
		if ($result == 1) {
			sendSuccess(lang('msg_delete_success'), []);
		} else {
			sendError(lang('msg_delete_failed'));
		}
	}

	public function save_kelas()
	{
		header('Content-Type: application/json');
		isAllowed("c-ruangan^create");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$postedtipe = $this->input->post();
		foreach ($postedtipe as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

		if ($namaKelas == "") return sendError("Nama wajib diisi");
		$savesatuan = $this->ruangan->_save_kelas(array(
			"clinic_id" 		=> $clinic_id,
			"namaKelas" 		=> $namaKelas,
		), array(), "namaKelas", "clinic_id");
		if ($savesatuan == 'exist') {
			sendError('Satuan Sudah terdaftar');
		} else {

			echo json_encode(array("message" => "Registrasi berhasil"));
		}
	}
	public function save_kategori()
	{
		header('Content-Type: application/json');
		isAllowed("c-ruangan^create");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$postedtipe = $this->input->post();
		foreach ($postedtipe as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

		if ($namaKategori == "") return sendError("Nama wajib diisi");
		$savesatuan = $this->ruangan->_save_kategori(array(
			"clinic_id" 		=> $clinic_id,
			"namaKategori" 		=> $namaKategori,
		), array(), "namaKategori", "clinic_id");
		if ($savesatuan == 'exist') {
			sendError('Satuan Sudah terdaftar');
		} else {

			echo json_encode(array("message" => "Registrasi berhasil"));
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

		$allUsers = $this->ruangan->ruanganlist($clinic_id);
		$heading = array('No.', 'Nama Ruangan', 'Kelas Ruangan', 'Kategori Ruangan', 'Nomor Kamar', 'Nomor Ranjang', 'Tarif', 'Status');
		$sheet->getActiveSheet()->setTitle('Ruangan');
		$from = "A1";
		$to = "H1";
		$sheet->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
		for ($col = 'A'; $col !== 'I'; $col++) {
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
			$sheet->getActiveSheet()->setCellValue('B' . $row, $user->namaRuangan);
			$sheet->getActiveSheet()->setCellValue('C' . $row, $user->namaKelas);
			$sheet->getActiveSheet()->setCellValue('D' . $row, $user->namaKategori);
			$sheet->getActiveSheet()->setCellValue('E' . $row, $user->nomor);
			$sheet->getActiveSheet()->setCellValue('F' . $row, $user->nomor_ranjang);
			$sheet->getActiveSheet()->setCellValue('G' . $row, $user->tarif);
			$sheet->getActiveSheet()->setCellValue('H' . $row, $user->status);
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

		$sheet->getActiveSheet()->getStyle("A$start_row:H$row")->applyFromArray($styleBorder);
		$sheet->getActiveSheet()->getStyle("A1:H1")->applyFromArray($styleHeader);
		$writer = new Xlsx($sheet);
		$filename = "Ruangan";

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
		$paths = 'files/imported/';
		$uploaded = (object) single_upload($paths, "file", "import_data_ruangan__" . date("Ymdhis") . "-" . $rand, ["xls", "xlsx"]);
		$upd_file = $paths . "/" . $uploaded->file_name;
		$ext_file = $uploaded->file_ext;
		$inputFileName = $upd_file;
		$list_kelas = $this->ruangan->_list_kelas();

		$arr_kelas = [];

		foreach ($list_kelas as $item) {
			$arr_kelas[$item->namaKelas] = $item;
		}

		$list_kategori = $this->ruangan->_list_kategori();
		$arr_kategori = [];
		foreach ($list_kategori as $items) {
			$arr_kategori[$items->namaKategori] = $items;
		}
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");
		$cols_range = range("B", "G");
		$start_rows = (int) $posted['start_row'];
		$cols_settings = [
			"B" => [
				"name" => "namaRuangan",
				"alias" => "Nama Ruangan",
				"format" => "string",
				"required" => true,
				"unique" => true

			],
			"C" => [
				"name" => "idKelasruangan",
				"alias" => "Kelas Ruangan",
				"format" => "string",
				"action" => "search_id",
				"required" => true,
				"options" => $arr_kelas
			],
			"D" => [
				"name" => "idKategoriruangan",
				"alias" => "Kategori Ruangan",
				"format" => "string",
				"action" => "search_id2",
				"required" => true,
				"options" => $arr_kategori
			],
			"E" => [
				"name" => "nomor",
				"alias" => "Nomor Kamar",
				"required" => true,
				"format" => "string"
			],
			"F" => [
				"name" => "nomor_ranjang",
				"alias" => "Nomor Ranjang",
				"required" => true,
				"format" => "string"
			],
			"G" => [
				"name" => "tarif",
				"alias" => "Tarif Kamar",
				"required" => true,
				"format" => "string"
			],
			"H" => [
				"name" => "status",
				"alias" => "Status",
				"format" => "lowercase",
				"required" => true,
				"options" => ["tersedia", "penuh"]
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
										$val = $options[$val]->idKelas;
									} else {
										$is_error = true;
										if (!in_array("$alias", $errors)) array_push($errors, "$alias");
									}
								} elseif (isset($action) && $action == "search_id2") {
									if (array_key_exists($val, $options)) {
										// if found, replace value with object id
										$val = $options[$val]->idKategori;
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
					$save = $this->ruangan->_save($dta, array(), "namaRuangan", "nomor", "nomor_ranjang", "clinic_id");
					if ($save == 'exist') {
						array_push($failed, "Row " . $item['row_no'] . " | " . $dta['namaRuangan'] . " | " . $dta['nomor']);
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
