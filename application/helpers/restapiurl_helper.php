<?php
	function base_restapi($url){
		$CI =& get_instance();
        return $CI->config->config['url_restapi']."/".$url;
	}
?>
