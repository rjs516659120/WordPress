<?php
## Author posts Widget
add_action( 'widgets_init', 'Author_post_widget_box' );
function Author_post_widget_box(){
    register_widget( 'author_post_widget' );
}
class author_post_widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'author_post_widget',
            THEME_NAME .__( ' - Posts By Post Author', 'wpdx' ),
            array( 'classname' => 'widget-author-posts' , 'description' => __( 'Display posts of current author.', 'wpdx' )),
            array( 'width' => 250, 'height' => 350)
            );
    }
    public function widget( $args, $instance ) {
        extract( $args );
        if ( is_single() ) :
            $no_of_posts = $instance['no_of_posts'];
            $see_all = $instance['see_all'];
            $orig_post = $post;
            $authorID = get_the_author_meta( 'ID' );
            $args=array('author' => $authorID , 'post__not_in' => array($post->ID), 'posts_per_page'=> $no_of_posts,'no_found_rows' => 1);
            $my_query = new wp_query( $args );
        if( $my_query->have_posts() ) :
            echo $before_widget;
                echo $before_title;
                printf( __( 'By %s', 'wpdx' ), get_the_author() );
            echo $after_title; ?>
            <ul>
            <?php while( $my_query->have_posts() ) { $my_query->the_post();?>
                <li><a href="<?php the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
            <?php } ?>
            </ul>
            <?php if($see_all) : ?>
            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"> <?php _e('All' , 'wpdx') ?> (<?php echo count_user_posts($authorID) ?>)</a>
            <?php endif; ?>
            <?php
            $post = $orig_post; wp_reset_query();
            echo $after_widget;
        endif;
        endif;
    }
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['no_of_posts'] = strip_tags( $new_instance['no_of_posts'] );
        $instance['see_all'] = strip_tags( $new_instance['see_all'] );
        return $instance;
    }
    public function form( $instance ) {
        $defaults = array( 'no_of_posts' => '5' , 'see_all' => 'true' );
        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>
        <p><em style="color:red;"><?php _e('This Widget appears in single post only .' , 'wpdx') ?></em></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'no_of_posts' ); ?>"><?php _e('Number of posts to show: ' , 'wpdx') ?></label>
            <input id="<?php echo $this->get_field_id( 'no_of_posts' ); ?>" name="<?php echo $this->get_field_name( 'no_of_posts' ); ?>" value="<?php echo $instance['no_of_posts']; ?>" type="text" size="3" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'see_all' ); ?>"><?php _e('Display ( see all ) link : ' , 'wpdx') ?></label>
            <input id="<?php echo $this->get_field_id( 'see_all' ); ?>" name="<?php echo $this->get_field_name( 'see_all' ); ?>" value="true" <?php if( $instance['see_all'] ) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
    <?php
    }
}