<?php
/**
 * Install mp_videos Plugin
 *
 */
 if (!function_exists('mp_videos_plugin_check')){
	function mp_videos_plugin_check() {
		$args = array(
			'plugin_name' => 'MP Videos', 
			'plugin_message' => __('You require the Move Plugins Videos plugin. Install it here.', 'mp_videos'), 
			'plugin_slug' => 'mp-videos', 
			'plugin_filename' => 'mp-videos.php',
			'plugin_required' => true,
			'plugin_download_link' => 'http://repo.moveplugins.com/repo/mp-videos/?downloadfile=true'
		);
		$mp_videos_plugin_check = new MP_CORE_Plugin_Checker($args);
	}
 }
add_action( '_admin_menu', 'mp_videos_plugin_check' );

