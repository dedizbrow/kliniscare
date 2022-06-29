<!DOCTYPE html>
<html lang="en">
<?php
$src_assets_template = "assets/azia-assets";
$src_view_template = "templates/menu-samping";
$time = (int) rand();
?>

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

<body class="az-body az-body-sidebar az-light az-body-dashboard-nine">
	<?php $this->load->view("$src_view_template/sidebar"); ?>
	<div class="az-content az-content-dashboard-five">
		<div class="az-header pt-2">
			<div class="container-fluid">
				<div class="az-header-left">
					<a href="#" id="azSidebarToggle" class="az-header-menu-icon"><span></span></a>
				</div><!-- az-header-left -->

				<div class="az-header-center" style="margin-left: 50px">
					<!-- <i class="fa fa-home"></i> <b class="page-title"><?= isset($page_title) ? $page_title : "&nbsp;"; ?> </b>
          <small class="page-title-small"><?= isset($page_title_small) ? $page_title_small : "&nbsp;"; ?></small> -->
				</div>
				<div class="az-header-right">

					<div class="az-header-message">
						<a href="app-chat.html"><i class="fa fa-envelop"></i></a>
					</div><!-- az-header-message -->
					<div class="dropdown az-header-notification">
						<!-- <span class="current-datetime"></span> -->
					</div><!-- az-header-notification -->
					<div class="dropdown az-profile-menu">
						<a href="#" class="az-img-user"><img src="<?= $C_USER_PROFILE; ?>" alt="<?= $C_NAME; ?>" height="80px"></a>
						<div class="dropdown-menu">
							<div class="az-dropdown-header d-sm-none">
								<a href="#" class="az-header-arrow"><i class="icon ion-md-arrow-back"></i></a>
							</div>
							<div class="az-header-profile">
								<div class="az-img-user">
									<img src="<?= $C_USER_PROFILE; ?>" alt="No Image">
								</div><!-- az-img-user -->
								<h6><?= $C_NAME; ?></h6>
								<span><?= $C_EMAIL; ?></span>
							</div><!-- az-header-profile -->

							<!-- <a href="<?= base_url(); ?>" class="dropdown-item"><i class="fa fa-home"></i> Home</a> -->
							

							<a href="<?= base_url('profile'); ?>" class="dropdown-item"><i class="fa fa-cog"></i> Pengaturan Akun</a>
							<a href="<?= base_url('admin/auth/signout'); ?>" class="dropdown-item"><i class="fa fa-sign-out"></i> Keluar</a>
							<?php
							if (conf('enable_templating')) { ?>
								<div class="az-footer-profile">
									<i class="fa fa-picture-o"></i> Tema

									<?php
									if (conf('ctc_templates') != null) {
										if ($this->session->userdata($app_code . "CTC-TPL")) {
											$tpls = $this->session->userdata($app_code . "CTC-TPL");
										} else {
											$tpls = conf('ctc_default_template');
										};
										foreach (conf('ctc_templates') as $tpl) {
											$checked = ($tpl == $tpls) ? "checked='true'" : "";
											echo "
                    <li><label class='rdiobox'>
                        <input type='radio' name='switch-template' value='" . $tpl . "' $checked>
                    <span>$tpl</span></label></li>";
										}
									}
									?>
								</div>
							<?php } ?>
						</div><!-- dropdown-menu -->
					</div>
				</div><!-- az-header-right -->
			</div><!-- container -->
		</div><!-- az-header -->
		<div class="az-content-body">
			<?php
			if (!empty(getClinic()) && getClinic()->id == 'allclinic' && (!isset($skip_select_clinic) || $skip_select_clinic === false)) {
			?>
				<div class="form-inline mb-2">
					<div class="input-group">
						<span class="input-group-prepend"><span class="input-group-text bg-primary tx-white"><i class="fa fa-question-circle-o mr-2" data-toggle="tooltip" title="Opsi ini hanya akan muncul untuk akun super-admin yang memiliki akses ke semua klinik"></i> Pilih Klinik </span> </span>
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
			<!-- <div class="row row-sm"> -->
			<?= (isset($contents)) ? $contents : ""; ?>
			<!-- </div> -->
		</div><!-- az-content-body -->
		<div class="az-footer">
			<div class="container-fluid">

				
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
				<input type="hidden" id="server_socket_antrian" value="<?= conf('SERVER_SOCKET_ANTRIAN'); ?>">
				<input type="hidden" id="ref_default_clinic" value="<?=(isset($DEF_CLINIC_ID)) ? $DEF_CLINIC_ID : "";?>">
			</div><!-- container -->
		</div><!-- az-footer -->
	</div><!-- az-content -->
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
	<script src="<?= base_url($src_assets_template . '/lib/jquery-steps/jquery.steps.min.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/parsleyjs/parsley.min.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/perfect-scrollbar/perfect-scrollbar.min.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/jquery.flot/jquery.flot.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/jquery.flot/jquery.flot.resize.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/js/azia.js'); ?>"></script>
	<script src="<?= base_url($src_assets_template . '/lib/moment/min/moment.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/jQuery.print.min.js?pid=' . $time); ?>"></script>
	<script src="<?= base_url('assets/pages/bootbox-custom.min.js?pid=' . $time); ?>"></script>
	<script src="<?= base_url('assets/pages/lodash.min.js'); ?>"></script>
	<?php
		if(isset($sistem_antrian) && $sistem_antrian!==false && conf('SERVER_SOCKET_ANTRIAN')!=""){
	?>
	<script>
	var SocketAntrian=false;
	</script>
<script>
	SocketAntrian = new WebSocket("ws://<?=conf('SERVER_SOCKET_ANTRIAN'); ?>");
	SocketAntrian.onopen = function(e) {
		console.log("Connection to SistemAntrian established!");
		SocketAntrian.send('' + "," + '' + "," + '');
	};
</script>
	<?php		
		}
	?>
	<script src="<?= base_url('assets/pages/ctc.js?pid=' . $time); ?>"></script>
	
	<!--Start of Tawk.to Script-->
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

			$('.az-sidebar .with-sub').on('click', function(e) {
				e.preventDefault();
				$(this).parent().toggleClass('show');
				$(this).parent().siblings().removeClass('show');
			})

			$(document).on('click touchstart', function(e) {
				e.stopPropagation();

				// closing of sidebar menu when clicking outside of it
				if (!$(e.target).closest('.az-header-menu-icon').length) {
					var sidebarTarg = $(e.target).closest('.az-sidebar').length;
					if (!sidebarTarg) {
						$('body').removeClass('az-sidebar-show');
					}
				}
			});


			$('#azSidebarToggle').on('click', function(e) {
				e.preventDefault();

				if (window.matchMedia('(min-width: 992px)').matches) {
					$('body').toggleClass('az-sidebar-hide');
				} else {
					$('body').toggleClass('az-sidebar-show');
				}
			});

			new PerfectScrollbar('.az-sidebar-body', {
				suppressScrollX: true
			});

			/* ----------------------------------- */
			/* Dashboard content */


			$.plot('#flotChart1', [{
				data: dashData1,
				color: '#6f42c1'
			}], {
				series: {
					shadowSize: 0,
					lines: {
						show: true,
						lineWidth: 2,
						fill: true,
						fillColor: {
							colors: [{
								opacity: 0
							}, {
								opacity: 1
							}]
						}
					}
				},
				grid: {
					borderWidth: 0,
					labelMargin: 0
				},
				yaxis: {
					show: false,
					min: 0,
					max: 100
				},
				xaxis: {
					show: false
				}
			});

			$.plot('#flotChart2', [{
				data: dashData2,
				color: '#007bff'
			}], {
				series: {
					shadowSize: 0,
					lines: {
						show: true,
						lineWidth: 2,
						fill: true,
						fillColor: {
							colors: [{
								opacity: 0
							}, {
								opacity: 1
							}]
						}
					}
				},
				grid: {
					borderWidth: 0,
					labelMargin: 0
				},
				yaxis: {
					show: false,
					min: 0,
					max: 100
				},
				xaxis: {
					show: false
				}
			});

			$.plot('#flotChart3', [{
				data: dashData3,
				color: '#f10075'
			}], {
				series: {
					shadowSize: 0,
					lines: {
						show: true,
						lineWidth: 2,
						fill: true,
						fillColor: {
							colors: [{
								opacity: 0
							}, {
								opacity: 1
							}]
						}
					}
				},
				grid: {
					borderWidth: 0,
					labelMargin: 0
				},
				yaxis: {
					show: false,
					min: 0,
					max: 100
				},
				xaxis: {
					show: false
				}
			});

			$.plot('#flotChart4', [{
				data: dashData4,
				color: '#00cccc'
			}], {
				series: {
					shadowSize: 0,
					lines: {
						show: true,
						lineWidth: 2,
						fill: true,
						fillColor: {
							colors: [{
								opacity: 0
							}, {
								opacity: 1
							}]
						}
					}
				},
				grid: {
					borderWidth: 0,
					labelMargin: 0
				},
				yaxis: {
					show: false,
					min: 0,
					max: 100
				},
				xaxis: {
					show: false
				}
			});

			$.plot('#flotChart5', [{
				data: dashData2,
				color: '#00cccc'
			}, {
				data: dashData3,
				color: '#007bff'
			}, {
				data: dashData4,
				color: '#f10075'
			}], {
				series: {
					shadowSize: 0,
					lines: {
						show: true,
						lineWidth: 2,
						fill: false,
						fillColor: {
							colors: [{
								opacity: 0
							}, {
								opacity: 1
							}]
						}
					}
				},
				grid: {
					borderWidth: 0,
					labelMargin: 20
				},
				yaxis: {
					show: false,
					min: 0,
					max: 100
				},
				xaxis: {
					show: true,
					color: 'rgba(0,0,0,.16)',
					ticks: [
						[0, ''],
						[10, '<span>Nov</span><span>05</span>'],
						[20, '<span>Nov</span><span>10</span>'],
						[30, '<span>Nov</span><span>15</span>'],
						[40, '<span>Nov</span><span>18</span>'],
						[50, '<span>Nov</span><span>22</span>'],
						[60, '<span>Nov</span><span>26</span>'],
						[70, '<span>Nov</span><span>30</span>'],
					]
				}
			});

			$.plot('#flotChart6', [{
				data: dashData2,
				color: '#6f42c1'
			}, {
				data: dashData3,
				color: '#007bff'
			}, {
				data: dashData4,
				color: '#00cccc'
			}], {
				series: {
					shadowSize: 0,
					stack: true,
					bars: {
						show: true,
						lineWidth: 0,
						fill: 0.85
						//fillColor: { colors: [ { opacity: 0 }, { opacity: 1 } ] }
					}
				},
				grid: {
					borderWidth: 0,
					labelMargin: 20
				},
				yaxis: {
					show: false,
					min: 0,
					max: 100
				},
				xaxis: {
					show: true,
					color: 'rgba(0,0,0,.16)',
					ticks: [
						[0, ''],
						[10, '<span>Nov</span><span>05</span>'],
						[20, '<span>Nov</span><span>10</span>'],
						[30, '<span>Nov</span><span>15</span>'],
						[40, '<span>Nov</span><span>18</span>'],
						[50, '<span>Nov</span><span>22</span>'],
						[60, '<span>Nov</span><span>26</span>'],
						[70, '<span>Nov</span><span>30</span>'],
					]
				}
			});

			$('#vmap').vectorMap({
				map: 'world_en',
				showTooltip: true,
				backgroundColor: '#f8f9fa',
				color: '#ced4da',
				colors: {
					us: '#6610f2',
					gb: '#8b4bf3',
					ru: '#aa7df3',
					cn: '#c8aef4',
					au: '#dfd3f2'
				},
				hoverColor: '#222',
				enableZoom: false,
				borderOpacity: .3,
				borderWidth: 3,
				borderColor: '#fff',
				hoverOpacity: .85
			});

		});
	</script>
</body>

<!-- dashboard-five.html  14:08:05 GMT -->

</html>
