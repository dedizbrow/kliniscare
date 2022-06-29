<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Jadwal_dokter_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tableJadwalDokter = "tbjadwaldokter";
		$this->tableDaftarDokter = "tbdaftardokter";
		$this->tableHari = "hari";
		$this->load->helper('ctc');
	}
	function _load_dt($posted)
	{
		$orders_cols = ["idJadwal", "namaDokter", "namaHari", "dari_jam", "sampai_jam"];
		$output = build_filter_table($posted, $orders_cols, [], "tbjadwaldokter.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (namaDokter LIKE '%" . $output->search . "%' OR namaHari LIKE '%" . $output->search . "%')";
		}
		if (isset($posted['sorting_dokter']) && $posted['sorting_dokter'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " dokter='" . htmlentities($posted['sorting_dokter']) . "'";
		}
		if (isset($posted['sorting_hari']) && $posted['sorting_hari'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " hari='" . htmlentities($posted['sorting_hari']) . "'";
		}
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbjadwaldokter join tbdaftardokter on tbdaftardokter.idDokter=tbjadwaldokter.dokter join hari on hari.idHari=tbjadwaldokter.hari $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$link = "";
			if (isAllowed('c-jadwaldokter^update', true))
				$link .= '<a href="#" class="link-edit-jadwal-dokter" data-id="' . $dt->idJadwal . '"><i class="fa fa-edit"></i></a>  &nbsp;';
			if (isAllowed('c-jadwaldokter^delete', true))
				$link .= '<a href="#" class="link-delete-jadwal-dokter" data-id="' . $dt->idJadwal . '"><i class="fa fa-trash text-danger"></i></a>';
			return [
				$dt->idJadwal,
				$dt->namaDokter,
				$dt->namaHari,
				$dt->dari_jam,
				$dt->sampai_jam,
				$link
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function _search($where)
	{
		$this->db->select('idJadwal as _id,namaDokter,namaHari,dari_jam,sampai_jam,hari,dokter');
		$this->db->from($this->tableJadwalDokter);
		$this->db->join($this->tableDaftarDokter, 'tbdaftardokter.idDokter=tbjadwaldokter.dokter');
		$this->db->join($this->tableHari, 'hari.idHari=tbjadwaldokter.hari');
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _search_select2($key = "", $clinic_id)
	{
		$this->db->select('idDokter as id,namaDokter as text');
		$this->db->from($this->tableDaftarDokter);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('namaDokter', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_select_hari($key = "")
	{
		$this->db->select('idHari as id,namaHari as text');
		$this->db->from($this->tableHari);
		if ($key != "") {
			$this->db->like('namaHari', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _save($data, $where, $key, $key2, $key3, $clinic_id)
	{
		if (empty($where)) {
			$this->db->select($key)->from($this->tableJadwalDokter)->where($key, $data[$key]);
			$this->db->where($key, $data[$key]);
			$this->db->where($key2, $data[$key2]);
			$this->db->where($key3, $data[$key3]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->tableJadwalDokter, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->select('idJadwal')->from($this->tableJadwalDokter);
			$this->db->where($key, $data[$key]);
			$this->db->where($key2, $data[$key2]);
			$this->db->where($key3, $data[$key3]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$this->db->where("idJadwal!=", $where['idJadwal']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->update($this->tableJadwalDokter, $data, $where);
			return $this->db->affected_rows();
		}
	}
	function _delete($where)
	{
		$this->db->delete($this->tableJadwalDokter, $where);
		return $this->db->affected_rows();
	}
	public function jadwal_list($clinic_id)
	{
		$this->db->select(array('namaDokter', 'namaHari', 'dari_jam', 'sampai_jam'));
		$this->db->from('tbjadwaldokter');
		$this->db->join('tbdaftardokter', 'tbdaftardokter.idDokter=tbjadwaldokter.dokter');
		$this->db->join('hari', 'hari.idHari=tbjadwaldokter.hari');
		$this->db->where("tbdaftardokter.clinic_id", $clinic_id);
		$query = $this->db->get();
		return $query->result();
	}

	function _list_dokter()
	{
		$this->db->select('idDokter,namaDokter')->from($this->tableDaftarDokter)->order_by("namaDokter");
		return $this->db->get()->result_object();
	}
	function _list_hari()
	{
		$this->db->select('idHari,namaHari')->from($this->tableHari)->order_by("namaHari");
		return $this->db->get()->result_object();
	}
}
