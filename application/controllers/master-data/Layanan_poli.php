<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Layanan_poli extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('master-data/layanan_poli', $this->session->userdata('site_lang'));
		$this->load->model("master-data/layanan_poli_model", "layanan_poli");
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		isAllowed('c-l-poli');
	}

	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Layanan Poli";
		$this->data['page_title'] = "Layanan Poli";
		$this->data['js_control'] = "master-data/layanan_poli/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'master-data/layanan_poli/index', $this->data);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->layanan_poli->_load_dt($posted);
		echo json_encode($data);
	}

	public function search__($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->layanan_poli->_search(array("id_layanan_poli" => $id));
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

		if ($nama_layanan_poli == "") return sendError("Masukkan nama layanan poli");
		if ($kode_layanan_poli == "") return sendError("Masukkan kode layanan poli");
		if (!isset($posted['_id']) || $posted['_id'] == "") {
			isAllowed("c-l-poli^create");
			$save = $this->layanan_poli->_save(array(
				"id_poli"               => $id_poli,
				"clinic_id" 			=> $clinic_id,
				"nama_layanan_poli"     => $nama_layanan_poli,
				"harga_layanan_poli"    => $harga_layanan_poli,
				"kode_layanan_poli"     => $kode_layanan_poli,
				"tarif_dokter_percent"     => $tarif_dokter_percent,
				"tarif_dokter"     => $tarif_dokter,
				'creator_id'            => $this->data['C_UID']
			), array(), "kode_layanan_poli", "clinic_id");
			if ($save == 'exist') {
				sendError('Sudah terdaftar');
			} else {
				echo json_encode(array("message" => "Data berhasil diinput"));
			}
		} else {
			isAllowed("c-l-poli^update");
			$id_layanan_poli = htmlentities(trim($posted['_id']));
			$data = array(
				"id_poli"               => $id_poli,
				"clinic_id" 			=> $clinic_id,
				"nama_layanan_poli"     => $nama_layanan_poli,
				"harga_layanan_poli"    => $harga_layanan_poli,
				"kode_layanan_poli"     => $kode_layanan_poli,
				"tarif_dokter_percent"     => $tarif_dokter_percent,
				"tarif_dokter"     => $tarif_dokter,
				'creator_id'            => $this->data['C_UID']
			);
			$where = ["id_layanan_poli" => $id_layanan_poli];
			$save = $this->layanan_poli->_save($data, $where, "kode_layanan_poli", "clinic_id");
			if ($save === "exist") {
				sendError("Data telah tersedia");
			} else {
				$dta = array(
					"message" => "Update data berhasil",
					"action" => "call_print"
				);
				echo json_encode($dta);
			}
		}
	}

	public function delete__($id = '')
	{
		header('Content-Type: application/json');
		isAllowed("c-l-poli^delete");
		$method = $this->input->method(true);
		if ($method != "DELETE") sendError(lang('msg_method_delete_required'), [], 405);
		$result = $this->layanan_poli->_delete(array('id_layanan_poli' => htmlentities(trim($id))));
		if ($result == 1) {
			sendSuccess(lang('msg_delete_success'), []);
		} else {
			sendError(lang('msg_delete_failed'));
		}
	}

	public function select2_()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->layanan_poli->_search_select2($key, $gets['clinic_id']);
		echo json_encode($search);
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

		$allUsers = $this->layanan_poli->layanan_list($clinic_id);
		$heading = array('No.', 'Poliklinik', 'Layanan Poliklinik', 'Kode Layanan', 'Harga Layanan');
		$sheet->getActiveSheet()->setTitle('Layanan Poliklinik');
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
			$sheet->getActiveSheet()->setCellValue('B' . $row, $user->namaPoli);
			$sheet->getActiveSheet()->setCellValue('C' . $row, $user->nama_layanan_poli);
			$sheet->getActiveSheet()->setCellValue('D' . $row, $user->kode_layanan_poli);
			$sheet->getActiveSheet()->setCellValue('E' . $row, $user->harga_layanan_poli);
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
		$filename = "Layanan Poliklinik";

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
		$uploaded = (object) single_upload($paths, "file", "import_data_layanan_poli__" . date("Ymdhis") . "-" . $rand, ["xls", "xlsx"]);
		$upd_file = $paths . "/" . $uploaded->file_name;
		$ext_file = $uploaded->file_ext;
		$inputFileName = $upd_file;
		$list_poli = $this->layanan_poli->_list_poli();
		$arr_poli = [];
		foreach ($list_poli as $item) {
			$arr_poli[$item->namaPoli] = $item;
		}
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");
		$cols_range = range("B", "E");
		$start_rows = (int) $posted['start_row'];
		$cols_settings = [
			"B" => [
				"name" => "id_poli",
				"alias" => "Poliklinik",
				"format" => "string",
				"action" => "search_id",
				"options" => $arr_poli

			],
			"C" => [
				"name" => "nama_layanan_poli",
				"alias" => "Nama Layanan Poli",
				"format" => "string",
				"required" => true
			],
			"D" => [
				"name" => "kode_layanan_poli",
				"alias" => "Kode Layanan Poli",
				"format" => "string",
				"required" => true,
				"unique" => true
			],
			"E" => [
				"name" => "harga_layanan_poli",
				"alias" => "Harga Layanan Poli",
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
							// check if any options, value must be inside the options
							if (isset($cols_settings["$col"]['options']) && !$ignore) {
								$options = $cols_settings["$col"]['options'];
								if (isset($action) && $action == "search_id") {
									if (array_key_exists($val, $options)) {
										// if found, replace value with object id
										$val = $options[$val]->idPoli;
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
					$save = $this->layanan_poli->_save($dta, array(), "kode_layanan_poli", "clinic_id");
					if ($save == 'exist') {
						array_push($failed, "Row " . $item['row_no'] . " | " . $dta['kode_layanan_poli'] . " | " . $dta['nama_layanan_poli']);
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
