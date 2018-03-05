<?php
add_action('admin_init','cmp_save_default_data');
function cmp_save_default_data(){
	if(get_option( 'cmp_options' )){
		$theme_options = get_option( 'cmp_options' );

		if(!isset($theme_options['time_format'])) $theme_options['time_format'] = 'traditional';
		if(!isset($theme_options['on_home'])) $theme_options['on_home'] = 'latest';
		if(!isset($theme_options['list_style'])) $theme_options['list_style'] = 'small_thumb';
		if(!isset($theme_options['blog_pagination_type'])) $theme_options['blog_pagination_type'] = 'pagination';
		if(!isset($theme_options['archive_pagination_type'])) $theme_options['archive_pagination_type'] = 'traditional';
		if(!isset($theme_options['archive_style'])) $theme_options['archive_style'] = 'small_thumb';
		if(!isset($theme_options['post_note_type'])) $theme_options['post_note_type'] = 'none';
		if(!isset($theme_options['related_query'])) $theme_options['related_query'] = 'tag';
		if(!isset($theme_options['slider_query'])) $theme_options['slider_query'] = 'latest';
		update_option( 'cmp_options' , $theme_options );
	}

}

if( is_admin() ){
	$theme_options = get_option( 'cmp_options' );
	if( get_option('cmp_'.THEME_SLUG.'_active') < 3.0 ){

		$theme_options['blog_pagination_type'] = 'pagination';
		$theme_options['archive_pagination_type'] = 'pagination';
		$theme_options['baidu_search'] = false;
		$theme_options['post_note_type'] = 'none';

		$theme_options['block_dashboard'] = true;
		$theme_options['publish_status'] = 'pending';
		$theme_options['post_success'] = 'Submitted successfully, thank you for your contributions!';
		$theme_options['post_failure'] 	= 'Submit failed, please try again later, if multiple failures, please contact the site administrator for help!';
		$theme_options['display_categories'] 	= 'list';
		$theme_options['category_order'] = 'id';
		$theme_options['editor_style'] 	= 'visual';
		$theme_options['title_required'] = true;
		$theme_options['allow_tags'] 	= true;
		$theme_options['enable_post_edit'] = true;
		$theme_options['disable_pending_edit']  = true;
		$theme_options['user_posts_per_page']  = '10';

		update_option( 'cmp_options' , $theme_options );
		update_option( 'cmp_'.THEME_SLUG.'_active' , '3.0' );
		echo '<script>location.reload();</script>';
		die;
	}

	if( get_option('cmp_'.THEME_SLUG.'_active') < 3.2 ){

		$theme_options['enable_frontend_post'] = true;
		$theme_options['enable_cmpuser']  = true;

		update_option( 'cmp_options' , $theme_options );
		update_option( 'cmp_'.THEME_SLUG.'_active' , '3.2' );
		echo '<script>location.reload();</script>';
		die;
	}

	if( get_option('cmp_'.THEME_SLUG.'_active') < 3.3 ){

		$theme_options['edd_per_page'] = '10';
		$theme_options['edd_archive_style'] = 'little_thumb';
		$theme_options['edd_related'] = true;
		$theme_options['edd_related_number'] = '4';
		$theme_options['edd_related_query'] = 'category';
		$theme_options['edd_share_post'] = true;
		$theme_options['edd_open_comments']	 = true;
		$theme_options['edd_add_product_name'] = true;

		update_option( 'cmp_options' , $theme_options );
		update_option( 'cmp_'.THEME_SLUG.'_active' , '3.3' );
		echo '<script>location.reload();</script>';
		die;
	}
//For Debugging 
//update_option( 'cmp_'.THEME_SLUG.'_active' , '3.3' );
//delete_option( 'cmp_'.THEME_SLUG.'_active');
}
?>