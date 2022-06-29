<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Data_pasien_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablePasien = "tbl_pasien";
		$this->load->helper('ctc');
	}

	function _load_dt($posted)
	{
		$orders_cols = ["id_pasien", "nomor_rm", "no_identitas", "nama_lengkap", "no_hp", "pasien.no_telp", "alamat", "create_at"];
		$output = build_filter_table($posted, $orders_cols, [], "pasien.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (no_identitas LIKE '%" . $output->search . "%' OR nama_lengkap LIKE '%" . $output->search . "%' OR nomor_rm LIKE '%" . $output->search . "%')";
		}
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . ",IFNULL(usr.name,'---') as user FROM tbl_pasien AS pasien LEFT JOIN c_users usr ON pasien.creator_id=usr.uid $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();

		$map_data = array_map(function ($dt) {
			$id_pasien = $dt->id_pasien;
			$link = "";
			if (isAllowed('c-datapasien^update', true))
				$link .= '<a href="#" class="link-edit-pasien" data-id="' . $dt->id_pasien . '"><i class="fa fa-edit"></i></a>  &nbsp;';
			if (isAllowed('c-datapasien^delete', true))
				$link .= '<a href="#" class="link-delete-pasien" data-id="' . $dt->id_pasien . '"><i class="fa fa-trash text-danger"></i></a>';
			return [
				$dt->id_pasien,
				'<a href="' . base_url('rawat-jalan/data-pasien/print/?viewid=' . $id_pasien) . '" target="_blank" class="print-pdf">' . $dt->nomor_rm . '</a>',
				$dt->nama_lengkap,
				'<a href="#" class="btn btn-primary btn-xs link-history" data-id="' . $dt->id_pasien . '">RM</a> <a href="#" class="btn btn-primary btn-xs link-history-lab" data-id="' . $dt->id_pasien . '">LAB</a>',
				$dt->no_identitas,
				$dt->no_hp,
				$dt->alamat,
				$dt->create_at,
				$dt->user,
				'<a href="#" class="link-info-pasien" data-id="' . $dt->id_pasien . '"><i class="fa fa-info-circle"></i></a>  &nbsp;' . $link . ''
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function _save_update($data, $where, $key, $clinic_id)
	{
		$this->db->select('id_pasien')->from($this->tablePasien);
		$this->db->where($key, $data[$key]);
		$this->db->where($clinic_id, $data[$clinic_id]);
		$this->db->where("id_pasien!=", $where['id_pasien']);
		$check = $this->db->get()->result();
		if (!empty($check)) return 'exist';
		$this->db->update($this->tablePasien, $data, $where);
		return $this->db->affected_rows();
	}
	function _search($where)
	{
		$this->db->select('id_pasien as _id,nomor_rm,nama_lengkap,jenis_kelamin,tempat_lahir,tgl_lahir,umur,pasien.email,pasien.kewarganegaraan,status_nikah,agama,gol_darah,identitas,no_identitas,provinsi,kabupaten,fix_agama.nama_agama,kecamatan,pasien.alamat,no_hp,no_telp,pekerjaan,perusahaan,ibu_kandung,asuransi_utama,asuransi.namaAsuransi,fix_status_pernikahan.nama_status_pernikahan,fix_gol_darah.nama_gol_darah ,no_asuransi,kec.nama as nama_kecamatan,kab.nama as nama_kabupaten,prov.nama as nama_provinsi,asuransi.namaAsuransi as nama_asuransi');
		$this->db->from($this->tablePasien . " pasien");
		$this->db->join('wilayah_kecamatan kec', 'pasien.kecamatan=kec.id', 'LEFT');
		$this->db->join('wilayah_kabupaten kab', 'pasien.kabupaten=kab.id', 'LEFT');
		$this->db->join('wilayah_provinsi prov', 'pasien.provinsi=prov.id', 'LEFT');
		$this->db->join('tbasuransi asuransi', 'pasien.asuransi_utama=asuransi.idAsuransi', 'LEFT');
		$this->db->join('fix_agama', 'pasien.agama=fix_agama.id_agama', 'LEFT');
		$this->db->join('fix_status_pernikahan', 'fix_status_pernikahan.id_status_pernikahan=pasien.status_nikah', 'LEFT');
		$this->db->join('fix_gol_darah', 'fix_gol_darah.id_gol_darah=pasien.gol_darah', 'LEFT');
		$this->db->where($where);
		return $this->db->get()->result();
	}

	function _search_pemeriksaan($id)
	{
		$data = $this->db->query('select id_pasien as _id,nomor_rm,nama_lengkap,pasien.*,pend.*,pend.create_at as tanggal_kunjungan,pem.*,IFNULL(GROUP_CONCAT(DISTINCT diag.namaDiagnosis SEPARATOR "|"),"") as diagnosa,IFNULL(GROUP_CONCAT(DISTINCT layanan_poli.nama_layanan_poli SEPARATOR "|"),"") as tindakan, kec.nama as nama_kecamatan,kab.nama as nama_kabupaten,prov.nama as nama_provinsi FROM tbl_pasien pasien INNER JOIN tbl_pendaftaran pend ON pasien.id_pasien=pend.fk_pasien 
		LEFT JOIN tbl_pemeriksaan pem ON pend.id_pendaftaran=pem.id_pendaftaran
		LEFT JOIN tbl_pemeriksaan_diagnosa p_diag ON pem.id_pemeriksaan=p_diag.fk_pemeriksaan
        LEFT JOIN tbdiagnosis diag ON p_diag.fk_diagnosa=diag.idDiagnosis
        LEFT JOIN tbl_pemeriksaan_tindakan p_tind ON pem.id_pemeriksaan=p_tind.fk_pemeriksaan
		LEFT JOIN tbl_layanan_poli layanan_poli ON p_tind.fk_tindakan=layanan_poli.id_layanan_poli
		LEFT JOIN wilayah_kecamatan kec ON pasien.kecamatan=kec.id
		LEFT JOIN wilayah_kabupaten kab ON pasien.kabupaten=kab.id
		LEFT JOIN wilayah_provinsi prov ON pasien.provinsi=prov.id
		 WHERE pasien.id_pasien="' . $id . '" GROUP BY pem.id_pemeriksaan ORDER BY pend.id_pendaftaran DESC')->result_object();
		return $data;
	}
	function _search_pemeriksaan_lab($id)
	{
		$data = $this->db->query('select pasien.id_pasien as _id,nomor_rm,nama_lengkap,lab.tgl_periksa,lab.hasil,sampling.nama_sampling,pem.jenis FROM tbl_pasien pasien INNER JOIN lab_data_pemeriksaan lab ON pasien.id_pasien=lab.id_pasien 

		LEFT JOIN lab_jenis_sampling sampling ON lab.jenis_sample=sampling.id
		LEFT JOIN lab_jenis_pemeriksaan pem ON lab.jenis_pemeriksaan=pem.id
		WHERE pasien.id_pasien="' . $id . '" GROUP BY lab.id ORDER BY lab.id DESC')->result_object();
		return $data;
	}

	public function pasien_list($clinic_id)
	{
		$this->db->select(array('id_pasien', 'nomor_rm', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'pasien.email', 'kewarganegaraan', 'tgl_lahir', 'umur', 'status_nikah', 'nama_status_pernikahan', 'agama', 'nama_agama', 'gol_darah', 'nama_gol_darah', 'identitas', 'no_identitas', 'provinsi', 'kabupaten', 'kecamatan', 'pasien.alamat', 'no_hp', 'no_telp', 'pekerjaan', 'perusahaan', 'ibu_kandung', 'asuransi_utama', 'no_asuransi', 'kec.nama as kecamatan', 'kab.nama as kabupaten', 'prov.nama as provinsi', 'asuransi.namaAsuransi as nama_asuransi'));
		$this->db->from($this->tablePasien . " pasien");
		$this->db->join('wilayah_kecamatan kec', 'pasien.kecamatan=kec.id', 'LEFT');
		$this->db->join('wilayah_kabupaten kab', 'pasien.kabupaten=kab.id', 'LEFT');
		$this->db->join('wilayah_provinsi prov', 'pasien.provinsi=prov.id', 'LEFT');
		$this->db->join('tbasuransi asuransi', 'pasien.asuransi_utama=asuransi.idAsuransi', 'LEFT');
		$this->db->join('fix_agama agama', 'pasien.agama=agama.id_agama', 'LEFT');
		$this->db->join('fix_gol_darah', 'fix_gol_darah.id_gol_darah=pasien.gol_darah', 'LEFT');
		$this->db->join('fix_status_pernikahan', 'fix_status_pernikahan.id_status_pernikahan=pasien.status_nikah', 'LEFT');
		$this->db->where("pasien.clinic_id", $clinic_id);
		$query = $this->db->get();
		return $query->result();
	}
	function _delete($where)
	{
		$this->db->delete($this->tablePasien, $where);
		return $this->db->affected_rows();
	}

	function _num_rows($clinic_id)
	{
		return $this->db->get_where($this->tablePasien, array('clinic_id' => $clinic_id))->num_rows();
	}
	function _list_status_nikah()
	{
		$this->db->select('id_status_pernikahan,nama_status_pernikahan')->from('fix_status_pernikahan')->order_by("nama_status_pernikahan");
		return $this->db->get()->result_object();
	}
	function _list_agama()
	{
		$this->db->select('id_agama,nama_agama')->from('fix_agama')->order_by("nama_agama");
		return $this->db->get()->result_object();
	}
	function _list_gol()
	{
		$this->db->select('id_gol_darah,nama_gol_darah')->from('fix_gol_darah')->order_by("nama_gol_darah");
		return $this->db->get()->result_object();
	}
	function _list_asuransi()
	{
		$this->db->select('idAsuransi,namaAsuransi')->from('tbasuransi')->order_by("namaAsuransi");
		return $this->db->get()->result_object();
	}
	function _save_import($data, $key, $clinic_id)
	{
		$this->db->select($key)->from($this->tablePasien)->where($key, $data[$key]);
		$this->db->where($clinic_id, $data[$clinic_id]);
		$check = $this->db->get()->result();
		if (!empty($check)) return 'exist';
		$this->db->insert($this->tablePasien, $data);
		return $this->db->affected_rows();
	}
}
