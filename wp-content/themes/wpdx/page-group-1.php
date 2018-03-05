<?php
/*
Template Name: 页面组（1）
*/
get_header(); ?>
  <div id="content-header">
    <?php cmp_breadcrumbs();?>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <?php if ( ! have_posts() ) : ?>
          <div class="widget-box">
            <article class="widget-content single-post">
             <header class="entry-header">
              <h1 class="page-title"><?php _e( 'Not Found', 'wpdx' ); ?></h1>
            </header>
            <div class="entry">
              <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'wpdx' ); ?></p>
              <?php get_search_form(); ?>
            </div>
          </article>
        </div>
      <?php endif; ?>

      <?php while ( have_posts() ) : the_post(); ?>
        <?php if(function_exists('cmp_setPostViews')) cmp_setPostViews(); ?>
        <div class="widget-box user-center page-group">
          <div id="user-left">
            <ul id="user-menu">
             <?php if(function_exists('wp_nav_menu')) wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'page-group-1', 'fallback_cb' => 'cmp_nav_fallback','walker' => new wp_bootstrap_navwalker())); ?>
           </ul>
         </div>
         <div class="widget-content single-post" id="user-right">
          <div id="post-header">
          <?php if(class_exists("Fep_Message")){
              $user_info = get_userdata('1');
              $pm_id = cmp_get_page_id_by_shortcode('front-end-pm');
              echo '<div class="feedback"><a href="'.get_permalink($pm_id).'?fepaction=newmessage&to='.$user_info->user_login.'" ><i class="fa fa-paper-plane-o"></i> '.__('Feedback ','wpdx').'</a></div>';
            }
            ?>
            <h1 class="page-title"><?php the_title(); ?></h1>
          </div>
          <div class="entry">
            <?php the_content(); ?>
          </div>
        </div>
        <div class="clear"></div>
      </div>
    <?php endwhile;?>
  </div>
  <?php //get_sidebar(); ?>
</div>
</div>
</div>
<?php get_footer(); ?>