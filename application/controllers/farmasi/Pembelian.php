<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Pembelian extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('farmasi/pembelian', $this->session->userdata('site_lang'));
		$this->load->model('farmasi/Pembelian_model', 'pembelian');
		$this->load->model("/admin/Other_setting_model", "other_set");
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		isAllowed('c-pembelian');
	}

	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Pembelian";
		$this->data["page_title"] = "Pembelian";
		// $this->data["page_title_small"] = "text small dibawah page title";
		$this->data['js_control'] = "farmasi/pembelian/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'farmasi/pembelian/index', $this->data);
	}

	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->pembelian->_load_dt($posted);
		echo json_encode($data);
	}
	//menampilkan daftar obat yng akan dipilih
	public function load_dt_obat()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->pembelian->_load_dt_obat($posted);
		echo json_encode($data);
	}

	function insert_temp()
	{
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

		$data = array(
			"obat" 		=> $id_obat,
			"qty" 		=> $qty,
			"diskon" 	=> $dis,
			"total" 	=> $total,
			"isi" 		=> $isi,
			"hargabeli" => $hargabeli,
			"satuan"	=> $satuan,
			"clinic_id" => $clinic_id,
			"status" 	=> 0
		);
		$save = $this->pembelian->insert_temp($data);
		sendSuccess("Berhasil ditambahkan " . $save);
	}
	function hapus_temp()
	{
		$id = $_GET['id'];
		$this->pembelian->hapus_temp($id);
	}
	function simpan()
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
		$is_ftr_exist = $this->pembelian->isFtrExist($faktur, $clinic_id);
		if ($is_ftr_exist) {
			$faktur = $this->pembelian->searchcode($clinic_id);
		}
		$data   =   array(
			'faktur'        => $faktur,
			'tanggal'       => $tanggal,
			'tunai_kredit'  => $tunai_kredit,
			'kredit_hari'   => $kredit_hari,
			'jatuh_tempo'   => $jatuh_tempo,
			'supplier'      => $supplier,
			'subtotal'      => $subtotal,
			'diskonsub'     => $diskonsub,
			'grandtotal'    => $grandtotal,
			'bayar'         => $bayar,
			"clinic_id" 	=> $clinic_id,
			'creator_id'	=> $this->data['C_UID'] // tambahkan user_id
		);
		$save_id = $this->pembelian->simpan_transaksi_beli($data);
		// $this->pembelian->ubah_status_detail_beli($save_id);
		if ($tunai_kredit == 'kredit') $this->pembelian->kredit($save_id, $clinic_id);
		sendSuccess("Data Berhasil ditambahkan");
	}

	function load_temp()
	{
		$gets = modify_post($this->input->get());
		$clinic_id = (!isset($gets['clinic_id']) || $gets['clinic_id'] == 'default') ? getClinic()->id : htmlentities(trim($gets['clinic_id']));
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

		echo "<table class='table table-bordered rm' id='bbb' cellpadding='1' >
        <thead>    
            <tr style='background-color:lightgrey;'>
                <th>Kode</th>
                <th>Nama</th>
                <th>QTY</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Diskon</th>
                <th>Total</th>
                <th>Operasi</th>
            </tr>
        </thead>";
		$no = 1;
		$subtotal_plg = 0;
		$data =  $this->pembelian->tampilkan_temp($clinic_id)->result();
		foreach ($data as $d) {
			echo "<tbody>
            <tr id='dataobat$d->belidetail_id'>
                <td data-code='" . $d->kode . "'>$d->kode</td>
                <td>$d->nama</td>
                <td>$d->qty</td>
                <td>$d->namaSatuanobat</td>
                <td>$d->hargabeli</td>
                <td>$d->diskon</td>
                <td id='totalitas'>$d->total</td>
                <td><p onClick='hapus($d->belidetail_id)'><i class='fa fa-trash text-danger'></i></p></td>
            </tr>
            </tbody>";

			$this->data['subtotal_plg'] = $subtotal_plg += $d->total;
			json_encode($this->data['subtotal_plg']);
			$no++;
		}
		echo "</table>";
	}
	public function search_obat($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->pembelian->_search_obat(array("idObat" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
	function search_nota()
	{
		$gets = $this->input->get();
		$id = $gets['id'];
		$nota = $this->pembelian->search_nota($id);
		$dtl_nota = $this->pembelian->search_nota_detail($id);
		echo json_encode(array("nota" => $nota[0], "detail" => $dtl_nota));
	}
	public function searchcode()
	{
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$this->data['kodeunik'] = $this->pembelian->searchcode($gets['clinic_id']);
		echo json_encode($this->data['kodeunik']);
	}
	public function select2_supplier()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->pembelian->_search_select_supplier($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function select2_satuan_obat()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$keys = (isset($gets['id_obat']) && $gets['id_obat'] != '') ? $gets['id_obat'] : "";
		$search = $this->pembelian->_search_select_satuan_obat($key, $keys, $gets['clinic_id']);
		echo json_encode($search);
	}

	public function delete__($id = '')
	{
		header('Content-Type: application/json');
		// isAllowed("c-privilege^delete work type");
		$method = $this->input->method(true);
		if ($method != "DELETE") sendError(lang('msg_method_delete_required'), [], 405);
		$result = $this->pembelian->_delete(array('transaksibeli_id' => htmlentities(trim($id))));
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
		$allUsers = $this->pembelian->transaksilist($clinic_id);
		$heading = array('No. ', 'Faktur', 'Tanggal', 'Jenis bayar', 'Lama kredit', 'Jatuh tempo', 'Suplier', 'Total');
		$sheet->getActiveSheet()->setTitle('Daftar Transaksi Beli');
		$from = "A1";
		$to = "H1";
		$sheet->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
		for ($col = 'A'; $col !== 'J'; $col++) {
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
			$sheet->getActiveSheet()->setCellValue('B' . $row, $user->faktur);
			$sheet->getActiveSheet()->setCellValue('C' . $row, $user->tanggal);
			$sheet->getActiveSheet()->setCellValue('D' . $row, $user->tunai_kredit);
			$sheet->getActiveSheet()->setCellValue('E' . $row, $user->kredit_hari);
			$sheet->getActiveSheet()->setCellValue('F' . $row, $user->jatuh_tempo);
			$sheet->getActiveSheet()->setCellValue('G' . $row, $user->namaSupplier);
			$sheet->getActiveSheet()->setCellValue('H' . $row, $user->grandtotal);
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
		$filename = "Pembelian";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
		exit();
	}
	public function print_faktur()
	{
		$clinic_id = getClinic()->id;
		$gets = $this->input->get();
		if ($clinic_id == 'allclinic' && isset($gets['clinic_id'])) $clinic_id = $gets['clinic_id'];
		$id = $gets['id'];
		$company_info = $this->other_set->get_com_profile(array("clinic_id" => $clinic_id));
		$this->data['company_info'] = $company_info[0];
		$exp_id = explode(",", $id);
		$nota = $this->pembelian->search_nota($exp_id);
		$no_faktur = [];
		foreach ($nota as $nt) {
			array_push($no_faktur, str_replace("FAKTUR-", "", $nt->faktur));
		}
		$page_title = "Faktur Pembelian - " . implode(", ", $no_faktur) . " - " . date("Y-m-d his");
		$dtl_nota = $this->pembelian->search_nota_detail($exp_id);
		$group_detail = array_group_by("transaksibeli_id", $dtl_nota);


		$doc_setting = $this->other_set->get_setting_doc_requirements(array("clinic_id" => $clinic_id));
		$this->data['doc_setting'] = array_group_by("code", $doc_setting, true);

		$this->data['nota'] = $nota;
		$this->data['detail'] = $group_detail;
		$mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

		$this->data['page_title'] = "Print Faktur";
		$mpdf->SetAuthor(conf('company_name'));
		$mpdf->SetCreator(conf('company_name'));
		$mpdf->SetTitle($page_title);
		$mpdf->SetSubject($page_title);
		$mpdf->shrink_tables_to_fit = 1;
		//$mpdf->SetProtection(['print'],'','--YM^21..');
		$margin_left = 0;
		$margin_right = 0;
		$margin_top = 35;
		$margin_bottom = 0;
		$margin_header_top = 0;
		$margin_footer_bottom = 0;
		$mpdf->addPage('P', '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);
		$mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url($this->data['doc_setting']->img_doc_header->path) . '"></div>', '', true);

		// $mpdf->SetHTMLFooter('<div class="page-header-pdfs"><img src="'.base_url('/assets/img/doc-footer.png').'"></div>','',false);
		$mpdf->defaultfooterline = 30;
		$html = $this->load->view('farmasi/pembelian/print-faktur', $this->data, true);
		$mpdf->WriteHTML($html);
		$mpdf->Output($page_title . ".pdf", 'I');
	}
	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
	public function search_obat_detail($id = '')
	{
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		header('Content-Type: application/json');
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->pembelian->_search_obat_detail($id);
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
}
