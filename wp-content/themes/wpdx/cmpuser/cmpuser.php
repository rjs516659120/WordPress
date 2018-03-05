<?php
/**
 * Project: Cmp User center
 * @Author: Changmeng Hu
 * @Date:   2016-07-10 21:39:16
 * @Last Modified time: 2017-11-18 10:22:15
 * Shortcodes: [cmpuser-login] [cmpuser-register] [cmpuser-edit-profile] [cmpuser-reset-password] [cmpuser-frontend-post] [cmpuser-post-list]
 */

/**
 * Custom page for login , register and reset password
 *
 * @since 1.0
 */
if( cmp_get_option('login_url') && !defined('CMP_REMOVE_LOGIN_URL')){
    add_filter( 'login_url', 'cmpuser_login_page', 10, 3 );
    function cmpuser_login_page( $login_url, $redirect, $force_reauth ) {
        $login_page = esc_url(cmp_get_option('login_url'));
        $login_url = add_query_arg( 'redirect_to', $redirect, $login_page );
        return $login_url;
    }

    add_action( 'init', 'cmpuser_wp_login_page_redirect') ;
    function cmpuser_wp_login_page_redirect() {
        global $pagenow;

        if ( !is_admin() && $pagenow == 'wp-login.php' && !(isset( $_GET['action'] ) && $_GET['action'] == 'register') && !(isset( $_GET['action'] ) && $_GET['action'] == 'logout') && !(isset( $_GET['action'] ) && $_GET['action'] == 'login') && !(isset( $_GET['action'] ) && $_GET['action'] == 'postpass') ){

            $login_page = esc_url(cmp_get_option('login_url'));
            wp_redirect( $login_page );
            exit;
        }
    }

}

if(cmp_get_option('register_url')){
    add_filter( 'register_url', 'cmpuser_register_page' );
    function cmpuser_register_page( $register_url ) {
        return esc_url(cmp_get_option('register_url'));
    }
}

if(cmp_get_option('password_url')){
    add_filter( 'lostpassword_url', 'cmpuser_lost_password_page', 10, 2 );
    function cmpuser_lost_password_page( $lostpassword_url, $redirect ) {
        if($redirect){
            $lostpassword_url = esc_url(cmp_get_option('password_url')).'?redirect_to=' . $redirect;
        }else{
            $lostpassword_url = esc_url(cmp_get_option('password_url'));
        }
        return $lostpassword_url;
    }
}

/**
 * [cmpuser_auto_set_pages description]
 * @return [type] [description]
 */
function cmpuser_auto_set_pages(){
    if(get_option( 'cmp_options' ) && cmp_get_option('enable_cmpuser')){
        
        $custom_login = cmp_get_page_id_by_shortcode('cmpuser-login');
        $custom_register = cmp_get_page_id_by_shortcode('cmpuser-register');
        $custom_password = cmp_get_page_id_by_shortcode('cmpuser-reset-password');
        $custom_profile = cmp_get_page_id_by_shortcode('cmpuser-edit-profile');

        $theme_options = get_option( 'cmp_options' );

        if( (!isset($theme_options['password_url']) || $theme_options['password_url'] == '' ) && $custom_password ) $theme_options['password_url'] = get_permalink($custom_password);
        if((!isset($theme_options['register_url']) || $theme_options['register_url']== '' ) && $custom_register ) $theme_options['register_url'] = get_permalink($custom_register);
        if((!isset($theme_options['login_url']) || $theme_options['login_url'] == '' ) && $custom_login ) $theme_options['login_url'] = get_permalink($custom_login);
        if((!isset($theme_options['profile_url']) || $theme_options['profile_url'] == '' ) && $custom_profile ) $theme_options['profile_url'] = get_permalink($custom_profile);

        update_option( 'cmp_options' , $theme_options );
    }
}
add_action('cmp_panel_save_options','cmpuser_auto_set_pages');

/**
 * [cmpuser-login] shortcode
 *
 * @since 1.0
 */
function show_cmpuser_login_form($atts) {

    ob_start();
    
    if ( isset( $_GET['authentication'] ) ) {
        if ( $_GET['authentication'] == 'success' ){
            echo "<div class='cmpuser-notification success'><p>". __( 'You have successfully logged in!', 'wpdx' ) ."</p></div>";
        }elseif ( $_GET['authentication'] == 'no-log' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'The username or email can not be empty!', 'wpdx' ) ."</p></div>";
        }elseif ( $_GET['authentication'] == 'no-pwd' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'The password can not be empty!', 'wpdx' ) ."</p></div>";
        }elseif ( $_GET['authentication'] == 'wrong-email' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'A user could not be found with this email address. Please check and try again!', 'wpdx' ) ."</p></div>";
        }elseif ( $_GET['authentication'] == 'failed' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'The username or password is incorrect. Please check and try again!', 'wpdx' ) ."</p></div>";
        }elseif ( $_GET['authentication'] == 'logout' ){
            echo "<div class='cmpuser-notification success'><p>". __( 'You have successfully logged out!', 'wpdx' ) ."</p></div>";
        }elseif ( $_GET['authentication'] == 'failed-activation' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'Something went wrong while activating your account. Please contact the webmaster for help.', 'wpdx' ) ."</p></div>";
        }elseif ( $_GET['authentication'] == 'disabled' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'Your account is currently disabled. For more information, please contact the webmaster.', 'wpdx' ) ."</p></div>";
        }elseif ( $_GET['authentication'] == 'success-activation' ){
            echo "<div class='cmpuser-notification success'><p>". __( 'Congratulations, your account has been successfully activated.', 'wpdx' ) ."</p></div>";
        }
    }

    if ( is_user_logged_in() ) {
        // show user preview data
        require(TEMPLATEPATH . '/cmpuser/templates/login-preview.php' );

    } else {
        // show login form
        require(TEMPLATEPATH . '/cmpuser/templates/login-form.php' );
    }

    return ob_get_clean();

}

//
if(!function_exists('cmpuser_scripts')){
    include_once( TEMPLATEPATH . '/cmpuser/templates/new-profile-edit-form.php' );
}

/**
 * [cmpuser-register] shortcode
 *
 * @since 1.0
 */
function show_cmpuser_register_form($atts) {
    
    $param = shortcode_atts( array(
        'role' => false,
    ), $atts );

    ob_start();

    if ( isset( $_GET['created'] ) ) {
        if ( $_GET['created'] == 'success' ){
            echo "<div class='cmpuser-notification success'><p>". __( 'Congratulations, your account has been successfully created.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['created'] == 'sendfailed' ){
            echo "<div class='cmpuser-notification success'><p>". __( 'Congratulations, your account has been successfully created.', 'wpdx' )."</p></div>";
            echo"<div class='cmpuser-notification error'><p>". __( 'But the server can not send the verification email to you, please contact the webmaster to handle the mail sending problem and activate your account.', 'wpdx' )."</p></div>";
        }
        elseif ( $_GET['created'] == 'success-link' ){
            echo "<div class='cmpuser-notification success'><p>". __( 'Congratulations, your account has been successfully created, but you must to activate your account to login. <strong>Please check your mailbox to confirm your account</strong>.', 'wpdx' )."</p></div>";
        }
        // elseif ( $_GET['created'] == 'honeypot' ){
        //     echo "<div class='cmpuser-notification error'><p>". __( 'Are you kidding me? Please register your account in the normal way.', 'wpdx' ) ."</p></div>";
        // }
        elseif ( $_GET['created'] == 'created' ){
            echo "<div class='cmpuser-notification success'><p>". __( 'Congratulations, your account has been successfully created and activated.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['created'] == 'passcomplex' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'Your password must contain at least 1 uppercase, 1 lowercase letter and at least 1 number. Passwords should not contain the user\'s username, email, or first/last name.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['created'] == 'emptyusername' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'Username cannot be empty.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['created'] == 'hasusername' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'The username has been used, please try another.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['created'] == 'wrongnickname' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'Nickname cannot be empty.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['created'] == 'wrongname' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'First Name cannot be empty.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['created'] == 'wrongsurname' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'Last Name cannot be empty.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['created'] == 'emptypass' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'Passwords cannot be empty.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['created'] == 'notsamepass' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'The two passwords are not the same.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['created'] == 'emptymail' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'Email cannot be empty.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['created'] == 'wrongmail' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'It does not appear to be a valid email address, please check and try again.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['created'] == 'hasmail' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'The email address is already in use. Please use a different email address.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['created'] == 'wrongcaptcha' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'CAPTCHA is incorrect. Please try again.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['created'] == 'sendfailed' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'Something strange has ocurred while created the new user. Please contact the webmaster for help.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['created'] == 'failed' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'Something strange has ocurred while created the new user. Please contact the webmaster for help.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['created'] == 'terms' ){
            echo "<div class='cmpuser-notification error'><p>\"". cmp_get_option( 'terms_conditions_msg' ) . '" ' .__( 'must be checked', 'wpdx' ) . "</p></div>";
        }
    }
    if ( isset( $_GET['send'] ) ) {
        if ( $_GET['send'] == 'notifyfailed' ){
            echo "<div class='cmpuser-notification success'><p>". __( 'Congratulations, your account has been successfully created.', 'wpdx' )."</p></div>";
            echo"<div class='cmpuser-notification error'><p>". __( '<strong>But the server can not send the email, please contact the webmaster to handle the mail sending problem</strong>.', 'wpdx' )."</p></div>";
        }
    }

    if ( !is_user_logged_in() ) {
        require(TEMPLATEPATH . '/cmpuser/templates/register-form.php' );
    } else {
        echo "<div class='cmpuser-notification error'><p>". __( 'You are now logged in. It makes no sense to register a new user.', 'wpdx' ) ."</p></div>";
        require(TEMPLATEPATH . '/cmpuser/templates/login-preview.php' );

    }

    return ob_get_clean();

}

/**
 * [cmpuser-reset-password] shortcode
 *
 * @since 1.0
 */
function show_cmpuser_reset_password_form($atts) {

    ob_start();

    if ( isset( $_GET['sent'] ) ) {
        if ( $_GET['sent'] == 'success' ){
            echo "<div class='cmpuser-notification success'><p>". __( 'You will receive an email with the activation link, click the verification link to change your password.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['sent'] == 'sent' ){
            echo "<div class='cmpuser-notification success'><p>". __( 'You may receive an email with the activation link, click the verification link to change your password.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['sent'] == 'failed' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'An error has ocurred sending the email, please contact the site administrator for help.', 'wpdx' ) ."</p></div>";
        }
        elseif ( $_GET['sent'] == 'wronguser' ){
            echo "<div class='cmpuser-notification error'><p>". __( 'Username or email is not valid, please try again.', 'wpdx' ) ."</p></div>";
        }
    }

    if ( !is_user_logged_in() ) {
        if ( isset( $_GET['pass'] ) ) {
            $new_password = sanitize_text_field( $_GET['pass'] );
            $login_url = cmp_get_option( 'login_url');
            require(TEMPLATEPATH . '/cmpuser/templates/show-new-password.php' );
        } else{
            require(TEMPLATEPATH . '/cmpuser/templates/reset-password-form.php' );
        }
    } else {
        echo "<div class='cmpuser-notification error'><p>". __( 'You are now logged in. It makes no sense to reset your account', 'wpdx' ) ."</p></div>";
        require(TEMPLATEPATH . '/cmpuser/templates/login-preview.php' );

    }

    return ob_get_clean();

}

/**
 * Password complexity checker
 *
 * @since 1.2
 */
function is_password_complex($candidate) {

    if (!preg_match_all('$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$', $candidate)){
        return false;
    }
    return true;

    /* Explaining $\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$
    $ = beginning of string
    \S* = any set of characters
    (?=\S{8,}) = of at least length 8
    (?=\S*[a-z]) = containing at least one lowercase letter
    (?=\S*[A-Z]) = and at least one uppercase letter
    (?=\S*[\d]) = and at least one number
    (?=\S*[\W]) = and at least a special character (non-word characters)
    $ = end of the string */
}


/**
 * Custom code to be loaded before headers
 *
 * @since 1.0
 */
function cmpuser_load_before_headers() {
    global $wp_query; 
    if ( is_singular() ) { 
        $post = $wp_query->get_queried_object(); 
        // If contains any shortcode of our ones
        if (
            strpos($post->post_content, 'cmpuser-login' ) !== false
            || strpos($post->post_content, 'cmpuser-register' ) !== false
            || strpos($post->post_content, 'cmpuser-reset-password' ) !== false
            //|| strpos($post->post_content, 'cmpuser-profile-edit' ) !== false
            ) {

            // Sets the redirect url to the current page 
            $url = cmpuser_url_cleaner( wp_get_referer() );
            $creds = array();

            // LOGIN
            if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'login' && !empty( $_POST['_wpnonce'] ) ) {

                if ( isset( $_POST['_wpnonce'] ) ) {
                    wp_verify_nonce( $_POST['_wpnonce'], 'wpuf_login_action' );
                }

                $url = cmp_get_option( 'login_url');

                if ( is_email( $_POST['log'] ) ) {
                    $user = get_user_by( 'email', $_POST['log'] );

                    if ( isset( $user->user_login ) ) {
                        $creds['user_login'] = $user->user_login;
                    } else {
                        $url = esc_url( add_query_arg( 'authentication', 'wrong-email', $url ) );
                    }
                } else {
                    $creds['user_login'] = $_POST['log'];
                }

                $creds['user_password'] = $_POST['pwd'];
                $creds['remember'] = isset( $_POST['rememberme'] );
                $secure_cookie = is_ssl() ? true : false;
                $user = wp_signon( apply_filters( 'wpuf_login_credentials', $creds ), $secure_cookie );
                if ( empty( $_POST['log'] ) ) {
                    $url = esc_url( add_query_arg( 'authentication', 'no-log', $url ) );
                }elseif ( empty( $_POST['pwd'] ) ) {
                    $url = esc_url( add_query_arg( 'authentication', 'no-pwd', $url ) );
                }elseif (  is_email( $_POST['log'] ) ) {
                    $user = get_user_by( 'email', $_POST['log'] );
                    if ( !isset( $user->user_login ) ) {
                        $url = esc_url( add_query_arg( 'authentication', 'wrong-email', $url ) );
                    }
                }elseif ( is_wp_error( $user ) ){
                    $url = esc_url( add_query_arg( 'authentication', 'failed', $url ) );
                } else {
                    // if the user is disabled
                    if( empty($user->roles) ) {
                        wp_logout();
                        $url = esc_url( add_query_arg( 'authentication', 'disabled', $url ) );
                    } else {
                        if(cmp_get_option('login_redirect_url')){
                            $url = esc_url( cmp_get_option('login_redirect_url') );
                        } elseif ( !empty( $_POST['redirect_to'] ) ) {
                            $url = esc_url( $_POST['redirect_to'] );
                        } elseif ( wp_get_referer() ) {
                            $url = esc_url( wp_get_referer() );
                        } else {
                            $url = home_url( '/' );
                        }
                    }
                }

                wp_safe_redirect( $url );

            // LOGOUT
            } elseif ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'logout' ) {
                wp_logout();
                $url = esc_url( add_query_arg( 'authentication', 'logout', $url ) );
                
                wp_safe_redirect( $url );

            // EDIT profile
            } elseif ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' ) {
                $url = esc_url( add_query_arg( 'updated', 'success', $url ) );

                $current_user = wp_get_current_user();
                $userdata = array( 'ID' => $current_user->ID );

                $nickname = isset( $_POST['nickname'] ) ? $_POST['nickname'] : '';
                $first_name = isset( $_POST['first_name'] ) ? $_POST['first_name'] : '';
                $last_name = isset( $_POST['last_name'] ) ? $_POST['last_name'] : '';
                $userdata['nickname'] = $nickname;
                $userdata['display_name'] = $nickname;
                $userdata['first_name'] = $first_name;
                $userdata['last_name'] = $last_name;
            
                $email = isset( $_POST['email'] ) ? $_POST['email'] : '';
                if ( ! $email || empty ( $email ) ) {
                    $url = esc_url( add_query_arg( 'updated', 'wrongmail', $url ) );
                } elseif ( ! is_email( $email ) ) {
                    $url = esc_url( add_query_arg( 'updated', 'wrongmail', $url ) );
                } elseif ( ( $email != $current_user->user_email ) && email_exists( $email ) ) {
                    $url = esc_url( add_query_arg( 'updated', 'wrongmail', $url ) );
                } else {
                    $userdata['user_email'] = $email;
                }

                // check if password complexity is checked
                $enable_passcomplex = cmp_get_option( 'password_complexity' );

                // password checker
                if ( isset( $_POST['pass1'] ) && ! empty( $_POST['pass1'] ) ) {
                    if ( ! isset( $_POST['pass2'] ) || ( isset( $_POST['pass2'] ) && $_POST['pass2'] != $_POST['pass1'] ) ) {
                        $url = esc_url( add_query_arg( 'updated', 'wrongpass', $url ) );
                    }
                    else {
                        if( $enable_passcomplex && !is_password_complex($_POST['pass1']) ){
                            $url = esc_url( add_query_arg( 'updated', 'passcomplex', $url ) );
                        }
                        else{
                            $userdata['user_pass'] = $_POST['pass1'];
                        }
                    }
                    
                }

                $user_id = wp_update_user( $userdata );
                if ( is_wp_error( $user_id ) ) {
                    $url = esc_url( add_query_arg( 'updated', 'failed', $url ) );
                }

                wp_safe_redirect( $url );

            // REGISTER a new user
            } elseif ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'register' ) {

                // check if captcha is checked
                $enable_captcha = cmp_get_option( 'antispam' );
                // check if standby role is checked
                $create_standby_role = cmp_get_option( 'standby' );
                // check if password complexity is checked
                $enable_passcomplex = cmp_get_option( 'password_complexity' );
                // check if custom role is selected and get the roles choosen
                $create_customrole = cmp_get_option( 'choose_role' );
                $newuserroles = cmp_get_option( 'new_user_roles' );
                // check if the admin should receive an email
                $emailnotificationadmin = cmp_get_option( 'email_notification_admin' );
                // check if the user should receive an email
                $emailnotificationuser = cmp_get_option( 'email_notification_user' );
                $emailnotificationcontent = cmp_get_option( 'email_notification_content' );
                // check if termsconditions is checked
                $termsconditions = cmp_get_option( 'terms_conditions' );
                // check if ask once for password is checked
                $singlepassword = cmp_get_option('single_password');
                // check if automatic login in on registration is checked
                $automaticlogin = cmp_get_option('automatic_login');
                // check if nameandsurname is checked
                $nameandsurname = cmp_get_option('first_last_name');
                // check if emailvalidation is checked, cannot be used with $automaticlogin
                $emailvalidation = cmp_get_option('email_validation');

                $successful_registration = false;

                $url = esc_url( add_query_arg( 'created', 'success', $url ) );

                //if nameandsurname is checked then get them
                if ($nameandsurname) {
                    $first_name = isset( $_POST['first_name'] ) ? $_POST['first_name'] : '';
                    $last_name = isset( $_POST['last_name'] ) ? $_POST['last_name'] : '';
                }
                $nickname = isset( $_POST['nickname'] ) ? $_POST['nickname'] : '';

                $username = isset( $_POST['username'] ) ? $_POST['username'] : '';

                $email = isset( $_POST['email'] ) ? $_POST['email'] : '';
                $pass1 = isset( $_POST['pass1'] ) ? $_POST['pass1'] : '';
                //if single password is checked then use pass1 as pass2
                if ($singlepassword){
                    $pass2 = isset( $_POST['pass1'] ) ? $_POST['pass1'] : '';
                }else{
                    $pass2 = isset( $_POST['pass2'] ) ? $_POST['pass2'] : '';
                }
                $website = isset( $_POST['website'] ) ? $_POST['website'] : '';
                $captcha = isset( $_POST['captcha'] ) ? $_POST['captcha'] : '';
                $captcha_session = isset( $_SESSION['cmpuser-captcha'] ) ? $_SESSION['cmpuser-captcha'] : '';
                $role = isset( $_POST['role'] ) ? $_POST['role'] : '';
                $terms = isset( $_POST['termsconditions'] ) && $_POST['termsconditions'] == 'on' ? true : false;
                
                // terms and conditions
                if( $termsconditions && !$terms ){
                    $url = esc_url( add_query_arg( 'created', 'terms', $url ) );
                }
                // password complexity checker
                elseif( $enable_passcomplex && !is_password_complex($pass1) ){
                    $url = esc_url( add_query_arg( 'created', 'passcomplex', $url ) );
                }
                // check if the selected role is contained in the roles selected in CL
                elseif ( $create_customrole && !in_array($role, $newuserroles)){
                    $url = esc_url( add_query_arg( 'created', 'failed', $url ) );
                }
                // captcha enabled
                elseif( $enable_captcha && $captcha != $captcha_session ){
                    $url = esc_url( add_query_arg( 'created', 'wrongcaptcha', $url ) );
                }
                // honeypot detection
                // elseif( $website != '1' ){
                //     $url = esc_url( add_query_arg( 'created', 'honeypot', $url ) );
                // }
                elseif( $nickname && $nickname == '' ){
                    $url = esc_url( add_query_arg( 'created', 'wrongnickname', $url ) );
                }
                // if nameandsurname then check them
                elseif( $nameandsurname && $first_name == '' ){
                    $url = esc_url( add_query_arg( 'created', 'wrongname', $url ) );
                }
                elseif( $nameandsurname && $last_name == '' ){
                    $url = esc_url( add_query_arg( 'created', 'wrongsurname', $url ) );
                }
                // check defaults
                elseif( $username == '' ){
                    $url = esc_url( add_query_arg( 'created', 'emptyusername', $url ) );
                }
                elseif( username_exists( $username ) ){
                    $url = esc_url( add_query_arg( 'created', 'hasusername', $url ) );
                }
                elseif( $email == '' ){
                    $url = esc_url( add_query_arg( 'created', 'emptymail', $url ) );
                }
                elseif( !is_email( $email ) ){
                    $url = esc_url( add_query_arg( 'created', 'wrongmail', $url ) );
                }
                elseif( email_exists( $email ) ){
                    $url = esc_url( add_query_arg( 'created', 'hasmail', $url ) );
                }
                elseif ( $pass1 == '' || (!$singlepassword && $pass2 == '')){
                    $url = esc_url( add_query_arg( 'created', 'emptypass', $url ) );
                }
                elseif ( $pass1 != $pass2){
                    $url = esc_url( add_query_arg( 'created', 'notsamepass', $url ) );
                }
                else {
                    $user_id = wp_create_user( $username, $pass1, $email );
                    if ( is_wp_error( $user_id ) ){
                        $url = esc_url( add_query_arg( 'created', 'failed', $url ) );
                    }
                    else {
                        $successful_registration = true;
                        $user = new WP_User( $user_id );

                        // email validation
                        if( $emailvalidation ) {
                            $user->set_role( '' );
                            // Send auth email
                            $url_msg = get_permalink();
                            $url_msg = esc_url( add_query_arg( 'activate', $user->ID, $url_msg ) );
                            $url_msg = wp_nonce_url( $url_msg, $user->ID );

                            $blog_title = get_bloginfo();
                            $message = sprintf( __( "Use the following link to activate your account: <a href='%s'>activate your account</a>.<br/><br/>%s<br/>", 'wpdx' ), $url_msg, $blog_title );

                            $subject = "[$blog_title] " . __( 'Activate your account', 'wpdx' );
                            add_filter( 'wp_mail_content_type', 'cmpuser_set_html_content_type' );
                            if( !wp_mail( $email, $subject , $message ) ){
                                $url = esc_url( add_query_arg( 'created', 'sendfailed', $url ) );
                            }
                            remove_filter( 'wp_mail_content_type', 'cmpuser_set_html_content_type' );

                            $url = esc_url( add_query_arg( 'created', 'success-link', $url ) );
                        }
                        elseif( $create_customrole ){
                            $user->set_role( $role );
                            // notify the user registration
                            do_action( 'user_register', $user_id );
                        }
                        elseif ( $create_standby_role ){
                            $user->set_role( 'standby' );
                        }

                        $userdata = array( 'ID' => $user_id );
                        $userdata['nickname'] = $nickname;
                        $userdata['display_name'] = $nickname;
                        
                        wp_update_user( $userdata );

                        if( $nameandsurname ) {
                            $userdata = array( 'ID' => $user_id );
                            $userdata['first_name'] = $first_name;
                            $userdata['last_name'] = $last_name;
                            wp_update_user( $userdata );
                        }

                        if( $emailnotificationadmin ) {
                            $adminemail = get_bloginfo( 'admin_email' );
                            $blog_title = get_bloginfo();

                            if ( $create_standby_role && !$emailvalidation ){
                                $message = sprintf( __( "New user registered: %s <br/><br/>Please change the role from 'Stand By' to 'Subscriber' or higher to allow full site access", 'wpdx' ), $username );
                            }
                            else{
                                $message = sprintf( __( "New user registered: %s <br/>", 'wpdx' ), $username );
                            }
                            
                            $subject = "[$blog_title] " . __( 'New user registered', 'wpdx' );
                            add_filter( 'wp_mail_content_type', 'cmpuser_set_html_content_type' );
                            if( !wp_mail( $adminemail, $subject, $message ) ){
                                $url = esc_url( add_query_arg( 'sent', 'notifyfailed', $url ) );
                            }
                            remove_filter( 'wp_mail_content_type', 'cmpuser_set_html_content_type' );
                        }

                        if( $emailnotificationuser ) {
                            if(!$emailnotificationcontent || $emailnotificationcontent == ''){
                                $emailnotificationcontent = sprintf( __( "Thanks for registering on our website, the following is your account information:<br/><br/>
                                    Username: %s <br/><br/>
                                    Password: %s <br/><br/>
                                    Email: %s <br/><br/>
                                    You can login from: %s <br/><br/>
                                    ", 'wpdx' ), $username, $pass1, $email, cmp_get_option('login_url') );
                            }else{
                                $emailnotificationcontent = str_replace("{username}", $username, $emailnotificationcontent);
                                $emailnotificationcontent = str_replace("{password}", $pass1, $emailnotificationcontent);
                                $emailnotificationcontent = str_replace("{email}", $email, $emailnotificationcontent);
                            }
                            
                            add_filter( 'wp_mail_content_type', 'cmpuser_set_html_content_type' );
                            if( !wp_mail( $email, $subject , $emailnotificationcontent ) ){
                                $url = esc_url( add_query_arg( 'sent', 'notifyfailed', $url ) );
                            }
                            remove_filter( 'wp_mail_content_type', 'cmpuser_set_html_content_type' );
                        }
                    }
                }

                // if automatic login is enabled then log the user in and redirect them, checking if it was successful or not,
                //  is not compatible with email validation feature. This had no meaning!
                if($automaticlogin && $successful_registration && !$emailvalidation) {
                    if(cmp_get_option('register_redirect_url')){
                        $url = esc_url( cmp_get_option('register_redirect_url') );
                    } elseif ( wp_get_referer() ) {
                        $url = esc_url( wp_get_referer() );
                    } else {
                        $url = home_url( '/' );
                    }
                    wp_signon(array('user_login' => $username, 'user_password' => $pass1), false);
                }                   
                    
                wp_safe_redirect( $url );

            // When a user click the activation link goes here to activate his/her account
            } elseif ( isset( $_REQUEST['activate'] ) ) {
                
                $user_id = $_REQUEST['activate'];

                $retrieved_nonce = $_REQUEST['_wpnonce'];
                if ( !wp_verify_nonce($retrieved_nonce, $user_id ) ){
                    die( 'Failed security check, expired Activation Link due to duplication or date.' );
                }

                $url = cmp_get_option( 'login_url');
                
                $user = get_user_by( 'id', $user_id );
                
                if ( !$user ) {
                    $url = esc_url( add_query_arg( 'authentication', 'failed-activation', $url ) );
                } else {
                    $user->set_role( get_option('default_role') );
                    $url = esc_url( add_query_arg( 'authentication', 'success-activation', $url ) );
                }
                
                wp_safe_redirect( $url );

            // Reset a password by sending an email with the activation link
            } elseif ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'reset_password' ) {
                $url = esc_url( add_query_arg( 'sent', 'success', $url ) );

                $username = isset( $_POST['username'] ) ? $_POST['username'] : '';
                $website = isset( $_POST['website'] ) ? $_POST['website'] : '';

                // Since 1.1 (get username from email if so)
                if ( is_email( $username ) ) {
                    $userFromMail = get_user_by( 'email', $username );
                    if ( $userFromMail == false ){
                        $username = '';
                    }
                    else{
                        $username = $userFromMail->user_login;
                    }
                }

                // honeypot detection
                // if( $website != '1' ){
                //     $url = esc_url( add_query_arg( 'sent', 'sent', $url ) );
                // }
                if( $username == '' || !username_exists( $username ) ){
                    $url = esc_url( add_query_arg( 'sent', 'wronguser', $url ) );
                }
                else {
                    $user = get_user_by( 'login', $username );

                    $url_msg = get_permalink();
                    $url_msg = esc_url( add_query_arg( 'reset_password', $user->ID, $url_msg ) );
                    $url_msg = wp_nonce_url( $url_msg, $user->ID );

                    $email = $user->user_email;
                    $blog_title = get_bloginfo();
                    $message = sprintf( __( "Use the following link to reset your password: <a href='%s'>reset your password</a> <br/><br/>%s<br/>", 'wpdx' ), $url_msg, $blog_title );

                    $subject = "[$blog_title] " . __( 'Reset your password', 'wpdx' );
                    add_filter( 'wp_mail_content_type', 'cmpuser_set_html_content_type' );
                    if( !wp_mail( $email, $subject , $message ) ){
                        $url = esc_url( add_query_arg( 'sent', 'failed', $url ) );
                    }
                    remove_filter( 'wp_mail_content_type', 'cmpuser_set_html_content_type' );

                }

                wp_safe_redirect( $url );

            // When a user click the activation link goes here to Reset his/her password
            } elseif ( isset( $_REQUEST['reset_password'] ) ) {
                

                $user_id = $_REQUEST['reset_password'];

                $retrieved_nonce = $_REQUEST['_wpnonce'];
                if ( !wp_verify_nonce($retrieved_nonce, $user_id ) ){
                    die( 'Failed security check, expired Activation Link due to duplication or date.' );
                }

                $profile_url = cmp_get_option( 'profile_url');
                $profile_url = esc_url( add_query_arg( 'reset', 'password', $profile_url ) ).'#password';
                
                // If edit profile page exists the user will be redirected there
                if( $profile_url) {
                    wp_clear_auth_cookie();
                    wp_set_current_user ( $user_id );
                    wp_set_auth_cookie  ( $user_id );
                    $url = $profile_url;

                // If not, a new password will be generated and notified
                } else {
                    $url = cmp_get_option( 'password_url');
                    // check if password complexity is checked
                    $enable_passcomplex = cmp_get_option( 'password_complexity' );
                    
                    if($enable_passcomplex){
                        $new_password = wp_generate_password(12, true);
                    }
                    else{
                        $new_password = wp_generate_password(8, false);
                    }

                    $user_id = wp_update_user( array( 'ID' => $user_id, 'user_pass' => $new_password ) );

                    if ( is_wp_error( $user_id ) ) {
                        $url = esc_url( add_query_arg( 'sent', 'wronguser', $url ) );
                    } else {
                        $url = esc_url( add_query_arg( 'pass', $new_password, $url ) );
                    }
                }

                wp_safe_redirect( $url );
            }
        } 
    }
}
add_action('template_redirect', 'cmpuser_load_before_headers');

/**
 * Cleans an url
 *
 * @since 1.0
 * @param url to be cleaned
 */
function cmpuser_url_cleaner( $url ) {
    $query_args = array(
        'authentication',
        'updated',
        'created',
        'sent',
        'reset_password'
    );
    return esc_url( remove_query_arg( $query_args, $url ) );
}

/**
 * Set email format to html
 *
 * @since 1.0
 */
function cmpuser_set_html_content_type()
{
    return 'text/html';
}

/**
 * It will only enable the dashboard for users with administrative privileges
 * Please note that you can only log in through wp-login.php and this plugin
 *
 * @since 0.9
 */
function cmpuser_block_dashboard_access() {
    $block_dashboard = cmp_get_option( 'block_dashboard' );

    if ( $block_dashboard && !current_user_can( 'manage_options' ) && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) ) {
        wp_redirect( home_url() );
        exit;
    }
}
add_action( 'admin_init', 'cmpuser_block_dashboard_access', 1 );

/**
 * session_start();
 *
 * @since 0.9
 */

function cmpuser_register_session(){
    if( !session_id() ){
        session_start();
    }
}
add_action('init','cmpuser_register_session');

/**
 * Add a role without any capability
 *
 * @since 1.0
 */
function cmpuser_add_roles() {

    $create_standby_role = cmp_get_option( 'standby' );
    $role = get_role( 'standby' );

    if ( $create_standby_role ) {
        // create if neccesary
        if ( !$role ){
            $role = add_role('standby', 'StandBy');
        }
        // and remove capabilities
        $role->remove_cap( 'read' );
    } else {
        // remove if exists
        if ( $role ){
            remove_role( 'standby' );
        }
    }
}
add_action( 'admin_init', 'cmpuser_add_roles');

/**
* This functions redirect after register
*
* @since 3.4
*/
// add_filter( 'registration_redirect', 'cmpuser_redirect_after_register' );
// function cmpuser_redirect_after_register( $registration_redirect ) {
//     if(!cmp_get_option('register_redirect_url')) return;
//     $register_redirect_url = esc_url(cmp_get_option('register_redirect_url'));
//     return $register_redirect_url;
// }

/**
* This functions redirect after login
*
* @since 3.4
*/
function cmpuser_redirect_after_login(){
    if(!cmp_get_option('login_redirect_url')) return;
    $login_redirect_url = esc_url(cmp_get_option('login_redirect_url'));
    wp_redirect( $login_redirect_url);
    exit();
}
// check if login redirect is enabled
add_action('wp_login','cmpuser_redirect_after_login');

/**
* This functions redirect after logout
*
* @since 3.4
*/
function cmpuser_redirect_after_logout(){
    if(!cmp_get_option('logout_redirect_url')) return;
    $logout_redirect_url = esc_url(cmp_get_option('logout_redirect_url'));
    wp_redirect( $logout_redirect_url );
    exit();
}
// check if logout redirect is enabled
add_action('wp_logout','cmpuser_redirect_after_logout');

/**
 * Fixed user roles translate
 */
function cmpuser_load_admin_textdomain_in_front() {
    if ( ! is_admin() ) {
        load_textdomain( 'default', WP_LANG_DIR . '/admin-' . get_locale() . '.mo' );
    }
}
add_action( 'init', 'cmpuser_load_admin_textdomain_in_front' );
