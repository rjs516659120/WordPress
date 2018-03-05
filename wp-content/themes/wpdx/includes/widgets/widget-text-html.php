<?php
add_action( 'widgets_init', 'text_html_widget' );
function text_html_widget() {
	register_widget( 'text_html' );
}
class text_html extends WP_Widget {
	public function __construct() {
        parent::__construct(
            'text-html-widget',
            THEME_NAME .__( ' - Text or Html' , 'wpdx'),
            array( 'classname' => 'text-html', 'description' => __( 'Display custom text, support html and shortcode.' , 'wpdx')  ),
            array( 'width' => 250, 'height' => 350)
            );
    }

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$text_code = $instance['text_code'];
		$hide_title = $instance['hide_title'];
		$center = $instance['center'];

		if ($center)
			$center = 'align-center';
		else
			$center = '';

		if( !$hide_title ){
			echo $before_widget;
			echo $before_title;
			echo $title ;
			echo $after_title;
			echo '<div class="'.$center.'">';
			echo do_shortcode( $text_code ) .'
		</div><div class="clear"></div>';
		echo $after_widget;
	} else {?>
	<div class="widget-box widget <?php echo $center ?>">
		<div class="widget-content">
		<?php echo do_shortcode( $text_code ) ?>
		</div>
	</div>
	<?php
}
}
public function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['title'] = strip_tags( $new_instance['title'] );
	$instance['text_code'] = $new_instance['text_code'] ;
	$instance['hide_title'] = strip_tags( $new_instance['hide_title'] );
	$instance['center'] = strip_tags( $new_instance['center'] );
	return $instance;
}
public function form( $instance ) {
	$defaults = array( 'title' =>__('Text' , 'wpdx')  );
	$instance = wp_parse_args( (array) $instance, $defaults ); ?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title : ', 'wpdx' ) ?></label>
		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'hide_title' ); ?>"><?php _e('Hide Widget Title :', 'wpdx' ) ?></label>
		<input id="<?php echo $this->get_field_id( 'hide_title' ); ?>" name="<?php echo $this->get_field_name( 'hide_title' ); ?>" value="true" <?php if( isset($instance['hide_title']) && $instance['hide_title'] ) echo 'checked="checked"'; ?> type="checkbox" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'center' ); ?>"><?php _e('Center content :', 'wpdx' ) ?></label>
		<input id="<?php echo $this->get_field_id( 'center' ); ?>" name="<?php echo $this->get_field_name( 'center' ); ?>" value="true" <?php if( isset($instance['center']) && $instance['center'] ) echo 'checked="checked"'; ?> type="checkbox" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'text_code' ); ?>"><?php _e('Text , Shortcodes or Html code : ', 'wpdx' ) ?></label>
		<textarea rows="15" id="<?php echo $this->get_field_id( 'text_code' ); ?>" name="<?php echo $this->get_field_name( 'text_code' ); ?>" class="widefat" ><?php if(isset($instance['text_code']) && $instance['text_code']) echo $instance['text_code']; ?></textarea>
	</p>

	<?php
}
}