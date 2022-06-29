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
		</table>
		<table width="100%">
			<tr>
				<td align="left" width="30%">
					<pre>Nama </pre>
				</td>
				<td width="10%">:</td>
				<td align="left" width="60%">
					<pre><?= (isset($pasien->nama_lengkap)) ? $pasien->nama_lengkap : ''; ?></pre>
				</td>
			</tr>
			<tr>
				<td align="left">
					<pre>NIK </pre>
				</td>
				<td>:</td>
				<td align="left">
					<pre><?= (isset($pasien->no_identitas)) ? $pasien->no_identitas : ''; ?></pre>
				</td>
			</tr>
			<tr>
				<td align="left">
					<pre>Tanggal Lahir </pre>
				</td>
				<td>:</td>
				<td align="left">
					<pre><?= (isset($pasien->tgl_lahir)) ? $pasien->tgl_lahir : ''; ?></pre>
				</td>
			</tr>
			<tr>
				<td align="left">
					<pre>Alamat </pre>
				</td>
				<td>:</td>
				<td align="left">
					<pre><?= (isset($pasien->alamat)) ? $pasien->alamat : ''; ?> <?= (isset($pasien->nama_kecamatan)) ? $pasien->nama_kecamatan : ''; ?><?= (isset($pasien->nama_kabupaten)) ? $pasien->nama_kabupaten : ''; ?><?= (isset($pasien->nama_provinsi)) ? $pasien->nama_provinsi : ''; ?></pre>
				</td>
			</tr>

		</table>
	</div>
</body>

</html>


<style>
	body {
		font-family: 'Times New Roman', Times, serif;
		font-size: 10px;
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
		padding-left: 0.1cm;
		padding-right: 0.1cm;
		padding-top: 0.1cm;
	}
</style>
