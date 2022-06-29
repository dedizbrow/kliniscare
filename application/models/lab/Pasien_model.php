<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pasien_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablePasien = "tbl_pasien";
		$this->tableProvider = "lab_data_provider";
		$this->tableJenisPemeriksaan = "lab_jenis_pemeriksaan";
		$this->load->helper('ctc');
	}
	function _load_dt($posted, $provider_id = "", $ori_provider = "")
	{
		$orders_cols = ["provider.nama", "pasien.id_pasien", "nama_lengkap", "no_identitas", "kewarganegaraan", "jenis_kelamin", "tempat_lahir", "jenis_kelamin", "pasien.alamat", "no_hp", "email", "pasien.create_at", "pasien.id_pasien"];
		$output = build_filter_table($posted, $orders_cols, ["nomor_rm"],"pasien.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere = "") ? " WHERE " : " AND ";
			$sWhere .= " (nama_lengkap LIKE '%" . $output->search . "%' OR no_identitas LIKE '%" . $output->search . "%')";
		}
		if ($provider_id != '' && $provider_id != 'pusat') {
			$sWhere .= ($sWhere = "") ? " WHERE " : " AND ";
			$sWhere .= " provider_id='$provider_id'";
		}
		if (isset($posted['filter_provider']) && $posted['filter_provider'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " provider_id='" . htmlentities($posted['filter_provider']) . "'";
		}
		if (isset($posted['import_id']) && $posted['import_id'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " import_id='" . htmlentities($posted['import_id']) . "'";
		}
		$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
		$sWhere .= " deleted='0' AND clinic_id='".htmlentities($posted['clinic_id'])."' ";
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		if ($posted['order']) {
			$ord = $posted['order'][0];
			$col = $ord['column'];
			$dir = $ord['dir'];
			if (isset($order_cols[(int) $col]) && $order_cols[(int) $col] == "nomor_rm") {
				$sOrder = " ORDER BY LENGTH(" . $order_cols[(int) $col] . ") $dir," . $order_cols[(int) $col] . " " . $dir;
			}
		}

		$limit = 0;
		$offset = 25;
		$ref_data = ["provider_id" => $provider_id, "ori_provider" => $ori_provider, "PV_GROUP" => $posted['PV_GROUP']];
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . ", nomor_rm,tgl_lahir,pasien.creator_id,pasien.createdAt,provider.id as provider_id,provider.nama as nama_provider,pasien.verified,pasien.self_register FROM tbl_pasien pasien INNER JOIN lab_data_provider provider ON pasien.provider_id=provider.id $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) use ($ref_data) {
			$id = $dt->id_pasien;
			$link_delete = '<a href="#" class="link-delete-pasien" data-id="' . $dt->id_pasien . '"><i class="fa fa-trash text-danger"></i></a>';
			$created = $dt->create_at;
			if ($dt->self_register == 1 && $dt->verified == 0) {
				if (isset($ref_data['ori_provider']) && ($ref_data['ori_provider'] == $dt->provider_id || $ref_data['PV_GROUP'] == 'pusat')) {
					$created = '<span class="accept-registrasi btn btn-xs btn-purple" data-id="' . $dt->id_pasien . '" title="Registrasi baru, Click untuk menerima sebagai pasien">Menunggu verifikasi</span>';
				} else {
					$created = '<span class=" btn btn-xs btn-warning" data-id="' . $dt->id_pasien . '" title="Registrasi baru, Menunggu verifikasi di provider">Menunggu verifikasi provider</span>';
				}
			}
			$nomor_rm=(strpos($dt->nomor_rm,"RM-")===false) ? "RM-".$dt->nomor_rm : $dt->nomor_rm;
			return [
				$dt->nama_provider,
				$nomor_rm,
				$dt->nama_lengkap,
				$dt->no_identitas,
				$dt->kewarganegaraan,
				ucwords($dt->tempat_lahir) . " /" . $dt->tgl_lahir,
				ucwords($dt->jenis_kelamin),
				$dt->alamat,
				$dt->no_hp,
				$dt->email,
				$created,
				'<a href="#" class="link-edit-pasien" data-id="' . $dt->id_pasien . '"><i class="fa fa-edit"></i></a>  &nbsp;' . $link_delete,
				$dt->self_register
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}

	function get_new_no_pasien($clinic_id)
	{
			$query=$this->db->query('SELECT IFNULL(CAST(REPLACE(nomor_rm,"RM-","") as UNSIGNED),0)+1 as new_no FROM tbl_pasien WHERE clinic_id="'.$clinic_id.'" ORDER BY LENGTH(IFNULL(CAST(REPLACE(nomor_rm,"RM-","") as UNSIGNED),0)) DESC, IFNULL(CAST(REPLACE(nomor_rm,"RM-","") as UNSIGNED),0) DESC LIMIT 1');
		// $this->db->select('IFNULL(CAST(REPLACE(nomor_rm,"RM-","") as UNSIGNED),0)+1 as new_no', false);
		// $this->db->where("clinic_id", $clinic_id);
		// // $this->db->order_by('LENGTH(IFNULL(CAST(REPLACE(nomor_rm,"RM-","") as UNSIGNED),0))', 'DESC');
		// $this->db->order_by('IFNULL(CAST(REPLACE(nomor_rm,"RM-","") as UNSIGNED),0)', 'DESC');
		// $this->db->limit(1);
		// $dta = $this->db->get('tbl_pasien')->row();
		$dta=$query->row();
		$no = 1;
		if ($dta != null) $no = $dta->new_no;
		$no = str_pad($no, 5, '0', STR_PAD_LEFT);
		return "RM-$no";
	}
	function _search($id)
	{
		$data = $this->db->query('select a.id_pasien as _id,a.no_identitas,a.kewarganegaraan,a.nama_lengkap,a.nomor_rm,nomor_rm as id_pasien,a.jenis_kelamin,a.tempat_lahir,a.tgl_lahir,a.alamat,a.no_hp,a.email,a.provider_id,b.nama as provider,IFNULL(a.reg_pemeriksaan,"") as reg_pemeriksaan,IFNULL(c.jenis,"") as jenis_pemeriksaan,
		IFNULL(a.asuransi_utama,"") as asuransi,IFNULL(a.no_asuransi,"") as no_asuransi,IFNULL(daftar.perujuk,"") as perujuk, IFNULL(daftar.nama_tenaga_perujuk,"") as nama_tenaga_perujuk
		from tbl_pasien a INNER JOIN lab_data_provider b ON a.provider_id=b.id
		LEFT JOIN lab_jenis_pemeriksaan c ON a.reg_pemeriksaan=c.id
		LEFT JOIN tbl_pendaftaran daftar ON a.id_pasien=daftar.fk_pasien
		WHERE a.id_pasien=' . $id . ' GROUP BY a.id_pasien
		')->result();
		return $data;
	}
	function _searchid_by_nik($no_identitas)
	{
		$this->db->select('id_pasien,no_identitas,akun_id_pasien')->from($this->tablePasien);
		$this->db->where('no_identitas', $no_identitas);
		return $this->db->get()->result_object();
	}
	function _search_select2($key = "", $provider = '',$clinic_id)
	{
		$this->db->select('id_pasien as id ,CONCAT(no_identitas," | ",nama_lengkap," | ",tgl_lahir) as text');
		$this->db->from($this->tablePasien);
		$this->db->where(array("clinic_id"=>$clinic_id));
		// if($provider!='') $this->db->where("provider_id",$provider);
		if ($key != "") {
			$this->db->where('(no_identitas like "%' . $key . '%" OR nama_lengkap LIKE "%' . $key . '%")');
		}
		$this->db->limit(100);
		return $this->db->get()->result_array();
	}
	function _save($data, $where)
	{
		if (empty($where)) {
			// check before insert
			$this->db->select('no_identitas')->from($this->tablePasien)->where(array("no_identitas"=> $data['no_identitas'],"clinic_id"=>$data['clinic_id']));
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->tablePasien, $data);
			return $this->db->insert_id();
		} else {
			$this->db->select('id_pasien')->from($this->tablePasien);
			$this->db->where("no_identitas", $data['no_identitas']);
			$this->db->where("id_pasien!=", $where['id_pasien']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->update($this->tablePasien, $data, $where);
			return $this->db->affected_rows();
		}
	}
	function _delete($id, $user)
	{
		$rtime = rand(1000, 9999);
		$this->db->query('UPDATE tbl_pasien SET deleted=1,deleted_at=CURDATE(),deleted_by="' . $user . '",no_identitas=CONCAT(no_identitas,"!!' . $rtime . '") WHERE id_pasien="' . $id . '"');
		return $this->db->affected_rows();
	}
	function _confirm_register($data, $where)
	{
		$this->db->update($this->tablePasien, $data, $where);
		return $this->db->affected_rows();
	}
}

/* End of file Pasien_model.php */
