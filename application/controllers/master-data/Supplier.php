<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('master-data/supplier', $this->session->userdata('site_lang'));
		$this->load->model("master-data/Supplier_model", "s");
		$this->load->model("master-data/Dokter_model", "d");
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		isAllowed('c-supplier');
	}

	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Suplier";
		$this->data["page_title"] = "Suplier";
		$this->data['js_control'] = "master-data/supplier/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'master-data/supplier/index', $this->data);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->s->_load_dt($posted);
		echo json_encode($data);
	}
	public function search__($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->s->_search(array("idSupplier" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
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

		if ($kodeSupplier == "") return sendError("Kode wajib diisi");
		if ($namaSupplier == "") return sendError("supplier Wajib diisi");
		if ($alamat == "") return sendError("Alamat Wajib diisi");
		if (!isset($posted['_id']) || $posted['_id'] == "") {
			isAllowed("c-supplier^create");
			$save = $this->s->_save(array(
				"kodeSupplier"              => $kodeSupplier,
				"clinic_id" 				=> $clinic_id,
				"namaSupplier"              => $namaSupplier,
				"notelp"                    => $notelp,
				"alamat"                    => $alamat,
				'creator_id'                => $this->data['C_UID']
			), array(), "kodeSupplier", "clinic_id");
			if ($save == 'exist') {
				sendError('kode Sudah terdaftar');
			} else {
				echo json_encode(array("message" => "Registrasi berhasil"));
			}
		} else {
			isAllowed("c-supplier^update");
			$id_supplier = htmlentities(trim($posted['_id']));
			$data = array(
				"kodeSupplier"              => $kodeSupplier,
				"clinic_id" 				=> $clinic_id,
				"namaSupplier"              => $namaSupplier,
				"notelp"                    => $notelp,
				"alamat"                    => $alamat,
				'creator_id'                => $this->data['C_UID']
			);
			$where = ["idSupplier" => $id_supplier];
			$save = $this->s->_save($data, $where, "kodeSupplier", "clinic_id");
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
		isAllowed("c-supplier^delete");
		$method = $this->input->method(true);
		if ($method != "DELETE") sendError(lang('msg_method_delete_required'), [], 405);
		$result = $this->s->_delete(array('idSupplier' => htmlentities(trim($id))));
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
	public function export_()
	{
		$sheet = new Spreadsheet();
		$posted = $this->input->post();
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : htmlentities(trim($posted['clinic_id']));
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

		$allUsers = $this->s->supplierlist($clinic_id);
		$heading = array('No. ', 'Kode Supplier', 'Nama Supplier', 'Alamat', 'Nomor Telp');
		$sheet->getActiveSheet()->setTitle('Supplier');
		$from = "A1";
		$to = "E1";
		$sheet->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
		for ($col = 'A'; $col !== 'F'; $col++) {
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
			$sheet->getActiveSheet()->setCellValue('B' . $row, $user->kodeSupplier);
			$sheet->getActiveSheet()->setCellValue('C' . $row, $user->namaSupplier);
			$sheet->getActiveSheet()->setCellValue('D' . $row, $user->alamat);
			$sheet->getActiveSheet()->setCellValue('E' . $row, $user->notelp);
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

		$sheet->getActiveSheet()->getStyle("A$start_row:E$row")->applyFromArray($styleBorder);
		$sheet->getActiveSheet()->getStyle("A1:E1")->applyFromArray($styleHeader);
		$writer = new Xlsx($sheet);
		$filename = "Supplier";

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
		$uploaded = (object) single_upload($paths, "file", "import_data_supplier__" . date("Ymdhis") . "-" . $rand, ["xls", "xlsx"]);
		$upd_file = $paths . "/" . $uploaded->file_name;
		$ext_file = $uploaded->file_ext;
		$inputFileName = $upd_file;
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

		$cols_range = range("B", "H");
		$start_rows = (int) $posted['start_row'];
		$cols_settings = [
			"B" => [
				"name" => "kodeSupplier",
				"alias" => "Kode Supplier",
				"format" => "string",
				"required" => true,
				"unique" => true

			],
			"C" => [
				"name" => "namaSupplier",
				"alias" => "Nama Supplier",
				"format" => "string",
				"required" => true,
			],
			"D" => [

				"name" => "alamat",
				"alias" => "Alamat Supplier",
				"format" => "string",
				"required" => true,
			],
			"E" => [

				"name" => "notelp",
				"alias" => "Nomor telp",
				"format" => "string",
				"required" => true,
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
					$save = $this->s->_save($dta, array(), "kodeSupplier", "clinic_id");
					if ($save == 'exist') {
						array_push($failed, "Row " . $item['row_no'] . " | " . $dta['kodeSupplier'] . " | " . $dta['namaSupplier']);
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
