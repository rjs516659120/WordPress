<?php
// function to display number of posts.
function cmp_the_views( $text ='' , $postID ='' ){
    echo cmp_views( $text , get_the_ID() );
}

function cmp_views( $text ='' , $postID ='' ){
    if( !cmp_get_option( 'post_views_enable' ) ){
        return false;
    }

    global $post;

    if( empty($postID) ){
        $postID = $post->ID ;
    }

    $count_key 	= 'views';
    $count 		= get_post_meta($postID, $count_key, true);
    $count 		= @number_format($count);
    if( empty($count) ){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, 0 );
        $count = 0;
    }
    return $count.' '.$text;
}

// function to count views.
function cmp_setPostViews() {
	global $post, $page;

	if( !cmp_get_option( 'post_views_enable' ) || $page > 1  || function_exists('the_views')){
		return false;
	}

	$count 		= 0;
	$postID 	= $post->ID ;
    $count_key 	= 'views';
    $count 		= (int)get_post_meta($postID, $count_key, true);

    if( !defined('WP_CACHE') || !WP_CACHE ){
      $count++;
      update_post_meta($postID, $count_key, (int)$count);
  }
}

### Function: Calculate Post Views With WP_CACHE Enabled
add_action('wp_enqueue_scripts', 'cmp_postview_cache_count_enqueue');
function cmp_postview_cache_count_enqueue() {
    if( function_exists('the_views')){
        return false;
    }
	global $post;
	if ( is_singular() && ( defined('WP_CACHE') && WP_CACHE) && cmp_get_option( 'post_views_enable' ) ) {
		// Enqueue and localize script here
		wp_register_script( 'cmp-postviews-cache', get_template_directory_uri() . '/assets/js/postviews-cache.js', array( 'jquery' ) );
		wp_localize_script( 'cmp-postviews-cache', 'cmpViewsCacheL10n', array('admin_ajax_url' => admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http')), 'post_id' => intval($post->ID)));
		wp_enqueue_script( 'cmp-postviews-cache');
	}
}

### Function: Increment Post Views
add_action('wp_ajax_postviews', 'cmp_increment_views');
add_action('wp_ajax_nopriv_postviews', 'cmp_increment_views');
function cmp_increment_views() {
    if( function_exists('the_views')){
        return false;
    }
	global $wpdb;
	if(!empty($_GET['postviews_id']) && cmp_get_option( 'post_views_enable' ))
	{
		$post_id = intval($_GET['postviews_id']);
		if($post_id > 0 && defined('WP_CACHE') && WP_CACHE) {
			$count 		= 0;
			$count_key 	= 'views';
			$count 		= (int)get_post_meta($post_id, $count_key, true);
			$count++;

			update_post_meta($post_id, $count_key, (int)$count);
			echo $count;
		}
	}
	exit();
}


// Add it to a column in WP-Admin
add_filter('manage_posts_columns', 'cmp_posts_column_views');
add_action('manage_posts_custom_column', 'cmp_posts_custom_column_views',5,2);
function cmp_posts_column_views($defaults){
    if( !function_exists('the_views')){
        $defaults['cmp_post_views'] = __( 'Views','wpdx' );
    }
    
    return $defaults;
}
function cmp_posts_custom_column_views($column_name, $id){
	if( $column_name === 'cmp_post_views' && !function_exists('the_views')){
        echo cmp_views( '', get_the_ID() );
    }
}