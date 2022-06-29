<?php
$src_assets_template = "assets/azia-assets";
$src_view_template = "templates/ctc-azia-topbar";
$time = (int) rand();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?= lang('meta_description'); ?>">
	<meta name="author" content="<?= lang('meta_author'); ?>">
	<link rel="icon" href="<?= base_url('assets/img/favicon.ico'); ?>" type="image/gif">
	<title>
		<?php if (isset($web_title)) {
			echo $web_title;
		} else if (isset($page_title)) {
			echo $page_title;
		} else {
			echo conf('app_name_short');
		}; ?>
	</title>
	<!-- vendor css -->
	<link href="<?= base_url($src_assets_template . '/lib/font-awesome-4.7.0/css/font-awesome.min.css'); ?>" rel="stylesheet">
	<link href="<?= base_url($src_assets_template . '/lib/lightslider/css/lightslider.min.css'); ?>" rel="stylesheet">
	<link href="<?= base_url($src_assets_template . '/lib/select2/css/select2.min.css'); ?>" rel="stylesheet">
	<!-- azia CSS -->
	<?php if (isset($datatable)) { ?>
		<link rel="stylesheet" href="<?= base_url($src_assets_template . '/lib/datatables.net-dt/css/jquery.dataTables.min.css'); ?>">
	<?php } ?>
	<link rel="stylesheet" href="<?= base_url($src_assets_template . '/css/azia.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/ctc.css?pid=' . $time); ?>">
</head>

<body class="col-lg-8 col-lg-offset-2">
	<div class="az-header">
		<div class="">
			<img src="<?= $header_img; ?>" style="width: 100%;height: auto">
		</div>
	</div>
	<div class="az-content az-content-app ">
		<div class="rows">
			<div class="az-error" style="text-align: left">
				<?php
				if (isset($status) && $status == 'exist') {
				?>
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Informasi Keabsahan Dokumen</h4>
						</div>
						<div class="card-body">
							Dokumen Pemeriksaan ini adalah <b class="text-success">SAH/VALID</b> a/n <b><?=$periksa->nama_pasien;?></b>, nomor: <?=$no_doc;?>.<br>
							Nama tersebut telah melakukan Pemeriksaan/Uji Lab <?=$periksa->jenis_periksa;?> dengan hasil <b><?=$periksa->hasil;?></b>. Dokumen berlaku s/d <?=$masa_berlaku;?>
							
							
						</div>
						<div class="card-footer">
							Catatan: *
							<pre class="text-normal" style="font-family: Arial, Helvetica, sans-serif;font-size: 1em">
							<li><span>Dokumen original dikeluarkan oleh <?= (isset($doc_setting_profile->nama)) ? $doc_setting_profile->nama : ""; ?> Telp. <?= (isset($doc_setting_profile->telp)) ? $doc_setting_profile->telp : ""; ?></span>
							</li>
							</pre>
							<!-- <a href="<?= base_url(); ?>"><?= base_url(); ?></a> -->
						</div>
					</div>
				<?php
				} else {
				?>
					<div class="card">
						<div class="card-header">
							<h4>Informasi Keabsahan Dokumen</h4>
						</div>
						<div class="card-body">
							<table class="display table">
								<tr>
									<td width="100px">No</td>
									<td width="10px">:</td>
									<td><?= $no_doc; ?></td>
								</tr>
								<tr>
									<td>Status</td>
									<td>:</td>
									<td><i class="fa fa-times text-danger" aria-hidden="true"></i> <b>TIDAK SAH/ TIDAK VALID</b></td>
								</tr>
							</table>
							<h2 class="m-lg-2 tx-danger">Data tidak ditemukan</a></h2>
						</div>
						<div class="card-footer valid-footer">
							Catatan: *
							<pre class="text-normal" style="font-family: Arial, Helvetica, sans-serif;font-size: 1em">
							<li><span>Dokumen original dikeluarkan oleh <?= (isset($doc_setting_profile->nama)) ? $doc_setting_profile->nama : ""; ?> Telp. <?= (isset($doc_setting_profile->telp)) ? $doc_setting_profile->telp : ""; ?></span>
							</li>
							</pre>
							<a href="<?= base_url(); ?>"><?= base_url(); ?></a>
						</div>
					</div>
				<?php
				}
				?>

			</div>
		</div>
	</div>

	<script src="<?= base_url($src_assets_template . '/lib/jquery/jquery.min.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/jquery-ui/ui/widgets/datepicker.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/lightslider/js/lightslider.min.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/select2/js/select2.min.js'); ?>"></script>
	<?php if (isset($datatable)) { ?>
		<script src="<?= base_url($src_assets_template . '/lib/datatables.net/js/jquery.dataTables.min.js'); ?>"></script>
		<script src="<?= base_url($src_assets_template . '/lib/datatables.net-dt/js/dataTables.dataTables.min.js'); ?>"></script>
	<?php
	}
	if (isset($chartjs)) { ?>
		<script src="<?= base_url($src_assets_template . '/lib/chart.js/Chart.bundle.min.js'); ?>"></script>
	<?php
	}
	?>
	<script src="<?= base_url($src_assets_template . '/js/azia.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/moment/min/moment.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/jQuery.print.min.js?pid=' . $time); ?>"></script>
	<script src="<?= base_url('assets/pages/bootbox-custom.min.js?pid=' . $time); ?>"></script>
	<script src="<?= base_url('assets/pages/lodash.min.js'); ?>"></script>
	<script src="<?= base_url('assets/pages/ctc.js?pid=' . $time); ?>"></script>
	<script>
		$(function() {
			'use strict'
		});
	</script>
	<div class="az-navbar-backdrop">
	</div>
</body>
</html>
