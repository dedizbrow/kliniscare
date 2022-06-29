<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<div class="card-header border c-header-large">
				<div class="card-title">
					Obat
				</div>
				<div class="ml-3 mr-3 tx-center label-filter ">

					<label class="ftr align-middle">
						<select class="form-control input-sm inline-block kategori" style="width: 155px; display: inline-block;" name="filter_kategori">
							<option value=""></option>
						</select>
					</label>
					<label class="ftr align-middle">
						<select class="form-control input-sm inline-block supplier" style="width: 155px; display: inline-block;" name="filter_supplier">
							<option value=""></option>
						</select>
					</label>
				</div>
				<div class="pull-right">
					<a href="#" id="refresh" style="padding-right: 20px; color:blanchedalmond"><i class="fa fa-refresh"></i></a>
					<?php if (isAllowed('c-obat^create', true)) { ?>
						<button type="button" class="btn btn-success add-obat btn-xs"><i class="fa fa-plus"></i> Tambah Data</button>
						<!-- <button type="button" class="btn btn-warning import__ btn-xs"><i class="fa fa-cloud-upload"></i> <span>Import</span> </button> -->
					<?php } ?>
					<button type="button" class="btn btn-import import__ btn-xs"><i class="fa fa-cloud-upload"></i> <span>Import</span> </button>
					<a href="<?php echo site_url(); ?>farmasi/obat/export_"><button type="button" class="btn btn-warning btn-xs"><i class="fa fa-cloud-upload"></i> <span>Export</span> </button></a>
					<!-- <button type="button" class="btn btn-warning btn-xs" id="printobat"><i class="fa fa-print"></i> <span>Print</span> </button> -->
				</div>
			</div>
			<div class="card-body">
				<table class="table table-bordered table-striped minimize-padding-all" id="dataObat" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%" cellspacing="0">
					<thead>
						<tr role="row">
							<th>No.</th>
							<th><?= lang('label_code'); ?></th>
							<th><?= lang('label_name'); ?></th>
							<th><?= lang('label_category'); ?></th>
							<th><?= lang('label_unit'); ?></th>
							<th>isi</th>
							<th><?= lang('label_purchase'); ?></th>
							<th><?= lang('label_sell'); ?></th>
							<th><?= lang('label_stock'); ?></th>
							<th><?= lang('label_supplier'); ?></th>
							<th><?= lang('label_expired'); ?></th>
							<th><?= lang('label_user'); ?></th>
							<th><?= lang('label_action'); ?></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
</div>


<div id="modal-manage-obat" class="modal">
	<div class="modal-dialog modal-lg" role="document">
		<form method="POST" name="form-manage-obat">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Tambah/Ubah Data Obat</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<section class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-sm-6">
									<label class="form-label"><?= lang('label_code'); ?></label>
									<div class="d-grid gap-2 d-md-flex ">
										<input type="text" class="hide" name="_id" value="">
										<input type="text" class="hide" name="_onedit_skip" value="">
										<input type="text" id="kode" name="kode" class="form-control input-sm" required="" placeholder="<?= lang('label_code'); ?>" autocomplete="off" readonly="readonly">
										<button type="button" name="autocode" id="autocode" class="btn btn-secondary btn-block btn-xs" style="width: 40px;">SET</button>
									</div>
								</div>
								<div class="col-sm-6"></div>
								<div class="col-sm-6">
									<div class="form-group">
										<label class="form-label"><?= lang('label_name'); ?></label>
										<input type="text" name="nama" class="form-control input-sm" required="" placeholder="<?= lang('label_name'); ?>" autocomplete="off">
									</div>
								</div>
								<div class="col-sm-6">
									<label class="form-label">Satuan Terkecil</label>
									<div class="d-grid gap-2 d-md-flex ">
										<select id="satuanbeli" class="form-control" name="satuanbeli" style="width:100%;">
											<option value=''></option>
										</select>
										<button type="button" class="btn btn-secondary btn-block btn-add btn-xs add-satuan-beli "><i class="fa fa-plus"></i></button>
									</div>
								</div>

								<div class="col-sm-12">
									<div class="row row-sm mg-b-20">
										<div class="col-sm-12">
											<label class="form-label"><?= lang('label_category'); ?></label>
											<div class="d-grid d-md-flex ">
												<select id="kategori" class="form-control kategori" name="kategori" style="width:100%;">
													<option value=""></option>
												</select>
												<button type="button" class="btn btn-secondary btn-block btn-xs btn-add add-kategori"><i class="fa fa-plus"></i></button>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-sm-8">
											<div class="form-group">
												<label class="form-label"><?= lang('label_purchase'); ?></label>
												<input type="number" name="hargaBeli" class="form-control input-sm" required="" placeholder="<?= lang('label_purchase'); ?>" autocomplete="off">

											</div>
										</div>
										<div class="col-sm-4" style="margin-top: 27px; margin-left:-25px"><b style="font-size: 16px; color:grey">/ </b><span style="height:30px;color:grey" class="label2">Box</span></div>
									</div>

								</div>
							</div>
						</section>
						<section class="col-lg-6 col-md-12 col-sm-12 col-xs-12 border-left-dashed">
							<div class="row">
								<div class="col-sm-12">
									<div class="row row-sm mg-b-20">
										<!-- <div class="row"> -->
										<div class="col-sm-12">
											<label class="form-label"><?= lang('label_supplier'); ?></label>
											<div class="d-grid gap-2 d-md-flex">
												<select id="supplier" class="form-control supplier" name="supplier" style="width:100%;">
													<option value=""></option>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-4 ">
											<div class="form-group">
												<label class="form-label"><?= lang('label_stock'); ?></label>
												<input type="number" name="stok" class="form-control input-sm" required="" placeholder="<?= lang('label_stock'); ?>" autocomplete="off">
											</div>
										</div>
										<div class="col-sm-4" style="margin-top: 27px; margin-left:-25px"><b style="font-size: 16px; color:grey">/ </b><span style="height:30px;color:grey" class="label2">Box</span></div>

									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label class="form-label"><?= lang('label_stockmin'); ?></label>
												<input type="number" name="stokmin" class="form-control no-space input-sm" required="" placeholder="<?= lang('label_stockmin'); ?>" autocomplete="off">
											</div>
										</div>

										<div class="col-sm-6">
											<div class="form-group">
												<label class="form-label"><?= lang('label_expired'); ?></label>
												<input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="expired" autocomplete="off">
											</div>
										</div>

										<!-- </div> -->
									</div>
								</div>
							</div>
						</section>
					</div>

					<div class="row">
						<section class="col-md-12 border-top-dashed">
							<div class="row">

								<div class="col-sm-12">
									<div class="row">

										<div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
											<label class="form-label" style="font-size: 9px;">Satuan Jual/Beli</label>
											<div class="d-grid d-md-flex ">
												<select class="form-control satuan" name="satuan[]" id="satuan1" style="width:100%;">
													<option value=""></option>
												</select>
												<button type="button" class="btn btn-secondary btn-block btn-add btn-xs add-satuan-obat "><i class="fa fa-plus"></i></button>
											</div>
										</div>
										<div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<label class="form-label" style="font-size: 9px;">Isi</label>
											<input type="number" name="isi[]" id="isi1" class="form-control input-sm" autocomplete="off">
										</div>

										<div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<label class="form-label" style="font-size: 9px;">Laba % </label>
											<input type="number" name="laba[]" id="laba1" class="form-control input-sm" autocomplete="off">
										</div>

										<div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<label class="form-label" style="font-size: 9px;">Harga Beli @satuan</label>
											<input type="number" name="hargabeli[]" id="hargabeli1" class="form-control input-sm" autocomplete="off">
										</div>
										<div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
											<label class="form-label" style="font-size: 9px;">Harga Jual</label>
											<input type="number" name="harga[]" id="harga1" class="form-control input-sm" autocomplete="off">
										</div>
									</div>
								</div>
							</div>
							<!-- end -->
							<div class="row">

								<div class="col-sm-12">
									<div class="row">
										<div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
											<div class="d-grid d-md-flex">
												<select class="form-control satuan" name="satuan[]" id="satuan2" style="width:100%;">
													<option value=""></option>
												</select>
											</div>
										</div>
										<div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<input type="number" name="isi[]" id="isi2" class="form-control input-sm" autocomplete="off">
										</div>

										<div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<input type="number" name="laba[]" id="laba2" class="form-control input-sm" autocomplete="off">
										</div>

										<div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<input type="number" name="hargabeli[]" id="hargabeli2" class="form-control input-sm" autocomplete="off">
										</div>
										<div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
											<input type="number" name="harga[]" id="harga2" class="form-control input-sm" autocomplete="off">
										</div>
									</div>
								</div>
							</div>
							<!-- end -->
							<div class="row">

								<div class="col-sm-12">
									<div class="row">

										<div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
											<div class="d-grid d-md-flex">
												<select class="form-control satuan" name="satuan[]" id="satuan3" style="width:100%;">
													<option value=""></option>
												</select>
											</div>
										</div>
										<div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<input type="number" name="isi[]" id="isi3" class="form-control input-sm" autocomplete="off">
										</div>


										<div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<input type="number" name="laba[]" id="laba3" class="form-control input-sm" autocomplete="off">
										</div>

										<div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<input type="number" name="hargabeli[]" id="hargabeli3" class="form-control input-sm" autocomplete="off">
										</div>
										<div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
											<input type="number" name="harga[]" id="harga3" class="form-control input-sm" autocomplete="off">
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>

				</div>
				<div class="modal-footer">
					<button type="submit" id="save-obat" class="btn btn-primary"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->


<div id="modal-manage-satuan-beli" class="modal">
	<!-- modal satuan obat-->
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-satuan-beli">
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
								<label class="form-label"><?= lang('label_unit'); ?></label>
								<input type="text" class="form-control input-sm" name="namaSatuanbeli" id="namaSatuanbeli" autocomplete="off" placeholder="<?= lang('label_unit'); ?>">
							</div>
						</div>
					</div>
					<input type="text" class="hide" name="_idsatuan" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-satuan-beli" class="btn btn-indigo"><i class="fa fa-save"></i> <?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div>
</div>

<div id="modal-manage-satuan-obat" class="modal">
	<!-- modal satuan obat-->
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-satuan-obat">
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
								<label class="form-label"><?= lang('label_unit'); ?></label>
								<input type="text" class="form-control input-sm" name="namaSatuanobat" id="namaSatuanobat" autocomplete="off" placeholder="<?= lang('label_unit'); ?>">
							</div>
						</div>
					</div>
					<input type="text" class="hide" name="_idsatuan" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-satuan-obat" class="btn btn-indigo"><i class="fa fa-save"></i> <?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div>
</div>

<div id="modal-manage-kategori" class="modal">
	<!-- modal kategori obat-->
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-kategori">
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
								<label class="form-label"><?= lang('label_category'); ?></label>
								<input type="text" class="form-control input-sm" name="namaKategoriobat" id="namaKategoriobat" autocomplete="off" placeholder="<?= lang('label_category'); ?>">
							</div>
						</div>
					</div>
					<input type="text" class="hide" name="_idkategori" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-kategori" class="btn btn-indigo"><i class="fa fa-save"></i> <?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div>
</div>

<div id="modal-manage-barcode" class="modal">
	<!-- modal barcode-->
	<div class="modal-dialog modal-lg" role="document">
		<form method="POST" name="form-manage-barcode">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Barcode</h6>

					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="printBarcode col-xs-4">
						<!-- <p id="harga"></p>
                            <img id="barcode" />
                            <p id="namaobat"> -->
						<div class="card" style="width: 15rem; padding: 3px;">
							<b style="margin-left: 10px;margin: bottom 2px;" id="harga"></b>
							<img id="barcode">
							<div class="card-body">
								<b id="namaobat"></b><b id="kode" style="float: right;"></b>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" id="tambah"><i class="fa fa-copy"></i> Copy</button>
					<button type="button" class="btn btn-indigo" id="barcode_download"><i class="fa fa-print"></i> Print</button>
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
							<li>Format urutan kolom harus disesuaikan dengan sample yang ada. Jika belum ada silahkan download <a href="<?= base_url('files/docs/M_FormatImportDataObat.xlsx'); ?>" class="tx-warning" target="_blank">disini</a></li>
						</ul>
						</p>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<label class="form-label">Format: </label>
							<img src="<?= base_url('assets/img/image-sample-import-obat.png'); ?>" class="zoomImage" style="max-width: 100%;border: 1px solid #ff0000">
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
											<input type="number" class="form-control input-sm" name="start_row" value="7">
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
					<button type="submit" id="submit-import" class="btn btn-indigo"><i class="fa fa-save"></i> Submit</button>
					<button type="button" class="btn btn-outline-light pull-right" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->
