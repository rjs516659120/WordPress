<?php
$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
if(!$author) $author = get_user_by( 'ID', get_query_var( 'author' ) );
$current_user = wp_get_current_user();
$user_posts_list_id = cmp_get_page_id_by_shortcode('cmpuser-post-list');
if( function_exists('wpuf_autoload')){
	$user_posts_list_id = cmp_get_page_id_by_shortcode('wpuf_dashboard');
}
$user_posts_list_url = get_permalink($user_posts_list_id);
if( cmp_get_option('redirect_author_uc') && $current_user->ID == $author->ID){
	wp_safe_redirect( $user_posts_list_url ); exit;
}
get_header(); ?>
<div id="content-header">
	<?php cmp_breadcrumbs();?>
</div>
<div class="container-fluid">
	<?php get_template_part('includes/ad-top' );?>
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box user-center">
				<div id="user-left">
					<div class="user-avatar">
						<?php echo get_avatar( $author->user_email, 100 ).'<p>'.$author->nickname.'</p>'; ?>
						<?php if( cmp_get_option( 'author_bio' ) ): ?>
							<p class="author-bio"><?php echo get_the_author_meta( 'description' ); ?></p>
						<?php endif; ?>
						<?php if(class_exists("Fep_Message")){
							$pm_id = cmp_get_page_id_by_shortcode('front-end-pm');
							echo '<p class="user-pm"><a href="'.get_permalink($pm_id).'?fepaction=newmessage&to='.$author->user_login.'" ><i class="fa fa-paper-plane-o"></i> '.__('Send a message','wpdx').'</a></p>';
						}
						?>
					</div>
					<ul id="user-menu">
						<li class="current-menu-item"><a href="<?php echo get_author_posts_url( $author->ID ); ?>"><i class="fa fa-book fa-fw"></i><?php _e('His/Her Posts','wpdx') ?></a></li>
					</ul>
				</div>
				<div class="widget-content" id="user-right">
					<header id="archive-head">
						<h1>
							<?php echo sprintf(__("%s's Posts",'wpdx'), $author->nickname); ?>
							<?php if( cmp_get_option( 'author_rss' ) ): ?>
								<a class="rss-cat-icon" title="<?php _e( 'Subscribe to this author', 'wpdx' ); ?>"  href="<?php echo get_author_feed_link( $author->ID ); ?>"><i class="fa fa-rss fa-fw"></i></a>
							<?php endif; ?>
						</h1>
					</header>
					<?php //endif; ?>
					<?php
					if(count_user_posts( $author->ID ) != '0'){
						printf(__('<div class="post-count"> %s has published %s posts :</div>','wpdx'), $author->nickname , count_user_posts( $author->ID ) ) ;
					}else{
						printf(__('<div class="post-count"> %s has not yet published any post, you can read the following wonderful posts : </div>','wpdx'), $author->nickname ) ;
					}
					?>
					<ul>
						<?php if(have_posts()) : while ( have_posts() ) : the_post(); ?>
							<?php if(cmp_get_option('author_archive_style') == 'small_thumb'): ?>
								<li class="archive-mobile user-posts-thumb">
									<article>
										<?php if (function_exists('cmp_post_thumbnail')): ?>
											<a class="pic" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>>
												<?php cmp_post_thumbnail(75,45) ?>
											</a>
										<?php endif; ?>
										<h3><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><?php the_title(); ?></a></h3>
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
										<div class="clear"></div>
									</article>
								</li>
							<?php else: ?>
								<li class="archive-simple">
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
							<?php endif; ?>
						<?php endwhile; ?>
					<?php else: ?>
						<?php $rand_posts = get_posts('numberposts=10&orderby=rand');  foreach( $rand_posts as $post ) : ?>
						<?php if(cmp_get_option('author_archive_style') == 'small_thumb'): ?>
							<li class="archive-mobile user-posts-thumb">
								<article>
									<?php if (function_exists('cmp_post_thumbnail')): ?>
										<a class="pic" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>>
											<?php cmp_post_thumbnail(75,45) ?>
										</a>
									<?php endif; ?>
									<h3><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><?php the_title(); ?></a></h3>
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
									<div class="clear"></div>
								</article>
							</li>
						<?php else: ?>
							<li class="archive-simple">
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
						<?php endif; ?>
					<?php endforeach; endif; ?>
				</ul>
				<?php if ($wp_query->max_num_pages > 1) cmp_pagenavi(); ?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php get_template_part('includes/ad-bottom' );?>
</div>
<?php get_footer(); ?>
</div>
