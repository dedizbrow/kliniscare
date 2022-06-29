<?php
class User_model extends CI_Model 
{
	
	
	function cari_user($IdUser)
		{
		$sql ="SELECT * FROM `login` WHERE `UserName` ='$IdUser'";
		$query = $this->db->query($sql);
        return $result = $query->result();
		}
		
		
	function Antrianbelumterlayani($tanggal_hari_ini,$tanggal_besok){
		$sql ="SELECT  KODE , NO_ANTRIAN , JENIS_PELAYANAN ,DOKTER , PRAKTEK , time(TGL_REGISTRASI) AS TGL_REGISTRASI ,  TIMEDIFF(CURRENT_TIME,time(TGL_REGISTRASI)) AS WaktuTunggu
					FROM antrian WHERE TGL_REGISTRASI >= '$tanggal_hari_ini' AND TGL_REGISTRASI < '$tanggal_besok' AND (TGL_PELAYANAN IS NULL) 
					AND JENIS_PELAYANAN IN('A','B','C','D','E','F') ORDER BY TGL_REGISTRASI ASC ";
		$query = $this->db->query($sql);
        return $result = $query->result();
		}
		
		
			
	function jumlah_antrian($tanggal_hari_ini,$tanggal_besok){
		$sql ="SELECT SUM(JUMLAHUMUM) AS JUMLAHUMUM , SUM(JUMLAHPERUSAHAAN) AS JMLPERUSAHAAN , SUM(JUMLAHBPJS) AS JUMLAHBPJS , SUM(JUMLAHRESERVASI) AS JUMLAHRESERVASI FROM (
					SELECT 
					CASE WHEN JENIS_PELAYANAN = 'A' THEN COUNT(KODE) ELSE 0 END AS JUMLAHUMUM,
					CASE WHEN JENIS_PELAYANAN = 'B' THEN COUNT(KODE) ELSE 0 END AS JUMLAHPERUSAHAAN, 
					CASE WHEN JENIS_PELAYANAN = 'C' THEN COUNT(KODE) ELSE 0 END AS JUMLAHBPJS, 
					CASE WHEN JENIS_PELAYANAN IN('D','F') THEN COUNT(KODE) ELSE 0 END AS JUMLAHRESERVASI
					FROM antrian 
					WHERE TGL_REGISTRASI >= '$tanggal_hari_ini' AND TGL_REGISTRASI < '$tanggal_besok' AND (TGL_PELAYANAN IS NULL)
					GROUP BY JENIS_PELAYANAN ) AS X";
		$query = $this->db->query($sql);
        return $result = $query->result();
		}
		
		function antrian_total($tanggal_hari_ini,$tanggal_besok){
		$sql ="	SELECT SUM(JUMLAHUMUM) AS JUMLAHUMUM , SUM(JUMLAHPERUSAHAAN) AS JMLPERUSAHAAN , SUM(JUMLAHBPJS) AS JUMLAHBPJS , SUM(JUMLAHRESERVASI) AS JUMLAHRESERVASI FROM (
					SELECT 
					CASE WHEN JENIS_PELAYANAN = 'A' THEN COUNT(KODE) ELSE 0 END AS JUMLAHUMUM,
					CASE WHEN JENIS_PELAYANAN = 'B' THEN COUNT(KODE) ELSE 0 END AS JUMLAHPERUSAHAAN, 
					CASE WHEN JENIS_PELAYANAN = 'C' THEN COUNT(KODE) ELSE 0 END AS JUMLAHBPJS, 
					CASE WHEN JENIS_PELAYANAN IN('D','F') THEN COUNT(KODE) ELSE 0 END AS JUMLAHRESERVASI
					FROM antrian 
					WHERE TGL_REGISTRASI >= '$tanggal_hari_ini' AND TGL_REGISTRASI < '$tanggal_besok'
					GROUP BY JENIS_PELAYANAN ) AS X";
		$query = $this->db->query($sql);
        return $result = $query->result();
		}
		
		
		
		function char_sudahdilayani($tanggal_hari_ini,$tanggal_besok){
		$sql ="	SELECT SUM(JUMLAHUMUM) AS JUMLAHUMUM , SUM(JUMLAHPERUSAHAAN) AS JMLPERUSAHAAN , SUM(JUMLAHBPJS) AS JUMLAHBPJS , SUM(JUMLAHRESERVASI) AS JUMLAHRESERVASI FROM (
					SELECT 
					CASE WHEN JENIS_PELAYANAN = 'A' THEN COUNT(KODE) ELSE 0 END AS JUMLAHUMUM,
					CASE WHEN JENIS_PELAYANAN = 'B' THEN COUNT(KODE) ELSE 0 END AS JUMLAHPERUSAHAAN, 
					CASE WHEN JENIS_PELAYANAN = 'C' THEN COUNT(KODE) ELSE 0 END AS JUMLAHBPJS, 
					CASE WHEN JENIS_PELAYANAN IN('D','F') THEN COUNT(KODE) ELSE 0 END AS JUMLAHRESERVASI
					FROM antrian 
					WHERE TGL_REGISTRASI >= '$tanggal_hari_ini' AND TGL_REGISTRASI < '$tanggal_besok' AND (TGL_PELAYANAN IS NOT NULL)
					GROUP BY JENIS_PELAYANAN ) AS X";
		$query = $this->db->query($sql);
        return $result = $query->result();
		}
		
		
		function max_antrian($tanggal_hari_ini,$tanggal_besok , $JENIS_ANTRIAN){
		$sql =" SELECT CASE WHEN ISNULL(MAX(RIGHT(NO_ANTRIAN,3)) + 1) THEN '1' ELSE MAX(RIGHT(NO_ANTRIAN,3)) + 1 END AS NEXT_ANTRIAN FROM `antrian` WHERE 
		     TGL_REGISTRASI >= '$tanggal_hari_ini' AND TGL_REGISTRASI < '$tanggal_besok' AND JENIS_PELAYANAN ='$JENIS_ANTRIAN'";
		$query = $this->db->query($sql);
        return $result = $query->result();
		}
		
		function insert_antrian($KODE_NOMER_ANTRIAN,$NOMER_ANTRIAN , $JENIS_ANTRIAN){
		$sql =" INSERT INTO antrian (KODE , NO_ANTRIAN , JENIS_PELAYANAN , TGL_REGISTRASI) VALUES ('$KODE_NOMER_ANTRIAN','$NOMER_ANTRIAN','$JENIS_ANTRIAN', NOW())";
		$query = $this->db->query($sql);
      return "OK";
		}
		
		
		function carimax_antrian($tanggal_hari_ini,$tanggal_besok , $JENIS_ANTRIAN){
		$sql =" SELECT 
				KODE , NO_ANTRIAN , CASE WHEN  ISNULL(TIMEDIFF(CURRENT_TIME(),TGL_REGISTRASI)) THEN '00:00:00' ELSE TIMEDIFF(CURRENT_TIME(),TGL_REGISTRASI) END AS WAKTUTUNGGU
				FROM antrian 
				WHERE TGL_REGISTRASI >= '$tanggal_hari_ini' AND TGL_REGISTRASI < '$tanggal_besok' AND TGL_PELAYANAN IS NULL AND JENIS_PELAYANAN ='$JENIS_ANTRIAN' ORDER BY TGL_REGISTRASI ASC LIMIT 1 ";
		$query = $this->db->query($sql);
        return $result = $query->result();
		}
		
		function carimax_antrian2($tanggal_hari_ini,$tanggal_besok ){
		$sql =" SELECT 
				KODE , NO_ANTRIAN , CASE WHEN  ISNULL(TIMEDIFF(CURRENT_TIME(),TGL_REGISTRASI)) THEN '00:00:00' ELSE TIMEDIFF(CURRENT_TIME(),TGL_REGISTRASI) END AS WAKTUTUNGGU
				FROM antrian 
				WHERE TGL_REGISTRASI >= '$tanggal_hari_ini' AND TGL_REGISTRASI < '$tanggal_besok' AND TGL_PELAYANAN IS NULL  ORDER BY TGL_REGISTRASI ASC LIMIT 1 ";
		$query = $this->db->query($sql);
        return $result = $query->result();
		}
		
		function update_antrian($WAKTUTUNGGU2,$konter , $KODE_ANTRIAN ){
		$sql =" UPDATE antrian SET TGL_PELAYANAN= NOW() , LAMA_ANTRIAN = '$WAKTUTUNGGU2' ,  DILAYANI_OLEH = '$konter' WHERE KODE ='$KODE_ANTRIAN'";
		$query = $this->db->query($sql);
        
		}
		
}


