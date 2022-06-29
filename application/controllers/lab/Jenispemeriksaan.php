<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Jenispemeriksaan extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		// isAllowed("c::setting");
		$this->data['path_module'] = "lab/";
		$this->lang->load($this->data['path_module'] . 'jenispemeriksaan', $this->session->userdata('site_lang'));
		$this->load->library("datatables");
		$this->load->model($this->data['path_module'] . "Jenispemeriksaan_model", "jenis_pemeriksaan");
		$this->load->model($this->data['path_module'] . "Common_model", "common");
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . "Data Jenis Pemeriksaan";
		$this->data["page_title"] = lang('page_title');
		$this->data['js_control'] = $this->data['path_module'] . "data-jenis-pemeriksaan.js";
		$this->data['datatable'] = true;
		$gets = $this->input->get();
		$this->data['status'] = "";
		if (isset($gets['status'])) $this->data['status'] = htmlentities($gets['status']);
		$this->template->load(get_template(), $this->data['path_module'] . 'data-jenis-pemeriksaan', $this->data);
	}

	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->jenis_pemeriksaan->_load_dt_jenis_pemeriksaan($posted);
		echo json_encode($data);
	}
	public function search__($id = '')
	{
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		header('Content-Type: application/json');
		$search = $this->jenis_pemeriksaan->_search(array("id" => $id));
		if (empty($search)) sendError(lang('msg_no_record'));
		$srch = $search[0];
		if ($srch->category == "covid") {
			$get_list_opsi = $this->jenis_pemeriksaan->_search_list_hasil(array("jenis_id" => $srch->_id));
			echo json_encode(array("data" => $search, "category" => $srch->category, "list_hasil" => $get_list_opsi));
		} else {

			$item_periksa = $this->jenis_pemeriksaan->_search_item_jenis_pemeriksaan(array("jenis_id" => $srch->_id));
			$subitem_periksa = $this->jenis_pemeriksaan->_search_subitem_jenis_pemeriksaan(array("jenis_id" => $srch->_id));

			$group_sub = groupBy($subitem_periksa, "item_id");
			$new_item_periksa = [];
			foreach ($item_periksa as $k => $dt_item) {
				$item = (object) $dt_item;
				$id = $item->sub_id;
				$item->hasil = (isset($item->hasil)) ? $item->hasil : "";
				if (isset($group_sub[$id])) {
					// contains sub
					$item->sub = $group_sub[$id];
				}
				array_push($new_item_periksa, $item);
			}
			// echo "<pre>";
			// print_r($new_item_periksa);
			// echo "</pre>";
			// die();
			$detail = $new_item_periksa;
			echo json_encode(array("data" => $search, "category" => $srch->category, "list_hasil" => [], "detail" => $detail));
		}
	}
	public function search_list_opsi()
	{
		$gets = $this->input->get();
		header('Content-Type: application/json');
		if (!isset($gets['hasil'])) return sendError("Missing hasil");
		if (!isset($gets['id_jenis'])) return sendError("Missing ID Pemeriksaan");
		$get_list_opsi = $this->jenis_pemeriksaan->_search_list_hasil(array("jenis_id" => htmlentities(trim($gets['id_jenis'])), "group_hasil" => htmlentities(trim($gets['hasil']))));
		echo json_encode(array("data" => $get_list_opsi));
	}
	public function select2_()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$not_in = (isset($gets['current'])) ? explode(",", $gets['current']) : [];
		$search = $this->jenis_pemeriksaan->_search_select2($key, $not_in, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function sampling_select2_()
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$gets = modify_post($gets);
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->jenis_pemeriksaan->_search_sampling_select2($key, $gets['clinic_id']);
		echo json_encode($search);
	}
	public function save__()
	{
		//header('Content-Type: application/json');
		//isAllowed("c-privilege^create-update-user");
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		$posted = modify_post($posted);
		foreach ($posted as $key => $value) {
			$$key = $value;
		}
		if ($kategori == "covid") {
			if (!isset($pemeriksaan)) return sendError("Hasil pemeriksaan belum di set");
			$pemeriksaan = (gettype($pemeriksaan) == 'string') ? [$pemeriksaan] : $pemeriksaan;
			if ($pemeriksaan[0] == "") return sendError("Pastikan telah mengisi nama pemeriksaan");
			//$hasil=(gettype($hasil)=='string') ? [$hasil] : $hasil;
			//$nilai_rujukan=(gettype($nilai_rujukan)=='string') ? [$nilai_rujukan] : $nilai_rujukan;
			if (!isset($group_hasil1) || !isset($group_hasil2)) return sendError("Hasil belum di pilih");
			if (!isset($posted['_id']) || $posted['_id'] == "") {
				// add new user
				$set_values = [];
				$save = $this->jenis_pemeriksaan->_save_jenis(array(
					"jenis" => htmlentities(trim($jenis_pemeriksaan)),
					"metode" => htmlentities(trim($metode)),
					"clinic_id" => $clinic_id,
					"category" => "covid",
					"created_by" => $this->data['C_UID']
				), array(), "jenis");
				if ($save == 'exist') return sendError(lang('msg_record_exist'));
				$any_data = [];
				foreach ($pemeriksaan as $k => $v) {
					if (htmlentities(trim($v)) != "") {
						array_push($any_data, $v);
						array_push($set_values, array(
							"jenis_id" => $save,
							"nama_pemeriksaan" => htmlentities(trim($v)),
							"hasil" => htmlentities(trim($hasil1[$k])),
							"group_hasil" => htmlentities(trim($group_hasil1)),
							"nilai_rujukan" => htmlentities(trim($nilai_rujukan1[$k])),
							"metode" => htmlentities(trim($metode)),
							"created_by" => $this->data['C_UID'],
							"is_main" => ($k == 0) ? 1 : 0
						));
						array_push($set_values, array(
							"jenis_id" => $save,
							"nama_pemeriksaan" => htmlentities(trim($v)),
							"hasil" => htmlentities(trim($hasil2[$k])),
							"group_hasil" => htmlentities(trim($group_hasil2)),
							"nilai_rujukan" => htmlentities(trim($nilai_rujukan2[$k])),
							"metode" => htmlentities(trim($metode)),
							"created_by" => $this->data['C_UID'],
							"is_main" => ($k == 0) ? 1 : 0
						));
					}
				}
				if (empty($any_data)) return sendError("Pemeriksaan belum di set");
				$save = $this->jenis_pemeriksaan->_save_opsi_hasil($set_values, array(), "nama_pemeriksaan");
				if ($save == 'exist') {
					sendError(lang('msg_record_exist'));
				} else {
					if ($save == 0) sendError(lang('msg_insert_failed'));
					sendSuccess(lang('msg_insert_success'));
				}
			} else { // on edit
				$set_values = [];
				$save = $this->jenis_pemeriksaan->_save_jenis(array(
					"jenis" => htmlentities(trim($jenis_pemeriksaan)),
					"metode" => htmlentities(trim($metode)),
					"created_by" => $this->data['C_UID']
				), array("id" => htmlentities(trim($_id))), "jenis");
				//if($save=='exist') return sendError("Jenis pemeriksaan ini sudah tersedia.");
				$set_new_values_opsi = [];
				foreach ($pemeriksaan as $k => $v) {
					if (htmlentities(trim($v)) != "") {
						if ($hasil_id1[$k] != "") {
							$s = $this->jenis_pemeriksaan->_update_opsi(array(
								"nama_pemeriksaan" => htmlentities(trim($v)),
								"hasil" => htmlentities(trim($hasil1[$k])),
								"group_hasil" => htmlentities(trim($group_hasil1)),
								"nilai_rujukan" => htmlentities(trim($nilai_rujukan1[$k])),
								"metode" => htmlentities(trim($metode)),
							), array("id" => htmlentities(trim($hasil_id1[$k]))), "nama_pemeriksaan");

							$s2 = $this->jenis_pemeriksaan->_update_opsi(array(
								"nama_pemeriksaan" => htmlentities(trim($v)),
								"hasil" => htmlentities(trim($hasil2[$k])),
								"group_hasil" => htmlentities(trim($group_hasil2)),
								"nilai_rujukan" => htmlentities(trim($nilai_rujukan2[$k])),
								"metode" => htmlentities(trim($metode))
							), array("id" => htmlentities(trim($hasil_id2[$k]))), "nama_pemeriksaan");
						} else {
							// mark as new
							array_push($set_new_values_opsi, array(
								"jenis_id" => htmlentities(trim($_id)),
								"nama_pemeriksaan" => htmlentities(trim($v)),
								"hasil" => htmlentities(trim($hasil1[$k])),
								"group_hasil" => htmlentities(trim($group_hasil1)),
								"nilai_rujukan" => htmlentities(trim($nilai_rujukan1[$k])),
								"metode" => htmlentities(trim($metode)),
								"created_by" => $this->data['C_UID'],
								"is_main" => ($k == 0) ? 1 : 0
							));
							array_push($set_new_values_opsi, array(
								"jenis_id" => htmlentities(trim($_id)),
								"nama_pemeriksaan" => htmlentities(trim($v)),
								"hasil" => htmlentities(trim($hasil2[$k])),
								"group_hasil" => htmlentities(trim($group_hasil2)),
								"nilai_rujukan" => htmlentities(trim($nilai_rujukan2[$k])),
								"metode" => htmlentities(trim($metode)),
								"created_by" => $this->data['C_UID'],
								"is_main" => ($k == 0) ? 1 : 0
							));
						}
					}
				}
				if (!empty($set_new_values_opsi)) {
					$save = $this->jenis_pemeriksaan->_save_opsi_hasil($set_new_values_opsi, array(), "nama_pemeriksaan");
				}
				sendSuccess(lang('msg_update_success'));
			} // end if update
		} else
		if ($kategori == "umum") { // else for kategori
			$posted = $this->input->input_stream();
			$posted = modify_post($posted);
			foreach ($posted as $key => $value) {
				$$key = $value;
			}
			if ($clinic_id == 'allclinic') return sendError("Klinik belum dipilih");
			// save jenis
			// check if not exist
			$where_check_jenis = array("jenis" => $jenis_pemeriksaan, "clinic_id" => $clinic_id);
			$is_edit = FALSE;
			if (isset($posted['_id']) && $posted['_id'] != "") {
				$is_edit = TRUE;
				$where_check_jenis = array("jenis" => $jenis_pemeriksaan, "clinic_id" => $clinic_id, "id!=" => $posted['_id']);
			}
			$check = $this->jenis_pemeriksaan->checkJenis($where_check_jenis);
			if ($check == 'exist') return sendError("Jenis Pemeriksaan ini sudah tersedia");
			if (!$is_edit) {
				$save_jenis = $this->jenis_pemeriksaan->saveJenisPemeriksaanNew(array(
					"jenis" => $jenis_pemeriksaan,
					"clinic_id" => $clinic_id,
					"category" => $kategori,
					"metode" => $metode,
					"created_by" => $this->data['C_UID']
				));
			} else {
				// on edit
				$save_jenis = $this->jenis_pemeriksaan->updateJenisPemeriksaanNew(array(
					"jenis" => $jenis_pemeriksaan,
					// "clinic_id"=>$clinic_id,
					// "category"=>$kategori,
					"metode" => $metode,
					// "created_by"=>$this->data['C_UID']
				), array("id" => $posted['_id']));
				$save_jenis = $posted['_id'];
				// delete sub item
				$this->jenis_pemeriksaan->_deleteItemJenisPemeriksaan(array("jenis_id" => $posted['_id']));
				$this->jenis_pemeriksaan->_deleteSubItemJenisPemeriksaan(array("jenis_id" => $posted['_id']));
			}
			foreach ($posted['item_periksa_umum'] as $k => $arr_item) {
				$item = (object) $arr_item;
				// save item pemeriksaan level 1
				$save_item = $this->jenis_pemeriksaan->saveItemJenisPemeriksaan(array(
					"jenis_id" => $save_jenis,
					"item" => $item->name,
					"nilai_rujukan" => $item->rujukan,
					"satuan" => $item->satuan,
				));
				// save subitem pemeriksaan - level 2
				if (isset($item->sub)) {
					foreach ($item->sub as $k2 => $arr_subitem) {
						$subitem = (object) $arr_subitem;
						$save_subitem = $this->jenis_pemeriksaan->saveSubItemJenisPemeriksaan(array(
							"jenis_id" => $save_jenis,
							"item_id" => $save_item,
							"item" => $subitem->name,
							"nilai_rujukan" => $subitem->rujukan,
							"satuan" => $subitem->satuan,
						));
					}
				}
			}
			if (!$is_edit) {
				sendSuccess("Jenis pemeriksaan berhasil ditambahkan");
			} else {
				sendSuccess("Jenis pemeriksaan berhasil diupdate");
			}
		}
	}
	public function delete__($id = '')
	{
		header('Content-Type: application/json');
		//isAllowed("c-privilege^delete work type");
		$method = $this->input->method(true);
		if ($method != "DELETE") sendError(lang('msg_method_delete_required'), [], 405);
		$result = $this->jenis_pemeriksaan->_delete(htmlentities(trim($id)));
		$r = $this->jenis_pemeriksaan->_delete_opsi(htmlentities(trim($id)));
		if ($result == 1) {
			sendSuccess(lang('msg_delete_success'), []);
		} else {
			sendError(lang('msg_delete_failed'));
		}
	}
	public function delete_opsi_hasil__()
	{
		header('Content-Type: application/json');
		//isAllowed("c-privilege^delete work type");
		$method = $this->input->method(true);
		if ($method != "DELETE") sendError(lang('msg_method_delete_required'), [], 405);
		$posted = $this->input->input_stream();
		if (!isset($posted['ids'])) return sendError("Missing ID");
		$ids = explode(",", $posted['ids']);
		$r = $this->jenis_pemeriksaan->_delete_opsi($ids);
		if ($r > 0) {
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
}

/* End of file Pasien.php */
