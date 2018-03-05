<?php
add_action( 'widgets_init', 'social_widget_box' );
function social_widget_box() {
	register_widget( 'social_widget' );
}
class social_widget extends WP_Widget {
	public function __construct() {
        parent::__construct(
            'social',
            THEME_NAME .__( ' - Follow & Subscribe', 'wpdx' ),
            array( 'classname' => 'social-icons-widget' ,'description' => __('Display your social and subscribe information.','wpdx')),
            array( 'width' => 250, 'height' => 350)
            );
    }
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$hide_title = $instance['hide_title'];
		if( !$hide_title ){
			echo $before_widget;
			echo $before_title;
			echo $title ;
			echo $after_title;
		}else{
			echo '<div class="widget side-box mt10 social-icons-widget">';
			echo '<div class="m15 widget-container">';
		}
		?>
		<div class="social-icons">
			<?php $rss_url = cmp_get_option('rss_url') ? cmp_get_option('rss_url') : get_bloginfo( 'rss2_url');
			if(cmp_get_option('display_social_icon')): ?>
			<ul class="follows">
				<?php if(cmp_get_option('qq')): ?>
					<li class="qq"><a href="http://wpa.qq.com/msgrd?v=3&amp;site=qq&amp;menu=yes&amp;uin=<?php echo stripslashes(cmp_get_option('qq')); ?>" target="_blank" rel="external nofollow" title="<?php _e('Contact me by QQ','wpdx') ?>">QQ</a></li>
				<?php endif ?>
				<?php if(cmp_get_option('send_email')): ?>
					<li class="email"><a href="<?php echo stripslashes(cmp_get_option('send_email')); ?>" target="_blank" rel="external nofollow" title="<?php _e('Send email','wpdx') ?>"><?php _e('Send email','wpdx') ?></a></li>
				<?php endif ?>
				<?php if(cmp_get_option('sina_weibo')): ?>
					<li class="sina_weibo"><a href="<?php echo stripslashes(cmp_get_option('sina_weibo')); ?>" target="_blank" rel="external nofollow" title="<?php _e('Sina Weibo','wpdx') ?>"><?php _e('Sina Weibo','wpdx') ?></a></li>
				<?php endif ?>
				<?php if(cmp_get_option('qq_weibo')): ?>
					<li class="qq_weibo"><a href="<?php echo stripslashes(cmp_get_option('qq_weibo')); ?>" target="_blank" rel="external nofollow" title="<?php _e('QQ Weibo','wpdx') ?>"><?php _e('QQ Weibo','wpdx') ?></a></li>
				<?php endif ?>
				<?php if(cmp_get_option('twitter')): ?>
					<li class="twitter"><a href="<?php echo stripslashes(cmp_get_option('twitter')); ?>" target="_blank" rel="external nofollow" title="<?php _e('Twitter','wpdx') ?>"><?php _e('Twitter','wpdx') ?></a></li>
				<?php endif ?>
				<?php if(cmp_get_option('google_plus')): ?>
					<li class="google_plus"><a href="<?php echo stripslashes(cmp_get_option('google_plus')); ?>" target="_blank" rel="external nofollow"  title="<?php _e('Google+','wpdx') ?>"><?php _e('Google+','wpdx') ?></a></li>
				<?php endif ?>
				<?php if(cmp_get_option('rss_url')): ?>
					<li class="rss"><a href="<?php echo stripslashes($rss_url); ?>" target="_blank" rel="external nofollow" title="<?php _e('Feed Subscription','wpdx') ?>"><?php _e('Feed Subscription','wpdx') ?></a></li>
				<?php endif; ?>
			</ul>
			<?php endif; ?>
			<div class="popup-follow-feed">
				<p class="feed-to"><?php _e('Subscribe To:','wpdx') ?>
					<a rel="external nofollow" target="_blank" href="http://reader.youdao.com/b.do?keyfrom=bookmarklet&url=<?php echo rawurlencode($rss_url); ?>"><?php _e('Youdao','wpdx') ?></a>
					<a rel="external nofollow" target="_blank" href="http://feedly.com/index.html#subscription%2Ffeed%2F<?php echo stripslashes($rss_url); ?>"><?php _e('Feedly','wpdx') ?></a>
				</p>
				<p><?php _e('Subscribe URL:','wpdx') ?>
					<input class="ipt" type="text" readonly value="<?php echo stripslashes($rss_url); ?>">
				</p>
			</div>
			<?php if(cmp_get_option('qq_email_list')): ?>
			<div class="popup-follow-mail">
				<form action="http://list.qq.com/cgi-bin/qf_compose_send" target="_blank" method="post">
					<input type="hidden" name="t" value="qf_booked_feedback">
					<input type="hidden" name="id" value="<?php echo stripslashes(cmp_get_option('qq_email_list')); ?>">
					<input id="to" placeholder="<?php _e('Enter your E-mail','wpdx') ?>" name="to" type="text" class="ipt"><input class="btn btn-primary" type="submit" value="<?php _ex('Subscribe','Subscribe to Email','wpdx') ?>">
				</form>
			</div>
			<?php endif; ?>
		</div>
		<?php
		if( !$hide_title ){
			echo $after_widget;
		}else{
			echo '</div>';
			echo '</div>';
		}
	}
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['hide_title'] = strip_tags( $new_instance['hide_title'] );
		return $instance;
	}
	public function form( $instance ) {
		$defaults = array( 'title' =>__('Follow & Subscribe' , 'wpdx') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title : ', 'wpdx' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'hide_title' ); ?>"><?php _e('Hide Widget Title :', 'wpdx' )?></label>
			<input id="<?php echo $this->get_field_id( 'hide_title' ); ?>" name="<?php echo $this->get_field_name( 'hide_title' ); ?>" value="true" <?php if( isset($instance['hide_title']) && $instance['hide_title'] ) echo 'checked="checked"'; ?> type="checkbox" />
		</p>
		<p><?php printf( __( 'Please visit <a href="%s" target="_blank">the theme options page</a> to set the Social Network.', 'wpdx' ), home_url().'/wp-admin/admin.php?page=panel' );?></p>
		<?php
	}
}