<?php
if (!function_exists('apache_request_headers')) {
	function apache_request_headers()
	{
		$arh = array();
		$rx_http = '/\AHTTP_/';
		foreach ($_SERVER as $key => $val) {
			if (preg_match($rx_http, $key)) {
				$arh_key = preg_replace($rx_http, '', $key);
				$rx_matches = array();
				// do some nasty string manipulations to restore the original letter case
				// this should work in most cases
				$rx_matches = explode('_', $arh_key);
				if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
					foreach ($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
					$arh_key = implode('-', $rx_matches);
				}
				$arh[$arh_key] = $val;
			}
		}
		return ($arh);
	}
}
function romawi_bulan($bulan)
{
	$array_bln = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
	return $array_bln[$bulan];
}
function count_age($born_date)
{
	return date_diff(date_create($born_date), date_create('now'))->y;
}
function get_template($template_dir = '')
{
	$ci = &get_instance();
	$ci->load->library('session');
	$app_code = $ci->config->item('app_code');
	if ($ci->session->userdata($app_code . "CTC-TPL") && $template_dir == '') {
		return "templates/" . $ci->session->userdata($app_code . "CTC-TPL") . "/template";
	} else
		if ($template_dir != '') {
		return "templates/" . $template_dir . "/template";
	} else {
		
		return "templates/".conf('ctc_default_template')."/template";
	}
}
function generateToken($data)
{
	if (gettype($data) != 'array' && gettype($data) != 'object') {
		sendError(lang('msg_invalid_token'));
	} else {
		$ci = &get_instance();
		$ci->load->library('session');
		$app_code = $ci->config->item('app_code');
		if (isset($data->template) && $data->template != "" && conf('enable_templating')!=FALSE)
			$ci->session->set_userdata($app_code . "CTC-TPL", $data->template);
		if (isset($data->lang) && $data->lang != "" && file_exists(APPPATH . "language/" . $data->lang . "/ctcapp_lang.php"))
			$ci->session->set_userdata($app_code . 'site_lang', $data->lang);
		$encoded = base64_encode(json_encode($data));
		$ci->session->set_userdata($app_code . 'CTC-X-KEY', $encoded);
		$ci->session->set_userdata($app_code . 'CTC-CL-ID', $data->clinic_id);
		$ci->session->set_userdata($app_code . 'CTC-CL-NAME', $data->clinic_name);
		if ($ci->input->get('redirect')){ 
			$cid=base64_encode($data->clinic_id);
			$add=(strpos($ci->input->get('redirect'),'?')!==false) ? "&cid=".$cid : "?cid=".$cid;
			redirect($ci->input->get('redirect').$add);
		}
		if (isset($data->last_page) && $data->last_page != "") {
			redirect($data->last_page);
		} else {
			redirect('admin/home');
		}
	}
}
function generateMenu($base_menu, $menus, $user_data)
{
	$ci = &get_instance();
	$app_code = $ci->config->item('app_code');
	if (gettype($base_menu) != 'object' && gettype($base_menu) != 'array') {
		die('Error in generating menu! It needs array type');
	}
	$new_menus = array();
	$actions_code = array();
	if (!empty($base_menu)) {
		foreach ($base_menu as $key => $arr) {
			// echo $arr->access_code."<br>";
			if(strpos($arr->access_code,"ctc::")!==false && $user_data->usrname!=conf('super_admin_id')){
			// echo "found as super.<br>";
				// for access code started with ctc:: will be shown only for super_admin ctc
			}else{
				array_push($actions_code, $arr->access_code);
				$excode = explode(",", $arr->actions_code);
				if (!empty($excode)) {
					foreach ($excode as $code) {
						if ($code != "") array_push($actions_code, $arr->access_code . "^" . $code);
					}
				}
				if ($arr->has_child == 1) {
					if (!empty($menus)) {
						$arr_menu = array(
							"label" => $arr->title,
							"url" => $arr->end_point,
							"icon" => $arr->icon,
							"sub_menu" => array()
						);
						foreach ($menus as $k => $menu) {
							if ($menu->base_id == $arr->base_id) {
								// echo "Menus : ".$menu->access_code."<br>";
								if(strpos($menu->access_code,"ctc::")!==false && $user_data->usrname!=conf('super_admin_id')){
									// for access code started with ctc:: will be shown only for super_admin ctc
									// echo "found as super.<br>";
								}else{
									array_push($arr_menu['sub_menu'], array(
										"label" => $menu->title,
										"url" => $menu->end_point
									));
									//if($menu->actions_code!="") array_merge($actions_code,explode(",",$arr->actions_code));
									array_push($actions_code, $menu->access_code);
									$excode = explode(",", $menu->actions_code);
									if (!empty($excode)) {
										foreach ($excode as $code) {
											if ($code != "") array_push($actions_code, $menu->access_code . "^" . $code);
										}
									}
								}
							}
						}
						
					}
				} else {
					$arr_menu = array(
						"label" => $arr->title,
						"url" => $arr->end_point,
						"icon" => $arr->icon,
						"sub_menu" => array()
					);
				}
				array_push($new_menus, $arr_menu);
			}
		}
	}
	$exp_user_actions_code = explode(",", $user_data->actions_code);
	$ci->session->set_userdata($app_code . "CTC-MENUS", $new_menus);
	if ($user_data->level == $ci->config->item('super_admin_code')) {
		$ci->session->set_userdata($app_code . "CTC-ACT-CODE", json_encode(array_unique($actions_code)));
	} else {
	// echo "<pre>";
	// echo "all available access code<br>";
	// print_r(array_unique($actions_code));
	// echo "accessibilty user<br>";
	// print_r(array_merge(explode(",", $user_data->accessibility), explode(",", $user_data->actions_code)));
	// echo "intersect access<br>";
	// print_r(array_intersect(array_unique($actions_code), array_merge(explode(",", $user_data->accessibility), explode(",", $user_data->actions_code),explode(",", $user_data->accessibility_base),explode(",", $user_data->actions_code_base))));
	
	// echo "</pre>";
	// die();

		$ci->session->set_userdata($app_code . "CTC-ACT-CODE", json_encode(array_intersect(array_unique($actions_code), array_merge(explode(",", $user_data->accessibility), explode(",", $user_data->actions_code),explode(",", $user_data->accessibility_base),explode(",", $user_data->actions_code_base)))));
		//$ci->session->set_userdata("CTC-ACT-CODE",json_encode(array_unique($actions_code)));
	}
}
function clearToken($redirect='')
{
	$ci = &get_instance();
	$ci->load->library('session');
	$app_code = $ci->config->item('app_code');
	$session_list = array($app_code . 'CTC-TPL', $app_code . 'CTC-X-KEY', $app_code . 'CTC-MENUS', $app_code . 'CTC-ACT-CODE');
	$ci->session->unset_userdata($session_list);
	$ci->session->sess_destroy();
	
	if($redirect!=''){ 
		redirect('admin/auth/signin?redirect='.$redirect);
	}else{
		redirect('admin/auth/signin');
	}
}

function lang($key)
{
	$ci = &get_instance();
	return $ci->lang->line($key);
}
function conf($key)
{
	$ci = &get_instance();
	return $ci->config->item($key);
}
function create_order_id($last_order, $type = '')
{
	$ci = &get_instance();
	$length = $ci->config->item('order_id_length');
	if ($type == "") {
		if ($last_order == null || $last_order == "") {
			$no = 1;
			$yr = date("y");
		} else {
			$split = explode("-", $last_order);
			$no = (int) $split[1];
			$no++;
			$yr = $split[0];
		}
		if (strlen($no) < $length) {
			$rep = str_repeat(0, $length - strlen($no));
			$no = $yr . "-" . $rep . $no;
		}
	} else {
		$no = $last_order;
		if (strlen($no) < $length) {
			$rep = str_repeat(0, $length - strlen($no));
			$no = $rep . $no;
		}
	}
	return $no;
}

function sendError($message, $data = array(), $code = '')
{
	$code = ($code == '') ? 400 : $code;
	http_response_code($code);
	die(json_encode(array('error' => "<strong>Error! </strong> $message", "data" => $data)));
}
function sendSuccess($message, $data = array(), $code = '')
{
	$code = ($code == '') ? 200 : $code;
	http_response_code($code);
	die(json_encode(array('message' => "<strong>Success! </strong> $message", "data" => $data)));
}
function sendJSON($array_data)
{
	die(json_encode($array_data));
}
function requiredMethod($method)
{
	$ci = &get_instance();
	$meth = $ci->input->method(true);
	if (strtoupper($method) != $meth) {
		sendError(lang('msg_method_invalid'), [], 405);
	}
}
function remove_prefix($text, $prefix)
{
	if (0 === strpos($text, $prefix))
		$text = substr($text, strlen($prefix)) . '';
	return $text;
}
function isEmailValid($email)
{
	$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
	if (preg_match($regex, $email)) return true;
	return false;
}
function isMatch($first, $second)
{
	if ($first == $second) return true;
	return false;
}
function hashPasswd($password)
{
	return md5(base64_encode($password));
}
function bilangRatusan($x)
{
	$kata = array('', 'Satu ', 'Dua ', 'Tiga ', 'Empat ', 'Lima ', 'Enam ', 'Tujuh ', 'Delapan ', 'Sembilan ');
	$string = '';
	$ratusan = floor($x / 100);
	$x = $x % 100;
	if ($ratusan > 1) $string .= $kata[$ratusan] . "Ratus ";
	else if ($ratusan == 1) $string .= "Seratus ";
	$puluhan = floor($x / 10);
	$x = $x % 10;
	if ($puluhan > 1) {
		$string .= $kata[$puluhan] . "Puluh ";
		$string .= $kata[$x];
	} else if (($puluhan == 1) && ($x > 0)) $string .= $kata[$x] . "Belas ";
	else if (($puluhan == 1) && ($x == 0)) $string .= $kata[$x] . "Sepuluh ";
	else if (($puluhan == 1) && ($x == 1)) $string .= $kata[$x] . "Sebelas ";
	else if ($puluhan == 0) $string .= $kata[$x];
	return $string;
}
function terbilang($x)
{
	$x = number_format($x, 0, "", ".");
	$pecah = explode(".", $x);
	$string = "";
	for ($i = 0; $i <= count($pecah) - 1; $i++) {
		if ((count($pecah) - $i == 5) && ($pecah[$i] != 0)) $string .= bilangRatusan($pecah[$i]) . "Triliyun ";
		else if ((count($pecah) - $i == 4) && ($pecah[$i] != 0)) $string .= bilangRatusan($pecah[$i]) . "Milyar ";
		else if ((count($pecah) - $i == 3) && ($pecah[$i] != 0)) $string .= bilangRatusan($pecah[$i]) . "Juta ";
		else if ((count($pecah) - $i == 2) && ($pecah[$i] == 1)) $string .= "Seribu ";
		else if ((count($pecah) - $i == 2) && ($pecah[$i] != 0)) $string .= bilangRatusan($pecah[$i]) . "Ribu ";
		else if ((count($pecah) - $i == 1) && ($pecah[$i] != 0)) $string .= bilangRatusan($pecah[$i]);
	}
	return $string;
}
function dateIndo($date_format)
{
	$day = date("d", strtotime($date_format));
	$month = date("n", strtotime($date_format));
	$year = date("Y", strtotime($date_format));
	$months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
	return "$day " . $months[$month - 1] . " $year";
}
function createUniqCode($data)
{
	$dte = date("ymd", strtotime($data->tgl_periksa));
	return $dte . "-".$data->clinic_id.".".$data->id_provider . ".". $data->id_pasien_int."-". (int) $data->id;
}
function extractUniqCode($code)
{
	if (preg_match("/^[0-9]{6}-[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,10}-[0-9]{1,10}$/", $code)) {
		$split_date = explode("-", $code);
		// format date-clinic_id.id_provider.idpasien-id_pemeriksaan
		$parseDate = date_parse_from_format("ymd", $split_date[0]);
		$m = $parseDate['month'];
		if ($m < 10) $m = '0' . $m;
		$d = $parseDate['day'];
		if ($d < 10) $d = '0' . $d;
		$date = $parseDate['year'] . "-" . $m . "-" . $d;
		$split2 = explode(".", $split_date[1]);
		$id_pemeriksaan=$split_date[2];
		$clinic_id = $split2[0];
		$provider = $split2[1];
		$pasien = $split2[2];
		$where=array("id_provider" => $provider,"periksa.clinic_id"=>$clinic_id, "periksa.id" => $id_pemeriksaan, "periksa.id_pasien" => $pasien, "tgl_periksa" => $date);
		return $where;
	} else {
		return "invalid";
	}
}
function arrayKeyVal($array,$id='nama',$value='id',$is_lowercase=true){
	$arr=[];
	foreach($array as $d){
		$d=(gettype($d)=='object') ? (array) $d: $d;
		if($is_lowercase){
			$arr[$d[$id]]=strtolower($d[strtolower($value)]);
		}else{
			$arr[$d[$id]]=$d[$value];
		}
	}
	return $arr;
}
function array_group_by($key, $data, $single = false)
{
	$result = array();
	foreach ($data as $val) {
		if (gettype($val) == 'object') $val = (array) $val;
		if (array_key_exists($key, $val)) {
			$result[$val[$key]][] = $val;
		} else {
			$result[""][] = $val;
		}
	}
	if ($single) {
		$result_end = [];
		foreach ($result as $k => $r) {
			$result_end[$k] = (object) $r[0];
		}
		return (object) $result_end;
	} else {
		return $result;
	}
}
/**
 * Group items from an array together by some criteria or value.
 *
 * @param  $arr array The array to group items from
 * @param  $criteria string|callable The key to group by or a function the returns a key to group by.
 * @return array
 *
 */
function groupBy($arr, $criteria): array
{
	return array_reduce($arr, function ($accumulator, $item) use ($criteria) {
		$key = (is_callable($criteria)) ? $criteria($item) : $item[$criteria];
		if (!array_key_exists($key, $accumulator)) {
			$accumulator[$key] = [];
		}

		array_push($accumulator[$key], $item);
		return $accumulator;
	}, []);
}
function format_number($nominal)
{
	return number_format($nominal, 0, ".", ",");
}

function build_filter_table($posted, $order_cols = [], $skipped_orders = [],$clinic_id_for_join_table="")
{
	$sWhere = "";
	$output = [];
	if (isset($posted['sSearch'])) {
		foreach ($posted['sSearch'] as $k => $v) {
			if ($v != "") {
				$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
				$sWhere .= htmlentities($k) . " LIKE '%" . trim(htmlentities($v)) . "%'";
			}
		}
	} else
	if (isset($posted['search'])) {
		if ($posted['search']['value'] != "") {
			$output['search'] = htmlentities(trim($posted['search']['value']));
		}
	}
	if(isset($posted['clinic_id'])){
		$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
		$clinic_id=($clinic_id_for_join_table!="") ? $clinic_id_for_join_table : "clinic_id";
		$sWhere .= "$clinic_id='" . trim(htmlentities($posted['clinic_id']))."'";
	}
	$order = "";
	$limit = 0;
	$offset = 25;
	if (isset($posted['start']) && isset($posted['length'])) {
		$limit = (int) $posted['start'];
		$offset = (int) $posted['length'];
	}
	if (isset($posted['order'])) {
		$ord = $posted['order'][0];
		$col = $ord['column'];
		$dir = $ord['dir'];
		if (isset($order_cols[(int) $col]) && !in_array($order_cols[(int) $col], $skipped_orders)) {
			$order = " ORDER BY " . $order_cols[(int) $col] . " " . $dir;
			if (isset($order_cols[(int) $col]) && ($order_cols[(int) $col] == "pasien.nomor_rm" || $order_cols[(int) $col] == "nomor_rm")) {
				$order = " ORDER BY LENGTH(REPLACE(" . $order_cols[(int) $col] . ",'RM-','')) $dir,REPLACE(" . $order_cols[(int) $col] . ",'RM-','') " . $dir;
			}
		}
	}
	// echo $order;
	$sLimit = " LIMIT $limit,$offset";
	$output['draw'] = (isset($posted['draw'])) ? (int) $posted['draw'] : 1;
	$output['recordsTotal'] = 0;
	$output['recordsFiltered'] = 0;
	$output['data'] = [];
	$output['where'] = $sWhere;
	$output['order'] = $order;
	$output['limit'] = $sLimit;
	return (object) $output;
}
function output_empty_datatable()
{

	$output = [];
	$output['sEcho'] = 0;
	$output['draw'] = 1;
	$output['iTotalRecords'] = 0;
	$output['iTotalDisplayRecords'] = 0;
	$output['data'] = [];
	return $output;
}

function curlApi($url, $method = 'GET', $headers = [], $data = [], $src_host = "")
{
	/* API URL */
	/* Init cURL resource */
	$url_path = (strpos($url, "http") === 0) ? $url : conf('api-url') . $url;
	if (strtoupper($method) == 'GET') {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_path);
	} else {
		$ch = curl_init($url_path);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	}
	/* Array Parameter Data */
	/* pass encoded JSON string to the POST fields */
	/* set the content type json */
	$headers = $headers;
	$headers['content-type'] = "application/json";
	$headers['c-appkey'] = conf('api-appkey');
	$nheaders = array();
	foreach ($headers as $k => $v) {
		array_push($nheaders, "$k:$v");
	}
	curl_setopt($ch, CURLOPT_HTTPHEADER, $nheaders);
	/* set return type json */
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	/* execute request */
	$result = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if (curl_errno($ch)) {
		$error = curl_error($ch);
		if (strpos(strtolower($error), 'connection refused')) $error = "Service unavailable. please try again later or contact us";
		curl_close($ch);
		die($error);
	} else
		if ($httpcode != 200) {
		curl_close($ch);
		http_response_code($httpcode);
		return json_decode($result, true);
	} else {
		curl_close($ch);
		return (object) json_decode($result, true);
	}
}
function check_stok($obat)
{
	$CI = &get_instance();
	$query_beli = "SELECT SUM(qty) as jumlah_beli FROM tbl_transaksi_beli_detail WHERE obat='$obat'";
	$query_jual = "SELECT SUM(qty) as jumlah_jual FROM tbl_transaksi_jual_detail WHERE obat='$obat'";
	$beli = $CI->db->query($query_beli)->row_array();
	$jual = $CI->db->query($query_jual)->row_array();
	return $beli['jumlah_beli'] - $jual['jumlah_jual'];
}

function biaya_pemeriksaan($id)
{
	$CI = &get_instance();
	$query = "SELECT sum(layanan.harga_layanan_poli) as total_bayar from tbl_pendaftaran as pend join tbl_pemeriksaan as periksa on pend.id_pendaftaran=periksa.id_pendaftaran join tbl_pemeriksaan_tindakan as tindakan on periksa.id_pemeriksaan=tindakan.fk_pemeriksaan join tbl_layanan_poli as layanan on layanan.id_layanan_poli=tindakan.fk_tindakan WHERE periksa.id_pendaftaran='$id'";
	$total_bayar = $CI->db->query($query)->row_array();
	return $total_bayar['total_bayar'];
}
function biaya_kamar($id)
{
	$CI = &get_instance();
	$query_lama_inap = "SELECT DATEDIFF(checkout_at,checkin_at) as lama_inap from tbruangan_transaksi where fk_pendaftaran='$id'";
	$lama_inap = $CI->db->query($query_lama_inap)->row_array();
	$lama_inap = ($lama_inap==null) ? 0 : $lama_inap['lama_inap'] + 1;
	$query_kamar = "SELECT tarif from tbruangan_transaksi join tbruangan ON tbruangan.idRuangan=tbruangan_transaksi.fk_ruangan where fk_pendaftaran='$id'";
	$total = $CI->db->query($query_kamar)->row_array();
	$total = ($total!=null) ? $total['tarif'] : 0;
	return $total * $lama_inap;
}
function biaya_resep($id)
{
	$CI = &get_instance();
	$query = "SELECT sum(resep.total) as total_biaya_resep from tbl_pendaftaran as daftar join tbl_resep_detail as resep on resep.fk_pendaftaran=daftar.id_pendaftaran WHERE daftar.id_pendaftaran='$id'";
	$total_biaya_resep = $CI->db->query($query)->row_array();
	return $total_biaya_resep['total_biaya_resep'];
}
function total_biaya($id)
{
	return biaya_pemeriksaan($id) + biaya_resep($id) + biaya_kamar($id);
}
function total_dibayar_pemeriksaan($id)
{
	$CI = &get_instance();
	$query = "SELECT sum(total_dibayar.biaya) as total_bayar from tbl_bayar_periksa as total_dibayar WHERE fk_pendaftaran='$id'";
	$total_bayar = $CI->db->query($query)->row_array();
	return $total_bayar['total_bayar'];
}
function sisa_pemeriksaan($id)
{
	return total_biaya($id) - total_dibayar_pemeriksaan($id);
}

function terakhir_dibayar_pemeriksaan($id)
{
	$CI = &get_instance();
	$query = "SELECT total_dibayar.create_at as tanggal_terakhir from tbl_pemeriksaan as periksa join tbl_bayar_periksa as total_dibayar on periksa.id_pendaftaran=total_dibayar.fk_pendaftaran WHERE fk_pendaftaran='$id' ORDER BY id_bayar DESC LIMIT 1;";
	$tanggal_terakhir = $CI->db->query($query)->row_array();
	if (empty($tanggal_terakhir)) return "---";
	return $tanggal_terakhir['tanggal_terakhir'];
}

function total_dibayar_hutang($id)
{
	$CI = &get_instance();
	$query = "SELECT sum(bayar_hutang.biaya) as total_dibayar from tbl_hutang as hutang join tbl_bayar_hutang as bayar_hutang on hutang.hutang_id=bayar_hutang.fk_hutang WHERE fk_hutang='$id'";
	$total_dibayar = $CI->db->query($query)->row_array();
	return $total_dibayar['total_dibayar'];
}

function sisa_hutang($id)
{
	$CI = &get_instance();
	$query = "SELECT grandtotal from tbl_transaksi_beli JOIN tbl_hutang on tbl_transaksi_beli.transaksibeli_id=tbl_hutang.transaksibeli_id  WHERE hutang_id='$id'";
	$total_hutang = $CI->db->query($query)->row_array();
	$sisa = $total_hutang['grandtotal'];
	return $sisa - total_dibayar_hutang($id);
}

function total_dibayar_piutang($id)
{
	$CI = &get_instance();
	$query = "SELECT sum(bayar_piutang.biaya) as total_dibayar from tbl_piutang as piutang join tbl_bayar_piutang as bayar_piutang on piutang.piutang_id=bayar_piutang.fk_piutang WHERE fk_piutang='$id'";
	$total_dibayar = $CI->db->query($query)->row_array();
	return $total_dibayar['total_dibayar'];
}

function sisa_piutang($id)
{
	$CI = &get_instance();
	$query = "SELECT grandtotal from tbl_transaksi_jual JOIN tbl_piutang on tbl_transaksi_jual.transaksijual_id=tbl_piutang.transaksijual_id  WHERE piutang_id='$id'";
	$total_piutang = $CI->db->query($query)->row_array();
	$sisa = $total_piutang['grandtotal'];
	return $sisa - total_dibayar_piutang($id);
}

// added 2021-08-23
function generateTokenLoginPasien($data)
{
	$ci = &get_instance();
	$encoded = base64_encode(json_encode($data));
	$ci->session->set_userdata(conf('app_code') . 'CTC-PSKEY', $encoded);
	return $encoded;
}
function extractTokenLoginPasien()
{
	$ci = &get_instance();
	$ci->load->library('session');
	if ($ci->session->userdata(conf('app_code') . "CTC-PSKEY")) {
		$data = json_decode(base64_decode($ci->session->userdata(conf('app_code') . 'CTC-PSKEY')));
		return $data;
	} else {
		return ["error" => "Invalid token"];
	}
}
// added 2021-08-23
function isAuthorizedPasien()
{
	$ci = &get_instance();
	$app_code = $ci->config->item('app_code');
	if ($ci->session->userdata($app_code . "CTC-PSKEY")) {
		$data = json_decode(base64_decode($ci->session->userdata($app_code . 'CTC-PSKEY')));
		$data = array(
			'app_code' => $app_code,
			'PSID' => $data->id,
			'PSNAME' => $data->name,
			'PSEMAIL' => $data->email
		);
		return $data;
	} else {
		return false;
	}
}
function clearTokenLoginPasien()
{
	$ci = &get_instance();
	$app_code = $ci->config->item('app_code');
	$session_list = array($app_code . "CTC-PSKEY");
	$ci->session->unset_userdata($session_list);
	// $ci->session->sess_destroy();
	redirect('webview/auth');
}
function getClinic(){
	$ci = &get_instance();
	if ($ci->session->userdata(conf('app_code') . "CTC-CL-ID")) {
		$data=[
			"id"=>$ci->session->userdata(conf('app_code') . "CTC-CL-ID"),
			"name"=>$ci->session->userdata(conf('app_code') . "CTC-CL-NAME"),
		];
		return (Object) $data;
	}else{
		return [];
	}
}
function modify_post($posted){
	$ci = &get_instance();
	if(!isset($posted['clinic_id']) || $posted['clinic_id']==null || $posted['clinic_id']=="" || strtolower($posted['clinic_id'])=="default" || $posted['clinic_id']=='undefined'){
		$posted['clinic_id']=$ci->session->userdata(conf('app_code')."CTC-CL-ID");
	}
	return $posted;
}

function generateQRCode($content,$size=2,$qr_path='./files/qrs/',$code='',$with_logo=false){
		$ci = &get_instance(); 
		$ci->load->library('ciqrcode');
		$qr_path=($qr_path==null || $qr_path=='') ? "./files/qrs/": $qr_path;
		if(!file_exists($qr_path)) mkdir($qr_path,0777,true);
		$config['cacheable']    = false; //boolean, the default is true
		$config['cachedir']     = $qr_path; //string, the default is application/cache/
		$config['errorlog']     = $qr_path; //string, the default is application/logs/
		$config['imagedir']     = $qr_path; //direktori penyimpanan qr code
		$config['quality']      = true; //boolean, the default is true
		$config['size']         = 1024; //interger, the default is 1024
		// $config['black']        = array(224,255,255); // array, default is array(255,255,255)
		$config['black']        = array(255,255,255); // array, default is array(255,255,255)
		// $config['white']        = array(70,130,180); // array, default is array(0,0,0)
		$config['white']        = array(0,0,0); // array, default is array(0,0,0)
		$ci->ciqrcode->initialize($config);
		$size=10;
		
		$code=($code=='') ? date("Ymdhis") : $code;
		$name_qr='qr_'.$code;
		$image_name=$name_qr.'.png'; //buat name dari qr code sesuai dengan nip
		$fullpath = $qr_path.$image_name;
		$params['data'] = $content;
		$params['level'] = 'H'; //H=High
		$params['size']=$size;
		$params['savename'] = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/images/
    $ci->ciqrcode->generate($params);
		$QR = imagecreatefrompng($fullpath);
			// memulai menggambar logo dalam file qrcode
		if($with_logo){
			$logopath = $with_logo;
			$logo = imagecreatefrompng($logopath);
		// imagecolortransparent($logo , imagecolorallocatealpha($logo , 0, 0, 0, 127));
		// imagealphablending($logo , false);
		// imagesavealpha($logo , true);
				$QR_width = imagesx($QR);//get logo width
				$QR_height = imagesy($QR);//get logo width
				$logo_width = imagesx($logo);
				$logo_height = imagesy($logo);
				// Scale logo to fit in the QR Code
				$logo_qr_width = $QR_width/3.8;
				$scale = $logo_width/$logo_qr_width;
				$logo_qr_height = $QR_height/3.8;
				$dsx=($QR_width-$logo_qr_width)/2;
				$dsy=($QR_height-$logo_qr_height)/2;
				imagecopyresampled($QR, $logo, $dsx, $dsy, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
				// Simpan kode QR lagi, dengan logo di atasnya
			imagepng($QR,$fullpath);
		}
		deleteOldQR();
		return $fullpath;
	}
	function deleteOldQR(){
		$files = glob(FCPATH."./files/qrs/*.png");
		$now   = time();
		foreach ($files as $file) {
			if (is_file($file)) {
				if ($now - filemtime($file) >= 60 * 60 * 24 * 3) { // 2 days
				//if ($now - filemtime($file) >= 60 * 2) { 
					unlink($file);
				}
			}
		}
	}
