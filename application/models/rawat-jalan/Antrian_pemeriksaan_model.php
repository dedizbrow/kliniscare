<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Antrian_pemeriksaan_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablePendaftaran = "tbl_pendaftaran";
		$this->tablePasien = "tbl_pasien";
		$this->tableAntrian = "tbl_antrian";
		$this->tablePemeriksaan = "tbl_pemeriksaan";
		$this->tableDokter = "tbdaftardokter";
		$this->tableDiagnosa = "tbdiagnosis";
		$this->tablePemDiagnosa = "tbl_pemeriksaan_diagnosa";
		$this->tableLayananPoli = "tbl_layanan_poli";
		$this->load->helper('ctc');
	}

	function _load_dt($posted)
	{
		$orders_cols = ["antrian.id_antrian", "antrian.nomor_antrian", "pasien.asuransi_utama", "pasien.no_asuransi", "pasien.nomor_rm", "pasien.nama_lengkap", "pasien.alamat", "poliklinik.namaPoli", "asuransi.namaAsuransi", "pendaftaran.no_invoice"];
		$output = build_filter_table($posted, $orders_cols, [], "antrian.clinic_id");
		$sWhere = $output->where;

		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= "nama_lengkap LIKE '%" . $output->search . "%'";
		}
		$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
		$sWhere .= " status=0 and DATE(antrian.create_at)=CURDATE()";
		// echo $sWhere;
		$sLimit = $output->limit;
		$sGroup = "";
		$dateNow = date('Y-m-d');
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . ",pasien.id_pasien FROM tbl_antrian AS antrian LEFT JOIN tbl_pendaftaran AS pendaftaran ON antrian.id_antrian = pendaftaran.fk_antrian LEFT JOIN tbl_pasien AS pasien ON pendaftaran.fk_pasien = pasien.id_pasien LEFT JOIN tbpoli as poliklinik ON pendaftaran.poli = poliklinik.idPoli LEFT JOIN tbasuransi AS asuransi ON pasien.asuransi_utama = asuransi.idAsuransi $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();

		$map_data = array_map(function ($dt) {
			$id_antrian = $dt->id_antrian;
			return [
				$dt->id_antrian,
				'<a href="#" class="btn btn-primary btn-xs link-periksa" data-dismiss="modal" data-id="' . $dt->id_antrian . '"><i class="fa fa-stethoscope"></i></a>',
				$dt->no_invoice,
				$dt->nomor_antrian . 
				' <a href="#" class="ml-1 btn btn-danger btn-xs link-lewati float-right" data-dismiss="modal" data-id="' . $dt->id_antrian . '" data-poli="'.$dt->namaPoli.'">Lewati</a>'.
				' <a href="#" class="btn  btn-success btn-xs link-panggil-antrian float-right" data-dismiss="modal" data-nomor="'.$dt->nomor_antrian.'"  data-poli="'.$dt->namaPoli.'" data-id="' . $dt->id_antrian . '"><i class="fa fa-bullhorn"></i> Panggil</a>',
				$dt->namaPoli,
				$dt->namaAsuransi,
				$dt->no_asuransi,
				'<a href="#" class="btn btn-primary btn-xs link-history" data-id="' . $dt->id_pasien . '">'.$dt->nomor_rm.'</a>',
				$dt->nama_lengkap,
				$dt->alamat,
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function ubah_status($id)
	{
		$this->db->query("UPDATE tbl_antrian SET status='1' where id_antrian='$id'");
	}

	function _search_pendaftaran($where)
	{
		$this->db->select('antrian.*,antrian.id_antrian as _id_antrian,pendaftaran.id_pendaftaran as _id,pasien.nama_lengkap,pasien.nomor_rm,poliklinik.namaPoli,dokter.namaDokter');
		$this->db->from('tbl_antrian as antrian');
		$this->db->join('tbl_pendaftaran as pendaftaran', 'pendaftaran.fk_antrian=antrian.id_antrian');
		$this->db->join('tbl_pasien as pasien', 'pendaftaran.fk_pasien=pasien.id_pasien');
		$this->db->join('tbpoli as poliklinik', 'pendaftaran.poli=poliklinik.idPoli');
		$this->db->join('tbdaftardokter as dokter', 'pendaftaran.dpjp=dokter.idDokter');
		$this->db->where($where);

		return $this->db->get()->result();
	}
	function _save($data)
	{
		$this->db->trans_start();
		$this->db->insert($this->tablePemeriksaan, $data);
		$id_pemeriksaan = $this->db->insert_id();
		$this->db->trans_complete();
		return $id_pemeriksaan;
	}
	function _save_diagnosa($data)
	{
		$this->db->insert_batch('tbl_pemeriksaan_diagnosa', $data);
	}
	function _save_tindakan($data)
	{
		$this->db->insert_batch('tbl_pemeriksaan_tindakan', $data);
	}

	function ubah_status_afterpemeriksaan($id)
	{
		$this->db->query("UPDATE tbl_antrian SET status='2' where id_antrian='$id'");
	}

	function _search_select_dokter($key = "", $clinic_id)
	{
		$this->db->select('idDokter as id,namaDokter as text');
		$this->db->from($this->tableDokter);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('namaDokter', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_select_diagnosa($key = "", $clinic_id)
	{
		$this->db->select('idDiagnosis as id,namaDiagnosis as text');
		$this->db->from($this->tableDiagnosa);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('namaDiagnosis', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_select_tindakan($key = "", $clinic_id)
	{
		$this->db->select('id_layanan_poli as id,nama_layanan_poli as text');
		$this->db->from($this->tableLayananPoli);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('nama_layanan_poli', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
}
