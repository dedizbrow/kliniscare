<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('report', $this->session->userdata('site_lang'));
		$this->load->model('Report_model', 'report');
		$this->load->model("/admin/Other_setting_model", "other_set");
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Laporan";
		// $this->data["page_title_small"] = "text small dibawah page title";
		$this->data["page_title"] = "Laporan";
		$this->data['js_control'] = "report/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'report/index', $this->data);
	}
	// fee dokter
	public function fee_dokter()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Fee Dokter";
		// $this->data["page_title_small"] = "text small dibawah page title";
		$this->data["page_title"] = "Laporan Fee Dokter";
		$this->data['js_control'] = "report/fee-dokter.js";
		$this->data['datatable'] = true;
		$this->template->load(get_template(), 'report/fee-dokter', $this->data);
	}
	public function load_dt_fee_dokter()
	{
		header('Content-Type: application/json');
		// requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$gets=$this->input->get();
		$is_export=(isset($gets['export']) && $gets['export']=='true') ? true : false;
		$posted=(!$is_export) ? modify_post($posted) : modify_post($gets);
		$data = $this->report->_load_dt_fee_dokter($posted,$is_export);
		if(!$is_export){
			echo json_encode($data);
		}else{
			$title="Data Fee Dokter";
			$spreadsheet = new Spreadsheet();
			$spreadsheet->getProperties()
			// ->setCreator(conf('company_name'))
			// ->setLastModifiedBy()
			->setTitle($title)
			->setSubject($title)
			->setDescription($title);
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
			
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setTitle("Sheet 1");
			$col_no="A";$col_tgl="B"; $col_poli="C"; $col_dokter="D"; 
			$col_tindakan="E";$col_tarif_dokter="F";$col_tarif_klinik="G";$col_total_biaya="H";
			$sheet->getColumnDimension($col_no)->setWidth(7);
			$sheet->getColumnDimension($col_tgl)->setWidth(12);
			$sheet->getColumnDimension($col_poli)->setWidth(20);
			$sheet->getColumnDimension($col_dokter)->setWidth(20);
			$sheet->getColumnDimension($col_tindakan)->setWidth(22);
			$sheet->getColumnDimension($col_tarif_dokter)->setWidth(12);
			$sheet->getColumnDimension($col_tarif_klinik)->setWidth(12);
			$sheet->getColumnDimension($col_total_biaya)->setWidth(12);
			$row=1;
			$sheet->mergeCells($col_no."$row:".$col_total_biaya.$row);
			$sheet->setCellValue("A$row", strtoupper($title));
			$sheet->getStyle("A$row")->getAlignment()->setHorizontal('center');
			$sheet->getStyle("A$row")->getFont()->setBold( true );
			
			// $row++;
			// $sheet->setCellValue("A$row","Poli");
			// $sheet->setCellValue("B$row",$poli);
			// $sheet->setCellValue("A$row","Dokter");
			// $sheet->setCellValue("B$row",$dokter);

			$row=3;
			$start_row_content=$row;
			$sheet->setCellValue($col_no.$row, 'No');
			$sheet->setCellValue($col_tgl.$row, "Tanggal");
			$sheet->setCellValue($col_poli.$row, "Poli");
			$sheet->setCellValue($col_dokter.$row, "Nama Dokter");
			$sheet->setCellValue($col_tindakan.$row, "Tindakan Medis");
			$sheet->setCellValue($col_tarif_dokter.$row, "Tarif Dokter");
			$sheet->setCellValue($col_tarif_klinik.$row, "Tarif Klinik");
			$sheet->setCellValue($col_total_biaya.$row, "Total");
			$row++; 
			$no=1;
			$total_tarif_dokter=0;$total_tarif_klinik=0; $total_biaya=0;
			foreach($data as $dt){
				$dt->tarif_klinik=$dt->biaya-$dt->tarif_dokter;
				$sheet->setCellValue($col_no.$row, $no);
				$sheet->setCellValue($col_tgl.$row, $dt->tgl_periksa);
				$sheet->setCellValue($col_poli.$row, $dt->namaPoli);
				$sheet->setCellValue($col_dokter.$row, $dt->namaDokter);
				$sheet->setCellValue($col_tindakan.$row, $dt->nama_layanan_poli);
				$sheet->setCellValue($col_tarif_dokter.$row, $dt->tarif_dokter);
				$sheet->setCellValue($col_tarif_klinik.$row, $dt->tarif_klinik);
				$sheet->setCellValue($col_total_biaya.$row, $dt->biaya);
				$total_tarif_dokter+=$dt->tarif_dokter;
				$total_tarif_klinik+=$dt->tarif_klinik;
				$total_biaya+=$dt->biaya;
				$row++;
				$no++;
			}
			$sheet->getStyle("$col_no$start_row_content:$col_total_biaya$row")->applyFromArray($styleBorder);
			$sheet->getStyle("$col_no$start_row_content:$col_no$row")->getAlignment()->setHorizontal('center');

			$sheet->setCellValue($col_tgl.$row, "Total");
			$sheet->setCellValue($col_tarif_dokter.$row, $total_tarif_dokter);
			$sheet->setCellValue($col_tarif_klinik.$row, $total_tarif_klinik);
			$sheet->setCellValue($col_total_biaya.$row, $total_biaya);
			$sheet->getStyle("$col_no$row:$col_total_biaya$row")->getFont()->setBold( true );

			
			$writer = new Xlsx($spreadsheet);
			$filename = $title;
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
			header('Cache-Control: max-age=0');
			$writer->save('php://output');	
		}
	}
	// end fee dokter
	public function kunjungan_pasien()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Kunjungan Pasien";
		// $this->data["page_title_small"] = "text small dibawah page title";
		$this->data["page_title"] = "Laporan Kunjungan Pasien";
		$this->data['js_control'] = "report/kunjungan-pasien.js";
		$this->data['datatable'] = true;
		$this->template->load(get_template(), 'report/kunjungan-pasien', $this->data);
	}
	public function load_dt_kunjungan_pasien()
	{
		header('Content-Type: application/json');
		// requiredMethod('POST');
		$posted = $this->input->input_stream();
		$gets=$this->input->get();
		$is_export=(isset($gets['export']) && $gets['export']=='true') ? true : false;
		$posted=(!$is_export) ? modify_post($posted) : modify_post($gets);
		$data = $this->report->_load_dt_kunjungan_pasien($posted,$is_export);
		if(!$is_export){
			echo json_encode($data);
		}else{
			$title="Data Kunjungan Pasien";
			// if(isset($posted['poli'])) $title.=" - ".$posted['poli'];
			// if(isset($posted['start_date'])) $title.=" - ".$posted['start_date'];
			$spreadsheet = new Spreadsheet();
			$spreadsheet->getProperties()
			// ->setCreator(conf('company_name'))
			// ->setLastModifiedBy()
			->setTitle($title)
			->setSubject($title)
			->setDescription($title);
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
			
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setTitle("Sheet 1");
			$col_no="A";$col_tgl="B"; $col_poli="C"; $col_name="D"; $col_nomor_rm="E";$col_alasan="F";$col_catatan="G";
			$sheet->getColumnDimension($col_no)->setWidth(7);
			$sheet->getColumnDimension($col_tgl)->setWidth(12);
			$sheet->getColumnDimension($col_poli)->setWidth(20);
			$sheet->getColumnDimension($col_name)->setWidth(22);
			$sheet->getColumnDimension($col_nomor_rm)->setWidth(12);
			$sheet->getColumnDimension($col_alasan)->setWidth(24);
			$sheet->mergeCells($col_no."1:".$col_catatan."1");
			$sheet->setCellValue("A1", strtoupper($title));
			$sheet->getStyle("A1")->getAlignment()->setHorizontal('center');
			$sheet->getStyle("A1")->getFont()->setBold( true );
			
			$row=3;
			$start_row_content=$row;
			$sheet->setCellValue($col_no.$row, 'No');
			$sheet->setCellValue($col_tgl.$row, "Tanggal");
			$sheet->setCellValue($col_poli.$row, "Poli");
			$sheet->setCellValue($col_name.$row, "Nama");
			$sheet->setCellValue($col_nomor_rm.$row, "No RM");
			$sheet->setCellValue($col_alasan.$row, "Alasan Berobat");
			$sheet->setCellValue($col_catatan.$row, "Keterangan");
			$row++; 
			$no=1;
			foreach($data as $dt){
				$status=($dt->status_bayar==1) ? "SELESAI":"";
				$sheet->setCellValue($col_no.$row, $no);
				$sheet->setCellValue($col_tgl.$row, $dt->tgl_periksa);
				$sheet->setCellValue($col_poli.$row, $dt->namaPoli);
				$sheet->setCellValue($col_name.$row, $dt->nama_lengkap);
				$sheet->setCellValue($col_nomor_rm.$row, $dt->nomor_rm);
				$sheet->setCellValue($col_alasan.$row, $dt->alasan_datang);
				$sheet->setCellValue($col_catatan.$row, $status);
				$row++;
				$no++;
			}
			$sheet->getStyle("$col_no$start_row_content:$col_catatan$row")->applyFromArray($styleBorder);
			$sheet->getStyle("$col_no$start_row_content:$col_no$row")->getAlignment()->setHorizontal('center');
			$writer = new Xlsx($spreadsheet);
			$filename = $title;
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
			header('Cache-Control: max-age=0');
			$writer->save('php://output');	
		}
	}
	// end kunjungan pasien
	public function summary_diagnosa()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Summary Diagnosa Penyakit";
		// $this->data["page_title_small"] = "text small dibawah page title";
		$this->data["page_title"] = "Laporan Ringkasan Diagnosa Penyakit";
		$this->data['js_control'] = "report/summary-diagnosa.js";
		$this->data['datatable'] = true;
		$this->template->load(get_template(), 'report/summary-diagnosa', $this->data);
	}
	public function load_dt_summary_diagnosa()
	{
		header('Content-Type: application/json');
		// requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$gets=$this->input->get();
		$is_export=(isset($gets['export']) && $gets['export']=='true') ? true : false;
		$posted=(!$is_export) ? modify_post($posted) : modify_post($gets);
		$data = $this->report->_load_dt_summary_diagnosa($posted,$is_export);
		if(!$is_export){
			echo json_encode($data);
		}else{
			$title="Ringkasan Diagnosa Penyakit ";
			$spreadsheet = new Spreadsheet();
			$spreadsheet->getProperties()
			// ->setCreator(conf('company_name'))
			// ->setLastModifiedBy()
			->setTitle($title)
			->setSubject($title)
			->setDescription($title);
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
			
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setTitle($posted['start_date']);
			$col_no="A";$col_diagnosa="B"; $col_jumlah="C"; 
			$sheet->getColumnDimension($col_no)->setWidth(7);
			$sheet->getColumnDimension($col_diagnosa)->setWidth(20);
			$sheet->getColumnDimension($col_jumlah)->setWidth(7);
			$sheet->mergeCells($col_no."1:".$col_jumlah."1");
			$sheet->setCellValue("A1", strtoupper($title));
			$sheet->getStyle("A1")->getAlignment()->setHorizontal('center');
			$sheet->getStyle("A1")->getFont()->setBold( true );

			$sheet->mergeCells($col_no."2:".$col_jumlah."2");
			$title_second=$posted['start_date'];
			if($posted['start_date']!=$posted['end_date']) $title_second.=" - ".$posted['end_date'];
			$sheet->setCellValue("A2", strtoupper($title_second));
			$sheet->getStyle("A2")->getAlignment()->setHorizontal('center');
			$sheet->getStyle("A2")->getFont()->setBold( true );
			
			$row=4;
			$start_row_content=$row;
			$sheet->setCellValue($col_no.$row, 'No');
			$sheet->setCellValue($col_diagnosa.$row, "Diagnosa");
			$sheet->setCellValue($col_jumlah.$row, "Jumlah");
			$row++; 
			$no=1;
			foreach($data as $dt){
				$sheet->setCellValue($col_no.$row, $no);
				$sheet->setCellValue($col_diagnosa.$row, $dt->namaDiagnosis);
				$sheet->setCellValue($col_jumlah.$row, $dt->total);
				$row++;
				$no++;
			}
			$sheet->getStyle("$col_no$start_row_content:$col_jumlah$row")->applyFromArray($styleBorder);
			$sheet->getStyle("$col_no$start_row_content:$col_no$row")->getAlignment()->setHorizontal('center');
			$writer = new Xlsx($spreadsheet);
			$filename = $title;
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
			header('Cache-Control: max-age=0');
			$writer->save('php://output');	
		}
	}

	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}

}
