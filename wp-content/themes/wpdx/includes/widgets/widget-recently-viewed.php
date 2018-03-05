<?php
add_action('widgets_init', create_function('', 'return register_widget("cmRecentViewedPosts");'));
class cmRecentViewedPosts extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'recent_viewed_posts',
            THEME_NAME .__( ' - Posts Viewed Recently' , 'wpdx'),
            array('classname' => 'recent_viewed_posts' ,'description' => __( 'Display recent viewed posts/pages by a visitor as a responsive sidebar widget or in page/post using shortcode', 'wpdx' )),
            array( 'width' => 250, 'height' => 350)
            );
    }
    public function form($instance) {
        $widgetID = str_replace('recent_viewed_posts-', '', $this->id);
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $numberofposts   = isset( $instance['numberofposts'] ) ? absint( $instance['numberofposts'] ) : 5;
        $selected_posttypes = isset($instance['selected_posttypes']) ? $instance['selected_posttypes'] : array();
        $custom_post_types = get_post_types( array('public' => true,'_builtin' => false), 'names', 'and');
        $default_post_types = array('post'=>'post','page'=>'page');
        $post_types = array_merge($custom_post_types, $default_post_types);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wpdx'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p class="typeholder">
            <label><?php _e('Select Types:', 'wpdx'); ?></label><br />
            <?php
            foreach ($post_types as $post_type) {
                $obj = get_post_type_object( $post_type );
                $postName = $obj->name;
                $is_selected = false;
                if(in_array($post_type,$selected_posttypes) )
                    $is_selected = true;
                ?>
                <input type="checkbox" class="checkbox" id="cm_checkbox_<?php echo $post_type ;?>"
                name="<?php echo $this->get_field_name('selected_posttypes').'[]'; ?>" value="<?php echo $post_type ?>" <?php checked( $is_selected ); ?> >
                <label><?php echo $postName; ?></label><br/>
                <?php   }
                ?>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('numberofposts'); ?>"><?php _e('Number of posts to show:', 'wpdx'); ?></label>
                <input  id="<?php echo $this->get_field_id('numberofposts'); ?>" name="<?php echo $this->get_field_name('numberofposts'); ?>" type="text"  size="3" value="<?php echo $numberofposts; ?>" />
            </p>
                <?php if($widgetID != "__i__") { ?>
                <p style="font-size: 12px; opacity:0.6">
                    <span class="shortcodeTtitle"><?php _e('Shortcode:', 'wpdx'); ?></span>
                    <span class="shortcode">[cm-recentlyviewed widget_id="<?php echo $widgetID; ?>"]</span>
                </p>
                <?php }
            }
            public function update($new_instance, $old_instance) {
                $old_instance['title'] = $new_instance['title'];
                $old_instance['selected_posttypes'] = $new_instance['selected_posttypes'];
                $old_instance['numberofposts'] = isset($new_instance['numberofposts'])?(int)$new_instance['numberofposts']:'';
                return $old_instance ;
            }
            public function widget($args,$instance1) {
                $widgetID = $args['widget_id'];
                $widgetID = str_replace('recent_viewed_posts-', '', $widgetID);
                $widgetOptions = get_option($this->option_name);
                $instance1 = $widgetOptions[$widgetID];
                $title = ( ! empty( $instance1['title'] ) ) ? $instance1['title'] : __( 'Recent Visited Posts','wpdx' );
                $title = apply_filters( 'widget_title', $title, $instance1, $this->id_base );
                $number = ( ! empty( $instance1['numberofposts'] ) ) ? absint( $instance1['numberofposts'] ) : 10;
                $selected_posttypes = isset($instance1["selected_posttypes"]) ? $instance1["selected_posttypes"] : array();
                extract($args, EXTR_SKIP);
                if(isset($_COOKIE['cm_recent_posts']) && $_COOKIE['cm_recent_posts']!='')
                    {   $cm_cookie_posts =  unserialize($_COOKIE['cm_recent_posts']);
                $cm_cookie_posts = array_diff($cm_cookie_posts, array(get_the_ID()));
                if (count($cm_cookie_posts) > 0) :
                    $currentPostId = get_the_ID();
                $count = 0;
                echo $before_widget;
              echo $before_title . $title . $after_title;
              echo '<ul class="recently_viewed">' ;
                ?>
                <?php foreach ( $cm_cookie_posts as $postId ) {
                    if($count >= $number) return;
                    global $wpdb;
                    $post_exists = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE id = '" . $postId . "'", 'ARRAY_A');
              $cm_post = get_post($postId);

              if ($post_exists && $cm_post && $cm_post->ID != $currentPostId && in_array($cm_post->post_type,$selected_posttypes) ) {
               $count++;
               ?>
               <li>
                    <a href="<?php echo get_permalink( $cm_post->ID ); ?>" title="<?php echo get_the_title($cm_post->ID); ?>">
                        <?php echo get_the_title($cm_post->ID) ; ?>
                    </a>
            </li>
            <?php //if($count > 0 && $count == $number)  ?>
            <?php } } echo '</ul>'. $after_widget;?>
            <?php
            endif ;
        }
    }
}
function cm_posts_visited(){
    $cm_posts = array();
    if ( is_single() || is_page()){
        if(isset($_COOKIE['cm_recent_posts']) && $_COOKIE['cm_recent_posts']!='')
        {
            $cm_posts =  unserialize($_COOKIE['cm_recent_posts']);
            if (! is_array($cm_posts)) {
                $cm_posts = array(get_the_ID());
            }else{
                $cm_posts = array_diff($cm_posts, array(get_the_ID()));
                array_unshift($cm_posts,get_the_ID());
            }
        }else{
            $cm_posts = array(get_the_ID());
        }
        $cm_blog_url_array = parse_url(esc_url( home_url() ) );
        $cm_blog_url = $cm_blog_url_array['host'];
        $cm_blog_url = str_replace('www.', '', $cm_blog_url);
        $cm_blog_url_dot = '.';
        $cm_blog_url_dot .= $cm_blog_url;
        setcookie( 'cm_recent_posts', serialize($cm_posts) ,time() + ( DAY_IN_SECONDS * 31 ),'/');
    }
}
add_action('template_redirect', 'cm_posts_visited');
function cm_shortcode_recentlyViewed( $atts ){
    $args = array(
        'widget_id' => $atts['widget_id'],
        'by_shortcode' => 'shortcode_',
    );
    ob_start();
    the_widget( 'cmRecentViewedPosts', '', $args);
    $output = ob_get_clean();
    return $output;
}
add_shortcode( 'cm-recentlyviewed', 'cm_shortcode_recentlyViewed' );