<!DOCTYPE html>
<html>
<head>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta charset="utf-8">
    <title><?=(isset($page_title)) ? $page_title : ''; ?></title>
		<link rel="icon" href="<?=base_url('assets/img/favicon.ico');?>" type="image/gif">
</head>
<body>
	<div class="body-print-pdf">
		<p style="text-align: center"><b>LAPORAN BIAYA PEMERIKSAAN</b></p>
			<table class="" width="100%" cellpadding="5" cellspacing="0">
				<tr>
					<td width="150px">Periode</td>
					<td width="5%">:</td>
					<td>
						<?php
						echo date("d-M-y",strtotime($start_date))." s/d ".date("d-M-y",strtotime($end_date));
						?>
					</td>
				</tr>
				<tr>
					<td>Provider</td>
					<td>:</td>
					<td>
						<?php
						foreach($list_provider as $item){
							if($provider==$item->id) echo $item->nama;
						}
					?>
					</td>
				</tr>
				<tr>
					<td>Jenis Pemeriksaan</td>
					<td>:</td>
					<td>
						<?php
						foreach($list_jenis as $jns){
							if($jenis==$jns->id) echo $jns->jenis;
						}
						?>
					</td>
				</tr>
			</table>
			<p></p>
			<table width="100%" id="table table-bordered dataTable table-striped" border="1" cellpadding="5" cellspacing="0" autosize="1">
					<thead>
						<tr>
							<th>No</th>
							<th>Tgl Pemeriksaan</th>
							<th>Jumlah Sample</th>
							<th>Tgl Hasil</th>
							<th>Tarif per Sample</th>
							<th>Jumlah Biaya</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($list_biaya as $dt){
								echo '<tr>
									<td align="center">'.$dt->no.'</td>
									<td align="center">'.$dt->tgl_periksa.'</td>
									<td align="center">'.$dt->qty.'</td>
									<td align="center">'.$dt->tgl_hasil.'</td>
									<td><span>Rp. </span><span class="pull-right" style="float: right">'.number_format($dt->tarif).'</span></td>
									<td><span>Rp. </span><span class="pull-right">'.number_format($dt->total_tarif).'</span></td>
								</tr>';
							}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="2">Total</th>
							<th class="text-center"><?=$total_qty;?></th>
							<th></th>
							<th></th>
							<th><span>Rp. </span><span class="pull-right"><?=number_format($total_biaya);?></span></th>
						</tr>
					</tfoot>
				</table>
		</div>
	</body>
</html>


<style>
	
	body{
		font-family: arial;
	}
	/* pdf print setting */
	.row-title-page{
		margin-bottom: 20px;
	}
	table tr td{
		vertical-align: top;
	}
	table.table-desc{
		line-height: 1.8
	}
	table { page-break-inside:auto }
  tr    { page-break-inside:avoid; page-break-after:auto }
	.page-header-pdf{
		padding-top: 0cm;
		padding-left: 0cm;
		padding-right: 0cm
	}
	.body-print-pdf{
		padding-left: 2cm; 
		padding-right: 2cm;
	}
	.page-footer-pdf{
		text-align: center;
		position: relative;
		background-image: url(<?=base_url('assets/img/lab/img-doc-footer.png');?>);
		background-repeat: no-repeat;
    background-size: 100% 100%;
		z-index: 1000;
		margin-top: -33px;
	}
	.page-footer-text{
		color: #fff;
		font-size: 12px;
		font-weight: bold;
		padding: 40px 3px 12px 3px
	}
	/* end pdf print setting */
	</style>
