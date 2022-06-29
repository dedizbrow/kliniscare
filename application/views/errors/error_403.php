<section class="content">
  <div class="error-pages" style="line-height: 2">
    <div class="error-content">
      <p>
        <?=lang('msg_error_403_description');?> &nbsp;&nbsp;<a href="<?php echo $this->session->userdata('url_403'); ?>"><?php echo $this->session->userdata('url_403'); ?></a>.<br>
        <br>
        <?=lang('msg_error_403_notes');?>
      </p>
    </div>
  </div>
</section>
