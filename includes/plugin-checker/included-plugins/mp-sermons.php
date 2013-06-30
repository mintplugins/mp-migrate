<?php
/**
 * Install mp_sermons Plugin
 *
 */
 if (!function_exists('mp_sermons_plugin_check')){
	function mp_sermons_plugin_check() {
		$args = array(
			'plugin_name' => 'MP Sermons', 
			'plugin_message' => __('You require the MP Sermons plugin. Install it here.', 'mp_sermons_text'), 
			'plugin_slug' => 'mp-sermons', 
			'plugin_filename' => 'mp-sermons.php',
			'plugin_required' => true,
			'plugin_download_link' => 'http://repo.moveplugins.com/repo/mp-sermons/?downloadfile=true'
		);
		$mp_sermons_plugin_check = new MP_CORE_Plugin_Checker($args);
	}
 }
add_action( '_admin_menu', 'mp_sermons_plugin_check' );

