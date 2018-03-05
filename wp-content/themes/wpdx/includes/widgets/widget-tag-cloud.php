<?php
add_action( 'widgets_init', 'Tag_Cloud_Widget' );
function Tag_Cloud_Widget() {
	register_widget( 'cm_Tag_Cloud_Widget' );
}
class cm_Tag_Cloud_Widget extends WP_Widget {
	public function __construct() {
        parent::__construct(
            'cm-tagcloud',
            THEME_NAME .__( ' - Tag Cloud' , 'wpdx'),
            array('classname' => 'widget-tagcloud', 'description' => __( 'Display the most common tags.', 'wpdx' )),
            array( 'width' => 250, 'height' => 350)
            );
    }
	public function widget( $args, $instance ) {
		extract($args);
		$nums = empty($instance['nums'])? 45 : $instance['nums'];
		$excludetag = $instance['excludetag'];
    	$url = empty($instance['url'])?'':$instance['url'];
		$ordertag = empty($instance['ordertag'])? 'ASC' : $instance['ordertag'];
		$orderbytag = empty($instance['orderbytag'])? 'name' : $instance['orderbytag'];
    	$tagunit = empty($instance['tagunit'])? 'px' : $instance['tagunit'];
    	$tagbigsize = empty($instance['tagbigsize'])? '20' : $instance['tagbigsize'];
    	$tagsmallsize = empty($instance['tagsmallsize'])? '12' : $instance['tagsmallsize'];
		$current_taxonomy = $this->_get_current_taxonomy($instance);
		if ( !empty($instance['title']) ) {
			$title = $instance['title'];
		} else {
			if ( 'post_tag' == $current_taxonomy ) {
				$title = __('Tags','wpdx');
			} else {
				$tax = get_taxonomy($current_taxonomy);
				$title = $tax->labels->name;
			}
		}
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);
		echo $before_widget;
		if ( $title )
      		echo $before_title .'<a href="'.$url.'" rel="nofollow">' .$title .'</a>' . $after_title;
		echo '<div class="tagcloud">';
		wp_tag_cloud( apply_filters('widget_tag_cloud_args', array(
			'smallest' => $tagsmallsize,
			'largest' => $tagbigsize,
			'unit' => $tagunit,
			'number' => $nums,
			'orderby' => $orderbytag,
			'order' => $ordertag,
			'taxonomy' => $current_taxonomy,
			'exclude' => $excludetag
			)));
		echo "</div>\n";
		echo $after_widget;
	}
	public function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
    		$instance['url'] = stripslashes($new_instance['url']);
		$instance['taxonomy'] = stripslashes($new_instance['taxonomy']);
		$instance['nums'] = stripslashes($new_instance['nums']);
		$instance['excludetag'] = stripslashes($new_instance['excludetag']);
		$instance['ordertag'] = stripslashes($new_instance['ordertag']);
		$instance['orderbytag'] = stripslashes($new_instance['orderbytag']);
		$instance['tagunit'] = stripslashes($new_instance['tagunit']);
		$instance['tagbigsize'] = stripslashes($new_instance['tagbigsize']);
		$instance['tagsmallsize'] = stripslashes($new_instance['tagsmallsize']);
		return $instance;
	}

	public function _get_current_taxonomy($instance) {
		if ( !empty($instance['taxonomy']) && taxonomy_exists($instance['taxonomy']) )
			return $instance['taxonomy'];

		return 'post_tag';
	}

	public function form( $instance ){
		$current_taxonomy = $this->_get_current_taxonomy($instance);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title : ', 'wpdx' ) ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (isset ( $instance['title']) && $instance['title']) echo esc_attr( $instance['title'] ); ?>" /></p>
    			<p><label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Tags page url: ', 'wpdx' ) ?></label>
      			<input type="text" class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" value="<?php if (isset ( $instance['url']) && $instance['url']) echo esc_attr( $instance['url'] ); ?>" /></p>
			<p><label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomies :','wpdx') ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>">
					<?php foreach ( get_object_taxonomies('post') as $taxonomy ) :
					$tax = get_taxonomy($taxonomy);
					if ( !$tax->show_tagcloud || empty($tax->labels->name) )
						continue;
					?>
					<option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, $current_taxonomy) ?>><?php echo $tax->labels->name; ?></option>
				<?php endforeach; ?>
			</select></p>
			<p><label for="<?php echo $this->get_field_id('nums'); ?>"><?php _e('Number to show (Default "45"): ', 'wpdx' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('nums'); ?>" name="<?php echo $this->get_field_name('nums'); ?>" value="<?php if (isset ( $instance['nums']) && $instance['nums']) echo esc_attr( $instance['nums'] ); ?>" /></p>
				<p><label for="<?php echo $this->get_field_id('orderbytag'); ?>"><?php _e('Tags orderby (Default "name"]): ', 'wpdx' ) ?></label>
					<select  class="widefat" id="<?php echo $this->get_field_id('orderbytag'); ?>" name="<?php echo $this->get_field_name('orderbytag'); ?>">
						<option <?php if ( isset($instance['orderbytag']) && $instance['orderbytag'] == 'name') echo 'selected="SELECTED"'; else echo ''; ?>  value="name"><?php  echo __('name','wpdx');?></option>
						<option <?php if ( isset($instance['orderbytag']) && $instance['orderbytag'] == 'count') echo 'selected="SELECTED"'; else echo ''; ?> value="count"><?php echo __('count','wpdx');?></option>
					</select>
				</p>
				<p><label for="<?php echo $this->get_field_id('ordertag'); ?>"><?php _e('Tags order (Default "ASC"): ', 'wpdx' ) ?></label>
					<select  class="widefat" id="<?php echo $this->get_field_id('ordertag'); ?>" name="<?php echo $this->get_field_name('ordertag'); ?>">
						<option <?php if ( isset($instance['ordertag']) && $instance['ordertag'] == 'ASC') echo 'selected="SELECTED"'; else echo ''; ?>  value="ASC"><?php  echo __('ASC','wpdx');?></option>
						<option <?php if ( isset($instance['ordertag']) && $instance['ordertag'] == 'DESC') echo 'selected="SELECTED"'; else echo ''; ?> value="DESC"><?php echo __('DESC','wpdx');?></option>
						<option <?php if ( isset($instance['ordertag']) && $instance['ordertag'] == 'RAND') echo 'selected="SELECTED"'; else echo ''; ?> value="RAND"><?php echo __('RAND','wpdx');?></option>
					</select>
				</p>
        	<p><label for="<?php echo $this->get_field_id('tagunit'); ?>"><?php _e('Unit (Default "px"):','wpdx') ?></label>
					<select  class="widefat" id="<?php echo $this->get_field_id('tagunit'); ?>" name="<?php echo $this->get_field_name('tagunit'); ?>">
            	<option <?php if ( isset($instance['tagunit']) && $instance['tagunit'] == 'px') echo 'selected="SELECTED"'; else echo ''; ?>  value="px"><?php  echo __('px','wpdx');?></option>
            	<option <?php if ( isset($instance['tagunit']) && $instance['tagunit'] == 'pt') echo 'selected="SELECTED"'; else echo ''; ?>  value="pt"><?php  echo __('pt','wpdx');?></option>
						<option <?php if ( isset($instance['tagunit']) && $instance['tagunit'] == 'em') echo 'selected="SELECTED"'; else echo ''; ?> value="em"><?php echo __('em','wpdx');?></option>
						<option <?php if ( isset($instance['tagunit']) && $instance['tagunit'] == '%') echo 'selected="SELECTED"'; else echo ''; ?> value="%"><?php echo __('%','wpdx');?></option>
					</select>
				</p>
        	<p><label for="<?php echo $this->get_field_id('tagsmallsize'); ?>"><?php _e('Smallest text size (Default "12"):','wpdx') ?></label>
					<input type="text" class="widefat" id="<?php echo $this->get_field_id('tagsmallsize'); ?>" name="<?php echo $this->get_field_name('tagsmallsize'); ?>" value="<?php if (isset ( $instance['tagsmallsize']) && $instance['tagsmallsize']) echo esc_attr( $instance['tagsmallsize'] ); ?>" /></p>
          	<p><label for="<?php echo $this->get_field_id('tagbigsize'); ?>"><?php _e('Largest text size (Default "20"):','wpdx') ?></label>
						<input type="text" class="widefat" id="<?php echo $this->get_field_id('tagbigsize'); ?>" name="<?php echo $this->get_field_name('tagbigsize'); ?>" value="<?php if (isset ( $instance['tagbigsize']) && $instance['tagbigsize']) echo esc_attr( $instance['tagbigsize'] ); ?>" /></p>
						<p><label for="<?php echo $this->get_field_id('excludetag'); ?>"><?php _e('Exclude tags (Use commas to separate IDs):','wpdx') ?></label>
							<input type="text" class="widefat" id="<?php echo $this->get_field_id('excludetag'); ?>" name="<?php echo $this->get_field_name('excludetag'); ?>" value="<?php if (isset ( $instance['excludetag']) && $instance['excludetag']) echo esc_attr( $instance['excludetag'] ); ?>" /></p>
							<?php
						}
					}