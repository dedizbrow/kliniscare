<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pasien_telah_diperiksa_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		// $this->tableBiaya = "tbl_biaya_farmasi";
		$this->tablePendaftaran = "tbl_pendaftaran";
		$this->tableAturanPakai = "tbl_resep_aturan_pakai";
		$this->tableCaraPakai = "tbl_resep_cara_pakai";
		$this->tableObat = "tbl_obat";
		$this->tableDiagnosis = "tbdiagnosis";
		$this->tableTindakan = "tbl_layanan_poli";
		$this->tablePemeriksaanDiagnosa = "tbl_pemeriksaan_diagnosa";
		$this->tablePemeriksaanTindakan = "tbl_pemeriksaan_tindakan";
		$this->load->helper('ctc');
	}

	function _load_dt($posted)
	{
		$orders_cols = ["antrian.id_antrian", "antrian.nomor_antrian", "antrian.status", "pasien.id_pasien", "pasien.asuransi_utama", "pasien.no_asuransi", "pasien.nomor_rm", "pasien.nama_lengkap", "pasien.alamat", "poliklinik.namaPoli", "resep.status as status_resep", "asuransi.namaAsuransi", "pendaftaran.no_invoice", "pendaftaran.id_pendaftaran", "pendaftaran.status_rawat"];
		$output = build_filter_table($posted, $orders_cols, [], "antrian.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= "(nama_lengkap LIKE '%" . $output->search . "%')";
		}
		$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
		$sWhere .= " (antrian.status=2 AND DATE(antrian.create_at)=CURDATE())";

		$sLimit = $output->limit;
		$sGroup = "GROUP BY id_antrian";
		$dateNow = date('Y-m-d');
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . "  FROM tbl_antrian AS antrian  LEFT JOIN tbl_pendaftaran AS pendaftaran ON antrian.id_antrian = pendaftaran.fk_antrian LEFT JOIN tbl_pasien AS pasien ON pendaftaran.fk_pasien = pasien.id_pasien LEFT JOIN tbpoli as poliklinik ON pendaftaran.poli = poliklinik.idPoli LEFT JOIN tbl_resep_detail AS resep ON resep.fk_pendaftaran=pendaftaran.id_pendaftaran LEFT JOIN tbasuransi AS asuransi ON pasien.asuransi_utama = asuransi.idAsuransi $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();

		$map_data = array_map(function ($dt) {
			$id_antrian = $dt->id_antrian;
			$link = "";
			if (isAllowed('c-t-antrianperiksa^update', true))
				$link .= '<a href="#" class="link-edit-pemeriksaan" data-id="' . $dt->id_antrian . '"><i class="fa fa-edit"></i></a>  &nbsp;';
			return [
				$dt->id_antrian,
				'' . ($dt->status_resep == 0  ? "<a href='#' class='btn btn-info btn-xs link-history' data-id='" . $dt->id_pasien . "'>History RM</a>
				<a href='#' class='btn btn-success btn-xs link-resep' data-id='" . $dt->id_pendaftaran . "'>Resep</a> " : "<a href='#' class='btn btn-info btn-xs link-history' data-id='" . $dt->id_pasien . "'>History RM</a>") . '',
				$dt->no_invoice,
				$dt->nomor_antrian,
				$dt->namaPoli,
				$dt->namaAsuransi,
				$dt->nomor_rm,
				$dt->nama_lengkap,
				$dt->alamat,
				'' . $link . '' . ($dt->status_rawat == 0  ? "<a href='#' class='link-pindah' data-id='" . $dt->id_pendaftaran . "'><i class='fa fa-forward text-danger'></i></a>" : "Dipindah") . '',
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function _load_dt_obat($posted)
	{
		$orders_cols = ["idObat", "kode", "nama", "namaKategoriobat", "namaSatuanbeli", "namaSupplier", "stok", "stokmin", "expired"];
		$output = build_filter_table($posted, $orders_cols, [], "tbl_obat.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " nama LIKE '%" . $output->search . "%'";
		}
		$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
		$sWhere .= " tbl_obat_detail.status = 1 ";

		$sLimit = $output->limit;
		$sGroup = "GROUP BY idObat";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . ", GROUP_CONCAT(format(tbl_obat_detail.harga,0) ORDER BY tbl_obat_detail.obat_detail_id ASC SEPARATOR '<br>') as harga,
		GROUP_CONCAT(format(tbl_obat_detail.hargabeli,0) ORDER BY tbl_obat_detail.obat_detail_id ASC SEPARATOR '<br>') as hargabeli,
		GROUP_CONCAT(tbl_satuan_obat.namaSatuanobat ORDER BY tbl_obat_detail.obat_detail_id ASC SEPARATOR '<br>') as namaSatuan 
		FROM tbl_obat 
		left join tbl_obat_detail on tbl_obat_detail.fk_obat=tbl_obat.idObat 
		left join tbl_kategori_obat on tbl_kategori_obat.idKategoriobat=tbl_obat.kategori 
		left join tbl_satuan_beli on tbl_satuan_beli.idSatuanbeli=tbl_obat.satuanbeli 
		left join tbl_satuan_obat on tbl_satuan_obat.idSatuanobat=tbl_obat_detail.satuan 
		left join tbsupplier on tbsupplier.idSupplier=tbl_obat.supplier $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$idObat = $dt->idObat;
			$link = '<a href="#" class="btn btn-primary btn-xs link-tambah-daftar-obat" data-dismiss="modal" data-id="' . $dt->idObat . '"><i class="fa fa-plus"></i></a>';
			if ((int) $dt->stok == 0) $link = "Stok Kosong";
			return [
				$dt->kode,
				$dt->nama,
				$dt->namaKategoriobat,
				$dt->namaSatuan,
				$dt->hargabeli,
				$dt->harga,
				'' . $dt->stok . " @" . $dt->namaSatuanbeli . '',
				$dt->namaSupplier,

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
		$this->db->select('pendaftaran.id_pendaftaran ,pendaftaran.no_invoice,pendaftaran.create_at,pasien.nomor_rm,pasien.nama_lengkap,dokter.namaDokter');
		$this->db->from($this->tablePendaftaran . " pendaftaran");
		$this->db->join('tbl_pasien pasien', 'pendaftaran.fk_pasien=pasien.id_pasien', 'RIGHT');
		$this->db->join('tbdaftardokter dokter', 'pendaftaran.dpjp=dokter.idDokter', 'RIGHT');
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _search_pemeriksaan($where)
	{
		$data = $this->db->query('select tbl_antrian.id_antrian as _id_antrian,pen.id_pendaftaran as _id,pasien.nama_lengkap,pasien.nomor_rm,poliklinik.namaPoli,dokter.namaDokter,anamnesa,pem.id_pemeriksaan,pemeriksaan_umum,alergi,sistole,diastole,tensi,derajat_nadi,nafas,suhu_tubuh,saturasi,bb,tb,catatan_dokter,nyeri,kesadaran
		FROM tbl_antrian
		JOIN tbl_pendaftaran pen on pen.fk_antrian=tbl_antrian.id_antrian
		LEFT JOIN tbl_pemeriksaan pem on pem.id_pendaftaran=pen.id_pendaftaran
		LEFT JOIN tbl_pemeriksaan_diagnosa p_diag ON pem.id_pemeriksaan=p_diag.fk_pemeriksaan
        LEFT JOIN tbdiagnosis diag ON p_diag.fk_diagnosa=diag.idDiagnosis
        LEFT JOIN tbl_pemeriksaan_tindakan p_tind ON pem.id_pemeriksaan=p_tind.fk_pemeriksaan
		LEFT JOIN tbl_layanan_poli layanan_poli ON p_tind.fk_tindakan=layanan_poli.id_layanan_poli
        LEFT JOIN tbl_pasien as pasien on pen.fk_pasien=pasien.id_pasien
        LEFT JOIN tbpoli as poliklinik on pen.poli=poliklinik.idPoli
		LEFT JOIN tbdaftardokter as dokter on pen.dpjp=dokter.idDokter
		WHERE id_antrian="' . $where . '" GROUP BY pem.id_pemeriksaan ORDER BY pen.id_pendaftaran DESC')->result_object();
		return $data;
	}
	function _update($id_pemeriksaan, $data_pemeriksaan)
	{
		$this->db->update('tbl_pemeriksaan', $data_pemeriksaan, "id_pemeriksaan = '$id_pemeriksaan'");
	}
	function _save_diagnosa($data)
	{
		$this->db->insert_batch('tbl_pemeriksaan_diagnosa', $data);
	}
	function _del_edited_diagnosa($id_pemeriksaan)
	{
		$this->db->delete('tbl_pemeriksaan_diagnosa', array('fk_pemeriksaan' => $id_pemeriksaan));
	}
	function _save_tindakan($data)
	{
		$this->db->insert_batch('tbl_pemeriksaan_tindakan', $data);
	}
	function _del_edited_tindakan($id_pemeriksaan)
	{
		$this->db->delete('tbl_pemeriksaan_tindakan', array('fk_pemeriksaan' => $id_pemeriksaan));
	}
	function _search_diagnosa_pemeriksaan($id_pemeriksaan)
	{
		$this->db->select("fk_diagnosa as id_diagnosa,namaDiagnosis as nama_diagnosis");
		$this->db->from($this->tablePemeriksaanDiagnosa . " diagper");
		$this->db->join($this->tableDiagnosis . " diag", "diagper.fk_diagnosa=diag.idDiagnosis");
		$this->db->where(array("fk_pemeriksaan" => $id_pemeriksaan));
		return $this->db->get()->result_object();
	}
	function _search_diagnosa_pemeriksaan_select2($id_pemeriksaan)
	{
		$this->db->select("fk_diagnosa as id,namaDiagnosis as text");
		$this->db->from($this->tablePemeriksaanDiagnosa . " diagper");
		$this->db->join($this->tableDiagnosis . " diag", "diagper.fk_diagnosa=diag.idDiagnosis");
		$this->db->where(array("fk_pemeriksaan" => $id_pemeriksaan));
		return $this->db->get()->result_array();
	}
	function _search_tindakan_pemeriksaan_select2($id_pemeriksaan)
	{
		$this->db->select("fk_tindakan as id,nama_layanan_poli as text");
		$this->db->from($this->tablePemeriksaanTindakan . " tinper");
		$this->db->join($this->tableTindakan . " tindakan", "tinper.fk_tindakan=tindakan.id_layanan_poli");
		$this->db->where(array("fk_pemeriksaan" => $id_pemeriksaan));
		return $this->db->get()->result_array();
	}
	function _num_rows($clinic_id, $periode)
	{
		$this->db->where("tbl_antrian.clinic_id", $clinic_id);
		$this->db->where('status', 2);
		$this->db->like("create_at", $periode, 'after');
		return $this->db->get('tbl_antrian')->num_rows();
	}
	function _search_select_aturan_pakai($key = "", $clinic_id)
	{
		$this->db->select('id ,nama_aturan_pakai as text');
		$this->db->from($this->tableAturanPakai);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('nama_aturan_pakai', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _save_aturan_pakai($data, $where, $key, $clinic_id)
	{
		$this->db->select($key)->from($this->tableAturanPakai)->where($key, $data[$key]);
		$this->db->where($clinic_id, $data[$clinic_id]);
		$check = $this->db->get()->result();
		if (!empty($check)) return 'exist';
		$this->db->insert($this->tableAturanPakai, $data);
		return $this->db->affected_rows();
	}

	function _search_select_cara_pakai($key = "", $clinic_id)
	{
		$this->db->select('id ,nama_cara_pakai as text');
		$this->db->from($this->tableCaraPakai);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('nama_cara_pakai', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _save_cara_pakai($data, $where, $key, $clinic_id)
	{
		$this->db->select($key)->from($this->tableCaraPakai)->where($key, $data[$key]);
		$this->db->where($clinic_id, $data[$clinic_id]);
		$check = $this->db->get()->result();
		if (!empty($check)) return 'exist';
		$this->db->insert($this->tableCaraPakai, $data);
		return $this->db->affected_rows();
	}
	function check_stock_obat($id_obat)
	{
		$this->db->select('idObat,stok')->from($this->tableObat);
		$this->db->where('idObat', $id_obat);
		return $this->db->get()->result_object();
	}
	function insert_temp($data)
	{
		$this->db->insert('tbl_resep_detail', $data);
		return $this->db->affected_rows();
	}

	function tampilkan_temp($clinic_id)
	{
		$query = "SELECT o.kode,o.nama,s.namaSatuanobat,td.*,aturan.nama_aturan_pakai,cara.nama_cara_pakai
                FROM tbl_resep_detail as td,tbl_obat as o, tbl_satuan_obat as s,tbl_resep_aturan_pakai as aturan,tbl_resep_cara_pakai as cara,tbl_obat_detail as de
         WHERE o.idObat=td.fk_obat and td.satuan=de.obat_detail_id and de.satuan=s.idSatuanobat and aturan.id=td.aturan_pakai and cara.id=td.cara_pakai and td.status=0 and td.clinic_id='$clinic_id'";
		return $this->db->query($query);
	}
	function hapus_temp($id)
	{
		$this->db->where('resep_detail_id', $id);
		$this->db->delete('tbl_resep_detail');
		return $this->db->affected_rows();
	}
	function ubah_status($id)
	{
		$this->db->query("UPDATE tbl_resep_detail SET fk_pendaftaran='$id' ,status='1' where fk_pendaftaran=0 ");
	}
	function _pindah_ranap($id_pendaftaran)
	{
		$this->db->query("UPDATE tbl_pendaftaran SET status_rawat=4 where id_pendaftaran='$id_pendaftaran'");
		return $this->db->affected_rows();
	}
}
