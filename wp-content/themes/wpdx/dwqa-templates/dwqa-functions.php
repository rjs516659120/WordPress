<?php
/**
 * custom permalink to id.html
 * http://www.wpdaxue.com/custom-post-type-permalink-code.html
 */
add_filter('post_type_link', 'custom_qa_link', 1, 3);
function custom_qa_link( $link, $post = 0 ){
    global  $dwqa_general_settings;
    $dwqa_general_settings = get_option( 'dwqa_options' );
    $question_rewrite = $dwqa_general_settings['question-rewrite'] ? $dwqa_general_settings['question-rewrite'] : 'question';
	if ( $post->post_type == 'dwqa-question' ){
		return home_url( $question_rewrite.'/' . $post->ID .'.html' );
	} else {
		return $link;
	}
}
add_action( 'init', 'custom_qa_rewrites_init' );
function custom_qa_rewrites_init(){
    global  $dwqa_general_settings;
    $dwqa_general_settings = get_option( 'dwqa_options' );
    $question_rewrite = $dwqa_general_settings['question-rewrite'] ? $dwqa_general_settings['question-rewrite'] : 'question';
	add_rewrite_rule(
		$question_rewrite.'/([0-9]+)?.html$',
		'index.php?post_type=dwqa-question&p=$matches[1]',
		'top' );
}
/**
 * reverse comments of DWQA
 * http://www.wpdaxue.com/wordpress-reverse-comments.html
 */
function dwqa_reverse_comments($comments) {
    global $post;
    if($post->post_type == 'dwqa-question' || $post->post_type == 'dwqa-answer' ){
        return array_reverse($comments);
    }else{
        return $comments;
    }
}
add_filter ('comments_array', 'dwqa_reverse_comments');
/**
  * load style for DW Q&A plugin
  */
if( !function_exists('dwqa_simplex_scripts') ){
	function dwqa_simplex_scripts(){
		wp_enqueue_style( 'dw-simplex-qa', get_stylesheet_directory_uri() . '/dwqa-templates/style.css' );
	}
	//add_action( 'wp_enqueue_scripts', 'dwqa_simplex_scripts' );
}
/**
 * Widgets For DWQA
 */
add_action( 'widgets_init', 'dwqa_widgets_init' );
function dwqa_widgets_init() {
	$before_widget =  '<div id="%1$s" class="widget-box widget %2$s">';
    $after_widget  =  '</div></div>';
    $before_title  =  '<div class="widget-title"><span class="icon"><i class="fa fa-list fa-fw"></i></span><h3>';
    $after_title   =  '</h3></div><div class="widget-content">';
	register_sidebar( array(
		'name' =>  __( 'DWQA Widget Area', 'wpdx' ),
		'id' => 'dwqa-widget-area',
		'description' => __( 'The DW Questions & Answer widget area', 'wpdx' ),
		'before_widget' => $before_widget , 'after_widget' => $after_widget ,
		'before_title' => $before_title , 'after_title' => $after_title ,
		) );
}

function my_dwqa_comment_form( $args = array(), $post_id = null ) {
    if ( null === $post_id )
        $post_id = get_the_ID();
    else
        $id = $post_id;
    $commenter = wp_get_current_commenter();
    $user = wp_get_current_user();
    $user_identity = $user->exists() ? $user->display_name : '';
    $args = wp_parse_args( $args );
    if ( ! isset( $args['format'] ) )
        $args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';
    $req      = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $html5    = 'html5' === $args['format'];
    $fields   = array(
        'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email', 'wpdx' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
                    '<input id="email-'.$post_id.'" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
        'author'  => '<p class="comment-form-name"><label for="name">' . __( 'Name', 'wpdx' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label>' . '<input id="name-' .$post_id.'" name="name" type="text" value="" size="30"/></p>'
    );
    $required_text = sprintf( ' ' . __( 'Required fields are marked %s','wpdx' ), '<span class="required">*</span>' );
    /**
     * Filter the default comment form fields.
     *
     * @since 3.0.0
     *
     * @param array $fields The default comment fields.
     */
    $fields = apply_filters( 'comment_form_default_fields', $fields );
    $defaults = array(
        'fields'               => $fields,
        'comment_field'        => '',
        'must_log_in'          => '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.','wpdx' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
        'logged_in_as'         => '<p class="comment-form-comment"><textarea id="comment" name="comment" placeholder="'.__('Post a comment','wpdx').'" rows="2" aria-required="true"></textarea></p>',
        'comment_notes_before' => '<p class="comment-form-comment"><textarea id="comment" name="comment" placeholder="Comment" rows="2" aria-required="true"></textarea></p>',
        'comment_notes_after'  => '<p class="form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s','wpdx' ), ' <code>' . allowed_tags() . '</code>' ) . '</p>',
        'id_form'              => 'commentform',
        'id_submit'            => 'submit',
        'title_reply'          => __( 'Leave a Reply','wpdx' ),
        'title_reply_to'       => __( 'Leave a Reply to %s','wpdx' ),
        'cancel_reply_link'    => __( 'Cancel reply', 'wpdx' ),
        'label_submit'         => __( 'Post Comment', 'wpdx' ),
        'format'               => 'xhtml',
    );
    /**
     * Filter the comment form default arguments.
     *
     * Use 'comment_form_default_fields' to filter the comment fields.
     *
     * @since 3.0.0
     *
     * @param array $defaults The default comment form arguments.
     */
    $args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );
    if ( comments_open( $post_id ) ) :
        /**
         * Fires before the comment form.
         *
         * @since 3.0.0
         */
        do_action( 'comment_form_before' );
        ?>
        <div id="dwqa-respond" class="dwqa-comment-form">
        <?php if ( !dwqa_current_user_can( 'post_comment' ) ) : ?>
            <?php echo $args['must_log_in']; ?>
            <?php
            /**
             * Fires after the HTML-formatted 'must log in after' message in the comment form.
             *
             * @since 3.0.0
             */
            do_action( 'comment_form_must_log_in_after' );
            ?>
        <?php else : ?>
            <form method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>" class="comment-form"<?php echo $html5 ? ' novalidate' : ''; ?>>
            <?php
            /**
             * Fires at the top of the comment form, inside the <form> tag.
             *
             * @since 3.0.0
             */
            do_action( 'comment_form_top' );
            ?>
            <?php if ( is_user_logged_in() ) : ?>
                <?php
                /**
                 * Filter the 'logged in' message for the comment form for display.
                 *
                 * @since 3.0.0
                 *
                 * @param string $args['logged_in_as'] The logged-in-as HTML-formatted message.
                 * @param array  $commenter            An array containing the comment author's username, email, and URL.
                 * @param string $user_identity        If the commenter is a registered user, the display name, blank otherwise.
                 */
                echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity );
                ?>
                <?php
                /**
                 * Fires after the is_user_logged_in() check in the comment form.
                 *
                 * @since 3.0.0
                 *
                 * @param array  $commenter     An array containing the comment author's username, email, and URL.
                 * @param string $user_identity If the commenter is a registered user, the display name, blank otherwise.
                 */
                do_action( 'comment_form_logged_in_after', $commenter, $user_identity );
                ?>
            <?php else : ?>
                <?php echo $args['comment_notes_before']; ?>
                <?php
                /**
                 * Fires before the comment fields in the comment form.
                 *
                 * @since 3.0.0
                 */
                do_action( 'comment_form_before_fields' );
                echo '<div class="dwqa-anonymous-fields">';
                foreach ( (array ) $args['fields'] as $name => $field ) {
                    /**
                     * Filter a comment form field for display.
                     *
                     * The dynamic portion of the filter hook, $name, refers to the name
                     * of the comment form field. Such as 'author', 'email', or 'url'.
                     *
                     * @since 3.0.0
                     *
                     * @param string $field The HTML-formatted output of the comment form field.
                     */
                    echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
                }
                echo '</div>';
                /**
                 * Fires after the comment fields in the comment form.
                 *
                 * @since 3.0.0
                 */
                do_action( 'comment_form_after_fields' );
                ?>
            <?php endif; ?>
            <?php
            /**
             * Filter the content of the comment textarea field for display.
             *
             * @since 3.0.0
             *
             * @param string $args['comment_field'] The content of the comment textarea field.
             */
            //echo apply_filters( 'comment_form_field_comment', $args['comment_field'] );
            ?>
            <input name="comment-submit" type="submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>" value="<?php echo esc_attr( $args['label_submit'] ); ?>" class="dwqa-btn dwqa-btn-primary" />
            <?php comment_id_fields( $post_id ); ?>
            <?php
            /**
             * Fires at the bottom of the comment form, inside the closing </form> tag.
             *
             * @since 1.5.0
             *
             * @param int $post_id The post ID.
             */
            //do_action( 'comment_form', $post_id );
            ?>
            </form>
        <?php endif; ?>
        </div><!-- #respond -->
        <?php
        /**
         * Fires after the comment form.
         *
         * @since 3.0.0
         */
        do_action( 'comment_form_after' );
    else :
        /**
         * Fires after the comment form if comments are closed.
         *
         * @since 3.0.0
         */
        do_action( 'comment_form_comments_closed' );
    endif;
}
