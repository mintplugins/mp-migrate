<?php

/**
 * Function which migrates all news custom post types
 *
 */
function mp_migrate_malachi_news(){	

	//Make sure "news" category exists in 'post' posttype
	$news_cat_arg = array('description' => 'A collection of news articles', 'parent' => '' );
		
	if( !is_term( 'News', 'category' ) ){
 		$news_cat_id = wp_insert_term("News", "category", $news_cat_arg);
	}
		
	//Get all cpt_news posts
	$cpt_news_posts = mp_core_get_all_posts_by_type( 'cpt_news' );
	
	//Loop through each cpt_news post
	foreach( $cpt_news_posts as $post_id => $cpt_news_post ){
						
		//Change post type 
		$this_post = array();
		$this_post['ID'] = $post_id;
		$this_post['post_type'] = 'post';
		
		// Update the post into the database
		wp_update_post( $this_post );	
		
		//Put this post in the news category we created at the start of this function
		$category_ids = array( $news_cat_id['term_id'] );
		wp_set_object_terms( $post_id, $category_ids, 'category');
	}
	
}
add_action('admin_init', 'mp_migrate_malachi_news');

/**
 * Function which migrates all photos custom post types
 *
 */
function mp_migrate_malachi_photos(){	

	//Make sure "photos" category exists in 'post' posttype
	$photos_cat_arg = array('description' => 'A collection of Photo Albums', 'parent' => '' );
		
	if( !is_term( 'Photo Albums', 'category' ) ){
 		$photos_cat_id = wp_insert_term("Photo Albums", "category", $photos_cat_arg);
	}
		
	//Get all cpt_photoalbums posts
	$cpt_photos_posts = mp_core_get_all_posts_by_type( 'cpt_photoalbums' );
		
	//Loop through each cpt_photoalbums post
	foreach( $cpt_photos_posts as $post_id => $cpt_photos_post ){
		
		//Get attached images
		$images = get_children(array(
			'post_parent' => $post_id,
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_mime_type' => 'image',
			)
		);
		
		//Assemble gallery shortcode
		$output_html = '[gallery ids="'; 
		
		foreach ( $images as $id => $image ){
			$output_html .= $id . ',';
		}
		
		$output_html .= '"]';		
		
		//Change post type 
		$this_post = array();
		$this_post['ID'] = $post_id;
		$this_post['post_type'] = 'post';
		$this_post['post_content'] = $output_html;
		
		// Update the post into the database
		wp_update_post( $this_post );	
		
		//Put this post in the news category we created at the start of this function
		$category_ids = array( $photos_cat_id['term_id'] );
		wp_set_object_terms( $post_id, $category_ids, 'category');
	}
	
}
add_action('admin_init', 'mp_migrate_malachi_photos');

/**
 * Function which migrates all videos custom post types
 *
 */
function mp_migrate_malachi_videos(){	

	//Plugin check for videos plugin
	if(!function_exists('mp_videos_textdomain')){
		
		/**
		 * Check if mp_videos in installed
		 */
		include_once( MP_MIGRATE_PLUGIN_DIR . 'includes/plugin-checker/included-plugins/mp-videos.php' );
	}
	//Videos plugin has been installed
	else{

		//Get all cpt_videos posts
		$cpt_videos_posts = mp_core_get_all_posts_by_type( 'cpt_videos' );
		
		//Loop through each cpt_videos post
		foreach( $cpt_videos_posts as $post_id => $cpt_videos_post ){
							
			//Change post type 
			$this_post = array();
			$this_post['ID'] = $post_id;
			$this_post['post_type'] = 'mp_video';
			
			// Update the post into the database
			wp_update_post( $this_post );	
			
		}
		
	}
	
}
add_action('load_textdomain', 'mp_migrate_malachi_videos');

/**
 * Function which migrates all sermon custom post types
 *
 */
function mp_migrate_malachi_sermons(){	

	//Plugin check for videos plugin
	if(!function_exists('mp_sermons_textdomain')){
		
		/**
		 * Check if mp_videos in installed
		 */
		include_once( MP_MIGRATE_PLUGIN_DIR . 'includes/plugin-checker/included-plugins/mp-sermons.php' );
	}
	//Videos plugin has been installed
	else{

		//Get all cpt_videos posts
		$cpt_videos_posts = mp_core_get_all_posts_by_type( 'cpt_sermons' );
		
		//Loop through each cpt_sermon post
		foreach( $cpt_videos_posts as $post_id => $cpt_videos_post ){
										
			//Change post type  to mp_sermon
			$this_post = array();
			$this_post['ID'] = $post_id;
			$this_post['post_type'] = 'mp_sermon';
			
			// Update the post into the database
			wp_update_post( $this_post );	
			
			//Get Old MP3 URL
			$sermon_mp3 = get_post_meta( $post_id, 'sermonmp3', true);
			
			$sermon_repeater_array = array(
				'0' => array( 
					'title' => get_the_title( $post_id ),
					'mp3' => $sermon_mp3,
				)
			);
				
			
			//Put MP3 in the first position of the jplayer field repeater
			update_post_meta( $post_id, 'jplayer', $sermon_repeater_array);
			
			//Get Old Author Name
			$sermon_author = get_post_meta( $post_id, 'sermonauthor', true);
						
			//Make sure this preacher exists as a preacher tax term
			if( !empty( $sermon_author ) && !is_term( $sermon_author, 'mp_preachers' ) ){
				
				$preacher_tax_arg = array('description' => 'Sermons by ' . $sermon_author, 'parent' => '' );
				$preacher_tax_term = wp_insert_term( $sermon_author, 'mp_preachers', $preacher_tax_arg);
		
			}
			
			//Put this post in the preacher tax we created above
			if ( $preacher_tax_term ){
				
				$preacher_tax_term = get_term_by('name', $sermon_author, 'mp_preachers');
				
				$category_ids = array( $preacher_tax_term->slug );
				
				wp_set_object_terms( $post_id, $category_ids, 'mp_preachers');
			}
				
		}		
		
	}
	
}
add_action('load_textdomain', 'mp_migrate_malachi_sermons');

/**
 * Function which migrates theme options for the Malachi Theme
 *
 */
function mp_migrate_malachi_theme_options(){	
	
	//Migrate logo
	$old_logo = get_option( 'cap_logo_image' );
	set_theme_mod( 'mp_core_logo', $old_logo );
	
	//Map URL
	$old_map = get_option( 'cap_map_link' );
	set_theme_mod( 'mt_malachi_top_bar_map_url', $old_map );
	
	//Meeting Times
	$old_times = get_option( 'cap_worship_times' );
	set_theme_mod( 'mt_malachi_top_bar_times', $old_times );
	
	//Phone Number
	$old_phone = get_option( 'cap_phone_number' );
	set_theme_mod( 'mt_malachi_top_bar_phone', $old_phone );
	
	//Phone Number
	$old_phone = get_option( 'cap_phone_number' );
	set_theme_mod( 'mt_malachi_top_bar_phone', $old_phone );

}
add_action('load_textdomain', 'mp_migrate_malachi_sermons');

/**
 * Function which migrates sliders tp mp_slide for the Malachi Theme
 *
 */
function mp_migrate_malachi_slides(){	

	//Plugin check for mp_slide plugin
	if(!function_exists('mp_slide_textdomain')){
		
		/**
		 * Check if mp_slide in installed
		 */
		include_once( MP_MIGRATE_PLUGIN_DIR . 'includes/plugin-checker/included-plugins/mp-slide.php' );
	}
	//Slide plugin has been installed
	else{
		
		
		//delete_slides();	
		
		//Get the variable that tells us whether this has already gone through
		$mp_slides_already_completed = get_option( 'mp_migrate_malachi_slides' );
		
		//Make sure we haven't already migrated these slides
		if ($mp_slides_already_completed != 'complete' ){	
		
			//Make sure "Homepage Slider" category exists in 'mp_slide' posttype
			$slider_cat_arg = array('description' => 'Slides for the Home Page', 'parent' => '' );
				
			if( !is_term( 'Homepage Slides', 'mp_sliders' ) ){
				$slider_cat_id = wp_insert_term("Homepage Slides", "mp_sliders", $slider_cat_arg);
			}
		
			//Loop through each of the old 6 slides in the old Malachi theme	
			for ($i = 1; $i <= 6; $i++) {
				
				//Create new MP Slide
				$this_post = array(
				  'post_status'           => 'publish', 
				  'post_type'             => 'mp_slide',
				  'post_title'     		  => 'Slide #' . $i //The title of your post
				);
					
				// Insert the slide post into the database
				$post_id = wp_insert_post( $this_post );
				
				//Put this slide in the mp_sliders tax we created above
				$slider_tax_term = get_term_by('name', 'Homepage Slides', 'mp_sliders');
				$category_ids = array( $slider_tax_term->slug );
				wp_set_object_terms( $post_id, $category_ids, 'mp_sliders');
				
						
				//Migrate Slide Image
				$slide_image = get_option( 'cap_slider_image' . $i );
				$slide_image = $slide_image == 'Image' ? NULL : $slide_image;
				
				//Make image an attachment to this post				
				$wp_filetype = wp_check_filetype(basename($slide_image), null );
				$wp_upload_dir = wp_upload_dir();
				$attachment = array(
					'guid' => $wp_upload_dir['url'] . '/' . basename( $slide_image ), 
					//'guid' => $slide_image, 
					'post_mime_type' => $wp_filetype['type'],
					'post_title' => preg_replace('/\.[^.]+$/', '', basename($slide_image)),
					'post_content' => '',
					'post_status' => 'inherit'
				);
				$attach_id = wp_insert_attachment( $attachment, $slide_image, $post_id );
								
				// you must first include the image.php file
				// for the function wp_generate_attachment_metadata() to work
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				$attach_data = wp_generate_attachment_metadata( $attach_id, $slide_image );
				wp_update_attachment_metadata( $attach_id, $attach_data );
				
				//get the attached file url we just saved
				$attached_file_url = get_post_meta( $attach_id, '_wp_attached_file', true);
				//remove the plugins root link from the start of the attachment url if it is there
				$attached_file_url = str_replace( $wp_upload_dir['baseurl'] . '/', "", $attached_file_url );
				//resave the attachment
				update_post_meta( $attach_id, '_wp_attached_file', $attached_file_url);
				
				//Set this attachment to be the featured image
				add_post_meta($post_id, '_thumbnail_id', $attach_id);
									
				//Migrate Slide Link
				$slide_link = get_option( 'cap_slider_link' . $i );
				$slide_link = $slide_link == 'Image' ? NULL : $slide_link;
				update_post_meta( $post_id, 'mp_slide_options_link_url', $slide_link );
			
			}
			
			update_option( 'mp_migrate_malachi_slides', 'complete' );
		
		}
					
	}

}
add_action('load_textdomain', 'mp_migrate_malachi_slides');

/**
 * Function which migrates links tp mp_links for the Malachi Theme
 *
 */
function mp_migrate_malachi_links(){	

	//Plugin check for mp_slide plugin
	if(!function_exists('mp_links_textdomain')){
		
		/**
		 * Check if mp_slide in installed
		 */
		include_once( MP_MIGRATE_PLUGIN_DIR . 'includes/plugin-checker/included-plugins/mp-links.php' );
	}
	//Slide plugin has been installed
	else{
						
		//Get the variable that tells us whether this has already gone through
		$mp_links_already_completed = get_option( 'mp_migrate_malachi_links' );
		
		//Make sure we haven't already migrated these slides
		if ($mp_links_already_completed != 'complete' ){	
		
			//Make sure "Homepage Link Group" category exists in 'mp_link' posttype
			$link_cat_arg = array('description' => 'Links for the Home Page', 'parent' => '' );
				
			if( !is_term( 'Homepage Links', 'mp_link_groups' ) ){
				$slider_cat_id = wp_insert_term("Homepage Links", "mp_link_groups", $link_cat_arg);
			}
		
			//Loop through each of the old 4 link in the old Malachi theme	
			for ($i = 1; $i <= 4; $i++) {
				
				if ($i == 1){ $option_name = 'cap_youtube_url'; }
				if ($i == 2){ $option_name = 'cap_vimeo_url'; }
				if ($i == 3){ $option_name = 'cap_twitter_url'; }
				if ($i == 4){ $option_name = 'cap_facebook_url'; }
								
				//Create new MP Slide
				$this_post = array(
				  'post_status'           => 'publish', 
				  'post_type'             => 'mp_link',
				  'post_title'     		  => 'Link #' . $i //The title of your post
				);
					
				// Insert the link post into the database
				$post_id = wp_insert_post( $this_post );
				
				//Put this link in the mp_link_groups tax we created above
				$link_tax_term = get_term_by('name', 'Homepage Links', 'mp_link_groups');
				$category_ids = array( $link_tax_term->slug );
				wp_set_object_terms( $post_id, $category_ids, 'mp_link_groups');				
									
				//Migrate Link URL
				$slide_link = get_option( $option_name );
			
				update_post_meta( $post_id, 'link_url', $slide_link );
				
				//Migrate Link Type
				$i == 1 ? update_post_meta( $post_id, 'link_type', 'mp-links-youtube' ) : NULL;
				$i == 2 ? update_post_meta( $post_id, 'link_type', 'mp-links-vimeo' ) : NULL;
				$i == 3 ? update_post_meta( $post_id, 'link_type', 'mp-links-twitter' ) : NULL;
				$i == 4 ? update_post_meta( $post_id, 'link_type', 'mp-links-facebook' ) : NULL;
	
			
			}
			
			update_option( 'mp_migrate_malachi_links', 'complete' );
		
		}
					
	}

}
add_action('load_textdomain', 'mp_migrate_malachi_links');

/**
 * Function which migrates links tp mp_links for the Malachi Theme
 *
 */
function mp_migrate_malachi_events(){	

	//Plugin check for mp_events plugin
	if(!function_exists('mp_events_textdomain')){
		
		/**
		 * Check if mp_events in installed
		 */
		include_once( MP_MIGRATE_PLUGIN_DIR . 'includes/plugin-checker/included-plugins/mp-events.php' );
	}
	//Slide plugin has been installed
	else{
		
		//Get all cpt_events posts
		$cpt_events_posts = mp_core_get_all_posts_by_type( 'cpt_events' );
		
		//Loop through each cpt_events post
		foreach( $cpt_events_posts as $post_id => $cpt_events_post ){
										
			//Change post type  to mp_event
			$this_post = array();
			$this_post['ID'] = $post_id;
			$this_post['post_type'] = 'mp_event';
			
			// Update the post into the database
			wp_update_post( $this_post );	
			
			//Update Start Date
			update_post_meta( $post_id, 'event_start_date', get_the_date( 'Y-m-d', $post_id ) );
			
			//Update Start Time
			update_post_meta( $post_id, 'event_start_time', get_post_time( 'g:i A', $post_id ) );
			
			//Update Address
			update_post_meta( $post_id, 'event_street_address', get_post_meta( $post_id, 'address', true ) );
			
			//Update Map Link
			update_post_meta( $post_id, 'event_map_url', get_post_meta( $post_id, 'map', true ) );
		}		
	}

}
add_action('load_textdomain', 'mp_migrate_malachi_events');


function delete_links(){
	//Get all mp_slide posts
	$mp_slides = mp_core_get_all_posts_by_type( 'mp_link' );

	foreach ( $mp_slides as $post_id => $mp_slide ){
		wp_delete_post( $post_id);	
	}
}