<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<div class="card-header border c-header">
				<div class="card-title">
					Karyawan
					<?= lang('label_list_patient'); ?>
					<?php
					if (isset($import_id)) {
						echo "<input type='hidden' id='import_id' value='" . $import_id . "'><br>
							<div class='tx-primary txt-normal mt-2'>[Data yang ditampilkan adalah yang baru saja anda import] 
							<a class='text-normal tx-danger' href='" . base_url('karyawan') . "' title='Click disini untuk kembali ke keseluruhan data karyawan'><sup><i class=''></i> clear filter</sup></a>
							</div>
							
							";
					}
					?>
				</div>

				<div class="pull-right">
					<?php if (isAllowed('c-karyawan^create', true)) { ?>
						<button type="button" class="btn btn-success add-karyawan btn-xs"><i class="fa fa-plus"></i> Tambah Data</button>
						<button type="button" class="btn btn-import import__ btn-xs"><i class="fa fa-cloud-upload"></i> <span>Import</span> </button>
					<?php } ?>
					<a href="<?php echo site_url(); ?>master-data/karyawan/export_"><button type="button" class="btn btn-warning btn-xs"><i class="fa fa-cloud-upload"></i> <span>Export</span> </button></a>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-bordered table-striped minimize-padding-all" id="dataKaryawan" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%" cellspacing="0">
					<thead>
						<tr role="row">
							<th>No.</th>
							<th><?= lang('label_code'); ?></th>
							<th><?= lang('label_name'); ?></th>
							<th><?= lang('label_address'); ?></th>
							<th><?= lang('label_telp_number'); ?></th>
							<th><?= lang('label_email'); ?></th>
							<th><?= lang('label_division'); ?></th>
							<th><?= lang('label_action'); ?></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
</div>

<div id="modal-manage-karyawan" class="modal">
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-karyawan">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Add/Update Data</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_code'); ?><span class="tx-danger"> *</span></label>
								<input type="text" class="form-control input-sm" name="kodeKaryawan" autocomplete="off" id="kodeKaryawan" placeholder="<?= lang('label_code'); ?>">
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_name'); ?><span class="tx-danger"> *</span></label>
								<input type="text" class="form-control input-sm" name="namaKaryawan" autocomplete="off" id="namaKaryawan" placeholder="<?= lang('label_name'); ?>">
							</div>
						</div>
					</div>
					<div class="row">

						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_address'); ?></label>
								<textarea class="form-control" rows="2" cols="100" name="alamat" autocomplete="off" id="alamat" placeholder="<?= lang('label_address'); ?>"></textarea>

							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_email'); ?><span class="tx-danger"> *</span></label>
								<input type="text" class="form-control input-sm" name="email" autocomplete="off" id="email" placeholder="<?= lang('label_email'); ?>">
							</div>
						</div>

						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_division'); ?><span class="tx-danger"> *</span></label>
								<div class="d-grid gap-2 d-md-flex ">
									<select id="bidang" class="form-control select2-no-search" name="bidang" style="width:100%;">
										<option value=''></option>
									</select>
									<button type="button" class="btn btn-secondary btn-block btn-add btn-xs add-bidang "><i class="fa fa-plus"></i></button>
								</div>
							</div>
						</div>



						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_telp_number'); ?></label>
								<input type="text" class="form-control input-sm" name="notelp" id="notelp" autocomplete="off" placeholder="<?= lang('label_telp_number'); ?>">
							</div>
						</div>
					</div>
					<input type="text" class="hide" name="_id" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-karyawan" class="btn btn-primary"> <?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->


<div id="modal-manage-bidang" class="modal">
	<!-- modal satuan kategori-->
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-bidang">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Add data</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_division'); ?></label>
								<input type="text" class="form-control input-sm" name="nama" autocomplete="off" placeholder="<?= lang('label_division'); ?>">
							</div>
						</div>
					</div>
					<input type="text" class="hide" name="_id" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-bidang" class="btn btn-primary"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-outline-light" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div>
</div>


<div id="modal-manage-import" class="modal">
	<div class="modal-dialog modal-lg" role="document">
		<form method="POST" name="form-manage-import">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title"><?= lang('label_title_import'); ?></h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="card card-body bg-primary tx-white bd-0">
						<h5 class="card-title tx-white tx-medium mg-b-5">CATATAN:</h5>
						<p class="card-text">
						<ul class="m-0 pl-3">
							<li>Hanya file excel yang dapat diproses untuk import</li>
							<li>Format urutan kolom harus disesuaikan dengan sample yang ada. Jika belum ada silahkan download <a href="<?= base_url('files/docs/M_FormatImportDataKaryawan.xlsx'); ?>" class="tx-warning" target="_blank">disini</a></li>
						</ul>
						</p>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<label class="form-label">Format: </label>
							<img src="<?= base_url('assets/img/image-sample-import-karyawan.png'); ?>" class="zoomImage" style="max-width: 100%;border: 1px solid #ff0000">
						</div>
					</div>
					<div class="row">
						<hr>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="card border-1">
								<div class="rows">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="form-label"><?= lang('label_choose_file'); ?></label>
										</div>
										<div class="btn btn-xs p-0 btn-import">
											<button type="button" class="btn btn-warning choose_file__ btn-xs"><i class="fa fa-folder-open-o"></i> <span><?= lang('label_browse'); ?></span></button>
											<input type="file" name="file" class="hide" id="file" accept=".xls,.xlsx">
											<span class="filename"></span>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
										<div class="form-group">
											<label class="form-label">Index Row Data</label>
											<input type="number" class="form-control input-sm" name="start_row" value="6">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 row-error">
							<h5 class="title m-0 pb-2 tx-bold tx-danger"></h5>
							<div class="content" style="height: 170px; overflow-y: auto;"></div>
						</div>
					</div>

				</div>
				<div class="modal-footer inline-block">
					<button type="submit" id="submit-import" class="btn btn-primary"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-outline-light pull-right" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->
