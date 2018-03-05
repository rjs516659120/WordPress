<?php
add_action('widgets_init', 'Login_init');
function Login_init() {
	register_widget('Login_Widget');
}
class Login_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'login', // 基本 ID
			THEME_NAME .__( ' - Login', 'wpdx' ),
			array( 'classname' => 'login-widget' , 'description' => __( 'A Login Widget', 'wpdx' )), // Args
			array( 'width' => 250, 'height' => 350)
			);
		// widget actual processes
	}
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		echo $before_title;
		echo $title;
		echo $after_title;
		global $user_ID, $user_identity, $user_email, $user_login;
		wp_get_current_user();
		if (!$user_ID) {
			?>
			<form id="login-form" action="<?php echo get_option('siteurl'); ?>/wp-login.php" method="post">
				<p>
					<label><?php _e( 'Username:' , 'wpdx' ) ?><input class="login" type="text" name="log" id="log" value="" size="12" /></label>
				</p>
				<p>
					<label><?php _e( 'Password:' , 'wpdx' ) ?><input class="login" type="password" name="pwd" id="pwd" value="" size="12" /></label>
				</p>
				<p>
					<input type="submit" name="submit" value="<?php _e( 'Log in' , 'wpdx' ) ?>" class="login-button"/>
					<label><?php _e( 'Remember Me' , 'wpdx' ) ?><input id="rememberme" type="checkbox" name="rememberme" value="forever" /></label>
				</p>
				<p>
					<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>"/>
				</p>
			</form>
			<?php }
			else { ?>
			<div id="user-avatar"><?php echo get_avatar($user_email, 56 ); ?></div>
			<ul id="user_control">
				<li><a rel="nofollow" target="_blank" href="<?php echo home_url() ?>/wp-admin/"><?php _e( 'Dashboard' , 'wpdx' ) ?></a></li>
				<li><a rel="nofollow" target="_blank" href="<?php echo home_url() ?>/wp-admin/post-new.php"><?php _e( 'New Post' , 'wpdx' ) ?></a></li>
				<li><a rel="nofollow" href="<?php echo home_url() ?>/wp-admin/profile.php"><?php _e( 'Your Profile' , 'wpdx' ) ?> </a></li>
				<li><a rel="nofollow" href="<?php echo wp_logout_url(); ?>"><?php _e( 'Logout' , 'wpdx' ) ?></a></li>
			</ul>
			<?php }
			echo $after_widget;
		}
		public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
			$instance = $old_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );
			return $instance;
		}
		public function form( $instance ) {
		// outputs the options form on admin
			$defaults = array( 'title' =>__('Login' , 'wpdx'));
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title : ', 'wpdx' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
			</p>
			<?php
		}
	}