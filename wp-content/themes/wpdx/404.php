<?php get_header(); ?>
<div id="content-header">
  <?php cmp_breadcrumbs();?>
</div>
<div class="container-fluid">
  <?php get_template_part('includes/ad-top' );?>
  <div class="row-fluid">
    <div class="span8">
      <div class="widget-box">
        <article class="widget-content single-post">
          <header id="post-header">
            <h1 class="post-title"><?php _e( 'Not Found', 'wpdx' ); ?></h1>
            <div class="clear"></div>
            <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'wpdx' ); ?></p>
          </header>
          <div class="entry">
            <?php get_search_form(); ?>
          </div>
        </article>
      </div>
    </div>
    <?php get_sidebar(); ?>
  </div>
  <?php get_template_part('includes/ad-bottom' );?>
</div>
</div>
<?php get_footer(); ?>