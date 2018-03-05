<?php get_header(); ?>
<div id="content-header">
  <?php cmp_breadcrumbs();?>
</div>
<div class="container-fluid">
  <?php get_template_part('includes/ad-top' );?>
  <div class="row-fluid">
    <section class="span8 archive-list">
      <div class="widget-box" role="main">
        <header id="archive-head">
          <h1>
            <?php echo __('Tag: ','wpdx') . single_tag_title( '', false ) ; ?>
            <?php if( cmp_get_option( 'tag_rss' ) ):
            $tag_id = get_query_var('tag_id'); ?>
            <a class="rss-cat-icon tooltip" title="<?php _e( 'Subscribe to this tag', 'wpdx' ); ?>"  href="<?php echo  get_term_feed_link($tag_id , 'post_tag', "rss2") ?>"><?php _e( 'Subscribe to this tag', 'wpdx' ); ?></a>
          <?php endif; ?>
        </h1>
        <div class="archive-description"><?php
        $description = category_description() ? category_description() : sprintf( __( 'The following articles associated with the tag: %s', 'wpdx' ), single_tag_title('', false));
        echo $description;
        ?></div>
      </header>
      <?php
      if( cmp_get_option('archive_mobile') &&  cmp_is_mobile()){
        query_posts($query_string . "&posts_per_page=".cmp_get_option('archive_mobile_number'));
      }else{
        query_posts($query_string . "&posts_per_page=".cmp_get_option('default_number'));
      }
      get_template_part( 'loop', 'tag' ); ?>
    </div>
  </section>
  <?php get_sidebar(); ?>
</div>
<?php get_template_part('includes/ad-bottom' );?>
</div>
</div>
<?php get_footer(); ?>