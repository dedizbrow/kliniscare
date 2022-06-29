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
		<!-- <b style="text-align: center;">NOTA</b> -->
		<table width="100%" style="font-family: 'Courier New', Courier, monospace; font-size: 8px;">
			<tr>
				<td align="center"><?= (isset($company_info->nama)) ? $company_info->nama : ''; ?></td>
			</tr>
			<tr>
				<td align="center">
					<pre><?= (isset($company_info->alamat)) ? $company_info->alamat : ''; ?></pre>
				</td>
			</tr>
			<tr>
				<td align="center" style="border-bottom: 1px solid black;"><?= (isset($company_info->telp)) ? $company_info->telp : ''; ?></td>
			</tr>
			<tr>
				<td align="center" style="font-size: 10px; font-family: 'Times New Roman' , Times, serif">
					Nomor Antrian
				</td>
			</tr>
			<tr>
				<td align="center" style="font-size: 30px;border-bottom: 1px solid black; font-family: 'Times New Roman', Times, serif"><b><?= (isset($antrian_info->nomor_antrian)) ? $antrian_info->nomor_antrian : ''; ?></b></td>
			</tr>
			<tr>
				<td align="center" style="font-size: 10px; font-family: 'Times New Roman' , Times, serif">Layanan : <?= (isset($antrian_info->namaPoli)) ? $antrian_info->namaPoli : ''; ?></td>
			</tr>
		</table>
		<p style="text-align: center; font-family: 'Courier New'"><?= dateIndo(date("d-m-Y H:i")) ?> <b><?= date("H:i") ?></b></p>

		<p style="text-align: center; font-family: 'Courier New'">Terimakasih telah mengantri.</p>

	</div>
</body>

</html>


<style>
	body {
		font-family: 'Times New Roman', Times, serif;
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

	.body-print-pdf {
		padding-left: 0.1cm;
		padding-right: 0.1cm;
		padding-top: 0.1cm;
		 height:40mm;
            width:58mm;
            page-break-after:auto;
	}
	@media print{
		.body-print-pdf {
		padding-left: 0.1cm;
		padding-right: 0.1cm;
		padding-top: 0.1cm;
		 height:40mm;
            width:58mm;
            page-break-after:auto;
	}
	}
</style>
