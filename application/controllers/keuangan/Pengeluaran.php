<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Pengeluaran extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('keuangan/pengeluaran', $this->session->userdata('site_lang'));
		$this->load->model('keuangan/Pengeluaran_model', 'pengeluaran');
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		isAllowed('c-pengeluaran');
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Pengeluaran";
		$this->data["page_title"] = "Pengeluaran";
		// $this->data["page_title_small"] = "text small dibawah page title";
		$this->data['js_control'] = "keuangan/pengeluaran/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'keuangan/pengeluaran/index', $this->data);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->pengeluaran->_load_dt($posted);
		echo json_encode($data);
	}

	public function search__($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->pengeluaran->_search(array("biaya_id" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}

	public function select2_kategori()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->pengeluaran->_search_select_kategori($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function save__()
	{
		header('Content-Type: application/json');
		isAllowed("c-pengeluaran^create");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");
		if ($nama == "") return sendError("Nama wajib diisi");
		if ($kategori_biaya == "") return sendError("Kategori wajib diisi");
		if ($total == "") return sendError("Total wajib diisi");
		if (!isset($posted['_id']) || $posted['_id'] == "") {
			$save = $this->pengeluaran->_save(array(
				"nama"            	=> $nama,
				"clinic_id" 		=> $clinic_id,
				"kategori_biaya"    => $kategori_biaya,
				"total"             => $total,
				"keterangan"        => $keterangan,
				"tanggal" 			=> date("Y-m-d"),
				'creator_id'    	=> $this->data['C_UID']
			), array(), "nama", "tanggal", "clinic_id");
			if ($save == 'exist') {
				sendError('Sudah terdaftar');
			} else {
				echo json_encode(array("message" => "Penambahan berhasil"));
			}
		} else {
			isAllowed("c-pengeluaran^update");
			$biaya_id = htmlentities(trim($posted['_id']));
			$data = array(
				"nama"            	=> $nama,
				"clinic_id" 		=> $clinic_id,
				"kategori_biaya"    => $kategori_biaya,
				"total"             => $total,
				"keterangan"        => $keterangan,
				"tanggal" 			=> date("Y-m-d"),
				'creator_id'    	=> $this->data['C_UID']
			);
			$where = ["biaya_id" => $biaya_id];
			$save = $this->pengeluaran->_save($data, $where, "nama", "tanggal", "clinic_id");
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
		isAllowed("c-pengeluaran^delete");
		$method = $this->input->method(true);
		if ($method != "DELETE") sendError(lang('msg_method_delete_required'), [], 405);
		$result = $this->pengeluaran->_delete(array('biaya_id' => htmlentities(trim($id))));
		if ($result == 1) {
			sendSuccess(lang('msg_delete_success'), []);
		} else {
			sendError(lang('msg_delete_failed'));
		}
	}

	public function save_kategori()
	{
		header('Content-Type: application/json');
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$postedtipe = $this->input->post();
		foreach ($postedtipe as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");
		if ($nama_kategori == "") return sendError("Nama wajib diisi");
		$savetipe = $this->pengeluaran->_savekategori(array(
			"nama_kategori" => $nama_kategori,
			"clinic_id" 		=> $clinic_id,
		), array(), "nama_kategori", "clinic_id");
		if ($savetipe == 'exist') {
			sendError('Kategori Sudah terdaftar');
		} else {

			echo json_encode(array("message" => "Registrasi berhasil"));
		}
	}
	public function export_()
	{
		$sheet = new Spreadsheet();
		$posted = $this->input->post();
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : htmlentities(trim($posted['clinic_id']));
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

		$allUsers = $this->pengeluaran->pengeluaranlist($clinic_id);
		$heading = array('No.', 'Pengeluaran', 'Tanggal', 'Kategori', 'Total', 'Keterangan');
		$sheet->getActiveSheet()->setTitle('Pengeluaran lain-lain');
		$from = "A1";
		$to = "F1";
		$sheet->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
		for ($col = 'A'; $col !== 'G'; $col++) {
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
			$sheet->getActiveSheet()->setCellValue('B' . $row, $user->nama);
			$sheet->getActiveSheet()->setCellValue('C' . $row, $user->tanggal);
			$sheet->getActiveSheet()->setCellValue('D' . $row, $user->nama_kategori);
			$sheet->getActiveSheet()->setCellValue('E' . $row, $user->total);
			$sheet->getActiveSheet()->setCellValue('F' . $row, $user->keterangan);
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

		$sheet->getActiveSheet()->getStyle("A$start_row:F$row")->applyFromArray($styleBorder);
		$sheet->getActiveSheet()->getStyle("A1:F1")->applyFromArray($styleHeader);
		$writer = new Xlsx($sheet);
		$filename = "Pengeluaran lain-lain";

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
}
