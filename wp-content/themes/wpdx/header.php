<!DOCTYPE html>
<html <?php language_attributes(); if( cmp_get_option('show_weibo')) echo ' xmlns:wb="http://open.weibo.com/wb"';?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Cache-Control" content="no-transform" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit">
    <meta name="applicable-device" content="pc,mobile">
    <?php wp_head(); ?>
</head>
<body id="top" <?php body_class(); ?>>
<div class="body-wrap">
    <div id="top-part">
        <?php get_template_part( "includes/top-bar" ) ?>
        <header id="header" role="banner">
            <nav id="main-nav"<?php if( cmp_get_option('theme_layout') =='vertical' && cmp_get_option('nav_fixed')) echo ' class="nav-fixed"'; ?> role="navigation">
            <div id="menu-button"><i class="fa fa-bars fa-fw"></i><?php _e('Navigation menu','wpdx');?></div>
                <ul>
                    <?php if(function_exists('wp_nav_menu')) wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s','theme_location' => 'main-menu', 'fallback_cb' => 'cmp_nav_fallback','walker' => new wp_bootstrap_navwalker() )) ; ?>
                </ul><div class="clear"></div>
            </nav>
        </header>
    </div>
    <div id="main-content">