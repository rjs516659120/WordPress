<?php

// include (TEMPLATEPATH . '/includes/widgets/widget-author.php');
// include (TEMPLATEPATH . '/includes/widgets/widget-custom-author.php');
include (TEMPLATEPATH . '/includes/widgets/widget-tag-cloud.php');
//include (TEMPLATEPATH . '/includes/widgets/widget-statistics.php');
include (TEMPLATEPATH . '/includes/widgets/widget-recently-viewed.php');

//include (TEMPLATEPATH . '/includes/widgets/widget-qr-code.php');

include (TEMPLATEPATH . '/includes/widgets/widget-posts.php');
include (TEMPLATEPATH . '/includes/widgets/widget-category.php');
include (TEMPLATEPATH . '/includes/widgets/widget-news-pic.php');
include (TEMPLATEPATH . '/includes/widgets/widget-text-html.php');
include (TEMPLATEPATH . '/includes/widgets/widget-follow-subscribe.php');

//include (TEMPLATEPATH . '/includes/widgets/widget-login.php');

include (TEMPLATEPATH . '/includes/widgets/widget-comment.php');
include (TEMPLATEPATH . '/includes/widgets/widget-slider.php');
include (TEMPLATEPATH . '/includes/widgets/widget-readers.php');

//include (TEMPLATEPATH . '/includes/widgets/widget-list-custom-taxonomy.php');

function remove_default_widgets() {
	if (function_exists('unregister_widget')) {
		unregister_widget( 'WP_Widget_Search' );
		unregister_widget( 'WP_Widget_Recent_Comments' );
		unregister_widget( 'WP_Widget_Search' );
		unregister_widget( 'WP_Widget_Tag_Cloud' );
		unregister_widget( 'WP_Widget_Text' );
	}
}
add_action('widgets_init', 'remove_default_widgets');

## Widgets
add_action( 'widgets_init', 'cmp_widgets_init' );
function cmp_widgets_init() {

	/*=================Widgets For Sidebars Right===========================*/

	$before_widget =  '<div id="%1$s" class="widget-box widget %2$s">';
	$after_widget  =  '</div></div>';
	$before_title  =  '<div class="widget-title"><span class="icon"><i class="fa fa-list fa-fw"></i></span><h3>';
	$after_title   =  '</h3></div><div class="widget-content">';

	register_sidebar( array(
		'name' =>  __( 'Primary Widget Area', 'wpdx' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The Primary widget area', 'wpdx' ),
		'before_widget' => $before_widget , 'after_widget' => $after_widget ,
		'before_title' => $before_title , 'after_title' => $after_title ,
		) );

	if( class_exists('QA_Core') ){
		register_sidebar( array(
		'name' =>  __( 'Questions Widget Area', 'wpdx' ),
		'id' => 'questions-widget-area',
		'description' => __( 'The Questions widget area', 'wpdx' ),
		'before_widget' => $before_widget , 'after_widget' => $after_widget ,
		'before_title' => $before_title , 'after_title' => $after_title ,
		) );
	}

	//Custom Sidebars
	$sidebars = cmp_get_option( 'sidebars' ) ;
	if($sidebars){
		foreach ($sidebars as $sidebar) {
			register_sidebar( array(
				'name' => $sidebar,
				'id' => sanitize_title($sidebar),
				'before_widget' => $before_widget , 'after_widget' => $after_widget ,
				'before_title' => $before_title , 'after_title' => $after_title ,
				) );
		}
	}
}