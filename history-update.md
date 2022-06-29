# update

# update 2022-01-21
	- fix bugs setting menu user (base menu not set)
	- add import data obat
		- alter table obat add import_id
		- satuan beli dan kategori otomatis ditambahkan kedatabase jika tidak terdaftar
		- supplier wajib terdaftar
	- add resep di koreksi tindakan
	
# update 2022-01-20
	- Fix session . change ctc_session to cisession
	- update pencarian nomor RM data pasien
	- fix sorting nomor rm
	- fix antrian
	- fix surat sakit
	- add rekam medis di antrian pemeriksaan

# update 2022-01-17 - 2022-01-19
	- Antrian
# update 2022-01-16
	- Fitur export pada laporan (kunjungan pasien, fee dokter, diagnosa penyakit)

# update 2022-01-15
	- Report
		- Laporan Kunjungan Pasien per tanggal 
			// add menu /report/kunjungan-pasien
		- Laporan Summary Diagnosa
			// add menu /report/summary-diagnosa

# update 2022-01-11 - 2022-01-14
	> Fix issue nomor RM stuck di 10K (lab/Pasien_model & Pendaftaran_model)
	> Tambah Tarif Dokter di layanan poli
		- alter table tbl_layanan_poli
			ALTER TABLE tbl_layanan_poli ADD tarif_dokter_percent DOUBLE, ADD tarif_dokter DOUBLE
			panggil /admin/common-control/alter-table
		
		- alter table tbl_bayar_periksa
			ALTER TABLE tbl_bayar_periksa ADD tarif_dokter DOUBLE
		
		- tarif dokter per pemeriksaan di Billing

	> new Menu Report
		- Report Fee Dokter

# update 2021-10-13
	> Fix bugs
		- Nomor Antrian,
			MAX(CAST(REGEXP_SUBSTR(nomor_antrian,"[0-9]+$") AS UNSIGNED))+1 = mengambil hanya nomor terakhir (ignore string didepan) + 1

# update 2021-10-10 ^2
	> Fix bugs
		- fix missing clinic_id jenis_pemeriksaan category covid

# update 2021-10-10
	> Fix bugs
		- add/update jenis pemeriksaan
		- hide menu with code ctc:: in setting user
		
# update 2021-10-09 ^2
	> update helper -> GenerateMenu
		- Untuk access code menu yang diawali dengan "ctc::" merupakan akses code super admin yang hanya akan muncul untuk akun super_admin ctc.
*** PENTING ***
		- Ubah access_code admin/klinik dari c-klinik ke ctc::clinic agar menu ini tidak muncul di super-admin masing-masing klinik

# update 2021-10-09
	> update admin/klinik
	> update load dt user

# update 2021-10-08 ^5
	> Alter backup
		ALTER TABLE `c_backup_list` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT; 
		- new table c_backup_ref (saving mail sender for backup)
	> update Adms/backup_db
		- auto backup and send email
		
# update 2021-10-08 ^4
	> Protect Klinik
		- hanya bisa diakses oleh 
	
	> Administrative 
		- hide akun 
	
	> Alter Table registered_clinics
		ALTER TABLE `c_registered_clinics` ADD `account_type` VARCHAR(40) NOT NULL COMMENT 'production/demo' AFTER `reg_by`, ADD `license_duration` INT(11) NOT NULL DEFAULT '1' AFTER `account_type`, ADD `license_type` VARCHAR(10) NOT NULL DEFAULT 'month' COMMENT 'day/month/year' AFTER `license_duration`, ADD `remarks` TEXT NOT NULL AFTER `license_type`, ADD INDEX `account_type` (`account_type`), ADD INDEX `license_type` (`license_type`), ADD INDEX `license_duration` (`license_duration`); 
		ALTER TABLE `c_registered_clinics` ADD `phone` VARCHAR(30) NOT NULL AFTER `license_type`, ADD `email` VARCHAR(50) NOT NULL AFTER `phone`; 
	> Klinik (control clinic)
		- Update form (sesuai alter diatas)

	> Login
		- Check license klinik ketika login

# update 2021-10-08 ^3
	> ctc_helper 
		- fix issue non-admin account -> clinic_id = undefined

# update 2021-10-08 ^2
	> update template 
		- jika clinic id di skip dan default=allclinic dan clinic_id di definisikan maka secara default buat selection source_clinic
	> add clinic_id form edit pemeriksaan
	> add clinic_id for notes, sampling

# update 2021-10-08
	> Add function generateQRCode
	> Change structure uniq_code for checking document
	> Update check doc
	> Add edit jenis pemeriksaan
	> Fix invalid content for column action in setting tarif
	
# update 2021-10-07 ^2
	> new tables
		- lab_item_hasil_periksa
		- lab_subitem_hasil_periksa

	> alter table
		ALTER TABLE `lab_tarif` ADD `clinic_id` INT(11) NOT NULL AFTER `id`, ADD INDEX `clinic_id` (`clinic_id`); 

	> Update hasil pemeriksaan
		- form update hasil
		- print pdf
		
	> Update setting tarif
	> Jenis Pemeriksaan
		- Add notes only
	> ctc.js
		- add function getURLParameters	
# update 2021-10-06 - 2021-10-07
	> Alter Table
		ALTER TABLE `lab_jenis_pemeriksaan` ADD `category` VARCHAR(50) NOT NULL DEFAULT 'covid' AFTER `clinic_id`, ADD INDEX `category` (`category`); 

	> New Tables
		- lab_item_jenis_pemeriksaan
		- lab_subitem_jenis_pemeriksaan
	
	> Update Jenis pemeriksaan
		- Add jenis pemeriksaan - complete
		** Edit belum
		** Review : Mungkin kategori akan dihapus, tidak perlu dipisahkan kategori umum dan covid
	
	> Pemeriksaan - Update Hasil
		** Inprocess
		
# update 2021-10-05 ^2
	> Alter lab
		ALTER TABLE `lab_data_pemeriksaan` ADD `clinic_id` INT NOT NULL AFTER `id`, ADD INDEX `clinic_id` (`clinic_id`); 
		ALTER TABLE `lab_data_pemeriksaan` DROP INDEX `no_test`;
		ALTER TABLE `lab_data_pemeriksaan` ADD INDEX(`no_test`); 
		ALTER TABLE `lab_jenis_pemeriksaan` ADD `clinic_id` INT NOT NULL AFTER `jenis`, ADD INDEX `clinic_id` (`clinic_id`); 
		ALTER TABLE `lab_jenis_sampling` ADD `clinic_id` INT NOT NULL AFTER `id`, ADD INDEX `clinic_id` (`clinic_id`); 
		ALTER TABLE `lab_data_notes` ADD `clinic_id` INT NOT NULL AFTER `id`, ADD INDEX `clinic_id` (`clinic_id`); 
		
# update 2021-10-05 
	> Fix issue other setting
	> Alter table remove unuqie
		ALTER TABLE c_doc_requirements DROP INDEX code ;
		ALTER TABLE c_doc_requirements ADD INDEX(code); 

# update 2021-10-04
	> helpers/authentication
		- send response error when no session on ajax request
		
	> Other Setting
		- Implement Clinic ID
		> Alter table
			- ALTER TABLE `c_doc_requirements` ADD `clinic_id` INT(11) NOT NULL AFTER `id`, ADD INDEX `clinic_id` (`clinic_id`); 

### update 2021-09-30 amr

    > Farmasi, KIR, Billing
    	* Farmasi : query load, query load temp obat, satuan obat per clinik, cek kode obat, cek faktur jual/beli,

# update 2021-09-29 amr

    > Master data
    	- Pemberian hak akses (creaate, update, delete)
    	- Membatasi select item per klinik
    	- check data ketika save per klinik
    	- import data per klinik
    	> alter table
    		- tbdaftardokter, tbdiagnosis,tbjadwaldokter, tbkaryawan, tbkaryawan_bidang,tbl_layanan_poli, tbrekanan, tbsupplier, tbpoli, tbruangan, tbruangan_kategori, tbruangan_kelas,tbperujuk, tbperujuk_tipe

# update 2021-09-28 by Yana

    > Master data
    	> Dokter
    		** Contoh untuk implementasi pembatasan akses (link edit/delete hanya muncul jika isAllowed(access_code^action_code))
    		- update query load_dt with clinic_id
    		- Implement limit user allow edit/delete


# update 2021-09-28 by Yana

    > Fix issue data pasien.
    	- Untuk data select2 yg kosong/null, maka di controller perlu di check dengan if isset
    > update query antrian pemeriksaan

    > Alter Table
    	ALTER TABLE `tbruangan` ADD `clinic_id` INT(11) NOT NULL AFTER `idRuangan`, ADD INDEX `clinic_id` (`clinic_id`);
    	ALTER TABLE `tbl_obat` ADD `clinic_id` INT(11) NOT NULL AFTER `idObat`, ADD INDEX `clinic_id` (`clinic_id`);

    > Rawat jalan
    	> Pasien Telah Diperiksa
    		- Update query load_dt & Load_dt_obat
    		- Update javascript - add clinic id
    	> Data Pasien
    		- Add clinic id ketika save
    		** Belum di test

    > Rawat Inap
    	> Pemeriksaan
    		- Update query load_dt, load_dt_ruangan
    		- Update javascript - add clinic id
    	> Koreksi Tindakan
    		- Update query to include clinic_id
    		- Update javascript - add clinic id
    	** Belum di test dengan data

    > Fix bugs ctc.js in adding clinic_id
    > Rawat Jalan
    	> Antrian Ditunda
    		- Add Clinic ID in query
    	> Data Pasien
    		- Fix filter where ketika search

# update 2021-09-27 #2

> alter tables

    - tbl_pendaftaran ,tbl_antrian,tbpoli,tbdaftardokter,tbl_triase

> Update Pendaftaran

    - membatasi selection DPJP,Poli Klinik sesuai dengan Clinic ID
    - make sure nomor_rm not in use when saving
    - make sure that no_antrian no in use when saving
    - change query for search nomor_rm,no_antrian,invoice_no

> Update Kunjungan ID

    - Hampir sama dengan pendaftaran

# update 2021-09-19 -> 2021-09-27

Administrative - Cara mengatur Access/Action Code
contoh: priviledge Account Setting (Create, Update, Delete) > Database/Table - access_code = buat akses code sesuai menu terkait, - actions_code = pembatasan akses tertentu, pisahkan dengan comma jika terdapat lebih dari 1 actions yang dibatasi > Controller - Jika seluruh methods dalam controller membutuhkan access_code untuk access, panggil function isAllowed pada constructor
isAllowed(access_code) - Jika tidak maka cukup letakkan pada method yang membutuhkan - Jika Method tertentu membutuhkan pemisahan priviledge (contoh: create,update,delete), maka
panggil isAllowed(access_code^action_code) => pisahkan dengan tanda ^
Contoh Kasus: - /Administrative/save_user - save new user / insert => isAllowed("c-privilege^create"); - save update => isAllowed("c-privilege^update"); - /Administrative/enable_disable_user
isAllowed("c-privilege^activate-user")

    		* Jika membutuhkan return nilai true/false maka tambahkan isAllowed(access_code,true)

    - Modify Data user
    	> Controller : Administrative/load_dt_users
    		- call modify_post di controller
    	> Model : Administrative/dt_users
    		- Modify Filter
    			/* PENTING */
    			** masukkan ke dalam tanda kurung jika terdapat OR karena pada function build_filter_table ada pembatasan untuk klinik yang dipilih
    			if(isset($output->search) && $output->search!=""){
    				$sWhere.=($sWhere=="") ? " WHERE ": " AND ";
    				$sWhere.=" (name LIKE '%".$output->search."%' OR email LIKE '%".$output->search."%')"; // **
    			}

Helper/ctc_helper - Update session ketika generateToken > Clinic ID = $app_code."CTC-CL-ID" > Clinic Name = $app_code."CTC-CL-NAME"

    - Update function build_filter_table
    	* build_filter_table($posted, $order_cols = [], $skipped_orders = [],$clinic_id_for_join_table="")
    	> Menambahkan filter clinic_id secara default jika dikirim posted['clinic_id']
    	> para parameter ke 4 $clinic_id_for_join_table, jika join table yg sama-sama memiliki clinic_id maka tentukan disini, contoh: pasien.clinic_id

    - Function getClinic()
    	> panggil function untuk mengambil default clinic

    - Function modify_post
    	> Untuk semua data khususnya datatable, function ini digunakan untuk modifikasi data post jika tidak menyertakan clinic_id (misal bukan akun kliniscare), maka posted data akan ditambah dengan default clinic_id dari user tersebut.
    		Contoh kasus pada load data untuk datatable (data_pasien).
    		>> panggil function ini pada method load_data


Authentication/Login - Update session to add clinic_id & name - create function getClinic() to return object of clinic
getClinic() => {
id=> id klinik (allclinic means super admin untuk akses semua klinik),
name=> nama klinik
}
Template - Secara default selection clinic (#source_clinic) akan muncul pada semua halaman untuk akun yang punya akses ke semua klinik - Untuk disable/hide selection clinic pada halaman tertentu: tambahkan parameter $this->data['skip_select_clinic']=true;

Data Pasien - Modify load data hanya untuk klinik terkait > Controller : load_dt >> Panggil modify_post > Model : load_dt >> update filter for datatable
/_ PENTING _/
** masukkan ke dalam tanda kurung jika terdapat OR karena pada function build_filter_table ada pembatasan untuk klinik yang dipilih
if (isset($output->search) && $output->search != "") {
				$sWhere.=($sWhere=="") ? " WHERE ": " AND ";
$sWhere = " (no_identitas LIKE '%" . $output->search . "%' OR nama_lengkap LIKE '%" . $output->search . "%')"; // **
}

    - javascript: tambahkan d.clinic_id = getSelectedClinic(); pada ajax data datatable
