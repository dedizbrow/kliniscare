<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adms extends CI_Controller {
    var $data;
	public function __construct()
	{
		parent::__construct();
		$this->load->model("admin/Adms_model","adms");
		$this->path_backup="../../backup_db";
		$this->file_backup_name="KlinisCare"; // will be add datetime
	}
  public function backup_db(){
		$this->load->dbutil();
		// Backup your entire database and assign it to a variable
		$backup = $this->dbutil->backup();
		// Load the file helper and write the file to your server
		$this->load->helper('file');
		
		$setting=$this->adms->load_account_backup();
		$acc=$setting[0];
		
		if(!file_exists($this->path_backup)) mkdir($this->path_backup,0777,true);
		$file_loc=$this->path_backup.'/'.$this->file_backup_name.'_'.date("Ymdhis").'.sql.gz';
		write_file($file_loc, $backup);
		$this->adms->save_backup_db(array("filename"=>$file_loc));
		$this->adms->delete_old_backup_db($acc->duration_store);
		//echo "Done";
		$files = glob($this->path_backup."/".$this->file_backup_name."*");
		$now   = time();
		foreach ($files as $file) {
			if (is_file($file)) {
				if ($now - filemtime($file) >= 60 * 60 * 24 * $acc->duration_store) { 
					unlink($file);
					// echo $file;
				}
			}
		}
		
		
		$configMail = [
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'protocol'  => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_user' => $acc->email_sender,  // Email gmail
            'smtp_pass'   => base64_decode($acc->pass_sender),  // Password gmail
            'smtp_crypto' => 'ssl',
            'smtp_port'   => 465,
            'crlf'    => "\r\n",
            'newline' => "\r\n"
        ];
		$this->load->library('email',$configMail);
		$this->email->from($acc->email_sender, 'CTC Backup');
		$this->email->to($acc->recipient);
		$this->email->attach($file_loc);
		$this->email->subject("Backup DB of ".conf('company_name'));
		$this->email->message( date("Y-m-d H:i:s").'<br>This email was sent automatically by system for database backup purpose,<br>System will send this backup twice/day.<br>If you wish to change the duration backup, please update cronjob at cpanel');
		if ($this->email->send()) {
				echo 'Sukses! email berhasil dikirim.';
		} else {
				echo 'Error! email tidak dapat dikirim.';
		}
	}
}
