<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pemeriksaan_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablePasien = "tbl_pasien";
		$this->tablePemeriksaan = "lab_data_pemeriksaan";
		$this->tableDetailPemeriksaan = "lab_detail_pemeriksaan";
		$this->tableNotes = "lab_data_notes";
		$this->tableJenisSampling = "lab_jenis_sampling";
		$this->tableOpsiHasil = "lab_opsi_hasil";
		$this->tableSetting = "lab_settings";

		$this->tableItemJenisPemeriksaan = "lab_item_jenis_pemeriksaan";
		$this->tableSubItemJenisPemeriksaan = "lab_subitem_jenis_pemeriksaan";
		$this->tableItemHasilPeriksa = "lab_item_hasil_periksa";
		$this->tableSubItemHasilPeriksa = "lab_subitem_hasil_periksa";
		$this->tableHasilPeriksa = "lab_jenis_hasil_periksa";

		$this->load->helper('ctc');
	}
	function _load_dt($posted, $provider_id = '')
	{
		$orders_cols = ["periksa.tgl_periksa", "periksa.tgl_sampling", "pasien.id_pasien", "pasien.no_identitas", "pasien.nama_lengkap", "provider.nama", "pasien.jenis_kelamin", "pasien.tgl_lahir", "jenis.jenis", "periksa.status", "periksa.biaya", "periksa.bayar", "update_hasil_at", "periksa.id"];
		$output = build_filter_table($posted, $orders_cols, ["pasien.nomor_rm"],"pasien.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere.=($sWhere=="") ? " WHERE ": " AND ";
			$sWhere.= " (nama_lengkap LIKE '%" . $output->search . "%' OR no_identitas LIKE '%" . $output->search . "%' OR periksa.id_pasien LIKE '%" . $output->search . "%')";
		}
		$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
		$sWhere .= " pasien.deleted=0 ";
		if ($provider_id != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " provider_id='$provider_id'";
		}
		if (isset($posted['filter_provider']) && $posted['filter_provider'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " provider_id='" . htmlentities($posted['filter_provider']) . "'";
		}
		if (isset($posted['filter_pemeriksaan']) && $posted['filter_pemeriksaan'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " jenis_pemeriksaan='" . htmlentities($posted['filter_pemeriksaan']) . "'";
		}
		if (isset($posted['filter_status']) && $posted['filter_status'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$stts = htmlentities(trim($posted['filter_status']));
			if ($stts == "BELUM") $stts = "";
			$sWhere .= " status='" . $stts . "'";
		}
		if (isset($posted['start_date']) && $posted['start_date'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$tgl_col = (isset($posted['filter_tgl_option']) && htmlentities(trim($posted['filter_tgl_option'])) == "tgl_sampling") ? 'tgl_sampling' : 'tgl_periksa';
			if (isset($posted['end_date']) && $posted['end_date'] != "") {
				$sWhere .= "$tgl_col IS NOT NULL AND $tgl_col BETWEEN '" . htmlentities($posted['start_date']) . "' AND '" . htmlentities($posted['end_date']) . "'";
			} else {
				$sWhere .= "$tgl_col IS NOT NULL AND $tgl_col BETWEEN '" . htmlentities($posted['start_date']) . "' AND CURDATE()";
			}
		}

		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		if ($posted['order']) {
			$ord = $posted['order'][0];
			$col = $ord['column'];
			$dir = $ord['dir'];
			if (isset($order_cols[(int) $col]) && $order_cols[(int) $col] == "pasien.nomor_rm") {
				$sOrder = " ORDER BY LENGTH(REPLACE(" . $order_cols[(int) $col] . "),'RM-','') $dir,REPLACE(" . $order_cols[(int) $col] . ",'RM-','') " . $dir;
			}
		}
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("
			SELECT SQL_CALC_FOUND_ROWS periksa.id,periksa.tgl_periksa,LPAD(periksa.no_test,5,'0') as no_test,periksa.keluhan,periksa.status,
			nomor_rm as no_pasien,periksa.tgl_sampling,pasien.nomor_rm,IFNULL(pasien.kewarganegaraan,'') as kewarganegaraan,
			periksa.update_hasil_at,periksa.cancel_remarks,periksa.deleted,periksa.biaya,periksa.bayar,
			pasien.nama_lengkap as nama_pasien,pasien.no_identitas,pasien.jenis_kelamin,pasien.tempat_lahir,pasien.tgl_lahir,pasien.no_hp,IF(TIMESTAMPDIFF(YEAR, pasien.tgl_lahir, CURDATE())<1,CONCAT(TIMESTAMPDIFF(MONTH, pasien.tgl_lahir, CURDATE()),' bulan'),CONCAT(TIMESTAMPDIFF(YEAR, pasien.tgl_lahir, CURDATE()),' tahun')) as usia,
			provider.nama as provider,jenis.jenis as jenis_pemeriksaan,
			user.name as create_by
			FROM lab_data_pemeriksaan periksa
			INNER JOIN tbl_pasien pasien ON periksa.id_pasien=pasien.id_pasien
			INNER JOIN lab_data_provider provider ON periksa.id_provider=provider.id
			INNER JOIN lab_jenis_pemeriksaan jenis ON periksa.jenis_pemeriksaan=jenis.id
			LEFT JOIN c_users user ON periksa.created_by=user.uid
		$sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$id = $dt->id;
			$status = strtoupper($dt->status);
			$link = ' <a href="' . base_url(conf('path_module_lab') . 'pemeriksaan/form/edit?viewid=' . $dt->id . '&tn=' . strtolower($dt->no_test)) . '" class="link-edit-pemeriksaan" data-id="' . $dt->id . '"><i class="fa fa-stethoscope"></i></a> &nbsp;';
			$link .= ($status == '') ? ' <a href="#" class="link-cancel-pemeriksaan" data-id="' . $dt->id . '"><i class="fa fa-times text-danger"></i></a> &nbsp;' : '';
			$update_at = ($dt->update_hasil_at != "") ? $dt->update_hasil_at : "";
			if ($status == 'SELESAI') {
				$link .= ' <a href="#" class="print-pdf" data-link="' . base_url(conf('path_module_lab') . 'pemeriksaan/form/?viewid=' . $dt->id . '&tn=' . strtolower($dt->no_test)) . '&pdf=true"><i class="fa fa-file-pdf-o text-danger"></i></a> &nbsp;';
				$link .= ' <a href="#" class="link-cancel-pemeriksaan-with-comment" data-id="' . $dt->id . '"><i class="fa fa-times text-danger"></i></a> &nbsp;';
			} else
				if ($status == 'CANCEL' && $dt->deleted == 2) {
				$link .= ' <a href="#" class="link-cancel-pemeriksaan-with-comment" data-id="' . $dt->id . '"><i class="fa fa-times text-danger"></i></a> &nbsp;';
				$update_at = $dt->cancel_remarks . "<br>" . $update_at;
				$link = "";
			}
			return [
				$dt->tgl_periksa,
				$dt->tgl_sampling,
				$dt->no_pasien,
				$dt->no_identitas,
				$dt->nama_pasien,
				$dt->provider,
				$dt->jenis_kelamin,
				$dt->usia,
				$dt->jenis_pemeriksaan,
				$status,
				format_number($dt->biaya),
				format_number($dt->bayar),
				$dt->create_by,
				$update_at,
				$link,
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;

		$output->data = $map_data;
		return (array) $output;
	}

	function _search($id, $tn)
	{
		$data = $this->db->query("
			SELECT periksa.id,periksa.id_provider,periksa.tgl_periksa,LPAD(periksa.no_test,6,'0') as no_test,periksa.keluhan,periksa.status,periksa.id_notes,
			pasien.nomor_rm as id_pasien,pasien.id_pasien as id_pasien_int,periksa.hasil,periksa.status,DATE_FORMAT(periksa.update_hasil_at,'%Y-%m-%d %H:%i:%s') as update_hasil_at,periksa.id_dokter,periksa.jenis_sample,
			periksa.masa_berlaku,periksa.masa_berlaku_opt,DATE_FORMAT(periksa.tgl_sampling,'%Y-%m-%d') as tgl_sampling,DATE_FORMAT(periksa.tgl_sampling,'%H:%i') as jam_sampling,
			pasien.nama_lengkap as nama_pasien,pasien.no_identitas,pasien.nomor_rm,pasien.jenis_kelamin,pasien.tempat_lahir,pasien.tgl_lahir,pasien.no_hp,IF(TIMESTAMPDIFF(YEAR, pasien.tgl_lahir, CURDATE())<1,CONCAT(TIMESTAMPDIFF(MONTH, pasien.tgl_lahir, CURDATE()),' bulan'),CONCAT(TIMESTAMPDIFF(YEAR, pasien.tgl_lahir, CURDATE()),' tahun')) as usia,pasien.alamat,IFNULL(pasien.kewarganegaraan,'') as kewarganegaraan,
			provider.nama as provider,periksa.clinic_id,jenis.metode,
			dokter.namaDokter as dokter,
			jenis.jenis as jenis_pemeriksaan,jenis.category, periksa.jenis_pemeriksaan as id_jenis,
			sampling.nama_sampling as nama_sample,sampling.nama_sampling_en as nama_sample_en,sampling.id as id_sample,
			periksa.asuransi,ass.namaAsuransi as nama_asuransi,periksa.no_asuransi,periksa.perujuk,perujuk.namaPerujuk as nama_perujuk,periksa.nama_tenaga_perujuk
			FROM lab_data_pemeriksaan periksa
			INNER JOIN tbl_pasien pasien ON periksa.id_pasien=pasien.id_pasien
			INNER JOIN lab_data_provider provider ON periksa.id_provider=provider.id
			LEFT JOIN tbdaftardokter dokter ON periksa.id_dokter=dokter.idDokter
			LEFT JOIN lab_jenis_pemeriksaan jenis ON periksa.jenis_pemeriksaan=jenis.id
			LEFT JOIN lab_jenis_sampling sampling ON periksa.jenis_sample=sampling.id
			LEFT JOIN tbasuransi ass ON periksa.asuransi=ass.idAsuransi
			LEFT JOIN tbperujuk perujuk ON periksa.perujuk=perujuk.idPerujuk
			WHERE periksa.id='" . $id . "' AND periksa.no_test='" . $tn . "' GROUP BY periksa.id")->result_object();
		return $data;
	}
	function _search_last_pasien_periksa($id_pasien,$clinic_id)
	{
		$data = $this->db->query("
			SELECT periksa.id,periksa.tgl_periksa,LPAD(periksa.no_test,5,'0') as no_test,periksa.keluhan,periksa.status,periksa.id_notes,
			LPAD(pasien.id_pasien,5,'0') as id_pasien,pasien.id_pasien as id_pasien_int,periksa.hasil,periksa.status,periksa.update_hasil_at,periksa.id_dokter,periksa.jenis_sample,
			periksa.masa_berlaku,periksa.masa_berlaku_opt,IF(periksa.tgl_sampling!='0000-00-00 00:00:00',LEFT(periksa.tgl_sampling,10),'') as tgl_sampling,
			pasien.nama_lengkap as nama_pasien,pasien.no_identitas,pasien.nomor_rm,pasien.jenis_kelamin,pasien.tempat_lahir,pasien.tgl_lahir,pasien.no_hp,IF(TIMESTAMPDIFF(YEAR, pasien.tgl_lahir, CURDATE())<1,CONCAT(TIMESTAMPDIFF(MONTH, pasien.tgl_lahir, CURDATE()),' bulan'),CONCAT(TIMESTAMPDIFF(YEAR, pasien.tgl_lahir, CURDATE()),' tahun')) as usia,pasien.alamat,IFNULL(pasien.kewarganegaraan,'') as kewarganegaraan,
			provider.nama as provider,
			dokter.namaDokter as dokter,
			jenis.jenis as jenis_pemeriksaan, periksa.jenis_pemeriksaan as id_jenis,
			sampling.nama_sampling as nama_sample,sampling.nama_sampling_en as nama_sample_en,sampling.id as id_sample
			FROM lab_data_pemeriksaan periksa
			INNER JOIN tbl_pasien pasien ON periksa.id_pasien=pasien.id_pasien
			INNER JOIN lab_data_provider provider ON periksa.id_provider=provider.id
			LEFT JOIN tbdaftardokter dokter ON periksa.id_dokter=dokter.idDokter
			LEFT JOIN lab_jenis_pemeriksaan jenis ON periksa.jenis_pemeriksaan=jenis.id
			LEFT JOIN lab_jenis_sampling sampling ON periksa.jenis_sample=sampling.id
			WHERE periksa.id_pasien='" . htmlentities(trim($id_pasien)) . "' AND periksa.clinic_id='".htmlentities($clinic_id)."' GROUP BY periksa.id ORDER BY periksa.id DESC")->result_object();
		return $data;
	}
	function _search_detail($id)
	{
		$data = $this->db->query("
			SELECT detail.id,detail.id_pemeriksaan as id_periksa,jenis.id as id_jenis,jenis.category,detail.nama_pemeriksaan,detail.hasil,detail.metode,detail.nilai_rujukan,
			sampling.nama_sampling
			FROM lab_detail_pemeriksaan detail
			INNER JOIN lab_jenis_pemeriksaan jenis ON detail.id_jenis=jenis.id
			INNER JOIN lab_jenis_sampling sampling ON detail.id_sampling=sampling.id
			WHERE detail.id_pemeriksaan='" . $id . "' ORDER BY detail.id ASC")->result_object();
		return $data;
	}
	function _get_list_hasil($jenis_id)
	{
		$this->db->select('id,group_hasil');
		$this->db->from($this->tableOpsiHasil);
		$this->db->where(array("jenis_id" => $jenis_id));
		$this->db->group_by("group_hasil");
		return $this->db->get()->result_object();
	}
	function _get_detail_hasil($jenis_id, $hasil = '')
	{
		$this->db->select('id,nama_pemeriksaan,group_hasil,hasil,nilai_rujukan,metode');
		$this->db->from($this->tableOpsiHasil);
		$where = ["jenis_id" => $jenis_id];
		if ($hasil != "") $where["group_hasil"] = $hasil;
		$this->db->where($where);
		$this->db->order_by("id asc,is_main desc");
		return $this->db->get()->result_object();
	}
	function get_new_no_pemeriksaan($clinic_id)
	{
		$this->db->select('IFNULL(MAX(CAST(no_test as INT))+1,1) as new_no')->from($this->tablePemeriksaan);
		$this->db->where(array("clinic_id"=>$clinic_id));
		$result = $this->db->get()->result_object();
		return $result[0]->new_no;
	}
	function check_notest($where){
		$query=$this->db->get_where($this->tablePemeriksaan,$where);
		$d=$query->row();
		if(!empty($d)) return 'exist';
		return $d;
	}
	function _save($data, $where)
	{
		if (empty($where)) {
			$this->db->insert($this->tablePemeriksaan, $data);
			return $this->db->insert_id();
		} else {
			$this->db->update($this->tablePemeriksaan, $data, $where);
			return $this->db->affected_rows();
		}
	}
	function check_last_assign_detail($id_pasien,$clinic_id)
	{
		return $this->db->query("SELECT a.nama_pemeriksaan,b.status,b.update_hasil_at FROM lab_detail_pemeriksaan a INNER JOIN lab_data_pemeriksaan b ON a.id_pemeriksaan=b.id WHERE a.id_pemeriksaan=(SELECT id FROM lab_data_pemeriksaan WHERE id_pasien='" . $id_pasien . "' AND clinic_id='".$clinic_id."' ORDER BY id DESC LIMIT 1)")->result_object();
	}
	function _update_last_pemeriksaan_pasien($data, $where)
	{
		$this->db->update($this->tablePemeriksaan, $data, $where);
		$this->db->order_by("id", "desc");
		$this->db->limit("1");
		return $this->db->affected_rows();
	}
	function _save_detail($data, $where, $key)
	{
		if (empty($where)) {
			$this->db->select($key)->from($this->tableDetailPemeriksaan)->where($key, $data[0][$key]);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert_batch($this->tableDetailPemeriksaan, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->update($this->tableDetailPemeriksaan, $data, $where);
			return $this->db->affected_rows();
		}
	}
	function _delete_detail_pemeriksaan($where)
	{
		$this->db->delete($this->tableDetailPemeriksaan, $where);
		return $this->db->affected_rows();
	}
	function _cancel($data, $where)
	{
		$this->db->update($this->tablePemeriksaan, $data, $where);
		return $this->db->affected_rows();
	}
	function checkNoTest($no)
	{
		$this->db->select('no_test')->from($this->tablePemeriksaan);
		$this->db->where(array("no_test" => $no));
		return $this->db->get()->result();
	}
	/* JENIS SAMPLE */
	function _search_jenis_sample($where)
	{
		$this->db->select('id as _id,nama_sampling,nama_sampling_en');
		$this->db->from($this->tableJenisSampling);
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _load_dt_jenis_sample($posted)
	{
		$orders_cols = ["nama_sampling", "nama_sampling_en", "id"];
		$output = build_filter_table($posted, $orders_cols);
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere.=($sWhere=="") ? " WHERE ": " AND ";
			$sWhere.= " (nama_sampling LIKE '%" . $output->search . "%' OR nama_sampling_en LIKE '%" . $output->search . "%')";
		}
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . ",created_by,created_at FROM lab_jenis_sampling $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$id = $dt->id;
			return [
				$dt->nama_sampling,
				$dt->nama_sampling_en,
				'<a href="#" class="link-edit-jenis-sample" data-id="' . $dt->id . '"><i class="fa fa-edit"></i></a>  &nbsp;
						<a href="#" class="link-delete-jenis-sample" data-id="' . $dt->id . '"><i class="fa fa-trash text-danger"></i></a>
						'
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function _save_jenis_sample($data, $where, $key)
	{
		if (empty($where)) {
			// check before insert
			$this->db->select($key)->from($this->tableJenisSampling)->where(array("nama_sampling"=>$data["nama_sampling"],"clinic_id"=>$data['clinic_id']));
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->tableJenisSampling, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->select('id')->from($this->tableJenisSampling);
			$this->db->where(array("nama_sampling"=>$data["nama_sampling"],"clinic_id"=>$data['clinic_id']));
			$this->db->where("id!=", $where['id']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->update($this->tableJenisSampling, $data, $where);
			return $this->db->affected_rows();
		}
	}
	function _delete_jenis_sample($where)
	{
		$this->db->delete($this->tableJenisSampling, $where);
		return $this->db->affected_rows();
	}
	/* NOTEST */
	function _search_notes($where_in)
	{
		$this->db->select('id,notes,english')->from('lab_data_notes');
		$this->db->where_in("id", $where_in);
		$this->db->order_by("id", "asc");
		return $this->db->get()->result_object();
	}
	function _search_notes_select2($key = "",$clinic_id)
	{
		$this->db->select('id,notes as text');
		$this->db->from($this->tableNotes);
		$this->db->where(array("clinic_id"=>$clinic_id));
		if ($key != "") {
			$this->db->like('notes', $key);
			// $this->db->or_like('note_group', $key);
		}
		
		$this->db->limit(20);
		return $this->db->get()->result_array();
	}
	function _load_dt_notes($posted)
	{
		$orders_cols = ["notes", "english", "id"];
		$output = build_filter_table($posted, $orders_cols,[],"clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere.=($sWhere=="") ? " WHERE ": " AND ";
			$sWhere.= " notes LIKE '%" . $output->search . "%'";
		}
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . ",created_by,created_at FROM lab_data_notes $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$id = $dt->id;
			return [
				$dt->notes,
				$dt->english,
				'<a href="#" class="link-edit-notes" data-id="' . $dt->id . '"><i class="fa fa-edit"></i></a>  &nbsp;
						<a href="#" class="link-delete-notes" data-id="' . $dt->id . '"><i class="fa fa-trash text-danger"></i></a>
						'
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function _save_notes($data, $where, $key)
	{
		if (empty($where)) {
			// check before insert
			$this->db->select($key)->from($this->tableNotes)->where(array("notes"=>$data["notes"],"clinic_id"=>$data["clinic_id"]));
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->tableNotes, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->select('id')->from($this->tableNotes);
			$this->db->where($key, $data[$key]);
			$this->db->where("id!=", $where['id']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->update($this->tableNotes, $data, $where);
			return $this->db->affected_rows();
		}
	}
	function _delete_notes($where)
	{
		$this->db->delete($this->tableNotes, $where);
		return $this->db->affected_rows();
	}
	function _get_setting($code = '')
	{
		$this->db->select('content,size,width,height')->from($this->tableSetting)->where(array("code" => $code));
		$dt = $this->db->get()->result_object();
		if (empty($dt)) {
			return "";
		} else {
			return $dt[0];
		}
	}

	// for support webview list pemeriksaan 
	function _list_pemeriksaan_pasien($id_akun, $day_filter = "")
	{
		if ($day_filter == "") $day_filter = " 1 YEAR";
		$andWhere = " AND periksa.tgl_periksa>=DATE(NOW()) - INTERVAL $day_filter";
		$data = $this->db->query("
			SELECT periksa.id,periksa.tgl_periksa,LPAD(periksa.no_test,6,'0') as no_test,periksa.keluhan,periksa.status,periksa.id_notes,
			LPAD(pasien.id_pasien,6,'0') as id_pasien,pasien.id_pasien as id_pasien_int,periksa.hasil,periksa.status,periksa.update_hasil_at,periksa.id_dokter,periksa.jenis_sample,
			periksa.masa_berlaku,periksa.masa_berlaku_opt,IF(periksa.tgl_sampling!='0000-00-00 00:00:00',LEFT(periksa.tgl_sampling,10),'') as tgl_sampling,
			pasien.nama_lengkap as nama_pasien,pasien.no_identitas,pasien.nomor_rm,pasien.jenis_kelamin,pasien.tempat_lahir,pasien.tgl_lahir,pasien.no_hp,IF(TIMESTAMPDIFF(YEAR, pasien.tgl_lahir, CURDATE())<1,CONCAT(TIMESTAMPDIFF(MONTH, pasien.tgl_lahir, CURDATE()),' bulan'),CONCAT(TIMESTAMPDIFF(YEAR, pasien.tgl_lahir, CURDATE()),' tahun')) as usia,pasien.alamat,IFNULL(pasien.kewarganegaraan,'') as kewarganegaraan,
			pasien.reg_as,
			provider.nama as provider,
			dokter.namaDokter as dokter,
			jenis.jenis as jenis_pemeriksaan, periksa.jenis_pemeriksaan as id_jenis,
			sampling.nama_sampling as nama_sample,sampling.nama_sampling_en as nama_sample_en,sampling.id as id_sample
			FROM lab_data_pemeriksaan periksa
			INNER JOIN tbl_pasien pasien ON periksa.id_pasien=pasien.id_pasien
			INNER JOIN lab_data_provider provider ON periksa.id_provider=provider.id
			LEFT JOIN tbdaftardokter dokter ON periksa.id_dokter=dokter.idDokter
			LEFT JOIN lab_jenis_pemeriksaan jenis ON periksa.jenis_pemeriksaan=jenis.id
			LEFT JOIN lab_jenis_sampling sampling ON periksa.jenis_sample=sampling.id
			WHERE pasien.akun_id_pasien='" . htmlentities(trim($id_akun)) . "' " . $andWhere . " GROUP BY periksa.id ORDER BY periksa.id DESC")->result_object();
		return $data;
	}
	function _list_jadwal_pemeriksaan($id_akun)
	{
		$andWhere = " AND periksa.tgl_periksa>=CURDATE() AND periksa.status=''";
		$data = $this->db->query("
			SELECT periksa.id,periksa.tgl_periksa,plan_jam_periksa as jam_periksa,LPAD(periksa.no_test,6,'0') as no_test,periksa.keluhan,periksa.status,periksa.id_notes,
			LPAD(pasien.id_pasien,6,'0') as id_pasien,pasien.id_pasien as id_pasien_int,periksa.hasil,periksa.status,periksa.update_hasil_at,periksa.id_dokter,periksa.jenis_sample,
			periksa.masa_berlaku,periksa.masa_berlaku_opt,IF(periksa.tgl_sampling!='0000-00-00 00:00:00',LEFT(periksa.tgl_sampling,10),'') as tgl_sampling,
			pasien.nama_lengkap as nama_pasien,pasien.no_identitas,pasien.nomor_rm,pasien.jenis_kelamin,pasien.tempat_lahir,pasien.tgl_lahir,pasien.no_hp,IF(TIMESTAMPDIFF(YEAR, pasien.tgl_lahir, CURDATE())<1,CONCAT(TIMESTAMPDIFF(MONTH, pasien.tgl_lahir, CURDATE()),' bulan'),CONCAT(TIMESTAMPDIFF(YEAR, pasien.tgl_lahir, CURDATE()),' tahun')) as usia,pasien.alamat,IFNULL(pasien.kewarganegaraan,'') as kewarganegaraan,
			pasien.reg_as,
			provider.nama as provider,
			dokter.namaDokter as dokter,
			jenis.jenis as jenis_pemeriksaan, periksa.jenis_pemeriksaan as id_jenis,
			sampling.nama_sampling as nama_sample,sampling.nama_sampling_en as nama_sample_en,sampling.id as id_sample
			FROM lab_data_pemeriksaan periksa
			INNER JOIN tbl_pasien pasien ON periksa.id_pasien=pasien.id_pasien
			INNER JOIN lab_data_provider provider ON periksa.id_provider=provider.id
			LEFT JOIN tbdaftardokter dokter ON periksa.id_dokter=dokter.idDokter
			LEFT JOIN lab_jenis_pemeriksaan jenis ON periksa.jenis_pemeriksaan=jenis.id
			LEFT JOIN lab_jenis_sampling sampling ON periksa.jenis_sample=sampling.id
			WHERE pasien.akun_id_pasien='" . htmlentities(trim($id_akun)) . "' " . $andWhere . " GROUP BY periksa.id ORDER BY periksa.id DESC")->result_object();
		return $data;
	}
	function _list_kwitansi_pasien($id_akun, $day_filter = "")
	{
		if ($day_filter == "") $day_filter = " 1 YEAR "; // ex.  14 DAY
		$andWhere = " AND periksa.biaya>0 AND periksa.status='SELESAI' AND periksa.tgl_periksa>=DATE(NOW()) - INTERVAL $day_filter";
		$data = $this->db->query("
			SELECT periksa.id,periksa.tgl_periksa,LPAD(periksa.no_test,6,'0') as no_test,periksa.keluhan,periksa.status,periksa.id_notes,
			LPAD(pasien.id_pasien,6,'0') as id_pasien,pasien.id_pasien as id_pasien_int,periksa.hasil,periksa.status,periksa.update_hasil_at,periksa.id_dokter,periksa.jenis_sample,
			periksa.masa_berlaku,periksa.masa_berlaku_opt,IF(periksa.tgl_sampling!='0000-00-00 00:00:00',LEFT(periksa.tgl_sampling,10),'') as tgl_sampling,
			periksa.biaya,periksa.bayar,
			pasien.nama_lengkap as nama_pasien,pasien.no_identitas,pasien.nomor_rm,pasien.jenis_kelamin,pasien.tempat_lahir,pasien.tgl_lahir,pasien.no_hp,IF(TIMESTAMPDIFF(YEAR, pasien.tgl_lahir, CURDATE())<1,CONCAT(TIMESTAMPDIFF(MONTH, pasien.tgl_lahir, CURDATE()),' bulan'),CONCAT(TIMESTAMPDIFF(YEAR, pasien.tgl_lahir, CURDATE()),' tahun')) as usia,pasien.alamat,IFNULL(pasien.kewarganegaraan,'') as kewarganegaraan,
			pasien.reg_as,
			provider.nama as provider,
			dokter.namaDokter as dokter,
			jenis.jenis as jenis_pemeriksaan, periksa.jenis_pemeriksaan as id_jenis,
			sampling.nama_sampling as nama_sample,sampling.nama_sampling_en as nama_sample_en,sampling.id as id_sample
			FROM lab_data_pemeriksaan periksa
			INNER JOIN tbl_pasien pasien ON periksa.id_pasien=pasien.id_pasien
			INNER JOIN lab_data_provider provider ON periksa.id_provider=provider.id
			LEFT JOIN tbdaftardokter dokter ON periksa.id_dokter=dokter.idDokter
			LEFT JOIN lab_jenis_pemeriksaan jenis ON periksa.jenis_pemeriksaan=jenis.id
			LEFT JOIN lab_jenis_sampling sampling ON periksa.jenis_sample=sampling.id
			WHERE pasien.akun_id_pasien='" . htmlentities(trim($id_akun)) . "' " . $andWhere . " GROUP BY periksa.id ORDER BY periksa.id DESC")->result_object();
		return $data;
	}
	/* new for jenis pemeriksaan with item n sub item */
	function _search_item_jenis_pemeriksaan($where){
		$query=$this->db->get_where($this->tableItemJenisPemeriksaan,$where);
		return $query->result_array();
	}
	function _search_subitem_jenis_pemeriksaan($where){
		$query=$this->db->get_where($this->tableSubItemJenisPemeriksaan,$where);
		return $query->result_array();
	}

	function saveHasilPeriksa($data){
		$this->db->insert($this->tableHasilPeriksa,$data);
		return $this->db->insert_id();
	}
	function deleteItemHasilPeriksa($where){
		$this->db->delete($this->tableItemHasilPeriksa,$where);
		return $this->db->affected_rows();
	}
	function saveItemHasilPeriksa($data){
		$this->db->insert($this->tableItemHasilPeriksa,$data);
		return $this->db->insert_id();
	}
	function deleteSubItemHasilPeriksa($where){
		$this->db->delete($this->tableSubItemHasilPeriksa,$where);
		return $this->db->affected_rows();
	}
	function saveSubItemHasilPeriksa($data){
		$this->db->insert($this->tableSubItemHasilPeriksa,$data);
		return $this->db->insert_id();
	}
	function _searchItemHasilPeriksa($where){
		$this->db->select("*")->from($this->tableItemHasilPeriksa);
		$this->db->where($where);
		$this->db->order_by("sub_id","ASC");
		return $this->db->get()->result_array();
	}
	function _searchSubItemHasilPeriksa($where){
		$query=$this->db->get_where($this->tableSubItemHasilPeriksa,$where);
		return $query->result_array();
	}
	function _reset_item_hasil_periksa($id){
		$this->db->delete($this->tableItemHasilPeriksa,array("id_periksa"=>$id));
		$this->db->reset_query();
		$this->db->delete($this->tableSubItemHasilPeriksa,array("id_periksa"=>$id));
		return $this->db->affected_rows();
	}

	/* end new */
	// 2022-01-16 add to billing
	function isBillingExist($ref_id){
		$this->db->where('fk_pendaftaran',$ref_id);
    $query = $this->db->get('tbl_bayar_periksa');
    if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	function save_billing($clinic_id,$ref_id,$biaya,$biaya_dokter=0,$user){
		$data=array("fk_pendaftaran"=>$ref_id,"clinic_id"=>$clinic_id,"is_kwitansi_lab"=>1,"biaya"=>$biaya,"tarif_dokter"=>$biaya_dokter,"creator_id"=>$user);
		if(!$this->isBillingExist($ref_id)){
			$this->db->insert('tbl_bayar_periksa',$data);
		}else{
			$this->db->update('tbl_bayar_periksa',$data,array("clinic_id"=>$clinic_id,"fk_pendaftaran"=>$ref_id));
		}
		return $this->db->affected_rows();
	}
}

/* End of file Pemeriksaan_model.php */
