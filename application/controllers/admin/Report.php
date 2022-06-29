<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('setting', $this->session->userdata('site_lang'));
		$this->load->helper('Authentication');
		$this->load->library("datatables");
		$this->load->model("Report_model","report");
		$this->load->model("Pasien_model","pasien");
		$this->load->model("Pemeriksaan_model","pemeriksaan");
		$this->load->model("Jenispemeriksaan_model","jenis");
		$this->load->model("Provider_model","provider");
		$this->asset_path='../../assets';
		$this->data=isAuthorized();
      isAllowed("c::report");
	}
	public function index()
	{
		redirect(base_url('report/by-periode'));
	}
	public function by_periode($act='',$file_type='')
	{
		$this->data["web_title"]=lang('app_name_short'). "Summary Data Pemeriksaan";
		$this->data["page_title"]=lang('page_title');
		$this->data['js_control']="report/summary-by-periode.js";
		$this->data['datatable']=true;
		$gets=$this->input->get();
		$this->data['status']="";
		if(isset($gets['status'])) $this->data['status']=htmlentities($gets['status']);
		$list_jenis=$this->jenis->_list_jenis_pemeriksaan();
		$this->data['list_jenis']=$list_jenis;
		
		$ym=date("Y-m");
		if(isset($gets['periode']) && preg_match("/\d{4}\-\d{2}/", $gets['periode'])) $ym=$gets['periode'];
		$split_ym=explode("-",$ym);
		$this->data['selected_year']=$split_ym[0]; $this->data['selected_month']=$split_ym[1];
		$arr_month=["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
		$this->data['periode']=$arr_month[((int) $split_ym[1])-1]." ".$split_ym[0];
		$count_summary=$this->report->count_pemeriksaan_per_provider('',$ym);
		$group_by_provider = array();
		$sum_total=array();
		$grand_total=0;
		$arr_jenis=[];
		foreach($list_jenis as $item){
			$sum_total[$item->jenis]=0;
			array_push($arr_jenis,$item->jenis);
		}
		foreach ( $count_summary as $value ) {
			$tot=(int) $value->jumlah_selesai;
			$sum_total[$value->jenis]+=$tot;
			$grand_total+=$tot;
			$group_by_provider[$value->provider][] = $value;
		}
		$this->data['list_by_provider']=$group_by_provider;
		$this->data['sum_total']=$sum_total;
		$this->data['grand_total']=$grand_total;
		if($act!='export'){
			$this->template->load(get_template(),'report/summary-periode',$this->data);
		}else{
			if($file_type=='' && isset($gets['excel']) && $gets['excel']=='true'){ 
				$file_type='excel'; 
			}else
			if($file_type=='' && isset($gets['pdf']) && $gets['pdf']=='true'){ 
				$file_type='pdf';
			}
			if($file_type!='pdf' && $file_type!='excel') return die("Export ke ".$file_type." tidak didukung");
			$page_title="Laporan Pemeriksaan ".$this->data['periode'];
			if($file_type=='pdf'){
				$setting_doc=$this->pemeriksaan->_get_setting('doc-footer-text');
				$mpdf = new \Mpdf\Mpdf(['format' => 'A4','setAutoBottomMargin' => 'stretch']);
				
				$this->data['page_title']=$page_title;
				$mpdf->SetAuthor(conf('company_name'));
				$mpdf->SetCreator(conf('company_name'));
				$mpdf->SetTitle($page_title);
				$mpdf->SetSubject($page_title);
				$mpdf->shrink_tables_to_fit = 1;
				//$mpdf->SetProtection(['print'],'','--YM^21..');
				$margin_left=0; $margin_right=0;$margin_top=45; $margin_bottom=0; $margin_header_top=0;$margin_footer_bottom=0;
				$mpdf->addPage('P','','','','',$margin_left,$margin_right,$margin_top,$margin_header_top,$margin_footer_bottom);
				
				$mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="'.base_url('/assets/img/ym-doc-header.png').'"></div>','',true);
				//<img src="'.base_url('/assets/img/ym-doc-footer.jpg').'">
				$mpdf->SetHTMLFooter('<div class="page-footer-pdf"><div class="page-footer-text">'.$setting_doc->content.'</div></div>'); 
				$mpdf->defaultfooterline=30;
				
				$html = $this->load->view('report/print-summary',$this->data,true);
				$mpdf->WriteHTML($html);
				$mpdf->Output($page_title.".pdf", 'I'); // opens in browser
			}else
			if($file_type=='excel'){
				$spreadsheet = new Spreadsheet();
				$spreadsheet->getProperties()->setCreator(conf('company_name'))
        ->setLastModifiedBy(conf('company_name'))
        ->setTitle($page_title)
        ->setSubject($page_title)
        ->setDescription($page_title);
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
				$sheet->setTitle($this->data['periode']);
				$col_no="A";$col_provider="B"; $col_jenis="D"; $col_jumlah="E";
				$sheet->mergeCells($col_no."1:".$col_jumlah."1");
				$sheet->setCellValue("A1", strtoupper($page_title));
				$sheet->getStyle("A1")->getAlignment()->setHorizontal('center');
				$sheet->getStyle("A1")->getFont()->setBold( true );
				
				$row=3;
				$sheet->mergeCells("A$row:B$row");
				$sheet->setCellValue("A".$row, 'Periode');
				$sheet->setCellValue("C".$row, $this->data['periode']);
				$row++;
				$sheet->mergeCells("A$row:B$row");
				$sheet->setCellValue("A".$row, 'Jumlah Total');
				$sheet->setCellValue("C".$row, $grand_total);
				$row++;
				foreach($list_jenis as $jns){
					$sheet->mergeCells("A$row:B$row");
					$sheet->setCellValue("A".$row, $jns->jenis);
					$sheet->setCellValue("C".$row, $sum_total[$jns->jenis]);
					$row++;
				}
				
				$row++;
				$start_row_content=$row;
				
				$sheet->getColumnDimension($col_jenis)->setWidth(20);
				// write header
				$sheet->setCellValue($col_no.$row, 'No');
				$sheet->mergeCells("B$row:C$row");
				$sheet->setCellValue($col_provider.$row, 'Nama Provider');
				$sheet->setCellValue($col_jenis.$row, 'Jenis Pemeriksaan');
				$sheet->setCellValue($col_jumlah.$row, 'Jumlah');
				$sheet->getStyle("$col_no$row:$col_jumlah$row")->getFont()->setBold( true );
				$row++;
				// write content
				$n=1;
				$totals=0;
				if(empty($this->data['list_by_provider'])){
					$sheet->mergeCells("$col_provider$row:$col_jumlah$row");
					$sheet->setCellValue($col_provider.$row, 'Tidak ada data');
				} 
				foreach($this->data['list_by_provider'] as $provider=>$item){
					$sheet->setCellValue($col_no.$row, $n);
					$sheet->mergeCells("B$row:C$row");
					$sheet->setCellValue($col_provider.$row, $provider);
					$sheet->setCellValue($col_jenis.$row, $arr_jenis[0]);
					$found=false;
					foreach($item as $j=>$v){
						$totals+=(int) $v->jumlah_selesai;
						if($v->jenis==$arr_jenis[0]){ $found=true; $sheet->setCellValue($col_jumlah.$row,$v->jumlah_selesai); }
					}
					if(!$found) $sheet->setCellValue($col_jumlah.$row,"0");
					$row++;
					$alias_jenis=$arr_jenis;
					array_shift($alias_jenis);
					foreach($alias_jenis as $jns){ 
						$sheet->setCellValue($col_jenis.$row,$jns);
						$found=false;
						foreach($item as $j=>$v){
							if($v->jenis==$jns){ $found=true; $sheet->setCellValue($col_jumlah.$row,$v->jumlah_selesai); }
						}
						$sheet->mergeCells("B$row:C$row");
						if(!$found) $sheet->setCellValue($col_jumlah.$row,"0");
						$row++;
					}
					$sheet->mergeCells("B$row:C$row");
					$row++;
					$n++;
				}
				$row--;
				$sheet->getStyle("$col_no$start_row_content:$col_jumlah$row")->applyFromArray($styleBorder);
				$sheet->getStyle("$col_no$start_row_content:$col_no$row")->getAlignment()->setHorizontal('center');
				$sheet->getStyle($col_jumlah)->getAlignment()->setHorizontal('right');
				$writer = new Xlsx($spreadsheet);
				$filename = $page_title;
				
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
				header('Cache-Control: max-age=0');
		
				$writer->save('php://output');	
			}
		}
	}
	public function by_pemeriksaan($act='',$file_type='')
	{
		$this->data["web_title"]=lang('app_name_short'). "Summary Data Pemeriksaan";
		$this->data["page_title"]=lang('page_title');
		$this->data['js_control']="report/summary-by-pemeriksaan.js";
		$gets=$this->input->get();
		$list_jenis=$this->jenis->_list_jenis_pemeriksaan();
		$this->data['list_jenis']=$list_jenis;
		$pv_id=($this->data['C_PV_GROUP']!='pusat') ? $this->data['C_PV_ID'] : '';
		$list_provider=$this->provider->_list_provider($pv_id);
		$this->data['list_provider']=$list_provider;
		$start_date=(isset($gets['start_date'])) ? htmlentities(trim($gets['start_date'])) : date("01-m-Y");
		$end_date=(isset($gets['end_date'])) ? htmlentities(trim($gets['end_date'])) : date("d-m-Y");
		// check format
		if(!preg_match("/\d{4}\-\d{2}-\d{2}/", $start_date) && !preg_match("/\d{2}\-\d{2}-\d{4}/", $start_date)) return sendError("Format tanggal salah");
		if(!preg_match("/\d{4}\-\d{2}-\d{2}/", $end_date) && !preg_match("/\d{2}\-\d{2}-\d{4}/", $end_date)) return sendError("Format tanggal salah");
		$this->data['start_date']=$start_date;
		$this->data['end_date']=$end_date;
		$start_date=date("Y-m-d",strtotime($start_date));
		$end_date=date("Y-m-d",strtotime($end_date));
		$this->data['provider']=(isset($gets['provider'])) ? htmlentities(trim($gets['provider'])) : $this->data['list_provider'][0]->id;
		$this->data['jenis']=(isset($gets['jenis'])) ? htmlentities(trim($gets['jenis'])) : $this->data['list_jenis'][0]->id;
		$this->data['list_pemeriksaan']=$this->report->load_report_pemeriksaan($this->data['provider'],$this->data['jenis'],$start_date,$end_date);
		
		if($act!='export'){
			$this->template->load(get_template(),'report/summary-pemeriksaan',$this->data);
		}else{
			if($file_type=='' && isset($gets['excel']) && $gets['excel']=='true'){ 
				$file_type='excel'; 
			}else
			if($file_type=='' && isset($gets['pdf']) && $gets['pdf']=='true'){ 
				$file_type='pdf';
			}
			if($file_type!='pdf' && $file_type!='excel') return die("Export ke ".$file_type." tidak didukung");
			$page_title="Laporan Pemeriksaan";

			if($file_type=='pdf'){
				$setting_doc=$this->pemeriksaan->_get_setting('doc-footer-text');
				$mpdf = new \Mpdf\Mpdf(['format' => 'A4','setAutoBottomMargin' => 'stretch']);
				$this->data['page_title']=$page_title;
				$mpdf->SetAuthor(conf('company_name'));
				$mpdf->SetCreator(conf('company_name'));
				$mpdf->SetTitle($page_title);
				$mpdf->SetSubject($page_title);
				$mpdf->shrink_tables_to_fit = 1;
				//$mpdf->SetProtection(['print'],'','--YM^21..');
				$margin_left=0; $margin_right=0;$margin_top=45; $margin_bottom=0; $margin_header_top=0;$margin_footer_bottom=0;
				$mpdf->addPage('P','','','','',$margin_left,$margin_right,$margin_top,$margin_header_top,$margin_footer_bottom);
				$mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="'.base_url('/assets/img/ym-doc-header.png').'"></div>','',true);
				//<img src="'.base_url('/assets/img/ym-doc-footer.jpg').'">
				$mpdf->SetHTMLFooter('<div class="page-footer-pdf"><div class="page-footer-text">'.$setting_doc->content.'</div></div>'); 
				$mpdf->defaultfooterline=30;
				$html = $this->load->view('report/print-summary-pemeriksaan',$this->data,true);
				$mpdf->WriteHTML($html);
				$mpdf->Output($page_title.".pdf", 'I');
			}else
			if($file_type=='excel'){
				$spreadsheet = new Spreadsheet();
				$spreadsheet->getProperties()->setCreator(conf('company_name'))
        ->setLastModifiedBy(conf('company_name'))
        ->setTitle($page_title)
        ->setSubject($page_title)
        ->setDescription($page_title);
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
				$sheet->setTitle("Sheet1");
				
				$sheet->mergeCells("A1:E1");
				$sheet->setCellValue("A1", strtoupper($page_title));
				$sheet->getStyle("A1")->getAlignment()->setHorizontal('center');
				$sheet->getStyle("A1")->getFont()->setBold( true );
				$sheet->getColumnDimension("B")->setWidth(14);
				$sheet->getColumnDimension("C")->setWidth(14);
				$sheet->getColumnDimension("D")->setWidth(6);
				$sheet->getColumnDimension("E")->setWidth(14);
				$row=3;
				$sheet->mergeCells("A$row:B$row");
				$sheet->setCellValue("A".$row, 'Periode');
				$sheet->setCellValue("C".$row, $this->data['start_date']);
				$sheet->setCellValue("D".$row, 's/d');
				$sheet->getStyle("D")->getAlignment()->setHorizontal('center');
				$sheet->setCellValue("E".$row, $this->data['end_date']);
				$row++;
				$sheet->mergeCells("A$row:B$row");
				$sheet->setCellValue("A".$row, 'Provider');
				$sheet->mergeCells("C$row:E$row");
				foreach($this->data['list_provider'] as $item){
					if($this->data['provider']==$item->id) $sheet->setCellValue("C".$row, $item->nama);
				}
				$row++;
				$sheet->mergeCells("A$row:B$row");
				$sheet->setCellValue("A".$row, 'Jenis Pemeriksaan');
				$sheet->mergeCells("C$row:E$row");
				foreach($this->data['list_jenis'] as $jns){
					if($this->data['jenis']==$jns->id) $sheet->setCellValue("C".$row, $jns->jenis);
				}
				$row++;
				$row++;
				$start_row_content=$row;
				
				// write header
				$col_no="A"; $col_tgl="B"; $col_nama="C"; $end_merge="E";
				$sheet->setCellValue($col_no.$row, 'No');
				$sheet->setCellValue($col_tgl.$row, 'Tanggal');
				$sheet->mergeCells("$col_nama$row:$end_merge$row");
				$sheet->setCellValue($col_nama.$row, 'Nama Pasien');
				$sheet->getStyle("$col_no$row:$col_nama$row")->getFont()->setBold( true );
				$row++;
				// write content
				$n=1;
				$totals=0;
				if(empty($this->data['list_pemeriksaan'])){
					$sheet->mergeCells("$col_tgl$row:$end_merge$row");
					$sheet->setCellValue($col_tgl.$row, 'Tidak ada data');
				} 
				foreach($this->data['list_pemeriksaan'] as $item){
					$sheet->setCellValue($col_no.$row, $n);
					$sheet->setCellValue($col_tgl.$row, date("d-m-Y",strtotime($item->tgl_periksa)));
					$sheet->mergeCells("$col_nama$row:$end_merge$row");
					$sheet->setCellValue($col_nama.$row, $item->nama_pasien);
					$row++;
					$n++;
				}
				$sheet->mergeCells("$col_no$row:$end_merge$row");

				$sheet->getStyle("$col_no$start_row_content:$end_merge$row")->applyFromArray($styleBorder);
				$sheet->getStyle("$col_no$start_row_content:$col_no$row")->getAlignment()->setHorizontal('center');
				$writer = new Xlsx($spreadsheet);
				$filename = $page_title;
				
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
				header('Cache-Control: max-age=0');
		
				$writer->save('php://output');	
			}
		}
	}

}

/* End of file Report.php */
