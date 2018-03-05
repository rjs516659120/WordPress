<?php if ( ! have_posts() ) : ?>
	<article class="widget-content archive-simple">
		<div class="entry">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'wpdx' ); ?></p>
			<?php get_search_form(); ?>
		</div>
	</article>
<?php else :?>
	<div class="widget-content">
		<ul class="posts-ul">
			<?php while ( have_posts() ) : the_post();
			if( cmp_get_option('archive_mobile') &&  cmp_is_mobile()){ ?>
			<li class="pl archive-mobile">
				<article>
					<?php if (function_exists('cmp_post_thumbnail')): ?>
						<a class="pic" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>>
							<?php cmp_post_thumbnail(75,45) ?>
						</a>
					<?php endif; ?>
					<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><?php the_title(); ?></a></h2>
					<div class="clear"></div>
				</article>
			</li>
		<?php
		}elseif ( is_category(explode(',', cmp_get_option('big_thumb') )) ) {?>
		<li class="pl archive-big">
			<article>
				<?php if (function_exists('cmp_post_thumbnail')): ?>
					<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>>
						<?php cmp_post_thumbnail(930,330) ?>
					</a>
				<?php endif; ?>
				<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><?php the_title(); ?></a></h2>
				<p><?php if (function_exists('cmp_excerpt')) cmp_excerpt() ?></p>
				<p class="more"><a class="more-link" href="<?php the_permalink() ?>" <?php echo cmp_target_blank();?>><?php _e( 'View More','wpdx' ) ?></a></p>
				<?php if (function_exists('archive_meta')) archive_meta(); ?>
			</article>
		</li>
		<?php
		}elseif ( is_category(explode(',', cmp_get_option('original_image') )) ) {?>
		<li class="pl archive-thumb original-thumb">
			<article>
			<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><?php the_title(); ?></a></h2>
				<?php if (function_exists('cmp_post_thumbnail_original')): ?>
					<div class="original-thumb-img">
					<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>>
						<?php cmp_post_thumbnail_original(); ?>
					</a>
					</div>
				<?php endif; ?>
				<p><?php if (function_exists('cmp_excerpt')) cmp_excerpt() ?></p>
				<p class="more"><a class="more-link" href="<?php the_permalink() ?>" <?php echo cmp_target_blank();?>><?php _e( 'View More','wpdx' ) ?></a></p>
				<?php if (function_exists('archive_meta')) archive_meta(); ?>
			</article>
		</li>
		<?php }elseif ( is_category(explode(',', cmp_get_option('row_thumb') )) ) {?>
			<li class="pl row-thumb">
				<a href="<?php the_permalink(); ?>" class="post-thumbnail" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" <?php echo cmp_target_blank();?>>
					<?php cmp_post_thumbnail(330,200) ?>
				</a>
				<a class="row-title" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><?php the_title(); ?></a>
			</li>
		<?php } elseif ( is_category(explode(',', cmp_get_option('small_thumb') )) ) { ?>
		<li class="pl archive-thumb">
			<article>
				<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><?php the_title(); ?></a></h2>
				<?php if (function_exists('cmp_post_thumbnail')): ?>
					<a class="pic <?php if(cmp_get_option('thumb_left')) echo 'float-left'; ?>" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>>
						<?php cmp_post_thumbnail(330,200) ?>
					</a>
				<?php endif; ?>
				<p><?php if (function_exists('cmp_excerpt')) cmp_excerpt() ?></p>
				<p class="more"><a class="more-link" href="<?php the_permalink() ?>" <?php echo cmp_target_blank();?>><?php _e( 'View More','wpdx' ) ?></a></p>
				<?php if (function_exists('archive_meta')) archive_meta(); ?>
				<div class="clear"></div>
			</article>
		</li>
		<?php } elseif ( is_category(explode(',', cmp_get_option('simple_title') )) ) { ?>
		<li class="pl archive-simple">
			<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><i class="fa fa-angle-right"></i><?php the_title(); ?></a></h2>
			<p class="post-meta">
				<?php if(function_exists('the_views')) : ?>
					<span><i class="fa fa-eye fa-fw"></i><?php the_views(); ?></span>
				<?php elseif( function_exists('cmp_the_views')) : ?>
					<span><i class="fa fa-eye fa-fw"></i><?php cmp_the_views(); ?></span>
				<?php endif; ?>
				<?php if(function_exists('cmp_get_time')) : ?>
					<span><i class="fa fa-clock-o fa-fw"></i><?php cmp_get_time();?></span>
				<?php endif; ?>
			</p>
		</li>
			<?php
		}elseif ( is_category(explode(',', cmp_get_option('big_thumb') )) ) {?>
		<li class="pl archive-big">
			<article>
				<?php if (function_exists('cmp_post_thumbnail')): ?>
					<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>>
						<?php cmp_post_thumbnail(930,330) ?>
					</a>
				<?php endif; ?>
				<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><?php the_title(); ?></a></h2>
				<p><?php if (function_exists('cmp_excerpt')) cmp_excerpt() ?></p>
				<p class="more"><a class="more-link" href="<?php the_permalink() ?>" <?php echo cmp_target_blank();?>><?php _e( 'View More','wpdx' ) ?></a></p>
				<?php if (function_exists('archive_meta')) archive_meta(); ?>
			</article>
		</li>
		<?php
		}elseif ( cmp_get_option('archive_style') == 'original_image') {?>
		<li class="pl archive-thumb original-thumb">
			<article>
			<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><?php the_title(); ?></a></h2>
				<?php if (function_exists('cmp_post_thumbnail_original')): ?>
					<div class="original-thumb-img">
					<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>>
						<?php cmp_post_thumbnail_original(); ?>
					</a>
					</div>
				<?php endif; ?>
				<p><?php if (function_exists('cmp_excerpt')) cmp_excerpt() ?></p>
				<p class="more"><a class="more-link" href="<?php the_permalink() ?>" <?php echo cmp_target_blank();?>><?php _e( 'View More','wpdx' ) ?></a></p>
				<?php if (function_exists('archive_meta')) archive_meta(); ?>
			</article>
		</li>
		<?php }elseif ( cmp_get_option('archive_style') == 'row_thumb' ) {?>
			<li class="pl row-thumb">
				<a href="<?php the_permalink(); ?>" class="post-thumbnail" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" <?php echo cmp_target_blank();?>>
					<?php cmp_post_thumbnail(330,200) ?>
				</a>
				<a class="row-title" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><?php the_title(); ?></a>
			</li>
		<?php } elseif ( cmp_get_option('archive_style') == 'small_thumb' ) { ?>
		<li class="pl archive-thumb">
			<article>
				<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><?php the_title(); ?></a></h2>
				<?php if (function_exists('cmp_post_thumbnail')): ?>
					<a class="pic <?php if(cmp_get_option('thumb_left')) echo 'float-left'; ?>" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>>
						<?php cmp_post_thumbnail(330,200) ?>
					</a>
				<?php endif; ?>
				<p><?php if (function_exists('cmp_excerpt')) cmp_excerpt() ?></p>
				<p class="more"><a class="more-link" href="<?php the_permalink() ?>" <?php echo cmp_target_blank();?>><?php _e( 'View More','wpdx' ) ?></a></p>
				<?php if (function_exists('archive_meta')) archive_meta(); ?>
				<div class="clear"></div>
			</article>
		</li>
		<?php } elseif ( cmp_get_option('archive_style') == 'simple_title' ) { ?>
		<li class="pl archive-simple">
			<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><i class="fa fa-angle-right"></i><?php the_title(); ?></a></h2>
			<p class="post-meta">
				<span><i class="fa fa-clock-o fa-fw"></i><?php if (function_exists('cmp_get_time')) cmp_get_time();?></span>
				<?php if(function_exists('the_views')) : ?>
					<span><i class="fa fa-eye fa-fw"></i><?php the_views(); ?></span>
				<?php elseif( function_exists('cmp_the_views')) : ?>
					<span><i class="fa fa-eye fa-fw"></i><?php cmp_the_views(); ?></span>
				<?php endif; ?>
			</p>
		</li>
		<?php
		}elseif ( cmp_get_option('archive_style') == 'big_thumb' ) {?>
		<li class="pl archive-big">
			<article>
				<?php if (function_exists('cmp_post_thumbnail')): ?>
					<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>>
						<?php cmp_post_thumbnail(930,330) ?>
					</a>
				<?php endif; ?>
				<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><?php the_title(); ?></a></h2>
				<p><?php if (function_exists('cmp_excerpt')) cmp_excerpt() ?></p>
				<p class="more"><a class="more-link" href="<?php the_permalink() ?>" <?php echo cmp_target_blank();?>><?php _e( 'View More','wpdx' ) ?></a></p>
				<?php if (function_exists('archive_meta')) archive_meta(); ?>
			</article>
		</li>
		<?php
		} ?>
	<?php endwhile;?>
</ul>
<div class="clearfix"></div>
<?php if($wp_query->max_num_pages > 1) cmp_pagenavi(); ?>
</div>
<?php endif; ?>
<?php
function archive_meta(){
	if (cmp_get_option('archive_meta')) :?>
	<p class="post-meta">
		<?php if (cmp_get_option('archive_author')):?>
			<span><i class="fa fa-user fa-fw"></i><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) )?>" title="<?php sprintf( esc_attr__( 'View all posts by %s', 'wpdx' ), get_the_author() ) ?>"><?php echo get_the_author() ?></a></span>
		<?php endif; ?>
		<?php
		if (cmp_get_option('archive_category') ){
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				echo '<span><i class="fa fa-folder-open"></i><a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a></span>';
			} 
		}
		?>
		<?php if (cmp_get_option('archive_date') && function_exists('cmp_get_time')) :?>
			<span><i class="fa fa-clock-o fa-fw"></i><?php cmp_get_time();?></span>
		<?php endif; ?>
		<?php if( cmp_get_option('archive_views') && function_exists('the_views')): ?>
			<span><i class="fa fa-eye fa-fw"></i><?php the_views();  ?></span>
		<?php elseif( cmp_get_option('archive_views') && function_exists('cmp_the_views')) : ?>
			<span><i class="fa fa-eye fa-fw"></i><?php cmp_the_views(); ?></span>
		<?php endif; ?>
		<?php if (cmp_get_option('archive_comments') && comments_open()) :?>
			<span><i class="fa fa-comment-o fa-fw"></i><?php comments_popup_link('0','1','%' ); ?></span>
		<?php endif; ?>
		<?php if (cmp_get_option('archive_tags') && has_tag() ) :?>
			<span><i class="fa fa-tags fa-fw"></i><?php the_tags('',''); ?></span>
		<?php endif; ?>
	</p>
<?php endif;
}
?>