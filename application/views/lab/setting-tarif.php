<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<div class="card-header border c-header">
				<div class="card-title">
					<?= lang('label_list_tarif'); ?>

				</div>
				<div class="ml-3 mr-3 tx-center label-filter">
					<?php if (isset($C_PV_GROUP) && $C_PV_GROUP == 'pusat'  && conf('lab_enable_select_provider') === TRUE) { ?>
						<label class="">Filter: </label>
						<label class="bg-black-1">Provider
							<select class="form-control input-sm inline-block" style="width: 200px" name="filter_provider"></select>
						</label>
					<?php } ?>
				</div>
				<div class="pull-right">
					<?php if (strtolower($C_PV_GROUP) == 'pusat') { ?>
						<button type="button" class="btn btn-success add-tarif btn-xs"><i class="fa fa-plus"></i> Tambah Data</button>
					<?php } ?>
				</div>
			</div>
			<div class="card-body">
				<table class="table-bordered minimize-padding-all table-striped" id="dataTarif" width="100%">
					<thead>
						<tr role="row">
							<th>Provider</th>
							<th class="search" data-name="jenis">Jenis Pemeriksaan</th>
							<th>Tanggal Berlaku</th>
							<th>Tarif</th>
							<th>Action</th>
							<th>Status</th>
							<th>SaveId</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="card-footer">
				<label>Keterangan:</label>
				<ul class="desc">
					<li><b>Tanggal Berlaku</b>: tanggal mulai berlakunya tarif s/d tanggal yang diatur setelahnya (jika tanggal setelahnya tidak ada, maka tarif berlaku s/d saat ini)<br></li>
					<li><b class="text-primary">Text berwarna biru</b> mengindikasikan bahwa tarif tersebut aktif s/d saat ini<br></li>
				</ul>
			</div>
		</div>
	</section>
</div>

<div id="modal-manage-tarif" class="modal">
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-tarif">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title"><?= lang('label_add_update_tarif'); ?></h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<?php if (isset($C_PV_GROUP) && $C_PV_GROUP == 'pusat'  && conf('lab_enable_select_provider') === TRUE) { ?>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="form-group">
									<label class="form-label"><?= lang('label_select_provider'); ?></label>
									<select class="form-control input-sm" name="provider_id"></select>
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<label class="form-label">Setting Tarif:</label>
							<table class="dataTable table-bordered minimize-padding-all" width="100%">
								<thead>
									<tr class="bg-primary">
										<th>Pemeriksaan</th>
										<th>Nominal</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($list_jenis as $jns) {
									?>
										<tr>
											<td><?= $jns->jenis; ?></td>
											<td>
												<div class="input-group">
													<span class="input-group-prepend">
														<span class="input-group-text">Rp. </span>
													</span>
													<input type="number" name="tarif_[<?= $jns->id; ?>]" value="" class="form-control input-sm" required>
												</div>
											</td>
										</tr>
									<?php
									}
									?>
								</tbody>
							</table>
							<p>&nbsp;</p>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label">Tanggal Berlaku</label>
								<input type="text" class="form-control input-sm" name="start_date" value="<?= date("Y-m-d"); ?>">
							</div>
						</div>
					</div>
					<input type="text" class="hide" name="_id" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-tarif" class="btn btn-primary"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-outline-light" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->
