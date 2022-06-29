<!DOCTYPE html>
<html>

<head>
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta charset="utf-8">
	<title><?= (isset($page_title)) ? $page_title : ''; ?></title>
	<link rel="icon" href="<?= base_url('assets/img/favicon.ico'); ?>" type="image/gif">
</head>

<body>
	<div class="body-print-pdf">

		<?php if (isset($data_periksa->lama_mc) && $data_periksa->lama_mc > 0) {
		?>
			<p style="text-align: center;"><b>
				<u style="font-size: 1.2em">Surat Keterangan Sakit</u><br>
				Nomor: ......./SKSK/<?= romawi_bulan(date('n')); ?>/<?= date("Y"); ?>
			</b></p>
		<p><br>
		<?php
		} else { ?>
			<p style="text-align: center;"><b>
				<u style="font-size: 1.2em">Surat Keterangan Sehat</u><br>
				Nomor: ......./SKSH/<?= romawi_bulan(date('n')); ?>/<?= date("Y"); ?>
			</b></p>
		<p><br>
		<?php
		}
		?>

		<p><br>
			Saya yang bertandatangan di bawah ini, Dokter pada <?= (isset($doc_setting_profile->nama)) ? $doc_setting_profile->nama : ""; ?>, yaitu:<br>
		<table style="margin-left: 30px">
			<tr>
				<td width="130px">Nama</td>
				<td>:</td>
				<td><b><?= (isset($data_periksa->namaDokter)) ? $data_periksa->namaDokter : ""; ?></b></td>
			</tr>
			<tr>
				<td width="130px">SIP</td>
				<td>:</td>
				<td><?= (isset($data_periksa->nip)) ? $data_periksa->nip : ""; ?></td>
			</tr>
		</table>
		</p>
		<p>
			Berdasarkan pemeriskaan menerangkan bahwa:
		<table style="margin-left: 30px">
			<tr>
				<td width="130px">Nama</td>
				<td>:</td>
				<td><b><?= (isset($data_periksa->nama_lengkap)) ? $data_periksa->nama_lengkap : ""; ?></b></td>
			</tr>
			<tr>
				<td width="130px">Umur</td>
				<td>:</td>
				<td><?= (isset($data_periksa->tgl_lahir) && $data_periksa->tgl_lahir != "0000-00-00") ? count_age($data_periksa->tgl_lahir) . " Tahun" : ""; ?></td>
			</tr>
			<tr>
				<td width="130px">Pekerjaan</td>
				<td>:</td>
				<td><?= (isset($data_periksa->pekerjaan)) ? $data_periksa->pekerjaan : "-"; ?><?= (isset($data_periksa->perusahaan) && $data_periksa->perusahaan != "") ? " (" . $data_periksa->perusahaan . ")" : ""; ?> </td>
			</tr>
			<tr>
				<td width="130px">Alamat</td>
				<td>:</td>
				<td><?= (isset($data_periksa->alamat)) ? $data_periksa->alamat : "-"; ?></td>
			</tr>
			<tr>
				<td width="130px">Tinggi Badan</td>
				<td>:</td>
				<td><?=(isset($data_periksa->tb)) ? $data_periksa->tb : '-';?> cm</td>
			</tr>
			<tr>
				<td width="130px">Berat Badan</td>
				<td>:</td>
				<td><?=(isset($data_periksa->bb)) ? $data_periksa->bb : '-';?> kg</td>
			</tr>
			<tr>
				<td width="130px">Tekanan Darah</td>
				<td>:</td>
				<td><?=(isset($data_periksa->tensi)) ? $data_periksa->tensi : '-';?> mm/Hg</td>
			</tr>
			<tr>
				<td width="130px">Golongan Darah</td>
				<td>:</td>
				<td><?=(isset($data_periksa->gol_darah)) ? $data_periksa->gol_darah : '-';?></td>
			</tr>
			<tr>
				<td width="130px">Buta Warna</td>
				<td>:</td>
				<td><?=(isset($data_periksa->buta_warna)) ? $data_periksa->buta_warna : '-';?></td>
			</tr>
		</table>
		</p>
		<?php if (isset($data_periksa->lama_mc) && $data_periksa->lama_mc > 0) {
		?>
			<p>
				Pasien di atas tersebut mengalami keadaan/kondisi yang memerlukan istirahat secara maksimal atau cukup selama <u>&nbsp;<b><?= (isset($data_periksa->lama_mc)) ? $data_periksa->lama_mc : ""; ?></b></u> (hari) terhitung sejak tanggal <i><b><?= (isset($data_periksa->tgl_mc)) ? $data_periksa->tgl_mc : ""; ?></b></i> s/d <i><b><?= (isset($data_periksa->tgl_mc_end)) ? $data_periksa->tgl_mc_end : ""; ?></b></i> dikediaman pasien dengan alamat tertera.
			</p>
		<?php
		} else { ?>
			<p>
				Pasien diatas tersebut dalam kondisi yang <b>SEHAT</b> berdasarkan pemeriksaan yang dilakukan pada tanggal <b><?= (isset($data_periksa->tgl_mc)) ? $data_periksa->tgl_mc : ""; ?></b>.
			</p>
		<?php
		}
		?>
		<p>
			Demikian Surat Medical Certificate (MC) ini kami buat dengan sebenar-benarnya, Mohon untuk dimaklumi dan digunakan semestinya.
		</p>
		<p>
			<!-- Catatan Tambahan :<br> -->

		</p>

		<p></p>
		<table width="100%">
			<tr>
				<td width="50%"></td>
				<td width="50%" align="center">
					<p style="font-size:17px">
					<?= (isset($doc_setting_profile->kabupaten)) ? $doc_setting_profile->kabupaten : ""; ?>, <?= dateIndo(date("Y-m-d H:i:s")); ?><br>
					</p>
				</td>
			</tr>
			<tr>
				<td width="50%"></td>
				<td width="50%" align="center">
					<p style="font-size:17px">Dokter Pemeriksa,</p>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="right">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="right">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="right">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="right">&nbsp;</td>
			</tr>
			<tr>
				<td width="50%" valign="bottom" style="font-size: 17px"></td>
				<td width="50%" align="center" valign="bottom">
					<b><u><?= (isset($data_periksa->namaDokter)) ? $data_periksa->namaDokter : ""; ?></u></b>
				</td>
			</tr>
		</table>
	</div>
</body>

</html>

<style>
	body {
		font-family: 'Times New Roman';
		line-height: 1.5;
	}

	/* pdf print setting */
	.row-title-page {
		margin-bottom: 20px;
	}

	table tr td {
		vertical-align: top;
	}

	table.table-desc {
		line-height: 1.8
	}

	table {
		page-break-inside: auto
	}

	tr {
		page-break-inside: avoid;
		page-break-after: auto
	}

	.page-header-pdf {
		padding-top: 0cm;
		padding-left: 0cm;
		padding-right: 0cm
	}

	.body-print-pdf {
		padding-left: 2cm;
		padding-right: 2cm;
	}

	.page-footer-pdf {
		text-align: center;
		position: relative;
		background-image: url(<?= base_url('assets/img/lab/img-doc-footer.png'); ?>);
		background-repeat: no-repeat;
		background-size: 100% 100%;
		z-index: 1000;
		margin-top: -33px;
	}

	.page-footer-text {
		color: #fff;
		font-size: 12px;
		font-weight: bold;
		padding: 40px 3px 12px 3px
	}

	.image-ttd2 {
		z-index: 1;
		width: 200px;
	}

	/* end pdf print setting */
</style>
