<!DOCTYPE html>
<html lang="en">
<?php
$src_assets_template = "assets/azia-assets";
$src_view_template = "templates/ctc-leftm";
$time = (int) rand();
?>
<!-- page-signin.html -->

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?= isset($meta_description) ? $meta_description : ''; ?>">
	<meta name="author" content="<?= isset($meta_author) ? $meta_author : 'kliniscare'; ?>">
	<title><?= isset($page_title) ? $page_title : "Sign up"; ?></title>
	<!-- vendor css -->
	<link href="<?= base_url($src_assets_template . '/lib/font-awesome-4.7.0/css/font-awesome.min.css'); ?>" rel="stylesheet">
	<link href="<?= base_url($src_assets_template . '/lib/lightslider/css/lightslider.min.css'); ?>" rel="stylesheet">
	<link href="<?= base_url($src_assets_template . '/lib/select2/css/select2.min.css'); ?>" rel="stylesheet">
	<!-- azia CSS -->
	<link rel="stylesheet" href="<?= base_url($src_assets_template . '/css/azia.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/ctc.css'); ?>">

</head>

<body class="az-body">
	<div class="az-signin-wrapper bg-login">
		<div class="az-card-signin">
			<div class="az-signin-header">
				<h2 class="signin-title text-primary">
					<img src="<?= base_url(conf('company_logo')); ?>" />
					<?= conf('company_name'); ?>
				</h2>
				<h4 class="text-disabled"></h4>
				<?php $redirect = (isset($redirect)) ? "?redirect=$redirect" : "";  ?>
				<form action="#" method="post" name="form-register">

					<div class="form-group">
						<label class="form-label">Nama Pengguna</label>
						<input type="text" name="name" class="form-control input-sm" placeholder="Masukan nama pengguna" value="" autocomplete="off" required>
					</div>
					<div class="form-group">
						<label class="form-label">Email</label>
						<input type="text" name="email" class="form-control input-sm" placeholder="Masukan email" value="" autocomplete="off" required>
					</div>
					<div class="form-group">
						<label class="form-label">Username</label>
						<input type="text" name="username" class="form-control input-sm" placeholder="Masukan username" value="" autocomplete="off" required>
					</div>
					<div class="form-group">
						<label class="form-label">Nomor Telp</label>
						<input type="text" name="no_telp" class="form-control input-sm" placeholder="Masukan nomor Telp" value="" autocomplete="off" required>
					</div>
					<div class="form-group">
						<label class="form-label">Nama Klinik</label>
						<input type="text" name="clinic_name" class="form-control input-sm" placeholder="Masukan nama Klinik" value="" autocomplete="off" required>
					</div>
					<div class="form-group">
						<label class="form-label">Password</label>
						<input type="password" name="password" class="form-control input-sm" placeholder="Masukan password" value="" autocomplete="off" required>
					</div>
					<div class="form-group">
						<label class="form-label">Konfirmasi Password</label>
						<input type="password" name="repassword" class="form-control input-sm" placeholder="Ulangi password" value="" autocomplete="off" required>
					</div>
					<!-- <hr class="m-2">
						<div class="form-group">
              <label>Username</label>
              <input type="text" name="username" class="form-control input-sm" placeholder="<?= lang('label_input_username'); ?>" value="" autocomplete="off" required autofocus="">
            </div>
            <div class="form-group">
              <label>Password</label>
              <input type="password" name="password" class="form-control input-sm" placeholder="<?= lang('label_input_password'); ?>" autocomplete="off" required>
            </div>
						<div class="form-group">
              <label>Ulangi Password</label>
              <input type="password" name="repassword" class="form-control input-sm" placeholder="Ulangi Password" autocomplete="off" required>
            </div>
            <div class="form-group">
              <div class="alert-error-login"><?php echo $this->session->flashdata("message"); ?></div>
            </div> -->
					<button type="submit" id="submit_register" class="btn btn-primary btn-block"><i class="fa fa-sign-in"></i> Daftar Sekarang</button>
				</form>
			</div><!-- az-signin-header -->
			<div class="az-signin-footer">
				<p>&nbsp;</p>
				<p">
					Sudah memiliki akun? Silahkan <a class=" text-primary" href="<?= base_url('admin/auth'); ?>">Masuk</a>
					<input type="hidden" id="base_url" value="<?= base_url(); ?>">
					</p>
			</div><!-- az-signin-footer -->
		</div><!-- az-card-signin -->
	</div><!-- az-signin-wrapper -->

	<div id="modal-reg-success" class="modal">
		<div class="modal-dialog" role="document">
			<form method="POST" name="form-manage-klinik">
				<div class="modal-content modal-content-demo">
					<div class="modal-header bg-success">
						<h6 class="modal-title tx-white">Registrasi Sukses</h6>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="printarea center-position label-data-pasien" style="border: 1px solid #000;">
							<table width="100%" style="font-size: 14px;padding: 10px;font-weight: bold;width: 400px;">
								<tr class="text-bold" style="font-weight: bold;font-size: 18px">
									<td width="100px">ID</td>
									<td width="10px">:</td>
									<td class="id_pasien"></td>
								</tr>
								<tr class="text-bold">
									<td>Nama</td>
									<td>:</td>
									<td class="nama_lengkap"></td>
								</tr>
								<tr class="text-bold">
									<td>Jenis Kelamin</td>
									<td>:</td>
									<td class="jenis_kelamin"></td>
								</tr>
								<tr>
									<td>Tgl. Reg</td>
									<td>:</td>
									<td class="tgl_reg"></td>
								</tr>
								<tr>
									<td>Provider</td>
									<td>:</td>
									<td class="provider"></td>
								</tr>
								<tr>
									<td>Pemeriksaan</td>
									<td>:</td>
									<td class="jenis_pemeriksaan"></td>
								</tr>
							</table>

						</div>
						<p class="no-print text-primary"><i>* Print, photo dan/atau catat data registrasi anda</i></p>
					</div>
					<div class="modal-footer tx-left" style="justify-content: flex-start">
						<button type="button" class="btn btn-print btn-xs btn-default" data-title_print="Print Kode Registrasi" data-printarea=".printarea.label-data-pasien"><i class="fa fa-print"></i> Print</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<script src="<?= base_url($src_assets_template . '/lib/jquery/jquery.min.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/jquery-ui/ui/widgets/datepicker.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/select2/js/select2.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/jQuery.print.min.js?pid=' . $time); ?>"></script>
	<script src="<?= base_url('assets/pages/bootbox-custom.min.js?pid=' . $time); ?>"></script>
	<script src="<?= base_url('assets/pages/lodash.min.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/js/azia.js'); ?>"></script>
	<script src="<?= base_url('assets/pages/ctc.js?pid=' . $time); ?>"></script>
	<script>
		$(document)
			.on('keypress', "input", function(e) {
				if (e.keyCode != 13) {
					$(".alert-error-login").html('');
				}
			})
			.on("click", "#submit_register", function(e) {
				e.preventDefault()
				var form = $(this).closest('form').serialize();
				http_request('admin/register/save_user', 'POST', form)
					.done(function(res) {
						Msg.success(res.message);
						setTimeout(function() {
							// window.open(res.link,'_blank')
							location.href = base_url('admin/auth')
						}, 500)


						$("#submit_register").removeAttr('disabled');
					})
					.fail(function() {
						$("#submit_register").removeAttr('disabled');
					})
					.always(function() {
						$("#submit_register").removeAttr('disabled');
					})
			})
			.on("click", ".btn-print", function() {
				var printarea = $(this).data("printarea");
				$(printarea).print({
					globalStyles: true,
					mediaPrint: true,
					stylesheet: null,
					noPrintSelector: ".no-print",
					iframe: true,
					append: null,
					prepend: null,
					manuallyCopyFormValues: true,
					deferred: $.Deferred(),
					timeout: 750,
					title: $(this).data("title-print"),
					doctype: '<!doctype html>'
				});
			})
		$("#search_provider").select2({
			minimumInputLength: 0,
			allowClear: false,
			multiple: false,
			placeholder: 'click untuk mencari',
			ajax: {
				url: base_url('auth/search-provider-select2-'),
				headers: {
					'x-user-agent': 'ctc-webapi'
				},
				data: function(params) {
					return {
						search: params.term
					}
				},
				processResults: function(data) {
					return {
						results: data
					};
				}
			},
		})
		$("#search_jenis_pemeriksaan").select2({
			minimumInputLength: 0,
			allowClear: false,
			multiple: false,
			placeholder: 'click untuk mencari',
			ajax: {
				url: base_url('auth/search-jenis-pemeriksaan-select2-'),
				headers: {
					'x-user-agent': 'ctc-webapi'
				},
				data: function(params) {
					return {
						search: params.term
					}
				},
				processResults: function(data) {
					return {
						results: data
					};
				}
			},
		})
		$('[name="tgl_lahir"]').datepicker({
			changeMonth: true,
			changeYear: true,
			showOtherMonths: true,
			selectOtherMonths: true,
			dateFormat: 'yy-mm-dd',
			reverseYearRange: true,
			yearRange: 'c-80:c',
			container: '#modal-manage-pasien'
		});
	</script>
</body>
<!-- page-signin.html -->

</html>

<style>

</style>
