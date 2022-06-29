<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<div class="card-header border c-header-large">
				<div class="card-title">
					Jadwal Dokter
				</div>
				<div class="ml-3 mr-3 tx-center label-filter ">
					<label class="ftr align-middle">Sorting
						<select class="form-control input-sm inline-block idDokter" style="width: 140px; display: inline-block;" name="sorting_dokter">

						</select>
						<select class="form-control input-sm inline-block idHari" style="width: 120px; display: inline-block;" name="sorting_hari">
						</select>
					</label>
				</div>
				<div class="pull-right">

					<?php if (isAllowed('c-jadwaldokter^create', true)) { ?>
						<button type="button" class="btn btn-success add-jadwal-dokter btn-xs"><i class="fa fa-plus"></i> Tambah Data</button>
						<!-- <button type="button" class="btn btn-import import__ btn-xs"><i class="fa fa-cloud-upload"></i> <span>Import</span> </button> -->
					<?php } ?>
					<a href="<?php echo site_url(); ?>master-data/jadwal_dokter/export_"><button type="button" class="btn btn-warning btn-xs"><i class="fa fa-cloud-upload"></i> <span>Export</span> </button></a>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-bordered table-striped minimize-padding-all" id="dataJadwal" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%" cellspacing="0">
					<thead>
						<tr role="row">
							<th>No.</th>
							<th><?= lang('label_name'); ?></th>
							<th><?= lang('label_schedule'); ?></th>
							<th><?= lang('label_from'); ?></th>
							<th><?= lang('label_until'); ?></th>
							<th><?= lang('label_action'); ?></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="card-footer">
				<label>Keterangan:</label>
				<ul class="desc">
					<li class="tx-primary">Dokter yang sama tidak dapat memimih jadwal dengan hari dan jam mulai yang sama (Jika hari sama maka pastikan jam mulai berbeda).</li>
				</ul>
			</div>
		</div>
	</section>
</div>

<div id="modal-manage-jadwal-dokter" class="modal">
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-jadwal-dokter">
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
								<label class="form-label">Pilih Dokter <span class="tx-danger"> *</span></label>
								<div class="d-grid gap-2 d-md-flex ">
									<select id="idDokter" class="form-control idDokter select2-no-search" name="dokter" style="width:210px;">
										<option value='' selected="selected"><?= lang('label_name'); ?></option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="form-label">Pilih Hari <span class="tx-danger"> *</span></label>
								<div class="d-grid gap-2 d-md-flex ">
									<select id="idHari" class="form-control idHari select2-no-search" name="hari" style="width:210px;">
										<option value='' selected="selected"><?= lang('label_schedule'); ?></option>
									</select>
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_from'); ?> <span class="tx-danger"> *</span></label>
								<input type="text" class="form-control input-sm" name="dari_jam" id="dari_jam" placeholder="<?= lang('label_format'); ?>">
							</div>
							<div class="form-group">
								<label class="form-label"><?= lang('label_until'); ?></label>
								<input type="text" class="form-control input-sm" name="sampai_jam" id="sampai_jam" placeholder="<?= lang('label_format'); ?>">
							</div>
						</div>
					</div>
					<input type="text" class="hide" name="_id" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-jadwal-dokter" class="btn btn-primary"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang('label_close'); ?></button>
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
							<li>Format urutan kolom harus disesuaikan dengan sample yang ada. Jika belum ada silahkan download <a href="<?= base_url('files/docs/M_FormatImportDataJadwal.xlsx'); ?>" class="tx-warning" target="_blank">disini</a></li>
						</ul>
						</p>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<label class="form-label">Format: </label>
							<img src="<?= base_url('assets/img/image-sample-import-dokter.png'); ?>" class="zoomImage" style="max-width: 100%;border: 1px solid #ff0000">
						</div>
					</div>
					<div class="row">
						<hr>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="card border-1">
								<!-- <div class="rows">
                                    <?php
									if (strtolower($C_PV_GROUP) == 'pusat' && conf('lab_enable_select_provider') === TRUE) {
									?>
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label class="form-label"><?= lang('label_select_provider'); ?></label>
                                                <select class="form-control input-sm" name="provider_id"></select>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div> -->
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
											<input type="number" class="form-control input-sm" name="start_row" value="5">
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
