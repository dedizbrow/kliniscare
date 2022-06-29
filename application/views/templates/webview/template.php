<!DOCTYPE HTML>
<html lang="en">
<?php
$src_assets_template = "assets/webview";
$time = (int) rand();
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <title><?=conf('lab_nama_klinik_id');?></title>
    <link rel="stylesheet" type="text/css" href="<?=base_url($src_assets_template.'/styles/bootstrap.css');?>">
    <link rel="stylesheet" type="text/css" href="<?=base_url($src_assets_template.'/fonts/bootstrap-icons.css');?>">
    <link rel="stylesheet" type="text/css" href="<?=base_url($src_assets_template.'/styles/style.css?id='.$time);?>">
    <link rel="stylesheet" type="text/css" href="<?=base_url($src_assets_template.'/styles/jquery-ui.css');?>">
    <link href="<?= base_url($src_assets_template . '/styles/select2.min.css'); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="manifest" href="<?=base_url($src_assets_template.'/_manifest.json');?>">
    <meta id="theme-check" name="theme-color" content="#FFFFFF">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=base_url($src_assets_template.'/app/icons/icon-192x192.png');?>">

    <body class="theme-light">
        <input type="hidden" id="base_url" value="<?= base_url(); ?>">
        <div id="preloader"><div class="spinner-border color-highlight" role="status"></div></div>

        <!-- Page Wrapper-->
        <div id="page">

            <!-- Footer Bar -->
            <div id="footer-bar" class="footer-bar-1 footer-bar-detached">
                <a href="<?=base_url('webview/lab/pemeriksaan/registrasi');?>"><i class="bi bi-clipboard-plus"></i><span>Pendaftaran</span></a>
                                <a href="<?=base_url('webview/history');?>"><i class="bi bi-clock-history"></i><span>History</span></a>
                <a href="<?=base_url('webview');?>" class="circle-nav-2"><i class="bi bi-house-fill"></i><span>Home</span></a>
                <a href="<?=base_url('webview/kwitansi');?>"><i class="bi bi-receipt"></i><span>Kwitansi</span></a>
                <a href="<?=base_url('webview/account');?>"><i class="bi bi-person"></i><span>Account</span></a>
            </div>

            <!-- Page Content - Only Page Elements Here-->
            <?= (isset($contents)) ? $contents : ""; ?>

        </div>
        <!-- End of Page Content-->

        <!-- Off Canvas and Menu Elements-->
        <!-- Always outside the Page Content-->
        

        <div class="offcanvas offcanvas-bottom rounded-m offcanvas-detached" id="menu-install-pwa-ios">
         <div class="content">
             <img src="assets/webview/app/icons/icon-128x128.png" alt="img" width="80" class="rounded-m mx-auto my-4">
             <h1 class="text-center">Install App</h1>
             <p class="boxed-text-xl">
              Install App on your home screen, and access it just like a regular app. Open your Safari menu and tap "Add to Home Screen".
          </p>
          <a href="#" class="pwa-dismiss close-menu color-theme text-uppercase font-900 opacity-50 font-11 text-center d-block mt-n2" data-bs-dismiss="offcanvas">Maybe Later</a>
      </div>
  </div>

  <div class="offcanvas offcanvas-bottom rounded-m offcanvas-detached" id="menu-install-pwa-android">
     <div class="content">
         <img src="assets/webview/app/icons/icon-128x128.png" alt="img" width="80" class="rounded-m mx-auto my-4">
         <h1 class="text-center">Install App</h1>
         <p class="boxed-text-l">
             Install App to your Home Screen to enjoy a unique and native experience.
         </p>
         <a href="#" class="pwa-install btn btn-m rounded-s text-uppercase font-900 gradient-highlight shadow-bg shadow-bg-s btn-full">Add to Home Screen</a><br>
         <a href="#" data-bs-dismiss="offcanvas" class="pwa-dismiss close-menu color-theme text-uppercase font-900 opacity-60 font-11 text-center d-block mt-n1">Maybe later</a>
     </div>
 </div>
</div>
</div>
<!-- End of Page ID-->
<script src="<?=base_url($src_assets_template . '/scripts/jquery.min.js'); ?>"></script>
<script src="<?=base_url($src_assets_template.'/scripts/bootstrap.min.js');?>"></script>
<script src="<?= base_url($src_assets_template . '/scripts/datepicker.js'); ?>"></script>
<script src="<?= base_url($src_assets_template . '/scripts/select2.min.js'); ?>"></script>
<script src="<?=base_url($src_assets_template.'/scripts/custom.js');?>"></script>
<script src="<?=base_url('assets/js/jquery.mask.js');?>"></script>
<script src="<?=base_url($src_assets_template.'/scripts/bootbox-custom.min.js');?>"></script>
<script src="<?=base_url('assets/pages/ctc.js?pid=' . $time); ?>"></script>
<?php
if (isset($add_js)) {
    echo '<script src="' . base_url($add_js) . '?pid=' . $time . '"></script>';
}
if (isset($js_control)) {
    if (gettype($js_control) == "string") {
        echo '<script src="' . base_url('assets/webview/js/' . $js_control) . '?pid=' . $time . '"></script>';
    } else {
        foreach ($js_control as $jsk) {
            echo '<script src="' . $jsk . '?pid=' . $time . '"></script>';
        }
    }
}
?>
</body>
