<?php
## Author Widget
add_action( 'widgets_init', 'Author_widget_box' );
function Author_widget_box(){
	register_widget( 'author_widget' );
}
class author_widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'author_widget',
			THEME_NAME .__( ' - Post Author', 'wpdx' ),
			array( 'classname' => 'widget_author', 'description' => __( 'Display author\'s information in single post page.', 'wpdx' )),
			array( 'width' => 250, 'height' => 350)
			);
	}
	public function widget( $args, $instance ) {
		extract( $args );
		if ( is_single() ) :
		$avatar = $instance['avatar'];
		$social = $instance['social'];
		echo $before_widget;
		echo $before_title;
		printf( __( 'About %s', 'wpdx' ), get_the_author() );
		echo $after_title;
		cmp_author_box( $avatar , $social );
		echo $after_widget;
		endif;
	}
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['avatar'] = strip_tags( $new_instance['avatar'] );
		$instance['social'] = strip_tags( $new_instance['social'] );
		return $instance;
	}
	public function form( $instance ) {
		$defaults = array( 'avatar' => 'true' , 'social' => 'true' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p><em style="color:red;"><?php _e('This Widget appears in single post only .', 'wpdx' ) ?></em></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'avatar' ); ?>"><?php _e('Display author\'s avatar : ', 'wpdx' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'avatar' ); ?>" name="<?php echo $this->get_field_name( 'avatar' ); ?>" value="true" <?php if( $instance['avatar'] ) echo 'checked="checked"'; ?> type="checkbox" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'social' ); ?>"><?php _e('Display Social icons : ', 'wpdx' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'social' ); ?>" name="<?php echo $this->get_field_name( 'social' ); ?>" value="true" <?php if( $instance['social'] ) echo 'checked="checked"'; ?> type="checkbox" />
		</p>
	<?php
	}
}