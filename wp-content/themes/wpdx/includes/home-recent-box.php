<?php
function get_home_recent( $cat_data ){
	$post_type = isset($cat_data['post_type']) ? $cat_data['post_type']: array('post');
	$mode = implode('',$post_type);
	$exclude = isset($cat_data['exclude']) ? $cat_data['exclude'] : '';
	$Posts = $cat_data['number'];
	$Box_Title = $cat_data['title'];
	$order = $cat_data['order'];
	$days = $cat_data['days'];
	$hours = isset($cat_data['hours']) ? $cat_data['hours']: 0 ;
	$icon = $cat_data['icon'] ? $cat_data['icon']:'fa-list';
	$more_text = $cat_data['more_text'] ? $cat_data['more_text']:'More';
	$more_url = $cat_data['more_url'];
	$post_ids = '';
	$args = array();
	if($cat_data['post_ids'] || $cat_data['post_ids'] !='') $post_ids = explode(',', $cat_data['post_ids'] );
	if($post_ids){
		$args = array( 'post__in' => $post_ids ,'posts_per_page'=> $Posts , 'orderby'=>'post__in','ignore_sticky_posts' => 1,'no_found_rows' => 1);
	}elseif( $order == 'modified') {
		$args = array ( 'ignore_sticky_posts' => 1, 'posts_per_page' => $Posts , 'post_type' => $post_type ,'orderby' => 'modified','category__not_in' => $exclude,'no_found_rows' => 1);
	}elseif( $order == 'random') {
		$args = array ( 'ignore_sticky_posts' => 1, 'posts_per_page' => $Posts , 'post_type' => $post_type ,'orderby' => 'rand','category__not_in' => $exclude,'no_found_rows' => 1);
	}elseif( $order == 'latest'){
		$args = array ( 'ignore_sticky_posts' => 1, 'posts_per_page' => $Posts , 'post_type' => $post_type ,'category__not_in' => $exclude,'no_found_rows' => 1);
	}elseif( $order == 'stick'){
		$args = array ( 'post__in'  => get_option( 'sticky_posts' ),'ignore_sticky_posts' => 1, 'posts_per_page' => $Posts , 'post_type' => $post_type ,'category__not_in' => $exclude,'no_found_rows' => 1);
	}
	$cat_query = new WP_Query( $args );
	$who = $cat_data['who'];
	if( $who == 'logged' && !is_user_logged_in()):
		// return none;
	elseif( $who == 'anonymous' && is_user_logged_in()):
		// return none;
	else:
	?>
	<section class="span4 home-recent">
		<div class="widget-box">
			<div class="widget-title">
				<?php if($more_url): ?>
					<span class="more"><a target="_blank" href="<?php echo $more_url; ?>"><?php echo $more_text; ?></a></span>
				<?php endif; ?>
				<span class="icon"> <i class="fa <?php echo $icon; ?> fa-fw"></i> </span>
				<h2><?php echo $Box_Title ; ?></h2>
			</div>
			<div class="widget-content">
				<ul class="news-list">
					<?php
					if( $order == 'most_comment'){
						if (function_exists('most_comm_posts')) most_comm_posts($mode, $Posts , $days , $exclude, true);
					} elseif ($order == 'most_viewed') {
						if (function_exists('most_viewed_posts')) most_viewed_posts($mode, $Posts , $days , $exclude, true);
					} else {

						if(@$cat_query->have_posts()): ?>

						<?php while ( $cat_query->have_posts() ) : $cat_query->the_post()?>
							<?php
							if(function_exists('cmp_check_if_new_post') && cmp_check_if_new_post($hours)){
								$class = ' class="red"';
							}else{
								$class = '';
							}
							?>

							<li><span<?php echo $class; ?>><?php the_time('m-d') ?></span><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark" <?php echo cmp_target_blank();?>><i class="fa fa-angle-right"></i><?php the_title(); ?></a></li>
						<?php endwhile;?>
					<?php endif;
				} ?>

			</ul>
		</div><!-- .widget-content /-->
	</div>
</section>
<?php endif;//$who ?>
<?php
}
?>