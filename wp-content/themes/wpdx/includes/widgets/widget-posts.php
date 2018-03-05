<?php
add_action( 'widgets_init', 'posts_list_widget' );
function posts_list_widget() {
	register_widget( 'posts_list' );
}
class posts_list extends WP_Widget {
	public function __construct() {
        parent::__construct(
            'posts-list-widget',
            THEME_NAME .__( ' - Posts list' , 'wpdx'),
            array( 'classname' => 'widget-posts' ,'description' => __('Display latest posts, random posts or posts with most comments.','wpdx') ),
            array( 'width' => 250, 'height' => 350)
            );
    }
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$no_of_posts = $instance['no_of_posts'];
		$posts_order = $instance['posts_order'];
		$thumb = $instance['thumb'];
		$time_limit = '';
		$time_limit = $instance['time_limit'];
		echo $before_widget;
		echo $before_title;
		echo $title ; ?>
		<?php echo $after_title; ?>
		<ul>
			<?php
			if( $posts_order == 'popular' )
				wp_popular_posts($no_of_posts , $thumb , $time_limit);
			elseif( $posts_order == 'random' )
				wp_random_posts($no_of_posts , $thumb);
			else
				wp_last_posts($no_of_posts , $thumb)?>
		</ul>
		<div class="clear"></div>
		<?php
		echo $after_widget;
	}
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['no_of_posts'] = strip_tags( $new_instance['no_of_posts'] );
		$instance['posts_order'] = strip_tags( $new_instance['posts_order'] );
		$instance['thumb'] = strip_tags( $new_instance['thumb'] );
		$instance['time_limit'] = strip_tags( $new_instance['time_limit'] );
		return $instance;
	}
	public function form( $instance ) {
		$defaults = array( 'title' =>__('Recent Posts' , 'wpdx') , 'no_of_posts' => '5' , 'posts_order' => 'latest', 'thumb' => 'true' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title : ', 'wpdx' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'no_of_posts' ); ?>"><?php _e('Number of posts to show: ', 'wpdx' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'no_of_posts' ); ?>" name="<?php echo $this->get_field_name( 'no_of_posts' ); ?>" value="<?php echo $instance['no_of_posts']; ?>" type="text" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'posts_order' ); ?>"><?php _e('Posts order : ', 'wpdx' ) ?></label>
			<select id="<?php echo $this->get_field_id( 'posts_order' ); ?>" name="<?php echo $this->get_field_name( 'posts_order' ); ?>" >
				<option value="latest" <?php if( $instance['posts_order'] == 'latest' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Most recent', 'wpdx' ) ?></option>
				<option value="random" <?php if( $instance['posts_order'] == 'random' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Random', 'wpdx' ) ?></option>
				<option value="popular" <?php if( $instance['posts_order'] == 'popular' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Popular', 'wpdx' ) ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb' ); ?>"><?php _e('Display Thumbinals : ', 'wpdx' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'thumb' ); ?>" name="<?php echo $this->get_field_name( 'thumb' ); ?>" value="true" <?php if( $instance['thumb'] ) echo 'checked="checked"'; ?> type="checkbox" />
		</p>
		<p style="color:red;"><?php _e('The following options apply only to popular.', 'wpdx' ) ?></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'time_limit' ); ?>"><?php _e('Time limit of popular: ', 'wpdx' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'time_limit' ); ?>" name="<?php echo $this->get_field_name( 'time_limit' ); ?>" value="<?php if(isset( $instance['time_limit']) && $instance['time_limit']) echo $instance['time_limit']; ?>" type="text" size="3" />
		</p>
		<?php
	}
}