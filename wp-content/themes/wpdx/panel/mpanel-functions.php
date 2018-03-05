<?php
/*-----------------------------------------------------------------------------------*/
# Show Categories on setting page
/*-----------------------------------------------------------------------------------*/
function show_category() {
    global $wpdb;
    $request = "SELECT $wpdb->terms.term_id, name FROM $wpdb->terms ";
    $request .= " LEFT JOIN $wpdb->term_taxonomy ON $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id ";
    $request .= " WHERE $wpdb->term_taxonomy.taxonomy = 'category' ";
    $request .= " ORDER BY term_id asc";
    $categorys = $wpdb->get_results($request);
    foreach ($categorys as $category) {
        $output = '<span>'.$category->name."(<em>".$category->term_id.'</em>)</span>';
        echo $output;
    }
}
/*-----------------------------------------------------------------------------------*/
# Custom Admin Bar Menus
/*-----------------------------------------------------------------------------------*/
function cmp_admin_bar() {
    global $wp_admin_bar;
    if ( current_user_can( 'manage_options' ) ){
        $wp_admin_bar->add_menu( array(
            'parent' => 0,
            'id' => 'mpanel_page',
            'title' => THEME_NAME .' '. THEME_VER ,
            'href' => admin_url( 'admin.php?page=panel')
            ) );
    }
}
add_action( 'wp_before_admin_bar_render', 'cmp_admin_bar' );
/*-----------------------------------------------------------------------------------*/
# Register main Scripts and Styles
/*-----------------------------------------------------------------------------------*/
function cmp_admin_register() {
    wp_register_script( 'cmp-admin-slider', get_template_directory_uri() . '/panel/js/jquery.ui.slider.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-mouse', 'jquery-ui-sortable' ) , THEME_VER , false );
    wp_register_script( 'cmp-admin-checkbox', get_template_directory_uri() . '/panel/js/checkbox.min.js', array( 'jquery' ) , THEME_VER , false );
    wp_register_script( 'cmp-admin-main', get_template_directory_uri() . '/panel/js/cmp.js', array( 'jquery' ) , THEME_VER , false );
    wp_register_style( 'cmp-style', get_template_directory_uri().'/panel/style.css', array(), THEME_VER , 'all' );

    if ( isset( $_GET['page'] ) && $_GET['page'] == 'panel' ) {
        wp_enqueue_script( 'cmp-admin-slider' );
        wp_enqueue_script( 'cmp-admin-checkbox' );
    }
    wp_enqueue_script( 'cmp-admin-main' );
    wp_localize_script( 'cmp-admin-main', 'cmp_var', array(
        'who' => __( 'Who can see this module:', 'wpdx' ),
        'anyone' => __( 'Anyone', 'wpdx' ),
        'logged' => __( 'Only logged in users', 'wpdx' ),
        'anonymous' => __( 'Only anonymous', 'wpdx' ),
        'news_box' => __( 'News Box', 'wpdx' ),
        'edd_news_box' => __( 'EDD News Box', 'wpdx' ),
        'tabs_box' => __( 'Categories Tabs Box', 'wpdx' ),
        'edd_tabs_box' => __( 'EDD Categories Tabs Box', 'wpdx' ),
        'category' => __( 'Box Category :', 'wpdx' ),
        'order'    => __( 'Posts Order :', 'wpdx' ),
        'recent'    => __( 'Recent Posts', 'wpdx' ),
        'latest' => __( 'Latest Posts', 'wpdx' ),
        'modified' => __( 'Last Modified', 'wpdx' ),
        'random' => __( 'Random Posts', 'wpdx' ),
        'stick'    => __( 'Sticky Posts', 'wpdx' ),
        'number' => __( 'Number of posts to show :', 'wpdx' ),
        'thumb' => __( 'Thumb/Avatar :', 'wpdx' ),
        'thumb_t' => __( 'Display thumb', 'wpdx' ),
        'thumb_a' => __( 'Display avatar', 'wpdx' ),
        'thumb_n' => __( 'Just title', 'wpdx' ),
        'bstyle' => __( 'Box Style :', 'wpdx' ),
        'scroll_box' => __( 'Scrolling Box', 'wpdx' ),
        'edd_scroll_box' => __( 'EDD Scrolling Box', 'wpdx' ),
        'rrh_posts' => __( 'Recent/Random/Hot Posts', 'wpdx' ),
        'post_type' => __('Select Custom Post Type :','wpdx'),
        'post_type_tip' => __('Hold down the [ctrl] key to select or deselect multiple post types.','wpdx'),
        'exclude' => __('Exclude Categories :','wpdx'),
        'exclude_tip' => __('Hold down the [ctrl] key to select or deselect multiple categories.','wpdx'),
        'choose_cat' => __('Choose Categories :','wpdx'),
        'choose_cat_tip' => __('Hold down the [ctrl] key to select or deselect multiple categories.','wpdx'),
        'box_title' => __('Box Title :','wpdx'),
        'most_comment' => __('Most Comment','wpdx'),
        'most_viewed' => __('Most Viewed','wpdx'),
        'days' => __('Days limit of popular :','wpdx'),
        'days_tip' => __('Only when the Order is Most Comment or Most Viewed, this option is to take effect.','wpdx'),
        'hours' => __('Highlight posts\'s date for X hours :','wpdx'),
        'hours_tip' => __('Default is 24 hours. Set 0 to disable.','wpdx'),
        'ads' => __('ADS','wpdx'),
        'new_pic' => __('News In Picture ','wpdx'),
        'edd_new_pic' => __('EDD News In Picture ','wpdx'),
        'show_title' => __('Show Title :','wpdx'),
        'divider' => __('Divider','wpdx'),
        'divider_tip' => __('This module can repair the dislocation problem of "Three modules in parallel", please add this module at the top of the first one.','wpdx'),
        'post_ids' => __('Special Post Ids :','wpdx'),
        'post_ids_tip' => __('You can enter the posts ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7); or leave it blank, and then set the following options to query the psots.','wpdx'),
        'icon' => __('Icon :','wpdx'),
        'more_text' => __('More Text :','wpdx'),
        'more_text_detail' => __('More Posts','wpdx'),
        'more_url' => __('More Url :','wpdx'),
        'users' => __('User IDs :','wpdx'),
        'users_tip' => __('You can enter the user ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7).ID number should be a multiple of 10, such as 10, 20 or 30 ids.','wpdx'),
        'del_cat' => __('Delete this module.','wpdx'),
        'choose_image' => __( 'Choose Images', 'wpdx' ),
        'celect' => __('Select','wpdx')
        )
    );
    wp_enqueue_style( 'cmp-style' );

}
add_action( 'admin_enqueue_scripts', 'cmp_admin_register' );
/*-----------------------------------------------------------------------------------*/
# To change Insert into Post Text
/*-----------------------------------------------------------------------------------*/
function cmp_options_setup() {
    global $pagenow;
    if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow )
        add_filter( 'gettext', 'cmp_replace_thickbox_text'  , 1, 3 );
}
add_action( 'admin_init', 'cmp_options_setup' );
function cmp_replace_thickbox_text($translated_text, $text, $domain) {
    if ('Insert into Post' == $text) {
        $referer = strpos( wp_get_referer(), 'cmp-settings' );
        if ( $referer != '' )
            return __('Use this image', 'wpdx' );
    }
    return $translated_text;
}
/*-----------------------------------------------------------------------------------*/
# Clean options before store it in DB
/*-----------------------------------------------------------------------------------*/
function cmp_clean_options(&$value) {
    $value = htmlspecialchars(stripslashes($value));
}
/*-----------------------------------------------------------------------------------*/
# Options Array
/*-----------------------------------------------------------------------------------*/
$array_options =
array(
    "cmp_home_cats",
    "cmp_options"
    );
/*-----------------------------------------------------------------------------------*/
# Save Theme Settings
/*-----------------------------------------------------------------------------------*/
function cmp_save_settings ( $data , $refresh = 0 ) {
    global $array_options ;
    foreach( $array_options as $option ){
        if( isset( $data[$option] )){
            array_walk_recursive( $data[$option] , 'cmp_clean_options');
            update_option( $option ,  $data[$option]   );
        }
        elseif( !isset( $data[$option] ) && $option != 'cmp_options' ){
            delete_option($option);
        }
    }
    if( $refresh == 2 )  die('2');
    elseif( $refresh == 1 ) die('1');
}
/*-----------------------------------------------------------------------------------*/
# Save Options
/*-----------------------------------------------------------------------------------*/
add_action('wp_ajax_test_theme_data_save', 'cmp_save_ajax');
function cmp_save_ajax() {
    check_ajax_referer('test-theme-data', 'security');
    $data = $_POST;
    $refresh = 1;
    if( $data['cmp_import'] ){
        $refresh = 2;
        $data = unserialize(base64_decode( $data['cmp_import'] ));
    }
    cmp_save_settings ($data , $refresh );
}
/*-----------------------------------------------------------------------------------*/
# Add Panel Page
/*-----------------------------------------------------------------------------------*/
function cmp_add_admin() {
    $current_page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';
    $icon = 'dashicons-art';
    add_menu_page(THEME_NAME.__( ' Settings','wpdx'), THEME_NAME ,'edit_theme_options', 'panel' , 'panel_options', $icon  );
    $theme_page = add_submenu_page('panel',THEME_NAME.__( ' Settings','wpdx'), THEME_NAME.__( 'Settings','wpdx'),'edit_theme_options', 'panel' , 'panel_options');

    add_action( 'admin_head-'. $theme_page, 'cmp_admin_head' );
    function cmp_admin_head(){
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                jQuery('.on-of').checkbox({empty:'<?php echo get_template_directory_uri(); ?>/panel/images/empty.png'});
                jQuery('form#cmp_form').submit(function() {
                    var data = jQuery(this).serialize();
                    jQuery.post(ajaxurl, data, function(response) {
                        if(response == 1) {
                            jQuery('#save-alert').addClass('save-done');
                            t = setTimeout('fade_message()', 1000);
                        }
                        else if( response == 2 ){
                            location.reload();
                        }
                        else {
                            jQuery('#save-alert').addClass('save-error');
                            t = setTimeout('fade_message()', 1000);
                        }
                    });
                    return false;
                });
            });
            function fade_message() {
                jQuery('#save-alert').fadeOut(function() {
                    jQuery('#save-alert').removeClass('save-done');
                });
                clearTimeout(t);
            }
            jQuery(function() {
                jQuery( "#cat_sortable" ).sortable({placeholder: "ui-state-highlight"});
                jQuery( "#customList" ).sortable({placeholder: "ui-state-highlight"});
                jQuery( "#tabs_cats" ).sortable({placeholder: "ui-state-highlight"});
            });
        </script>
        <?php
        // wp_print_scripts('media-upload');
        // wp_enqueue_script('thickbox');
        // wp_enqueue_style('thickbox');
        wp_enqueue_media();
        do_action('admin_print_styles');
    }
    if( isset( $_REQUEST['action'] ) ){
        if( 'reset' == $_REQUEST['action']  && $current_page == 'panel' && check_admin_referer('reset-action-code' , 'resetnonce') ) {
            global $default_data;
            cmp_save_settings( $default_data );
            header("Location: admin.php?page=panel&reset=true");
            die;
        }
    }
}
/*-----------------------------------------------------------------------------------*/
# Add Options
/*-----------------------------------------------------------------------------------*/
function cmp_options($value){
    global $options_fonts;
    ?>
    <div class="option-item" id="<?php echo $value['id'] ?>-item">
        <span class="label"><?php  echo $value['name']; ?></span>
        <?php
        switch ( $value['type'] ) {
            case 'text': ?>
            <input  name="cmp_options[<?php echo $value['id']; ?>]" id="<?php  echo $value['id']; ?>" type="text" value="<?php echo cmp_get_option( $value['id'] ); ?>" />
            <?php if( isset( $value['extra_text'] ) ) : ?><span class="extra-text"><?php echo $value['extra_text'] ?></span><?php endif; ?>
            <?php
            if( $value['id']=="slider_tag" || $value['id']=="breaking_tag"){
                $tags = get_tags('orderby=count&order=desc&number=50'); ?>
                <a style="cursor:pointer" title="<?php _e('Choose from the most used tags','wpdx'); ?>" onclick="toggleVisibility('<?php echo $value['id']; ?>_tags');"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/expand.png" alt="" /></a>
                <span class="tags-list" id="<?php echo $value['id']; ?>_tags">
                    <?php foreach ($tags as $tag){?>
                    <a style="cursor:pointer" onclick="if(<?php echo $value['id'] ?>.value != ''){ var sep = ' , '}else{var sep = ''} <?php echo $value['id'] ?>.value=<?php echo $value['id'] ?>.value+sep+(this.rel);" rel="<?php echo $tag->name ?>"><?php echo $tag->name ?></a>
                    <?php } ?>
                </span>
                <?php } ?>
                <?php
                break;
                case 'arrayText':  $currentValue = cmp_get_option( $value['id'] );?>
                <input  name="cmp_options[<?php echo $value['id']; ?>][<?php echo $value['key']; ?>]" id="<?php  echo $value['id']; ?>[<?php echo $value['key']; ?>]" type="text" value="<?php echo $currentValue[$value['key']] ?>" />
                <?php
                break;
                case 'short-text': ?>
                <input style="width:50px" name="cmp_options[<?php echo $value['id']; ?>]" id="<?php  echo $value['id']; ?>" type="text" value="<?php echo cmp_get_option( $value['id'] ); ?>" />
                <?php
                break;
                case 'checkbox':
                if(cmp_get_option($value['id'])){$checked = "checked=\"checked\"";  } else{$checked = "";} ?>
                <input class="on-of" type="checkbox" name="cmp_options[<?php echo $value['id'] ?>]" id="<?php echo $value['id'] ?>" value="true" <?php echo $checked; ?> />
                <?php
                break;
                case 'radio':
                ?>
                <div style="float:left; width: 295px;">
                    <?php foreach ($value['options'] as $key => $option) { ?>
                    <label style="display:block; margin-bottom:8px;"><input name="cmp_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" type="radio" value="<?php echo $key ?>" <?php if ( cmp_get_option( $value['id'] ) == $key) { echo ' checked="checked"' ; } ?>> <?php echo $option; ?></label>
                    <?php } ?>
                </div>
                <?php
                break;

                case 'radio-img':
                ?>
                <label>
                    <ul class="cmp-cats-options cmp-options">
                    <?php foreach ($value['options'] as $key => $option) { ?>
                        <li class="radio-img">
                          <input id="<?php echo $value['id']; ?>" name="cmp_options[<?php echo $value['id']; ?>]" type="radio" value="<?php echo $key ?>" <?php if ( cmp_get_option( $value['id'] ) == $key) { echo ' checked="checked"' ; } ?> />
                          <a class="checkbox-select" href="#"><?php echo $option; ?></a>
                        </li>
                    <?php } ?>
                    </ul>
                    <div class="clear"></div>
                </label>
                <?php
                break;

                case 'select':
                ?>
                <select name="cmp_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>">
                    <?php foreach ($value['options'] as $key => $option) { ?>
                    <option value="<?php echo $key ?>" <?php if ( cmp_get_option( $value['id'] ) == $key) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
                    <?php } ?>
                </select>
                <?php
                break;

                case 'multiple':
                ?>

                <select multiple="multiple" name="cmp_options[<?php echo $value['id']; ?>][]" id="<?php echo $value['id']; ?>[]">
                    <?php foreach ($value['options'] as $key => $option) {?>
                    <option value="<?php echo $key ?>" <?php if ( @in_array( $key , cmp_get_option( $value['id'] ) )) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
                    <?php } ?>
                </select>
                <?php
                break;

                case 'textarea':
                ?>
                <textarea style="direction:ltr; text-align:left" name="cmp_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" type="textarea" cols="100%" rows="3" tabindex="4"><?php echo cmp_get_option( $value['id'] );  ?></textarea>
                <?php
                break;
                case 'upload':
                ?>
                <input id="<?php echo $value['id']; ?>" class="img-path" type="text" size="56" style="direction:ltr; text-laign:left" name="cmp_options[<?php echo $value['id']; ?>]" value="<?php echo cmp_get_option($value['id']); ?>" />
                <input id="upload_<?php echo $value['id']; ?>_button" type="button" class="small_button" value="<?php _e( 'Upload', 'wpdx' ); ?>" />
                <div id="<?php echo $value['id']; ?>-preview" class="img-preview" <?php if(!cmp_get_option( $value['id'] )) echo 'style="display:none;"' ?>>
                    <img src="<?php if(cmp_get_option( $value['id'] )) echo cmp_get_option( $value['id'] ); else echo get_template_directory_uri().'/panel/images/spacer.png'; ?>" alt="" />
                    <a class="del-img" title="<?php _e( 'Delete', 'wpdx' ); ?>"></a>
                </div>
                <script type='text/javascript'>
                    jQuery('#<?php echo $value['id']; ?>').change(function(){
                        jQuery('#<?php echo $value['id']; ?>-preview').show();
                        jQuery('#<?php echo $value['id']; ?>-preview img').attr("src", jQuery(this).val());
                    });
                    cmp_set_uploader( '<?php echo $value['id']; ?>' );
                </script>
                <?php
                break;
                case 'slider':
                ?>
                <div id="<?php echo $value['id']; ?>-slider"></div>
                <input type="text" id="<?php echo $value['id']; ?>" value="<?php echo cmp_get_option($value['id']); ?>" name="cmp_options[<?php echo $value['id']; ?>]" style="width:50px;" /> <?php echo $value['unit']; ?>
                <script>
                    jQuery(document).ready(function() {
                        jQuery("#<?php echo $value['id']; ?>-slider").slider({
                            range: "min",
                            min: <?php echo $value['min']; ?>,
                            max: <?php echo $value['max']; ?>,
                            value: <?php if( cmp_get_option($value['id']) ) echo cmp_get_option($value['id']); else echo 0; ?>,
                            slide: function(event, ui) {
                                jQuery('#<?php echo $value['id']; ?>').attr('value', ui.value );
                            }
                        });
                    });
                </script>
                <?php
                break;
                case 'background':
                $current_value = cmp_get_option($value['id']);
                ?>
                <input id="<?php echo $value['id']; ?>-img" class="img-path" type="text" size="56" style="direction:ltr; text-align:left" name="cmp_options[<?php echo $value['id']; ?>][img]" value="<?php echo $current_value['img']; ?>" />
                <input id="upload_<?php echo $value['id']; ?>_button" type="button" class="small_button" value="Upload" />
                <div style="margin-top:15px; clear:both">
                    <div id="<?php echo $value['id']; ?>colorSelector" class="color-pic"><div style="background-color:<?php echo $current_value['color'] ; ?>"></div></div>
                    <input style="width:80px; margin-right:5px;"  name="cmp_options[<?php echo $value['id']; ?>][color]" id="<?php  echo $value['id']; ?>color" type="text" value="<?php echo $current_value['color'] ; ?>" />
                    <select name="cmp_options[<?php echo $value['id']; ?>][repeat]" id="<?php echo $value['id']; ?>[repeat]" style="width:96px;">
                        <option value="" <?php if ( !$current_value['repeat'] ) { echo ' selected="selected"' ; } ?>></option>
                        <option value="repeat" <?php if ( $current_value['repeat']  == 'repeat' ) { echo ' selected="selected"' ; } ?>>repeat</option>
                        <option value="no-repeat" <?php if ( $current_value['repeat']  == 'no-repeat') { echo ' selected="selected"' ; } ?>>no-repeat</option>
                        <option value="repeat-x" <?php if ( $current_value['repeat'] == 'repeat-x') { echo ' selected="selected"' ; } ?>>repeat-x</option>
                        <option value="repeat-y" <?php if ( $current_value['repeat'] == 'repeat-y') { echo ' selected="selected"' ; } ?>>repeat-y</option>
                    </select>
                    <select name="cmp_options[<?php echo $value['id']; ?>][attachment]" id="<?php echo $value['id']; ?>[attachment]" style="width:96px;">
                        <option value="" <?php if ( !$current_value['attachment'] ) { echo ' selected="selected"' ; } ?>></option>
                        <option value="fixed" <?php if ( $current_value['attachment']  == 'fixed' ) { echo ' selected="selected"' ; } ?>>Fixed</option>
                        <option value="scroll" <?php if ( $current_value['attachment']  == 'scroll') { echo ' selected="selected"' ; } ?>>scroll</option>
                    </select>
                    <select name="cmp_options[<?php echo $value['id']; ?>][hor]" id="<?php echo $value['id']; ?>[hor]" style="width:96px;">
                        <option value="" <?php if ( !$current_value['hor'] ) { echo ' selected="selected"' ; } ?>></option>
                        <option value="left" <?php if ( $current_value['hor']  == 'left' ) { echo ' selected="selected"' ; } ?>>Left</option>
                        <option value="right" <?php if ( $current_value['hor']  == 'right') { echo ' selected="selected"' ; } ?>>Right</option>
                        <option value="center" <?php if ( $current_value['hor'] == 'center') { echo ' selected="selected"' ; } ?>>Center</option>
                    </select>
                    <select name="cmp_options[<?php echo $value['id']; ?>][ver]" id="<?php echo $value['id']; ?>[ver]" style="width:100px;">
                        <option value="" <?php if ( !$current_value['ver'] ) { echo ' selected="selected"' ; } ?>></option>
                        <option value="top" <?php if ( $current_value['ver']  == 'top' ) { echo ' selected="selected"' ; } ?>>Top</option>
                        <option value="center" <?php if ( $current_value['ver'] == 'center') { echo ' selected="selected"' ; } ?>>Center</option>
                        <option value="bottom" <?php if ( $current_value['ver']  == 'bottom') { echo ' selected="selected"' ; } ?>>Bottom</option>
                    </select>
                </div>
                <div id="<?php echo $value['id']; ?>-preview" class="img-preview" <?php if( !$current_value['img']  ) echo 'style="display:none;"' ?>>
                    <img src="<?php if( $current_value['img'] ) echo $current_value['img'] ; else echo get_template_directory_uri().'/panel/images/spacer.png'; ?>" alt="" />
                    <a class="del-img" title="Delete"></a>
                </div>
                <script>
                    jQuery('#<?php echo $value['id']; ?>colorSelector').ColorPicker({
                        color: '<?php echo $current_value['color'] ; ?>',
                        onShow: function (colpkr) {
                            jQuery(colpkr).fadeIn(500);
                            return false;
                        },
                        onHide: function (colpkr) {
                            jQuery(colpkr).fadeOut(500);
                            return false;
                        },
                        onChange: function (hsb, hex, rgb) {
                            jQuery('#<?php echo $value['id']; ?>colorSelector div').css('backgroundColor', '#' + hex);
                            jQuery('#<?php echo $value['id']; ?>color').val('#'+hex);
                        }
                    });
                    cmp_styling_uploader('<?php echo $value['id']; ?>');
                </script>
                <?php
                break;
                case 'color':
                ?>
                <div id="<?php echo $value['id']; ?>colorSelector" class="color-pic"><div style="background-color:<?php echo cmp_get_option($value['id']) ; ?>"></div></div>
                <input style="width:80px; margin-right:5px;"  name="cmp_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" type="text" value="<?php echo cmp_get_option($value['id']) ; ?>" />
                <script>
                    jQuery('#<?php echo $value['id']; ?>colorSelector').ColorPicker({
                        color: '<?php echo cmp_get_option($value['id']) ; ?>',
                        onShow: function (colpkr) {
                            jQuery(colpkr).fadeIn(500);
                            return false;
                        },
                        onHide: function (colpkr) {
                            jQuery(colpkr).fadeOut(500);
                            return false;
                        },
                        onChange: function (hsb, hex, rgb) {
                            jQuery('#<?php echo $value['id']; ?>colorSelector div').css('backgroundColor', '#' + hex);
                            jQuery('#<?php echo $value['id']; ?>').val('#'+hex);
                        }
                    });
                </script>
                <?php
                break;
                case 'typography':
                $current_value = cmp_get_option($value['id']);
                ?>
                <div style="clear:both;"></div>
                <div style="clear:both; padding:10px 14px; margin:0 -15px;">
                    <div id="<?php echo $value['id']; ?>colorSelector" class="color-pic"><div style="background-color:<?php echo $current_value['color'] ; ?>"></div></div>
                    <input style="width:80px; margin-right:5px;"  name="cmp_options[<?php echo $value['id']; ?>][color]" id="<?php  echo $value['id']; ?>color" type="text" value="<?php echo $current_value['color'] ; ?>" />
                    <select name="cmp_options[<?php echo $value['id']; ?>][size]" id="<?php echo $value['id']; ?>[size]" style="width:55px;">
                        <option value="" <?php if (!$current_value['size'] ) { echo ' selected="selected"' ; } ?>></option>
                        <?php for( $i=1 ; $i<101 ; $i++){ ?>
                        <option value="<?php echo $i ?>" <?php if (( $current_value['size']  == $i ) ) { echo ' selected="selected"' ; } ?>><?php echo $i ?></option>
                        <?php } ?>
                    </select>
                    <select name="cmp_options[<?php echo $value['id']; ?>][font]" id="<?php echo $value['id']; ?>[font]" style="width:150px;">
                        <?php foreach( $options_fonts as $font => $font_name ){ ?>
                        <option value="<?php echo $font ?>" <?php if ( $current_value['font']  == $font ) { echo ' selected="selected"' ; } ?>><?php echo $font_name ?></option>
                        <?php } ?>
                    </select>
                    <select name="cmp_options[<?php echo $value['id']; ?>][weight]" id="<?php echo $value['id']; ?>[weight]" style="width:96px;">
                        <option value="" <?php if ( !$current_value['weight'] ) { echo ' selected="selected"' ; } ?>></option>
                        <option value="normal" <?php if ( $current_value['weight']  == 'normal' ) { echo ' selected="selected"' ; } ?>>Normal</option>
                        <option value="bold" <?php if ( $current_value['weight']  == 'bold') { echo ' selected="selected"' ; } ?>>Bold</option>
                        <option value="lighter" <?php if ( $current_value['weight'] == 'lighter') { echo ' selected="selected"' ; } ?>>Lighter</option>
                        <option value="bolder" <?php if ( $current_value['weight'] == 'bolder') { echo ' selected="selected"' ; } ?>>Bolder</option>
                        <option value="100" <?php if ( $current_value['weight'] == '100') { echo ' selected="selected"' ; } ?>>100</option>
                        <option value="200" <?php if ( $current_value['weight'] == '200') { echo ' selected="selected"' ; } ?>>200</option>
                        <option value="300" <?php if ( $current_value['weight'] == '300') { echo ' selected="selected"' ; } ?>>300</option>
                        <option value="400" <?php if ( $current_value['weight'] == '400') { echo ' selected="selected"' ; } ?>>400</option>
                        <option value="500" <?php if ( $current_value['weight'] == '500') { echo ' selected="selected"' ; } ?>>500</option>
                        <option value="600" <?php if ( $current_value['weight'] == '600') { echo ' selected="selected"' ; } ?>>600</option>
                        <option value="700" <?php if ( $current_value['weight'] == '700') { echo ' selected="selected"' ; } ?>>700</option>
                        <option value="800" <?php if ( $current_value['weight'] == '800') { echo ' selected="selected"' ; } ?>>800</option>
                        <option value="900" <?php if ( $current_value['weight'] == '900') { echo ' selected="selected"' ; } ?>>900</option>
                    </select>
                    <select name="cmp_options[<?php echo $value['id']; ?>][style]" id="<?php echo $value['id']; ?>[style]" style="width:100px;">
                        <option value="" <?php if ( !$current_value['style'] ) { echo ' selected="selected"' ; } ?>></option>
                        <option value="normal" <?php if ( $current_value['style']  == 'normal' ) { echo ' selected="selected"' ; } ?>>Normal</option>
                        <option value="italic" <?php if ( $current_value['style'] == 'italic') { echo ' selected="selected"' ; } ?>>Italic</option>
                        <option value="oblique" <?php if ( $current_value['style']  == 'oblique') { echo ' selected="selected"' ; } ?>>oblique</option>
                    </select>
                </div>
                <script>
                    jQuery('#<?php echo $value['id']; ?>colorSelector').ColorPicker({
                        color: '#<?php echo $current_value['color'] ; ?>',
                        onShow: function (colpkr) {
                            jQuery(colpkr).fadeIn(500);
                            return false;
                        },
                        onHide: function (colpkr) {
                            jQuery(colpkr).fadeOut(500);
                            return false;
                        },
                        onChange: function (hsb, hex, rgb) {
                            jQuery('#<?php echo $value['id']; ?>colorSelector div').css('backgroundColor', '#' + hex);
                            jQuery('#<?php echo $value['id']; ?>color').val('#'+hex);
                        }
                    });
                </script>
                <?php
                break;
            }
            ?>
            <?php if( isset( $value['help'] ) ) : ?>
                <a class="mo-help tooltip"  title="<?php echo $value['help'] ?>"></a>
            <?php endif; ?>
        </div>
        <?php
    }
    add_action('admin_menu', 'cmp_add_admin');
