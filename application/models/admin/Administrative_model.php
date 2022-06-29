<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Administrative_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->TableUser = "c_users";
		$this->TableClinic = "c_registered_clinics";
		$this->TableUserPasien = "c_userpasien";
		$this->TableBaseMenu = "c_base_menu";
		$this->TableMenu = "c_menus";
	}
	function dt_users($posted)
	{
		// $this->datatables->select('uid,name,uname as username,email,enabled,IF(level="c-spadmin","Super Admin","User") as level',false);
		// $this->datatables->from($this->TableUser." user");
		// $this->datatables->where("uname!=",conf("super_admin_id"));
		// $this->datatables->add_column('edit', '<a href="#" class="link-edit-user" data-id="$1"><i class="fa fa-edit"></i></a>', 'uid');
		// return $this->datatables->generate();
		$orders_cols = ["uid", "clinic.clinic_name", "name", "uname", "user.email", "enabled", "level", "created_by", "uid"];
		$output = build_filter_table($posted, $orders_cols);
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (name LIKE '%" . $output->search . "%' OR email LIKE '%" . $output->search . "%')";
		}
		$sWhere.=($sWhere=="") ? " WHERE ": " AND ";
		$sWhere.="uname!='".conf("super_admin_id")."'";
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . ",IFNULL(clinic.rc_id,'allclinic') as rc,IFNULL(clinic.clinic_name,'SEMUA KLINIK') as nama_klinik FROM " . $this->TableUser . " user LEFT JOIN " . $this->TableClinic . " clinic ON user.clinic_id=clinic.rc_id $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$id = $dt->uid;
			return [
				$dt->uid,
				$dt->nama_klinik,
				$dt->name,
				$dt->uname,
				$dt->email,
				$dt->enabled,
				$dt->level,
				'<a href="#" class="link-edit-user" data-id="' . $dt->uid . '"><i class="fa fa-edit"></i></a>  &nbsp;
					'
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function dt_akun_pasien($where)
	{
		$this->datatables->select('user.id,user.name,user.email,user.hp,user.createdAt,CONCAT(pasien.nomor_rm," (",pasien.nama_lengkap,")"),user.enabled', false);
		$this->datatables->from($this->TableUserPasien . " user");
		$this->datatables->join('tbl_pasien pasien', 'user.id=pasien.akun_id_pasien AND pasien.reg_as="sendiri"', 'left');
		$this->datatables->add_column('edit', '<a href="#" class="link-reset-password btn btn-xs btn-primary" data-id="$1"><i class="fa fa-unlock-alt"></i> Reset Password</a>', 'uid');
		return $this->datatables->generate();
	}
	function search_user($where)
	{
		$this->db->select('uid as user_id,name,uname as username,clinic_id,email,accessibility_base,actions_code_base,accessibility,actions_code,level');
		$this->db->from($this->TableUser . " user");
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function search_enabled_menus($clinic_id)
	{
		$this->db->select('enabled_menus');
		$this->db->from('c_registered_clinics');
		$this->db->where('rc_id',$clinic_id);
		return $this->db->get()->result_object();
	}
	function save_user($data, $where)
	{
		if (empty($where)) {
			// check before insert
			$this->db->select('uid')->from($this->TableUser)->where("uname", $data['uname']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->TableUser, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->select('uid')->from($this->TableUser);
			$this->db->where("uname", $data['uname']);
			$this->db->where("uid!=", $where['uid']);
			$check = $this->db->get()->result();

			if (!empty($check)) {
				return 'exist';
			}
			$this->db->update($this->TableUser, $data, $where);
			return $this->db->affected_rows();
		}
	}
	function enable_disable_user($data, $where)
	{
		$this->db->update($this->TableUser, $data, $where);
		return $this->db->affected_rows();
	}
	function load_base_menu($enabled_menus=[])
	{
		$this->db->select("id,title,icon,access_code,actions_code,has_child");
		$this->db->from($this->TableBaseMenu);
		if(sizeof($enabled_menus)>0){
			$this->db->where_in('id',$enabled_menus);
		}
		$this->db->order_by("order_no");
		return $this->db->get()->result();
	}
	function load_menus()
	{
		$this->db->select("base_id,title,access_code,actions_code");
		$this->db->from($this->TableMenu);
		$this->db->where(array("enabled" => 1));
		$this->db->order_by("order_no");
		return $this->db->get()->result();
	}
	function save_backup_db($data)
	{
		$this->db->insert('c_backup_list', $data);
		return $this->db->insert_id();
	}
	function delete_old_backup_db()
	{
		$this->db->query('DELETE FROM c_backup_list WHERE created_at < NOW() - INTERVAL 2 DAY');
		return $this->db->affected_rows();
	}
}

/* End of file Administrative_model.php */
/* Location: ./application/models/Administrative_model.php */
