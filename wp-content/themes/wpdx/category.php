<?php get_header(); ?>
<div id="content-header">
  <?php cmp_breadcrumbs();?>
</div>
<div class="container-fluid">
  <?php get_template_part('includes/ad-top' );?>
  <div class="row-fluid">
    <section class="span8 archive-list">
      <?php $category_id = get_query_var('cat') ; ?>
      <div class="widget-box" role="main">
        <header id="archive-head">
          <h1>
            <?php echo __('Category: ','wpdx') . single_cat_title( '', false ) ; ?>
            <?php if( cmp_get_option( 'category_rss' ) ): ?>
              <a class="rss-cat-icon" title="<?php _e( 'Subscribe to this category', 'wpdx' ); ?>" href="<?php echo get_category_feed_link($category_id) ?>"><i class="fa fa-rss fa-fw"></i></a>
            <?php endif; ?>
          </h1>
          <?php
          if(cmp_get_option( 'category_desc' ) ):
            $category_description = category_description();
          if(!empty( $category_description ) )
            echo '<div class="archive-description">' . $category_description . '</div>';
          endif;
          ?>
        </header>
        <?php
        if ( is_category(explode(',', cmp_get_option('cats_row_thumb') )) ){
          echo '<div class="widget-content">';
          $args = array(
            'taxonomy' => 'category',
            'show_option_none' => __('No Menu Items.','wpdx'),
            'child_of' => $category_id,
            'echo' => 1,
            'depth' => 10,
            'wrap_class' => 'categories-list',
            'level_class' => 'pattern_garment_type',
            'parent_title_format' => '<h5>%s</h5>',
            'current_class' => 'selected'
            );
          
          cmp_custom_list_categories( $args );

          $Posts = cmp_get_option('cats_row_thumb_number');
          $orderby = cmp_get_option('cats_row_thumb_by') ? cmp_get_option('cats_row_thumb_by') : 'ID';
          $args = array(
            'cat' => $category_id ,
            'posts_per_page'=> $Posts ,
            'orderby'=>'post__in',
            'ignore_sticky_posts' => 1,
            'no_found_rows' => 1,
            'orderby' => $orderby
            );
          $cat_query = new WP_Query( $args );
          if($cat_query->have_posts()):
            echo '<ul>';
          while ( $cat_query->have_posts() ) : $cat_query->the_post(); ?>
          <li class="row-thumb">
            <a href="<?php the_permalink(); ?>" class="post-thumbnail" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" <?php echo cmp_target_blank();?>>
              <?php cmp_post_thumbnail(330,200) ?>
            </a>
            <a class="row-title" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><?php the_title(); ?></a>
          </li>

        <?php endwhile;
        echo "</ul>";
        endif;
        echo '</div>';
      }else{
// 不同分类存档每页显示不同文章数
        if( cmp_get_option('archive_mobile') &&  cmp_is_mobile()){
          query_posts($query_string . "&posts_per_page=".cmp_get_option('archive_mobile_number'));
        } elseif ( is_category(explode(',', cmp_get_option('big_thumb') )) ){
          query_posts($query_string . "&posts_per_page=".cmp_get_option('big_thumb_number'));
        } elseif ( is_category(explode(',', cmp_get_option('row_thumb') )) ){
          query_posts($query_string . "&posts_per_page=".cmp_get_option('row_thumb_number'));
        } elseif ( is_category(explode(',', cmp_get_option('original_image') )) ){
          query_posts($query_string . "&posts_per_page=".cmp_get_option('original_image_number'));
        } elseif ( is_category(explode(',', cmp_get_option('small_thumb') )) ){
          query_posts($query_string . "&posts_per_page=".cmp_get_option('small_thumb_number'));
        } elseif ( is_category(explode(',', cmp_get_option('simple_title') )) ){
          query_posts($query_string . "&posts_per_page=".cmp_get_option('simple_title_number'));
        } else {
          query_posts($query_string . "&posts_per_page=".cmp_get_option('default_number'));
        }
        get_template_part( 'loop', 'category' );  
      }
      ?>
    </div>
  </section>
  <?php get_sidebar(); ?>
</div>
<?php get_template_part('includes/ad-bottom' );?>
</div>
<?php get_footer(); ?>
</div>
