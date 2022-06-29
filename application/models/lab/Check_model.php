<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Check_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->TablePemeriksaan = "lab_data_pemeriksaan";
		$this->TableJenisPemeriksaan = "lab_jenis_pemeriksaan";
		$this->TablePasien = "tbl_pasien";
		$this->TableProvider = "lab_data_provider";
		$this->tableSetting = "lab_settings";
	}
	function no_doc($where)
	{
		$this->db->select("periksa.nama_pasien,jns.jenis as jenis_periksa,periksa.hasil,periksa.update_hasil_at,periksa.tgl_periksa,periksa.masa_berlaku,periksa.masa_berlaku_opt,periksa.status,pasien.nomor_rm,provider.nama as provider");
		$this->db->from($this->TablePemeriksaan . " periksa");
		$this->db->join($this->TablePasien . " pasien", "periksa.id_pasien=pasien.id_pasien");
		$this->db->join($this->TableProvider . " provider", "periksa.id_provider=provider.id");
		$this->db->join($this->TableJenisPemeriksaan." jns","periksa.jenis_pemeriksaan=jns.id");
		$this->db->where($where);
		return $this->db->get()->result_object();
	}
	function update($where)
	{
		$data = ["qr_check" => 1, "qr_checkdate" => date("Y-m-d h:i:s"), "src_check" => $_SERVER['REMOTE_ADDR']];
		$this->db->update($this->TablePemeriksaan." periksa", $data, $where);
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
}

/* End of file Check_model.php */
/* Location: ./application/models/Check_model.php */
