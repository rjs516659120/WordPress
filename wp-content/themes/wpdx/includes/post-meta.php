<?php
global $get_meta;
global $post;
if( (is_single() && cmp_get_option( 'post_meta' ) ) || (is_page() && cmp_get_option( 'page_meta' ))): ?>
<p class="post-meta">
    <?php if( cmp_get_option( 'post_author' ) ): ?>
        <span><i class="fa fa-user fa-fw"></i><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) )?>" title="<?php sprintf( esc_attr__( 'View all posts by %s', 'wpdx' ), get_the_author() ) ?>"><?php echo get_the_author() ?></a></span>
    <?php endif; ?>
    <?php if( cmp_get_option( 'post_date' ) && cmp_get_option( 'time_format' ) != 'none' ): ?>
        <span class="time"><i class="fa fa-clock-o fa-fw"></i><?php cmp_get_time() ?></span>
    <?php endif; ?>
    <?php if( cmp_get_option( 'post_cats' ) && get_post_type( get_the_ID() ) == 'post' ): ?>
        <span class="cat"><i class="fa fa-folder-open fa-fw"></i><?php printf('%1$s', get_the_category_list( ', ' ) ); ?></span>
    <?php elseif( cmp_get_option( 'post_cats' ) && get_post_type( get_the_ID() ) == 'download' ): ?>
        <span class="cat"><i class="fa fa-folder-open fa-fw"></i><?php the_terms( get_the_ID(), 'download_category', '', ', ', '' );?></span>
    <?php endif; ?>
    <?php if( cmp_get_option( 'post_views' )  && function_exists('the_views') ): ?>
        <span class="eye"><i class="fa fa-eye fa-fw"></i><?php the_views(); ?></span>
    <?php elseif( cmp_get_option( 'post_views' )  && function_exists('cmp_the_views') ): ?>
        <span class="eye"><i class="fa fa-eye fa-fw"></i><?php cmp_the_views(); ?></span>
    <?php endif; ?>
    <?php if( cmp_get_option( 'post_comments' ) && comments_open() ): ?>
        <span class="comm"><i class="fa fa-comment-o fa-fw"></i><?php comments_popup_link('0','1','%' ); ?></span>
    <?php endif; ?>
    <?php edit_post_link( __( 'Edit', 'wpdx' ), '<span class="edit"><i class="fa fa-edit fa-fw"></i>', '</span>' ); ?>
</p>
<div class="clear"></div>
<?php endif; ?>