<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-12">
		<div class="card card-dashboard-one">
			<div class="card-header c-header-large">
				<div class="card-title">
					<?=$page_title;?>
				</div>
				<label class="ftr align-middle">
						<button type="button" class="btn btn-xs btn-primary" id="export"><i class="fa fa-file-excel-o"></i> Export</button>
					</label>
			</div>
			<div class="card-body">
				<div class="ml-3 mr-3 tx-center label-filters ">
					
					<label class="ftr align-middle">
						<div class="input-group">
							<span class="input-group-append"><span class="input-group-text"> Tanggal </span></span>
							<input type="text" name="start_date" class="form-control input-sm" autocomplete="off" value="<?=date('Y-m-01');?>" style="width: 110px">
							<span class="input-group-append"><span class="input-group-text"> s/d </span></span>
							<input type="text" name="end_date" class="form-control input-sm" autocomplete="off" value="<?=date('Y-m-t');?>" style="width: 110px">
						</div>
					</label>
					
				</div>
				<table class="table-bordered table-striped minimize-padding-all" id="tableData" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th><?= lang('label_diagnosa'); ?></th>
							<th><?=lang('label_jumlah_diagnosa');?></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
