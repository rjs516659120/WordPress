<?php
/**
*重要提示：请将你自己要添加到 functions.php 的所有代码，添加到主题根目录的 custom-functions.php，不要添加到这个文件，不要编辑这个文件！！！！！！！
*/
$my_theme = wp_get_theme();
define ('THEME_NAME', 'wpdx' );
define ('THEME_SLUG', 'wpdx' );
define ('THEME_VER', $my_theme->get( 'Version' ) );
define ('THEME_AUTHOR', 'Changmeng Hu' );
if(!defined('THEME_DOC')) define ('THEME_DOC', 'https://www.wpdaxue.com/docs/wpdx' );

include (TEMPLATEPATH . '/includes/home-cats.php');
include (TEMPLATEPATH . '/includes/home-cat-tabs.php');
include (TEMPLATEPATH . '/includes/home-cat-scroll.php');
include (TEMPLATEPATH . '/includes/home-cat-pic.php');
include (TEMPLATEPATH . '/includes/home-recent-box.php');
include (TEMPLATEPATH . '/includes/pagenavi.php');
include (TEMPLATEPATH . '/includes/breadcrumbs.php');
include (TEMPLATEPATH . '/includes/widgets.php');
include (TEMPLATEPATH . '/functions/theme-functions.php');
include (TEMPLATEPATH . '/functions/seo-meta-box.php');
include (TEMPLATEPATH . '/functions/wp_bootstrap_navwalker.php');
include (TEMPLATEPATH . '/functions/categories-metas.php');
include (TEMPLATEPATH . '/functions/common-scripts.php');
include (TEMPLATEPATH . '/functions/seo.php');
include (TEMPLATEPATH . '/custom-functions.php');
include (TEMPLATEPATH . '/panel/mpanel-ui.php');
include (TEMPLATEPATH . '/panel/mpanel-functions.php');
include (TEMPLATEPATH . '/panel/default-options.php');
include (TEMPLATEPATH . '/panel/custom-slider.php');
include (TEMPLATEPATH . '/panel/updates.php');
include (TEMPLATEPATH . '/functions/theme-updater-class.php');
include (TEMPLATEPATH . '/functions/theme-updater.php');
require_once(TEMPLATEPATH . '/cmpuser/includes/user-avatars.php' );
if(cmp_get_option( 'lightbox' )) include (TEMPLATEPATH . '/functions/auto-highslide.php');
if(cmp_get_option( 'lazyload' )) include (TEMPLATEPATH . '/functions/my-lazyload.php');
if(cmp_get_option( 'show_ids' )) include (TEMPLATEPATH . '/functions/show-ids.php');
if(cmp_get_option( 'post_views_enable') ) include (TEMPLATEPATH . '/functions/post-views.php');
if(class_exists('DW_Question_Answer')) include (TEMPLATEPATH . '/dwqa-templates/dwqa-functions.php');
if(!class_exists('Nav_Menu_Roles')) include (TEMPLATEPATH . '/functions/nav-menu-roles.php');
if(class_exists('Easy_Digital_Downloads')){
    include (TEMPLATEPATH . '/functions/functions-edd.php');
}

 
/**
* ！！所有设置结束！！
* 重要提示：请将你自己要添加到 functions.php 的所有代码，添加到主题根目录的 custom-functions.php，不要添加到这个文件，不要编辑这个文件！！！！！！！
*/