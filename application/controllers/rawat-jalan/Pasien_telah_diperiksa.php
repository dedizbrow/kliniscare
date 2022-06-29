<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pasien_telah_diperiksa extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('rawat-jalan/pasien_telah_diperiksa', $this->session->userdata('site_lang'));
		$this->load->model('rawat-jalan/Pasien_telah_diperiksa_model', 'pasien_telah_diperiksa');
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		isAllowed('c-t-antrianperiksa');
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | pasien_telah_diperiksa";
		$this->data["page_title"] = "pasien_telah_diperiksa";
		// $this->data["page_title_small"] = "text small dibawah page title";
		$this->data['js_control'] = "rawat-jalan/pasien_telah_diperiksa/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'rawat-jalan/pasien_telah_diperiksa/index', $this->data);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->pasien_telah_diperiksa->_load_dt($posted);
		echo json_encode($data);
	}
	public function load_dt_obat()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->pasien_telah_diperiksa->_load_dt_obat($posted);
		echo json_encode($data);
	}
	public function search_($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->pasien_telah_diperiksa->_search(array("id_pendaftaran" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data" => $search[0]));
	}
	public function search_pemeriksaan($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->pasien_telah_diperiksa->_search_pemeriksaan($id);
		if (empty($search)) sendError(lang('msg_no_record'));
		$search_diagnosa_pemeriksaan = $this->pasien_telah_diperiksa->_search_diagnosa_pemeriksaan_select2($search[0]->id_pemeriksaan);
		$search_tindakan_pemeriksaan = $this->pasien_telah_diperiksa->_search_tindakan_pemeriksaan_select2($search[0]->id_pemeriksaan);
		// print_r($search_tindakan_pemeriksaan);
		// die();
		echo json_encode(array("data" => $search[0], "diagnosa_pemeriksaan" => $search_diagnosa_pemeriksaan, "tindakan_pemeriksaan" => $search_tindakan_pemeriksaan));
	}
	public function save__()
	{
		isAllowed("c-t-antrianperiksa^update");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = (gettype($value) == 'string') ? htmlentities(trim($value)) : $value;
		}

		$id_pendaftaran = htmlentities(trim($posted['_id']));
		$id_antrian = htmlentities(trim($posted['_id_antrian']));
		$id_pemeriksaan = htmlentities(trim($posted['id_pemeriksaan']));
		$data_pemeriksaan = array(
			"id_pendaftaran"        => $id_pendaftaran,
			"kesadaran"             => $kesadaran,
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
		$this->pasien_telah_diperiksa->_update($id_pemeriksaan, $data_pemeriksaan);
		$this->pasien_telah_diperiksa->_del_edited_diagnosa($id_pemeriksaan);

		if (isset($fk_diagnosa) && !empty($fk_diagnosa)) {
			if ($fk_dokter == "") return sendError("Dokter pemberi diagnosa wajib diisi");
			$data_diagnosa = array();
			foreach ($fk_diagnosa as $key => $value) {
				$data_diagnosa[$key]['fk_pemeriksaan'] = $id_pemeriksaan;
				$data_diagnosa[$key]['fk_dokter'] = $fk_dokter;
				$data_diagnosa[$key]['fk_diagnosa'] = $value;
			}
			$this->pasien_telah_diperiksa->_save_diagnosa($data_diagnosa);
		}

		$this->pasien_telah_diperiksa->_del_edited_tindakan($id_pemeriksaan);
		if (isset($fk_tindakan) && !empty($fk_tindakan)) {
			if ($fk_dokter_tindakan == "") return sendError("Dokter pemberi tindakan wajib diisi");
			$data_tindakan = array();
			foreach ($fk_tindakan as $key => $value) {
				$data_tindakan[$key]['fk_pemeriksaan'] = $id_pemeriksaan;
				$data_tindakan[$key]['fk_dokter'] = $fk_dokter_tindakan;
				$data_tindakan[$key]['fk_tindakan'] = $value;
			}
			$this->pasien_telah_diperiksa->_save_tindakan($data_tindakan);
		}

		if ($id_pemeriksaan > 0) sendSuccess("<a href='pasien_telah_diperiksa')'>Pasien telah diperiksa</a>");
		sendError("Penambahan gagal");
	}
	public function select2_aturan_pakai()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->pasien_telah_diperiksa->_search_select_aturan_pakai($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function save_aturan_pakai()
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
		if ($nama_aturan_pakai == "") return sendError("Nama wajib diisi");
		$savesatuan = $this->pasien_telah_diperiksa->_save_aturan_pakai(array(
			"nama_aturan_pakai" => $nama_aturan_pakai,
			"clinic_id" 		=> $clinic_id,
		), array(), "nama_aturan_pakai", "clinic_id");
		if ($savesatuan == 'exist') {
			sendError('Sudah terdaftar');
		} else {

			echo json_encode(array("message" => "Penambahan berhasil"));
		}
	}
	public function select2_cara_pakai()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->pasien_telah_diperiksa->_search_select_cara_pakai($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function save_cara_pakai()
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
		if ($nama_cara_pakai == "") return sendError("Nama wajib diisi");
		$savesatuan = $this->pasien_telah_diperiksa->_save_cara_pakai(array(
			"nama_cara_pakai" => $nama_cara_pakai,
			"clinic_id" 		=> $clinic_id,
		), array(), "nama_cara_pakai", "clinic_id");
		if ($savesatuan == 'exist') {
			sendError('Sudah terdaftar');
		} else {

			echo json_encode(array("message" => "Penambahan berhasil"));
		}
	}
	function insert_temp()
	{
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}

		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");
		// before insert, do check stock
		$stock = $this->pasien_telah_diperiksa->check_stock_obat($id_obat);
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
			"clinic_id" 	=> $clinic_id,
			"status" 		=> 0
		);
		$save = $this->pasien_telah_diperiksa->insert_temp($data);
		sendSuccess("Ditambahkan " . $save);
	}

	function hapus_temp()
	{
		$posted = $this->input->post();
		$id = htmlentities($posted['id']);
		$hapus = $this->pasien_telah_diperiksa->hapus_temp($id);
		sendSuccess("Berhasil (" . $hapus . ")");
	}
	function load_temp()
	{
		$posted = $this->input->post();
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : htmlentities(trim($posted['clinic_id']));
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

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
		$data =  $this->pasien_telah_diperiksa->tampilkan_temp($clinic_id)->result();
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
		$this->pasien_telah_diperiksa->ubah_status($id_pendaftaran);
		echo json_encode(array("message" => "Berhasil"));
	}
	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
	public function pindah_ranap($id = '')
	{
		header('Content-Type: application/json');
		$this->input->method(true);
		$id_pendaftaran = htmlentities(trim($id));
		if ($id_pendaftaran == '' || $id_pendaftaran == null) sendError("Missing ID");
		$result = $this->pasien_telah_diperiksa->_pindah_ranap($id_pendaftaran);
		if ($result == 1) {
			sendSuccess("Berhasil dipindah ke Rawat Inap", []);
		} else {
			sendError("Gagal");
		}
	}
}
