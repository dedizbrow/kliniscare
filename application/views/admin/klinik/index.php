<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<div class="card-header border c-header">
				<div class="card-title">
					<?=lang('label_title_page_klinik');?>
				</div>

				<div class="pull-right">
					<?php if (isAllowed('c-klinik^create', true)) { ?>
						<button type="button" class="btn btn-primary add-klinik btn-xs"><i class="fa fa-plus"></i></button>
					<?php } ?>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-striped minimize-padding-all table-bordered" id="dataKlinik" width="100%">
					<thead>
						<tr>
							<th><?= lang('label_number'); ?></th>
							<th><?= lang('label_code'); ?></th>
							<th><?= lang('label_name'); ?></th>
							<th><?= lang('label_logo'); ?></th>
							<th><?= lang('label_reg_date'); ?></th>
							<th><?= lang('label_status'); ?></th>
							<th><?= lang('label_account_type'); ?></th>
							<th><?= lang('label_license'); ?></th>
							<th><?= lang('label_end_license'); ?></th>
							<th><?= lang('label_phone'); ?></th>
							<th><?= lang('label_email'); ?></th>
							<th><?= lang('label_remarks'); ?></th>
							<th><?= lang('label_timestamp'); ?></th>
							<th><?= lang('label_action'); ?></th>
							<th><?= lang('label_license_status'); ?></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
</div>


<div id="modal-manage-klinik" class="modal">
	<div class="modal-dialog modal-lg" role="document">
		<form method="POST" name="form-manage-klinik">
			<div class="modal-content">
				<div class="modal-header">
					<h6 class="modal-title">Add/Update Data</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<div class="row">
								<div class="col-12">
									<div class="form-group">
										<label class="form-label"><?= lang('label_enter_code'); ?></label>
										<input type="text" name="clinic_code" class="form-control input-sm" required="" placeholder="<?= lang('label_enter_code'); ?>" autocomplete="off">
									</div>
								</div>
						</div>
						<div class="row">
							<div class="col-12">
								<div class="form-group">
									<label class="form-label"><?= lang('label_enter_name'); ?></label>
									<input type="text" name="clinic_name" class="form-control input-sm" required="" placeholder="<?= lang('label_enter_name'); ?>" autocomplete="off">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-6">
								<div class="form-group">
									<label class="form-label"><?= lang('label_account_type'); ?></label>
									<div class="btn-group">
										<label class="btn btn-xs btn-success"><input type="radio" name="account_type" value="production"> Production</label>
										<label class="btn btn-xs btn-warning"><input type="radio" name="account_type" value="demo"> Demo</label>
									</div>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group">
									<label class="form-label"><?= lang('label_license'); ?></label>
									<div class="input-group">
										<input type="number" class="form-control input-sm" name="license_duration" id="license_duration" value="1">
										<select class="form-control input-sm" name="license_type" id="license_type">
											<option value="day"><?=lang('label_day');?></option>
											<option value="month"><?=lang('label_month');?></option>
											<option value="year"><?=lang('label_year');?></option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-6">
								<div class="form-group">
									<label class="form-label"><?= lang('label_phone'); ?></label>
									<input type="text" name="phone" class="form-control input-sm" placeholder="<?= lang('label_phone'); ?>" autocomplete="off">
								</div>
							</div>
							<div class="col-6">
								<div class="form-group">
									<label class="form-label"><?= lang('label_email'); ?></label>
									<input type="text" name="email" class="form-control input-sm" placeholder="<?= lang('label_email'); ?>" autocomplete="off">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<div class="form-group">
									<label class="form-label"><?= lang('label_remarks'); ?></label>
									<textarea name="remarks" class="form-control input-sm" placeholder="<?= lang('label_remarks'); ?>" autocomplete="off"></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 border-left-dashed">
						<h5>Enabled Menus:</h5>
						<?php
						if (isset($enabled_menus)) {
							$row = "<ul class='setting_menu'>";
							foreach ($enabled_menus as $base) {
								$row .= "<li><label class='ckbox'>
								<input type='checkbox' name='enabled_menus[]' autocomplete='off' value='" . $base->id . "' class='accessibility' > <span> " . $base->title . "</span></label>";
								$row .= "</li>";
							}
							$row .= "</ul>";
							echo $row;
						}
						?>
					</div>
					<input type="text" class="hide" name="_id" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-klinik" class="btn btn-primary"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->
<style>
	.settinh
</style>
