<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pemeriksaan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('rawat-inap/pemeriksaan', $this->session->userdata('site_lang'));
		$this->load->model('rawat-inap/pemeriksaan_model', 'pemeriksaan');
		$this->load->model("/admin/Other_setting_model", "other_set");
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		isAllowed('c-pemeriksaan-igd');
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Pemeriksaan";
		$this->data["page_title"] = "Pemeriksaan";
		$this->data['js_control'] = "rawat-inap/pemeriksaan/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'rawat-inap/pemeriksaan/index', $this->data);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->pemeriksaan->_load_dt($posted);
		echo json_encode($data);
	}
	public function load_dt_ruangan()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->pemeriksaan->_load_dt_ruangan($posted);
		echo json_encode($data);
	}
	public function search_pendaftaran($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->pemeriksaan->_search_pendaftaran(array("id_pendaftaran" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
	public function save_pemeriksaan()
	{
		// header('Content-Type: application/json');
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = (gettype($value) == 'string') ? htmlentities(trim($value)) : $value;
		}
		$id_pendaftaran = htmlentities(trim($posted['id_pendaftaran']));
		$data_pemeriksaan = array(
			"id_pendaftaran"        => $id_pendaftaran,
			"anamnesa"              => $anamnesa,
			"pemeriksaan_umum"      => $pemeriksaan_umum,
			"alergi"                => $alergi,
			"sistole"               => $sistole,
			"diastole"              => $diastole,
			"tensi"                 => $tensi,
			"derajat_nadi"          => $derajat_nadi,
			"nafas"                 => $nafas,
			"suhu_tubuh"            => $suhu_tubuh,
			"saturasi"				=> $saturasi,
			"bb"                    => $bb,
			"tb"                    => $tb,
			"catatan_dokter"        => $catatan_dokter,
			"nyeri"                 => $nyeri,
			"creator_id"            => $this->data['C_UID']
		);
		$dtpoli = htmlentities(trim($posted['poli']));
		if ($dtpoli == "") return sendError("Jenis Layanan Wajib diisi");
		$this->pemeriksaan->update_poli_pendaftaran($dtpoli, $id_pendaftaran);
		$this->pemeriksaan->update_status_telah_diperiksa($id_pendaftaran);

		$id_pemeriksaan = $this->pemeriksaan->_save($data_pemeriksaan);
		if (isset($fk_diagnosa) && !empty($fk_diagnosa)) {
			if ($fk_dokter == "") return sendError("Dokter pemberi diagnosa wajib diisi");
			$data_diagnosa = array();
			foreach ($fk_diagnosa as $key => $value) {
				$data_diagnosa[$key]['fk_pemeriksaan'] = $id_pemeriksaan;
				$data_diagnosa[$key]['fk_dokter'] = $fk_dokter;
				$data_diagnosa[$key]['fk_diagnosa'] = $value;
			}
			$this->pemeriksaan->_save_diagnosa($data_diagnosa);
		}


		if (isset($fk_tindakan) && !empty($fk_tindakan)) {
			if ($fk_dokter_tindakan == "") return sendError("Dokter pemberi tindakan wajib diisi");
			$data_tindakan = array();
			foreach ($fk_tindakan as $key => $value) {
				$data_tindakan[$key]['fk_pemeriksaan'] = $id_pemeriksaan;
				$data_tindakan[$key]['fk_dokter'] = $fk_dokter_tindakan;
				$data_tindakan[$key]['fk_tindakan'] = $value;
			}
			$this->pemeriksaan->_save_tindakan($data_tindakan);
		}
		if ($id_pemeriksaan > 0) sendSuccess("Pasien telah diperiksa");
		sendError("Penambahan gagal");
	}
	public function search_($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->pemeriksaan->_search(array("id_pendaftaran" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
	public function search_checkout($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->pemeriksaan->_search_checkout(array("id_pendaftaran" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
	public function tambah_ruangan($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->pemeriksaan->tambah_ruangan(array("idRuangan" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
	public function save_checkin()
	{
		header('Content-Type: application/json');
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		$id_pendaftaran = htmlentities(trim($posted['id_pendaftaran']));
		$id_ruangan = htmlentities(trim($posted['_idRuangan']));
		if ($id_ruangan == "") return sendError("Pilih ruangan");

		$data_checkin_ruangan = array(
			"checkin_at"		 => date('Y-m-d'),
			"fk_pendaftaran"     => $id_pendaftaran,
			"fk_ruangan"         => $id_ruangan,
			"creator_id"            => $this->data['C_UID']
		);
		$id_checkin = $this->pemeriksaan->_save_checkin($data_checkin_ruangan);
		$this->pemeriksaan->_save_status_checkin($id_pendaftaran, $id_checkin);
		$this->pemeriksaan->_update_status_kamar($id_ruangan);
		echo json_encode(array("message" => "Penambahan berhasil"));
	}
	public function save_checkout()
	{
		header('Content-Type: application/json');
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}

		if (!isset($status_checkout)) return sendError("Pastikan memilih kondisi keluar");
		$id_pendaftaran = htmlentities(trim($posted['id_pendaftaran']));
		$id_ruangan = htmlentities(trim($posted['idRuangan']));
		$status_checkout = htmlentities(trim($posted['status_checkout']));
		// $data_checkout_ruangan = array(
		// 	"status_checkout"		=> $status_checkout,
		// );
		$this->pemeriksaan->_save_checkout($status_checkout, $id_pendaftaran);
		$this->pemeriksaan->_save_status_checkout($id_pendaftaran);
		$this->pemeriksaan->_update_status_kamar_checkout($id_ruangan);
		echo json_encode(array("message" => "Checkout berhasil"));
	}
	function insert_temp()
	{
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		// before insert, do check stock
		$stock = $this->pemeriksaan->check_stock_obat($id_obat);
		if (empty($stock)) sendError("Tidak ada stok obat");
		if ($stock[0]->stok < (int) $qty * $isi) sendError("Stok obat \"" . $obat . "\" (" . $stock[0]->stok . ") tidak mencukupi. ");
		$data = array(
			"fk_obat" 	=> $id_obat,
			"qty" 		=> $qty,
			"total" 	=> $total,
			"isi" 		=> $isi,
			"harga" 	=> $harga,
			"satuan"	=> $satuan,
			'cara_pakai' 	=> $cara_pakai,
			'aturan_pakai' 	=> $aturan_pakai,
			"status" 		=> 0,
			"clinic_id"=>$clinic_id
		);
		$save = $this->pemeriksaan->insert_temp($data);
		sendSuccess("Ditambahkan " . $save);
	}
	function hapus_temp()
	{
		$posted = $this->input->post();
		$id = htmlentities($posted['id']);
		$hapus = $this->pemeriksaan->hapus_temp($id);
		sendSuccess("Berhasil (" . $hapus . ")");
	}
	function load_temp()
	{
		$gets = modify_post($this->input->get());
		$clinic_id = (!isset($gets['clinic_id']) || $gets['clinic_id'] == 'default') ? getClinic()->id : htmlentities(trim($gets['clinic_id']));
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");
		$id_daftar=$gets['id'];$status_flag=$gets['status'];
		echo "<table class='table table-bordered rm' cellpadding='1'>
        <thead>    
            <tr style='background-color:lightgrey;'>
				<th>Kode</th>
                <th>Nama</th>
                <th>QTY</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Total</th>
                <th>Aturan Pakai</th>
                <th>Cara Pakai</th>
                <th>Operasi</th>
            </tr>
        </thead>";
		// $no = 1;
		$subtotal_plg = 0;
		$data =  $this->pemeriksaan->tampilkan_temp($clinic_id,$id_daftar,$status_flag)->result();
		foreach ($data as $d) {
			echo "<tbody>
            <tr id='dataobat$d->resep_detail_id'>
				<td data-code='" . $d->kode . "'>$d->kode</td>
                <td>$d->nama</td>
                <td>$d->qty</td>
                <td>$d->namaSatuanobat</td>
                <td>$d->harga</td>
                <td id='totalitas'>$d->total</td>
                <td>$d->nama_aturan_pakai</td>
                <td>$d->nama_cara_pakai</td>
                <td><p onClick='hapus($d->resep_detail_id)'><i class='fa fa-trash text-danger'></i></p></td>
            </tr>
            </tbody>";

			$this->data['subtotal_plg'] = $subtotal_plg += $d->total;
			json_encode($this->data['subtotal_plg']);
			// $no++;
		}
		echo "</table>";
	}
	public function save_resep()
	{
		header('Content-Type: application/json');
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		$id_pendaftaran = htmlentities(trim($posted['id_pendaftaran']));
		$this->pemeriksaan->ubah_status($id_pendaftaran);
		echo json_encode(array("message" => "Berhasil"));
	}
	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
	public function print()
	{
		$this->data["web_title"] = lang('app_name_short') . "Print Surat Keterangan Kematian";
		$this->data["page_title"] = lang('page_title');
		$clinic_id = getClinic()->id;
		$gets = $this->input->get();
		if ($clinic_id == 'allclinic' && isset($gets['clinic_id'])) $clinic_id = $gets['clinic_id'];
		$dt_profile = $this->other_set->get_com_profile(array("clinic_id" => $clinic_id));

		$page_title = "Surat Kematian ";
		$paper_size = (isset($gets['size'])) ? $gets['size'] : 'A4';
		$mode = ($paper_size == 'A4') ? 'P' : 'P';
		if (!isset($gets['pendaftaran'])) {
			die("No Document can be print");
		}
		$doc_setting = $this->other_set->get_setting_doc_requirements(array("clinic_id" => $clinic_id));

		$data_profile = $dt_profile[0];
		$this->data['doc_setting_profile'] = $data_profile;

		$this->data['doc_setting'] = array_group_by("code", $doc_setting, true);


		$vid = htmlentities($gets['pendaftaran']);
		$periksa = $this->pemeriksaan->_search_print((int) htmlentities($vid));
		if (empty($periksa)) die("Data Not exist");
		$dt_periksa = $periksa[0];

		// echo '<pre>';
		// print_r($dt_periksa);
		// die();
		$this->data['data_periksa'] = $dt_periksa;
		// $this->data['text_city_of_klinik'] = (isset($this->data['doc_setting']->text_city_of_klinik)) ? $this->data['doc_setting']->text_city_of_klinik->content : conf('lab_kota_praktek');
		// $detail=$this->pemeriksaan->_search_detail($vid);
		// $this->data['detail_periksa']=$detail;
		$mpdf = new \Mpdf\Mpdf(['format' => $paper_size, 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

		$this->data['page_title'] = $page_title;
		$mpdf->SetAuthor(conf('lab_nama_klinik_id'));
		$mpdf->SetCreator(conf('lab_nama_klinik_id'));
		$mpdf->SetTitle($page_title);
		$mpdf->SetSubject($page_title);
		$mpdf->shrink_tables_to_fit = 1;
		//$mpdf->SetProtection(['print'],'','--YM^21..');
		$margin_left = 0;
		$margin_right = 0;
		$margin_top = 30;
		$margin_bottom = 0;
		$margin_header_top = 0;
		$margin_footer_bottom = 0;
		$mpdf->addPage($mode, '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);
		$mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url($this->data['doc_setting']->img_doc_header->path) . '"></div>', '', true);

		// $mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url('/assets/img/' . conf('path_module_lab') . 'img-header-kwitansi.png') . '"></div>', '', true);
		//<img src="'.base_url('/assets/img/ym-doc-footer.jpg').'">
		// $mpdf->SetHTMLFooter('<div class="page-footer-pdf"><div class="page-footer-text">'.$setting_doc->content.'</div></div>'); 
		// $mpdf->SetHTMLFooter('<div class="page-header-pdfs"><img src="'.base_url('/assets/img/'.conf('path_module_lab').'img-doc-footer.png').'"></div>','',false);
		$mpdf->defaultfooterline = 30;

		$html = $this->load->view('rawat-inap/pemeriksaan/print', $this->data, true);
		$mpdf->WriteHTML($html);
		$mpdf->Output($page_title . ".pdf", 'I');
	}
}
