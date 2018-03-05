<?php
/*-----------------------------------------------------------------------------------*/
# Register main Scripts and Styles
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_enqueue_scripts', 'cmp_register' );
function cmp_register() {
  if(cmp_get_option('jquery_cdn') =='default'){
    wp_enqueue_script( 'jquery' );
  }else{
    wp_deregister_script( 'jquery' );
    if(cmp_get_option('jquery_cdn') == 'jquery') {
      $jquery_cdn = '//code.jquery.com/jquery-1.10.2.min.js';
      $jquery_migrate_cdn = '//code.jquery.com/jquery-migrate-1.2.1.js';
    }elseif (cmp_get_option('jquery_cdn') == 'google') {
      $jquery_cdn = '//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js';
    }elseif (cmp_get_option('jquery_cdn') == 'mrosoft') {
      $jquery_cdn = '//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js';
      $jquery_migrate_cdn = '//ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.2.1.min.js';
    }elseif (cmp_get_option('jquery_cdn') == 'baidu') {
      $jquery_cdn = '//libs.baidu.com/jquery/1.8.3/jquery.min.js';
    }elseif (cmp_get_option('jquery_cdn') == 'sae') {
      $jquery_cdn = '//lib.sinaapp.com/js/jquery/1.10.2/jquery-1.10.2.min.js';
      $jquery_migrate_cdn = '//lib.sinaapp.com/js/jquery.migrate/1.2.1/jquery-migrate-1.2.1.min.js';
    } elseif (cmp_get_option('jquery_cdn') == 'upyun') {
      $jquery_cdn = '//upcdn.b0.upaiyun.com/libs/jquery/jquery-1.8.3.min.js';
    }elseif (cmp_get_option('jquery_cdn') == 'qiniu') {
      $jquery_cdn = '//cdn.staticfile.org/jquery/1.8.3/jquery.min.js';
    }
    if(isset($jquery_cdn)) wp_register_script( 'jquery',$jquery_cdn, false, THEME_VER );
    wp_enqueue_script( 'jquery' );
    if(isset($jquery_migrate_cdn) && $jquery_migrate_cdn){
      wp_register_script( 'jquery-migrate-cdn',$jquery_migrate_cdn, false,'1.2.1' );
      wp_enqueue_script( 'jquery-migrate-cdn' );
    }
  }
  wp_register_script( 'base-js', get_template_directory_uri() . '/assets/js/base.js', array('jquery'), THEME_VER,1,true );
  wp_enqueue_script( 'base-js' );
  if ( is_home() || is_active_widget( '', '', 'slider-widget' ) ) {
    wp_register_script( 'BxSlider', get_template_directory_uri() . '/assets/js/BxSlider.min.js', array('jquery'), THEME_VER,1,true );
    wp_enqueue_script( 'BxSlider' );
  }

  if( cmp_get_option('share_post')||cmp_get_option('edd_share_post')){
    wp_register_script( 'share-js', get_template_directory_uri() . '/assets/js/jquery.share.min.js', array('jquery'), THEME_VER , 1,true );
    wp_enqueue_script( 'share-js' );
  }

  if(cmp_get_option('blog_pagination_type') == 'ajax' || cmp_get_option('archive_pagination_type') == 'ajax' ){
    wp_register_script( 'ajax-paged', get_template_directory_uri() . '/assets/js/jquery-ias.js', array('jquery'), THEME_VER , 1,true);
    if(is_home()||is_front_page()){
      $ajax_num = cmp_get_option('blog_ajax_num') ? cmp_get_option('blog_ajax_num') : '5';
    }else{
      $ajax_num = cmp_get_option('archive_ajax_num') ? cmp_get_option('archive_ajax_num') : '5';
    }
    $lazy_on = 0;
    if(cmp_get_option( 'lazyload' )) $lazy_on = 1;
    wp_localize_script( 'ajax-paged', 'paged_var', array(
      'loading' => __('LOADING ...','wpdx'),
      'ajax_num' => $ajax_num + 1,
      'lazy_on' => $lazy_on,
      'more' => __('LOAD MORE','wpdx'),
      'last' => __('Already the last page','wpdx')
      )
    );
    if(( (is_home() || is_front_page()) && cmp_get_option('blog_pagination_type') == 'ajax') || (is_archive() && !is_author() && cmp_get_option('archive_pagination_type') == 'ajax')) wp_enqueue_script( 'ajax-paged' );
  }

  wp_register_style( 'font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css','','' );
  wp_register_style( 'default', get_template_directory_uri() . '/style.css','',THEME_VER );
  wp_register_style( 'vertical', get_template_directory_uri() . '/assets/css/style-vertical.css','',THEME_VER );
  wp_enqueue_style( 'font-awesome' );
  wp_enqueue_style( 'default' );
  if(cmp_get_option('theme_layout') == 'vertical' )wp_enqueue_style( 'vertical' );
  if(is_singular() && comments_open()){
    wp_enqueue_script( 'comments-ajax', get_template_directory_uri() . '/comments-ajax.js', array('jquery'), THEME_VER, 1,true);
    if(cmp_get_option('comment_bottom')) $top1 = '180'; else $top1 = '200';
    wp_localize_script( 'comments-ajax', 'comments_ajax_var', array(
      't1' => __('Being submitted, please wait ...','wpdx'),
      't2' => __('Submitted successfully','wpdx'),
      't3' => __('Prior to refresh the page, you can','wpdx'),
      't4' => __('edit again','wpdx'),
      't5' => __('Cancel Edit','wpdx'),
      'top1' => $top1
      )
    );
  }
  if(is_singular() && cmp_get_option('smilies')){
    wp_enqueue_script( 'smilies', get_template_directory_uri() . '/assets/js/smilies.js', '', THEME_VER, 1,true);
  }
  global $post;
  if ( comments_open() && is_singular() && $post->post_type != 'dwqa-question' && $post->post_type != 'dwqa-answer' && !is_page('new-post' ) && !is_page('edit' ) && cmp_get_option('comment_quicktags')) {
    wp_enqueue_script( 'my-quicktags', get_template_directory_uri() . '/assets/js/my-quicktags.js', array('quicktags','jquery'), THEME_VER, 1,true);
  }
}
/*-----------------------------------------------------------------------------------*/
# Cmp Wp Head
/*-----------------------------------------------------------------------------------*/
add_action('wp_head', 'cmp_wp_head');
function cmp_wp_head() {
  echo '
  <!--[if lt IE 9]>
  <script src="'.get_template_directory_uri().'/assets/js/html5.js"></script>
  <script src="'. get_template_directory_uri().'/assets/js/css3-mediaqueries.js"></script>
  <![endif]-->
  <!--[if IE 8]>
  <link rel="stylesheet" href="'. get_template_directory_uri().'/assets/css/ie8.css">
  <![endif]-->
  ';
  if(cmp_get_option('theme_color') && cmp_get_option('theme_color') !=='default' ){
    echo '
    <link rel="stylesheet" type="text/css" media="all" href="'. get_template_directory_uri().'/assets/css/style-'.cmp_get_option("theme_color").'.css" />';
  }
  if(cmp_get_option('logo')){
    echo '<style type="text/css" media="screen">';
    echo '#logo .logoimg{background:url("'.cmp_get_option('logo').'") no-repeat scroll 0 0 transparent;}';
    echo '.style-vertical #logo .logoimg{background:url("'.cmp_get_option('logo').'") no-repeat scroll 0 0 transparent;line-height:70px;}';
    echo '</style>';
  }
  if(cmp_get_option('custom_css')){
    echo '<style type="text/css" media="screen">';
    echo htmlspecialchars_decode( cmp_get_option('css') ) , "\n";
    echo '</style>';
  }
  if( cmp_get_option('header_code') ){
    echo htmlspecialchars_decode( cmp_get_option('header_code') ) , "\n";
  }
}
/*-----------------------------------------------------------------------------------*/
# Cmp Wp Footer
/*-----------------------------------------------------------------------------------*/
add_action('wp_footer', 'cmp_wp_footer');
function cmp_wp_footer() {
  if( cmp_get_option('right_rolling') ){
    if( is_single() ){
      $r_1 = cmp_get_option('right_one')?cmp_get_option('right_one'):0;
      $r_2 = cmp_get_option('right_two')?cmp_get_option('right_two'):0;
    }elseif( is_home() || is_front_page() ){
      $r_1 = cmp_get_option('right_h_one')?cmp_get_option('right_h_one'):0;
      $r_2 = cmp_get_option('right_h_two')?cmp_get_option('right_h_two'):0;
    }elseif( is_page() ){
      $r_1 = cmp_get_option('right_p_one')?cmp_get_option('right_p_one'):0;
      $r_2 = cmp_get_option('right_p_two')?cmp_get_option('right_p_two'):0;
    }else{
      $r_1 = 0;
      $r_2 = 0;
    }
    echo '<script>var right_1 = '.$r_1.',right_2 = '.$r_2.';</script>';
    echo '<script src="'.get_template_directory_uri().'/assets/js/post.js"></script>';
  }

if( cmp_get_option('show_weibo')){ ?>
<script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js" type="text/javascript" charset="utf-8"></script>
<?php }
}