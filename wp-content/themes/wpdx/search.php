<?php get_header(); ?>
  <div id="content-header">
    <?php cmp_breadcrumbs();?>
  </div>
  <div class="container-fluid">
<?php get_template_part('includes/ad-top' );?>
    <div class="row-fluid">
      <section class="span8 archive-list">
        <div class="widget-box">
          <div id="archive-head">
          <?php if ( have_posts() ) : ?>
            <header class="page-header">
              <h1 itemprop="headline">
                  <?php printf( __( 'Search: %s', 'wpdx' ),  get_search_query() ); ?>
              </h1>
            </header>
            <div class="archive-description"><?php printf( __( 'Posts 0f search results for: %s', 'wpdx' ), get_search_query()); ?></div>
            <?php else : ?>
              <header class="page-header">
              <h1>
                  <?php echo __( 'Nothing Found', 'wpdx' ); ?>
              </h1>
            </header>
            <div class="archive-description"><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'wpdx' ); ?></p></div>
                <?php endif; ?>
          </div>
          <?php
          query_posts($query_string . "&posts_per_page=".cmp_get_option('default_number'));
           if ( have_posts() ) : ?>
            <?php get_template_part( 'loop', 'search' );  ?>
          <?php else : ?>
            <article id="post-0" class="post not-found">
              <div class="entry">
                <?php get_search_form(); ?>
                <div class="clearfix">
                </div>
              </div>
            </article>
          <?php endif; ?>
        </div>
      </section>
      <?php get_sidebar(); ?>
    </div>
    <?php get_template_part('includes/ad-bottom' );?>
  </div>
</div>
<?php get_footer(); ?>