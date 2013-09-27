<?php
/**
 * This file contains a function which checks if the MP Videos plugin is installed.
 *
 * @since 1.0.0
 *
 * @package    MP Core
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2013, Move Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */
 
/**
* Check to make sure the MP Videos Plugin is installed.
*
* @since    1.0.0
* @link     http://moveplugins.com/doc/plugin-checker-class/
* @return   array $plugins An array of plugins to be installed. This is passed in through the mp_core_check_plugins filter.
* @return   array $plugins An array of plugins to be installed. This is passed to the mp_core_check_plugins filter. (see link).
*/
if (!function_exists('mp_videos_plugin_check')){
	function mp_videos_plugin_check( $plugins ) {
		
		$add_plugins = array(
			array(
				'plugin_name' => 'MP Videos',
				'plugin_message' => __('You require the MP Videos plugin. Install it here.', 'mp_migrate'),
				'plugin_filename' => 'mp-videos.php',
				'plugin_download_link' => 'http://moveplugins.com/repo/mp-videos/?downloadfile=true',
				'plugin_info_link' => 'http://moveplugins.com/plugins/mp-videos',
				'plugin_group_install' => true,
				'plugin_required' => true,
				'plugin_wp_repo' => true,
			)
		);
		
		return array_merge( $plugins, $add_plugins );
	}
}
add_filter( 'mp_core_check_plugins', 'mp_videos_plugin_check' );