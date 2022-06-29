<div class="az-header">
  <div class="container bg-transparent">
    <div class="az-header-left">
      <a href="<?= base_url(); ?>admin/home" class="d-md-none d-lg-block">
        <img class='img-logo hidden-xs' src="<?= base_url(conf('company_logo')); ?>" />
      </a>
      <a href="#" id="azNavShow" class="az-header-menu-icon d-lg-none">
        <span></span>
      </a>
      <a class="d-lg-none">
        <img class="img-logo-small" src="<?= base_url(conf('company_logo')); ?>" />
      </a>
    </div><!-- az-header-left -->
    <div class="az-navbar az-navbar-three">
      <ul class="nav">
        <li class="nav-item">
          <a href="<?= base_url(); ?>admin/home" class="nav-link"><i class="fa fa-home"></i> Home</a>
        </li>
        <?php
        $build_menu = '';
        $segment1 = $this->uri->segment(1, "");
        $segment2 = $this->uri->segment(2, "");
        $main_menu = $this->session->userdata(conf('app_code') . 'CTC-MENUS');
        if (isset($main_menu) && gettype($main_menu) != 'undefined' && $main_menu != null) {
          foreach ($main_menu as $key => $menu) {
            $menu = (object) $menu;
            $menu_active = (strpos($menu->url, $segment1) !== false) ? 'active' : '';
            $build_menu .= '<li class="nav-item ' . $menu_active . '">';
            if (isset($menu->sub_menu) && !empty($menu->sub_menu)) {
              $build_menu .= '
              <a href="' . base_url($menu->url) . '" class="nav-link with-sub"><i class="fa ' . $menu->icon . '"></i> ' . $menu->label . '</a>
              <nav class="nav-sub">';
              foreach ($menu->sub_menu as $k => $sub) {
                $sub = (object) $sub;
                $split_s = explode("/", $sub->url);
                $sub_active = (end($split_s) === $segment2) ? 'active' : '';
                $build_menu .= '<a href="' . base_url($sub->url) . '" class="nav-sub-link ' . $sub_active . '">' . $sub->label . '</a>';
              }
              $build_menu .= '</nav>';
            } else {
              $build_menu .= '<a href="' . base_url($menu->url) . '" class="nav-link"><i class="fa ' . $menu->icon . '"></i> ' . $menu->label . '</a>';
            }
            $build_menu .= '</li>';
          }
        }
        echo $build_menu;
        ?>
      </ul><!-- nav -->
    </div><!-- az-navbar -->
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

          <!-- <a href="<?= base_url(); ?>admin/home" class="dropdown-item"><i class="fa fa-home"></i> Home</a> -->
          
          <a href="<?= base_url('profile'); ?>" class="dropdown-item"><i class="fa fa-cog"></i> Pengaturan Akun</a>
          <a href="<?= base_url('admin/auth/signout'); ?>" class="dropdown-item text-danger"><i class="fa fa-sign-out"></i> Keluar</a>
          <?php
          if (conf('enable_templating')) { ?>
            <div class="az-footer-profile text-secondary">
              <i class="fa fa-picture-o"></i> Tema

              <?php
              if (conf('ctc_templates') != null) {
                if ($this->session->userdata(conf('app_code') . "CTC-TPL")) {
                  $tpls = $this->session->userdata(conf('app_code') . "CTC-TPL");
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
  <div class="d-lg-none d-md-none app-desc-mobile">
    <small><?= conf('app_name'); ?></small>
  </div>
  <div class="page-header">
    <!-- <b class="page-title"><span class="tx-purple"><?= (isset($C_PV_NAME)) ? $C_PV_NAME : ""; ?> <i class="fa fa-chevron-right"></i></span> <?= (isset($page_title)) ? $page_title : "&nbsp;"; ?> </b> -->
    <!-- <b class="page-title"><span class="tx-purple"><i class="fa fa-chevron-right"></i></span> <?= (isset($page_title)) ? $page_title : "&nbsp;"; ?> </b>
    <small class="page-title-small"><?= isset($page_title_small) ? $page_title_small : "&nbsp;"; ?></small> -->
    <div class="pull-right link-segment col-sm-6 text-right hidden-xs">
      <?php
      $page_url = "<a href='" . base_url() . "admin/home' style='color: white;'><i class='fa fa-home' style='color: white;'></i> Home</a>";
      $segments = $this->uri->segment_array();
      $total_segments = count($segments);
      $cp_url = base_url();
      for ($sg = 1; $sg <= $total_segments; $sg++) {
        $page_url .= " <i class='fa fa-angle-right'></i> ";
        $cp_url .= $segments[$sg] . "/";
        if ($sg < $total_segments) {
          $page_url .= $segments[$sg];
        } else {
          $page_url .= $segments[$sg];
        }
      }
      echo $page_url;
      ?>
    </div>
  </div>
  <br>
</div><!-- az-header -->