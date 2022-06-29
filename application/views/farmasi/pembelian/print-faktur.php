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
		<?php
		$total_nota = sizeof($nota);
		$n = 0;
		foreach ($nota as $nt) {
			$n++;
		?>
			<p style="text-align: right;"><b>FAKTUR PEMBELIAN OBAT</b></p>
			<table>
				<tr>
					<td>No. Izin</td>
					<td>:</td>
					<td><?= $company_info->no_izin; ?></td>
				</tr>
				<tr>
					<td>Alamat</td>
					<td>:</td>
					<td>
						<pre><?= $company_info->alamat; ?></pre>
					</td>
				</tr>
				<tr>
					<td>Telp</td>
					<td>:</td>
					<td><?= $company_info->telp; ?></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
			</table>

			<table width="100%">
				<tr>
					<td>No Faktur: <b><?= $nt->faktur; ?></b></td>
					<td align="right">Supplier: <b><?= $nt->nama_supplier; ?></b></td>
				</tr>
				<tr>
					<td>Tanggal: <b><?= date("d-m-Y", strtotime($nt->tanggal)); ?></b></td>
					<td align="right"></td>
				</tr>
			</table>
			<table width="100%" class="table-bordered table-striped minimize-padding-all" cellspacing=0 cellpadding="5" autosize="1">
				<thead>
					<tr style="background-color:lightgrey; ">
						<th align="left">Nama Obat</th>
						<th align="right">Harga</th>
						<th align="center">Qty</th>
						<th align="left">Satuan</th>
						<th align="left">Disc</th>
						<th align="right">Total</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$t_item = sizeof($detail);
					$total_item = 0;
					if ($t_item == 0) {
						echo "<tr><td colspan='4'>TIDAK ADA DATA</td></tr>";
					} else {
						foreach ($detail[$nt->transaksibeli_id] as $dtl) {
							$t_item++;
							$total_item++;
							if (gettype($dtl) == 'array') $dtl = (object) $dtl;
							echo "<tr>
								<td style='border-top: 1px solid black;'>" . $dtl->nama_obat . "</td>
								<td style='border-top: 1px solid black;' align='right'>" . number_format($dtl->hargabeli) . "</td>
								<td style='border-top: 1px solid black;' align='center'>" . $dtl->qty . "</td>
								<td style='border-top: 1px solid black;'>" . $dtl->namaSatuanobat . "</td>
								<td style='border-top: 1px solid black;'>" . $dtl->diskon . "</td>
								<td style='border-top: 1px solid black;' align='right'>" . number_format($dtl->total) . "</td>
							</tr>";
						}
					}

					?>
				</tbody>
				<tfoot>
					<?php if ($t_item > 0) { ?>
						<tr>
							<td colspan="6"></td>
						</tr>
						<tr>
							<th colspan="3" align="left">Total Item</th>
							<th align="center"><?= $total_item; ?></th>
							<th></th>
							<th align="right"><?= number_format($nt->subtotal); ?></th>
						</tr>
						<tr>
							<th colspan="4" align="left">Diskon</th>
							<th></th>
							<th align="right"><?= $nt->diskonsub; ?></th>
						</tr>
						<tr>
							<th colspan="4" align="left" style="border-top: 1px solid black;">Grand Total</th>
							<th style="border-top: 1px solid black;"></th>
							<th align="right" style="border-top: 1px solid black;"><?= number_format($nt->grandtotal); ?></th>
						</tr>
						<!-- <tr>
							<th colspan="3" align="right">Tunai</th>
							<th align="right"><?= number_format($nt->bayar); ?></th>
						</tr>
						<tr>
							<th colspan="3" align="right">Kembali</th>
							<th align="right"><?= number_format($nt->bayar - $nt->grandtotal); ?></th>
						</tr> -->
					<?php } ?>
				</tfoot>
			</table>
			<p><i>Terbilang : <?php echo terbilang($nt->grandtotal) ?>rupiah</i></p>
		<?php
			echo "<i>* Tgl Print: " . date("d-m-Y H:i:s") . "</i>";
			if ($n < $total_nota) {
				echo '<p style="page-break-after: always;"></p>';
			}
		} ?>
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
		page-break-inside: auto;
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
