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
		<p style="text-align: center;"><b>
				<u style="font-size: 1.2em">Surat Keterangan Kematian</u><br>
				Nomor: ......./MC/<?= romawi_bulan(date('n')); ?>/<?= date("Y"); ?>
			</b></p>
		<p><br>
			Saya yang bertandatangan di bawah ini, Dokter pada <?= (isset($doc_setting_profile->nama)) ? $doc_setting_profile->nama : ""; ?>, yaitu:<br>
		<table style="margin-left: 30px">
			<tr>
				<td width="100px">Nama</td>
				<td>:</td>
				<td><b><?= (isset($data_periksa->namaDokter)) ? $data_periksa->namaDokter : ""; ?></b></td>
			</tr>
			<tr>
				<td width="100px">SIP</td>
				<td>:</td>
				<td><?= (isset($data_periksa->nip)) ? $data_periksa->nip : ""; ?></td>
			</tr>
			<tr>
				<td width="100px">Jabatan</td>
				<td>:</td>
				<td><?= (isset($data_periksa->position)) ? $data_periksa->position : ""; ?></td>
			</tr>
		</table>
		</p>
		<p>
			Dengan ini menerangkan bahwa yang tersebut namanya di bawah ini:
		<table style="margin-left: 30px">
			<tr>
				<td width="100px">Nama</td>
				<td>:</td>
				<td><b><?= (isset($data_periksa->nama_lengkap)) ? $data_periksa->nama_lengkap : ""; ?></b></td>
			</tr>
			<tr>
				<td width="100px">Tgl Lahir</td>
				<td>:</td>
				<td><?= (isset($data_periksa->tgl_lahir)) ? dateIndo($data_periksa->tgl_lahir) : ""; ?></td>
			</tr>
			<tr>
				<td width="100px">Umur</td>
				<td>:</td>
				<td><?= (isset($data_periksa->tgl_lahir) && $data_periksa->tgl_lahir != "0000-00-00") ? count_age($data_periksa->tgl_lahir) . " Tahun" : ""; ?></td>
			</tr>
			<tr>
				<td width="100px">Pekerjaan</td>
				<td>:</td>
				<td><?= (isset($data_periksa->pekerjaan)) ? $data_periksa->pekerjaan : ""; ?><?= (isset($data_periksa->perusahaan) && $data_periksa->perusahaan != "") ? " (" . $data_periksa->perusahaan . ")" : ""; ?> </td>
			</tr>
			<tr>
				<td width="100px">Alamat</td>
				<td>:</td>
				<td><?= (isset($data_periksa->alamat)) ? $data_periksa->alamat : ""; ?> <?= (isset($data_periksa->nama_kecamatan)) ? $data_periksa->nama_kecamatan : ""; ?> <?= (isset($data_periksa->nama_kabupaten)) ? $data_periksa->nama_kabupaten : ""; ?><?= (isset($data_periksa->nama_provinsi)) ? $data_periksa->nama_provinsi : ""; ?></td>
			</tr>
		</table>
		</p>
		<?php $detail_tgl_kematian = explode(" ", $data_periksa->tgl_kematian); ?>
		<br>
		<p>
			Benar masuk <?= (isset($doc_setting_profile->nama)) ? $doc_setting_profile->nama : ""; ?> pada tanggal <?= dateIndo($data_periksa->tgl_masuk) ?>.Pasien menjalani perawatan selama <?= (isset($data_periksa->lama_inap)) ? $data_periksa->lama_inap : ""; ?> hari. Dan dinyatakan <b>meninggal dunia</b> pada <u>&nbsp;<b><?= dateIndo($detail_tgl_kematian[0]) ?></b></u> pukul <b><u><?= date('H:i', strtotime($detail_tgl_kematian[1])) ?> </u></b> .
		</p>
		<br>
		<p>
			Demikian Surat Keterangan Kematian ini dibuat untuk dipergunakan sebagaimana mestinya.
		</p>
		<p>
			<!-- Catatan Tambahan :<br> -->

		</p>

		<p></p>
		<table width="100%">
			<tr>
				<td width="70%"></td>
				<td width="30%" align="center">
					<?= (isset($doc_setting_profile->kabupaten)) ? $doc_setting_profile->kabupaten : ""; ?>
					, <?= dateIndo(date("Y-m-d H:i:s")); ?><br>
				</td>
			</tr>
			<tr>
				<td width="70%"></td>
				<td width="30%" align="center">
					Dokter Pemeriksa,
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
				<td width="70%" valign="bottom" style="font-size: 10px"></td>
				<td width="30%" align="center" valign="bottom">
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
