<?php
add_action('widgets_init', 'statistics_init');
function statistics_init() {
    register_widget('statistics');
}
class statistics extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'statistics',
            THEME_NAME .__( ' - Statistics' , 'wpdx'),
            array('classname' => 'widget-statistics','description' => __( 'Display Statistics of posts, comments, pages and so on.', 'wpdx' )),
            array( 'width' => 250, 'height' => 350)
            );
    }
    public function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', $instance['title'] );
        $start_date = $instance['start_date'];
        $last_date = $instance['last_date'];
        $count_days = $instance['count_days'];
        $count_users = $instance['count_users'];
        $count_posts = $instance['count_posts'];
        $count_comments = $instance['count_comments'];
        $count_pages = $instance['count_pages'];
        $count_categories = $instance['count_categories'];
        $count_tags = $instance['count_tags'];
        echo $before_widget;
        echo $before_title;
        echo $title ;
        echo $after_title;
?>
        <ul class="statistics clear">
            <?php
                if ($start_date) echo "<li><span>".sprintf( __( 'Start Date : %s ','wpdx'), $start_date)."</span></li>";

                global $wpdb;
                $last = get_transient('site_modified_date');
                if ( false === $last ){
                  $last = $wpdb->get_results("SELECT MAX(post_modified) AS MAX_m FROM $wpdb->posts WHERE (post_type = 'post' OR post_type = 'page') AND (post_status = 'publish' OR post_status = 'private')");
                    $last = date('Y-n-j', strtotime($last[0]->MAX_m));
                    set_transient( 'site_modified_date' , $last, 3600 );
                }
                if ($last_date) echo "<li><span>".sprintf( __( 'Last Updated : %s ','wpdx'), $last)."</span></li>";

                $online = get_transient('site_online_days');
                if ( false === $online ){
                    $online = floor((time()-strtotime($start_date))/86400);
                    set_transient( 'site_online_days' , $online, 3600 );
                }
                if ($count_days && $start_date) echo "<li><span>".sprintf( __( 'Online Days : %s ','wpdx'), $online)."</span></li>";

                $users = get_transient('site_all_users');
                if ( false === $users ){
                    $users = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
                    set_transient( 'site_all_users' , $users, 3600 );
                }
                if ($count_users) echo "<li><span>".sprintf( __( 'Users Number : %s ','wpdx'), $users)."</span></li>";

                $published_posts = get_transient('site_published_posts');
                if ( false === $published_posts ){
                    $posts_number = wp_count_posts();
                    $published_posts = $posts_number->publish;
                    set_transient( 'site_published_posts' , $published_posts, 3600 );
                }
                if ($count_posts) echo "<li><span>".sprintf( __( 'Posts Number : %s ', 'wpdx' ), $published_posts)."</span></li>";

                $comments_number = get_transient('site_comments_number');
                if ( false === $comments_number ){
                    $comments_number = get_comment_count();
                    set_transient( 'site_comments_number' , $comments_number, 3600 );
                }
                if ($count_comments) echo "<li><span>".sprintf( __( 'Comments Number : %s ','wpdx'), $comments_number['approved'])."</span></li>";

                $page_posts = get_transient('site_page_posts');
                if ( false === $page_posts ){
                    $pages_number = wp_count_posts('page');
                    $page_posts = $pages_number->publish;
                    set_transient( 'site_page_posts' , $page_posts, 3600 );
                }
                if ($count_pages) echo "<li><span>".sprintf( __( 'Pages Number : %s ','wpdx'), $page_posts)."</span></li>";

                $categories_number = get_transient('site_categories_number');
                if ( false === $categories_number ){
                    $categories_number = wp_count_terms('category');
                    set_transient( 'site_categories_number' , $categories_number, 3600 );
                }
                if ($count_categories) echo "<li><span>".sprintf( __( 'Categories Number : %s ','wpdx'), $categories_number)."</span></li>";

                $tags_number = get_transient('site_tags_number');
                if ( false === $tags_number ){
                    $tags_number = wp_count_terms('post_tag');
                    set_transient( 'site_tags_number' , $tags_number, 3600 );
                }
                if ($count_tags) echo "<li><span>".sprintf( __( 'Tags Number : %s ','wpdx'), $tags_number)."</span></li>";
            ?>
        </ul>
<?php
      echo $after_widget;
   }
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['start_date'] = strip_tags($new_instance['start_date']);
        $instance['last_date'] = strip_tags($new_instance['last_date']);
        $instance['count_days'] = strip_tags($new_instance['count_days']);
        $instance['count_users'] = strip_tags($new_instance['count_users']);
        $instance['count_posts'] = strip_tags($new_instance['count_posts']);
        $instance['count_comments'] = strip_tags($new_instance['count_comments']);
        $instance['count_pages'] = strip_tags($new_instance['count_pages']);
        $instance['count_categories'] = strip_tags($new_instance['count_categories']);
        $instance['count_tags'] = strip_tags($new_instance['count_tags']);
        return $instance;
    }
    public function form($instance) {
        $defaults = array( 'start_date' => get_first_post_date() );
        $instance = wp_parse_args( (array) $instance, $defaults );
        global $wpdb;
?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title : ', 'wpdx' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if(isset($instance['title']) && $instance['title']) echo $instance['title']; ?>" class="widefat" type="text" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'start_date' ); ?>"><?php _e('Start Date (e.g.2013-05-01) : ', 'wpdx' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'start_date' ); ?>" name="<?php echo $this->get_field_name( 'start_date' ); ?>" value="<?php if( isset($instance['start_date']) &&  $instance['start_date']) echo $instance['start_date']; ?>" class="widefat" type="text" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'last_date' ); ?>"><?php _e('Display Last Updated : ', 'wpdx' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'last_date' ); ?>" name="<?php echo $this->get_field_name( 'last_date' ); ?>" value="true" <?php if( isset($instance['last_date']) && $instance['last_date']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'count_days' ); ?>"><?php _e('Display Online Days : ', 'wpdx' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'count_days' ); ?>" name="<?php echo $this->get_field_name( 'count_days' ); ?>" value="true" <?php if( isset($instance['count_days']) && $instance['count_days']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'count_users' ); ?>"><?php _e('Display Users Number : ', 'wpdx' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'count_users' ); ?>" name="<?php echo $this->get_field_name( 'count_users' ); ?>" value="true" <?php if( isset($instance['count_users']) && $instance['count_users']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'count_posts' ); ?>"><?php _e('Display Posts Number : ', 'wpdx' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'count_posts' ); ?>" name="<?php echo $this->get_field_name( 'count_posts' ); ?>" value="true" <?php if( isset($instance['count_posts']) && $instance['count_posts']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'count_comments' ); ?>"><?php _e('Display Comments Number : ', 'wpdx' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'count_comments' ); ?>" name="<?php echo $this->get_field_name( 'count_comments' ); ?>" value="true" <?php if( isset($instance['count_comments']) && $instance['count_comments']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'count_pages' ); ?>"><?php _e('Display Pages Number : ', 'wpdx' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'count_pages' ); ?>" name="<?php echo $this->get_field_name( 'count_pages' ); ?>" value="true" <?php if( isset($instance['count_pages']) && $instance['count_pages']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'count_categories' ); ?>"><?php _e('Display Categories Number : ', 'wpdx' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'count_categories' ); ?>" name="<?php echo $this->get_field_name( 'count_categories' ); ?>" value="true" <?php if( isset($instance['count_categories']) && $instance['count_categories']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'count_tags' ); ?>"><?php _e('Display Tags Number : ', 'wpdx' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'count_tags' ); ?>" name="<?php echo $this->get_field_name( 'count_tags' ); ?>" value="true" <?php if( isset($instance['count_tags']) && $instance['count_tags']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
<?php
    }
}