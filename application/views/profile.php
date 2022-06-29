<div class="az-profile-overview">
	<div class="az-img-user">
		<img src="<?= (isset($user->profile) && $user->profile != "") ? base_url('files/imgs/' . $user->profile) : base_url('assets/img/img1.jpg'); ?>" alt="No Image" height="80px">
	</div><!-- az-img-user -->
	<div class="d-flex justify-content-between mg-b-20">
		<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<form method="post" name="form-profile">
				<div class="row">
					<div class="col-sm-5">
						<div class="form-group">
							<label class="form-label"><?= lang('label_name'); ?></label>
							<input type="text" class="hide" name="user_id" value="">
							<input type="text" name="name" class="form-control input-sm" required="" value="<?= $user->name; ?>" placeholder="<?= lang('label_name'); ?>" autocomplete="off">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-5">
						<div class="form-group">
							<label class="form-label"><?= lang('label_username'); ?></label>
							<input type="text" name="username" class="form-control no-space input-sm" required="" value="<?= $user->username; ?>" placeholder="<?= lang('label_username'); ?>" autocomplete="off">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-5">
						<div class="form-group">
							<label class="form-label"><?= lang('label_email'); ?></label>
							<input type="text" name="email" class="form-control input-sm" required="" value="<?= $user->email; ?>" placeholder="<?= lang('label_email'); ?>" autocomplete="off">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-5">
						<div class="form-group">
							<label class="form-label"><?= lang('label_password'); ?></label>
							<input type="password" name="password" class="form-control input-sm" required="" placeholder="<?= lang('label_password'); ?>" autocomplete="off">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-5">
						<div class="form-group">
							<label class="form-label"><?= lang('label_repassword'); ?></label>
							<input type="password" name="repassword" class="form-control input-sm" required="" placeholder="<?= lang('label_repassword'); ?>" autocomplete="off">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-5">
						<sup class="show-on-update text-danger"><?= lang('label_is_change_password'); ?></sup>
					</div>
				</div>
				<div class="row">
					<p></p>
				</div>
				<div class="row">
					<div class="col-sm-5">
						<div class="form-groups">
							<button type="button" class="btn btn-primary" id="submit_profile"><i class="fa fa-save"></i> Simpan</button>
						</div>
					</div>
				</div>
			</form>
		</section>
	</div>
	<div class="az-profile-bio">

	</div>
</div>