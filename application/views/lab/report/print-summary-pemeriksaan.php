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
		<p style="text-align: center"><b>REKAPITULASI DATA PEMERIKSAAN</b></p>
			<table class="" width="100%" cellpadding="5" cellspacing="0">
				<tr>
					<td width="150px">Periode</td>
					<td width="5%">:</td>
					<td>
						<?php
						echo $start_date." s/d ".$end_date;
						?>
					</td>
				</tr>
				<?php if(isset($C_PV_GROUP) && $C_PV_GROUP=='pusat' && conf('lab_enable_select_provider')===TRUE){ ?>
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
				?>
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
				<tr>
						<td>Hasil Pemeriksaan</td>
						<td>:</td>
						<td><?=$hasil_pemeriksaan;?>
						</td>
					</tr>
			</table>
			<p></p>
			<table width="100%" id="table table-bordered dataTable table-striped" border="1" cellpadding="5" cellspacing="0" autosize="1">
				<thead>
					<tr>
						<th align="left">No</th>
						<th align="left">Tanggal</th>
						<th align="left">Nama Pasien</th>
						<th align="left">Hasil</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i=1;
						foreach($list_pemeriksaan as $item){
					?>
							<tr>
								<td><?=$i;?></td>
								<td><?=date("d-m-Y",strtotime($item->tgl_periksa));?></td>
								<td><?=$item->nama_pasien;?></td>
								<td><?=$item->hasil;?></td>
							</tr>
					<?php
						$i++;
						}
					?>
				</tbody>
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
		padding-top: 0m;
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
