<?php
$src_assets_template = "assets/azia-assets";
$src_view_template = "templates/menu-atas";
$time = (int) rand();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?= lang('meta_description'); ?>">
	<meta name="author" content="<?= lang('meta_author'); ?>">
	<link rel="icon" href="<?= base_url('assets/img/favicon.ico'); ?>" type="image/gif">

	<title>
		<?php if (isset($web_title)) {
			echo $web_title;
		} else if (isset($page_title)) {
			echo $page_title;
		} else {
			echo conf('app_name_short');
		}; ?></title>
	<!-- vendor css -->
	<link href="<?= base_url($src_assets_template . '/lib/font-awesome-4.7.0/css/font-awesome.min.css'); ?>" rel="stylesheet">
	<link href="<?= base_url($src_assets_template . '/lib/lightslider/css/lightslider.min.css'); ?>" rel="stylesheet">
	<link href="<?= base_url($src_assets_template . '/lib/select2/css/select2.min.css'); ?>" rel="stylesheet">
	<!-- azia CSS -->
	<?php if (isset($datatable)) { ?>
		<link rel="stylesheet" href="<?= base_url($src_assets_template . '/lib/datatables.net-dt/css/jquery.dataTables.min.css'); ?>">
	<?php } ?>
	<link rel="stylesheet" href="<?= base_url($src_assets_template . '/css/azia.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/ctc.css?pid=' . $time); ?>">
</head>

<body class="az-minimal">
	<?php $this->load->view("$src_view_template/topbar"); ?>
	<div class="az-content">
		<div class="container">
			<div class="az-content-body">
				<?php
				if (!empty(getClinic()) && getClinic()->id == 'allclinic' && (!isset($skip_select_clinic) || $skip_select_clinic === false)) {
				?>
					<div class="form-inline mb-2">
						<div class="input-group">
							<span class="input-group-prepend"><span class="input-group-text bg-primary tx-white"><i class="fa fa-question-circle-o mr-2" data-toggle="tooltip" title="Opsi ini hanya akan muncul untuk akun super-admin yang memiliki akses ke semua klinik "></i> Pilih Klinik </span> </span>
							<select id="source_clinic" name="source_clinic" class="form-control input-sm" style="max-width: 300px"></select>
						</div>
					</div>
				<?php
				} else {
					if (!empty(getClinic()) && getClinic()->id == "allclinic" && isset($skip_select_clinic) && isset($clinic_id)) {
						echo '<select id="source_clinic" name="source_clinic" class="hide form-control input-sm default" style="max-width: 300px" ><option value="' . $clinic_id . '" selected>' . $clinic_id . '</option></select>';
					}
				}
				?>
				<?= (isset($contents)) ? $contents : ""; ?>
			</div><!-- az-content-body -->
		</div>
	</div><!-- az-content -->

	<div class="az-footer">
		<div class="container">
			<!-- <span>&copy; <?= conf('publish_year'); ?><?= (date("Y") > conf('publish_year')) ? "-" . date("Y") : ""; ?> | <?= conf('company_name'); ?> </span> -->
			<span>Software Klinik & Apotik | Design by <a href="#" target="_blank">Klinis Care</a></span>
			<span class="<?= (conf('multi_lang')) ? '' : 'hide'; ?>">
				<span class="option-language form-inline">
					<label class=""><?= lang('label_switch_lang'); ?> </label>
					<?php
					$site_lang = $this->session->userdata($app_code . 'site_lang');
					$current_lang = (isset($site_lang)) ? $site_lang : conf('language');
					$this->session->set_userdata('referred_from', current_url());
					echo "<select id='switch-lang' class='form-control input-sm minimize-padding'>";
					$langs = array('English', 'Indonesia');
					foreach ($langs as $language) {
						$selected = (strtolower($language) == strtolower($current_lang)) ? "selected='selected'" : "";
						echo "<option value='" . strtolower($language) . "' $selected>$language</option>";
					}
					echo "</select>";
					?>
				</span>
			</span>
			
			<input type="hidden" id="base_url" value="<?= base_url(); ?>">
		</div><!-- container -->
	</div><!-- az-footer -->
	<script src="<?= base_url($src_assets_template . '/lib/jquery/jquery.min.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/jquery-ui/ui/widgets/datepicker.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/lightslider/js/lightslider.min.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/select2/js/select2.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/jquery.mask.js'); ?>"></script>
	<?php if (isset($datatable)) { ?>
		<script src="<?= base_url($src_assets_template . '/lib/datatables.net/js/jquery.dataTables.min.js'); ?>"></script>
		<script src="<?= base_url($src_assets_template . '/lib/datatables.net-dt/js/dataTables.dataTables.min.js'); ?>"></script>
	<?php
	}
	if (isset($chartjs)) { ?>
		<script src="<?= base_url($src_assets_template . '/lib/chart.js/Chart.bundle.min.js'); ?>"></script>
	<?php
	}
	?>
	<script src="<?= base_url($src_assets_template . '/js/azia.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/moment/min/moment.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/jQuery.print.min.js?pid=' . $time); ?>"></script>
	<script src="<?= base_url('assets/pages/bootbox-custom.min.js?pid=' . $time); ?>"></script>
	<script src="<?= base_url('assets/pages/lodash.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/JsBarcode.all.min.js'); ?>"></script>
	<script src="<?= base_url('assets/pages/ctc.js?pid=' . $time); ?>"></script>
	<!-- <script type="text/javascript">
  var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
  (function(){
  var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
  s1.async=true;
  s1.src='https://embed.tawk.to/611cb642d6e7610a49b0b90d/1fdc2ft10';
  s1.charset='UTF-8';
  s1.setAttribute('crossorigin','*');
  s0.parentNode.insertBefore(s1,s0);
  })();
  </script> -->
	<!--End of Tawk.to Script-->
	<?php
	if (isset($add_js)) {
		echo '<script src="' . base_url($add_js) . '?pid=' . $time . '"></script>';
	}
	if (isset($js_control)) {
		if (gettype($js_control) == "string") {
			echo '<script src="' . base_url('assets/pages/' . $js_control) . '?pid=' . $time . '"></script>';
		} else {
			foreach ($js_control as $jsk) {
				echo '<script src="' . $jsk . '?pid=' . $time . '"></script>';
			}
		}
	}
	?>
	<script>
		$(function() {
			'use strict'


			$('#navComplex').lightSlider({
				autoWidth: true,
				pager: false,
				slideMargin: 3,
				onSliderLoad: function(el) {
					$('.lSPrev').addClass('disabled');
				},
				onBeforeSlide: function(el) {
					var curSlide = el.getCurrentSlideCount();
					var totalSlide = el.getTotalSlideCount();

					if (curSlide === 1) {
						$('.lSPrev').addClass('disabled');
					} else {
						$('.lSPrev').removeClass('disabled');
					}

					if ((totalSlide - 3) === curSlide) {
						$('.lSNext').addClass('disabled');
					} else {
						$('.lSNext').removeClass('disabled');
					}
				}
			});

			$('.az-nav-tabs .tab-link').on('click', function(e) {
				e.preventDefault();
				$(this).addClass('active');
				$(this).parent().siblings().find('.tab-link').removeClass('active');

				var target = $(this).attr('href');
				$(target).addClass('active');
				$(target).siblings().removeClass('active');
			})


		});
	</script>
</body>
<!-- dashboard-seven.html  14:08:12 GMT -->

</html>
