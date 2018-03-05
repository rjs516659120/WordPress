<?php
add_action( 'widgets_init', 'news_pic_widget' );
function news_pic_widget() {
	register_widget( 'news_pic' );
}
class news_pic extends WP_Widget {
	public function __construct() {
        parent::__construct(
            'news-pic-widget',
            THEME_NAME .__( ' - News in Pictures' , 'wpdx'),
            array( 'classname' => 'widget-pic','description' => __( 'Display posts with big image of any category.', 'wpdx' ) ),
            array( 'width' => 250, 'height' => 350)
            );
    }
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$no_of_posts = $instance['no_of_posts'];
		$cats_id = $instance['cats_id'];
		$posts_order = $instance['posts_order'];
		echo $before_widget;
			echo $before_title;
			echo $title ; ?>
		<?php echo $after_title; ?>
				<?php wp_last_news_pic($posts_order , $no_of_posts , $cats_id)?>
		<div class="clear"></div>
	<?php
		echo $after_widget;
	}
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['no_of_posts'] = strip_tags( $new_instance['no_of_posts'] );
		$instance['cats_id'] = implode(',' , $new_instance['cats_id']  );
		$instance['posts_order'] = strip_tags( $new_instance['posts_order'] );
		return $instance;
	}
	public function form( $instance ) {
		$defaults = array( 'title' =>__('News in Pictures' , 'wpdx'), 'no_of_posts' => '6' , 'cats_id' => '1' , 'posts_order' => 'latest' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$categories_obj = get_categories();
		$categories = array();
		foreach ($categories_obj as $pn_cat) {
			$categories[$pn_cat->cat_ID] = $pn_cat->cat_name;
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title : ', 'wpdx' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'no_of_posts' ); ?>"><?php _e('Number of posts to show: ', 'wpdx' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'no_of_posts' ); ?>" name="<?php echo $this->get_field_name( 'no_of_posts' ); ?>" value="<?php echo $instance['no_of_posts']; ?>" type="text" size="3" />
		</p>
		<p>
			<?php $cats_id = explode ( ',' , $instance['cats_id'] ) ; ?>
			<label for="<?php echo $this->get_field_id( 'cats_id' ); ?>"><?php _e('Category : ', 'wpdx' ) ?></label>
			<select multiple="multiple" id="<?php echo $this->get_field_id( 'cats_id' ); ?>[]" name="<?php echo $this->get_field_name( 'cats_id' ); ?>[]">
				<?php foreach ($categories as $key => $option) { ?>
				<option value="<?php echo $key ?>" <?php if ( in_array( $key , $cats_id ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'posts_order' ); ?>"><?php _e('Posts order : ', 'wpdx' ) ?></label>
			<select id="<?php echo $this->get_field_id( 'posts_order' ); ?>" name="<?php echo $this->get_field_name( 'posts_order' ); ?>" >
				<option value="latest" <?php if( $instance['posts_order'] == 'latest' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Most recent', 'wpdx' ) ?></option>
				<option value="random" <?php if( $instance['posts_order'] == 'random' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Random', 'wpdx' ) ?></option>
			</select>
		</p>
	<?php
	}
}