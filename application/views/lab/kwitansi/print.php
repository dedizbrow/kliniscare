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
		<br>
		<br>
		<p style="text-align: right;"><b>KWITANSI</b></p>
		<table cellspacing="0" cellpadding="5" width="100%">
			<tr>
				<td width="25%">Sudah Terima dari</td>
				<td width="5%">:</td>
				<td><?= $desc_terima_dari; ?></td>
			</tr>
			<tr>
				<td>Banyak Uang</td>
				<td>:</td>
				<td># <?= $biaya_terbilang; ?> Rupiah #</td>
			</tr>
			<tr>
				<td>Untuk Pembayaran</td>
				<td>:</td>
				<td><?= $desc_pembayaran; ?></td>
			</tr>
			<tr>
				<td>Jumlah Uang</td>
				<td>:</td>
				<td>Rp. <?= format_number($biaya); ?></td>
			</tr>
		</table>
		<br>
		<table width="100%" border="0" cellspacing="0" cellpadding="7">
			<tr>
				<th width="50%" align="left">DETAIL TRANSAKSI</th>
				<th width="15%" align="center">Jumlah</th>
				<th width="30%" align="right">Total</th>
			</tr>
			<!-- <tr>
				<td width="50%">1. Administration</td>
				<td width="15%" align="center">1x</td>
				<td width="30%" align="right">0</td>
			</tr> -->
			<tr>
				<td width="50%">1. <?= $data_periksa->jenis_pemeriksaan; ?></td>
				<td width="15%" align="center">1x</td>
				<td width="30%" align="right">Rp. <?= format_number($biaya); ?></td>
			</tr>
			<tr>
				<td width="5%" colspan="3"></td>
			</tr>
			<tr>
				<td colspan="2">Pajak</td>
				<td align="right">-</td>
			</tr>
			<tr>
				<td colspan="2"><u>Grand Total</u></td>
				<td align="right"><u>Rp. <?= format_number($biaya); ?></u></td>
			</tr>
		</table>
		<p></p>
		<table width="100%">
			<tr>
				<td width="70%"></td>
				<td width="30%" align="center">
					<?= (isset($doc_setting_profile->kabupaten)) ? $doc_setting_profile->kabupaten : ""; ?>, <?= dateIndo($data_periksa->update_hasil_at); ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="right"> <?php
												if (isset($ttd_hasil)) {
													echo '<img src="' . $ttd_hasil . '" style="max-width: 120px" class="image-ttd">';
												} ?></td>
			</tr>
			<tr>
				<td width="70%" valign="bottom" style="font-size: 10px">Print Date: <?= date("Y-m-d H:i:s"); ?></td>
				<td width="30%" align="center" valign="bottom">
					<!-- (&nbsp;&nbsp;&nbsp;&nbsp;Admin&nbsp;&nbsp;&nbsp;&nbsp;) -->
				</td>
			</tr>
		</table>
	</div>
</body>

</html>

<style>
	body {
		font-family: 'Courier New', Courier, monospace;
		font-size: 11px;
		line-height: 1.2;
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
