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
		<p style="text-align: right;"><b>KWITANSI</b></p>
		<table cellspacing="0" cellpadding="5" width="100%">
			<tr>
				<td width="15%" align="left">Pasien</td>
				<td width="1%" align="left">:</td>
				<td width="33%"><?= $data_periksa->nama_lengkap ?></td>

				<td width="15%" align="left">No Invoice</td>
				<td width="1%" align="right">:</td>
				<td width="33%" align="right"><?= $data_periksa->no_invoice ?></td>

			</tr>
			<tr>
				<td width="15%" align="left">NIK</td>
				<td width="1%" align="right">:</td>
				<td width="33%">[<?= $data_periksa->identitas ?>] <?= $data_periksa->no_identitas ?></td>

				<td width="15%" align="left">Tanggal</td>
				<td width="1%" align="left">:</td>
				<td width="33%" align="right"><?= date("Y-m-d"); ?></td>

			</tr>
		</table>
		<table width="100%" border="0" class="t-font" cellspacing="0" cellpadding="7">
			<thead>
				<tr style="background-color:lightgrey;">
					<th colspan="5" align="left">Layanan</th>
					<th align="right">Harga </th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no = 1;
				$sum = 0;
				foreach ($data_tindakan as $item) {
				?>
					<tr>
						<td colspan="5" align="left"><?php echo $item->nama_layanan_poli; ?></td>
						<td align="right"><?php echo format_number($item->harga_layanan_poli); ?></td>
					</tr>
				<?php
					$no++;
					$sum += $item->harga_layanan_poli;
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="5" align="left" style="border-top: 1px solid black;"><b>Sub Total</b></td>
					<td align="right" style="border-top: 1px solid black;"><b><?php echo format_number($sum); ?></b></td>
				</tr>
				<tr>
					<td colspan="6"></td>
				</tr>
				<tr>
					<td colspan="6">Detail Resep#</td>
				</tr>
			</tfoot>
		</table>

		<table width="100%" border="0" class="t-font" cellspacing="0" cellpadding="7">
			<thead>
				<tr style="background-color:lightgrey;">
					<th width="30%" align="left">Nama</th>
					<th width="20%" align="left">Aturan pakai </th>
					<th width="20%" align="left">Cara pakai</th>
					<th width="5%" align="right">QTY </th>
					<th width="8%" align="right">Harga </th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no = 1;
				$sum_obat = 0;
				foreach ($data_resep as $item) {
				?>
					<tr>
						<td width="30%" align="left"><?php echo $item->nama; ?></td>
						<td width="20%" align="left"><?php echo $item->nama_aturan_pakai; ?></td>
						<td width="20%" align="left"><?php echo $item->nama_cara_pakai; ?></td>
						<td width="5%" align="right"><?php echo $item->qty; ?></td>
						<td width="8%" align="right"><?php echo format_number($item->total); ?></td>
					</tr>
				<?php
					$no++;
					$sum_obat += $item->total;
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4" align="left" style="border-top: 1px solid black;"><b>Sub Total</b></td>
					<td align="right" style="border-top: 1px solid black;"><b><?php echo format_number($sum_obat); ?></b></td>
				</tr>
				<tr>
					<td colspan="5"></td>
				</tr>
				<tr>
					<td colspan="5">Detail Ruangan#</td>
				</tr>

			</tfoot>
		</table>
		<table width="100%" class="t-font" border="0" cellspacing="0" cellpadding="7">
			<thead>
				<tr style="background-color:lightgrey;">
					<th width="39%" align="left">Nama/Nomor Ruangan</th>
					<th width="15%" align="left">Tgl Masuk</th>
					<th width="15%" align="left">Tgl Keluar </th>
					<th width="10%" align="right">Harga (hari) </th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="39%" align="left"><?= (isset($data_kamar->checkin_at)) ? $data_kamar->namaRuangan . $data_kamar->nomor : ''; ?></td>
					<td width="15%" align="left"><?= (isset($data_kamar->checkin_at)) ? dateIndo($data_kamar->checkin_at) : ''; ?></td>
					<td width="15%" align="left"><?= (isset($data_kamar->checkout_at)) ? dateIndo($data_kamar->checkout_at) : ''; ?></td>
					<td width="10%" align="right"><?= (isset($data_kamar->tarif)) ?  format_number($data_kamar->tarif) : ''; ?></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3" align="left" style="border-top: 1px solid black;"><b>Sub Total</b></td>
					<td width="30%" align="right" style="border-top: 1px solid black;"><b><?= (isset($tot_biaya_kamar)) ?  format_number($tot_biaya_kamar) : '0'; ?></b></td>
				</tr>
				<?php $sum_kamar = $tot_biaya_kamar; ?>
				<tr>
					<td colspan="3" align="left" style="border-top: 1px solid black;"><b>Grand Total</b></td>
					<td width="20%" align="right" style="border-top: 1px solid black;"><b><?php echo format_number($sum_obat + $sum + $sum_kamar); ?></b></td>
				</tr>
			</tfoot>
		</table>
		<?php $grandtotal = $sum + $sum_obat + $sum_kamar; ?>
		<p><i>Terbilang : <?php echo terbilang($grandtotal) ?> rupiah</i></p>
		<table width="100%">
			<tr>
				<td width="70%"></td>
				<td width="30%" align="center">
					<?= (isset($doc_setting_profile->kabupaten)) ? $doc_setting_profile->kabupaten : ""; ?>, <?= dateIndo(date("Y-m-d H:i:s")); ?><br>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="right">
					<?php
					if (isset($ttd_hasil)) {
						echo '<img src="' . $ttd_hasil . '" style="max-width: 200px" class="image-ttd">';
					} ?></td>
			</tr>
			<tr>
				<td width="70%" valign="bottom" style="font-size: 10px">Print Date: <?= date("Y-m-d H:i:s"); ?></td>
				<td width="30%" align="center" valign="bottom">
				</td>
			</tr>
		</table>
	</div>
</body>

</html>

<style>
	body {
		font-family: 'Times New Roman';
		font-size: 12px;
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
		page-break-inside: auto;
	}

	.t-font tr td {
		font-family: 'Courier New';
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
