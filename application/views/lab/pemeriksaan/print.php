<!DOCTYPE html>
<html>

<head>
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta charset="utf-8">
	<title><?= (isset($page_title)) ? $page_title : ''; ?></title>
	<link rel="icon" href="<?= base_url('assets/img/favicon.ico'); ?>" type="image/gif">
</head>

<body>
	<div style="row-banner">

	</div>
	<div class="body-print-pdf">
		<div class="table-inline">
			<br>
			<br>
			<h2 style="text-align:center"><u>HASIL PEMERIKSAAN</u></h2>
            <br>

			<table cellspacing=0 class="dataTable minimize-padding-all">
				<tr>
					<td width="351px" style="text-align:left; font-size:12px">
						<b></b><br>
					</td>
					<!-- 2nd col -->
					<td width="352px" style="text-align:right; font-size:12px">
						<b>Penanggung Jawab : <?= (isset($data_periksa->dokter)) ? $data_periksa->dokter : ''; ?></b>
					</td>
				</tr>
			</table>
			<hr style="height:2.7px;border:none;color:#333;background-color:#333; margin-top: 0px;padding-top: 0px;" />
			<table class="dataTable minimize-padding-all table-desc v-center table-striped" width="100%">
				<tr>
					<td width="100px">
						<b>NO. Reg</b><br>
					</td>
					<td width="10px">:</td>
					<td width="215px">
						<?= (isset($data_periksa->no_test)) ? $data_periksa->no_test : ''; ?>
					</td>
					<!-- 2nd col -->
					<td width="40px"></td>
					<td><b>Tanggal Reg</b><br>
					</td>
					<td>:</td>
					<td>
						<?= (isset($data_periksa->tgl_periksa)) ? $data_periksa->tgl_periksa : ''; ?>
					</td>
				</tr>
				<tr>
					<td>
						<b>Nama Pasien</b><br>
					</td>
					<td>:</td>
					<td><?= (isset($data_periksa->nama_pasien)) ? $data_periksa->nama_pasien : ''; ?></td>
					<td></td>
					<td>
						<b>PID	</b><br>
					</td>
					<td>:</td>
					<td>126229 (RM PASIEN)</td>
				</tr>
				<tr>
					<td>
						<b>Pengirim</b><br>
					</td>
					<td>:</td>
					<td class="tgl_lahir">-</td>
					<td></td>
					<td>
						<b>Jenis Kelamin</b><br>
					</td>
					<td>:</td>
					<td>
						<?php
						if (isset($data_periksa->jenis_kelamin)) {
							echo ucwords($data_periksa->jenis_kelamin);
							echo '<br>';
							echo ($data_periksa->jenis_kelamin == 'Perempuan') ? '' : '';
						}
						?>
					</td>
				</tr>
				<tr>
					<td><b>Kel. Pelanggan</b><br>
					</td>
					<td>:</td>
					<td>
						Pasien Mandiri
					</td>
					<td></td>
					<td><b>Tgl. Lahir / Usia</b><br>
					</td>
					<td>:</td>
					<td>
						<?php 

						$tgl_lahir = (isset($data_periksa->tgl_lahir)) ? $data_periksa->tgl_lahir : '';
						$lahir = new DateTime($tgl_lahir);
						$hari_ini = new DateTime();

						$diff = $hari_ini->diff($lahir);

						?>
						<?= (isset($data_periksa->tgl_lahir)) ? $data_periksa->tgl_lahir : ''; echo " / ". $diff->y ." Tahun"; echo " ". $diff->m ." Bulan";echo " ". $diff->d ." Hari";?></td>
					</tr>
					<tr>
						<td><b>Alamat</b><br>
						</td>
						<td>:</td>
						<td class="alamat"><?= (isset($data_periksa->alamat)) ? $data_periksa->alamat : ''; ?></td>
						<td></td>
						<td><b>No. TLP/HP</b><br>
						</td>
						<td>:</td>

						<td><?= (isset($data_periksa->no_hp)) ? $data_periksa->no_hp : ''; ?></td>
						<?php if (conf('lab_enable_select_provider') === TRUE) { ?>
							<td><b>Provider</b><br>
							</td>
							<td>:</td>
							<td><?= (isset($data_periksa->provider)) ? $data_periksa->provider : ''; ?></td>
						<?php } ?>
					</tr>

					<tr>
						<td><b>No. Identitas</b><br>
						</td>
						<td>:</td>
						<td><?= (isset($data_periksa->no_identitas)) ? $data_periksa->no_identitas : ''; ?></td>
						<td></td>
						<td><b>Masa Berlaku</b><br>
						</td>
						<td>:</td>

						<td><?= (isset($data_periksa->masa_berlaku)) ? $data_periksa->masa_berlaku : ''; ?> Hari</td>
					</tr>

					<?php if (conf('lab_enable_select_provider') === TRUE) { ?>
						<tr>
							<td></td>
							<td>:</td>
							<td class=""></td>
							<td></td>
							<td><b></b>
							</td>
							<td></td>
							<td></td>

							<td><b>Provider</b><br>
							</td>
							<td>:</td>
							<td><?= (isset($data_periksa->provider)) ? $data_periksa->provider : ''; ?></td>

						</tr>
					<?php } ?>
				</table>
			</div>
			<p class="table-hasil">
				<?php if ($category == "covid") { ?>
					<table id="tableJenisPeriksa" cellspacing=0 class="dataTable minimize-padding-all v-center table-detail table-bordered table-striped with-border" width="100%">
						<thead>
							<tr>
								<th>
									<b>JENIS PEMERIKSAAN</b><br>
								</th>
								<th>
									<b>HASIL</b><br>
								</th>
								<th><b>NILAI RUJUKAN</b><br>
								</th>
								<th><b>METODE</b><br>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$hasil_periksa_english = "";
							$nilai_rujukan_english = "";
							if (isset($detail_periksa)) {
								if (!empty($detail_periksa)) {
									foreach ($detail_periksa as $i => $dt) {
										$hasil = ($dt->hasil == null) ? "BELUM ADA" : $dt->hasil;
										$hasil_english = "";
										if ($hasil == 'Positif') {
											$hasil_english = "<br><i>Positive</i>";
										} else
										if ($hasil == 'Negatif') {
											$hasil_english = "<br><i>Negative</i>";
										} else
										if ($hasil == 'Reaktif') {
											$hasil_english = "<br><i>Reactive</i>";
										} else
										if ($hasil == 'Non Reaktif') {
											$hasil_english = "<br><i>Non Reactive</i>";
										}
										$nilai_english = "";
										$nil_rujukan = $dt->nilai_rujukan;
										if ($nil_rujukan == 'Positif') {
											$nilai_english = "<br><i>Positive</i>";
										} else
										if ($nil_rujukan == 'Negatif') {
											$nilai_english = "<br><i>Negative</i>";
										} else
										if ($nil_rujukan == 'Reaktif') {
											$nilai_english = "<br><i>Reactive</i>";
										} else
										if ($nil_rujukan == 'Non Reaktif') {
											$nilai_english = "<br><i>Non Reactive</i>";
										}
										if ($i == 0) {
											$hasil_periksa_english = str_replace("<br>", "", $hasil_english);
											$nilai_rujukan_english = str_replace("<br>", "", $nilai_english);
										}
										print_r($dt);
										$metode = ($i == 0) ? $data_periksa->metode : "";
										echo "<tr>
										<td>" . $dt->nama_pemeriksaan . "</td>
										<td align='center'>" . $hasil . "" . $hasil_english . "</td>
										<td align='center'>" . $nil_rujukan . "" . $nilai_english . "</td>
										<td>" . $metode . "</td>
										</tr>";
									}
								} else {
									echo '<tr><td colspan="4">HASIL BELUM KELUAR</td></tr>';
								}
							}
							?>
						</tbody>
					</table>
	<?php } else { // umum 
		?>
		<div class="row">
			<p><b style="font-size: 14px;"><?= $data_periksa->jenis_pemeriksaan; ?></b></p>
			<table cellspacing=0 class="dataTable minimize-padding-all v-center table-detail table-bordered table-striped with-border" width="100%">
				<thead>
					<tr>
						<th width="35px"><b>N0</b></th>
						<th align="center"><b>ITEM PEMERIKSAAN</b></th>
						<th align="center"><b>HASIL</b></th>
						<th align="center"><b>NILAI RUJUKAN</b></th>
						<th align="center"><b>SATUAN</b></th>
						<th align="center"><b>METODE</b></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($detail_periksa as $k => $item) {
						$dt = (object) $item;
						?>
						<tr>
							<td align="center"><?= $no; ?></td>
							<td><b><?= $dt->item; ?></b></td>
							<td><?= $dt->hasil; ?></td>
							<td>
								<pre style="display: block;white-space: pre-line;"><?= $dt->nilai_rujukan; ?></pre>
							</td>
							<td><?= $dt->satuan; ?></td>
							<td><?= $dt->satuan; ?></td>
						</tr>
						<?php
						$no++;
						if (isset($dt->sub)) {
							foreach ($dt->sub as $s => $subitem) {
								$sub = (object) $subitem;
								?>
								<tr>
									<td></td>
									<td style="padding-left: 20px"><?= $sub->item; ?></td>
									<td><?= (isset($sub->hasil)) ? $sub->hasil : ""; ?></td>
									<td>
										<pre style="display: block;white-space: pre-line;"><?= $sub->nilai_rujukan; ?></pre>
									</td>
									<td><?= $sub->satuan; ?></td>
								</tr>

								<!-- END SUB ROW ITEM PERIKSA -->
								<?php
							} // end for each subitem
						} // end for each iteme
					} ?>
				</tbody>
			</table>

		</div>
	<?php } ?>
</p>


<div class="p-notes" style="margin-top: 10px; margin-bottom: 0px; ">
		<ul class="notes">
		<li><span>Scan QRCode dan klik link didalamnya untuk memastikan bahwa dokumen ini benar dikeluarkan oleh <?= (isset($doc_setting_profile->nama)) ? $doc_setting_profile->nama : ""; ?></span>
			<br><i>Scan the QR Code and click the link in it to ensure that this document was issued by <?= (isset($doc_setting_profile->nama)) ? $doc_setting_profile->nama : ""; ?></i>
		</li>
		
		</ul>
	<?php
	if (isset($data_notes)) {
		echo ' 
		<ul class="notes" style="margin-top: 0;margin-bottom: 25px">';
		foreach ($data_notes as $dt) {
			echo "<li style='text-align: justify'>
			<span>" . $dt->notes;
			if (isset($dt->english)) {
				echo "
				<br><i>" . $dt->english . "</i>";
			}
			echo "
			</li>";
		}
		echo '</ul>';
	}
	?>
</div>

<table width="100%">
	<tr>
		<td width="67%" style="vertical-align: bottom">
			<div class="text-center">
				<?php
				if (isset($detail_periksa)) {
					if (!empty($detail_periksa) && isset($image_qr) && $image_qr != "") {
						echo '<img src="' . $image_qr . '" class="image-ttd" width="100px">';
						echo "<br><span style='font-weight: bold;margin-top: 4px'>No. " . substr($code,10) . "</span>";
					}
				}

				?>
			</div>
		</td>
		<td>
			<div class="" style="position: relative">
				<?php
				if (isset($detail_periksa)) {
					if (!empty($detail_periksa)) { ?>
						<?= (isset($doc_setting_profile->kabupaten)) ? ucwords($doc_setting_profile->kabupaten) : ""; ?>, <?= dateIndo($data_periksa->update_hasil_at); ?><br>
						Pemeriksa<br>
						<?php
						if (isset($ttd_hasil)) {
							echo '<img src="' . $ttd_hasil . '" style="max-width: 150px" class="image-ttd">';
						}
					}
				}
				?>
			</div>

		</td>
	</tr>
	<tr>
		<td colspan="2"></td>
	</tr>

</table>
<br>
<p style="margin-left: 6px;">Jenis & Waktu Pengambilan Spesimen : <?php
if (isset($data_periksa->nama_sample)) {
	echo $data_periksa->nama_sample;
}
?> / 
<?= (isset($data_periksa->tgl_sampling)) ? $data_periksa->tgl_sampling : ''; ?> <?= (isset($data_periksa->jam_sampling)) ? $data_periksa->jam_sampling : ''; ?>
</p>
	<?php if (conf('lab_print_surat_keterangan') === TRUE) { ?>
		<p style="page-break-before: always;" class="suket">
			<p class="text-center" style="text-align: center;font-size: 12px"><b>SURAT KETERANGAN</b>
				<br><i>Medical Information Letter</i>
			</p>
			<p>
				Yang bertanda tangan di bawah ini, dokter menerangkan bahwa:<br>
				<i>To Whom it may concern, the Doctor explains that</i>:
			</p>
			<table width="100%">
				<tr>
					<td width="30%">Nama/<i>Name</i></td>
					<td width="5%">:</td>
					<td><b><?= (isset($data_periksa->nama_pasien)) ? $data_periksa->nama_pasien : ''; ?></b></td>
				</tr>
				<tr>
					<td width="30%">Tanggal Lahir/<i>Date of Birth</i></td>
					<td width="5%">:</td>
					<td><?= (isset($data_periksa->tgl_lahir)) ? $data_periksa->tgl_lahir : ''; ?></td>
				</tr>
				<tr>
					<td width="30%">Alamat/<i>Address</i></td>
					<td width="5%">:</td>
					<td><?= (isset($data_periksa->alamat)) ? $data_periksa->alamat : ''; ?></td>
				</tr>
				<tr>
					<td width="30%">No Identitas/<i>Identity No</i></td>
					<td width="5%">:</td>
					<td><?= (isset($data_periksa->no_identitas)) ? $data_periksa->no_identitas : ''; ?></td>
				</tr>
				<tr>
					<td width="30%">Kewarganegaraan/<i>Nationality</i></td>
					<td width="5%">:</td>
					<td><?= (isset($data_periksa->kewarganegaraan)) ? $data_periksa->kewarganegaraan : ''; ?></td>
				</tr>
			</table>
			<p>Telah dilakukan pemeriksaan <b><?= $detail_periksa[0]->nama_pemeriksaan; ?></b> dengan hasil <b><?= $detail_periksa[0]->hasil; ?></b>. Surat keterangan ini hanya berlaku <?= $masa_berlaku_indo; ?> sejak tanggal pemeriksaan.
				Terlampir hasil pemeriksaan <b><?= $detail_periksa[0]->nama_pemeriksaan; ?></b> yang merupakan bagian yang tidak terpisahkan dari surat keterangan ini.
			</p>
			<p>
				Demikian surat keterangan ini dibuat dengan sebenarnya dan mohon dipergunakan sebagaimana mestinya.
			</p>
			<p style="font-style: italic">
				<b><?= $detail_periksa[0]->nama_pemeriksaan; ?></b> examination has been carried out with result <b><?= $hasil_periksa_english; ?></b>. This certificate is only valid for <?= $masa_berlaku_english; ?> since the results came out (the result is attached).
			</p>
			<p style="font-style: italic">
				This statement is thus made properly and please use as appropriate
			</p>

			<p></p>
			<p style="text-align: center">
				Tanggal/<i>Date</i>: <?= date("d-m-Y", strtotime($data_periksa->update_hasil_at)); ?><br>
				Waktu surat dikeluarkan/<i>Issued time of letter</i>: <?= date("H:i", strtotime($data_periksa->update_hasil_at)); ?> WIB<br>
				Dokter Pemeriksa/<i>Doctor Incharge</i><br>
				<p></p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<?php
				if (isset($logo_ttd)) {
					echo '<img src="' . $logo_ttd_suket . '" style="" class="image-ttd2">';
				}
				?>
				<div class="doc-ttd2"><?= (isset($data_periksa->dokter)) ? $data_periksa->dokter : ''; ?></div>
			</p>
	<?php } // end print surat keterangan 
	?>

</div>

</body>

</html>
<style>
	p.suket {
		text-align: justify;
		text-justify: inter-word;
	}

	.image-ttd2 {
		z-index: 1;
		width: 200px;
		margin-left: 300px;
		margin-top: -70px;
		position: absolute;
		top: 0px;
		left: 50px;
	}

	div.doc-ttd2 {
		z-index: 2;
		text-align: center;
		font-weight: bold;
	}

	.suket-ttd {
		background-image: url('<?= $logo_ttd_suket; ?>');
		background-repeat: no-repeat;
		height: 200px;
		vertical-align: bottom;
		background-size: 570px 350px;
		background-position: center;
	}

	body {
		font-family: arial-narrow;
		font-size: 10px
	}

	.row-banner {
		border: 1px solid #ff0000;
	}

	/* pdf print setting */
	.row-title-page {
		margin-bottom: 20px;
	}

	table tr td {
		vertical-align: top;
	}

	table tr td {
		font-size: 10px;
		font-family: 'arial-narrow';
		line-height: 1.2
	}

	table.table-desc {
		line-height: 1.8
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

	.page-footer-pdf_old {
		text-align: center;
		position: relative;
		background-image: url(<?= base_url('assets/img/ym-doc-footer.png'); ?>);
		background-repeat: no-repeat;
		background-size: 100% 100%;
		z-index: 1000;
		margin-top: -33px;
	}

	.textLayer span {
		z-index: 5000
	}

	.page-footer-pdf {}

	.page-footer-pdf .page-footer-text {
		z-index: 0;
		position: absolute;
		border: 1px solid #000000;
		text-align: center;
	}

	.page-footer-text {
		color: #fff;
		font-size: 12px;
		font-weight: bold;
		padding: 40px 3px 12px 3px
	}

	/* end pdf print setting */
	.image-ttd {
		position: relative;

	}

	.row-print {
		width: 100%;
		margin: 10px;
		margin-top: 10px;
		display: block;
	}

	.col-lg-6 {
		border: 1px solid #ff0000;
		width: 45%;
		padding: 0;
		margin: 0;
		vertical-align: top;
	}

	.row-print table {
		width: 100%;
		display: table;
	}

	.table-detail {}

	.table-detail thead tr {
		background: #ccc;
	}

	.table-detail tr th {
		font-weight: normal;
		font-size: 10px;
		font-family: 'arial-narrow';
		line-height: 1.2;
	}

	.table-detail tr th,
	.table-detail tr td {
		padding: 5px 10px;
		border: 0.2px solid #000;
	}

	.pull-right {
		float: right;
	}

	.ttd {
		width: 200px;
	}

	ul.desc {
		padding-left: 20px
	}

	ul.desc li {
		text-align: justify;
		font-size: 10px;
		font-family: 'arial-narrow'
	}

	ul.notes li {
		font-size: 10px;
		font-family: 'arial-narrow'
	}
</style>
