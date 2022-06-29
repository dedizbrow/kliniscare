<div class="az-sidebar az-sidebar-sticky">
  <div class="az-sidebar-header text-center">
    <a href="<?= base_url(); ?>admin/home" class="d-md-none d-lg-block" style="padding-top: 10px">
      <!-- <img class='img-logo' width="200px" src="<?= base_url(conf('company_logo')); ?>" /> -->
      <!-- <div class="company-name hidden-xs" style="font-size: 12px"><b><?= conf('company_name'); ?></b></div> -->
      <!-- <i class="az-logo-small-text hidden-xs"><?= conf('app_name'); ?></i> -->
    </a>

  </div><!-- az-sidebar-header -->
  <div class="az-sidebar-loggedin">
		<div class="az-img-user online"><img src="<?=base_url($DEF_CLINIC_LOGO);?>" alt=""></div>
		<div class="media-body">
			<h6><?=$C_EMAIL;?></h6>
			<span><?=getClinic()->name;?></span>
		</div><!-- media-body -->
	</div>
  <div class="az-sidebar-body">
    <ul class="nav">
      <!-- <li class="nav-label">Main Menu</li> -->
      <li class="nav-item">
        <a href="<?= base_url(); ?>admin/home" class="nav-link"><i class="fa fa-home"></i> Home</a>
      </li>
      <?php
      $build_menu = '';
      $segment1 = $this->uri->segment(1, "");
      $segment2 = $this->uri->segment(2, "");
      $main_menu = $this->session->userdata($app_code . 'CTC-MENUS');
      if (isset($main_menu) && gettype($main_menu) != 'undefined' && $main_menu != null) {
        foreach ($main_menu as $key => $menu) {
          $menu = (object) $menu;
          $show = (strpos($menu->url, $segment1) !== false) ? 'show active' : '';
          $build_menu .= '<li class="nav-item ' . $show . '" data-segment1="' . $segment1 . " -- " . $menu->url . '">';
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
  </div><!-- az-sidebar-body -->
</div><!-- az-sidebar -->
