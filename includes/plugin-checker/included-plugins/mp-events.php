<?php
/**
 * Install mp_events Plugin
 *
 */
 if (!function_exists('mp_events_plugin_check')){
	function mp_events_plugin_check() {
		$args = array(
			'plugin_name' => 'MP Events', 
			'plugin_message' => __('You require the MP Events plugin. Install it here.', 'mp_events'), 
			'plugin_slug' => 'mp-events', 
			'plugin_filename' => 'mp-events.php',
			'plugin_required' => true,
			'plugin_download_link' => 'http://repo.moveplugins.com/repo/mp-events/?downloadfile=true'
		);
		$mp_events_plugin_check = new MP_CORE_Plugin_Checker($args);
	}
 }
add_action( '_admin_menu', 'mp_events_plugin_check' );

