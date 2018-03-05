<?php
/**
 * @Author: Changmeng Hu
 * @Date:   2016-07-10 21:39:16
 * @Last Modified time: 2017-11-18 10:22:15
 */
if (!class_exists("cmpUserFrontendPost")) {
    class cmpUserFrontendPost {

    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/

    /**
     * Initializes the plugin by setting localization, filters, and administration functions.
     */
    function __construct() {

        // Register site styles and scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );

        // Setup Ajax Support
        add_action('wp_ajax_process_frontend_post_form', array( $this, 'process_frontend_post_form' ) );
        add_action('wp_ajax_nopriv_process_frontend_post_form', array( $this, 'process_frontend_post_form' ) );
         
        // Save an auto-draft to get a valid post-id
        add_action ('save_cmp_auto_draft', array($this, 'save_cmp_auto_draft'));

        // Print an edit post on front end link whenever an edit post link is printed on front end.
        add_filter('edit_post_link', array($this, 'edit_post_link'), 10, 2);

        // Redirect non admin users from dashboard
        add_filter('login_redirect', array($this, 'cmp_login_redirect'), 10, 3);
        
        //Call our shortcode handler
        add_shortcode('cmpuser-frontend-post', array($this, 'frontend_post_shortcode'));

        add_shortcode('cmpuser-post-list', array($this, 'post_list_shortcode'));
        
    } // end constructor

    /**
     * Registers and enqueues scripts.
     */
    public function register_scripts() {
        $pageId = cmp_get_page_id_by_shortcode('cmpuser-frontend-post');
        if( is_page($pageId) ){
            wp_enqueue_script( 'frontend-post', get_template_directory_uri() . '/assets/js/frontend-post.js', array('jquery'), THEME_VER);
            wp_localize_script( 'frontend-post', 'fp_var', array(
              'update' => __('Update','wpdx')
              )
            );
        }  
    }

    public function cmp_login_redirect( $redirect_to, $request, $user  ) {
        if ( ! is_wp_error( $user ) ) {
            if( $user->has_cap( 'administrator' ) ){
                return admin_url();
            }else{
                return site_url();
            }
            //return ( is_array( $user->roles ) && in_array( 'administrator', $user->roles ) ) ? admin_url() : site_url();
        }
    }

    /*
     * Validate input
     */
    public function cmpuser_frontend_post_validate_input($input) {

        // Create our array for storing the validated options
        $output = array();

        // Loop through each of the incoming options
        foreach( $input as $key => $value ) {

            // Check to see if the current option has a value. If so, process it.
            if( isset( $input[$key] ) ) {
            // Strip all HTML and PHP tags and properly handle quoted strings
            $output[$key] = esc_attr(strip_tags( stripslashes( $input[ $key ] ) ) );
            }
        }
        // Return the array processing any additional functions filtered by this action
        return apply_filters( 'cmpuser_frontend_post_validate_input', $output, $input );
    }

    // Following two functions make sure that image attachment gets the right post-id
    public function cmp_insert_media_fix( $post_id ) {
        global $cmp_media_post_id;
        global $post_ID; 
    
        /* WordPress 3.4.2 fix */
        $post_ID = $post_id; 
    
        /* WordPress 3.5.1 fix */
        $cmp_media_post_id = $post_id;
        add_filter( 'media_view_settings', array($this, 'cmp_insert_media_fix_filter'), 10, 2 );
    }

    public function cmp_insert_media_fix_filter( $settings, $post ) {
        global $cmp_media_post_id;
    
        $settings['post']['id'] = $cmp_media_post_id;
        $settings['post']['nonce'] = wp_create_nonce( 'update-post_' . $cmp_media_post_id );
        return $settings;
    }
    
    /*---------------------------------------------*
     * Core Functions
     *---------------------------------------------*/

    /*
     * Print a link to edit post on front end whenever an edit post link is printed on front end.
     */
    function edit_post_link($link, $post_id) {
        if ( 'page' != get_post_type($post_id) ) {
            if ( cmp_get_option('edit_page_id') ) {
                if ( cmp_get_option('hide_edit_link') ) {
                    $link = '<a class="post-edit-link" href="' . home_url('/') . '?page_id='.cmp_get_option('edit_page_id') . '&post_id='.$post_id . '" title="'.__('Frontend Edit','wpdx').'">'.__('Frontend Edit','wpdx').'</a>';
                } else {
                    $link = $link . ' | <a class="post-edit-link" href="' . home_url('/') . '?page_id='.cmp_get_option('edit_page_id') . '&post_id='.$post_id . '" title="'.__('Frontend Edit','wpdx').'">'.__('Frontend Edit','wpdx').'</a>';
                }
            }
        }
        return $link;
    }

    /*
     * Format error messages for output.
     */
    function format_error_msg($message, $type = '',  $source = ''){
        $html = '<p style="color:red"><em>';
        if(!$type)
            $type = __("Error", 'wpdx');
        $html .= "<strong>" . htmlspecialchars($type) . "</strong>: ";
        $html .= $message;
        $html .= '</em></p>';
        if($source){
            $html .= '<pre style="margin-left:5px; border-left:solid 1px red; padding-left:5px;"><code class="xhtml malformed">';
            $html .= htmlspecialchars($source);
            $html .= '</code></pre>';
        }
        return $html;
    }

    /*
     * Get current user info. If user is not logged in we check if guest posts are permitted and set variables accordingly.
     */
    function verify_user() {
        $cmp_userinfo = array ();

        if (is_user_logged_in()) {
            global $current_user;
            wp_get_current_user();
            $cmp_userinfo['cmp_user_id'] = $current_user->ID;
            $cmp_userinfo['cmp_user_login'] = $current_user->user_login;
            if ( current_user_can('publish_posts') )
                $cmp_userinfo['cmp_can_publish_posts'] = true;
            if ( current_user_can('manage_categories') )
                $cmp_userinfo['cmp_can_manage_categories'] = true;
                
            if ( current_user_can('contributor') ) {
                $contributor = get_role('contributor');
                $contributor->add_cap('upload_files');
                $contributor->add_cap('read');
                $contributor->add_cap('edit_posts');
                $contributor->add_cap('edit_published_pages');
                $contributor->add_cap('edit_others_pages');
                $cmp_userinfo['media_upload'] = true;
            }
            return $cmp_userinfo;

        } elseif ( (!is_user_logged_in()) && (cmp_get_option('allow_guest_posts')) ) {
            $user_query = get_userdata(cmp_get_option('guest_account'));
            $cmp_userinfo['cmp_user_id'] = $user_query->ID;
            $cmp_userinfo['cmp_user_login'] = $user_query->user_login;
            
            // We give guests rights as a subscriber. Very limited, no media uploads.
            $cmp_userinfo['cmp_can_manage_categories'] = false;
            $cmp_userinfo['cmp_can_publish_posts'] = true;
            $cmp_userinfo['publish_status'] = 'pending';
            $cmp_userinfo['media_upload'] = false;

            return $cmp_userinfo;
        }
        return false;
    } // end verify_user()

    function cmp_check_user_role( $role, $user_id = null ) {
     
        if ( is_numeric( $user_id ) )
            $user = get_userdata( $user_id );
        else
            $user = wp_get_current_user();
     
        if ( empty( $user ) )
            return false;
        return in_array( $role, (array) $user->roles );
    }

    function save_cmp_auto_draft( $error_msg = false ) {

        global $cmp_post_id;
    
        if (!function_exists('get_default_post_to_edit')){
            require_once(ABSPATH . 'wp-admin/includes/post.php');
        }
    
        /* Check if a new auto-draft (= no new post_ID) is needed or if the old can be used */
        $last_post_id = (int) get_user_option( 'dashboard_quick_press_last_post_id' ); // Get the last post_ID
        if ( $last_post_id ) {
            $post = get_post( $last_post_id );
            if ( empty( $post ) || $post->post_status != 'auto-draft' ) { // auto-draft doesn't exists anymore
                $post = get_default_post_to_edit( 'post', true );
                update_user_option( get_current_user_id(), 'dashboard_quick_press_last_post_id', (int) $post->ID ); // Save post_ID
            } else {
                $post->post_title = ''; // Remove the auto draft title
            }
        } else {
            $post = get_default_post_to_edit( 'post' , true);
            $user_id = get_current_user_id();
            // Don't create an option if this is a super admin who does not belong to this site.
            if ( ! ( is_super_admin( $user_id ) && ! in_array( get_current_blog_id(), array_keys( get_blogs_of_user( $user_id ) ) ) ) )
                update_user_option( $user_id, 'dashboard_quick_press_last_post_id', (int) $post->ID ); // Save post_ID
        }
    
        $cmp_post_id = (int) $post->ID;
    
        // Getting the right post-id for media attachments
        $this->cmp_insert_media_fix( $cmp_post_id );
    
    }

    /*
     * Registers the shortcode that has a required @name param indicating the function which returns the HTML code for the shortcode.
     *
     * Shortcode: [cmp-site-post] With parameters: [cmp-site-post success_url="url" success_page_id="id"]
     * Parameters:
     *  success_url: URL of the page to redirect to after the post.
     *  success_page_id: ID of the page to redirect to after the post. Overwrites success_url if set.
     */
    function frontend_post_shortcode($atts, $content = null){

        global $shortcode_cache, $post, $cmp_post_id;
        
        extract(shortcode_atts(array(
            'success_url' => '',
            'success_page_id' => 0,
            'called_from_widget' => '0',
        ), $atts));
        $form_name = 'frontend_post_form';

        // Check for user logged in or guest posts permitted.
        if(!$user_verified = $this->verify_user())
            return $this->format_error_msg(__("Please login or register to use this function.", 'wpdx'),__("Notice", 'wpdx'));

        do_action ('save_cmp_auto_draft');
            
        // success_page_id overwrites success_url.
        if($success_page_id)
            $success_url = get_permalink($success_page_id);

        // Shortcode 'success_url' attribute. This has priority over redirect set in admin panel.
        if(!$success_url) {
            $success_url = cmp_get_option('success_url');
            if (empty($success_url)) $success_url = home_url() . "/";
        }

        // Call the function and grab the results (if nothing, output a comment noting that it was empty).
        // This one calls the form presented to the user.
        return call_user_func_array(array($this, $form_name), array($atts, $content, $user_verified, $cmp_post_id, $called_from_widget));

    }

    function process_frontend_post_form() {
        if( isset($_POST) ){
            
            if ( !empty ($_POST["cmp-our-id"])) $cmp_post_id = $_POST["cmp-our-id"];
    
                // Create post object with defaults
                $my_post = array(
                    'ID' => $cmp_post_id,
                    'post_title' => '',
                    'post_status' => 'publish',
                    'post_author' => '',
                    'post_category' => '',
                    'comment_status' => 'open',
                    'ping_status' => 'open',
                    'post_content' => '',
                    'post_excerpt' => '',
                    'post_type' => 'post',
                    'tags_input' => '',
                    'to_ping' =>  ''
                );
    
                //Fill our $my_post array
                $my_post['post_title'] = wp_strip_all_tags($_POST['cmpuser_frontend_post_title']);

                if( array_key_exists('cmpufpcontent', $_POST)) {
                    $my_post['post_content'] = $_POST['cmpufpcontent'];
                }
                if( array_key_exists('cmpuser_frontend_post_excerpt', $_POST)) {
                    $my_post['post_excerpt'] = wp_strip_all_tags($_POST['cmpuser_frontend_post_excerpt']);
                }
                if( array_key_exists('cmpuser_frontend_post_select_category', $_POST)) {
                    $ourCategory =  array($_POST['cmpuser_frontend_post_select_category']);
                }
                if( array_key_exists('cmpuser_frontend_post_checklist_category', $_POST)) {
                    $ourCategory =  $_POST['cmpuser_frontend_post_checklist_category'];
                }
                // if( array_key_exists('cmpuser_frontend_post_new_category', $_POST)) {
                //     if (!empty( $_POST['cmpuser_frontend_post_new_category']) ) {
                //         require_once(WP_PLUGIN_DIR . '/../../wp-admin/includes/taxonomy.php');
                //         if ($newCatId = wp_create_category(wp_strip_all_tags($_POST['cmpuser_frontend_post_new_category']))) {
                //             $ourCategory =  array($newCatId);
                //         } else {
                //             throw new Exception(__('Unable to create new category. Please try again or select an existing category.', 'wpdx'));
                //         }
                //     }
                // }
                
                if ( ! is_user_logged_in() && ! cmp_get_option('guest_cat_select') ) {
                    $ourCategory = array( cmp_get_option('guest_cat') );
                }
                
                $my_post['post_category'] = $ourCategory;

                if ( !empty ($_POST["cmp-our-author"])) {
                    $my_post['post_author'] =  $_POST["cmp-our-author"];
                } else {
                    $my_post['post_author'] = $user_verified['cmp_user_id'];
                }
    
                if( array_key_exists('cmpuser_frontend_post_tags', $_POST)) {
                    $my_post['tags_input'] = wp_strip_all_tags($_POST['cmpuser_frontend_post_tags']);
                }
    
                if( cmp_get_option('publish_status')) {
                    $my_post['post_status'] = cmp_get_option('publish_status');
                }
                if( array_key_exists('cmp-priv-publish-status', $_POST)) {
                    $my_post['post_status'] = wp_strip_all_tags($_POST['cmp-priv-publish-status']);
                }

                // Insert the post into the database
                $post_success = wp_update_post( $my_post );

                if($post_success === false) {
                    $result = "error";
                }
                else {
                    $result = "success";
                    //if ( 'publish' == $my_post['post_status'] ) do_action('publish_post');
                    if (isset($_POST['cmp-post-format'])) {
                        set_post_format( $post_success, wp_strip_all_tags($_POST['cmp-post-format']));
                    } else {
                        set_post_format( $post_success, wp_strip_all_tags(cmp_get_option('post_format_default')));
                    }
                }               

                if( array_key_exists('cmpuser_frontend_post_guest_name', $_POST)) {
                    add_post_meta( $post_success, 'guest_name', wp_strip_all_tags($_POST['cmpuser_frontend_post_guest_name']), true ) || update_post_meta( $post_success, 'guest_name', wp_strip_all_tags($_POST['cmpuser_frontend_post_guest_name']) );
                }
                if( array_key_exists('cmpuser_frontend_post_guest_email', $_POST)) {
                    add_post_meta( $post_success, 'guest_email', wp_strip_all_tags($_POST['cmpuser_frontend_post_guest_email']), true ) || update_post_meta( $post_success, 'guest_name', wp_strip_all_tags($_POST['cmpuser_frontend_post_guest_name']) );
                }
                
                if(apply_filters('form_abort_on_failure', true, $form_name))
                    $success = $post_success;
                if($success){
                    if(cmp_get_option('new_post_mail')) {
                        $this->cmp_sendmail($post_success, wp_strip_all_tags($_POST['cmpuser_frontend_post_title']));
                    }
                    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        echo $result;
                    } else {
                        setcookie('form_ok', 1,  time() + 10, '/');
                        header("Location: ".$_SERVER["HTTP_REFERER"]);
                        die();
                    }
                }
                else {
                    throw new Exception( cmp_get_option('post_failure') ? cmp_get_option('post_failure') : __('We were unable to accept your post at this time. Please try again. If the problem persists tell the site owner.', 'wpdx'));
                }
        } // isset($_POST)
        die();
    } //function process_frontend_post_form
    
    /**
     * Notify admin about new post via email
     */
    function cmp_sendmail ($post_id, $post_title) {
        $blogname = get_option('blogname');
        $email = get_option('admin_email');
        $headers = "MIME-Version: 1.0\r\n" . "From: ".$blogname." "."<".$email.">\n" . "Content-Type: text/HTML; charset=\"" . get_option('blog_charset') . "\"\r\n";
        $content = '<p>'.__('New post submitted from frontend to', 'wpdx').' '.$blogname.'.'.'<br/>' .__('To view the entry click here:', 'wpdx') . ' '.'<a href="'.get_permalink($post_id).'"><strong>'.$post_title.'</strong></a></p>';
        wp_mail($email, __('New frontend post to', 'wpdx') . ' ' . $blogname . ': ' . $post_title, $content, $headers);
    }
    
    /**
     * Print the post form at the front end
     */
    function frontend_post_form($attrs, $content = null, $verified_user, $cmp_post_id, $called_from_widget){
        ob_start();
        global $current_user; //Global WordPress variable that stores what wp_get_current_user() returns.
        wp_get_current_user();

        // Render the form html
        
        include_once (TEMPLATEPATH . '/cmpuser/templates/frontend-post-form.php');

        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
    }

    /**
     * Handle's user dashboard functionality
     *
     * Insert shortcode [cmpuser-post-list] in a page to
     * show the user posts
     *
     * @since 0.1
     */
    function post_list_shortcode( $atts ) {

        extract( shortcode_atts( array('post_type' => 'post'), $atts ) );

        ob_start();

        if ( is_user_logged_in() ) {
            $this->post_listing( $post_type );
        } else {
            $message = '<div class="cmpuser-message">' . sprintf( __( "This page is restricted. Please %s to view this page.", 'wpdx' ), wp_loginout( get_permalink(), false ) ) . '</div>';
                //wp_login_form();
            echo $message;
        }

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * List's all the posts by the user
     *
     * @global object $wpdb
     * @global object $userdata
     */
    function post_listing( $post_type ) {
        global $post;

        $pagenum = isset( $_GET['pagenum'] ) ? intval( $_GET['pagenum'] ) : 1;

        //delete post
        if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == "del" ) {
            $this->delete_post();
        }

        //show delete success message
        if ( isset( $_GET['msg'] ) && $_GET['msg'] == 'deleted' ) {
            echo '<div class="success">' . __( 'Post Deleted', 'wpdx' ) . '</div>';
        }

        $posts_per_page = cmp_get_option( 'user_posts_per_page' )?cmp_get_option( 'user_posts_per_page' ) : 10;

        $args = array(
            'author' => get_current_user_id(),
            'post_status' => array('draft', 'future', 'pending', 'publish', 'private'),
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'paged' => $pagenum
        );

        $original_post = $post;
        $dashboard_query = new WP_Query( apply_filters( 'cmpuser_dashboard_query', $args ) );
        $post_type_obj = get_post_type_object( $post_type );

        include_once (TEMPLATEPATH . '/cmpuser/templates/frontend-post-list.php');

        // wpuf_load_template( 'dashboard.php', array(
        //     'post_type' => $post_type,
        //     'userdata' => wp_get_current_user(),
        //     'dashboard_query' => $dashboard_query,
        //     'post_type_obj' => $post_type_obj,
        //     'post' => $post,
        //     'pagenum' => $pagenum
        // ) );

        wp_reset_postdata();

    }

    /**
     * Delete a post
     *
     * Only post author and editors has the capability to delete a post
     */
    function delete_post() {
        global $userdata;

        $nonce = $_REQUEST['_wpnonce'];
        if ( !wp_verify_nonce( $nonce, 'cmpufp_del' ) ) {
            die( "Security check" );
        }

        //check, if the requested user is the post author
        $maybe_delete = get_post( $_REQUEST['post_id'] );

        if ( ($maybe_delete->post_author == $userdata->ID) || current_user_can( 'delete_others_pages' ) ) {
            wp_delete_post( $_REQUEST['post_id'] );

            //redirect
            $redirect = add_query_arg( array('msg' => 'deleted'), get_permalink() );
            wp_redirect( $redirect );
        } else {
            echo '<div class="error">' . __( 'You are not the post author. Cheeting huh!', 'wpdx' ) . '</div>';
        }
    }

    /**
    * Send debug code to the Javascript console
    */
    function dtc($data) {
        if(is_array($data) || is_object($data))
        {
            echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
        } else {
            echo("<script>console.log('PHP: ".$data."');</script>");
        }
    }

    } // end class
} // end if (!class_exists)

/**
 * [cmpuser_auto_set_edit_page_id description]
 * @return [type] [description]
 */
function cmpuser_auto_set_edit_page_id(){
    if(get_option( 'cmp_options' )){
        $custom_post_edit = cmp_get_page_id_by_shortcode('cmpuser-frontend-post');

        $theme_options = get_option( 'cmp_options' );

        if((!isset($theme_options['edit_page_id']) || $theme_options['edit_page_id'] == '' ) && $custom_post_edit ){
            $theme_options['edit_page_id'] = $custom_post_edit;
        }
        update_option( 'cmp_options' , $theme_options );
    }
}
add_action('admin_init','cmpuser_auto_set_edit_page_id');