<?php
/**
 * Install mp_slide Plugin
 *
 */
 if (!function_exists('mp_slide_plugin_check')){
	function mp_slide_plugin_check() {
		$args = array(
			'plugin_name' => 'MP Slide', 
			'plugin_message' => __('You require the MP Slide plugin. Install it here.', 'mp_slide'), 
			'plugin_slug' => 'mp-slide', 
			'plugin_filename' => 'mp-slide.php',
			'plugin_required' => true,
			'plugin_download_link' => 'http://repo.moveplugins.com/repo/mp-slide/?downloadfile=true'
		);
		$mp_slide_plugin_check = new MP_CORE_Plugin_Checker($args);
	}
 }
add_action( '_admin_menu', 'mp_slide_plugin_check' );

