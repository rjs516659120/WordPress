<?php
//widget readers
add_action('widgets_init', create_function('', 'return register_widget("readers");'));
class readers extends WP_Widget {
	public function __construct() {
        parent::__construct(
            'readers',
            THEME_NAME .__( ' - Active readers' , 'wpdx'),
            array('classname' => 'readers' ,'description' => __( 'Display avatar of the most active readers.', 'wpdx' )),
            array( 'width' => 250, 'height' => 350)
            );
    }
	public function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title']);
		$limit = $instance['limit'];
		$outer = $instance['outer'];
		$timer = $instance['timer'];
		echo $before_widget;
		echo $before_title.$title.$after_title;
		echo '<ul>';
		echo cmhello_readers( $out=$outer, $tim=$timer, $lim=$limit );;
		echo '</ul>';
		echo '<div class="clear"></div>';
		echo $after_widget;
	}
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['limit'] = strip_tags($new_instance['limit']);
		$instance['outer'] = strip_tags($new_instance['outer']);
		$instance['timer'] = strip_tags($new_instance['timer']);
		return $instance;
	}
	public function form($instance) {
		$defaults = array( 'title' => __( 'Active readers', 'wpdx' ),'limit' => '20','outer' => '1','timer' => '60' );
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title : ', 'wpdx' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e('Number: ', 'wpdx' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php echo $instance['limit']; ?>" type="text" size="5" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'outer' ); ?>"><?php _e('Exclude someone(ID): ', 'wpdx' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'outer' ); ?>" name="<?php echo $this->get_field_name( 'outer' ); ?>" value="<?php echo $instance['outer']; ?>" type="text" size="5" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'timer' ); ?>"><?php _e('Time limit(Days): ', 'wpdx' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'timer' ); ?>" name="<?php echo $this->get_field_name( 'timer' ); ?>" value="<?php echo $instance['timer']; ?>" type="text" size="5" />
		</p>
<?php
	}
}
/*
 * 读者墙
 * cmhello_readers( $outer='name', $timer='3', $limit='14' );
 * $outer 不显示某人
 * $timer 几个月时间内
 * $limit 显示条数
*/
function cmhello_readers($out,$tim,$lim){
  global $wpdb;
  $sql = "select count(comment_author) as cnt, comment_author, comment_author_url, comment_author_email from (select * from $wpdb->comments left outer join $wpdb->posts on ($wpdb->posts.id=$wpdb->comments.comment_post_id) where comment_date > date_sub( now(), interval $tim day ) and user_id='0' and comment_author != '".$out."' and post_password='' and comment_approved='1' and comment_type='') as tempcmt group by comment_author order by cnt desc limit $lim";
  $readers = get_transient('cmp_widget_readers');
  if ( false === $readers ){
      $readers = $wpdb->get_results($sql);
      set_transient( 'cmp_widget_readers' , $readers, 300 );
  }
  $i=1;
  foreach ($readers as $reader) {
    $c_url = $reader->comment_author_url;
    if ($c_url == '') $c_url = 'javascript:;';
    if(cmp_get_option( 'lazyload' )){
      echo '<li class="avatar-'.$i.'"><a rel="nofollow" target="_blank" href="'. $c_url . '" title="' . $reader->comment_author . ' + '. $reader->cnt . '" ><img class="avatar lazy lazy-hidden" src="'.get_template_directory_uri().'/assets/images/grey.gif" data-lazy-type="image" lazydata-src="'.get_avatar_url($reader->comment_author_email,array('size'=>'60')).'" alt="'. $reader->comment_author .'" width="60" height="60"/><noscript><img class="avatar" src="'.get_avatar_url($reader->comment_author_email,array('size'=>'60')).'" alt="'. $reader->comment_author .'" width="60" height="60"/></noscript></a></li>';
    }else{
      echo '<li class="avatar-'.$i.'"><a rel="nofollow" target="_blank" href="'. $c_url . '" title="' . $reader->comment_author . ' + '. $reader->cnt . '" ><img class="avatar" src="'.get_avatar_url($reader->comment_author_email,array('size'=>'60')).'" alt="'. $reader->comment_author .'" width="60" height="60"/></a></li>';
    }
    $i++;
  }
}