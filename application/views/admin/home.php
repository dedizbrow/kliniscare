<script type="text/javascript" src="<?= base_url('assets/plugins/charts/loader.js'); ?>"></script>
<div class="contain">
	<?php
	foreach ($news as $dt) {
	?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="alert alert-outline-info" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong><?= $dt->judul; ?></strong> <?= $dt->keterangan; ?>
				</div>
			</div>
		</div>
	<?php
	}
	?>
	<div class="row" style="padding-bottom: 10px;">
		<div class="col-lg-4 col-md-4 col-sm-12 mt-4">
			<div class="card-dashboard-ten module-lab-enable" style="background-color: #6f42c1;">
				<h6 class="az-content-label"><b>PASIEN</b></h6>
				<div class="card-body">
					<div>
						<h6><?= $num_patient; ?></h6>
						<label>TOTAL PASIEN</label>
					</div>
					<!-- <div>
            <?php if (conf('enable_module_lab') && isset($lab_jumlah_pasien)) { ?>
              <h6><?= (isset($lab_jumlah_pasien)) ? $lab_jumlah_pasien : 0; ?></h6>
            <?php } ?>
            <label>PASIEN LAB</label>
          </div> -->
				</div><!-- card-body -->
			</div><!-- card -->
		</div>
		<div class="col-lg-4 col-md-4 col-sm-12 mt-4">
			<div class="card-dashboard-ten  bg-primary">
				<h6 class="az-content-label"><b>KLINIK</b></h6>
				<div class="card-body">
					<div>
						<h6><?= $num_rawat_jalan;  ?></h6>
						<label>RAWAT JALAN</label>
					</div>
					<div>
						<h6><?= $num_rawat_inap; ?></h6>
						<label>RAWAT INAP</label>
					</div>
				</div><!-- card-body -->
			</div><!-- card -->
			<!-- </a> -->
		</div>
		<div class="col-lg-4 col-md-4 col-sm-12 mt-4">
			<div class="card-dashboard-ten " style="background-color: #189A1A ;">
				<h6 class="az-content-label"><b>APOTEK</b></h6>
				<div class="card-body">
					<div>
						<h6><?= $num_obat; ?></h6>
						<label>TOTAL OBAT</label>
					</div>
					<div>
						<h6><?= $num_trans_obat; ?></h6>
						<label>OBAT TERJUAL</label>
					</div>
				</div><!-- card-body -->
			</div><!-- card -->
		</div>
	</div>

	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 ">

					<!-- <div style="background-color: yellow;"> -->
					<script type="text/javascript">
						google.charts.load("current", {
							packages: ['corechart']
						});
						google.charts.setOnLoadCallback(drawChart);

						function drawChart() {
							var data = google.visualization.arrayToDataTable([
								["Periode", "Kunjungan", {
									role: "style"
								}],
								<?php
								$colors = array('#77E08E', '#FDFE8E', '#FF9571', '#5ACCFF', '#F89AFF', '#2AFFFD', '#77E08E', '#FDFE8E', '#FF9571', '#5ACCFF', '#F89AFF', '#2AFFFD');
								$no = 0;
								foreach ($chart_data_rawat_jalan as $row) {
									echo "['$row[periode]', $row[total], '$colors[$no]'],";
									$no++;
								}
								?>
							]);
							var view = new google.visualization.DataView(data);
							view.setColumns([0, 1,
								{
									calc: "stringify",
									sourceColumn: 1,
									type: "string",
									role: "annotation"
								},
								2
							]);
							var options = {
								title: "Grafik Instalasi Rawat Jalan",
								// width: 1100,
								// height: 300,
								bar: {
									groupWidth: "70%"
								},
								legend: {
									position: "none"
								},
								hAxis: {
									title: 'Periode'
								},
								vAxis: {
									title: 'Pasien'
								}
							};
							var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values2"));
							chart.draw(view, options);
						}
					</script>
					<div id="columnchart_values2" style="width: 100%; height: 300px;"></div>
					<!-- </div> -->
				</div>
			</div>
		</div>
	</div>
	<?php if (conf('enable_module_lab')) { ?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card bd-0 with-border ">
					<div class="card-header bd-0 tx-white bg-indigo ">
						<h4 class="card-title">LabKlinik Summary [Pemeriksaan]</h4>
					</div>
					<div class="card-body ">
						<div class="" id="chartLabArea"></div>
					</div>
				</div>
			<?php } ?>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h5 style="margin-bottom: 20px;"> <b style="color: black;">Stok HABIS / dibawah MINIMAL stok </b></h5>
						<table class="table table-bordered table-striped minimize-padding-all" id="obatStokmin" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%" cellspacing="0">
							<thead>
								<tr role="row">
									<th>Kode Obat</th>
									<th>Nama obat</th>
									<th class="tx-danger">Stok Saat Ini</th>
									<th class="tx-primary">Minimal Stok</th>
									<th>Supplier</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 mt-4">
						<!-- <div class="periode"> -->
						<div class="border-prd radius-prd" style="background-color: white;">
							<script type="text/javascript">
								google.charts.load("current", {
									packages: ['corechart']
								});
								google.charts.setOnLoadCallback(drawChart1);

								function drawChart1() {
									var data = google.visualization.arrayToDataTable([
										["Periode", "Penjualan", {
											role: "style"
										}],
										<?php
										$colors = array('#77E08E', '#FDFE8E', '#FF9571', '#5ACCFF', '#F89AFF', '#2AFFFD');
										$no = 0;
										foreach ($chart_data_apotek as $row) {
											echo "['$row[periode]', " . (int) $row['total'] . ", '$colors[$no]'],";
											$no++;
										}
										?>
									]);
									var view = new google.visualization.DataView(data);
									view.setColumns([0, 1,
										{
											calc: "stringify",
											sourceColumn: 1,
											type: "string",
											role: "annotation"
										},
										2
									]);
									var options = {
										title: "Grafik Transaksi Penjualan Apotek",
										// width: 500,
										// height: 330,
										bar: {
											groupWidth: "85%"
										},
										legend: {
											position: "none"
										},
										hAxis: {
											title: 'Periode'
										},
										vAxis: {
											title: 'Mata Uang Rupiah'
										}
									};
									var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values1"));
									chart.draw(view, options);
								}
							</script>
							<div id="columnchart_values1" style="background-color: white;"></div>
							<!-- </div> -->
						</div>
					</div>
				</div>
			</div>
		</div>
</div>
