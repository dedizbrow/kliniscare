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
			<!-- <b style="text-align: center;">NOTA</b> -->
			<table width="100%" style="font-family: 'Courier New', Courier, monospace; font-size: 8px;">
				<tr>
					<td align="center"><?= $company_info->nama; ?></td>
				</tr>
				<tr>
					<td align="center">
						<pre><?= $company_info->alamat; ?></pre>
					</td>
				</tr>
				<tr>
					<td align="center"><?= $company_info->telp; ?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>

			<table width="100%" style="font-family: 'Courier New', Courier, monospace; font-size: 8px;">
				<tr>
					<td align="left"><?= $nt->faktur; ?></td>
					<td align="right"><?= date("d-m-Y H:i") ?></td>
				</tr>
			</table>
			<table width="100%" class="table-bordered table-striped minimize-padding-all" cellspacing=0 cellpadding="5" autosize="1" style="font-family: 'Courier New', Courier, monospace; font-size: 10px;">
				<thead>
					<tr style="background-color:lightgrey; ">
						<th align="left" width="36%" style='border-top: 1px dashed black;border-bottom: 1px dashed black;'>Obat</th>
						<th align="center" width="15%" style='border-top: 1px dashed black;border-bottom: 1px dashed black;'>Qty</th>
						<th align="right" width="23%" style='border-top: 1px dashed black;border-bottom: 1px dashed black;'>Harga</th>
						<th align="right" width="26%" style='border-top: 1px dashed black;border-bottom: 1px dashed black;'>Total</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$t_item = sizeof($detail);
					$total_item = 0;
					if ($t_item == 0) {
						echo "<tr><td colspan='4'>TIDAK ADA DATA</td></tr>";
					} else {
						foreach ($detail[$nt->transaksijual_id] as $dtl) {
							$t_item++;
							$total_item++;
							if (gettype($dtl) == 'array') $dtl = (object) $dtl;
							echo "<tr>
								<td >" . $dtl->nama_obat . "</td>
								<td  align='center'>" . $dtl->qty . "</td>
								<td  align='right'>" . number_format($dtl->harga) . "</td>
								<td align='right'>" . number_format($dtl->total) . "</td>
							</tr>";
						}
					}

					?>
				</tbody>
				<tfoot>
					<?php if ($t_item > 0) { ?>
						<tr>
							<td colspan="4"></td>
						</tr>
						<tr>
							<th colspan="2" align="left">Total Item</th>
							<th align="right"><?= $total_item; ?></th>
							<th align="right"><?= number_format($nt->subtotal); ?></th>
						</tr>
						<tr>
							<th colspan="2" align="left">Diskon</th>
							<th></th>
							<th align="right"><?= $nt->diskonsub; ?> %</th>
						</tr>
						<tr>
							<th colspan="2" align="left">Tunai</th>
							<th></th>
							<th align="right"><?= number_format($nt->bayar); ?></th>
						</tr>
						<tr>
							<th colspan="2" align="left">Kembali</th>
							<th></th>
							<th align="right"><?= number_format($nt->bayar - $nt->grandtotal); ?></th>
						</tr>
						<tr>
							<th style="border-top: 1px solid black;" colspan="3" align="left">Grand Total</th>
							<th style="border-top: 1px solid black;" align="right"><?= number_format($nt->grandtotal); ?></th>
						</tr>

					<?php } ?>
				</tfoot>
			</table>
			<p style="text-align: center; font-family: 'Courier New'">Terimakasih telah membeli.</p> <br>
			<p>-</p>
			<!-- <p style="font-size: 6px;"><i>Terbilang : <?php echo terbilang($nt->grandtotal) ?>rupiah</i></p> </br> -->
			<!-- <?php
					echo "<i style='font-size: 7px'>* Tgl Print: " . date("d-m-Y H:i:s") . "</i> </br>";
					if ($n < $total_nota) {
						echo '<p style="page-break-after: always;"></p>';
					}
				} ?> -->
	</div>
</body>

</html>


<style>
	body {
		font-family: 'Times New Roman';
		font-size: 9px;
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

	/* .page-header-pdf {
		padding-top: 0cm;
		padding-left: 0cm;
		padding-right: 0cm
	} */

	.body-print-pdf {
		padding-left: 0.1cm;
		padding-right: 0.1cm;
		padding-top: 0.1cm;
	}

	/* 
	.page-footer-pdf {
		text-align: center;
		position: relative;
		background-repeat: no-repeat;
		background-size: 100% 100%;
		z-index: 1000;
		margin-top: -33px;
	} */

	/* .page-footer-text {
		color: #fff;
		font-size: 12px;
		font-weight: bold;
		padding: 40px 3px 12px 3px
	} */
</style>
