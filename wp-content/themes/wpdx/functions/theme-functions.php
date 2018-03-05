<?php
/**
 * Remove Nav Classes
 * From http://www.wpdaxue.com/remove-wordpress-nav-classes.html
 */
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var) {
    return is_array($var) ? array_intersect($var, array('current-menu-item','current-post-ancestor','current-menu-ancestor','current-menu-parent','display-none','children','current_page_parent','current_page_item','current_page_ancestor')) : '';
}
/*-----------------------------------------------------------------------------------*/
# Get Theme Options
/*-----------------------------------------------------------------------------------*/
function cmp_get_option( $name ) {
    $get_options = get_option( 'cmp_options' );
    if( !empty( $get_options[$name] ))
        return $get_options[$name];
    return false ;
}
/*-----------------------------------------------------------------------------------*/
# Setup Theme
/*-----------------------------------------------------------------------------------*/
add_action( 'after_setup_theme', 'cmp_setup' );
function cmp_setup() {
    global $default_data;
    add_theme_support( 'automatic-feed-links' );
    load_theme_textdomain( 'wpdx', get_template_directory() . '/languages' );
    register_nav_menus( array(
        'main-menu' => __( 'Main Menu', 'wpdx' ),
        'foot-menu' => __( 'Footer Menu', 'wpdx' ),
        'foot-link' => __( 'Footer Link', 'wpdx' ),
        'user-menu' => __( 'User Menu', 'wpdx' ),
        //'admin-menu' => __( 'Admin Menu', 'wpdx' ),
        'page-group-1' => __( 'Page Group 1 Menu', 'wpdx' ),
        'page-group-2' => __( 'Page Group 2 Menu', 'wpdx' )
        ) );
}

/*-----------------------------------------------------------------------------------*/
# Check WordPress version  --Added wpdx 1.2
/*-----------------------------------------------------------------------------------*/
add_action('admin_notices', 'wp_version_check_massage');
function wp_version_check_massage(){
    global $wp_version;
    $ver = 4.5;
    if (version_compare($wp_version, $ver) < 0) {
        echo '<div id="message" class="error"><p>'.__("WordPress version you are currently using is less than 4.5, in order to ensure the normal use of the theme, please update to version 4.5 or above.",'wpdx').'</p></div>';
    }
}
/*-----------------------------------------------------------------------------------*/
# Custom Dashboard login page logo
/*-----------------------------------------------------------------------------------*/
function cmp_login_logo(){
    if( cmp_get_option('dashboard_logo') )
        echo '<style  type="text/css"> h1 a {  background-image:url('.cmp_get_option('dashboard_logo').')  !important; } </style>';
}
add_action('login_head',  'cmp_login_logo');

function cmp_login_logo_url($url) {
    if( cmp_get_option('dashboard_logo_url') )
    return cmp_get_option('dashboard_logo_url'); 
}
add_filter( 'login_headerurl', 'cmp_login_logo_url' );

function cmp_login_logo_title($url) {
    if( cmp_get_option('dashboard_logo_title') )
    return cmp_get_option('dashboard_logo_title'); 
}
add_filter( 'login_headertitle', 'cmp_login_logo_title' );
/*-----------------------------------------------------------------------------------*/
# Check  WP-PostViews
/*-----------------------------------------------------------------------------------*/
add_action('admin_notices', 'plugin_check_massage');
function plugin_check_massage(){
    $plugin_messages = array();
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    if(is_plugin_active( 'wp-postviews/wp-postviews.php' ) && cmp_get_option( 'post_views_enable' )){
        $plugin_messages[] = __('Your site seems to have both the WP-PostViews plugin and "<a href=\'/wp-admin/admin.php?page=panel\'>Advanced Settings - Enable Post views statistics</a>" enabled, please make sure only one of them is enabled.','wpdx');
    }
    if(count($plugin_messages) > 0){
        echo '<div id="message" class="error">';
        foreach($plugin_messages as $message)
        {
            echo '<p>'.$message.'</p>';
        }
        echo '</div>';
    }
}
/*-----------------------------------------------------------------------------------*/
# Add custom post types archive to nav menus  -- Added wpdx 1.2
# http://www.wpdaxue.com/add-custom-post-types-archive-to-nav-menus.html
/*-----------------------------------------------------------------------------------*/
if( !class_exists('CustomPostTypeArchiveInNavMenu') ) {
    class CustomPostTypeArchiveInNavMenu {
        function __construct() {
            add_action( 'admin_head-nav-menus.php', array( &$this, 'cpt_navmenu_metabox' ) );
            add_filter( 'wp_get_nav_menu_items', array( &$this,'cpt_archive_menu_filter'), 10, 3 );
        }
        function cpt_navmenu_metabox() {
            add_meta_box( 'add-cpt', __('Custom Post Types Archive','wpdx'), array( &$this, 'cpt_navmenu_metabox_content' ), 'nav-menus', 'side', 'default' );
        }
        function cpt_navmenu_metabox_content() {
            $post_types = get_post_types( array( 'show_in_nav_menus' => true, 'has_archive' => true ), 'object' );
            if( $post_types ) {
                foreach ( $post_types as &$post_type ) {
                    $post_type->classes = array();
                    $post_type->type = $post_type->name;
                    $post_type->object_id = $post_type->name;
                    $post_type->title = $post_type->labels->name;
                    $post_type->object = 'cpt-archive';
                }
                $walker = new Walker_Nav_Menu_Checklist( array() );
                echo '<div id="cpt-archive" class="posttypediv">';
                echo '<div id="tabs-panel-cpt-archive" class="tabs-panel tabs-panel-active">';
                echo '<ul id="ctp-archive-checklist" class="categorychecklist form-no-clear">';
                echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', $post_types), 0, (object) array( 'walker' => $walker) );
                echo '</ul>';
                echo '</div><!-- /.tabs-panel -->';
                echo '</div>';
                echo '<p class="button-controls">';
                echo '<span class="add-to-menu">';
                echo '<input type="submit"' . disabled( $nav_menu_selected_id, 0 ) . ' class="button-secondary submit-add-to-menu right" value="'. __('Add to menu','wpdx') . '" name="add-ctp-archive-menu-item" id="submit-cpt-archive" />';
                echo '<span class="spinner"></span>';
                echo '</span>';
                echo '</p>';
            } else {
                echo __('No Custom Post Types.','wpdx');
            }
        }
        function cpt_archive_menu_filter( $items, $menu, $args ) {
            foreach( $items as &$item ) {
                if( $item->object != 'cpt-archive' ) continue;
                $item->url = get_post_type_archive_link( $item->type );
                if( get_query_var( 'post_type' ) == $item->type ) {
                    $item->classes[] = 'current-menu-item';
                    $item->current = true;
                }
            }
            return $items;
        }
    }
    $CustomPostTypeArchiveInNavMenu = new CustomPostTypeArchiveInNavMenu();
}

/*-----------------------------------------------------------------------------------*/
# Post Thumbinals
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'add_theme_support' ) ){
    add_theme_support( 'post-thumbnails' );
}

/**
 * Get the Attachment ID from an Image URL
 * https://philipnewcomer.net/2012/11/get-the-attachment-id-from-an-image-url-in-wordpress/
 * Since 2.4
 */
function cmp_get_image_id( $attachment_url = '' ) {
    global $wpdb;
    $attachment_id = false;
    // If there is no url, return.
    if ( '' == $attachment_url )
        return;
    // Get the upload directory paths
    $upload_dir_paths = wp_upload_dir();
    // Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
    if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
        // If this is the URL of an auto-generated thumbnail, get the URL of the original image
        $attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
        // Remove the upload path base directory from the attachment URL
        $attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
        // Finally, run a custom database query to get the attachment ID from the modified attachment URL
        $attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
    }
    return $attachment_id;
}
/**
 * Get the first image ID of a post
 * Since 2.4
 */
function cmp_get_first_image_id($postID){
    $attachment_id = '';
    $args = array(
        'numberposts' => 1,
        'order' => 'ASC',
        'post_mime_type' => 'image',
        'post_parent' => $postID,
        'post_status' => null,
        'post_type' => 'attachment',
    );
    $attachments = get_children( $args );
    if ( $attachments ) {
        foreach ( $attachments as $attachment ) {
            $attachment_id = $attachment->ID;
        }
    }
    return $attachment_id;
}
/**
 * Get a random thumbnail for a post
 * Since 2.4
 */
function cmp_get_random_thumb($width,$height){
    $random_thumb = '';
    $random = mt_rand(1, 5);
    $random_thumb = get_template_directory_uri().'/assets/images/pic/'.$random.'-'.$width.'x'.$height.'.jpg';
    return $random_thumb;
}
/**
 * 001. Use OTF Regenerate Thumbnails to get thumb url
 * https://github.com/gambitph/WP-OTF-Regenerate-Thumbnails
 * Since 2.4
 */
require_once(TEMPLATEPATH . '/functions/otf_regen_thumbs.php');
function cmp_otf_thumb_src($width,$height){
    global $post;
    $post_thumbnail_src = '';
    if( $values = get_post_custom_values("thumb") ) {
        $values = get_post_custom_values("thumb");
        $image_id = cmp_get_image_id($values[0]);
        $thumbnail_src = wp_get_attachment_image_src($image_id,array($width,$height));
        $post_thumbnail_src = $thumbnail_src[0];
    } elseif( has_post_thumbnail() ){
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),array($width,$height));
        $post_thumbnail_src = $thumbnail_src[0];
    } else {
        $attachment_id = cmp_get_first_image_id($post->ID);
        if ( $attachment_id ) {
            $thumbnail_src = wp_get_attachment_image_src( $attachment_id, array($width,$height));
            $post_thumbnail_src = $thumbnail_src[0];
        }else{
            $post_thumbnail_src = cmp_get_random_thumb($width,$height);
        }
    }
    return $post_thumbnail_src;
}

/**
 * 002. Use Aqua Resizer to get thumb url
 * https://github.com/syamilmj/Aqua-Resizer
 * Since 2.4
 */
require_once(TEMPLATEPATH . '/functions/aq_resizer.php');
function cmp_aq_thumb_src($width,$height){
    global $post;
    $post_thumbnail_src = '';
    if( $values = get_post_custom_values("thumb") ) {
        $values = get_post_custom_values("thumb");
        $post_thumbnail_src = aq_resize( $values[0], $width, $height, true , true , true );
    } elseif( has_post_thumbnail() ){
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $post_thumbnail_src = aq_resize( $thumbnail_src[0], $width, $height, true , true , true );
    } else {
        $attachment_id = cmp_get_first_image_id($post->ID);
        if ( $attachment_id ) {
            $thumbnail_src = wp_get_attachment_image_src( $attachment_id, 'full');
            $post_thumbnail_src = aq_resize( $thumbnail_src[0], $width, $height, true , true , true );
        }else{
            $post_thumbnail_src = cmp_get_random_thumb($width,$height);
        }
    }
    return $post_thumbnail_src;
}

/**
 * 003. Use Qiniu to get thumb url
 * https://github.com/syamilmj/Aqua-Resizer
 * Since 2.4
 */
function cmp_qiniu_cut($img,$width,$height){
    $q = cmp_get_option('thumb_q')?cmp_get_option('thumb_q'):'90';
    $result = $img.'?imageView2/1/w/'.$width.'/h/'.$height.'/q/'.$q;
    return $result;
}
function cmp_qiniu_thumb_src($width,$height){
    global $post;
    $post_thumbnail_src = '';
    if( $values = get_post_custom_values("thumb") ) {
        $values = get_post_custom_values("thumb");
        $post_thumbnail_src = cmp_qiniu_cut($values[0],$width,$height);
    } elseif( has_post_thumbnail() ){
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $post_thumbnail_src = cmp_qiniu_cut($thumbnail_src[0],$width,$height);
    } else {
        $attachment_id = cmp_get_first_image_id($post->ID);
        if ( $attachment_id ) {
            $thumbnail_src = wp_get_attachment_image_src( $attachment_id, 'full');
            $post_thumbnail_src = cmp_qiniu_cut($thumbnail_src[0],$width,$height);
        }else{
            $post_thumbnail_src = cmp_get_random_thumb($width,$height);
        }
    }
    return $post_thumbnail_src;
}
/**
 * 004. Use Timthumb to get thumb url
 *
 * Since 2.4 ,Changed 2.7
 */
function cmp_tim_cut($img,$width,$height){
    $q = cmp_get_option('thumb_q')?cmp_get_option('thumb_q'):'90';
    $zc = '1';
    if(cmp_get_option('thumb_zc')=='3'){
        $zc = '0';
    }else{
        $zc = cmp_get_option('thumb_zc');
    }
    $result = get_template_directory_uri().'/timthumb.php?src='.$img.'&w='.$width.'&h='.$height.'&zc='.$zc.'&q='.$q.'&ct=1';
    return $result;
}
function cmp_tim_thumb_src($width,$height){
    global $post;
    $post_thumbnail_src = '';
    if( $values = get_post_custom_values("thumb") ) {
        $values = get_post_custom_values("thumb");
        $post_thumbnail_src = cmp_tim_cut($values[0],$width,$height);
    } elseif( has_post_thumbnail() ){
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $post_thumbnail_src = cmp_tim_cut($thumbnail_src[0],$width,$height);
    } else {
        ob_start();
        ob_end_clean();
        $output = preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/Ui', $post->post_content, $matches);
        if(isset($matches[1])) $first_img_src = $matches[1];
        if(!empty($first_img_src)){
            $post_thumbnail_src = cmp_tim_cut($first_img_src,$width,$height);
        }else{
            $post_thumbnail_src = cmp_get_random_thumb($width,$height);
        }
    }
    return $post_thumbnail_src;
}
/**
 * Choose the final way to cut thumbnail
 * Since 2.4
 */
function post_thumbnail_src($width,$height){
    $post_thumbnail_src = '';
    if (cmp_get_option('thumb_cut') == 'tim'){
        $post_thumbnail_src = cmp_tim_thumb_src($width,$height);
    }elseif (cmp_get_option('thumb_cut') == 'otf'){
        $post_thumbnail_src = cmp_otf_thumb_src($width,$height);
    }elseif (cmp_get_option('thumb_cut') == 'qiniu'){
        $post_thumbnail_src = cmp_qiniu_thumb_src($width,$height);
    }else{
        $post_thumbnail_src = cmp_aq_thumb_src($width,$height);
        //$post_thumbnail_src = cmp_otf_thumb_src($width,$height);
    }
    return $post_thumbnail_src;
}
/**
 * echo thumbnail html code
 * Since 2.4
 */
function cmp_post_thumbnail($width,$height){
    if(cmp_get_option( 'lazyload' )): ?>
        <img class="lazy lazy-hidden" src="<?php echo get_template_directory_uri(); ?>/assets/images/grey.gif" data-lazy-type="image" lazydata-src="<?php echo post_thumbnail_src($width,$height); ?>" alt="<?php the_title_attribute(); ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" />
        <noscript><img src="<?php echo post_thumbnail_src($width,$height); ?>" alt="<?php the_title_attribute(); ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" /></noscript>
    <?php else: ?>
        <img src="<?php echo post_thumbnail_src($width,$height); ?>" alt="<?php the_title_attribute(); ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" />
    <?php endif;
}
/**
 * echo thumbnail original
 * Since 2.7
 */
function cmp_post_thumbnail_original(){
    global $post;
    $post_thumbnail_original = '';
    if( $values = get_post_custom_values("thumb") ) {
        $values = get_post_custom_values("thumb");
        $post_thumbnail_original = $values[0];
    } elseif( has_post_thumbnail() ){
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $post_thumbnail_original = $image[0];
    } else {
        ob_start();
        ob_end_clean();
        $output = preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/Ui', $post->post_content, $matches);
        if(isset($matches[1])) $first_img_src = $matches[1];
        if(!empty($first_img_src)){
            $post_thumbnail_original = $first_img_src;
        }
    }
    if($post_thumbnail_original != ''){
        if(cmp_get_option( 'lazyload' )){
            echo '<img class="lazy lazy-hidden" src="'.get_template_directory_uri().'/assets/images/grey.gif" data-lazy-type="image" lazydata-src="'.$post_thumbnail_original.'" alt="'.the_title_attribute('echo=0').'" />
            <noscript><img src="'.$post_thumbnail_original.'" alt="'.the_title_attribute('echo=0').'" /></noscript>';
        }else{
            echo '<img src="'.$post_thumbnail_original.'" alt="'.the_title_attribute('echo=0').'" />';
        }
    }
}
/**
 * Echo Slider Img Src
 * Since 2.4
 */
function cmp_slider_img_src($image_id , $width='' , $height=''){
    $img_src = '';
    if (cmp_get_option('thumb_cut') == 'otf'){
        $img = wp_get_attachment_image_src($image_id, array($width,$height) );
        $img_src = $img[0];
    }else{
        $img =  wp_get_attachment_image_src( $image_id , 'full' );
        if (cmp_get_option('thumb_cut') == 'tim'){
            $img_src = cmp_tim_cut($img[0],$width,$height);
        }elseif (cmp_get_option('thumb_cut') == 'qiniu'){
            $img_src = cmp_qiniu_cut($img[0],$width,$height);
        }elseif (cmp_get_option('thumb_cut') == 'aq'){
            $img_src = aq_resize( $img[0], $width, $height, true , true , true );
        }
    }
    return $img_src;
}
/*-----------------------------------------------------------------------------------*/
# If the menu doesn't exist
/*-----------------------------------------------------------------------------------*/
function cmp_nav_fallback(){
    echo '<li class="the_tips">'.__( 'Please Visit "Appearance > Menus" to build menus' , 'wpdx' ).'</li>';
}
/*-----------------------------------------------------------------------------------*/
# Menu Shortcode [menu name="menu name"]
# http://www.wpdaxue.com/embed-menu-in-content-shortcode.html
/*-----------------------------------------------------------------------------------*/
function print_menu_shortcode($atts, $content = null) {
    extract(shortcode_atts(array( 'name' => null, ), $atts));
    return wp_nav_menu( array( 'menu' => $name, 'echo' => false ) );
    echo '<div class="clear"></div>';
}
add_shortcode('menu', 'print_menu_shortcode');
/*-----------------------------------------------------------------------------------*/
# add_contact_fields on profile
/*-----------------------------------------------------------------------------------*/
add_filter( 'user_contactmethods', 'cm_add_contact_fields' );
function cm_add_contact_fields( $contactmethods ) {
    $contactmethods['qq'] = __('QQ','wpdx');
    $contactmethods['qm_mailme'] = __('QQ Mail Me','wpdx');
    $contactmethods['qq_weibo'] = __('QQ Weibo','wpdx');
    $contactmethods['sina_weibo'] = __('Sina Weibo','wpdx');
    $contactmethods['twitter'] = __('Twitter','wpdx');
    $contactmethods['google_plus'] = __('Google+','wpdx');
    //$contactmethods['donate'] = __('Donate url','wpdx');
    unset( $contactmethods['yim'] );
    unset( $contactmethods['aim'] );
    unset( $contactmethods['jabber'] );
    return $contactmethods;
}
/*-----------------------------------------------------------------------------------*/
# Author Box --used in single post、archive and sidebar
/*-----------------------------------------------------------------------------------*/
function cmp_author_box($avatar = true , $social = true ){
    if( $avatar ) : ?>
    <div class="author-avatar">
        <?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'MFW_author_bio_avatar_size', 64 ) ); ?>
    </div>
<?php endif; ?>
<div class="author-description">
    <p><?php if(get_the_author_meta( 'description' )){
        echo get_the_author_meta( 'description' );
    }else{
        $current_user = wp_get_current_user();
        $author_id = get_the_author_meta( 'ID' );
        if(class_exists("WP_User_Frontend") && $current_user->ID == $author_id){
            $profile_url = get_home_url().'/user/profile';
            $description = sprintf(__('Please visit <a href="%s">Your Profile</a> to fill in Biographical Info.','wpdx'),$profile_url);
        }elseif($current_user->ID == $author_id){
            $profile_url = get_home_url().'/wp-admin/profile.php';
            $description = sprintf(__('Please visit <a href="%s">Your Profile</a> to fill in Biographical Info.','wpdx'),$profile_url);
        }else{
            $description = __('The user is lazy, not fill in his Biographical Info.','wpdx');
        }
        echo $description;
    }
    ?>
</p>
<?php  if( $social ) :  ?>
    <ul class="author-social follows nb">
        <?php  if( !is_author() ) :
        $userlinks = get_author_posts_url( get_the_author_meta( 'ID' ) );
        ?>
        <li class="archive">
            <a target="_blank" href="<?php echo $userlinks; ?>" title="<?php echo sprintf( __( "Read more articles of %s", 'wpdx' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Read more articles of %s", 'wpdx' ), get_the_author_meta( 'display_name' )); ?></a>
        </li>
    <?php endif; ?>
    <?php if ( get_the_author_meta( 'url' ) ) : ?>
        <li class="website">
            <a target="_blank" rel="nofollow" href="<?php the_author_meta( 'url' ); ?>" title="<?php echo sprintf( __( "Visit %s's site", 'wpdx' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Visit %s's site", 'wpdx' ), get_the_author_meta( 'display_name' )); ?></a>
        </li>
    <?php endif ?>
    <?php if ( get_the_author_meta( 'qq' ) ) : ?>
        <li class="qq">
            <a target="_blank" rel="nofollow" href="http://wpa.qq.com/msgrd?v=3&amp;site=qq&amp;menu=yes&amp;uin=<?php the_author_meta( 'qq' ); ?>" title="<?php echo sprintf( __( "Contact %s by QQ", 'wpdx' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Contact %s by QQ", 'wpdx' ), get_the_author_meta( 'display_name' )); ?></a>
        </li>
    <?php endif ?>
    <?php if(class_exists("fep_main_class")){ ?>
    <li class="email">
        <a target="_blank" rel="nofollow" href="<?php echo get_home_url(); ?>/user/pm?fepaction=newmessage&amp;to=<?php echo get_the_author_meta( 'user_login' ); ?>" title="<?php echo sprintf( __( "Contact %s by Private Messages", 'wpdx' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Contact %s by Private Messages", 'wpdx' ), get_the_author_meta( 'display_name' )); ?></a>
    </li>
    <?php } elseif(get_the_author_meta( 'qm_mailme' )) { ?>
    <li class="email">
        <a target="_blank" rel="nofollow" href="<?php the_author_meta( 'qm_mailme' ); ?>" title="<?php echo sprintf( __( "Contact %s by Email", 'wpdx' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Contact %s by Email", 'wpdx' ), get_the_author_meta( 'display_name' )); ?></a>
    </li>
    <?php } ?>
    <?php if ( get_the_author_meta( 'sina_weibo' ) ) : ?>
        <li class="sina_weibo">
            <a target="_blank" rel="nofollow" href="<?php the_author_meta( 'sina_weibo' ); ?>" title="<?php echo sprintf( __( "Follow %s on Sina Weibo", 'wpdx' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Follow %s on Sina Weibo", 'wpdx' ), get_the_author_meta( 'display_name' )); ?></a>
        </li>
    <?php endif ?>
    <?php if ( get_the_author_meta( 'qq_weibo' ) ) : ?>
        <li class="qq_weibo">
            <a target="_blank" rel="nofollow" href="<?php the_author_meta( 'qq_weibo' ); ?>" title="<?php echo sprintf( __( "Follow %s on QQ Weibo", 'wpdx' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Follow %s on QQ Weibo", 'wpdx' ), get_the_author_meta( 'display_name' )); ?></a>
        </li>
    <?php endif ?>
    <?php if ( get_the_author_meta( 'twitter' ) ) : ?>
        <li class="twitter">
            <a target="_blank" rel="nofollow" href="<?php the_author_meta( 'twitter' ); ?>" title="<?php echo sprintf( __( "Follow %s on Twitter", 'wpdx' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Follow %s on Twitter", 'wpdx' ), get_the_author_meta( 'display_name' )); ?></a>
        </li>
    <?php endif ?>
    <?php if ( get_the_author_meta( 'google_plus' ) ) : ?>
        <li class="google_plus">
            <a target="_blank" href="<?php the_author_meta( 'google_plus' ); ?>" rel="author" title="<?php echo sprintf( __( "Follow %s on Google+", 'wpdx' ), get_the_author_meta( 'display_name' )); ?>"><?php echo sprintf( __( "Follow %s on Google+", 'wpdx' ), get_the_author_meta( 'display_name' )); ?></a>
        </li>
    <?php endif ?>
</ul>
</div>
<?php endif; ?>
<div class="clear"></div>
<?php
}

/*-----------------------------------------------------------------------------------*/
# Archives list by zwwooooo | http://zww.me
# Changed 2.8
/*-----------------------------------------------------------------------------------*/
function zww_archives_list() {
    if( !$output = get_option('zww_db_cache_archives_list') ){
        $output = '<div id="archives"><p>[<a id="al_expand_collapse" href="#">'.__('Expand / Collapse All </a>] <em>(Note: Click on the month you can expand it)</em>','wpdx').'</p>';
        $args = array(
            'post_type' => array('post', 'product'), 
            'posts_per_page' => -1,
            'ignore_sticky_posts' => 1
        );
        $the_query = new WP_Query( $args );
        $posts_rebuild = array();
        $year = $mon = 0;
        while ( $the_query->have_posts() ) : $the_query->the_post();
            $post_year = get_the_time('Y');
            $post_mon = get_the_time('m');
            $post_day = get_the_time('d');
            if ($year != $post_year) $year = $post_year;
            if ($mon != $post_mon) $mon = $post_mon;
            $posts_rebuild[$year][$mon][] = '<li>'. get_the_time('d') .__('Day:','wpdx').'<a href="'. get_permalink() .'">'. get_the_title() .'</a> <em>('. get_comments_number('0', '1', '%') .')</em></li>';
        endwhile;
        wp_reset_postdata();

        foreach ($posts_rebuild as $key_y => $y) {
            $output .= '<h3 class="al_year">'. $key_y .__(' Year','wpdx').'</h3><ul class="al_mon_list">';
            foreach ($y as $key_m => $m) {
                $posts = ''; $i = 0;
                foreach ($m as $p) {
                    ++$i;
                    $posts .= $p;
                }
                $output .= '<li><span class="al_mon">'. $key_m .__(' Month','wpdx').'<em> ( '. $i . __(' Posts','wpdx').' )</em></span><ul class="al_post_list">'; 
                $output .= $posts;
                $output .= '</ul></li>';
            }
            $output .= '</ul>';
        }

        $output .= '</div>';
        update_option('zww_db_cache_archives_list', $output);
    }
    echo $output;
}
function clear_db_cache_archives_list() {
    update_option('zww_db_cache_archives_list', ''); 
}
add_action('save_post', 'clear_db_cache_archives_list'); 
/*-----------------------------------------------------------------------------------*/
# Custom comment style
/*-----------------------------------------------------------------------------------*/
function cm_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    $comorder =  get_option('comment_order');
    if($comorder == 'asc'){
        global $commentcount;
        if(!$commentcount) {
            $page = get_query_var('cpage')-1;
            $cpp=get_option('comments_per_page');
            $commentcount = $cpp * $page;
        }
    }else{
        global $commentcount,$wpdb, $post;
        if(!$commentcount) {
            $comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $post->ID AND comment_type = '' AND comment_approved = '1' AND !comment_parent");
            $cnt = count($comments);
            $page = get_query_var('cpage');
            $cpp=get_option('comments_per_page');
            if (ceil($cnt / $cpp) == 1 || ($page > 1 && $page  == ceil($cnt / $cpp))) {
                $commentcount = $cnt + 1;
            } else {$commentcount = $cpp * $page + 1;}
        }
    }
    ?>
    <li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
            <?php $add_below = 'div-comment'; ?>
            <div class="comment-author vcard">
                <?php echo get_avatar( $comment, 54 , '',$comment->comment_author); ?>
                <div class="floor">
                    <?php
                    if($comorder == 'asc'){
                        if(!$parent_id = $comment->comment_parent){printf('%1$s#', ++$commentcount);}
                    }else{
                        if(!$parent_id = $comment->comment_parent){printf('%1$s#', --$commentcount);}
                    }
                    ?>
                </div>
                <?php comment_author_link() ?>:<?php edit_comment_link(__('Edit','wpdx'),'&nbsp;&nbsp;',''); ?>
            </div>
            <?php if ( $comment->comment_approved == '0' ) : ?>
                <span><?php _e('Your comment is awaiting moderation ...','wpdx') ?></span>
                <br />
            <?php endif; ?>
            <?php comment_text() ?>
            <div class="clear"></div>
            <span class="datetime"><?php comment_date('Y-m-d') ?> <?php comment_time() ?> </span>
            <span class="reply"><?php comment_reply_link(array_merge( $args, array('reply_text' => __('[Reply]','wpdx'), 'add_below' =>$add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?></span>
        </div>
    <?php
}
function cm_end_comment() {
    echo '</li>';
}

/*-----------------------------------------------------------------------------------*/
# comment_mail_notify
# Changed 3.6
/*-----------------------------------------------------------------------------------*/
/**
 * Persists the customer choice.
 *
 * @param  int     $commentId The comment ID
 * @return boolean
 */
function cmp_comment_mail_notify_opt_in($commentId) {
    $value = (isset($_POST['comment_mail_notify']) && $_POST['comment_mail_notify'] == '1') ? '1' : '0';
    return add_comment_meta($commentId, 'comment_mail_notify', $value, true);
}

/**
 * Filter that changes the email content type when the notification is sent.
 * @param  string $contentType The content type
 * @return string
 */
function cmp_wp_mail_content_type_filter($contentType) {
    return 'text/html';
}
/**
 * [cmp_add_checkbox description]
 * @return [type] [description]
 */
function cmp_add_checkbox() {
    global $post;
    if($post->post_type == 'post' || $post->post_type == 'page' ){
        echo '<p><label for="comment_mail_notify"><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked"/>'.__('E-mail me when someone replies to me.','wpdx').'</label></p>';
    }
}

/**
 * Sends a notification if a comment is approved
 * @param  int    $commentId     The comment ID
 * @param  string $commentStatus The new comment status
 * @return boolean
 */
function cmp_comment_status_update($commentId, $commentStatus) {
    $comment = get_comment($commentId);

    if ($commentStatus == 'approve') {
        cmp_comment_notification($comment->comment_ID, $comment);
    }
}

/**
 * Sends an email notification when a comment receives a reply
 *
 * @param  int    $commentId The comment ID
 * @param  object $comment   The comment object
 * @return boolean
 */
function cmp_comment_notification($commentId, $comment) {
    if ($comment->comment_approved == 1 && $comment->comment_parent > 0) {
        $parent = get_comment($comment->comment_parent);
        $email  = $parent->comment_author_email;

        // Parent comment author == new comment author
        // In this case, we don't send a notification.
        if ($email == $comment->comment_author_email) {
            return false;
        }

        $subscription = get_comment_meta($parent->comment_ID, 'comment_mail_notify', true);

        // If we don't find the option, we assume the user is subscribed.
        if ($subscription && $subscription == '0') {
            return false;
        }

        $body = '
        <table align="center" width="600" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:40px auto; padding: 30px; border: 1px solid #eeeeee; border-collapse: collapse; font-family:Arial,\'Microsoft YaHei\',\'WenQuanYi Micro Hei\',\'Open Sans\',\'Hiragino Sans GB\',Verdana,sans-serif;">
            <thead>
                <tr>
                <th align="center" bgcolor="#01ACE2" style="padding: 30px 0; color: #ffffff; font-size: 22px; ">'.__( 'Comment Reply notice', 'wpdx' ).'</th>
                </tr>
            </thead>
            <tbody width="90%" style="width:90%; padding: 20px 5%; font-size: 16px; display:block;line-height: 24px;">
                <tr>
                    <td style="padding: 8px;font-size: 16px;"><b>' . sprintf( __( 'Hi, %s! %s just reply to your comments.', 'wpdx' ), trim($parent->comment_author), trim($comment->comment_author) ) . '</b></td>
                </tr>
                <tr>
                <td style="padding: 8px;font-size: 16px;"><b>' . sprintf( __( 'Your comments on [<a href="%s">%s</a>]:', 'wpdx' ), get_permalink($parent->comment_post_ID), get_the_title($parent->comment_post_ID) ) . '</b></td>
                </tr>
                <tr>
                    <td width="95%" bgcolor="#f4f4f4" style="padding: 10px 2%;border:1px solid #eee;font-size: 16px; width:95%; display:inline-block;">'. trim($parent->comment_content) . '</td>
                </tr>
                <tr>
                    <td style="padding: 8px;font-size: 16px;"><b>' . trim($comment->comment_author) . __(' Reply to you:','wpdx').'</b></td>
                </tr>
                <tr>
                   <td width="95%" bgcolor="#f4f4f4" style="padding: 10px 2%;border:1px solid #eee;font-size: 16px; width:95%; display:inline-block;">'
                    . trim($comment->comment_content) . '</td>
                </tr>
                <tr>
                    <td style="padding: 8px;font-size: 16px;">' . sprintf( __( 'Click <a href="%s">HERE</a> for more details and reply to %s.', 'wpdx' ), htmlspecialchars(get_comment_link($parent->comment_ID)), trim($comment->comment_author) ) . '</td>
                </tr>
                <tr>
                    <td style="padding: 8px;font-size: 16px;">'.__('Welcome back to','wpdx').'<a href="' . home_url() . '">' . get_option('blogname') . '</a></td>
                </tr>
                <tr>
                    <td style="padding: 8px; color:#E74C3C;font-size: 16px;">'.__('Note: This message is sent automatically by the system, do not reply to.','wpdx').'</td>
                </tr>
            </tbody>
            <tfoot>
                <tr><td bgcolor="#21292E" style="padding: 20px 30px; font-size: 14px; color: #76838F; text-align: center">' . sprintf( __( '© %s all rights reserved', 'wpdx' ), get_option('blogname') ) . '</td></tr>
            </tfoot>
        </table>';

        $title = sprintf( __( '[%s] Hi, %s! %s just reply to your comments', 'wpdx' ), get_option("blogname"), trim($parent->comment_author), trim($comment->comment_author) );

        add_filter('wp_mail_content_type', 'cmp_wp_mail_content_type_filter');

        wp_mail($email, $title, $body);

        remove_filter('wp_mail_content_type', 'cmp_wp_mail_content_type_filter');
    }
}

if (cmp_get_option('comment_mail_notify')) {
    add_action('comment_form','cmp_add_checkbox');
    add_action('wp_insert_comment', 'cmp_comment_notification', 99, 2);
    add_action('wp_set_comment_status','cmp_comment_status_update', 99, 2);
    add_action('comment_post', 'cmp_comment_mail_notify_opt_in');
}

/*-----------------------------------------------------------------------------------*/
# Get Home Cats Boxes
/*-----------------------------------------------------------------------------------*/
function cmp_get_home_cats($cat_data){
    switch( $cat_data['type'] ){
        case 'n':
        get_home_cats( $cat_data );
        break;
        case 'n-edd':
        get_home_cats_edd( $cat_data );
        break;
        case 'tabs':
        get_home_tabs( $cat_data );
        break;
        case 'tabs-edd':
        get_home_tabs_edd( $cat_data );
        break;
        case 's':
        get_home_scroll( $cat_data );
        break;
        case 's-edd':
        get_home_scroll_edd( $cat_data );
        break;
        case 'news-pic':
        get_home_news_pic( $cat_data );
        break;
        case 'news-pic-edd':
        get_home_news_pic_edd( $cat_data );
        break;
        case 'recent':
        get_home_recent( $cat_data );
        break;
        case 'users':
        get_home_users( $cat_data );
        break;
        case 'divider': ?>
        </div>
        <div class="row-fluid">
        <?php
        break;
        case 'ads':
        get_home_ads($cat_data);
        break;
    }
}
/*-----------------------------------------------------------------------------------*/
# Get Home ads
# Since 2.4
/*-----------------------------------------------------------------------------------*/
function get_home_ads($cat_data){
    $ad_code = $cat_data['text'];
    $who = $cat_data['who'];
    if( $who == 'logged' && !is_user_logged_in()){
        // return none;
    }elseif( $who == 'anonymous' && is_user_logged_in()){
        // return none;
    }else{
        if($ad_code){ ?>
            </div>
            <div class="row-fluid gsfha3">
            <?php echo do_shortcode( htmlspecialchars_decode(stripslashes ($ad_code) )) ?>
            </div>
            <div class="clear"></div>
            <div class="row-fluid">
        <?php
        }
    }
}

/*-----------------------------------------------------------------------------------*/
# Get Home Users
# Since 2.4
/*-----------------------------------------------------------------------------------*/
function get_home_users($cat_data){
    $Box_Title = $cat_data['title'];
    $icon = $cat_data['icon'] ? $cat_data['icon']:'fa-list';
    $more_text = $cat_data['more_text'] ? $cat_data['more_text']:'More';
    $more_url = $cat_data['more_url'];
    $users = explode(',', $cat_data['user']);
    $who = $cat_data['who'];
    if( $who == 'logged' && !is_user_logged_in()):
        // return none;
    elseif( $who == 'anonymous' && is_user_logged_in()):
        // return none;
    else:
    if($users): ?>
    <div class="span12 home-users">
        <div class="widget-box">
            <div class="widget-title">
            <?php if($more_url): ?>
                    <span class="more"><a target="_blank" href="<?php echo $more_url; ?>"><?php echo $more_text; ?></a></span>
                <?php endif; ?>
                <span class="icon"> <i class="fa <?php echo $icon; ?> fa-fw"></i> </span>
                <h2><?php echo $Box_Title ; ?></h2>
            </div>
            <div class="widget-content">
                <ul>
                    <?php
                    foreach ( $users as $user_id ) {
                        $user = get_userdata($user_id);
                        ?>
                        <li><a class="user-avatar" target="_blank" rel="nofollow" href="<?php echo get_author_posts_url($user->ID); ?>"><?php echo get_avatar($user->ID, 160); ?></a><p><a class="user-name" href="<?php echo get_author_posts_url($user->ID); ?>">
                            <?php echo $user->display_name; ?></a></p>
                            <p><?php echo __('Posts ','wpdx').'('. count_user_posts( $user->ID ).')'; ?></p>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <?php
    endif;
    endif;
}

/*-----------------------------------------------------------------------------------*/
# Get templates
/*-----------------------------------------------------------------------------------*/
function cmp_include($template){
    include ( get_template_directory() . '/includes/'.$template.'.php' );
}
/*-----------------------------------------------------------------------------------*/
# News In Picture
/*-----------------------------------------------------------------------------------*/
function wp_last_news_pic($order , $numberOfPosts = 12 , $cats = 1 ){
    global $post;
    $orig_post = $post;
    if( $order == 'random')
        $lastPosts = get_posts( $args = array('numberposts' => $numberOfPosts, 'orderby' => 'rand', 'category' => $cats ,'no_found_rows' => 1));
    else
        $lastPosts = get_posts( $args = array('numberposts' => $numberOfPosts, 'category' => $cats ,'no_found_rows' => 1));
    get_posts('no_found_rows=true&category='.$cats.'&numberposts='.$numberOfPosts);
    $i=1;
    foreach($lastPosts as $post): setup_postdata($post); ?>
    <div class="new-pic<?php if($i % 2 == 0)echo ' pic-even'; ?>">
    <p><a class="post-thumbnail" href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>>
        <?php cmp_post_thumbnail(330,200) ?>
    </a>
    </p>
    <p class="pic-t"><a href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" <?php echo cmp_target_blank();?>><?php the_title(); ?></a></p>
    </div>
    <?php $i++; endforeach;
    $post = $orig_post;
}
/*-----------------------------------------------------------------------------------*/
# Get Most Racent posts
/*-----------------------------------------------------------------------------------*/
function wp_last_posts($numberOfPosts = 5 , $thumb = true){
    global $post;
    $orig_post = $post;
    $lastPosts = get_posts('no_found_rows=true&numberposts='.$numberOfPosts);
    foreach($lastPosts as $post): setup_postdata($post);?>
    <li>
        <div class="widget-thumb">
            <?php if ( $thumb ) : ?>
                <a class="post-thumbnail" href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>>
                    <?php cmp_post_thumbnail(75,45) ?>
                </a>
            <?php endif; ?>
            <a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php echo the_title(); ?>" <?php echo cmp_target_blank();?>><?php echo the_title(); ?></a>
            <span class="date"><?php cmp_get_time(); ?></span>
        </div>
    </li>
    <?php endforeach;
    $post = $orig_post;
}
/*-----------------------------------------------------------------------------------*/
# Get Most Racent posts from Category
/*-----------------------------------------------------------------------------------*/
function wp_last_posts_cat($numberOfPosts = 5 , $thumb = true , $cats = 1){
    global $post;
    $orig_post = $post;
    $lastPosts = get_posts('no_found_rows=true&category='.$cats.'&numberposts='.$numberOfPosts);
    foreach($lastPosts as $post): setup_postdata($post);
    ?>
    <li>
        <div class="widget-thumb">
            <?php if ( $thumb ) : ?>
                <a class="post-thumbnail" href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>>
                    <?php cmp_post_thumbnail(75,45) ?>
                </a>
            <?php endif; ?>
            <a href="<?php the_permalink(); ?>" <?php echo cmp_target_blank();?>><?php the_title();?></a>
            <span class="date"><?php cmp_get_time() ?></span>
        </div>
    </li>
<?php endforeach;
$post = $orig_post;
}
/*-----------------------------------------------------------------------------------*/
# Get Random posts
/*-----------------------------------------------------------------------------------*/
function wp_random_posts($numberOfPosts = 5 , $thumb = true){
    global $post;
    $orig_post = $post;
    $lastPosts = get_posts('no_found_rows=true&orderby=rand&numberposts='.$numberOfPosts);
    foreach($lastPosts as $post): setup_postdata($post);?>
        <li>
            <div class="widget-thumb">
                <?php if ( $thumb ) : ?>
                    <a class="post-thumbnail" href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php echo cmp_target_blank();?>>
                        <?php cmp_post_thumbnail(75,45) ?>
                    </a>
                <?php endif; ?>
                <a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php echo the_title(); ?>" <?php echo cmp_target_blank();?>><?php echo the_title(); ?></a>
                <span class="date"><?php cmp_get_time(); ?></span>
            </div>
        </li>
    <?php endforeach;
    $post = $orig_post;
}
/*-----------------------------------------------------------------------------------*/
# Get Popular posts
/*-----------------------------------------------------------------------------------*/
function wp_popular_posts($pop_posts = 5 , $thumb = true , $days = 30){
    global $wpdb , $post;
    $orig_post = $post;
    $today = date("Y-m-d H:i:s");
    $daysago = date( "Y-m-d H:i:s", strtotime($today) - ($days * 24 * 60 * 60) );
    $popularposts = "SELECT ID,post_title,post_date,post_author,post_content,post_type FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_date BETWEEN '$daysago' AND '$today' ORDER BY comment_count DESC LIMIT 0,".$pop_posts;
    $posts = $wpdb->get_results($popularposts);
    if($posts){
        global $post;
        foreach($posts as $post){
            setup_postdata($post);?>
            <li>
                <div class="widget-thumb">
                    <?php if ( $thumb ) : ?>
                        <a class="post-thumbnail" href="<?php echo get_permalink( $post->ID ) ?>" title="<?php printf( __( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" title="<?php echo the_title(); ?>" >
                            <?php cmp_post_thumbnail(75,45) ?>
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php echo the_title(); ?>" title="<?php echo the_title(); ?>" ><?php echo the_title(); ?></a>
                    <span class="date"><?php cmp_get_time(); ?></span>
                </div>
            </li>
            <?php
        }
    }
    $post = $orig_post;
}

/*-----------------------------------------------------------------------------------*/
# Get the post time
/*-----------------------------------------------------------------------------------*/
function cmp_get_time(){
    global $post ;
    if( cmp_get_option( 'time_format' ) == 'none' ){
        return false;
    }elseif( cmp_get_option( 'time_format' ) == 'modern' ){
        $to = current_time('timestamp',1);
        $from = get_the_time('U') ;
        $since = human_time_diff( $from, $to ). __( 'ago' , 'wpdx' );
    }else{
        $since = get_the_time(get_option('date_format'));
    }
    echo $since ;
}

/*-----------------------------------------------------------------------------------*/
#  Banners
/*-----------------------------------------------------------------------------------*/
function cmp_banner( $banner , $before= false , $after = false, $output = true){
    $ads =  '';
    $who = cmp_get_option( $banner.'_who' );
    if( $who == 'logged' && !is_user_logged_in()){

    }elseif( $who == 'anonymous' && is_user_logged_in()){

    }else{
        if(cmp_get_option( $banner )){
            if(cmp_is_mobile()){
                if(cmp_get_option( $banner.'_mobile_adsense' )) 
                $ads =  $before.htmlspecialchars_decode(cmp_get_option( $banner.'_mobile_adsense' )).$after;
            }else{
                $target="";
                if( cmp_get_option( $banner.'_tab' )) $target='target="_blank"';
                if(cmp_get_option( $banner.'_img' )){
                    $ads =  $before.'
                    <a href="'.cmp_get_option( $banner.'_url' ).'" rel="nofollow" title="'.cmp_get_option( $banner.'_alt').'" '.$target.'>
                        <img src="'.cmp_get_option( $banner.'_img' ).'" alt="'.cmp_get_option( $banner.'_alt').'" />
                    </a>
                    '.$after;
                }elseif(cmp_get_option( $banner.'_adsense' )){
                    $ads =  $before.htmlspecialchars_decode(cmp_get_option( $banner.'_adsense' )).$after;
                }

            }
        }
    }
    if($output = true){
        echo $ads;
    }else{
        return $ads;
    }
}
/*-----------------------------------------------------------------------------------*/
#  Ads Shortcode
/*-----------------------------------------------------------------------------------*/
## Ads1 -------------------------------------------------- #
function cmp_shortcode_ads1( $atts, $content = null ) {
    $ads = cmp_banner('ads1_shortcode' , '<div class="ggg-shortcode">' , '</div>' ,false );
    return $ads;
}
add_shortcode('ads1', 'cmp_shortcode_ads1');
## Ads2 -------------------------------------------------- #
function cmp_shortcode_ads2( $atts, $content = null ) {
    $ads = cmp_banner('ads2_shortcode' , '<div class="ggg-shortcode">' , '</div>' ,false );
    return $ads;
}
add_shortcode('ads2', 'cmp_shortcode_ads2');

/**
* Disable the emoji's
 */
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );
/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param    array  $plugins
 * @return   array             Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
}
/**
 * 4.2表情
 */
function my_init_smilies(){
    global $wpsmiliestrans;
    $wpsmiliestrans = array(
        ':mrgreen:' => 'icon_mrgreen.gif',
        ':neutral:' => 'icon_neutral.gif',
        ':twisted:' => 'icon_twisted.gif',
        ':arrow:' => 'icon_arrow.gif',
        ':shock:' => 'icon_eek.gif',
        ':smile:' => 'icon_smile.gif',
        ':???:' => 'icon_confused.gif',
        ':cool:' => 'icon_cool.gif',
        ':evil:' => 'icon_evil.gif',
        ':grin:' => 'icon_biggrin.gif',
        ':idea:' => 'icon_idea.gif',
        ':oops:' => 'icon_redface.gif',
        ':razz:' => 'icon_razz.gif',
        ':roll:' => 'icon_rolleyes.gif',
        ':wink:' => 'icon_wink.gif',
        ':cry:' => 'icon_cry.gif',
        ':eek:' => 'icon_surprised.gif',
        ':lol:' => 'icon_lol.gif',
        ':mad:' => 'icon_mad.gif',
        ':sad:' => 'icon_sad.gif',
        '8-)' => 'icon_cool.gif',
        '8-O' => 'icon_eek.gif',
        ':-(' => 'icon_sad.gif',
        ':-)' => 'icon_smile.gif',
        ':-?' => 'icon_confused.gif',
        ':-D' => 'icon_biggrin.gif',
        ':-P' => 'icon_razz.gif',
        ':-o' => 'icon_surprised.gif',
        ':-x' => 'icon_mad.gif',
        ':-|' => 'icon_neutral.gif',
        ';-)' => 'icon_wink.gif',
        '8O' => 'icon_eek.gif',
        ':(' => 'icon_sad.gif',
        ':)' => 'icon_smile.gif',
        ':?' => 'icon_confused.gif',
        ':D' => 'icon_biggrin.gif',
        ':P' => 'icon_razz.gif',
        ':o' => 'icon_surprised.gif',
        ':x' => 'icon_mad.gif',
        ':|' => 'icon_neutral.gif',
        ';)' => 'icon_wink.gif',
        ':!:' => 'icon_exclaim.gif',
        ':?:' => 'icon_question.gif',
    );
}
add_action( 'init', 'my_init_smilies', 5 );
/**
 * Fixed 4.2+ activity widget avatar
 * Since 2.4
 */
if( !function_exists('fixed_activity_widget_avatar_style')){
    function fixed_activity_widget_avatar_style(){
        echo '<style type="text/css">
        #activity-widget #the-comment-list .avatar {
        position: absolute;
        top: 13px;
        width: 50px;
        height: 50px;
        }
    </style>';
    }
    add_action('admin_head', 'fixed_activity_widget_avatar_style' );
}
/**
 * Test if the current browser runs on a mobile device (smart phone, tablet, etc.)
 * Since 2.4
 */
function cmp_is_mobile() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $mobile_browser = Array(
        "mqqbrowser","opera mobi","juc","iuc","fennec","ios","applewebKit/420","applewebkit/525","applewebkit/532","ipad","iphone","ipaq","ipod","iemobile", "windows ce","240x320","480x640","acer","android","anywhereyougo.com","asus","audio","blackberry","blazer","coolpad" ,"dopod", "etouch", "hitachi","htc","huawei", "jbrowser", "lenovo","lg","lg-","lge-","lge", "mobi","moto","nokia","phone","samsung","sony","symbian","tablet","tianyu","wap","xda","xde","zte"
        );
    $is_mobile = false;
    foreach ($mobile_browser as $device) {
        if (stristr($user_agent, $device)) {
            $is_mobile = true;
            break;
        }
    }
    return $is_mobile;
}

/*-----------------------------------------------------------------------------------*/
# Add Page Break button in WordPress Visual Editor
# 2.4
/*-----------------------------------------------------------------------------------*/
add_filter( 'mce_buttons', 'cmp_add_page_break_button', 1, 2 );
function cmp_add_page_break_button( $buttons, $id ){
    if ( 'content' != $id )
        return $buttons;
    array_splice( $buttons, 13, 0, 'wp_page' );
    return $buttons;
}

/*-----------------------------------------------------------------------------------*/
# Get avatar from http://cn.gravatar.com
# Since 2.4
/*-----------------------------------------------------------------------------------*/
if(!function_exists('get_ssl_avatar') && !function_exists('get_cn_avatar')){
    function cmp_get_cn_avatar($avatar) {
        $avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/','<img src="//cn.gravatar.com/avatar/$1?s=$2" class="avatar avatar-$2" height="$2" width="$2">',$avatar);
        return $avatar;
    }
    add_filter('get_avatar', 'cmp_get_cn_avatar',99,1);
}
function cmp_get_cn_avatar_url($avatar) {
    $avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/','//cn.gravatar.com/avatar/$1?s=$2"',$avatar);
    return $avatar;
}
add_filter('get_avatar_url', 'cmp_get_cn_avatar_url',99,1);

/*-----------------------------------------------------------------------------------*/
# Add cuctom content after post
# 2.4
/*-----------------------------------------------------------------------------------*/
function add_after_post_content() {
    global $post;
    $note = '';
    $original_url_nofollow = '';
    if(cmp_get_option('original_url_nofollow')) $original_url_nofollow = 'rel="nofollow"';
    $original_url = get_post_meta( $post->ID, '_cmp_original_url', true );
    $original_author = get_post_meta( $post->ID, '_cmp_original_author', true );
    $original_website = get_post_meta( $post->ID, '_cmp_original_website', true );
    if( $post->post_type == "post" && (is_feed() || is_single()) ){

        if(cmp_get_option('post_note_type') =='static' && cmp_get_option('post_note') ) {
            $note = '<div class="old-message">'.htmlspecialchars_decode(cmp_get_option('post_note')).'</div>';
        }elseif(cmp_get_option('post_note_type') =='dynamic'){
            if($original_url){
                $note = '<div class="old-message">'.sprintf(__('Note: This article is reproduced from %s, posted by %s, original URL: <a href="%s" target="_blank" %s>%s</a>','wpdx'),$original_website,$original_author,esc_url( $original_url),$original_url_nofollow,esc_url( $original_url)).'</div>';
            }else{
                $note = '<div class="old-message">'.sprintf(__('Note: This is an original article, posted by %s, please keep this statement and URL link when reproduced: <a href="%s" target="_blank">%s</a>','wpdx'),get_the_author(),get_permalink(),get_permalink()).'</div>';
            }
        }
    }
    return $note;
}
/*-----------------------------------------------------------------------------------*/
# Excerpt Length
/*-----------------------------------------------------------------------------------*/
function cmp_excerpt_global_length( $length ) {
    if( cmp_get_option( 'exc_length' ) )
        return cmp_get_option( 'exc_length' );
    else return 120;
}
function cmp_excerpt_home_length( $length ) {
    if( cmp_get_option( 'home_exc_length' ) )
        return cmp_get_option( 'home_exc_length' );
    else return 80;
}
function cmp_excerpt(){
    add_filter( 'excerpt_length', 'cmp_excerpt_global_length', 999 );
    if(cmp_get_option( 'exc_length_repair' )){
        $words = cmp_get_option( 'exc_length' ) ? cmp_get_option( 'exc_length' ) : 120;
        echo wp_trim_words( get_the_excerpt() , $words , ' ...' );
    }else{
        echo get_the_excerpt();
    }
}
function cmp_excerpt_home(){
    add_filter( 'excerpt_length', 'cmp_excerpt_home_length', 999 );
    if(cmp_get_option( 'exc_length_repair' )){
        $words = cmp_get_option( 'home_exc_length' ) ? cmp_get_option( 'home_exc_length' ) : 80;
        echo wp_trim_words( get_the_excerpt() , $words , ' ...' );
    }else{
        echo get_the_excerpt();
    }
}
/*-----------------------------------------------------------------------------------*/
# Read More Functions
/*-----------------------------------------------------------------------------------*/
function cmp_remove_excerpt( $more ) {
    return ' ...';
}
add_filter('excerpt_more', 'cmp_remove_excerpt');
/*-----------------------------------------------------------------------------------*/
# fixed_zh_CN_menus_screen_options
# http://www.wpdaxue.com/fixed-wordpress-zh_cn-menu-display-option.html
# Added in 2.7
/*-----------------------------------------------------------------------------------*/
function cmp_fixed_zh_CN_menus_screen_options( $translations, $text, $domain ){
    if( get_locale() == 'zh_CN' && $text == 'To add a custom link, <strong>expand the Custom Links section, enter a URL and link text, and click Add to Menu</strong>' && $domain == 'default' ){
        $translations = __('To add a custom link, <strong>expand the Custom Links section, enter a URL and link text, and click Add to Menu</strong>','wpdx');
    }
    return $translations;
}
if(cmp_get_option( 'menus_screen_repair' )){
    add_action( 'gettext', 'cmp_fixed_zh_CN_menus_screen_options', 10, 3 );
}
/*-----------------------------------------------------------------------------------*/
# Cmp_Category_Walker
# http://wordpress.stackexchange.com/questions/98755/how-can-i-customize-the-wp-list-categories
# Added in 2.8
/*-----------------------------------------------------------------------------------*/
class Cmp_Category_Walker extends Walker_Category {

    var $lev = -1;
    var $skip = 0;
    static $current_parent;

    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $this->lev = 0;
        $output .= "<ul>" . PHP_EOL;
    }

    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= "</ul>" . PHP_EOL;
        $this->lev = -1;
    }

    function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
        extract($args);
        $class = $cat_icon = $icon = $desc = '';
        $cat_name = esc_attr( $category->name );
        $cat_description = esc_attr( $category->category_description );
        $cat_icon = esc_attr( get_option('z_taxonomy_image' . $category->term_id));
        $class_current = $current_class ? $current_class . ' ' : 'current ';
        if ( ! empty($current_category) ) {
            $_current_category = get_term( $current_category, $category->taxonomy );
            if ( $category->term_id == $current_category ) $class = $class_current;
            elseif ( $category->term_id == $_current_category->parent ) $class = rtrim($class_current) . '-parent ';
        } else {
            $class = '';
        }
        if ( ! $category->parent ) {
            if ( ! get_term_children( $category->term_id, $category->taxonomy ) ) {
                $this->skip = 1;
            } else {
                if ($class == $class_current) self::$current_parent = $category->term_id;
                $output .= "<li class='" . $class . $level_class . "'>" . PHP_EOL;
                $output .= sprintf($parent_title_format, $cat_name) . PHP_EOL;
            }
        } else { 
            if ( $this->lev == 0 && $category->parent) {
                $link = get_term_link(intval($category->parent) , $category->taxonomy);
                $stored_parent = intval(self::$current_parent);
                $now_parent = intval($category->parent);
                $all_class = ($stored_parent > 0 && ( $stored_parent === $now_parent) ) ? $class_current . ' all' : 'all';
                //$output .= "<li class='" . $all_class . "'><a href='" . $link . "'>" . __('All') . "</a></li>\n";
                self::$current_parent = null;
            }
            $link = '<p class="cat-title"><a href="' . esc_url( get_term_link($category) ) . '" >' . $cat_name . '</a></p>';
            if($cat_description) $desc = '<p class="cat-description">'.$cat_description.'</p>';
            if($cat_icon) $icon = '<a href="' . esc_url( get_term_link($category) ) . '" ><img class="cat-icon" src="'.$cat_icon.'" alt="'.$cat_name.'"></a>';
            $output .= "<li";
            $class .= $category->taxonomy . '-item ' . $category->taxonomy . '-item-' . $category->term_id;
            $output .=  ' class="' . $class . '"';
            $output .= ">" .$icon. $link.$desc;
        }
    }

    function end_el( &$output, $page, $depth = 0, $args = array() ) {
        $this->lev++;
        if ( $this->skip == 1 ) {
            $this->skip = 0;
            return;
        }
        $output .= '<div class="clearfix"></div></li>' . PHP_EOL;
    }

}

function cmp_custom_list_categories( $args = '' ) {
  $defaults = array(
    'taxonomy' => 'category',
    'show_option_none' => '',
    'echo' => 1,
    'depth' => 5,
    'wrap_class' => '',
    'level_class' => '',
    'parent_title_format' => '%s',
    'current_class' => 'current'
  );
  $r = wp_parse_args( $args, $defaults );
  if ( ! isset( $r['wrap_class'] ) ) $r['wrap_class'] = ( 'category' == $r['taxonomy'] ) ? 'categories' : $r['taxonomy'];
  extract( $r );
  if ( ! taxonomy_exists($taxonomy) ) return false;
  $categories = get_categories( $r );
  $output = "<ul class='" . esc_attr( $wrap_class ) . "'>" . PHP_EOL;
  if ( empty( $categories ) ) {
    if ( ! empty( $show_option_none ) ) $output .= "<li>" . $show_option_none . "</li>" . PHP_EOL;
  } else {
    if ( is_category() || is_tax() || is_tag() ) {
      $current_term_object = get_queried_object();
      if ( $r['taxonomy'] == $current_term_object->taxonomy ) $r['current_category'] = get_queried_object_id();
    }
    $depth = $r['depth'];
    $walker = new Cmp_Category_Walker;
    $output .= $walker->walk($categories, $depth, $r);
  }
  $output .= "</ul>" . PHP_EOL;
  if ( $echo ) echo $output; else return $output;
}

function cmp_target_blank(){
    if(cmp_get_option( 'target_blank' )){
        return 'target="_blank"';
    }
}

/*-----------------------------------------------------------------------------------*/
# Custom post types for different conditions
/*-----------------------------------------------------------------------------------*/
function cmp_post_type_filter($query) {
    if ( !is_admin() ) {
        if ($query->is_author && cmp_get_option('post_types_for_author_archive')) {
            $author_types = cmp_get_option('post_types_for_author_archive');
            $query->set('post_type',  $author_types );
        }
        if ($query->is_search && cmp_get_option('post_types_for_search')) {
            $search_types = cmp_get_option('post_types_for_search');
            $query->set('post_type', $search_types );
        }
        if ($query->is_feed() && cmp_get_option('post_types_for_feed')) {
            $feed_types = cmp_get_option('post_types_for_feed');
            $query->set('post_type', $feed_types );
        }

        return $query;
    }
}
add_filter('pre_get_posts','cmp_post_type_filter',999,1);

/**
 * WordPress media showurl
 * http://www.wpdaxue.com/media-column-show-url.html
 */
add_filter( 'manage_media_columns', 'wpdaxue_media_column' );
function wpdaxue_media_column( $columns ) {
    $columns["media_url"] = "URL";
    return $columns;
}
add_action( 'manage_media_custom_column', 'wpdaxue_media_value', 10, 2 );
function wpdaxue_media_value( $column_name, $id ) {
    if ( $column_name == "media_url" ) echo '<input type="text" width="100%" onclick="jQuery(this).select();" value="'. wp_get_attachment_url( $id ). '" />';
}

/*-----------------------------------------------------------------------------------*/
# custom comment form
/*-----------------------------------------------------------------------------------*/
function custom_smilies_src($src, $img){
    return get_template_directory_uri().'/assets/images/smilies/' . $img;
}
add_filter('smilies_src', 'custom_smilies_src', 10, 2);
function add_my_smilies() {
    global $post; 
    if($post->post_type == 'post' || $post->post_type == 'page' ) get_template_part( 'includes/smilies');
}
if(cmp_get_option('smilies')){
    add_filter('comment_form_field_comment', 'add_my_smilies', 10, 2);
}
function my_update_comment_field($comment_field) {
    $placeholder = '';
    if(cmp_get_option('comment_placeholder')) $placeholder = 'placeholder="'.htmlspecialchars_decode(cmp_get_option('comment_placeholder') ).'"';
    $comment_field =
    '<p class="comment-form-comment">
        <textarea required '.$placeholder.'id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
    </p>';
    return $comment_field;
}
add_filter('comment_form_field_comment','my_update_comment_field',10,1);

function comment_form_url_filtered($fields){
    if(isset($fields['url']))
        unset($fields['url']);
    return $fields;
}
if(cmp_get_option('comment_url_filtered')){
    add_filter('comment_form_default_fields', 'comment_form_url_filtered',10,1);
}

function comment_form_top_ad(){
    global $post;
    if ( $post->post_type = 'post' || $post->post_type = 'page') cmp_banner('banner_top_form' , '<div class="gsfha3-form">' , '</div>' );
}
add_action('comment_form_top','comment_form_top_ad');

function comment_form_bottom_ad(){
    global $post;
    if ( $post->post_type = 'post' || $post->post_type = 'page') cmp_banner('banner_bottom_form' , '<div class="gthfdsa-form">' , '</div>' );
}
add_action('comment_form','comment_form_bottom_ad');

/*-----------------------------------------------------------------------------------*/
# Browser detection body_class() output
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'cmp_browser_body_class' ) ) {
    function cmp_browser_body_class( $classes ) {
        global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
        if($is_lynx){
            $classes[] = 'lynx';
        }elseif($is_gecko){
            $classes[] = 'gecko';
        }elseif($is_opera){
            $classes[] = 'opera';
        }elseif($is_NS4){
            $classes[] = 'ns4';
        }elseif($is_safari){
            $classes[] = 'safari';
        }elseif($is_chrome){
            $classes[] = 'chrome';
        }elseif($is_IE) {
            $browser = $_SERVER['HTTP_USER_AGENT'];
            $browser = substr( "$browser", 25, 8);
            if ($browser == "MSIE 7.0"  ) {
                $classes[] = 'ie7';
                $classes[] = 'ie';
            } elseif ($browser == "MSIE 6.0" ) {
                $classes[] = 'ie6';
                $classes[] = 'ie';
            } elseif ($browser == "MSIE 8.0" ) {
                $classes[] = 'ie8';
                $classes[] = 'ie';
            } elseif ($browser == "MSIE 9.0" ) {
                $classes[] = 'ie9';
                $classes[] = 'ie';
            } else {
                $classes[] = 'ie';
            }
        }else{
            $classes[] = 'unknown';
        }
        if( $is_iphone ) $classes[] = 'iphone';
        if(cmp_get_option('theme_layout') == 'vertical' ){
            $classes[] = 'style-vertical';
        } else {
            $classes[] = 'style-horizontal';
        }
        if(cmp_get_option('p_text_indent')) $classes[] = 'p-text-indent';
        return $classes;
    }
}
add_filter( 'body_class', 'cmp_browser_body_class' );
/**
 * Remove Js & Css Version
 * http://www.wpdaxue.com/remove-js-css-version.html
 */
function wpdaxue_remove_cssjs_ver( $src ) {
    if( strpos( $src, 'ver='. get_bloginfo( 'version' ) ) ){
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}
add_filter( 'style_loader_src', 'wpdaxue_remove_cssjs_ver', 999 );
add_filter( 'script_loader_src', 'wpdaxue_remove_cssjs_ver', 999 );

/*-----------------------------------------------------------------------------------*/
# Disable WordPress Admin Bar for all users but admins.
/*-----------------------------------------------------------------------------------*/
if(cmp_get_option('hide_toolbar')){
    show_admin_bar(false);
}

/*-----------------------------------------------------------------------------------*/
# get_first_post_date
/*-----------------------------------------------------------------------------------*/
function get_first_post_date($format = "Y-m-d")
{
    $ax_args = array
    (
        'numberposts' => -1,
        'post_status' => 'publish',
        'order' => 'ASC'
        );
    $ax_get_all = get_posts($ax_args);
    $ax_first_post = $ax_get_all[0];
    $ax_first_post_date = $ax_first_post->post_date;
    $output = date($format, strtotime($ax_first_post_date));
    return $output;
}
/*-----------------------------------------------------------------------------------*/
# Comment Must Contain Chinese
/*-----------------------------------------------------------------------------------*/
function wpdaxue_comment_post( $incoming_comment ) {
    $pattern = '/[一-龥]/u';
    $text = '/['.trim(cmp_get_option( 'sensitive_character' )).']/u';
    if(cmp_get_option( 'comment_chinese' ) && !preg_match($pattern, $incoming_comment['comment_content'])) {
        wp_die( __("Your comment must contain Chinese.",'wpdx' ));
    }
    if(cmp_get_option( 'comment_sensitive' ) && preg_match($text, $incoming_comment['comment_content'])) {
        wp_die( __("Comments are not allowed sensitive character.",'wpdx' ) );
    }
    return( $incoming_comment );
}
add_filter('preprocess_comment', 'wpdaxue_comment_post');

/*-----------------------------------------------------------------------------------*/
# Custom Gravatar
/*-----------------------------------------------------------------------------------*/
function cmp_custom_gravatar ($avatar) {
    $cmp_gravatar = cmp_get_option( 'gravatar' );
    if($cmp_gravatar){
        $custom_avatar = cmp_get_option( 'gravatar' );
        $avatar[$custom_avatar] = "Custom Gravatar";
    }
    return $avatar;
}
add_filter( 'avatar_defaults', 'cmp_custom_gravatar' );


/*-----------------------------------------------------------------------------------*/
# Custom Favicon
/*-----------------------------------------------------------------------------------*/
function cmp_favicon() {
    $default_favicon = get_template_directory_uri()."/favicon.ico";
    $custom_favicon = cmp_get_option('favicon');
    $favicon = (empty($custom_favicon)) ? $default_favicon : $custom_favicon;
    echo '<link rel="shortcut icon" href="'.$favicon.'" title="Favicon" />';
}
add_action('wp_head', 'cmp_favicon');


/*-----------------------------------------------------------------------------------*/
# no self ping
/*-----------------------------------------------------------------------------------*/
function no_self_ping(&$links) {
    $home = home_url();
    foreach ($links as $l => $link ){
        if (0 === strpos($link, $home)){
            unset($links[$l]);
        }
    }
}
add_action( 'pre_ping', 'no_self_ping' );

/*-----------------------------------------------------------------------------------*/
# remove wptexturize
/*-----------------------------------------------------------------------------------*/
remove_filter('the_content', 'wptexturize');
remove_filter('the_excerpt', 'wptexturize');
remove_filter('comment_text', 'wptexturize');
/*-----------------------------------------------------------------------------------*/
# image_default_link_type as file
/*-----------------------------------------------------------------------------------*/
update_option('image_default_link_type' , 'file');
/*-----------------------------------------------------------------------------------*/
# clean head
/*-----------------------------------------------------------------------------------*/
function cmp_remove_version() {
    return '';
}
add_filter('the_generator', 'cmp_remove_version');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action('wp_head', 'rel_canonical' );

/*-----------------------------------------------------------------------------------*/
# color tags Cloud
/*-----------------------------------------------------------------------------------*/
function colorCloud($text) {
    $text = preg_replace_callback('|<a (.+?)>|i','colorCloudCallback', $text);
    return $text;
}
function colorCloudCallback($matches) {
    $text = $matches[1];
    $color = dechex(rand(0,16777215));
    $pattern = '/style=(\'|\")(.*)(\'|\")/i';
    $text = preg_replace($pattern, "style=\"color:#{$color};$2;\"", $text);
    return "<a $text>";
}
add_filter('wp_tag_cloud', 'colorCloud', 1);

/*-----------------------------------------------------------------------------------*/
# anti_spam 垃圾评论拦截
/*-----------------------------------------------------------------------------------*/
if (cmp_get_option('anti_spam')){
    class anti_spam {
        function __construct() {
            if ( !is_user_logged_in() ) {
                add_action('template_redirect', array($this, 'w_tb'), 1);
                add_action('pre_comment_on_post', array($this, 'gate'), 1);
                add_action('preprocess_comment', array($this, 'sink'), 1);
            }
        }
        function w_tb() {
            if ( is_singular() ) {
                ob_start(create_function('$input', 'return preg_replace("#textarea(.*?)name=([\"\'])comment([\"\'])(.+)/textarea>#",
                    "textarea$1name=$2w$3$4/textarea><textarea name=\"comment\" cols=\"60\" rows=\"4\" style=\"display:none\"></textarea>", $input);') );
            }
        }
        function gate() {
            ( !empty($_POST['w']) && empty($_POST['comment']) ) ? $_POST['comment'] = $_POST['w'] : $_POST['spam_confirmed'] = 1;
        }
        function sink( $comment ) {
            if ( !empty($_POST['spam_confirmed']) ) {
                die();
            //add_filter('pre_comment_approved', create_function('', 'return "spam";'));
            //$comment['comment_content'] = "[Spam! ]\n" . $comment['comment_content'];
            }
            return $comment;
        }
    }
    $anti_spam = new anti_spam();
}
/*-----------------------------------------------------------------------------------*/
# Custom mail from
/*-----------------------------------------------------------------------------------*/
function cmp_from_email($email) {
    if (cmp_get_option('from_email')) {
        $wp_from_email = cmp_get_option('from_email');
    }else{
        $wp_from_email = get_option('admin_email');
    }
    return $wp_from_email;
}
function cmp_mail_from_name($email){
    if (cmp_get_option('from_name')) {
        $wp_from_email = cmp_get_option('from_name');
    }else{
        $wp_from_name = get_option('blogname');
    }
    return $wp_from_name;
}
// add_filter('wp_mail_from', 'cmp_from_email');
// add_filter('wp_mail_from_name', 'cmp_mail_from_name');

/*-----------------------------------------------------------------------------------*/
# Page Navigation
/*-----------------------------------------------------------------------------------*/
function cmp_pagenavi(){
    echo '<div class="page-nav">';
    echo cmp_get_pagenavi();
    echo '</div>
    <div class="clear"></div>';
}

/*-----------------------------------------------------------------------------------*/
# Get Most Comments posts
/*-----------------------------------------------------------------------------------*/
function most_comm_posts($mode = '', $nums=10 , $days=7, $exclude = '', $display = true) {
    global $wpdb;
    $today = date("Y-m-d H:i:s");
    $daysago = date( "Y-m-d H:i:s", strtotime($today) - ($days * 24 * 60 * 60) );
    $where = '';
    $category_sql = '';
    if(!empty($mode) && $mode != 'both') {
        if(is_array($mode)) {
            $mode = implode("','",$mode);
            $where = "post_type IN ('".$mode."')";
        } else {
            $where = "post_type = '$mode'";
        }
    } else {
        $where = '1=1';
    }
    if(!empty($exclude)) {
        if(is_array($exclude)) {
            $category_sql = "$wpdb->term_taxonomy.term_id NOT IN (".join(',', $exclude).')';
        } else {
            $category_sql = "$wpdb->term_taxonomy.term_id NOT IN (".$exclude.')';
        }
        $result = $wpdb->get_results("SELECT comment_count, ID, post_title, post_date FROM $wpdb->posts INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id) WHERE post_date BETWEEN '$daysago' AND '$today' AND $wpdb->term_taxonomy.taxonomy = 'category' AND $category_sql AND $where AND post_status = 'publish' ORDER BY comment_count DESC LIMIT 0 , $nums");
    }else{
        $result = $wpdb->get_results("SELECT comment_count, ID, post_title, post_date FROM $wpdb->posts WHERE post_date BETWEEN '$daysago' AND '$today' AND $where AND post_status = 'publish' ORDER BY comment_count DESC LIMIT 0 , $nums");
    }
    $output = '';
    if(empty($result)) {
        $output = '<li>'.__('No items in the selected time period.', 'wpdx').'</li>'."\n";
    } else {
        foreach ($result as $topten) {
            $postid = $topten->ID;
            $title = $topten->post_title;
            $commentcount = $topten->comment_count;
            if ($commentcount != 0) {
                if($display) {
                    $output .= '<li><span>'.$commentcount.' ℃</span><a href="'.get_permalink($postid).'" title="'.$title.'" '.cmp_target_blank() .'><i class="fa fa-angle-right"></i>'.$title.'</a></li>';
                } else {
                    $output .= '<li><a href="'.get_permalink($postid).'" title="'.$title.'" '.cmp_target_blank() .'><i class="fa fa-angle-right"></i>'.$title.'</a></li>';
                }
            }
        }
    }
    echo $output;
}
/*-----------------------------------------------------------------------------------*/
# Get TimeSpan Most Viewed posts
/*-----------------------------------------------------------------------------------*/
function most_viewed_posts($mode = '', $limit = 8, $days = 7, $exclude = '', $display = true) {
    global $wpdb, $post;
    $limit_date = current_time('timestamp') - ($days*86400);
    $limit_date = date("Y-m-d H:i:s",$limit_date);
    $where = '';
    $temp = '';
    $category_sql = '';
    if(!empty($mode) && $mode != 'both') {
        if(is_array($mode)) {
            $mode = implode("','",$mode);
            $where = "post_type IN ('".$mode."')";
        } else {
            $where = "post_type = '$mode'";
        }
    } else {
        $where = '1=1';
    }
    if(!empty($exclude)) {
        if(is_array($exclude)) {
            $category_sql = "$wpdb->term_taxonomy.term_id NOT IN (".join(',', $exclude).')';
        } else {
            $category_sql = "$wpdb->term_taxonomy.term_id NOT IN (".$exclude.')';
        }
        $most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id) WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $wpdb->term_taxonomy.taxonomy = 'category' AND $category_sql AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");
    }else{
        $most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");
    }
    if($most_viewed) {
        foreach ($most_viewed as $post) {
            $post_title = get_the_title();
            $post_views = intval($post->views);
            $post_views = number_format($post_views);
            if($display) {
                $temp .= '<li class="most-view"><span>'.$post_views.' ℃</span><a href="'.get_permalink().'" '.cmp_target_blank() .'><i class="fa fa-angle-right"></i>'.$post_title.'</a></li>';
            } else {
                $temp .= '<li><a href="'.get_permalink().'" '.cmp_target_blank().'><i class="fa fa-angle-right"></i>'.$post_title.'</a></li>';
            }
        }
    } else {
        $temp = '<li>'.__('No items in the selected time period.', 'wpdx').'</li>'."\n";
    }
    echo $temp;
}
/**
 * cmp_get_url_by_shortcode
 * @param  [type] $shortcode [description]
 * @return [type]            [description]
 */
function cmp_get_page_id_by_shortcode($shortcode) {
    global $wpdb;
    $id = '';
    $sql = 'SELECT ID FROM ' . $wpdb->posts . ' WHERE post_type = "page" AND post_status="publish" AND post_content LIKE "%' . $shortcode . '%"';
    $var = $wpdb->get_var($sql);
    if ($var) {
        $id = $var;
    }
    return $id;
}

/**
 * [cmp_add_admin_bar_menu description]
 * @param  [type] &$wp_admin_bar [description]
 * @since 3.2
 */
function cmp_add_admin_bar_menu( &$wp_admin_bar )
{
    if(has_nav_menu('user-menu')){
        $menu = wp_get_nav_menu_object( 'user-menu' );
        $menu_items = wp_get_nav_menu_items( $menu->term_id );
        if(is_array($menu_items) && !empty($menu_items)){
            $wp_admin_bar->add_menu( array(
                'id' => 'user-menu-0',
                'title' => __('Frontend User Menu','wpdx'),
                ) );
            foreach ( $menu_items as $menu_item ) {
                $wp_admin_bar->add_menu( array(
                    'id' => 'user-menu-' . $menu_item->ID,
                    'parent' => 'user-menu-' . $menu_item->menu_item_parent,
                    'title' => $menu_item->title,
                    'href' => $menu_item->url,
                    'meta' => array(
                        'title' => $menu_item->attr_title,
                        'target' => '_blank',
                        'class' => implode( ' ', $menu_item->classes ),
                        ),
                    ) );
            }
        }
    }
}
add_action( 'admin_bar_menu', 'cmp_add_admin_bar_menu',999 );
/**
 * [cmp_get_first_image description]
 * @return [type] [description]
 * @since 3.3
 */
function cmp_get_first_image(){
    global $post;
    $first_image_src = '';
    if( $values = get_post_custom_values("thumb") ) {
        $values = get_post_custom_values("thumb");
        $first_image_src = $values[0];
    } elseif( has_post_thumbnail() ){
        $first_image_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $first_image_src = $first_image_src[0];
    } else {
        ob_start();
        ob_end_clean();
        $output = preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/Ui', $post->post_content, $matches);
        if(isset($matches[1])) $first_image_src = $matches[1];
    }
    return $first_image_src;
}

/**
 * [cmp_get_post_type_name description]
 * @param  [type] $post_type [description]
 * @return [type]            [description]
 * @since 3.3
 */
if(!function_exists('cmp_get_post_type_name')){
    function cmp_get_post_type_name($post_type){
        $obj = get_post_type_object( $post_type );
        return $obj->labels->singular_name;
    }
}

/**
 * WordPress Add Additional columns to User list
 * http://www.wpdaxue.com/add-user-nickname-column.html
 * @since 3.3
 */
if(cmp_get_option('add_user_columns')){
    // store login ip
    add_action('user_register', 'cmp_log_ip', 99 ,1);
    function cmp_log_ip($user_id){
        $ip = $_SERVER['REMOTE_ADDR'];
        update_user_meta($user_id, 'signup_ip', $ip);
    }
    // store last login info
    add_action( 'wp_login', 'cmp_insert_last_login',99,1 );
    function cmp_insert_last_login( $login ) {
        global $user_id;
        $user = get_user_by( 'login', $login );
        update_user_meta( $user->ID, 'last_login', current_time( 'mysql' ) );
        $last_login_ip = $_SERVER['REMOTE_ADDR'];
        update_user_meta( $user->ID, 'last_login_ip', $last_login_ip);
    }
    // add column
    add_filter('manage_users_columns', 'cmp_add_user_additional_column',99,1);
    function cmp_add_user_additional_column($columns) {
        $columns['user_nickname'] = __('Nickname','wpdx');
        $columns['user_url'] = __('Website','wpdx');
        $columns['reg_time'] = __('Registration','wpdx');
        $columns['last_login'] = __('Last login','wpdx');
        unset($columns['name']);
        return $columns;
    }
    //display column content
    add_action('manage_users_custom_column',  'cmp_show_user_additional_column_content', 99, 3);
    function cmp_show_user_additional_column_content($value, $column_name, $user_id) {
        $user = get_userdata( $user_id );
        if ( 'user_nickname' == $column_name )
            return $user->nickname;
        if ( 'user_url' == $column_name )
            return '<a href="'.$user->user_url.'" target="_blank">'.$user->user_url.'</a>';
        if('reg_time' == $column_name ){
            return get_date_from_gmt($user->user_registered) .'<br />'.get_user_meta( $user->ID, 'signup_ip', true);
        }
        if ( 'last_login' == $column_name && $user->last_login ){
            return get_user_meta( $user->ID, 'last_login', 'ture' ).'<br />'.get_user_meta( $user->ID, 'last_login_ip', 'ture' );
        }
        return $value;
    }
    // sort by reg_time
    add_filter( "manage_users_sortable_columns", 'cmp_users_sortable_columns',999,1 );
    function cmp_users_sortable_columns($sortable_columns){
        $sortable_columns['reg_time'] = 'reg_time';
        return $sortable_columns;
    }
    add_action( 'pre_user_query', 'cmp_users_search_order',999,1 );
    function cmp_users_search_order($obj){
        if(!isset($_REQUEST['orderby']) || $_REQUEST['orderby']=='reg_time' ){
            if( !@in_array($_REQUEST['order'],array('asc','desc')) ){
                $_REQUEST['order'] = 'desc';
            }
            $obj->query_orderby = "ORDER BY user_registered ".$_REQUEST['order']."";
        }
    }
} //if(cmp_get_option('add_user_columns'))

/**
 * [cmp_check_if_new_post description]
 * @param  integer $hours [description]
 * @return [bool]         [description]
 * @since 3.4
 */
function cmp_check_if_new_post($hours = 24){
    if($hours == 0) return false;
    global $post;
    $t1=$post->post_date;
    $t2=date("Y-m-d H:i:s");
    $diff=(strtotime($t2)-strtotime($t1))/3600;
    if( $diff < $hours ){
        return true;
    }
}
