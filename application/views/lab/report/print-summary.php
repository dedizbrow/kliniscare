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
		<table cellspacing="0" cellpadding="5">
			<tr>
				<td>Periode</td>
				<td>:</td>
				<td>
					<?=$periode;?>
				</td>
			</tr>
			<tr>
				<td>Jumlah Total</td>
				<td>:</td>
				<td><?=$grand_total;?></td>
			</tr>
			<?php
			$arr_jenis=[];
			foreach($list_jenis as $item){ ?>
				<tr>
					<td><?=$item->jenis;?></td>
					<td>:</td>
					<td><?=$sum_total[$item->jenis];?></td>
				</tr>
			<?php
				array_push($arr_jenis,$item->jenis);
			}
			?>
			
		</table>
		<p></p>
		<table width="100%" id="table table-bordered dataTable table-striped table-detail" border="1" cellspacing=0 cellpadding="5" autosize="1" style="border: 1px solid #000">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama Provider</th>
					<th>Jenis Pemeriksaan</th>
					<th>Jumlah</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$n=1;
				$totals=0;
				if(empty($list_by_provider)) echo '<tr><td colspan="3">Tidak ada data</td></tr>';
				foreach($list_by_provider as $provider=>$item){ ?>
					<tr>
						<td><b><?=$n++;?></b></td>
						<td><b><?=$provider;?></b></td>
						<td><?=$arr_jenis[0];?></td>
						<td>
						<?php
							$found=false;
							foreach($item as $j=>$v){
							$totals+=(int) $v->jumlah_selesai;
								if($v->jenis==$arr_jenis[0]){ $found=true; echo $v->jumlah_selesai; }
							}
							if(!$found) echo 0;
						?>
						</td>
					</tr>
				<?php 
					$alias_jenis=$arr_jenis;
					array_shift($alias_jenis);
					foreach($alias_jenis as $jns){ ?>
					<tr >
						<td></td>
						<td></td>
						<td><?=$jns;?></td>
						<td>
							<?php
							$found=false;
							foreach($item as $j=>$v){
								if($v->jenis==$jns){ $found=true; echo $v->jumlah_selesai; }
							}
							if(!$found) echo 0;
							?>
						</td>
					</tr>
				<?php
					}
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3">TOTAL</td>
					<td><?=$totals;?></td>
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
