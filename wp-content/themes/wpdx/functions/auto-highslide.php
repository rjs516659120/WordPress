<?php
/* Add HighSlide Image Code */
add_filter('the_content', 'addhighslideclass_replace');
function addhighslideclass_replace ($content){
    global $post;
    if( !has_shortcode( $content, 'cmpuser-register' ) ) {    
        $pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
        $replacement = '<a$1href=$2$3.$4$5 class="highslide-image" onclick="return hs.expand(this);"$6>$7</a>';
        $content = preg_replace($pattern, $replacement, $content);
    }
    return $content;
}
/* Add HighSlide */
add_action( 'wp_enqueue_scripts', 'cmp_highslide' );
function cmp_highslide() {
  wp_register_script( 'highslide', get_template_directory_uri() . '/assets/js/highslide.js', array('jquery'), THEME_VER, 1,true );
  wp_register_style( 'highslide', get_template_directory_uri() . '/assets/css/highslide.css','',THEME_VER );
  global $post;
  if( (is_a( $post, 'WP_Post' ) && !has_shortcode( $post->post_content, 'cmpuser-register' ) ) && (is_single() || is_page())){
    wp_enqueue_script( 'highslide' );
    wp_localize_script( 'highslide', 'h_var', array(
      'gDir' => get_template_directory_uri().'/assets/images/highslide/',
        'loadingText' => __('Loading...','wpdx'),
        'loadingTitle' => __('Click to cancel','wpdx'),
        'focusTitle' => __('Click to bring to front','wpdx'),
        'fullExpandTitle' => __('Expand to actual size (f)','wpdx'),
        'previousText' => __('Previous','wpdx'),
        'nextText' => __('Next','wpdx'),
        'moveText' => __('Move','wpdx'),
        'closeText' => __('Close','wpdx'),
        'closeTitle' => __('Close (esc)','wpdx'),
        'resizeTitle' => __('Resize','wpdx'),
        'playText' => __('Play','wpdx'),
        'playTitle' => __('Play slideshow (spacebar)','wpdx'),
        'pauseText' => __('Pause','wpdx'),
        'pauseTitle' => __('Pause slideshow (spacebar)','wpdx'),
        'previousTitle' => __('Previous (arrow left)','wpdx'),
        'nextTitle' => __('Next (arrow right)','wpdx'),
        'moveTitle' => __('Move','wpdx'),
        'fullExpandText' => __('1:1','wpdx'),
        'number' => __('Image %1 of %2','wpdx'),
        'restoreTitle' => __('Click to close image, click and drag to move. Use arrow keys for next and previous.','wpdx')
      )
    );
    wp_enqueue_style( 'highslide' );
  }
}