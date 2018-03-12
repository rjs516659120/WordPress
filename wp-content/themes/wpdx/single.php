<?php
get_header();
$span = 'span8';
if(cmp_get_option('remove_post_sidebar')){
  $span = 'span12';
}
?>
<div id="content-header">
  <?php cmp_breadcrumbs();?>
</div>
<div class="container-fluid">
  <?php get_template_part('includes/ad-top' );?>
  <div class="row-fluid">
   <div class="<?php echo $span; ?>">
    <?php if ( ! have_posts() ) : ?>
     <div class="widget-box">
      <article id="post-<?php the_ID(); ?>" class="widget-content single-post">
       <header class="entry-header">
        <h1 class="post-title"><?php _e( 'Not Found', 'wpdx' ); ?></h1>
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
 <div class="widget-box">
  <article id="post-<?php the_ID(); ?>" class="widget-content single-post">
   <header id="post-header">
    <?php if (function_exists('wpfp_link')) { wpfp_link(); } ?>
    <h1 class="post-title"><?php the_title(); ?></h1>
    <div class="clear"></div>
    <?php cmp_include( 'post-meta' ); ?>
  </header>
  <div class="entry">
    <?php get_template_part('includes/ad-post-above' );?>
    <?php the_content(); ?>
    <?php wp_link_pages( array( 'before' => '<div class="post-pages">' . __( 'Pages:', 'wpdx' ), 'after' => '</div>','link_before' =>'<span>', 'link_after'=>'</span>' ) ); echo add_after_post_content(); ?>
  </div>
  <footer class="entry-meta">
      <?php //if (function_exists('wpfp_link')) { wpfp_link(); } ?>
      <?php if( cmp_get_option('reward_author')) get_template_part('includes/reward');?>
      <?php if( cmp_get_option('share_post')) cmp_include( 'single-post-share' ); ?>
      <?php if( cmp_get_option( 'post_tags' ) ) the_tags( '<p class="post-tag">'.__( 'Tagged with: ', 'wpdx' )  ,' ', '</p>'); ?>
      <?php get_template_part('includes/ad-post-below' );?>
      <?php if( ( cmp_get_option( 'post_authorbio' ) && empty( $get_meta["cmp_hide_author"][0] ) ) || ( isset( $get_meta["cmp_hide_related"][0] ) && $get_meta["cmp_hide_author"][0] == 'no' ) ): ?>

       <div id="author-box">
        <h3><span><?php _e( 'Last edited: ', 'wpdx' ); echo get_the_modified_time('Y/n/j')?></span><?php
          $original_url = get_post_meta( $post->ID, '_cmp_original_url', true );
          $posted_by = __( 'Author: ', 'wpdx' );
          if($original_url) $posted_by = __( 'Editor: ', 'wpdx' );
        echo $posted_by . get_the_author() ?></h3>
        <div class="author-info">
         <?php cmp_author_box() ?>
       </div>
     </div>
   <?php endif; ?>
 </footer>
 <?php if( cmp_get_option( 'post_nav' ) ): ?>
  <div class="post-navigation">
   <div class="post-previous"><?php previous_post_link( '%link', '<span>'. __( 'Previous:', 'wpdx' ).'</span> %title' ); ?></div>
   <div class="post-next"><?php next_post_link( '%link', '<span>'. __( 'Next:', 'wpdx' ).'</span> %title' ); ?></div>
 </div>
<?php endif; ?>
</article>
</div>
<?php cmp_include( 'post-related' ); ?>

<?php endwhile;?>

<?php
if(!cmp_get_option('remove_post_comment')) :
if ( (!comments_open() && have_comments()) || comments_open() ): ?>
<div class="widget-box comment-box">
  <section class="widget-content">
    <?php comments_template( '', true ); ?>
  </section>
</div>
<?php
endif;
endif; ?>

</div>
<?php if(!cmp_get_option('remove_post_sidebar')) get_sidebar(); ?>
</div>
<?php get_template_part('includes/ad-bottom' );?>
</div>
<?php get_footer(); ?>
</div>
