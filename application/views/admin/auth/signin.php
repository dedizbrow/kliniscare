<!DOCTYPE html>
<html lang="en">
<?php
$src_assets_template = "assets/azia-assets";
$src_view_template = "templates/ctc-leftm";
?>
<!-- page-signin.html -->

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?= isset($meta_description) ? $meta_description : ''; ?>">
	<meta name="author" content="<?= isset($meta_author) ? $meta_author : 'kliniscare'; ?>">
	<title><?= isset($page_title) ? $page_title : "SignIn"; ?></title>
	<!-- vendor css -->
	<link href="<?= base_url($src_assets_template . '/lib/font-awesome-4.7.0/css/font-awesome.min.css'); ?>" rel="stylesheet">
	<link href="<?= base_url($src_assets_template . '/lib/lightslider/css/lightslider.min.css'); ?>" rel="stylesheet">
	<!-- azia CSS -->
	<link rel="stylesheet" href="<?= base_url($src_assets_template . '/css/azia.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/ctc.css'); ?>">

</head>

<body class="az-body body-login">
	<div class="az-signin-wrapper bg-login">
		<div class="az-card-signin" style="border-radius: 10px;">
			<div class="az-signin-header">
				<img class="img-fluid" src="<?= base_url(conf('company_logo')); ?>" style="width: 40%; display: block;margin-left: auto;margin-right: auto;"/>
				<h2 class="signin-title text-primary">

					<?= conf('company_name'); ?>

				</h2>

				<!-- <h4 class="text-disabled"><?= lang('label_title_to_signin'); ?></h4> -->
				<?php $redirect = (isset($redirect)) ? "?redirect=$redirect" : "";  ?>
				<form action="<?= base_url('admin/auth/signin' . $redirect); ?>" method="post">
					<div class="form-group">
						<label><b>Username</b></label>
						<input type="text" name="username" class="form-control" placeholder="<?= lang('label_input_username'); ?>" value="" autocomplete="off" required autofocus="">
					</div><!-- form-group -->
					<div class="form-group">
						<label><b>Password</b></label>
						<input type="password" name="password" class="form-control" placeholder="<?= lang('label_input_password'); ?>" autocomplete="off" required>
					</div><!-- form-group -->
					<div class="form-group">
						<div class="alert-error-login"><?php echo $this->session->flashdata("message"); ?></div>
						<?php
						if(isset($error)){
						echo '<div class="alert-error-login mb-2">'.$error.'</div><p>&nbsp;</p>';
						}
						?>
					</div>
					<button class="btn btn-primary btn-block" style="background-color:#5156be"> Login</button>
				</form>
			</div><!-- az-signin-header -->
			<div class="az-signin-footer">
				<p>&nbsp;</p>
				<p>
					<!-- <a class="text-warning" href="#" onclick="javscript:alert('Anda dapat request reset password ke YM Pusat')"><?= lang('label_forget_password'); ?></a> -->
					<!-- <a class="pull-right text-primary" href="<?= base_url('admin/register'); ?>"> Daftar demo klinik</a> -->
					
				</p>
			</div><!-- az-signin-footer -->
		</div><!-- az-card-signin -->
	</div><!-- az-signin-wrapper -->
	<script src="<?= base_url($src_assets_template . '/lib/jquery/jquery.min.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/js/azia.js'); ?>"></script>
	<script>
		$(document)
			.on('keypress', "input", function(e) {
				if (e.keyCode != 13) {
					$(".alert-error-login").html('');
				}
			})
	</script>
</body>
<!-- page-signin.html -->

</html>
