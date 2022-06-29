<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Obat extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('farmasi/obat', $this->session->userdata('site_lang'));
		$this->load->model('farmasi/Obat_model', 'obat');
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		isAllowed('c-obat');
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Obat";
		$this->data["page_title"] = "Obat";
		$this->data['js_control'] = "farmasi/obat/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'farmasi/obat/index', $this->data);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->obat->_load_dt($posted);
		echo json_encode($data);
	}
	public function search__($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->obat->_search(array("idObat" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
	public function searchcode()
	{
		$gets = $this->input->get();
		$gets = modify_post($gets);
		// print_r($gets);
		$this->data['kodeunik'] = $this->obat->searchcode($gets['clinic_id']);
		echo json_encode($this->data['kodeunik']);
	}
	public function select2_satuan_beli()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->obat->_search_select_satuan_beli($key, $gets['clinic_id']);
		echo json_encode($search);
	}

	public function select2_satuan_obat()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->obat->_search_select_satuan_obat($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function select2_kategori()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->obat->_search_select_kategori($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function select2_supplier()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->obat->_search_select_supplier($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function save__()
	{
		header('Content-Type: application/json');
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = (gettype($value) == 'string') ? htmlentities(trim($value)) : $value;
		}
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

		if ($kode == "") return sendError("Kode wajib diisi");
		if ($nama == "") return sendError("Nama wajib diisi");
		if ($kategori == "") return sendError("Kategori wajib diisi");
		if ($satuanbeli == "") return sendError("Satuan wajib diisi");
		if ($stok == "") return sendError("Stok wajib diisi");
		if ($supplier == "") return sendError("Supplier wajib diisi");
		
		if (!isset($posted['_id']) || $posted['_id'] == "") {
			isAllowed("c-obat^create");
			$is_kode_exist = $this->obat->isKodeExist($kode, $clinic_id);
			if ($is_kode_exist) {
				$kode = $this->obat->searchcode($clinic_id);
			}
			$data = array(
				"kode"          => $kode,
				"clinic_id" 	=> $clinic_id,
				"nama"          => $nama,
				"kategori"      => $kategori,
				"satuanbeli"    => $satuanbeli,
				"hargaBeli"     => $hargaBeli,
				"stok"          => $stok,
				"stokmin"       => $stokmin,
				"supplier"      => $supplier,
				"expired"       => $expired,
				"creator_id"	=> $this->data['C_UID']
			);
			if($satuan[0]=="" || $isi[0]=="" || $laba[0]=="" || $hargabeli[0]=="" || $harga[0]=="") return sendError("Satuan Jual Beli harus diisi setidaknya pada baris pertama");
			$save = $this->obat->_save($data, array(), "kode", "clinic_id");
			if (isset($_onedit_skip) && !empty($_onedit_skip)) {
				$data_obat_detail = array();
				foreach ($isi as $key => $value) {
					$data_obat_detail[] = array(
						'fk_obat' 	=> $save,
						'isi' 		=> $value === '' ? null : $isi[$key],
						'satuan' 	=> $value === '' ? null : $satuan[$key],
						'hargabeli' => $value === '' ? null : $hargabeli[$key],
						'harga' 	=> $value === '' ? null : $harga[$key],
						'laba' 		=> $value === '' ? null : $laba[$key],
					);
				}
				$this->obat->_save_detail_obat($data_obat_detail);
			}
			if ($save == 'exist') {
				sendError('Obat Sudah terdaftar');
			} else {
				echo json_encode(array("message" => "Penambahan berhasil"));
			}
		} else {
			isAllowed("c-obat^update");
			$id_obat = htmlentities(trim($posted['_id']));
			$data = array(
				"kode"          => $kode,
				"clinic_id" 	=> $clinic_id,
				"nama"          => $nama,
				"kategori"      => $kategori,
				"satuanbeli"    => $satuanbeli,
				"hargaBeli"     => $hargaBeli,
				"stok"          => $stok,
				"stokmin"       => $stokmin,
				"supplier"      => $supplier,
				"expired"       => $expired,
				"creator_id"	=> $this->data['C_UID']
			);
			$where = ["idObat" => $id_obat];
			$save = $this->obat->_save($data, $where, "kode", "clinic_id");
			if (isset($_onedit_skip) && !empty($_onedit_skip)) {
				$data_obat_detail = array();
				foreach ($isi as $key => $value) {
					$data_obat_detail[] = array(
						'fk_obat' 	=> $id_obat,
						'isi' 		=> $value === '' ? null : $isi[$key],
						'satuan' 	=> $value === '' ? null : $satuan[$key],
						'hargabeli' => $value === '' ? null : $hargabeli[$key],
						'harga' 	=> $value === '' ? null : $harga[$key],
						'laba' 		=> $value === '' ? null : $laba[$key],
					);
				}
				$this->obat->_update_detail_obat($id_obat);
				$this->obat->_save_detail_obat($data_obat_detail);
			}
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
		isAllowed("c-obat^delete");
		$method = $this->input->method(true);
		if ($method != "DELETE") sendError(lang('msg_method_delete_required'), [], 405);
		$result = $this->obat->_delete(array('idObat' => htmlentities(trim($id))));
		$result2 = $this->obat->_delete_detail(array('fkobat' => htmlentities(trim($id))));
		if ($result == 1) {
			sendSuccess(lang('msg_delete_success'), []);
		} else {
			sendError(lang('msg_delete_failed'));
		}
	}

	public function save_satuan_beli()
	{
		header('Content-Type: application/json');
		isAllowed("c-obat^create");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$postedtipe = $this->input->post();
		foreach ($postedtipe as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

		if ($namaSatuanbeli == "") return sendError("Nama wajib diisi");
		$savesatuan = $this->obat->_savesatuan_beli(array(
			"namaSatuanbeli" => $namaSatuanbeli,
			"clinic_id" 	=> $clinic_id,
		), array(), "namaSatuanbeli", "clinic_id");
		if ($savesatuan == 'exist') {
			sendError('Satuan Sudah terdaftar');
		} else {

			echo json_encode(array("message" => "Registrasi berhasil"));
		}
	}

	public function save_satuan_obat()
	{
		header('Content-Type: application/json');
		isAllowed("c-obat^create");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$postedtipe = $this->input->post();
		foreach ($postedtipe as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

		if ($namaSatuanobat == "") return sendError("Nama wajib diisi");
		$savesatuanobat = $this->obat->_savesatuan_obat(array(
			"namaSatuanobat" => $namaSatuanobat,
			"clinic_id" 	=> $clinic_id,
		), array(), "namaSatuanobat", "clinic_id");
		if ($savesatuanobat == 'exist') {
			sendError('Satuan Sudah terdaftar');
		} else {

			echo json_encode(array("message" => "Registrasi berhasil"));
		}
	}

	public function save_kategori()
	{
		header('Content-Type: application/json');
		isAllowed("c-obat^create");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$postedtipe = $this->input->post();
		foreach ($postedtipe as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

		if ($namaKategoriobat == "") return sendError("Nama wajib diisi");
		$savetipe = $this->obat->_savekategori(array(
			"namaKategoriobat" 	=> $namaKategoriobat,
			"clinic_id" 		=> $clinic_id,
		), array(), "namaKategoriobat", "clinic_id");
		if ($savetipe == 'exist') {
			sendError('Kategori obat Sudah terdaftar');
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
		$allUsers = $this->obat->obatlist($clinic_id);
		$heading = array('No. ', 'Kode', 'Nama obat', 'Kategori Obat', 'Stok', 'Harga Beli', 'Harga Jual', 'Satuan Jual', 'Isi', 'Supplier');

		$sheet->getActiveSheet()->setTitle('Daftar Obat');
		$from = "A1";
		$to = "J1";
		$sheet->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
		for ($col = 'A'; $col !== 'L'; $col++) {
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
			$sheet->getActiveSheet()->setCellValue('B' . $row, $user->kode);
			$sheet->getActiveSheet()->setCellValue('C' . $row, $user->nama);
			$sheet->getActiveSheet()->setCellValue('D' . $row, $user->namaKategoriobat);
			$sheet->getActiveSheet()->setCellValue('E' . $row, $user->stok . ' ' . $user->namaSatuanbeli);
			$sheet->getActiveSheet()->setCellValue('F' . $row, $user->hargabeli);
			$sheet->getActiveSheet()->setCellValue('G' . $row, $user->harga);
			$sheet->getActiveSheet()->setCellValue('H' . $row, $user->namaSatuan);
			$sheet->getActiveSheet()->setCellValue('I' . $row, $user->isi);
			$sheet->getActiveSheet()->setCellValue('J' . $row, $user->namaSupplier);
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

		$sheet->getActiveSheet()->getStyle("A$start_row:J$row")->applyFromArray($styleBorder);
		$sheet->getActiveSheet()->getStyle("A1:J1")->applyFromArray($styleHeader);
		$writer = new Xlsx($sheet);
		$filename = "Obat";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
		exit();
	}

	public function barcode($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->obat->barcode_search(array("idObat" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
	private function validateDate($date, $format = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	public function import_()
	{
		ini_set('max_execution_time', 0);
		header('Content-Type: application/json');
		if(isAllowed('c-obat^import',true)===false){
			return sendError("Akun anda tidak diizinkan untuk import data obat");
		} 
		$this->load->helper('uploadfile');
		$rand = rand(1000, 9999);
		$import_id = "import:" . $this->data['C_UID'] . "-" . date("Ymdhis") . "-" . $rand;
		$posted = $this->input->post();
		
		$clinic_id = (!isset($posted['clinic_id']) || $posted['clinic_id'] == 'default') ? getClinic()->id : htmlentities(trim($posted['clinic_id']));;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");
		echo $clinic_id;
		$paths = 'files/imported/';
		$uploaded = (object) single_upload($paths, "file", "import_data_obat__" . date("Ymdhis") . "-" . $rand, ["xls", "xlsx"]);
		$upd_file = $paths . "/" . $uploaded->file_name;
		$ext_file = $uploaded->file_ext;
		$inputFileName = $upd_file;
		$arr_supplier = arrayKeyVal($this->obat->_list_supplier($clinic_id));
		$arr_satuan_obat = arrayKeyVal($this->obat->_list_satuan_obat($clinic_id));
		$arr_kategori_obat = arrayKeyVal($this->obat->_list_kategori_obat($clinic_id));
		$arr_satuan_beli = arrayKeyVal($this->obat->_list_satuan_beli($clinic_id));
		$cols_range = range("B", "V");
		$start_rows = (int) $posted['start_row'];
		$cols_settings = [
			"B" => [
				"name" => "kode",
				"alias" => "Kode Obat",
				"format" => "string",
				"required" => true,
				"unique" => true

			],
			"C" => [
				"name" => "nama",
				"alias" => "Nama Obat",
				"format" => "string",
				"required" => true
			],
			"D" => [
				"name" => "satuanbeli",
				"alias" => "Satuan Beli",
				"format" => "string",
				"required" => true,
				"search_id"=>$arr_satuan_beli
			],
			"E" => [

				"name" => "kategori",
				"alias" => "Kategori",
				"format" => "string",
				"required"=>true,
				"search_id"=>$arr_kategori_obat
			],
			"F" => [
				"name" => "supplier",
				"alias" => "Supplier",
				"format" => "string",
				"required"=>true,
				"search_id"=>$arr_supplier
			],
			"G" => [
				"name" => "stok",
				"alias" => "Stok",
				"format" => "string",
				"required"=>true
			],
			"H" => [
				"name" => "stokmin",
				"alias" => "Stok Minimal",
				"format" => "string",
				"required"=>true
			],
			"I" => [
				"name" => "hargaBeli",
				"alias" => "Harga Beli",
				"format" => "string",
				"required"=>true
			],
			"J" => [
				"name" => "expired",
				"alias" => "Expire",
				"format" => "date",
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
							$search_id = (isset($cols_settings["$col"]['search_id'])) ? $cols_settings["$col"]['search_id'] : false;
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
											// if format valid, make sure year is same. to prevent change to 1970
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
							if(isset($cols_settings["$col"]['search_id'])){
								$searchIn=$cols_settings["$col"]['search_id'];
								if(isset($searchIn[$val])){
									$val=$searchIn[$val];
								}else{
									// id not exist:: auto save
									// $sv=$this->obat->_save
									$colname=$cols_settings["$col"]['name'];
									$sv=false;
									if($colname=='kategori' && $val!=''){
										$sv=$this->obat->_save_kategori_obat($val,$clinic_id);
										$cols_settings["$col"]['search_id'][$val]=$sv;
										$val=$sv; // replace val with saved id
									}else
									if($colname=='satuanbeli' && $val!=''){
										$sv=$this->obat->_save_satuan_beli($val,$clinic_id);
										$cols_settings["$col"]['search_id'][$val]=$sv;
										$val=$sv; // replace val with saved id
									}
									if(!$sv){
										$is_error = true;
										if (!in_array("$alias", $errors) && $val!=''){ 
											array_push($errors, "$alias: <i>$val tidak terdaftar</i>");
										}else
										if(!in_array("$alias", $errors) && $val==''){
											array_push($errors, "$alias: <i>Wajib diisi</i>");
										}
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
					$cells['create_at'] = date('Y-m-d H:i:s');
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
					$save = $this->obat->_save_import($dta, 'kode', 'clinic_id');
					if ($save == 'exist') {
						array_push($failed, "Row " . $item['row_no'] . " | " . $dta['kode'] . " | " . $dta['nama']);
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
