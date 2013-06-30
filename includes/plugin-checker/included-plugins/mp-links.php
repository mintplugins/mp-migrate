<?php
/**
 * Install mp_links Plugin
 *
 */
 if (!function_exists('mp_links_plugin_check')){
	function mp_links_plugin_check() {
		$args = array(
			'plugin_name' => 'MP Links', 
			'plugin_message' => __('You require the MP Links plugin. Install it here.', 'mp_links'), 
			'plugin_slug' => 'mp-links', 
			'plugin_filename' => 'mp-links.php',
			'plugin_required' => true,
			'plugin_download_link' => 'http://repo.moveplugins.com/repo/mp-links/?downloadfile=true'
		);
		$mp_links_plugin_check = new MP_CORE_Plugin_Checker($args);
	}
 }
add_action( '_admin_menu', 'mp_links_plugin_check' );

