<?php get_header(); ?>
<div id="content-header">
  <?php cmp_breadcrumbs();?>
</div>
<div class="container-fluid">
  <?php get_template_part('includes/ad-top' );?>
  <div class="row-fluid">
    <section class="span8 archive-list">
      <?php $term_id = get_queried_object_id(); ?>
      <div class="widget-box" role="main">
        <header id="archive-head">
          <h1>
            <?php echo __('Category: ','wpdx') . single_term_title( '', false ) ; ?>
            <?php if( cmp_get_option( 'category_rss' ) ): ?>
              <a class="rss-cat-icon" title="<?php _e( 'Subscribe to this category', 'wpdx' ); ?>" href="<?php echo get_term_feed_link($term_id,'download_category') ?>"><i class="fa fa-rss fa-fw"></i></a>
            <?php endif; ?>
          </h1>
          <?php
          if(cmp_get_option( 'category_desc' ) ){
            $category_description = category_description();
            if(!empty( $category_description ) ){
              echo '<div class="archive-description">' . $category_description . '</div>';
            }
          }
          ?>
        </header>
        <?php
        get_template_part( 'loop', 'edd' );  
        ?>
      </div>
    </section>
    <?php get_sidebar('edd'); ?>
  </div>
  <?php get_template_part('includes/ad-bottom' );?>
</div>
</div>
<?php get_footer(); ?>