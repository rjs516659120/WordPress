<?php
function get_home_news_pic( $cat_data ){
$Cat_ID = $cat_data['id'];
$Posts = 7;
$order = $cat_data['order'];
$Box_Title = $cat_data['title'] ? $cat_data['title'] : get_the_category_by_ID($Cat_ID[0]);
$Box_style = $cat_data['style'];
$icon = $cat_data['icon'] ? $cat_data['icon']:'fa-list';
$more_text = trim($cat_data['more_text']);
$more_url = $cat_data['more_url'];
@$show_title = $cat_data['show_title'];
$post_ids = '';
if($Box_style == 'row') $Posts = 10;
if($cat_data['post_ids'] || $cat_data['post_ids'] !='')$post_ids = explode(',', $cat_data['post_ids'] );
if($post_ids){
	$args = array( 'post__in' => $post_ids ,'posts_per_page'=> $Posts , 'orderby'=>'post__in','ignore_sticky_posts' => 1 ,'no_found_rows' => 1);
	$cat_query = new WP_Query( $args );
}elseif($Cat_ID){
	if( $order == 'rand') {
		$args = array ( 'ignore_sticky_posts' => 1, 'posts_per_page' => $Posts , 'orderby' => 'rand', 'cat' => $Cat_ID,'no_found_rows' => 1);
	} else {
		$args = array ( 'ignore_sticky_posts' => 1, 'posts_per_page' => $Posts , 'cat' => $Cat_ID,'no_found_rows' => 1);
	}
	$cat_query = new WP_Query($args);
}
$who = $cat_data['who'];
if( $who == 'logged' && !is_user_logged_in()):
	// return none;
elseif( $who == 'anonymous' && is_user_logged_in()):
	// return none;
else:
?>
<section class="span12 pic-box">
	<div class="widget-box">
		<div class="widget-title">
			<?php if($more_url && $more_text !=''): ?>
				<span class="more"><a target="_blank" href="<?php echo $more_url; ?>"><?php echo $more_text; ?></a></span>
			<?php endif; ?>
			<span class="icon"> <i class="fa <?php echo $icon; ?> fa-fw"></i> </span>
			<?php if($more_url): ?>
				<h2><a target="_blank" href="<?php echo $more_url; ?>"><?php echo $Box_Title ; ?></a></h2>
			<?php else: ?>
				<h2><?php echo $Box_Title ; ?></h2>
			<?php endif; ?>
		</div>
		<div class="widget-content">
			<?php if($cat_query->have_posts()): $count=0; ?>
				<ul>
					<?php while ( $cat_query->have_posts() ) : $cat_query->the_post(); $count ++ ;?>
						<?php if($Box_style == 'row') : ?>
							<li class="style-row">
								<a href="<?php the_permalink(); ?>" class="post-thumbnail" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" <?php echo cmp_target_blank();?>>
									<?php cmp_post_thumbnail(330,200) ?>
								</a>
								<?php if($show_title): ?>
								<a class="row-title" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><?php the_title(); ?></a>
								<?php endif;?>
							</li>
						<?php elseif($count == 1 && $Box_style != 'row') : ?>
							<li class="first-pic">
								<a href="<?php the_permalink(); ?>" class="post-thumbnail" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>>
									<?php cmp_post_thumbnail(660,400) ?>
								</a>
								<?php if($show_title): ?>
								<a class="first-pic-title" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><?php the_title(); ?></a>
								<p class="summary">
									<?php cmp_excerpt_home(); ?>
								</p>
								<?php else: ?>
								<h3><a class="first-pic-title" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><?php the_title(); ?></a></h3>
								<?php endif;?>
							</li><!-- .first-pic -->
						<?php else: ?>
							<li>
								<a href="<?php the_permalink(); ?>" class="post-thumbnail" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" <?php echo cmp_target_blank();?>>
									<?php cmp_post_thumbnail(330,200) ?>
								</a>
								<?php if($show_title): ?>
								<a class="row-title" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><?php the_title(); ?></a>
								<?php endif;?>
							</li>
						<?php endif; ?>
					<?php endwhile;?>
				</ul>
				<div class="clear"></div>
			<?php endif; ?>
		</div>
	</div><!-- .cat-box-content /-->
</section>
<?php endif;//$who ?>
<?php } ?>