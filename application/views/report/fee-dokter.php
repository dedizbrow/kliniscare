<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12">
		<div class="card card-dashboard-one">
			<div class="card-header c-header-large">
				<div class="card-title">
					<?=lang('label_report_title');?>
				</div>
				<div class="ml-3 mr-3 tx-center label-filters ">
					<label class="ftr align-middle">
						<div class="input-group">
							<span class="input-group-append"><span class="input-group-text"> <?=lang('label_poli');?> </span></span>
							<select class="form-control input-sm inline-block" style="width: 120px; display: inline-block;" name="select_poli"></select>
						</div>
					</label>
					<label class="ftr align-middle">
						<div class="input-group">
							<span class="input-group-append"><span class="input-group-text"> <?=lang('label_dokter');?> </span></span>
							<select class="form-control input-sm inline-block" style="width: 120px; display: inline-block;" name="select_dokter"></select>
						</div>
					</label>
					<label class="ftr align-middle">
						<div class="input-group">
							<span class="input-group-append"><span class="input-group-text"> Tanggal </span></span>
							<input type="text" name="start_date" class="form-control input-sm" autocomplete="off" value="<?=date("Y-m-01");?>" style="width: 110px">
							<span class="input-group-append"><span class="input-group-text"> s/d </span></span>
							<input type="text" name="end_date" class="form-control input-sm" autocomplete="off" value="<?=date("Y-m-t");?>" style="width: 110px">
						</div>
					</label>
					<label class="ftr align-middle">
						<button type="button" class="btn btn-xs btn-primary" id="export"><i class="fa fa-file-excel-o"></i> Export</button>
					</label>
				</div>
			</div>
			<div class="card-body">
				<table class="table-bordered table-striped minimize-padding-all" id="tableData" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>No.</th>
							<th><?= lang('label_tanggal'); ?></th>
							<th><?= lang('label_poli'); ?></th>
							<th><?=lang('label_dokter');?></th>
							<th><?= lang('label_tindakan'); ?></th>
							<th><?= lang('label_tarif_dokter'); ?></th>
							<th><?= lang('label_tarif_klinik'); ?></th>
							<th><?= lang('label_total_biaya'); ?></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
