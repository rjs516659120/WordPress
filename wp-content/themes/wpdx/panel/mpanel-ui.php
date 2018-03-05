<?php
function panel_options() {
  do_action( 'cmp_panel_save_options' );
  $categories_obj = get_categories('hide_empty=0');
  $categories = array();
  foreach ($categories_obj as $pn_cat) {
    $categories[$pn_cat->cat_ID] = $pn_cat->cat_name;
  }

  $edd_categories = array();
  if(class_exists('Easy_Digital_Downloads')){
    $edd_categories_obj = get_terms( array(
      'taxonomy' => 'download_category',
      'hide_empty' => false,
    ) );
    foreach ($edd_categories_obj as $ec_cat) {
      $edd_categories[$ec_cat->term_id] = $ec_cat->name;
    }
  }
  $sliders = array();
  $custom_slider = new WP_Query( array( 'post_type' => 'cmp_slider', 'posts_per_page' => -1 ,'no_found_rows' => 1 ) );
  while ( $custom_slider->have_posts() ) {
    $custom_slider->the_post();
    $sliders[get_the_ID()] = get_the_title();
  }
  // Pull all the pages into an array
  $options_pages = array();
  $options_pages_obj = get_pages('sort_column=post_parent,menu_order');
  $options_pages[''] = __('Select a page:','wpdx');
  foreach ($options_pages_obj as $page) {
    $options_pages[$page->ID] = $page->post_title;
  }

  $options_roles = array();
  global $wp_roles;
  $roles = $wp_roles->get_names();
  foreach ($roles as $role_value => $role_name) {
    $role_name = translate_user_role( $role_name );
    $options_roles[$role_value] = $role_name;
  }

  $options_post_types = array();
  $args = array( 'public' => true );
  $post_types = get_post_types( $args, 'names' );
  foreach ($post_types as $post_type_value => $post_type_name) {
    $obj = get_post_type_object( $post_type_name );
    $label =  $obj->labels->singular_name;
    $options_post_types[$post_type_value] = $label.'('.$post_type_name.')';
    unset($options_post_types['attachment']);
    unset($options_post_types['dwqa-answer']);
  }

  $save='
  <div class="mpanel-submit">
    <input type="hidden" name="action" value="test_theme_data_save" />
    <input type="hidden" name="security" value="'. wp_create_nonce("test-theme-data").'" />
    <input name="save" class="mpanel-save" type="submit" value="'. __( 'Save Changes', 'wpdx' ).'" />
  </div>';
  
  if(!cmp_get_option('footer_code')) echo '<div id="message" class="error"><p>'.__('Hello, Thank you for using our theme. If this is your first time using the settings panel, you can click the "Reset Settings" button which is at the bottom to import some preset defaults, allowing you to quickly set the theme.','wpdx').'</p></div>'
  ?>
  <div id="save-alert"></div>
  <div class="mo-panel">
    <div class="mo-panel-tabs">
      <div class="logo"><img alt="" src="<?php echo get_template_directory_uri().'/panel/images/panel.png' ?>" class="avatar-80" height="80" width="80" alt="<?php _e('Changmeng\'s Works','wpdx') ?>" title="<?php _e('Changmeng\'s Works','wpdx') ?>"/>
        </div>
        <?php if( get_locale() =='zh_CN' || get_locale() =='zh_TW' || get_locale() =='zh_HK'){
          echo '<ul>';
        }else{
          echo '<ul class="en-tabs">';
        } ?>
        <li class="cmp-tabs general"><a href="#tab1"><i class="dashicons dashicons-admin-generic"></i><?php _e( 'General', 'wpdx' ); ?></a></li>
        <li class="cmp-tabs homepage"><a href="#tab2"><i class="dashicons dashicons-admin-home"></i><?php _e( 'Homepage', 'wpdx' ); ?></a></li>
        <li class="cmp-tabs header"><a href="#tab3"><i class="dashicons dashicons-editor-insertmore"></i><?php _e( 'Header', 'wpdx' ); ?></a></li>
        <li class="cmp-tabs archives"><a href="#tab4"><i class="dashicons dashicons-category"></i><?php _e( 'Archives', 'wpdx' ); ?></a></li>
        <li class="cmp-tabs article"><a href="#tab5"><i class="dashicons dashicons-format-aside"></i><?php _e( 'Article', 'wpdx' ); ?></a></li>
        <li class="cmp-tabs sidebars"><a href="#tab6"><i class="dashicons dashicons-align-left"></i><?php _e( 'Sidebars', 'wpdx' ); ?></a></li>
        <li class="cmp-tabs banners"><a href="#tab7"><i class="dashicons dashicons-smiley"></i><?php _e( 'Banners', 'wpdx' ); ?></a></li>
        <li class="cmp-tabs styling"><a href="#tab8"><i class="dashicons dashicons-art"></i><?php _e( 'Styling', 'wpdx' ); ?></a></li>
        <li class="cmp-tabs register-login"><a href="#tab9"><i class="dashicons dashicons-admin-users"></i><?php _e( 'Register & Login', 'wpdx' ); ?></a></li>
        <li class="cmp-tabs frontend-post"><a href="#tab10"><i class="dashicons dashicons-edit"></i><?php _e( 'Frontend Post', 'wpdx' ); ?></a></li>
        <?php if(class_exists('Easy_Digital_Downloads')): ?>
        <li class="cmp-tabs shop"><a href="#tab11"><i class="dashicons dashicons-download"></i><?php _e( 'EDD Settings', 'wpdx' ); ?></a></li>
        <?php endif;?>
        <li class="cmp-tabs Social"><a href="#tab12"><i class="dashicons dashicons-admin-site"></i><?php _e( 'Social Network', 'wpdx' ); ?></a></li>
        <li class="cmp-tabs advanced"><a href="#tab13"><i class="dashicons dashicons-admin-settings"></i><?php _e( 'Advanced', 'wpdx' ); ?></a></li>
        <li class="theme-doc"><a href="<?php echo THEME_DOC ?>" target="_blank"><i class="dashicons dashicons-book-alt"></i><?php _e( 'Theme Document', 'wpdx' ); ?></a></li>
      </ul>
      <div class="clear"></div>
    </div> <!-- .mo-panel-tabs -->
    <div class="mo-panel-content">
      <form action="/" name="cmp_form" id="cmp_form">
        <div id="tab1" class="tabs-wrap">
          <div class="mo-panel-top">
            <h2><i class="dashicons dashicons-admin-generic"></i><?php _e( 'General', 'wpdx' ); ?></h2> <?php echo $save ?>
            <div class="clear"></div>
          </div>
          <div class="cmppanel-item">
            <h3><?php _e( 'Announcement', 'wpdx' ); ?></h3>
            <?php
            cmp_options(
                array(  "name" => __( 'Homepage Announcement' , 'wpdx' ),
                  "id" => "announcement",
                  "type" => "textarea",
                  "help" => __( 'Only show in the homepage, support for HTML code.' , 'wpdx' )
                  )
              );
              cmp_options(
                array(  "name" => __( 'User Center Announcement' , 'wpdx' ),
                  "id" => "user_tips",
                  "type" => "textarea",
                  "help" => __( 'Only show in the User Center Page Template, support for HTML code. Leave blank not show anything.' , 'wpdx' )
                  )
              );
            ?>
            <h3><?php _e( 'Title Settings', 'wpdx' ); ?></h3>
            <?php
            cmp_options(
              array(  "name" => __( 'Display Blog name after title' , 'wpdx' ),
                "id" => "title_suffix",
                "type" => "checkbox",
                "help" => __( 'Display Blog name after title of all pages.' , 'wpdx' )
                )
              );
            cmp_options(
                array(  "name" => __( 'Separator between title and Blog name' , 'wpdx' ),
                  "id" => "separator",
                  "type" => "text",
                  "help" => __( "Separator between title and Blog name, you can use | , _ , - , > and so on. Default is '|'." , 'wpdx' )
                  )
              );
            ?>
          </div>
          <div class="cmppanel-item">
            <h3><?php _e( 'Favicon', 'wpdx' ); ?></h3>
            <?php
            cmp_options(array(
              "name" => __( 'Custom Favicon' , 'wpdx' ),
              "id" => "favicon",
              "type" => "upload",
              "help" => __('Upload a icon (.ico), size 32x32 or 16x16','wpdx')
              ));
              ?>
          </div>
          <div class="cmppanel-item">
            <h3><?php _e( 'Logo settings', 'wpdx' ); ?></h3>
            <?php
            cmp_options(array(
              "name" => __( 'Blog Logo' , 'wpdx' ),
              "id" => "logo",
              "type" => "upload",
              "help" => __('You can replace the logo from theme\'s images directory, or upload your blog logo here. The size of the logo is 180*48 px(Horizontal layout) or 260*70 px(Vertical layout). Best to use a transparent png image.','wpdx'),
              ));
            cmp_options(array(
              "name" => __( 'Login page Logo' , 'wpdx' ),
              "id" => "dashboard_logo",
              "type" => "upload",
              "help" => __('This logo will replace the logo of WP login page, 84*84px.','wpdx')
              ));
            cmp_options(array(
              "name" => __( '--Login page Logo url' , 'wpdx' ),
              "id" => "dashboard_logo_url",
              "type" => "text",
              "help" => __('This url will replace the logo url of WP login page, must have http:// or https://','wpdx')
              ));
            cmp_options(array(
              "name" => __( '--Login page Logo title' , 'wpdx' ),
              "id" => "dashboard_logo_title",
              "type" => "text",
              "help" => __('This text will replace the logo title of WP login page.','wpdx')
              ));
            ?>
          </div>
          <div class="cmppanel-item">
            <h3><?php _e( 'Time format', 'wpdx' ); ?></h3>
            <?php
            cmp_options(
              array(  "name" => __( 'Time format for blog posts' , 'wpdx' ),
                "id" => "time_format",
                "type" => "radio",
                "options" => array( "traditional"=>__( 'Traditional' , 'wpdx' ),
                  "modern"=>__( 'Time Ago Format' , 'wpdx' ),
                  "none"=>__( 'Disable all' , 'wpdx' )
                  )
                )
              );
            ?>
          </div>
          <div class="cmppanel-item">
            <h3><?php _e( 'Breadcrumbs Settings', 'wpdx' ); ?></h3>
            <?php
            cmp_options(
              array(  "name" => __( 'Breadcrumbs' , 'wpdx' ),
                "id" => "breadcrumbs",
                "type" => "checkbox")
              );
            cmp_options(
              array(  "name" => __( 'Breadcrumbs output title of post' , 'wpdx' ),
                "id" => "breadcrumbs_title",
                "type" => "checkbox",
                "help" => __('In a single post, breadcrumbs output "article" at last, if you want to output the title of the post at last, check this option.','wpdx')
                )
              );
            ?>
          </div>
          <div class="cmppanel-item">
            <h3><?php _e( 'Thumbnail Resizing', 'wpdx' ); ?></h3>
            <div class="option-item">
              <?php _e('<p>You can choose one method to resize thumbnails.</p><p>"Aqua Resizer" and "OTF Regenerate Thumbnails" require that all images must be uploaded to the WP directory.</p><p>Qiniu imageView2 requires that all images must be synchronized to the Qiniu server. </p><p>Only Timthumb support remote images, but using timthumb may have security risks, so if you do not choose timthumb, please delete timthumb.php and timthumb-config.php in the  theme root directory.</p>','wpdx'); ?>
            </div>
            <?php
            cmp_options(
              array(  "name" => __( 'Thumbnail resizing method' , 'wpdx' ),
                "id" => "thumb_cut",
                "help" => __( 'Choose the method of resizing thumbnail. Default is Aqua Resizer.' , 'wpdx' ),
                "options" => array(
                  "aq"=>__( 'Aqua Resizer' , 'wpdx' ),
                  "otf"=>__( 'OTF Regenerate Thumbnails' , 'wpdx' ),
                  "tim"=>__( 'Timthumb' , 'wpdx' ),
                  "qiniu"=>__( 'Qiniu imageView2' , 'wpdx' )
                  ),
                "type" => "radio"));
            cmp_options(
              array(  "name" => __( 'Timthumb Crop mode' , 'wpdx' ),
                "id" => "thumb_zc",
                "help" => __( 'Crop mode of thumb, default: Scaled in proportion(Cropping) .' , 'wpdx' ),
                "type" => "radio",
                "options" => array( 
                  "3"=>__( 'Fixed height and width(No cropping)' , 'wpdx' ),
                  "1"=>__( 'Scaled in proportion(Cropping)' , 'wpdx' ),
                  "2"=>__( 'Scaled in proportion(No cropping)' , 'wpdx' )),
                ));
            cmp_options(
              array(  "name" => __( 'Thumb Quality' , 'wpdx' ),
                "id" => "thumb_q",
                "type" => "slider",
                "help" => __( 'The quality of thumb, max 100, default 90 .' , 'wpdx' ),
                "unit" => "%",
                "max" => 100,
                "min" => 0 ));
            ?>
          </div>
          <div class="cmppanel-item">
            <h3><?php _e( 'Header Code', 'wpdx' ); ?></h3>
            <div class="option-item">
              <p><?php _e( 'The following code will add to the &lt;head&gt; tag. Useful if you need to add additional scripts such as CSS or JS.', 'wpdx' ); ?></p>
              <textarea id="header_code" name="cmp_options[header_code]" style="width:100%" rows="7"><?php echo htmlspecialchars_decode(cmp_get_option('header_code'));  ?></textarea>
            </div>
          </div>
          <div class="cmppanel-item">
            <h3><?php _e( 'Footer Code', 'wpdx' ); ?></h3>
            <div class="option-item">
              <p><?php _e( 'The following code will add to the &lt;footer&gt; tag. You can add Copyright text or Analysis code.', 'wpdx' ); ?></p>
              <textarea id="footer_code" name="cmp_options[footer_code]" style="width:100%" rows="7"><?php echo htmlspecialchars_decode(cmp_get_option('footer_code'));  ?></textarea>
            </div>
          </div>
        </div>
        <div id="tab3" class="tabs-wrap">
          <div class="mo-panel-top">
            <h2><i class="dashicons dashicons-editor-insertmore"></i></i><?php _e( 'Header', 'wpdx' ); ?></h2> <?php echo $save ?>
            <div class="clear"></div>
          </div>
          <div class="cmppanel-item">
          <?php
            cmp_options(
              array(  "name" => __( 'Hide WP Admin Bar' , 'wpdx' ),
                "id" => "hide_toolbar",
                "type" => "checkbox",
                "help" => __('If you have set the front-end user management menu, you can hide WordPress Admin Bar for all users.','wpdx')
                ));
          ?>
          <h3><?php _e( 'Left Top Nav', 'wpdx' ); ?></h3>
            <?php
            cmp_options(
              array(  "name" => __( 'Show Login Module' , 'wpdx' ),
                "id" => "show_login",
                "type" => "checkbox"));
            cmp_options(
              array(  "name" => __( '-- Custom Login Url:' , 'wpdx' ),
                "id" => "login_url",
                "type" => "text",
                "help" => __('Fill in a login url (including http://), Leave blank to use WP default login url.','wpdx')
                ));
            cmp_options(
              array(  "name" => __( '-- Custom Register Url:' , 'wpdx' ),
                "id" => "register_url",
                "type" => "text",
                "help" => __('Please enable registration option in the general setting page, then fill in a custom registration url (including http://), Leave blank to use WP default registration url.','wpdx')
                ));
            cmp_options(
              array(  "name" => __( '-- Custom Lost Password Url:' , 'wpdx' ),
                "id" => "password_url",
                "type" => "text",
                "help" => __('Fill in a custom lost password url (including http://), Leave blank to use WP default lost password url.','wpdx')
                ));
            cmp_options(
              array(  "name" => __( '-- Custom Profile edit Url:' , 'wpdx' ),
                "id" => "profile_url",
                "type" => "text",
                "help" => __('Fill in the profile edit url (including http://), Leave blank to use WP default profile edit url.','wpdx')
                ));
            cmp_options(
              array(  "name" => __( 'Show QQ Qun' , 'wpdx' ),
                "id" => "show_qqqun",
                "type" => "checkbox"));
            cmp_options(
              array(  "name" => __( '-- Url:' , 'wpdx' ),
                "id" => "qqqun_url",
                "type" => "text"));
            cmp_options(
              array(  "name" => __( '-- Title:' , 'wpdx' ),
                "id" => "qqqun_title",
                "type" => "text"));
            cmp_options(
              array(  "name" => __( 'Show QQ Chat' , 'wpdx' ),
                "id" => "show_qq",
                "type" => "checkbox"));
            cmp_options(
              array(  "name" => __( '-- QQ Code:' , 'wpdx' ),
                "id" => "qq_code",
                "type" => "textarea"));
            cmp_options(
              array(  "name" => __( 'Show Weixin' , 'wpdx' ),
                "id" => "show_wx",
                "type" => "checkbox"));
            cmp_options(array(
              "name" => __( '-- Weixin Image' , 'wpdx' ),
              "id" => "weixin_img",
              "type" => "upload",
              "help" => __('You can upload your weixin image here. The size is 200*200 px.','wpdx'),
            ));
            cmp_options(
              array(  "name" => __( 'Show Sina Weibo' , 'wpdx' ),
                "id" => "show_weibo",
                "type" => "checkbox"));
            cmp_options(
              array(  "name" => __( '-- Uid:' , 'wpdx' ),
                "id" => "weibo_uid",
                "type" => "text"));
            cmp_options(
              array(  "name" => __( '-- Type:' , 'wpdx' ),
                "id" => "weibo_type",
                "type" => "radio",
                "options" => array(
                  "red_1"=>__( 'Only button' , 'wpdx' ),
                  "red_2"=>__( 'Button + number' , 'wpdx' )
                )
              ));
            cmp_options(
              array(  "name" => __( 'Show QQ Weibo' , 'wpdx' ),
                "id" => "show_qqweibo",
                "type" => "checkbox"));
            cmp_options(
              array(  "name" => __( '-- Name:' , 'wpdx' ),
                "id" => "qqweibo_name",
                "type" => "text"));
            cmp_options(
              array(  "name" => __( '-- Uid:' , 'wpdx' ),
                "id" => "qqweibo_t",
                "type" => "text"));
            cmp_options(
              array(  "name" => __( '-- Type:' , 'wpdx' ),
                "id" => "qqweibo_f",
                "type" => "radio",
                "options" => array(
                  "0"=>__( 'Only button' , 'wpdx' ),
                  "1"=>__( 'Button + number' , 'wpdx' )
                )
              ));
            ?>
          </div>
          <div class="cmppanel-item">
          <h3><?php _e( 'Fixed main menu (Vertical Layout)', 'wpdx' ); ?></h3>
            <?php
            cmp_options(
              array(  "name" => __( 'Fixed main menu' , 'wpdx' ),
                "id" => "nav_fixed",
                "type" => "checkbox",
                "help" => __('If you want to fixed the main menu while scrolling down the page, please check this option.Take effect only on the vertical layout.','wpdx')
              ));
              ?>
          </div>
        </div> <!-- Header -->
        <div id="tab2" class="tabs-wrap">
          <div class="mo-panel-top">
            <h2><i class="dashicons dashicons-admin-home"></i><?php _e( 'Homepage', 'wpdx' ); ?></h2> <?php echo $save ?>
            <div class="clear"></div>
          </div>
          <div class="cmppanel-item">
            <h3><?php _e( 'Homepage SEO', 'wpdx' ); ?></h3>
            <?php
            cmp_options(
              array(  "name" => __( 'Homepage Title' , 'wpdx' ),
                "id" => "homepage_title",
                "type" => "textarea")
            );
            cmp_options(
              array(  "name" => __( 'Homepage Keywords' , 'wpdx' ),
                "id" => "homepage_keywords",
                "type" => "textarea",
                "help" => __('Keywords separated by commas.','wpdx')
                )
            );
            cmp_options(
              array(  "name" => __( 'Homepage Description' , 'wpdx' ),
                "id" => "homepage_description",
                "type" => "textarea"
                )
            );
            ?>
          </div>
          <div class="cmppanel-item">
            <h3><?php _e( 'Homepage Slider', 'wpdx' ); ?></h3>
            <?php
            cmp_options(
              array(  "name" => __( 'Enable Homepage Slider' , 'wpdx' ),
                "id" => "slider",
                "type" => "checkbox"));
            cmp_options(
              array(  "name" => __( 'Slider Style' , 'wpdx' ),
                "id" => "slider_style",
                "type" => "select",
                "help" => __( 'The number of slides to be shown. Slides will be sized down if carousel becomes smaller than the original size.' , 'wpdx' ),
                "options" => array(
                  '1' => __('Slide Wide No ADs','wpdx'),
                  '2' => __('Slide + ADs','wpdx'),
                  '3' => __('Slide No ADs (Blog Layout only)','wpdx')
                  )));
            cmp_options(
              array(  "name" => __( 'Number of images per screen' , 'wpdx' ),
                "id" => "images_number",
                "type" => "select",
                "help" => __( 'The number of slides to be shown. Slides will be sized down if carousel becomes smaller than the original size.' , 'wpdx' ),
                "options" => array(
                  '1' => __('One','wpdx'),
                  '2' => __('Two','wpdx'),
                  '3' => __('Three','wpdx'),
                  '4' => __('Four','wpdx')
                  )));
            cmp_options(
              array(  "name" => __( 'Image Height(px)' , 'wpdx' ),
                "id" => "images_height",
                "type" => "short-text"));
            cmp_options(
              array(  "name" => __( 'Slideshow Mode' , 'wpdx' ),
                "id" => "slider_mode",
                "type" => "select",
                "options" => array(
                  'horizontal' => __('horizontal','wpdx'),
                  'vertical' => __('vertical','wpdx'),
                  'fade' => __('fade','wpdx')
                  )));
            cmp_options(
              array(  "name" => __( 'Autoplay' , 'wpdx' ),
                "id" => "slider_auto",
                "type" => "checkbox"));
            cmp_options(
              array(  "name" => __( 'AutoHover' , 'wpdx' ),
                "id" => "slider_autoHover",
                "type" => "checkbox"));
            cmp_options(
              array(  "name" => __( 'Controls' , 'wpdx' ),
                "id" => "slider_controls",
                "type" => "checkbox"));
            cmp_options(
              array(  "name" => __( 'Pager' , 'wpdx' ),
                "id" => "slider_pager",
                "type" => "checkbox"));
            cmp_options(
              array(  "name" => __( 'Title' , 'wpdx' ),
                "id" => "slider_captions",
                "type" => "checkbox"));
            cmp_options(
              array(  "name" => __( 'Slideshow Speed' , 'wpdx' ),
                "id" => "slider_pause",
                "type" => "slider",
                "unit" => "ms",
                "max" => 40000,
                "min" => 100 ));
            cmp_options(
              array(  "name" => __( 'Number Of Posts To Show' , 'wpdx' ),
                "id" => "slider_number",
                "type" => "short-text"));
            cmp_options(
              array(  "name" => __( 'Query Type' , 'wpdx' ),
                "id" => "slider_query",
                "options" => array(
                  "latest"=>__( 'Latest Posts' , 'wpdx' ),
                  "sticky"=>__( 'Sticky Posts' , 'wpdx' ),
                  "category"=>__( 'Category' , 'wpdx' ),
                  "tag"=>__( 'Tag' , 'wpdx' ),
                  "post"=>__( 'Selctive Posts' , 'wpdx' ),
                  "page"=>__( 'Selctive pages' , 'wpdx' ),
                  "custom"=>__( 'Custom Slider' , 'wpdx' ) ),
                "type" => "radio"));
            cmp_options(
              array(  "name" => __( 'Tags' , 'wpdx' ),
                "help" => __( 'Enter a tag name, or names seprated by comma. ' , 'wpdx' ),
                "id" => "slider_tag",
                "type" => "text"));
            ?>
        <?php
        $slider_cat = cmp_get_option('slider_cat') ;
        if( !is_array( $slider_cat ) ){
          $slider_cat = array();
          $slider_cat[] = cmp_get_option('slider_cat');
        }
        ?>
        <div class="option-item" id="slider_cat-item">
          <span class="label"><?php _e( 'Category', 'wpdx' ); ?></span>
          <select multiple="multiple" name="cmp_options[slider_cat][]" id="cmp_slider_cat">
            <?php foreach ($categories as $key => $option) { ?>
            <option value="<?php echo $key ?>" <?php if ( @in_array( $key , $slider_cat ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
            <?php } ?>
          </select>
          <a class="mo-help tooltip" title="<?php _e( 'Choose categories yuo want to show . Use [Ctrl] key to choose multiple categories. ', 'wpdx' ); ?>"></a>
        </div>
        <?php
        cmp_options(
          array(  "name" => __( 'Selctive Posts IDs' , 'wpdx' ),
            "help" => __( 'Enter a post ID, or IDs seprated by comma. ' , 'wpdx' ),
            "id" => "slider_posts",
            "type" => "text"));
        cmp_options(
          array(  "name" => __( 'Selctive Pages IDs' , 'wpdx' ),
            "help" => __( 'Enter a page ID, or IDs seprated by comma. ' , 'wpdx' ),
            "id" => "slider_pages",
            "type" => "text"));
        cmp_options(
          array(  "name" => __( 'Custom Slider' , 'wpdx' ),
            "help" => __( 'Choose your custom slide' , 'wpdx' ),
            "id" => "slider_custom",
            "type" => "select",
            "options" => $sliders));
         ?>
      </div>
          <div class="cmppanel-item">
            <h3><?php _e( 'Show Links', 'wpdx' ); ?></h3>
            <?php
            cmp_options(
              array(  "name" => __( 'Show Links in Homepage Footer' , 'wpdx' ),
                "id" => "footer_links",
                "type" => "checkbox",
                "help" => __('If checked, Please visit Appearance - Menus to set a Footer Link menu.','wpdx')
                )
            );
            ?>
          </div>
          <div class="cmppanel-item">
            <h3><?php _e( 'Homepage displays', 'wpdx' ); ?></h3>
            <?php
            cmp_options(
              array(  "name" => __( 'Homepage displays' , 'wpdx' ),
                "id" => "on_home",
                "type" => "radio",
                "options" => array( "latest"=>__( 'Latest posts - Blog Layout' , 'wpdx' ),
                  "boxes"=>__( 'CMS Layout - Using Home Builder' , 'wpdx' )))
            );
            ?>
          </div>
          <div id="Home_blog" class="cmppanel-child">
            <div class="cmppanel-item">
              <h4><?php _e( 'Latest Posts', 'wpdx' ); ?></h4>
              <?php
              cmp_options(array(
                "name" => __( 'List Style' , 'wpdx' ),
                "id" => "list_style",
                "type" => "radio",
                "options" => array(
                  "simple_title"=>__( 'Simple Title List' , 'wpdx' ),
                  "row_thumb"=>__( 'Row Thumb' , 'wpdx' ),
                  "small_thumb"=>__( 'Small Thumb + Excerpt' , 'wpdx' ),
                  "big_thumb"=>__( 'Big Thumb + Excerpt' , 'wpdx' ),
                  "original_image"=>__( 'Original Image + Excerpt' , 'wpdx' ) )
              ));
              cmp_options(array(
                "name" => __( 'Ignore Sticky Posts' , 'wpdx' ),
                "id" => "ignore_sticky",
                "help" => __('If home slider show sticky posts, I propose to ignore the sticky posts from latest posts here.','wpdx'),
                "type" => "checkbox"
              ));
              cmp_options(array(
                "name" => __( 'Posts Per Page' , 'wpdx' ),
                "id" => "blog_posts_number",
                "help" => __('Default is 10.','wpdx'),
                "type" => "short-text"
              ));
              cmp_options(array(
                "name" => __( 'Exclude Categories' , 'wpdx' ),
                "id" => "exclude_categories",
                "help" => __('Use minus sign (-) to exclude categories from Latest posts on homepage. Example: (-1,-4,-7) =  exclude Category 1,4,7.','wpdx'),
                "type" => "text"
              ));
              cmp_options(array(
                "name" => __( 'Exclude Posts' , 'wpdx' ),
                "id" => "exclude_posts",
                "help" => __('Use minus sign (-) to exclude posts from Latest posts on homepage. Example: (-2,-5,-8) =  exclude post 2,5,8.','wpdx'),
                "type" => "text"
              ));
              cmp_options(array(
                "name" => __( 'Pagination Type' , 'wpdx' ),
                "id" => "blog_pagination_type",
                "type" => "radio",
                "options" => array(
                  "pagination"=>__( 'Pagination' , 'wpdx' ),
                  "ajax"=>__( 'Ajax loading more' , 'wpdx' )
                )
              ));
              cmp_options(array(
                "name" => __( 'Turn to click ajax loading after scroll' , 'wpdx' ),
                "id" => "blog_ajax_num",
                "help" => __('Turn to click ajax loading after scrolling how many times, default is 5. If you want never turn to click ajax loading, just set a large enough number, for example: 999999.','wpdx'),
                "type" => "short-text"
              ));
              // cmp_options(array(
              //   "name" => __( 'Turn to pagination after ajax' , 'wpdx' ),
              //   "id" => "blog_no_ajax_num",
              //   "help" => __('Turn to pagination after ajax loading how many times , including scroll and click, default is 10. If you want never turn to pagination, just set a large enough number, for example: 999999.','wpdx'),
              //   "type" => "short-text"
              // ));
              ?>
              <div class="option-item">
                <p style="color:#DA542E;font-weight:bolder"><?php _e( 'Important: The value of "Posts Per Page" ​​can not be less than the value of "Blog pages show at most", which is set in [Settins > Reading]. The current value of "Blog pages show at most" is ', 'wpdx');echo get_option('posts_per_page'); ?></p>
              </div>
            </div>
          </div>
          <div id="Home_Builder" class="cmppanel-child">
            <div class="cmppanel-item">
              <h4><?php _e( 'First News Excerpt Length', 'wpdx' ); ?></h4>
              <?php
              cmp_options(
                array(  "name" => __( 'First News Excerpt Length' , 'wpdx' ),
                  "id" => "home_exc_length",
                  "type" => "short-text")
              );
              ?>
            </div>
            <div class="cmppanel-item"  style=" overflow: visible; ">
              <h4><?php _e( 'Home Builder', 'wpdx' ); ?></h4>
              <div class="option-item">
                <select style="display:none" id="cats_defult">
                  <?php foreach ($categories as $key => $option) { ?>
                  <option value="<?php echo $key ?>"><?php echo $option; ?></option>
                  <?php } ?>
                </select>
                <select style="display:none" id="edd_cats_defult">
                  <?php foreach ($edd_categories as $key => $option) { ?>
                  <option value="<?php echo $key ?>"><?php echo $option; ?></option>
                  <?php } ?>
                </select>
                <select style="display:none" id="post_type_defult">
                  <?php
                  $args = array( 'public' => true );
                  $post_types = get_post_types( $args, 'names' );
                  foreach ($post_types as $key => $option) { ?>
                  <option value="<?php echo $key ?>"><?php echo $option; ?></option>
                  <?php } ?>
                </select>
                <div style="clear:both"></div>
                <div class="home-builder-buttons">
                  <a id="add-recent" ><?php _e( 'Recent/Random/Hot Posts', 'wpdx' ); ?></a>
                  <a id="add-cat" ><?php _e( 'News Box', 'wpdx' ); ?></a>
                  <a id="add-news-picture" ><?php _e( 'News in picrure', 'wpdx' ); ?></a>
                  <a id="add-slider" ><?php _e( 'Scrolling Box', 'wpdx' ); ?></a>
                  <a id="add-tabs" ><?php _e( 'Categories Tabs Box', 'wpdx' ); ?></a>
                  <?php if(class_exists('Easy_Digital_Downloads')){ ?>
                    <a id="add-cat-edd" ><?php _e( 'EDD News Box', 'wpdx' ); ?></a>
                    <a id="add-news-picture-edd" ><?php _e( 'EDD News in picrure', 'wpdx' ); ?></a>
                    <a id="add-slider-edd" ><?php _e( 'EDD Scrolling Box', 'wpdx' ); ?></a>
                    <a id="add-tabs-edd" ><?php _e( 'EDD Categories Tabs Box', 'wpdx' ); ?></a>
                  <?php } ?>
                  <a id="add-users" ><?php _e( 'Users', 'wpdx' ); ?></a>
                  <a id="add-ads" ><?php _e( 'ADS', 'wpdx' ); ?></a>
                  <a id="add-divider" ><?php _e( 'Divider', 'wpdx' ); ?></a>
                </div>
                  <a id="collapse-all"><?php _e( '[-] Collapse All', 'wpdx' ); ?></a>
                  <a id="expand-all"><?php _e( '[+] Expand All', 'wpdx' ); ?></a>
                  <div class="clear"></div>
                  <ul id="cat_sortable">
                    <?php
                    $cats = get_option( 'cmp_home_cats' ) ;
                    $i=0;
                    if($cats){
                      foreach ($cats as $cat) {
                        $i++;
                        ?>
                        <li id="listItem_<?php echo $i ?>" class="ui-state-default">
                          <?php
                          if( $cat['type'] == 'n' ) : ?>
                          <div class="widget-head"> <?php _e( 'News Box :', 'wpdx' ); ?> <?php if($cat['title']) echo $cat['title']; elseif(isset($cat['id'])) echo get_the_category_by_ID($cat['id'][0]) ?>
                            <a class="toggle-open">+</a>
                            <a class="toggle-close">-</a>
                          </div>
                          <div class="widget-content">
                          <label for="cmp_home_cats[<?php echo $i ?>][who]"><span><?php _e( 'Who can see this module:', 'wpdx' ); ?></span>
                                <select id="cmp_home_cats[<?php echo $i ?>][who]" name="cmp_home_cats[<?php echo $i ?>][who]">
                                  <option value="anyone" <?php if ( $cat['who'] == 'anyone') { echo ' selected="selected"' ; } ?>><?php _e( 'Anyone', 'wpdx' ); ?></option>
                                  <option value="logged" <?php if ( $cat['who'] == 'logged') { echo ' selected="selected"' ; } ?>><?php _e( 'Only logged in users', 'wpdx' ); ?></option>
                                  <option value="anonymous" <?php if ( $cat['who'] == 'anonymous') { echo ' selected="selected"' ; } ?>><?php _e( 'Only anonymous', 'wpdx' ); ?></option>
                                </select>
                            </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][post_ids]"><span>
                              <?php _e( 'Special Post Ids :', 'wpdx' ); ?></span><a class="mo-help tooltip" original-title="<?php _e('You can enter the posts ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7); or leave it blank, and then set the following options to query the psots.','wpdx') ?>"></a>
                              <textarea id="cmp_home_cats[<?php echo $i ?>][post_ids]" name="cmp_home_cats[<?php echo $i ?>][post_ids]" placeholder="<?php _e('You can enter the posts ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7); or leave it blank, and then set the following options to query the psots.','wpdx') ?>"><?php echo $cat['post_ids'];?></textarea>
                            </label>
                            <label><span style="float:left;"><?php _e( 'Choose Categories :', 'wpdx' ); ?> </span><a class="mo-help tooltip" original-title="<?php _e('Hold down the [ctrl] key to select or deselect multiple categories.','wpdx') ?>"></a>
                                <select multiple="multiple" name="cmp_home_cats[<?php echo $i ?>][id][]" id="cmp_home_cats[<?php echo $i ?>][id][]">
                                  <?php foreach ($categories as $key => $option) { ?>
                                  <option value="<?php echo $key ?>" <?php if ( @in_array( $key , $cat['id'] ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
                                  <?php } ?>
                                </select>
                              </label>
                            <label><span><?php _e( 'Posts Order :', 'wpdx' ); ?> </span>
                              <select name="cmp_home_cats[<?php echo $i ?>][order]" id="cmp_home_cats[<?php echo $i ?>][order]"><option value="latest" <?php if( $cat['order'] == 'latest' || $cat['order']=='' ) echo 'selected="selected"'; ?>><?php _e('Latest Posts','wpdx') ?></option><option  <?php if( $cat['order'] == 'rand' ) echo 'selected="selected"'; ?> value="rand"><?php _e('Random Posts','wpdx') ?></option>
                              </select>
                            </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][number]"><span>
                            <?php _e( 'Number of posts to show :', 'wpdx' ); ?></span><input style="width:50px;" id="cmp_home_cats[<?php echo $i ?>][number]" name="cmp_home_cats[<?php echo $i ?>][number]" value="<?php  echo $cat['number']  ?>" type="text" />
                            </label>
                            <label>
                              <span style="float:left; width:200px"><?php _e( 'Box Style :', 'wpdx' ); ?> </span>
                              <ul class="cmp-cats-options cmp-options">
                              <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="3c" <?php if( $cat['style'] == '3c' ) echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/3c.png" /></a>
                                </li>
                              <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="2c1" <?php if( $cat['style'] == '2c1' ) echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/2c1.png" /></a>
                                </li>
                              <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="2c" <?php if( $cat['style'] == '2c' ) echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/2c.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="li1" <?php if( $cat['style'] == 'li1' || $cat['style']=='' ) echo 'checked="checked"'; ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/li1.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="li" <?php if( $cat['style'] == 'li' || $cat['style']=='' ) echo 'checked="checked"'; ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/li.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="1c1" <?php if( $cat['style'] == '1c1') echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/1c1.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="1c" <?php if( $cat['style'] == '1c') echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/1c.png" /></a>
                                </li>
                              </ul>
                              <div class="clear"></div>
                            </label>
                            <label><span><?php _e( 'Thumb/Avatar :', 'wpdx' ); ?> </span>
                              <select name="cmp_home_cats[<?php echo $i ?>][thumb]" id="cmp_home_cats[<?php echo $i ?>][thumb]">
                              <option  <?php if( $cat['thumb'] == 'n' || $cat['thumb']=='' ) echo 'selected="selected"'; ?> value="n"><?php _e('Just title','wpdx') ?></option>
                              <option value="t" <?php if( $cat['thumb'] == 't' ) echo 'selected="selected"'; ?>><?php _e('Display thumb','wpdx') ?></option>
                              <option  <?php if( $cat['thumb'] == 'a' ) echo 'selected="selected"'; ?> value="a"><?php _e('Display avatar','wpdx') ?></option>
                              </select>
                            </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][title]"><span><?php _e( 'Box Title :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][title]" name="cmp_home_cats[<?php echo $i ?>][title]" value="<?php  echo $cat['title']  ?>" type="text" />
                              </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][icon]"><span>
                              <?php _e( 'Icon :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][icon]" name="cmp_home_cats[<?php echo $i ?>][icon]" value="<?php  echo $cat['icon']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_text]"><span>
                              <?php _e( 'More Text :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_text]" name="cmp_home_cats[<?php echo $i ?>][more_text]" value="<?php  echo $cat['more_text']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_url]"><span>
                              <?php _e( 'More Url :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_url]" name="cmp_home_cats[<?php echo $i ?>][more_url]" value="<?php  echo $cat['more_url']  ?>" type="text" />
                              </label>
                          <?php elseif( $cat['type'] == 'n-edd' ) : ?>
                          <div class="widget-head"> <?php _e( 'EDD News Box :', 'wpdx' ); ?> <?php $edd_cat = get_term( $cat['id'][0], 'download_category'); if($cat['title']) echo $cat['title']; elseif(isset($cat['id'])) echo $edd_cat->name; ?>
                            <a class="toggle-open">+</a>
                            <a class="toggle-close">-</a>
                          </div>
                          <div class="widget-content">
                          <label for="cmp_home_cats[<?php echo $i ?>][who]"><span><?php _e( 'Who can see this module:', 'wpdx' ); ?></span>
                                <select id="cmp_home_cats[<?php echo $i ?>][who]" name="cmp_home_cats[<?php echo $i ?>][who]">
                                  <option value="anyone" <?php if ( $cat['who'] == 'anyone') { echo ' selected="selected"' ; } ?>><?php _e( 'Anyone', 'wpdx' ); ?></option>
                                  <option value="logged" <?php if ( $cat['who'] == 'logged') { echo ' selected="selected"' ; } ?>><?php _e( 'Only logged in users', 'wpdx' ); ?></option>
                                  <option value="anonymous" <?php if ( $cat['who'] == 'anonymous') { echo ' selected="selected"' ; } ?>><?php _e( 'Only anonymous', 'wpdx' ); ?></option>
                                </select>
                            </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][post_ids]"><span>
                              <?php _e( 'Special Post Ids :', 'wpdx' ); ?></span><a class="mo-help tooltip" original-title="<?php _e('You can enter the posts ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7); or leave it blank, and then set the following options to query the psots.','wpdx') ?>"></a>
                              <textarea id="cmp_home_cats[<?php echo $i ?>][post_ids]" name="cmp_home_cats[<?php echo $i ?>][post_ids]" placeholder="<?php _e('You can enter the posts ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7); or leave it blank, and then set the following options to query the psots.','wpdx') ?>"><?php echo $cat['post_ids'];?></textarea>
                            </label>
                            <label><span style="float:left;"><?php _e( 'Choose Categories :', 'wpdx' ); ?> </span><a class="mo-help tooltip" original-title="<?php _e('Hold down the [ctrl] key to select or deselect multiple categories.','wpdx') ?>"></a>
                                <select multiple="multiple" name="cmp_home_cats[<?php echo $i ?>][id][]" id="cmp_home_cats[<?php echo $i ?>][id][]">
                                  <?php foreach ($edd_categories as $key => $option) { ?>
                                  <option value="<?php echo $key ?>" <?php if ( @in_array( $key , $cat['id'] ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
                                  <?php } ?>
                                </select>
                              </label>
                            <label><span><?php _e( 'Posts Order :', 'wpdx' ); ?> </span>
                              <select name="cmp_home_cats[<?php echo $i ?>][order]" id="cmp_home_cats[<?php echo $i ?>][order]"><option value="latest" <?php if( $cat['order'] == 'latest' || $cat['order']=='' ) echo 'selected="selected"'; ?>><?php _e('Latest Posts','wpdx') ?></option><option  <?php if( $cat['order'] == 'rand' ) echo 'selected="selected"'; ?> value="rand"><?php _e('Random Posts','wpdx') ?></option>
                              </select>
                            </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][number]"><span>
                            <?php _e( 'Number of posts to show :', 'wpdx' ); ?></span><input style="width:50px;" id="cmp_home_cats[<?php echo $i ?>][number]" name="cmp_home_cats[<?php echo $i ?>][number]" value="<?php  echo $cat['number']  ?>" type="text" />
                            </label>
                            <label>
                              <span style="float:left; width:200px"><?php _e( 'Box Style :', 'wpdx' ); ?> </span>
                              <ul class="cmp-cats-options cmp-options">
                              <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="3c" <?php if( $cat['style'] == '3c' ) echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/3c.png" /></a>
                                </li>
                              <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="2c1" <?php if( $cat['style'] == '2c1' ) echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/2c1.png" /></a>
                                </li>
                              <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="2c" <?php if( $cat['style'] == '2c' ) echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/2c.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="li1" <?php if( $cat['style'] == 'li1' || $cat['style']=='' ) echo 'checked="checked"'; ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/li1.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="li" <?php if( $cat['style'] == 'li' || $cat['style']=='' ) echo 'checked="checked"'; ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/li.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="1c1" <?php if( $cat['style'] == '1c1') echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/1c1.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="1c" <?php if( $cat['style'] == '1c') echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/1c.png" /></a>
                                </li>
                              </ul>
                              <div class="clear"></div>
                            </label>
                            <label><span><?php _e( 'Thumb/Avatar :', 'wpdx' ); ?> </span>
                              <select name="cmp_home_cats[<?php echo $i ?>][thumb]" id="cmp_home_cats[<?php echo $i ?>][thumb]">
                              <option  <?php if( $cat['thumb'] == 'n' || $cat['thumb']=='' ) echo 'selected="selected"'; ?> value="n"><?php _e('Just title','wpdx') ?></option>
                              <option value="t" <?php if( $cat['thumb'] == 't' ) echo 'selected="selected"'; ?>><?php _e('Display thumb','wpdx') ?></option>
                              <option  <?php if( $cat['thumb'] == 'a' ) echo 'selected="selected"'; ?> value="a"><?php _e('Display avatar','wpdx') ?></option>
                              </select>
                            </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][title]"><span><?php _e( 'Box Title :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][title]" name="cmp_home_cats[<?php echo $i ?>][title]" value="<?php  echo $cat['title']  ?>" type="text" />
                              </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][icon]"><span>
                              <?php _e( 'Icon :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][icon]" name="cmp_home_cats[<?php echo $i ?>][icon]" value="<?php  echo $cat['icon']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_text]"><span>
                              <?php _e( 'More Text :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_text]" name="cmp_home_cats[<?php echo $i ?>][more_text]" value="<?php  echo $cat['more_text']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_url]"><span>
                              <?php _e( 'More Url :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_url]" name="cmp_home_cats[<?php echo $i ?>][more_url]" value="<?php  echo $cat['more_url']  ?>" type="text" />
                              </label>
                          <?php elseif( $cat['type'] == 'tabs' ) : ?>
                          <div class="widget-head"> <?php _e( 'Categories Tabs Box :', 'wpdx' ); ?> <?php if($cat['title']) echo $cat['title']; elseif(isset($cat['id'])) echo get_the_category_by_ID($cat['id'][0]) ?>
                            <a class="toggle-open">+</a>
                            <a class="toggle-close">-</a>
                          </div>
                          <div class="widget-content">
                          <label for="cmp_home_cats[<?php echo $i ?>][who]"><span><?php _e( 'Who can see this module:', 'wpdx' ); ?></span>
                                <select id="cmp_home_cats[<?php echo $i ?>][who]" name="cmp_home_cats[<?php echo $i ?>][who]">
                                  <option value="anyone" <?php if ( $cat['who'] == 'anyone') { echo ' selected="selected"' ; } ?>><?php _e( 'Anyone', 'wpdx' ); ?></option>
                                  <option value="logged" <?php if ( $cat['who'] == 'logged') { echo ' selected="selected"' ; } ?>><?php _e( 'Only logged in users', 'wpdx' ); ?></option>
                                  <option value="anonymous" <?php if ( $cat['who'] == 'anonymous') { echo ' selected="selected"' ; } ?>><?php _e( 'Only anonymous', 'wpdx' ); ?></option>
                                </select>
                            </label>
                            <label><span style="float:left;"><?php _e( 'Choose Categories :', 'wpdx' ); ?> </span><a class="mo-help tooltip" original-title="<?php _e('Hold down the [ctrl] key to select or deselect multiple categories.','wpdx') ?>"></a>
                                <select multiple="multiple" name="cmp_home_cats[<?php echo $i ?>][id][]" id="cmp_home_cats[<?php echo $i ?>][id][]">
                                  <?php foreach ($categories as $key => $option) { ?>
                                  <option value="<?php echo $key ?>" <?php if ( @in_array( $key , $cat['id'] ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
                                  <?php } ?>
                                </select>
                              </label>
                            <label><span><?php _e( 'Posts Order :', 'wpdx' ); ?> </span>
                              <select name="cmp_home_cats[<?php echo $i ?>][order]" id="cmp_home_cats[<?php echo $i ?>][order]"><option value="latest" <?php if( $cat['order'] == 'latest' || $cat['order']=='' ) echo 'selected="selected"'; ?>><?php _e('Latest Posts','wpdx') ?></option><option  <?php if( $cat['order'] == 'rand' ) echo 'selected="selected"'; ?> value="rand"><?php _e('Random Posts','wpdx') ?></option>
                              </select>
                            </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][number]"><span>
                            <?php _e( 'Number of posts to show :', 'wpdx' ); ?></span><input style="width:50px;" id="cmp_home_cats[<?php echo $i ?>][number]" name="cmp_home_cats[<?php echo $i ?>][number]" value="<?php  echo $cat['number']  ?>" type="text" />
                            </label>
                            <label>
                              <span style="float:left; width:200px"><?php _e( 'Box Style :', 'wpdx' ); ?> </span>
                              <ul class="cmp-cats-options cmp-options">
                              <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="3c" <?php if( $cat['style'] == '3c' ) echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/3c.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="2c1" <?php if( $cat['style'] == '2c1' ) echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/2c1.png" /></a>
                                </li>
                              <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="2c" <?php if( $cat['style'] == '2c' ) echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/2c.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="li1" <?php if( $cat['style'] == 'li1' || $cat['style']=='' ) echo 'checked="checked"'; ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/li1.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="li" <?php if( $cat['style'] == 'li' || $cat['style']=='' ) echo 'checked="checked"'; ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/li.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="1c1" <?php if( $cat['style'] == '1c1') echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/1c1.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="1c" <?php if( $cat['style'] == '1c') echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/1c.png" /></a>
                                </li>
                              </ul>
                              <div class="clear"></div>
                            </label>
                            <label><span><?php _e( 'Thumb/Avatar :', 'wpdx' ); ?> </span>
                              <select name="cmp_home_cats[<?php echo $i ?>][thumb]" id="cmp_home_cats[<?php echo $i ?>][thumb]">
                              <option  <?php if( $cat['thumb'] == 'n' || $cat['thumb']=='' ) echo 'selected="selected"'; ?> value="n"><?php _e('Just title','wpdx') ?></option>
                              <option value="t" <?php if( $cat['thumb'] == 't' ) echo 'selected="selected"'; ?>><?php _e('Display thumb','wpdx') ?></option>
                              <option  <?php if( $cat['thumb'] == 'a' ) echo 'selected="selected"'; ?> value="a"><?php _e('Display avatar','wpdx') ?></option>
                              </select>
                            </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][title]"><span><?php _e( 'Box Title :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][title]" name="cmp_home_cats[<?php echo $i ?>][title]" value="<?php  echo $cat['title']  ?>" type="text" />
                              </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][icon]"><span>
                              <?php _e( 'Icon :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][icon]" name="cmp_home_cats[<?php echo $i ?>][icon]" value="<?php  echo $cat['icon']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_text]"><span>
                              <?php _e( 'More Text :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_text]" name="cmp_home_cats[<?php echo $i ?>][more_text]" value="<?php  echo $cat['more_text']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_url]"><span>
                              <?php _e( 'More Url :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_url]" name="cmp_home_cats[<?php echo $i ?>][more_url]" value="<?php  echo $cat['more_url']  ?>" type="text" />
                              </label>
                          <?php elseif( $cat['type'] == 'tabs-edd' ) : ?>
                          <div class="widget-head"> <?php _e( 'EDD Categories Tabs Box :', 'wpdx' ); ?> <?php $edd_cat = get_term( $cat['id'][0], 'download_category'); if($cat['title']) echo $cat['title']; elseif(isset($cat['id'])) echo $edd_cat->name; ?>
                            <a class="toggle-open">+</a>
                            <a class="toggle-close">-</a>
                          </div>
                          <div class="widget-content">
                          <label for="cmp_home_cats[<?php echo $i ?>][who]"><span><?php _e( 'Who can see this module:', 'wpdx' ); ?></span>
                                <select id="cmp_home_cats[<?php echo $i ?>][who]" name="cmp_home_cats[<?php echo $i ?>][who]">
                                  <option value="anyone" <?php if ( $cat['who'] == 'anyone') { echo ' selected="selected"' ; } ?>><?php _e( 'Anyone', 'wpdx' ); ?></option>
                                  <option value="logged" <?php if ( $cat['who'] == 'logged') { echo ' selected="selected"' ; } ?>><?php _e( 'Only logged in users', 'wpdx' ); ?></option>
                                  <option value="anonymous" <?php if ( $cat['who'] == 'anonymous') { echo ' selected="selected"' ; } ?>><?php _e( 'Only anonymous', 'wpdx' ); ?></option>
                                </select>
                            </label>
                            <label><span style="float:left;"><?php _e( 'Choose Categories :', 'wpdx' ); ?> </span><a class="mo-help tooltip" original-title="<?php _e('Hold down the [ctrl] key to select or deselect multiple categories.','wpdx') ?>"></a>
                                <select multiple="multiple" name="cmp_home_cats[<?php echo $i ?>][id][]" id="cmp_home_cats[<?php echo $i ?>][id][]">
                                  <?php foreach ($edd_categories as $key => $option) { ?>
                                  <option value="<?php echo $key ?>" <?php if ( @in_array( $key , $cat['id'] ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
                                  <?php } ?>
                                </select>
                              </label>
                            <label><span><?php _e( 'Posts Order :', 'wpdx' ); ?> </span>
                              <select name="cmp_home_cats[<?php echo $i ?>][order]" id="cmp_home_cats[<?php echo $i ?>][order]"><option value="latest" <?php if( $cat['order'] == 'latest' || $cat['order']=='' ) echo 'selected="selected"'; ?>><?php _e('Latest Posts','wpdx') ?></option><option  <?php if( $cat['order'] == 'rand' ) echo 'selected="selected"'; ?> value="rand"><?php _e('Random Posts','wpdx') ?></option>
                              </select>
                            </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][number]"><span>
                            <?php _e( 'Number of posts to show :', 'wpdx' ); ?></span><input style="width:50px;" id="cmp_home_cats[<?php echo $i ?>][number]" name="cmp_home_cats[<?php echo $i ?>][number]" value="<?php  echo $cat['number']  ?>" type="text" />
                            </label>
                            <label>
                              <span style="float:left; width:200px"><?php _e( 'Box Style :', 'wpdx' ); ?> </span>
                              <ul class="cmp-cats-options cmp-options">
                              <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="3c" <?php if( $cat['style'] == '3c' ) echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/3c.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="2c1" <?php if( $cat['style'] == '2c1' ) echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/2c1.png" /></a>
                                </li>
                              <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="2c" <?php if( $cat['style'] == '2c' ) echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/2c.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="li1" <?php if( $cat['style'] == 'li1' || $cat['style']=='' ) echo 'checked="checked"'; ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/li1.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="li" <?php if( $cat['style'] == 'li' || $cat['style']=='' ) echo 'checked="checked"'; ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/li.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="1c1" <?php if( $cat['style'] == '1c1') echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/1c1.png" /></a>
                                </li>
                                <li>
                                  <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="1c" <?php if( $cat['style'] == '1c') echo 'checked="checked"' ?> />
                                  <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/1c.png" /></a>
                                </li>
                              </ul>
                              <div class="clear"></div>
                            </label>
                            <label><span><?php _e( 'Thumb/Avatar :', 'wpdx' ); ?> </span>
                              <select name="cmp_home_cats[<?php echo $i ?>][thumb]" id="cmp_home_cats[<?php echo $i ?>][thumb]">
                              <option  <?php if( $cat['thumb'] == 'n' || $cat['thumb']=='' ) echo 'selected="selected"'; ?> value="n"><?php _e('Just title','wpdx') ?></option>
                              <option value="t" <?php if( $cat['thumb'] == 't' ) echo 'selected="selected"'; ?>><?php _e('Display thumb','wpdx') ?></option>
                              <option  <?php if( $cat['thumb'] == 'a' ) echo 'selected="selected"'; ?> value="a"><?php _e('Display avatar','wpdx') ?></option>
                              </select>
                            </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][title]"><span><?php _e( 'Box Title :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][title]" name="cmp_home_cats[<?php echo $i ?>][title]" value="<?php  echo $cat['title']  ?>" type="text" />
                              </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][icon]"><span>
                              <?php _e( 'Icon :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][icon]" name="cmp_home_cats[<?php echo $i ?>][icon]" value="<?php  echo $cat['icon']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_text]"><span>
                              <?php _e( 'More Text :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_text]" name="cmp_home_cats[<?php echo $i ?>][more_text]" value="<?php  echo $cat['more_text']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_url]"><span>
                              <?php _e( 'More Url :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_url]" name="cmp_home_cats[<?php echo $i ?>][more_url]" value="<?php  echo $cat['more_url']  ?>" type="text" />
                              </label>
                            <?php
                            elseif( $cat['type'] == 'recent' ) :  ?>
                            <div class="widget-head"> <?php _e( 'Recent/Random/Hot Posts :', 'wpdx' ); ?> <?php if($cat['title']) echo $cat['title'];?>
                              <a class="toggle-open">+</a>
                              <a class="toggle-close">-</a>
                            </div>
                            <div class="widget-content">
                            <label for="cmp_home_cats[<?php echo $i ?>][who]"><span><?php _e( 'Who can see this module:', 'wpdx' ); ?></span>
                                <select id="cmp_home_cats[<?php echo $i ?>][who]" name="cmp_home_cats[<?php echo $i ?>][who]">
                                  <option value="anyone" <?php if ( $cat['who'] == 'anyone') { echo ' selected="selected"' ; } ?>><?php _e( 'Anyone', 'wpdx' ); ?></option>
                                  <option value="logged" <?php if ( $cat['who'] == 'logged') { echo ' selected="selected"' ; } ?>><?php _e( 'Only logged in users', 'wpdx' ); ?></option>
                                  <option value="anonymous" <?php if ( $cat['who'] == 'anonymous') { echo ' selected="selected"' ; } ?>><?php _e( 'Only anonymous', 'wpdx' ); ?></option>
                                </select>
                              </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][post_ids]"><span>
                              <?php _e( 'Special Post Ids :', 'wpdx' ); ?></span><a class="mo-help tooltip" original-title="<?php _e('You can enter the posts ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7); or leave it blank, and then set the following options to query the psots.','wpdx') ?>"></a>
                              <textarea id="cmp_home_cats[<?php echo $i ?>][post_ids]" name="cmp_home_cats[<?php echo $i ?>][post_ids]" placeholder="<?php _e('You can enter the posts ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7); or leave it blank, and then set the following options to query the psots.','wpdx') ?>"><?php echo $cat['post_ids'];?></textarea>
                            </label>
                              <label><span style="float:left;"><?php _e( 'Select Custom Post Type :', 'wpdx' ); ?> </span><a class="mo-help tooltip" original-title="<?php _e('Hold down the [ctrl] key to select or deselect multiple post types.','wpdx') ?>"></a>
                                <select multiple="multiple" name="cmp_home_cats[<?php echo $i ?>][post_type][]" id="cmp_home_cats[<?php echo $i ?>][post_type][]">
                                <?php
                                foreach ($post_types as $post_type) { ?>
                                <option value="<?php echo $post_type; ?>" <?php if ( isset($cat['post_type']) && in_array( $post_type , $cat['post_type'] ) ) { echo ' selected="selected"' ; } ?>><?php echo $post_type; ?></option>
                                <?php } ?>
                                </select>
                              </label>
                              <label><span style="float:left;"><?php _e( 'Exclude Categories :', 'wpdx' ); ?> </span><a class="mo-help tooltip" original-title="<?php _e('Hold down the [ctrl] key to select or deselect multiple categories.','wpdx') ?>"></a>
                                <select multiple="multiple" name="cmp_home_cats[<?php echo $i ?>][exclude][]" id="cmp_home_cats[<?php echo $i ?>][exclude][]">
                                  <?php foreach ($categories as $key => $option) { ?>
                                  <option value="<?php echo $key ?>" <?php if ( @in_array( $key , $cat['exclude'] ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
                                  <?php } ?>
                                </select>
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][number]"><span>
                              <?php _e( 'Number of posts to show :', 'wpdx' ); ?></span><input style="width:50px;" id="cmp_home_cats[<?php echo $i ?>][number]" name="cmp_home_cats[<?php echo $i ?>][number]" value="<?php  echo $cat['number']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][order]"><span><?php _e( 'Order:', 'wpdx' ); ?></span>
                                <select id="cmp_home_cats[<?php echo $i ?>][order]" name="cmp_home_cats[<?php echo $i ?>][order]">
                                  <option value="latest" <?php if ( $cat['order'] == 'latest') { echo ' selected="selected"' ; } ?>><?php _e( 'Latest', 'wpdx' ); ?></option>
                                  <option value="modified" <?php if ( $cat['order'] == 'modified') { echo ' selected="selected"' ; } ?>><?php _e( 'Last Modified', 'wpdx' ); ?></option>
                                  <option value="random" <?php if ( $cat['order'] == 'random') { echo ' selected="selected"' ; } ?>><?php _e( 'Random', 'wpdx' ); ?></option>
                                  <option value="stick" <?php if ( $cat['order'] == 'stick') { echo ' selected="selected"' ; } ?>><?php _e( 'Stick', 'wpdx' ); ?></option>
                                  <option value="most_comment" <?php if ( $cat['order'] == 'most_comment') { echo ' selected="selected"' ; } ?>><?php _e( 'Most Comment', 'wpdx' ); ?></option>
                                  <option value="most_viewed" <?php if ( $cat['order'] == 'most_viewed') { echo ' selected="selected"' ; } ?>><?php _e( 'Most Viewed', 'wpdx' ); ?></option>
                                </select>
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][days]"><span><?php _e( 'Days limit of popular :', 'wpdx' ); ?></span><a class="mo-help tooltip" original-title="<?php _e('Only when the Order is Most Comment or Most Viewed, this option is to take effect.','wpdx') ?>"></a><input style="width:50px;" id="cmp_home_cats[<?php echo $i ?>][days]" name="cmp_home_cats[<?php echo $i ?>][days]" value="<?php  echo $cat['days']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][hours]"><span><?php _e( 'Highlight posts\'s date for X hours :', 'wpdx' ); ?></span><a class="mo-help tooltip" original-title="<?php _e('Default is 24 hours. Set 0 to disable.','wpdx') ?>"></a><input style="width:50px;" id="cmp_home_cats[<?php echo $i ?>][hours]" name="cmp_home_cats[<?php echo $i ?>][hours]" value="<?php  echo $cat['hours']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][title]"><span>
                              <?php _e( 'Box Title :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][title]" name="cmp_home_cats[<?php echo $i ?>][title]" value="<?php  echo $cat['title']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][icon]"><span>
                              <?php _e( 'Icon :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][icon]" name="cmp_home_cats[<?php echo $i ?>][icon]" value="<?php  echo $cat['icon']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_text]"><span>
                              <?php _e( 'More Text :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_text]" name="cmp_home_cats[<?php echo $i ?>][more_text]" value="<?php  echo $cat['more_text']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_url]"><span>
                              <?php _e( 'More Url :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_url]" name="cmp_home_cats[<?php echo $i ?>][more_url]" value="<?php  echo $cat['more_url']  ?>" type="text" />
                              </label>
                            <?php elseif( $cat['type'] == 's' ) : ?>
                            <div class="widget-head scrolling-box"> <?php _e( 'Scrolling Box :', 'wpdx' ); ?> <?php if($cat['title']) echo $cat['title']; elseif(isset($cat['id'])) echo get_the_category_by_ID($cat['id'][0]) ?>
                              <a class="toggle-open">+</a>
                              <a class="toggle-close">-</a>
                            </div>
                            <div class="widget-content">
                            <label for="cmp_home_cats[<?php echo $i ?>][who]"><span><?php _e( 'Who can see this module:', 'wpdx' ); ?></span>
                                <select id="cmp_home_cats[<?php echo $i ?>][who]" name="cmp_home_cats[<?php echo $i ?>][who]">
                                  <option value="anyone" <?php if ( $cat['who'] == 'anyone') { echo ' selected="selected"' ; } ?>><?php _e( 'Anyone', 'wpdx' ); ?></option>
                                  <option value="logged" <?php if ( $cat['who'] == 'logged') { echo ' selected="selected"' ; } ?>><?php _e( 'Only logged in users', 'wpdx' ); ?></option>
                                  <option value="anonymous" <?php if ( $cat['who'] == 'anonymous') { echo ' selected="selected"' ; } ?>><?php _e( 'Only anonymous', 'wpdx' ); ?></option>
                                </select>
                              </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][post_ids]"><span>
                              <?php _e( 'Special Post Ids :', 'wpdx' ); ?></span><a class="mo-help tooltip" original-title="<?php _e('You can enter the posts ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7); or leave it blank, and then set the following options to query the psots.','wpdx') ?>"></a>
                              <textarea id="cmp_home_cats[<?php echo $i ?>][post_ids]" name="cmp_home_cats[<?php echo $i ?>][post_ids]" placeholder="<?php _e('You can enter the posts ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7); or leave it blank, and then set the following options to query the psots.','wpdx') ?>"><?php echo $cat['post_ids'];?></textarea>
                            </label>
                              <label><span style="float:left;"><?php _e( 'Choose Categories :', 'wpdx' ); ?> </span><a class="mo-help tooltip" original-title="<?php _e('Hold down the [ctrl] key to select or deselect multiple categories.','wpdx') ?>"></a>
                                <select multiple="multiple" name="cmp_home_cats[<?php echo $i ?>][id][]" id="cmp_home_cats[<?php echo $i ?>][id][]">
                                  <?php foreach ($categories as $key => $option) { ?>
                                  <option value="<?php echo $key ?>" <?php if ( @in_array( $key , $cat['id'] ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
                                  <?php } ?>
                                </select>
                              </label>
                              <label><span><?php _e( 'Posts Order :', 'wpdx' ); ?> </span>
                              <select name="cmp_home_cats[<?php echo $i ?>][order]" id="cmp_home_cats[<?php echo $i ?>][order]"><option value="latest" <?php if( $cat['order'] == 'latest' || $cat['order']=='' ) echo 'selected="selected"'; ?>><?php _e('Latest Posts','wpdx') ?></option><option  <?php if( $cat['order'] == 'rand' ) echo 'selected="selected"'; ?> value="rand"><?php _e('Random Posts','wpdx') ?></option>
                              </select>
                            </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][number]"><span><?php _e( 'Number of posts to show :', 'wpdx' ); ?></span><input style="width:50px;" id="cmp_home_cats[<?php echo $i ?>][number]" name="cmp_home_cats[<?php echo $i ?>][number]" value="<?php  echo $cat['number']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][title]"><span><?php _e( 'Box Title :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][title]" name="cmp_home_cats[<?php echo $i ?>][title]" value="<?php  echo $cat['title']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][icon]"><span>
                              <?php _e( 'Icon :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][icon]" name="cmp_home_cats[<?php echo $i ?>][icon]" value="<?php  echo $cat['icon']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_text]"><span>
                              <?php _e( 'More Text :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_text]" name="cmp_home_cats[<?php echo $i ?>][more_text]" value="<?php  echo $cat['more_text']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_url]"><span>
                              <?php _e( 'More Url :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_url]" name="cmp_home_cats[<?php echo $i ?>][more_url]" value="<?php  echo $cat['more_url']  ?>" type="text" />
                              </label>
                            <?php elseif( $cat['type'] == 's-edd' ) : ?>
                            <div class="widget-head scrolling-box"> <?php _e( 'EDD Scrolling Box :', 'wpdx' ); ?> <?php $edd_cat = get_term( $cat['id'][0], 'download_category'); if($cat['title']) echo $cat['title']; elseif(isset($cat['id'])) echo $edd_cat->name; ?>
                              <a class="toggle-open">+</a>
                              <a class="toggle-close">-</a>
                            </div>
                            <div class="widget-content">
                            <label for="cmp_home_cats[<?php echo $i ?>][who]"><span><?php _e( 'Who can see this module:', 'wpdx' ); ?></span>
                                <select id="cmp_home_cats[<?php echo $i ?>][who]" name="cmp_home_cats[<?php echo $i ?>][who]">
                                  <option value="anyone" <?php if ( $cat['who'] == 'anyone') { echo ' selected="selected"' ; } ?>><?php _e( 'Anyone', 'wpdx' ); ?></option>
                                  <option value="logged" <?php if ( $cat['who'] == 'logged') { echo ' selected="selected"' ; } ?>><?php _e( 'Only logged in users', 'wpdx' ); ?></option>
                                  <option value="anonymous" <?php if ( $cat['who'] == 'anonymous') { echo ' selected="selected"' ; } ?>><?php _e( 'Only anonymous', 'wpdx' ); ?></option>
                                </select>
                              </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][post_ids]"><span>
                              <?php _e( 'Special Post Ids :', 'wpdx' ); ?></span><a class="mo-help tooltip" original-title="<?php _e('You can enter the posts ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7); or leave it blank, and then set the following options to query the psots.','wpdx') ?>"></a>
                              <textarea id="cmp_home_cats[<?php echo $i ?>][post_ids]" name="cmp_home_cats[<?php echo $i ?>][post_ids]" placeholder="<?php _e('You can enter the posts ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7); or leave it blank, and then set the following options to query the psots.','wpdx') ?>"><?php echo $cat['post_ids'];?></textarea>
                            </label>
                              <label><span style="float:left;"><?php _e( 'Choose Categories :', 'wpdx' ); ?> </span><a class="mo-help tooltip" original-title="<?php _e('Hold down the [ctrl] key to select or deselect multiple categories.','wpdx') ?>"></a>
                                <select multiple="multiple" name="cmp_home_cats[<?php echo $i ?>][id][]" id="cmp_home_cats[<?php echo $i ?>][id][]">
                                  <?php foreach ($edd_categories as $key => $option) { ?>
                                  <option value="<?php echo $key ?>" <?php if ( @in_array( $key , $cat['id'] ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
                                  <?php } ?>
                                </select>
                              </label>
                              <label><span><?php _e( 'Posts Order :', 'wpdx' ); ?> </span>
                              <select name="cmp_home_cats[<?php echo $i ?>][order]" id="cmp_home_cats[<?php echo $i ?>][order]"><option value="latest" <?php if( $cat['order'] == 'latest' || $cat['order']=='' ) echo 'selected="selected"'; ?>><?php _e('Latest Posts','wpdx') ?></option><option  <?php if( $cat['order'] == 'rand' ) echo 'selected="selected"'; ?> value="rand"><?php _e('Random Posts','wpdx') ?></option>
                              </select>
                            </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][number]"><span><?php _e( 'Number of posts to show :', 'wpdx' ); ?></span><input style="width:50px;" id="cmp_home_cats[<?php echo $i ?>][number]" name="cmp_home_cats[<?php echo $i ?>][number]" value="<?php  echo $cat['number']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][title]"><span><?php _e( 'Box Title :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][title]" name="cmp_home_cats[<?php echo $i ?>][title]" value="<?php  echo $cat['title']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][icon]"><span>
                              <?php _e( 'Icon :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][icon]" name="cmp_home_cats[<?php echo $i ?>][icon]" value="<?php  echo $cat['icon']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_text]"><span>
                              <?php _e( 'More Text :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_text]" name="cmp_home_cats[<?php echo $i ?>][more_text]" value="<?php  echo $cat['more_text']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_url]"><span>
                              <?php _e( 'More Url :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_url]" name="cmp_home_cats[<?php echo $i ?>][more_url]" value="<?php  echo $cat['more_url']  ?>" type="text" />
                              </label>
                          <?php elseif( $cat['type'] == 'ads' ) : ?>
                            <div class="widget-head g-box"><?php _e( 'ADS', 'wpdx' ); ?>
                              <a class="toggle-open">+</a>
                              <a class="toggle-close">-</a>
                            </div>
                            <div class="widget-content">
                            <label for="cmp_home_cats[<?php echo $i ?>][who]"><span><?php _e( 'Who can see this module:', 'wpdx' ); ?></span>
                                <select id="cmp_home_cats[<?php echo $i ?>][who]" name="cmp_home_cats[<?php echo $i ?>][who]">
                                  <option value="anyone" <?php if ( $cat['who'] == 'anyone') { echo ' selected="selected"' ; } ?>><?php _e( 'Anyone', 'wpdx' ); ?></option>
                                  <option value="logged" <?php if ( $cat['who'] == 'logged') { echo ' selected="selected"' ; } ?>><?php _e( 'Only logged in users', 'wpdx' ); ?></option>
                                  <option value="anonymous" <?php if ( $cat['who'] == 'anonymous') { echo ' selected="selected"' ; } ?>><?php _e( 'Only anonymous', 'wpdx' ); ?></option>
                                </select>
                              </label>
                              <textarea cols="36" rows="5" name="cmp_home_cats[<?php echo $i ?>][text]" id="cmp_home_cats[<?php echo $i ?>][text]"><?php echo stripslashes($cat['text']) ; ?></textarea>
                          <?php elseif( $cat['type'] == 'users' ) : ?>
                            <div class="widget-head g-box"><?php _e( 'Users :', 'wpdx' ); ?><?php echo $cat['title']  ?>
                              <a class="toggle-open">+</a>
                              <a class="toggle-close">-</a>
                            </div>
                            <div class="widget-content">
                            <label for="cmp_home_cats[<?php echo $i ?>][who]"><span><?php _e( 'Who can see this module:', 'wpdx' ); ?></span>
                                <select id="cmp_home_cats[<?php echo $i ?>][who]" name="cmp_home_cats[<?php echo $i ?>][who]">
                                  <option value="anyone" <?php if ( $cat['who'] == 'anyone') { echo ' selected="selected"' ; } ?>><?php _e( 'Anyone', 'wpdx' ); ?></option>
                                  <option value="logged" <?php if ( $cat['who'] == 'logged') { echo ' selected="selected"' ; } ?>><?php _e( 'Only logged in users', 'wpdx' ); ?></option>
                                  <option value="anonymous" <?php if ( $cat['who'] == 'anonymous') { echo ' selected="selected"' ; } ?>><?php _e( 'Only anonymous', 'wpdx' ); ?></option>
                                </select>
                              </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][user]"><span>
                              <?php _e( 'User IDs :', 'wpdx' ); ?></span>
                              <a class="mo-help tooltip" original-title="<?php _e('You can enter the user ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7).ID number should be a multiple of 10, such as 10, 20 or 30 ids.','wpdx'); ?>"></a>
                              <textarea cols="30" rows="5" name="cmp_home_cats[<?php echo $i ?>][user]" id="cmp_home_cats[<?php echo $i ?>][user]" placeholder="<?php _e('You can enter the user ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7).ID number should be a multiple of 10, such as 10, 20 or 30 ids.','wpdx'); ?>"><?php echo stripslashes($cat['user']) ; ?></textarea>
                            </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][title]"><span><?php _e( 'Box Title :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][title]" name="cmp_home_cats[<?php echo $i ?>][title]" value="<?php  echo $cat['title']  ?>" type="text" />
                              </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][icon]"><span>
                              <?php _e( 'Icon :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][icon]" name="cmp_home_cats[<?php echo $i ?>][icon]" value="<?php  echo $cat['icon']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_text]"><span>
                              <?php _e( 'More Text :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_text]" name="cmp_home_cats[<?php echo $i ?>][more_text]" value="<?php  echo $cat['more_text']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_url]"><span>
                              <?php _e( 'More Url :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_url]" name="cmp_home_cats[<?php echo $i ?>][more_url]" value="<?php  echo $cat['more_url']  ?>" type="text" />
                              </label>
                          <?php elseif( $cat['type'] == 'news-pic' ) : ?>
                            <div class="widget-head news-pic-box"><?php _e( 'News In Picture :', 'wpdx' );  ?><?php if($cat['title']) echo $cat['title']; elseif(isset($cat['id'])) echo get_the_category_by_ID($cat['id'][0]) ?>
                              <a class="toggle-open">+</a>
                              <a class="toggle-close">-</a>
                            </div>
                            <div class="widget-content">
                            <label for="cmp_home_cats[<?php echo $i ?>][who]"><span><?php _e( 'Who can see this module:', 'wpdx' ); ?></span>
                                <select id="cmp_home_cats[<?php echo $i ?>][who]" name="cmp_home_cats[<?php echo $i ?>][who]">
                                  <option value="anyone" <?php if ( $cat['who'] == 'anyone') { echo ' selected="selected"' ; } ?>><?php _e( 'Anyone', 'wpdx' ); ?></option>
                                  <option value="logged" <?php if ( $cat['who'] == 'logged') { echo ' selected="selected"' ; } ?>><?php _e( 'Only logged in users', 'wpdx' ); ?></option>
                                  <option value="anonymous" <?php if ( $cat['who'] == 'anonymous') { echo ' selected="selected"' ; } ?>><?php _e( 'Only anonymous', 'wpdx' ); ?></option>
                                </select>
                              </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][post_ids]"><span>
                              <?php _e( 'Special Post Ids :', 'wpdx' ); ?></span><a class="mo-help tooltip" original-title="<?php _e('You can enter the posts ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7); or leave it blank, and then set the following options to query the psots.','wpdx') ?>"></a>
                              <textarea id="cmp_home_cats[<?php echo $i ?>][post_ids]" name="cmp_home_cats[<?php echo $i ?>][post_ids]" placeholder="<?php _e('You can enter the posts ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7); or leave it blank, and then set the following options to query the psots.','wpdx') ?>"><?php echo $cat['post_ids'];?></textarea>
                            </label>
                              <label><span style="float:left;"><?php _e( 'Choose Categories :', 'wpdx' ); ?> </span><a class="mo-help tooltip" original-title="<?php _e('Hold down the [ctrl] key to select or deselect multiple categories.','wpdx') ?>"></a>
                                <select multiple="multiple" name="cmp_home_cats[<?php echo $i ?>][id][]" id="cmp_home_cats[<?php echo $i ?>][id][]">
                                  <?php foreach ($categories as $key => $option) { ?>
                                  <option value="<?php echo $key ?>" <?php if ( @in_array( $key , $cat['id'] ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
                                  <?php } ?>
                                </select>
                              </label>
                              <label><span><?php _e( 'Posts Order :', 'wpdx' ); ?> </span>
                              <select name="cmp_home_cats[<?php echo $i ?>][order]" id="cmp_home_cats[<?php echo $i ?>][order]"><option value="latest" <?php if( $cat['order'] == 'latest' || $cat['order']=='' ) echo 'selected="selected"'; ?>><?php _e('Latest Posts','wpdx') ?></option><option  <?php if( $cat['order'] == 'rand' ) echo 'selected="selected"'; ?> value="rand"><?php _e('Random Posts','wpdx') ?></option>
                              </select>
                            </label>
                              <label>
                                <span style="float:left;"><?php _e( 'Box Style :', 'wpdx' ); ?> </span>
                                <ul class="cmp-cats-options cmp-options">
                                  <li>
                                    <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="default" <?php if( $cat['style'] == 'default' || $cat['style']=='' ) echo 'checked="checked"'; ?> />
                                    <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/news-in-pic1.png" /></a>
                                  </li>
                                  <li>
                                    <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="row" <?php if( $cat['style'] == 'row' ) echo 'checked="checked"' ?> />
                                    <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/news-in-pic2.png" /></a>
                                  </li>
                                </ul>
                                <div class="clear"></div>
                              </label>
                              <?php if($cat['show_title']){$checked = "checked=\"checked\"";  } else{$checked = "";} ?>
                              <label for="cmp_home_cats[<?php echo $i ?>][show_title]"><span><?php _e( 'Show Title :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][show_title]" name="cmp_home_cats[<?php echo $i ?>][show_title]" value="true" type="checkbox" <?php echo $checked; ?>/>
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][title]"><span><?php _e( 'Box Title :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][title]" name="cmp_home_cats[<?php echo $i ?>][title]" value="<?php  echo $cat['title']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][icon]"><span>
                              <?php _e( 'Icon :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][icon]" name="cmp_home_cats[<?php echo $i ?>][icon]" value="<?php  echo $cat['icon']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_text]"><span>
                              <?php _e( 'More Text :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_text]" name="cmp_home_cats[<?php echo $i ?>][more_text]" value="<?php  echo $cat['more_text']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_url]"><span>
                              <?php _e( 'More Url :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_url]" name="cmp_home_cats[<?php echo $i ?>][more_url]" value="<?php  echo $cat['more_url']  ?>" type="text" />
                              </label>
                            <?php elseif( $cat['type'] == 'news-pic-edd' ) : ?>
                            <div class="widget-head news-pic-box"><?php _e( 'EDD News In Picture :', 'wpdx' );  ?> <?php $edd_cat = get_term( $cat['id'][0], 'download_category'); if($cat['title']) echo $cat['title']; elseif(isset($cat['id'])) echo $edd_cat->name; ?>
                              <a class="toggle-open">+</a>
                              <a class="toggle-close">-</a>
                            </div>
                            <div class="widget-content">
                            <label for="cmp_home_cats[<?php echo $i ?>][who]"><span><?php _e( 'Who can see this module:', 'wpdx' ); ?></span>
                                <select id="cmp_home_cats[<?php echo $i ?>][who]" name="cmp_home_cats[<?php echo $i ?>][who]">
                                  <option value="anyone" <?php if ( $cat['who'] == 'anyone') { echo ' selected="selected"' ; } ?>><?php _e( 'Anyone', 'wpdx' ); ?></option>
                                  <option value="logged" <?php if ( $cat['who'] == 'logged') { echo ' selected="selected"' ; } ?>><?php _e( 'Only logged in users', 'wpdx' ); ?></option>
                                  <option value="anonymous" <?php if ( $cat['who'] == 'anonymous') { echo ' selected="selected"' ; } ?>><?php _e( 'Only anonymous', 'wpdx' ); ?></option>
                                </select>
                              </label>
                            <label for="cmp_home_cats[<?php echo $i ?>][post_ids]"><span>
                              <?php _e( 'Special Post Ids :', 'wpdx' ); ?></span><a class="mo-help tooltip" original-title="<?php _e('You can enter the posts ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7); or leave it blank, and then set the following options to query the psots.','wpdx') ?>"></a>
                              <textarea id="cmp_home_cats[<?php echo $i ?>][post_ids]" name="cmp_home_cats[<?php echo $i ?>][post_ids]" placeholder="<?php _e('You can enter the posts ids in the form here to display them (Multiple IDs separated by commas.e.g. 3,5,7); or leave it blank, and then set the following options to query the psots.','wpdx') ?>"><?php echo $cat['post_ids'];?></textarea>
                            </label>
                              <label><span style="float:left;"><?php _e( 'Choose Categories :', 'wpdx' ); ?> </span><a class="mo-help tooltip" original-title="<?php _e('Hold down the [ctrl] key to select or deselect multiple categories.','wpdx') ?>"></a>
                                <select multiple="multiple" name="cmp_home_cats[<?php echo $i ?>][id][]" id="cmp_home_cats[<?php echo $i ?>][id][]">
                                  <?php foreach ($edd_categories as $key => $option) { ?>
                                  <option value="<?php echo $key ?>" <?php if ( @in_array( $key , $cat['id'] ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
                                  <?php } ?>
                                </select>
                              </label>
                              <label><span><?php _e( 'Posts Order :', 'wpdx' ); ?> </span>
                              <select name="cmp_home_cats[<?php echo $i ?>][order]" id="cmp_home_cats[<?php echo $i ?>][order]"><option value="latest" <?php if( $cat['order'] == 'latest' || $cat['order']=='' ) echo 'selected="selected"'; ?>><?php _e('Latest Posts','wpdx') ?></option><option  <?php if( $cat['order'] == 'rand' ) echo 'selected="selected"'; ?> value="rand"><?php _e('Random Posts','wpdx') ?></option>
                              </select>
                            </label>
                              <label>
                                <span style="float:left;"><?php _e( 'Box Style :', 'wpdx' ); ?> </span>
                                <ul class="cmp-cats-options cmp-options">
                                  <li>
                                    <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="default" <?php if( $cat['style'] == 'default' || $cat['style']=='' ) echo 'checked="checked"'; ?> />
                                    <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/news-in-pic1.png" /></a>
                                  </li>
                                  <li>
                                    <input id="cmp_home_cats[<?php echo $i ?>][style]" name="cmp_home_cats[<?php echo $i ?>][style]" type="radio" value="row" <?php if( $cat['style'] == 'row' ) echo 'checked="checked"' ?> />
                                    <a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/news-in-pic2.png" /></a>
                                  </li>
                                </ul>
                                <div class="clear"></div>
                              </label>
                              <?php if( @$cat['show_title']){$checked = "checked=\"checked\"";  } else{$checked = "";} ?>
                              <label for="cmp_home_cats[<?php echo $i ?>][show_title]"><span><?php _e( 'Show Title :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][show_title]" name="cmp_home_cats[<?php echo $i ?>][show_title]" value="true" type="checkbox" <?php echo $checked; ?>/>
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][title]"><span><?php _e( 'Box Title :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][title]" name="cmp_home_cats[<?php echo $i ?>][title]" value="<?php  echo $cat['title']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][icon]"><span>
                              <?php _e( 'Icon :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][icon]" name="cmp_home_cats[<?php echo $i ?>][icon]" value="<?php  echo $cat['icon']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_text]"><span>
                              <?php _e( 'More Text :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_text]" name="cmp_home_cats[<?php echo $i ?>][more_text]" value="<?php  echo $cat['more_text']  ?>" type="text" />
                              </label>
                              <label for="cmp_home_cats[<?php echo $i ?>][more_url]"><span>
                              <?php _e( 'More Url :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][more_url]" name="cmp_home_cats[<?php echo $i ?>][more_url]" value="<?php  echo $cat['more_url']  ?>" type="text" />
                              </label>
                            <?php elseif( $cat['type'] == 'divider' ) : ?>
                              <div class="widget-head"><?php _e( 'Divider', 'wpdx' ); ?>
                                <a class="toggle-open">+</a>
                                <a class="toggle-close">-</a>
                              </div>
                              <div class="widget-content">
                                <label style="display:none;" for="cmp_home_cats[<?php echo $i ?>][height]"><span><?php _e( 'Height :', 'wpdx' ); ?></span><input id="cmp_home_cats[<?php echo $i ?>][height]" name="cmp_home_cats[<?php echo $i ?>][height]" value="<?php  echo $cat['height']  ?>" type="text" style="width:50px;" /> px
                                </label>
                                <p><?php _e('This module can repair the dislocation problem of "Three modules in parallel", please add this module at the top of the first one.','wpdx');?></p>
                            <?php endif; ?>
                              <input id="cmp_home_cats[<?php echo $i ?>][type]" name="cmp_home_cats[<?php echo $i ?>][type]" value="<?php  echo $cat['type']  ?>" type="hidden" />
                              <a class="del-cat" title="<?php _e('Delete this module.','wpdx') ?>"></a>
                            </div>
                          </li>
                        <?php }
                          } else { ?>
                        <?php } ?>
                        </ul>
                        <script>
                          var nextCell = <?php echo $i+1 ?> ;
                          var templatePath ='<?php echo get_template_directory_uri(); ?>';
                        </script>
                      </div>
                    </div>
                </div>
              </div> <!-- Homepage Settings -->
              <div id="tab9" class="tab_content tabs-wrap">
                <div class="mo-panel-top">
                  <h2><i class="dashicons dashicons-admin-users"></i><?php _e( 'Register & Login', 'wpdx' ); ?></h2> <?php echo $save ?>
                  <div class="clear"></div>
                </div>
                <div class="cmppanel-item">
                  <h3><?php _e('Function switch','wpdx') ?></h3>
                  <?php
                    cmp_options(array(
                      "name" => __( 'Enable Register & Login Of This Theme' , 'wpdx' ),
                      "id" => "enable_cmpuser",
                      "type" => "checkbox",
                      "help" => __('If checked, will enable theme\'s custom registration and login functions.','wpdx')
                    ));
                  ?>
                <h3><?php _e('Shortcodes','wpdx') ?></h3>
                <p><?php _e('Register: [cmpuser-register] <br/>Login: [cmpuser-login] <br/>Edit Profile: [cmpuser-edit-profile] <br/>Reset Password: [cmpuser-reset-password]','wpdx'); ?></p>
                <h3><?php _e('Options','wpdx') ?></h3>
                  <?php
                  cmp_options(array(
                    "name" => __( 'Disable dashboard access for non-admin users?' , 'wpdx' ),
                    "id" => "block_dashboard",
                    "type" => "checkbox",
                    "help" => __('Please note that you can only log in through wp-login.php and this plugin. wp-admin permalink will be inaccessible.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Add first name and last name?' , 'wpdx' ),
                    "id" => "first_last_name",
                    "type" => "checkbox",
                    "help" => __('Add first name and last name?','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Enable captcha?' , 'wpdx' ),
                    "id" => "antispam",
                    "type" => "checkbox",
                    "help" => __('Honeypot antispam detection is enabled by default. For captcha usage the PHP-GD library needs to be enabled in your server/hosting.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Enable Standby role?' , 'wpdx' ),
                    "id" => "standby",
                    "type" => "checkbox"
                  ));
                  ?>
                  <p class="description"><?php _e( 'Standby role disables all the capabilities for new users, until the administrator changes. It usefull for site with restricted components.', 'wpdx' ); ?></p>
                  <?php
                  cmp_options(array(
                    "name" => __( 'Choose the role(s) in the registration form?' , 'wpdx' ),
                    "id" => "choose_role",
                    "type" => "checkbox",
                    "help" => __('This feature allows you to choose the role from the frontend, with the selected roles you want to show. You can also define an standard predefined role through a shortcode parameter, e.g. [cmp-register role="contributor"].','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( '-- Choose the role(s)' , 'wpdx' ),
                    "help" => __( 'You need to choose only the role(s) you want to accept to avoid security/infiltration issues.' , 'wpdx' ),
                    "id" => "new_user_roles",
                    "type" => "multiple",
                    "options" => $options_roles
                    ));
                  cmp_options(array(
                    "name" => __( 'Enable password complexity?' , 'wpdx' ),
                    "id" => "password_complexity",
                    "type" => "checkbox",
                    "help" => __( 'Password must contain at least 1 uppercase, 1 lowercase letter and at least 1 number. Passwords should not contain the user\'s username, email, or first/last name.', 'wpdx' )
                  ));
                  cmp_options(array(
                      "name" => __( 'Send notification email to admin?' , 'wpdx' ),
                      "id" => "email_notification_admin",
                      "type" => "checkbox",
                      "help" => __( 'If checked, will send notification email to admin when new user registered.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Send account details email to the new user?' , 'wpdx' ),
                      "id" => "email_notification_user",
                      "type" => "checkbox",
                      "help" => __( 'If checked, will send account details email to the new user.' , 'wpdx' )
                    ));
                  cmp_options(array(
                    "name" => __( '-- Email notification content' , 'wpdx' ),
                    "id" => "email_notification_content",
                    "type" => "textarea"
                  ));
                  ?>
                  <p class="email_notification_content_tips description"><?php _e('Please use HMTL tags for all formatting. And also you can use:','wpdx') ?> {username} {password} {email}</p>
                  <?php
                  cmp_options(array(
                    "name" => __( 'Accept terms / conditions in the registration form?' , 'wpdx' ),
                    "id" => "terms_conditions",
                    "type" => "checkbox",
                    "help" => __('Accept terms / conditions in the registration form?','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( '-- Terms and conditions message' , 'wpdx' ),
                    "id" => "terms_conditions_msg",
                    "type" => "text",
                    "help" => __('Terms and conditions message.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( '-- Target URL' , 'wpdx' ),
                    "id" => "terms_conditions_url",
                    "type" => "text",
                    "help" => __('Terms and conditions target URL.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Only ask for password once on registration form?' , 'wpdx' ),
                    "id" => "single_password",
                    "type" => "checkbox",
                    "help" => __('Only ask for password once on registration form?','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Automatically Login after registration?' , 'wpdx' ),
                    "id" => "automatic_login",
                    "type" => "checkbox",
                    "help" => __('Automatically Login after registration?','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( '-- URL after Automatically Login' , 'wpdx' ),
                    "id" => "register_redirect_url",
                    "type" => "text",
                    "help" => __('URL after Automatically Login. Leave blank will be redirected to the homepage.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Validate user registration through an email?' , 'wpdx' ),
                    "id" => "email_validation",
                    "type" => "checkbox",
                    "help" => __('Validate user registration through an email?','wpdx')
                  ));
                  ?>
                  <p class="description"><?php _e('This feature cannot be used with [Automatically Login after registration] and [Send account details email to the new user]','wpdx') ?></p>
                  <?php
                  cmp_options(array(
                    "name" => __( 'Redirect URL after login' , 'wpdx' ),
                    "id" => "login_redirect_url",
                    "type" => "text",
                    "help" => __('Redirect URL after login, leave blank will be redirected to the page before login.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Redirect URL after logout' , 'wpdx' ),
                    "id" => "logout_redirect_url",
                    "type" => "text",
                    "help" => __('Redirect URL after logout, leave blank will be redirected to the page before logout.','wpdx')
                  ));
                  ?>
                </div>
              </div> <!-- users-->
              <div id="tab10" class="tab_content tabs-wrap">
                <div class="mo-panel-top">
                  <h2><i class="dashicons dashicons-edit"></i><?php _e( 'Frontend Post', 'wpdx' ); ?></h2> <?php echo $save ?>
                  <div class="clear"></div>
                </div>

                <div class="cmppanel-item">
                <h3><?php _e('Function switch','wpdx') ?></h3>
                  <?php
                    cmp_options(array(
                      "name" => __( 'Enable Frontend Post Of This Theme' , 'wpdx' ),
                      "id" => "enable_frontend_post",
                      "type" => "checkbox",
                      "help" => __('If checked, will enable theme\'s frontend post functions.','wpdx')
                    ));
                  ?>
                <h3><?php _e('Shortcodes','wpdx') ?></h3>
                <p><?php _e('Frontend Post Form: [cmpuser-frontend-post] <br/>User Posts List: [cmpuser-post-list]','wpdx'); ?></p>
                <h3><?php _e( 'Main Settings', 'wpdx' ); ?></h3>
                  <?php
                  cmp_options(array(
                    "name" => __( 'Edit Page ID' , 'wpdx' ),
                    "id" => "edit_page_id",
                    "type" => "short-text",
                    "help" => __('Enter the ID of the page where the edit form appears (the page where you entered the frontend post shortcode).','wpdx')
                  ));
                    cmp_options(array(
                      "name" => __( 'Publish Status' , 'wpdx' ),
                      "id" => "publish_status",
                      "type" => "select",
                      "options" => array(
                        "pending"=>__( 'Pending' , 'wpdx' ),
                        "publish"=>__( 'Publish' , 'wpdx' ) ,
                        "draft"=>__( 'Draft' , 'wpdx' ),
                        "private"=>__( 'Private' , 'wpdx' )
                      ),
                      "help"=>__( 'The Status assigned to the new Post (Publish, Pending, Draft, Private).' , 'wpdx' )
                    ));
                  cmp_options(array(
                    "name" => __( 'Post Success Message' , 'wpdx' ),
                    "id" => "post_success",
                    "type" => "textarea",
                    "help" => __('Your custom post-success message.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Post Failure Message' , 'wpdx' ),
                    "id" => "post_failure",
                    "type" => "textarea",
                    "help" => __('Your custom post-failure message.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Mail on New Post' , 'wpdx' ),
                    "id" => "new_post_mail",
                    "type" => "checkbox",
                    "help" => __('Check to notify admin on new post.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Display Login Link in Form' , 'wpdx' ),
                    "id" => "login_link",
                    "type" => "checkbox",
                    "help" => __('Display a Login Link inside the form.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Allow selection of Post Format' , 'wpdx' ),
                    "id" => "post_format",
                    "type" => "checkbox",
                    "help" => __('Allow the selection off the Post Format inside the form.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Hide regular WP Edit Link' , 'wpdx' ),
                    "help" => __( 'Hide the regular WordPress Edit Link.' , 'wpdx' ),
                    "id" => "hide_edit_link",
                    "type" => "checkbox"
                    ));
                  ?>
                  <h3><?php _e( 'Category Settings', 'wpdx' ); ?></h3>
                  <?php
                  cmp_options(array(
                    "name" => __( 'Display Categories' , 'wpdx' ),
                    "id" => "display_categories",
                    "type" => "select",
                    "options" => array(
                      "list"=>__( 'Droplist' , 'wpdx' ),
                      "check"=>__( 'Check boxes' , 'wpdx' ) ,
                      "none"=>__( 'Not display' , 'wpdx' )
                    ),
                    "help" => __('How categories appear at the front end.You can select not to display categories at all.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Categrory Order' , 'wpdx' ),
                    "id" => "category_order",
                    "type" => "select",
                    "options" => array(
                      "id"=>__( 'by ID' , 'wpdx' ),
                      "name"=>__( 'by name' , 'wpdx' )
                    ),
                    "help" => __('The sort order of categories at the front end.','wpdx')
                  ));
                  ?>
                  <h3><?php _e( 'Field Settings', 'wpdx' ); ?></h3>
                  <?php
                  cmp_options(array(
                    "name" => __( 'Require a Title' , 'wpdx' ),
                    "id" => "title_required",
                    "type" => "checkbox",
                    "help" => __('Check to enforce the user to enter a title for his post.Please note that the Title will always be included - No Post without Title.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Show Separate Excerpt' , 'wpdx' ),
                    "id" => "show_excerpt",
                    "type" => "checkbox",
                    "help" => __('Check to display a separate field for the excerpt.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Content Field Style' , 'wpdx' ),
                    "id" => "editor_style",
                    "type" => "select",
                    "options" => array(
                      "simple"=>__( 'Plain Text' , 'wpdx' ),
                      "rich"=>__( 'Visual and HTML' , 'wpdx' ),
                      "visual"=>__( 'Visual Only' , 'wpdx' ),
                      "html"=>__( 'HTML Only' , 'wpdx' )
                    ),
                    "help" => __('For responsive layouts it is recommended not to use the visual content field style,because some Android browsers don\'t support some of the tages used.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Allow Media Upload' , 'wpdx' ),
                    "id" => "allow_media_upload",
                    "type" => "checkbox",
                    "help" => __('Allow user to upload new media file (picture, video).','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Allow Tags' , 'wpdx' ),
                    "id" => "allow_tags",
                    "type" => "checkbox",
                    "help" => __('Check to display a field to enter tags.','wpdx')
                  ));
                  ?>
                  <h3><?php _e( 'Guest Post', 'wpdx' ); ?></h3>
                  <?php
                  cmp_options(array(
                    "name" => __( 'Allow guest to post' , 'wpdx' ),
                    "id" => "allow_guest_posts",
                    "type" => "checkbox",
                    "help" => __( 'Allow guests to post.' , 'wpdx' )
                  ));
                  cmp_options(array(
                    "name" => __( 'Email & Name for Guest Posts' , 'wpdx' ),
                    "id" => "guest_info",
                    "type" => "checkbox",
                    "help" => __('Check to require email and name for guest posts.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Guest Account ID' , 'wpdx' ),
                    "id" => "guest_account",
                    "type" => "short-text",
                    "help" => __( 'The dedicated account that should be used for guest posts.' , 'wpdx' )
                  ));
                  cmp_options(array(
                    "name" => __( 'Allow Guests to select Category' , 'wpdx' ),
                    "id" => "guest_cat_select",
                    "type" => "checkbox",
                    "help" => __( 'Check if you want guests to select categories themselves. If not checked the default category you specify below will be used for guest posts.' , 'wpdx' )
                  ));
                  cmp_options(array(
                    "name" => __( 'Category for Guest Posts' , 'wpdx' ),
                    "id" => "guest_cat",
                    "type" => "select",
                    "options" => $categories,
                    "help" => __('The category guest posts should be assigned to.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Show Guests a Spam Prevention Quiz' , 'wpdx' ),
                    "id" => "guest_quiz",
                    "type" => "checkbox",
                    "help" => __('Display a Spam Prevention Quiz. Applies to useres not logged in.','wpdx')
                  ));                        
                  ?>
                  <h3><?php _e( 'Post List', 'wpdx' ); ?></h3>
                  <?php
                  cmp_options(array(
                    "name" => __( 'Enable post edit' , 'wpdx' ),
                    "id" => "enable_post_edit",
                    "type" => "checkbox",
                    "help" => __('Check on to allow the author edit their posts.','wpdx')
                  )); 
                  cmp_options(array(
                    "name" => __( 'Disable edit pending post' , 'wpdx' ),
                    "id" => "disable_pending_edit",
                    "type" => "checkbox",
                    "help" => __('Check on to disable edit pending post.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Enable post delete' , 'wpdx' ),
                    "id" => "enable_post_del",
                    "type" => "checkbox",
                    "help" => __('Check on to allow the author delete their posts.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Posts per page' , 'wpdx' ),
                    "id" => "user_posts_per_page",
                    "type" => "short-text",
                    "help" => __('How many posts per page to display.','wpdx')
                  ));
                  ?>
                </div>
              </div> <!-- frontend post-->
                  <div id="tab11" class="tab_content tabs-wrap">
                      <div class="mo-panel-top">
                        <h2><i class="dashicons dashicons-download"></i>
                        <?php _e( 'Easy Digital Downloads', 'wpdx' ); ?>
                        </h2>
                        <?php echo $save ?>
                        <div class="clear"></div>
                      </div>
                  <div class="cmppanel-item">
                  <h3><?php _e( 'Custom EDD Labels and Slug', 'wpdx' ); ?></h3>
                  <?php
                  cmp_options(
                    array(  "name" => __( 'Custom EDD Labels' , 'wpdx' ),
                      "id" => "edd_labels",
                      "type" => "text",
                      "help" => __('The format is [singular]|[plural], such as Download|Downloads. If left blank, will use edd default labels.','wpdx')
                      ));
                  cmp_options(
                    array(  "name" => __( 'Custom EDD Slug' , 'wpdx' ),
                      "id" => "edd_slug",
                      "type" => "text",
                      "help" => __('If left blank, will use edd default slug.','wpdx')
                      ));
                  ?>
                  <div class="option-item">
                    <p style="color:#DA542E;font-weight:bolder">
                    <?php echo sprintf(__( 'Important: After modifying EDD Slug, you must visit <a href="%s" target="_blank">this page</a> to re-save the settings, otherwise it will not take effect.', 'wpdx'), admin_url().'options-permalink.php'); ?>
                    </p>
                  </div>
                  <h3><?php _e( 'EDD Archive Settings', 'wpdx' ); ?></h3>
                  <?php
                  cmp_options(
                    array(  "name" => __( 'EDD Archive Title' , 'wpdx' ),
                      "id" => "edd_archive_title",
                      "type" => "textarea",
                      "help" => __('You can set a different title for the EDD Archive page &lt;title&gt; label.','wpdx')
                      ));
                  cmp_options(
                    array(  "name" => __( 'EDD Archive Keywords' , 'wpdx' ),
                      "id" => "edd_archive_keywords",
                      "type" => "textarea",
                      "help" => __('Contents set here will be used as the value of the EDD Archive page keywords meta, and multiple keywords separated by commas.','wpdx')
                      ));
                  cmp_options(
                    array(  "name" => __( 'EDD Archive Description' , 'wpdx' ),
                      "id" => "edd_archive_description",
                      "type" => "textarea",
                      "help" => __('Contents set here will be used as the value of the EDD Archive page description meta.','wpdx')
                      ));
                  cmp_options(array(
                        "name" => __( 'EDD Archive Style' , 'wpdx' ),
                        "id" => "edd_archive_style",
                        "type" => "radio",
                        "options" => array(
                          "simple_title"=>__( 'Simple Title List' , 'wpdx' ),
                          "row_thumb"=>__( 'Row Thumb' , 'wpdx' ),
                          "small_thumb"=>__( 'Small Thumb + Excerpt' , 'wpdx' ),
                          "big_thumb"=>__( 'Big Thumb + Excerpt' , 'wpdx' ),
                          "original_image"=>__( 'Original Image + Excerpt' , 'wpdx' ) )
                      ));
                  cmp_options(array(
                    "name" => __( 'Products per page for EDD Archive' , 'wpdx' ),
                    "id" => "edd_per_page",
                    "type" => "short-text",
                    "help" => __('How many products you want to show per page in EDD Archives.','wpdx')
                  ));
                  ?>
                  <div class="option-item">
                    <p style="color:#DA542E;font-weight:bolder"><?php _e( 'Important: The value of "Products per page for EDD Archive" ​​can not be less than the value of "Blog pages show at most", which is set in [Settins > Reading]. The current value of "Blog pages show at most" is ', 'wpdx');echo get_option('posts_per_page'); ?></p>
                  </div>
                  <h3><?php _e( 'EDD Related Downloads', 'wpdx' ); ?></h3>
                  <?php
                  cmp_options(
                      array(  "name" => __( 'Enable Related Downloads' , 'wpdx' ),
                        "id" => "edd_related",
                        "type" => "checkbox"));
                  cmp_options(
                      array(  "name" => __( '--Title Of Related Downloads' , 'wpdx' ),
                        "id" => "edd_title_related",
                        "type" => "text"));
                    cmp_options(
                      array(  "name" => __( '--Number of Downloads to show' , 'wpdx' ),
                        "id" => "edd_related_number",
                        "type" => "short-text"));
                    cmp_options(
                      array(  "name" => __( '--Query Type' , 'wpdx' ),
                        "id" => "edd_related_query",
                        "options" => array(
                          "category"=>__( 'Category' , 'wpdx' ),
                          "tag"=>__( 'Tag' , 'wpdx' )),
                        "type" => "radio"));
                  ?>
                  <h3><?php _e( 'EDD Other Settings', 'wpdx' ); ?></h3>
                  <?php
                  cmp_options(array(
                    "name" => __( 'Enable Reward author' , 'wpdx' ),
                    "id" => "edd_reward_author",
                    "type" => "checkbox",
                    "help" => __( 'Display reward author at the bottom of the article.' , 'wpdx' )
                  ));
                  cmp_options(array(
                    "name" => __( 'Enable Share Buttons' , 'wpdx' ),
                    "id" => "edd_share_post",
                    "type" => "checkbox",
                    "help" => __( 'Display social share buttons.' , 'wpdx' )
                  ));
                  cmp_options(array(
                    "name" => __( 'Show Featured image On Single Download Page' , 'wpdx' ),
                    "id" => "edd_show_featured_img",
                    "type" => "checkbox",
                    "help" => __( 'If you have set a Featured image for Single Download Page, just show it at the top of post title.' , 'wpdx' )
                  ));
                  cmp_options(array(
                    "name" => __( 'Open EDD Comments' , 'wpdx' ),
                    "id" => "edd_open_comments",
                    "type" => "checkbox",
                    "help" => __('EDD is not allowed to comment default, check to open the comments.','wpdx')
                  ));
                  cmp_options(array(
                    "name" => __( 'Add Product Name To Purchase History' , 'wpdx' ),
                    "id" => "edd_add_product_name",
                    "type" => "checkbox",
                    "help" => __('EDD purchase history is not show the product name default, check to add product name.','wpdx')
                  ));

                  ?>
                  </div>
                </div>
              <div id="tab12" class="tabs-wrap">
                <div class="mo-panel-top">
                  <h2><i class="dashicons dashicons-admin-site"></i><?php _e( 'Social Network', 'wpdx' ); ?></h2> <?php echo $save ?>
                  <div class="clear"></div>
                </div>
                <div class="cmppanel-item">
                  <h3><?php _e( 'Social Network', 'wpdx' ); ?></h3>
                  <?php
                  cmp_options(
                    array(  "name" => __( 'Display Social Icon' , 'wpdx' ),
                      "id" => "display_social_icon",
                      "type" => "checkbox"));
                      ?>
                  <p style="padding:10px; color:red;"><?php _e( 'Don\'t forget http:// before link (except QQ and QQ Email List ).', 'wpdx' ); ?></p>
                  <?php
                  cmp_options(
                    array(  "name" => __( 'Sina Weibo' , 'wpdx' ),
                      "id" => "sina_weibo",
                      "help" => __( 'e.g. http://weibo.com/hcm602' , 'wpdx' ),
                      "type" => "text"));
                  cmp_options(
                    array(  "name" => __( 'QQ Weibo' , 'wpdx' ),
                      "id" => "qq_weibo",
                      "help" => __( 'e.g. http://t.qq.com/hcm602' , 'wpdx' ),
                      "type" => "text"));
                  cmp_options(
                    array(  "name" => __( 'QQ' , 'wpdx' ),
                      "id" => "qq",
                      "help" => __( 'e.g. 745722006' , 'wpdx' ),
                      "type" => "text"));
                  cmp_options(
                    array(  "name" => __( 'Google+' , 'wpdx' ),
                      "id" => "google_plus",
                      "help" => __( 'e.g. https://plus.google.com/105276589845964937994' , 'wpdx' ),
                      "type" => "text"));
                  cmp_options(
                    array(  "name" => __( 'Twitter' , 'wpdx' ),
                      "id" => "twitter",
                      "help" => __( 'e.g. https://twitter.com/hcm602' , 'wpdx' ),
                      "type" => "text"));
                  cmp_options(
                    array(  "name" => __( '<a class="tooltip" href="http://www.cmhello.com/list-qq-com.html" target="_blank" title="Setting method of QQ Email List">QQ Email List</a>' , 'wpdx' ),
                      "id" => "qq_email_list",
                      "help" => __( 'e.g. 04d10e789e984f55c7eddb77e9ce652b631cbc84842b16fa' , 'wpdx' ),
                      "type" => "text"));
                  cmp_options(
                    array(  "name" => __( '<a class="tooltip" href="http://www.cmhello.com/qq-email-me.html" target="_blank" title="Setting method of QQ Email Email-me">QQ Email Email-me</a>' , 'wpdx' ),
                      "id" => "send_email",
                      "help" => __( 'e.g. http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&email=0Lm9s72Qprmg-qGh-rO-vQ' , 'wpdx' ),
                      "type" => "text"));
                  cmp_options(
                    array(  "name" => __( 'Custom Feed URL' , 'wpdx' ),
                      "id" => "rss_url",
                      "help" => __( 'e.g. http://feed.feedsky.com/cmhello' , 'wpdx' ),
                      "type" => "text"));
                      ?>
                    </div>
                  </div><!-- Social Network -->
              <div id="tab5" class="tab_content tabs-wrap">
                <div class="mo-panel-top">
                  <h2><i class="dashicons dashicons-format-aside"></i><?php _e( 'Article', 'wpdx' ); ?></h2> <?php echo $save ?>
                  <div class="clear"></div>
                </div>
                <div class="cmppanel-item">
                  <h3><?php _e( 'Original Article Settings', 'wpdx' ); ?></h3>
                  <?php
                  cmp_options(
                    array(  "name" => __( 'Output Original Metas To &lt;head&gt;' , 'wpdx' ),
                      "id" => "enable_original",
                      "type" => "checkbox",
                      "help" => __( 'Output original metas to <head> in single post and page, just for baidu seo.' , 'wpdx' )
                      ));
                  cmp_options(
                    array(  "name" => __( 'Post Note Type' , 'wpdx' ),
                      "id" => "post_note_type",
                      "type" => "radio",
                      "options" => array(
                        "none"=>__( 'Don\'t Display Note' , 'wpdx' ),
                        "dynamic"=>__( 'Dynamic Note' , 'wpdx' ),
                        "static"=>__( 'Static Note' , 'wpdx' )
                      ),
                      "help" => __( 'The Post Note will display in the bottom of the content. If choose [Dynamic Note], the note will change according to the seo settings of the post. Otherwise, it shows the text of [Post Static Note].' , 'wpdx' )
                    ));
                  cmp_options(
                    array(  "name" => __( 'Nofollow Original Url' , 'wpdx' ),
                      "id" => "original_url_nofollow",
                      "type" => "checkbox",
                      "help" => __( 'If checked, it will add [rel=nofollow] to the original url when the post is reproduced.' , 'wpdx' )
                      ));
                  cmp_options(
                      array(  "name" => __( 'Post Static Note' , 'wpdx' ),
                        "id" => "post_note",
                        "type" => "textarea",
                        "help" => __( 'Displayed at the bottom of the article, blank will not be displayed.' , 'wpdx' )
                        )
                    );
                  ?>
                  <h3><?php _e( 'Article Elements', 'wpdx' ); ?></h3>
                  <?php
                  cmp_options(
                    array(  "name" => __( 'Post Author Box' , 'wpdx' ),
                      "id" => "post_authorbio",
                      "type" => "checkbox"));
                  cmp_options(
                    array(  "name" => __( 'Next/Prev Article' , 'wpdx' ),
                      "id" => "post_nav",
                      "type" => "checkbox"));
                  ?>
                </div>
                <div class="cmppanel-item">
                  <h3><?php _e( 'Post Meta Settings', 'wpdx' ); ?></h3>
                  <?php
                  cmp_options(
                    array(  "name" => __( 'Post Meta :' , 'wpdx' ),
                      "id" => "post_meta",
                      "type" => "checkbox"));
                  cmp_options(
                    array(  "name" => __( 'Page Meta :' , 'wpdx' ),
                      "id" => "page_meta",
                      "type" => "checkbox"));
                  cmp_options(
                    array(  "name" => __( 'Author Meta' , 'wpdx' ),
                      "id" => "post_author",
                      "type" => "checkbox"));
                  cmp_options(
                    array(  "name" => __( 'Date Meta' , 'wpdx' ),
                      "id" => "post_date",
                      "type" => "checkbox"));
                  cmp_options(
                    array(  "name" => __( 'Views Meta' , 'wpdx' ),
                      "id" => "post_views",
                      "type" => "checkbox"));
                  cmp_options(
                    array(  "name" => __( 'Categories Meta' , 'wpdx' ),
                      "id" => "post_cats",
                      "type" => "checkbox"));
                  cmp_options(
                    array(  "name" => __( 'Comments Meta' , 'wpdx' ),
                      "id" => "post_comments",
                      "type" => "checkbox"));
                  cmp_options(
                    array(  "name" => __( 'Tags Meta' , 'wpdx' ),
                      "id" => "post_tags",
                      "type" => "checkbox"));
                  ?>
                  <h3><?php _e( 'Paragraph Indent', 'wpdx' ); ?></h3>
                    <?php
                    cmp_options(
                      array(  "name" => __( 'Paragraph first line indent 2 characters' , 'wpdx' ),
                        "id" => "p_text_indent",
                        "type" => "checkbox",
                        "help" => __( 'Check this to make paragraph first line indent 2 characters.' , 'wpdx' )
                        )
                    );
                    ?>
                  <h3><?php _e( 'Reward author', 'wpdx' ); ?></h3>
                  <?php
                  cmp_options(
                    array(  "name" => __( 'Enable Reward author' , 'wpdx' ),
                      "id" => "reward_author",
                      "type" => "checkbox",
                      "help" => __( 'Display reward author at the bottom of the article.' , 'wpdx' )));
                  cmp_options(
                    array(  "name" => __( 'Reward tips' , 'wpdx' ),
                      "id" => "reward_tips",
                      "type" => "textarea"));
                  cmp_options(
                    array(  "name" => __( 'Alipay QR code image' , 'wpdx' ),
                      "id" => "alipay_img",
                      "type" => "upload",
                      "help" => __( 'Upload alipay QRcode image, the size is 150*150 px.' , 'wpdx' )));
                  cmp_options(
                    array(  "name" => __( 'Alipay tips' , 'wpdx' ),
                      "id" => "alipay_tips",
                      "type" => "text"));
                  cmp_options(
                    array(  "name" => __( 'Wechatpay QR code image' , 'wpdx' ),
                      "id" => "wechatpay_img",
                      "type" => "upload",
                      "help" => __( 'Upload wechatpay QRcode image, the size is 150*150 px.' , 'wpdx' )));
                  cmp_options(
                    array(  "name" => __( 'Wechatpay tips' , 'wpdx' ),
                      "id" => "wechatpay_tips",
                      "type" => "text"));
                  ?>
                  <h3><?php _e( 'Share Post Settings', 'wpdx' ); ?></h3>
                  <?php
                  cmp_options(
                    array(  "name" => __( 'Share Post Buttons' , 'wpdx' ),
                      "id" => "share_post",
                      "type" => "checkbox",
                      "help" => __( 'Display social share buttons.' , 'wpdx' )
                      ));
                  ?>
                </div>
                <div class="cmppanel-item">
                  <h3><?php _e( 'Related Posts Settings', 'wpdx' ); ?></h3>
                    <?php
                    cmp_options(
                      array(  "name" => __( 'Related Posts' , 'wpdx' ),
                        "id" => "related",
                        "type" => "checkbox"));
                    cmp_options(array(
                      "name" => __( 'Custom Title' , 'wpdx' ),
                      "id" => "title_related",
                      "type" => "text",
                      "help" => __( 'Custom title of related posts box.' , 'wpdx' )
                    ));
                    cmp_options(
                      array(  "name" => __( 'Number of posts to show' , 'wpdx' ),
                        "id" => "related_number",
                        "type" => "short-text"));
                    cmp_options(
                      array(  "name" => __( 'Query Type' , 'wpdx' ),
                        "id" => "related_query",
                        "options" => array( "category"=>__( 'Category' , 'wpdx' ),
                          "tag"=>__( 'Tag' , 'wpdx' ),
                          "author"=>__( 'Author' , 'wpdx' ) ),
                        "type" => "radio"));
                    cmp_options(
                      array(  "name" => __( 'Display Style' , 'wpdx' ),
                        "id" => "related_style",
                        "options" => array( "big_thumb"=>__( 'Big thumb + Title' , 'wpdx' ),
                          "small_thumb"=>__( 'Small thumb + Title' , 'wpdx' ),
                          "title_only"=>__( 'Only title' , 'wpdx' ) ),
                        "type" => "radio"));
                    ?>
                  </div>
                <div class="cmppanel-item">
                  <h3><?php _e( 'Comment Settings', 'wpdx' ); ?></h3>
                  <?php
                  cmp_options(array(
                    "name" => __( 'Remove Comment Part For Posts' , 'wpdx' ),
                    "id" => "remove_post_comment",
                    "type" => "checkbox",
                    "help" => __( 'Remove the comment part for all posts.' , 'wpdx' )
                  ));
                  cmp_options(array(
                    "name" => __( 'Remove Comment Part For Pages' , 'wpdx' ),
                    "id" => "remove_page_comment",
                    "type" => "checkbox",
                    "help" => __( 'Remove the comment part for all pages.' , 'wpdx' )
                  ));
                  cmp_options(array(
                    "name" => __( 'Enable Smilies' , 'wpdx' ),
                    "id" => "smilies",
                    "type" => "checkbox"
                  ));
                  cmp_options(array(
                    "name" => __( 'Remove Url Field' , 'wpdx' ),
                    "id" => "comment_url_filtered",
                    "type" => "checkbox",
                    "help" => __( 'Check this to remove url field from comment form.' , 'wpdx' )
                  ));
                  cmp_options(array(
                    "name" => __( 'Enable Quicktags' , 'wpdx' ),
                    "id" => "comment_quicktags",
                    "type" => "checkbox",
                    "help" => __( 'If you want to add a group of quicktag buttons to the comment form, please check this option' , 'wpdx' )
                  ));
                  cmp_options(array(
                    "name" => __( 'Comment Mail Notify' , 'wpdx' ),
                    "id" => "comment_mail_notify",
                    "type" => "checkbox"
                  ));
                  cmp_options(array(
                    "name" => __( 'Anti Spam' , 'wpdx' ),
                    "id" => "anti_spam",
                    "type" => "checkbox",
                    "help" => __( 'Check this can prevent most of the spam comments' , 'wpdx' )
                  ));
                  cmp_options(array(
                    "name" => __( 'Comment must contain Chinese' , 'wpdx' ),
                    "id" => "comment_chinese",
                    "type" => "checkbox",
                    "help" => __( 'If your site visitors are almost all Chinese people, you can check this option.' , 'wpdx' )
                  ));
                  cmp_options(array(
                    "name" => __( 'Comment Textarea placeholder' , 'wpdx' ),
                    "id" => "comment_placeholder",
                    "type" => "textarea",
                    "help" => __( 'The placeholder text will display in the comment textarea.' , 'wpdx' )
                    ));
                  ?>
                </div>
                </div>  <!-- Article -->
                <div id="tab7" class="tab_content tabs-wrap">
                  <div class="mo-panel-top">
                    <h2><i class="dashicons dashicons-smiley"></i><?php _e( 'Banners', 'wpdx' ); ?></h2> <?php echo $save ?>
                    <div class="clear"></div>
                  </div>
                  <div class="cmppanel-item">
                    <h3><?php _e( 'Top Banner Area', 'wpdx' ); ?></h3>
                    <?php
                    cmp_options(array(
                      "name" => __( 'Enable' , 'wpdx' ),
                      "id" => "banner_top",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Who can see this ads:' , 'wpdx' ),
                      "id" => "banner_top_who",
                      "type" => "select",
                      "options" => array(
                      "anyone"=>__( 'Anyone' , 'wpdx' ),
                      "logged"=>__( 'Only logged in users' , 'wpdx' ) ,
                      "anonymous"=>__( 'Only anonymous' , 'wpdx' )
                      )
                    ));
                    cmp_options(array(
                      "name" => __( 'Image' , 'wpdx' ),
                      "id" => "banner_top_img",
                      "type" => "upload",
                      "help" => __( 'You can upload a picture, or simply fill out the image URL.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Link' , 'wpdx' ),
                      "id" => "banner_top_url",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Alternative Text For The image' , 'wpdx' ),
                      "id" => "banner_top_alt",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Open The Link In a new Tab' , 'wpdx' ),
                      "id" => "banner_top_tab",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Adsense Code' , 'wpdx' ),
                      "id" => "banner_top_adsense",
                      "type" => "textarea",
                      "help" => __( 'If you want to add more than one banner ad, clear image ads settings above, and then add your adsense code here.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Mobile Adsense Code' , 'wpdx' ),
                      "id" => "banner_top_mobile_adsense",
                      "type" => "textarea",
                      "help" => __( 'This adsense only display in mobile.' , 'wpdx' )
                    ));
                    ?>
                  </div>
                  <div class="cmppanel-item">
                    <h3><?php _e( 'Top Right Banner Area(Vertical layout only)', 'wpdx' ); ?></h3>
                    <?php
                    cmp_options(array(
                      "name" => __( 'Enable' , 'wpdx' ),
                      "id" => "banner_top_right",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Who can see this ads:' , 'wpdx' ),
                      "id" => "banner_top_right_who",
                      "type" => "select",
                      "options" => array(
                      "anyone"=>__( 'Anyone' , 'wpdx' ),
                      "logged"=>__( 'Only logged in users' , 'wpdx' ) ,
                      "anonymous"=>__( 'Only anonymous' , 'wpdx' )
                      )
                    ));
                    cmp_options(array(
                      "name" => __( 'Image' , 'wpdx' ),
                      "id" => "banner_top_right_img",
                      "type" => "upload",
                      "help" => __( 'You can upload a picture, or simply fill out the image URL.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Link' , 'wpdx' ),
                      "id" => "banner_top_right_url",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Alternative Text For The image' , 'wpdx' ),
                      "id" => "banner_top_right_alt",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Open The Link In a new Tab' , 'wpdx' ),
                      "id" => "banner_top_right_tab",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Adsense Code' , 'wpdx' ),
                      "id" => "banner_top_right_adsense",
                      "type" => "textarea",
                      "help" => __( 'If you want to add more than one banner ad, clear image ads settings above, and then add your adsense code here.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Mobile Adsense Code' , 'wpdx' ),
                      "id" => "banner_top_right_mobile_adsense",
                      "type" => "textarea",
                      "help" => __( 'This adsense only display in mobile.' , 'wpdx' )
                    ));
                    ?>
                  </div>
                  <div class="cmppanel-item">
                    <h3><?php _e( 'Bottom Banner Area', 'wpdx' ); ?></h3>
                    <?php
                    cmp_options(array(
                      "name" => __( 'Enable' , 'wpdx' ),
                      "id" => "banner_bottom",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Who can see this ads:' , 'wpdx' ),
                      "id" => "banner_bottom_who",
                      "type" => "select",
                      "options" => array(
                      "anyone"=>__( 'Anyone' , 'wpdx' ),
                      "logged"=>__( 'Only logged in users' , 'wpdx' ) ,
                      "anonymous"=>__( 'Only anonymous' , 'wpdx' )
                      )
                    ));
                    cmp_options(array(
                      "name" => __( 'Image' , 'wpdx' ),
                      "id" => "banner_bottom_img",
                      "type" => "upload",
                      "help" => __( 'You can upload a picture, or simply fill out the image URL.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Link' , 'wpdx' ),
                      "id" => "banner_bottom_url",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Alternative Text For The image' , 'wpdx' ),
                      "id" => "banner_bottom_alt",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Open The Link In a new Tab' , 'wpdx' ),
                      "id" => "banner_bottom_tab",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Adsense Code' , 'wpdx' ),
                      "id" => "banner_bottom_adsense",
                      "type" => "textarea",
                      "help" => __( 'If you want to add more than one banner ad, clear image ads settings above, and then add your adsense code here.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Mobile Adsense Code' , 'wpdx' ),
                      "id" => "banner_bottom_mobile_adsense",
                      "type" => "textarea",
                      "help" => __( 'This adsense only display in mobile.' , 'wpdx' )
                    ));
                    ?>
                  </div>
                  <div class="cmppanel-item">
                    <h3><?php _e( 'Right of Home Slider', 'wpdx' ); ?></h3>
                    <?php
                    cmp_options(array(
                      "name" => __( 'Enable' , 'wpdx' ),
                      "id" => "banner_right",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Who can see this ads:' , 'wpdx' ),
                      "id" => "banner_right_who",
                      "type" => "select",
                      "options" => array(
                      "anyone"=>__( 'Anyone' , 'wpdx' ),
                      "logged"=>__( 'Only logged in users' , 'wpdx' ) ,
                      "anonymous"=>__( 'Only anonymous' , 'wpdx' )
                      )
                    ));
                    cmp_options(array(
                      "name" => __( 'Image' , 'wpdx' ),
                      "id" => "banner_right_img",
                      "type" => "upload",
                      "help" => __( 'You can upload a picture, or simply fill out the image URL.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Link' , 'wpdx' ),
                      "id" => "banner_right_url",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Alternative Text For The image' , 'wpdx' ),
                      "id" => "banner_right_alt",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Open The Link In a new Tab' , 'wpdx' ),
                      "id" => "banner_right_tab",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Adsense Code' , 'wpdx' ),
                      "id" => "banner_right_adsense",
                      "type" => "textarea",
                      "help" => __( 'If you want to add more than one banner ad, clear image ads settings above, and then add your adsense code here.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Mobile Adsense Code' , 'wpdx' ),
                      "id" => "banner_right_mobile_adsense",
                      "type" => "textarea",
                      "help" => __( 'This adsense only display in mobile.' , 'wpdx' )
                    ));
                    ?>
                  </div>
                  <div class="cmppanel-item">
                    <h3><?php _e( 'Above Article Banner Area', 'wpdx' ); ?></h3>
                    <?php
                    cmp_options(array(
                      "name" => __( 'Enable' , 'wpdx' ),
                      "id" => "banner_above",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Who can see this ads:' , 'wpdx' ),
                      "id" => "banner_above_who",
                      "type" => "select",
                      "options" => array(
                      "anyone"=>__( 'Anyone' , 'wpdx' ),
                      "logged"=>__( 'Only logged in users' , 'wpdx' ) ,
                      "anonymous"=>__( 'Only anonymous' , 'wpdx' )
                      )
                    ));
                    cmp_options(array(
                      "name" => __( 'Image' , 'wpdx' ),
                      "id" => "banner_above_img",
                      "type" => "upload",
                      "help" => __( 'You can upload a picture, or simply fill out the image URL.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Link' , 'wpdx' ),
                      "id" => "banner_above_url",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Alternative Text For The image' , 'wpdx' ),
                      "id" => "banner_above_alt",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Open The Link In a new Tab' , 'wpdx' ),
                      "id" => "banner_above_tab",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Adsense Code' , 'wpdx' ),
                      "id" => "banner_above_adsense",
                      "type" => "textarea",
                      "help" => __( 'If you want to add more than one banner ad, clear image ads settings above, and then add your adsense code here.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Mobile Adsense Code' , 'wpdx' ),
                      "id" => "banner_above_mobile_adsense",
                      "type" => "textarea",
                      "help" => __( 'This adsense only display in mobile.' , 'wpdx' )
                    ));
                    ?>
                  </div>
                  <div class="cmppanel-item">
                    <h3><?php _e( 'Below Article Banner Area', 'wpdx' ); ?></h3>
                    <?php
                    cmp_options(array(
                      "name" => __( 'Enable' , 'wpdx' ),
                      "id" => "banner_below",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Who can see this ads:' , 'wpdx' ),
                      "id" => "banner_below_who",
                      "type" => "select",
                      "options" => array(
                      "anyone"=>__( 'Anyone' , 'wpdx' ),
                      "logged"=>__( 'Only logged in users' , 'wpdx' ) ,
                      "anonymous"=>__( 'Only anonymous' , 'wpdx' )
                      )
                    ));
                    cmp_options(array(
                      "name" => __( 'Image' , 'wpdx' ),
                      "id" => "banner_below_img",
                      "type" => "upload",
                      "help" => __( 'You can upload a picture, or simply fill out the image URL.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Link' , 'wpdx' ),
                      "id" => "banner_below_url",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Alternative Text For The image' , 'wpdx' ),
                      "id" => "banner_below_alt",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Open The Link In a new Tab' , 'wpdx' ),
                      "id" => "banner_below_tab",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Adsense Code' , 'wpdx' ),
                      "id" => "banner_below_adsense",
                      "type" => "textarea",
                      "help" => __( 'If you want to add more than one banner ad, clear image ads settings above, and then add your adsense code here.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Mobile Adsense Code' , 'wpdx' ),
                      "id" => "banner_below_mobile_adsense",
                      "type" => "textarea",
                      "help" => __( 'This adsense only display in mobile.' , 'wpdx' )
                    ));
                    ?>
                  </div>
                  <div class="cmppanel-item">
                    <h3><?php _e( 'Top of the comment form', 'wpdx' ); ?></h3>
                    <a class="mo-help tooltip" original-title="<?php _e( 'This ad will be displayed at the top of the comment form.', 'wpdx' ); ?>"></a>
                    <?php
                    cmp_options(array(
                      "name" => __( 'Enable' , 'wpdx' ),
                      "id" => "banner_top_form",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Who can see this ads:' , 'wpdx' ),
                      "id" => "banner_top_form_who",
                      "type" => "select",
                      "options" => array(
                      "anyone"=>__( 'Anyone' , 'wpdx' ),
                      "logged"=>__( 'Only logged in users' , 'wpdx' ) ,
                      "anonymous"=>__( 'Only anonymous' , 'wpdx' )
                      )
                    ));
                    cmp_options(array(
                      "name" => __( 'Image' , 'wpdx' ),
                      "id" => "banner_top_form_img",
                      "type" => "upload",
                      "help" => __( 'You can upload a picture, or simply fill out the image URL.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Link' , 'wpdx' ),
                      "id" => "banner_top_form_url",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Alternative Text For The image' , 'wpdx' ),
                      "id" => "banner_top_form_alt",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Open The Link In a new Tab' , 'wpdx' ),
                      "id" => "banner_top_form_tab",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Adsense Code' , 'wpdx' ),
                      "id" => "banner_top_form_adsense",
                      "type" => "textarea",
                      "help" => __( 'If you want to add more than one banner ad, clear image ads settings above, and then add your adsense code here.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Mobile Adsense Code' , 'wpdx' ),
                      "id" => "banner_top_form_mobile_adsense",
                      "type" => "textarea",
                      "help" => __( 'This adsense only display in mobile.' , 'wpdx' )
                    ));
                    ?>
                  </div>
                  <div class="cmppanel-item">
                    <h3><?php _e( 'Bottom of the comment form', 'wpdx' ); ?></h3>
                    <a class="mo-help tooltip" original-title="<?php _e( 'This ad will be displayed at the bottom of the comment form.', 'wpdx' ); ?>"></a>
                    <?php
                    cmp_options(array(
                      "name" => __( 'Enable' , 'wpdx' ),
                      "id" => "banner_bottom_form",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Who can see this ads:' , 'wpdx' ),
                      "id" => "banner_bottom_form_who",
                      "type" => "select",
                      "options" => array(
                      "anyone"=>__( 'Anyone' , 'wpdx' ),
                      "logged"=>__( 'Only logged in users' , 'wpdx' ) ,
                      "anonymous"=>__( 'Only anonymous' , 'wpdx' )
                      )
                    ));
                    cmp_options(array(
                      "name" => __( 'Image' , 'wpdx' ),
                      "id" => "banner_bottom_form_img",
                      "type" => "upload",
                      "help" => __( 'You can upload a picture, or simply fill out the image URL.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Link' , 'wpdx' ),
                      "id" => "banner_bottom_form_url",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Alternative Text For The image' , 'wpdx' ),
                      "id" => "banner_bottom_form_alt",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Open The Link In a new Tab' , 'wpdx' ),
                      "id" => "banner_bottom_form_tab",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Adsense Code' , 'wpdx' ),
                      "id" => "banner_bottom_form_adsense",
                      "type" => "textarea",
                      "help" => __( 'If you want to add more than one banner ad, clear image ads settings above, and then add your adsense code here.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Mobile Adsense Code' , 'wpdx' ),
                      "id" => "banner_bottom_form_mobile_adsense",
                      "type" => "textarea",
                      "help" => __( 'This adsense only display in mobile.' , 'wpdx' )
                    ));
                    ?>
                  </div>
                  <div class="cmppanel-item">
                    <h3><?php _e( 'Shortcode [ads1] ADS', 'wpdx' ); ?></h3>
                    <p><?php _e( 'You can add Shortcode [ads1] to any post, page or widget to display this ads.', 'wpdx' ); ?></p>
                    <?php
                    cmp_options(array(
                      "name" => __( 'Enable' , 'wpdx' ),
                      "id" => "ads1_shortcode",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Who can see this ads:' , 'wpdx' ),
                      "id" => "ads1_shortcode_who",
                      "type" => "select",
                      "options" => array(
                      "anyone"=>__( 'Anyone' , 'wpdx' ),
                      "logged"=>__( 'Only logged in users' , 'wpdx' ) ,
                      "anonymous"=>__( 'Only anonymous' , 'wpdx' )
                      )
                    ));
                    cmp_options(array(
                      "name" => __( 'Image' , 'wpdx' ),
                      "id" => "ads1_shortcode_img",
                      "type" => "upload",
                      "help" => __( 'You can upload a picture, or simply fill out the image URL.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Link' , 'wpdx' ),
                      "id" => "ads1_shortcode_url",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Alternative Text For The image' , 'wpdx' ),
                      "id" => "ads1_shortcode_alt",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Open The Link In a new Tab' , 'wpdx' ),
                      "id" => "ads1_shortcode_tab",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( '[ads1] Shortcode Banner' , 'wpdx' ),
                      "id" => "ads1_shortcode_adsense",
                      "type" => "textarea",
                      "help" => __( 'Add your adsense code here, and then use shortcode [ads1] to call it.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( '[ads1] Mobile Adsense Code' , 'wpdx' ),
                      "id" => "ads1_shortcode_mobile_adsense",
                      "type" => "textarea",
                      "help" => __( 'This adsense only display in mobile.' , 'wpdx' )
                    ));
                    ?>
                    <h3><?php _e( 'Shortcode [ads2] ADS', 'wpdx' ); ?></h3>
                    <p><?php _e( 'You can add Shortcode [ads2] to any post, page or widget to display this ads.', 'wpdx' ); ?></p>
                    <?php
                    cmp_options(array(
                      "name" => __( 'Enable' , 'wpdx' ),
                      "id" => "ads2_shortcode",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( 'Who can see this ads:' , 'wpdx' ),
                      "id" => "ads2_shortcode_who",
                      "type" => "select",
                      "options" => array(
                      "anyone"=>__( 'Anyone' , 'wpdx' ),
                      "logged"=>__( 'Only logged in users' , 'wpdx' ) ,
                      "anonymous"=>__( 'Only anonymous' , 'wpdx' )
                      )
                    ));
                    cmp_options(array(
                      "name" => __( 'Image' , 'wpdx' ),
                      "id" => "ads2_shortcode_img",
                      "type" => "upload",
                      "help" => __( 'You can upload a picture, or simply fill out the image URL.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( 'Link' , 'wpdx' ),
                      "id" => "ads2_shortcode_url",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Alternative Text For The image' , 'wpdx' ),
                      "id" => "ads2_shortcode_alt",
                      "type" => "text"
                    ));
                    cmp_options(array(
                      "name" => __( 'Open The Link In a new Tab' , 'wpdx' ),
                      "id" => "ads2_shortcode_tab",
                      "type" => "checkbox"
                    ));
                    cmp_options(array(
                      "name" => __( '[ads2] Shortcode Banner' , 'wpdx' ),
                      "id" => "ads2_shortcode_adsense",
                      "type" => "textarea",
                      "help" => __( 'Add your adsense code here, and then use shortcode [ads2] to call it.' , 'wpdx' )
                    ));
                    cmp_options(array(
                      "name" => __( '[ads2] Mobile Adsense Code' , 'wpdx' ),
                      "id" => "ads2_shortcode_mobile_adsense",
                      "type" => "textarea",
                      "help" => __( 'This adsense only display in mobile.' , 'wpdx' )
                    ));
                    ?>
                  </div>
                </div> <!-- Banners -->
                  <div id="tab6" class="tab_content tabs-wrap">
                    <div class="mo-panel-top">
                      <h2><i class="dashicons dashicons-align-left"></i></i><?php _e( 'Sidebars', 'wpdx' ); ?></h2> <?php echo $save ?>
                      <div class="clear"></div>
                    </div>
                    <div class="cmppanel-item">
                      <h3><?php _e( 'Sidebar Rolling', 'wpdx' ); ?></h3>
                      <?php
                      cmp_options(
                        array(  "name" => __( 'Right Sidebar Rolling' , 'wpdx' ),
                          "id" => "right_rolling",
                          "type" => "checkbox",
                          "help" => __( 'When enabled, the sidebar widget will fixed when the page scroll down.' , 'wpdx' )
                          ));
                      cmp_options(
                        array(  "name" => __( '-- Home Rolling Widget One' , 'wpdx' ),
                          "id" => "right_h_one",
                          "type" => "short-text",
                          "help" => __( 'Enter the widgets position(According to the display order).If you want to just roll a widget, please enter the same number in both of widget one and widget two, if you want to roll the last widget, please enter 0 in both of widget one and widget two. Following is the same.' , 'wpdx' )
                          ));
                      cmp_options(
                        array(  "name" => __( '-- Home Rolling Widget Two' , 'wpdx' ),
                          "id" => "right_h_two",
                          "type" => "short-text"));
                      cmp_options(
                        array(  "name" => __( '-- Post Rolling Widget One' , 'wpdx' ),
                          "id" => "right_one",
                          "type" => "short-text"
                          ));
                      cmp_options(
                        array(  "name" => __( '-- Post Rolling Widget Two' , 'wpdx' ),
                          "id" => "right_two",
                          "type" => "short-text"));
                      cmp_options(
                        array(  "name" => __( '-- Page Rolling Widget One' , 'wpdx' ),
                          "id" => "right_p_one",
                          "type" => "short-text"
                          ));
                      cmp_options(
                        array(  "name" => __( '-- Page Rolling Widget Two' , 'wpdx' ),
                          "id" => "right_p_two",
                          "type" => "short-text"));
                      ?>
                       <p style="padding:10px; color:#28B78D;"><?php _e( 'Note: In addition to the home page, posts and pages in accordance with a fixed set above, other pages are only fixed last widget.', 'wpdx' ); ?></p>
                    </div>
                    <div class="cmppanel-item">
                      <h3><?php _e( 'Hide Sidebar', 'wpdx' ); ?></h3>
                      <?php
                      cmp_options(
                        array(  "name" => __( 'Hide Sidebar in Mobile Client' , 'wpdx' ),
                          "id" => "hide_sidebar",
                          "type" => "checkbox",
                          "help" => __( 'When the device is smaller than the width of 980px, the sidebar will not be displayed.' , 'wpdx' )
                          ));
                      cmp_options(
                        array(  "name" => __( 'Remove Sidebar For Posts' , 'wpdx' ),
                          "id" => "remove_post_sidebar",
                          "type" => "checkbox",
                          "help" => __( 'Remove sidebar for all posts.' , 'wpdx' )
                          ));
                      cmp_options(
                        array(  "name" => __( 'Remove Sidebar For Pages' , 'wpdx' ),
                          "id" => "remove_page_sidebar",
                          "type" => "checkbox",
                          "help" => __( 'Remove sidebar for all pages.' , 'wpdx' )
                          ));
                      ?>
                    </div>
                    <div class="cmppanel-item">
                      <h3><?php _e( 'Add Sidebar', 'wpdx' ); ?></h3>
                      <div class="option-item">
                        <span class="label"><?php _e( 'Sidebar Name', 'wpdx' ); ?></span>
                        <input id="sidebarName" type="text" size="56" style="direction:ltr; text-laign:left" name="sidebarName" value="" />
                        <input id="sidebarAdd"  class="small_button" type="button" value="<?php _e( 'Add', 'wpdx' ); ?>" />
                        <a class="mo-help tooltip" original-title="<?php _e( 'Please use letters and numbers named, otherwise, it may cause the sidebar does not work.', 'wpdx' ); ?>"></a>
                        <ul id="sidebarsList">
                          <?php $sidebars = cmp_get_option( 'sidebars' ) ;
                            if($sidebars){
                              foreach ($sidebars as $sidebar) { ?>
                              <li>
                                <div class="widget-head"><?php echo $sidebar ?>  <input id="cmp_sidebars" name="cmp_options[sidebars][]" type="hidden" value="<?php echo $sidebar ?>" /><a class="del-sidebar"></a></div>
                                </li>
                              <?php }
                            }
                          ?>
                        </ul>
                      </div>
                    </div>
                    <div class="cmppanel-item" id="custom-sidebars">
                      <h3><?php _e( 'Custom Sidebars', 'wpdx' ); ?></h3>
                      <?php
                      $new_sidebars = array(''=> 'Default');
                      if($sidebars){
                        foreach ($sidebars as $sidebar) {
                          $new_sidebars[$sidebar] = $sidebar;
                        }
                      }
                      cmp_options(
                        array(  "name" => __( 'Home Sidebar' , 'wpdx' ),
                          "id" => "sidebar_home",
                          "type" => "select",
                          "options" => $new_sidebars ));
                      cmp_options(
                        array(  "name" => __( 'Single Page Sidebar' , 'wpdx' ),
                          "id" => "sidebar_page",
                          "type" => "select",
                          "options" => $new_sidebars ));
                      cmp_options(
                        array(  "name" => __( 'Single Article Sidebar' , 'wpdx' ),
                          "id" => "sidebar_post",
                          "type" => "select",
                          "options" => $new_sidebars ));
                      cmp_options(
                        array(  "name" => __( 'Archives Sidebar' , 'wpdx' ),
                          "id" => "sidebar_archive",
                          "type" => "select",
                          "options" => $new_sidebars ));
                      foreach ($categories_obj as $pn_cat) {
                        cmp_options(
                          array(  "name" => __( '<em><small>Category : </small></em>' , 'wpdx' ).$pn_cat->cat_name,
                            "id" => "sidebar_cat_".$pn_cat->cat_ID,
                            "type" => "select",
                            "options" => $new_sidebars ));
                      }
                      ?>
                    </div>
                  </div> <!-- Sidebars -->
                  <div id="tab4" class="tab_content tabs-wrap">
                    <div class="mo-panel-top">
                      <h2><i class="dashicons dashicons-category"></i><?php _e( 'Archives', 'wpdx' ); ?></h2> <?php echo $save ?>
                      <div class="clear"></div>
                    </div>
                    <div class="cmppanel-item">
                      <h3><?php _e( 'Archive Style Settings', 'wpdx' ); ?></h3>
                      <div id="all_cat" class="option-item">
                        <p><?php _e( 'All Categries of your website:', 'wpdx' ); ?></p>
                        <?php if(function_exists('show_category')) show_category(); ?>
                      </div>
                      <div class="option-item">
                        <p><?php _e( 'The default archive style is <strong>[Simple Title List]</strong>, You can enter the Category IDs to custom the archive style of them. Multiple IDs separated by commas.e.g. 3,5,7', 'wpdx' ); ?></p>
                        <p style="color:#DA542E;font-weight:bolder"><?php _e( 'Important: The value of "Posts Per Page" ​​can not be less than the value of "Blog pages show at most", which is set in [Settins > Reading]. The current value of "Blog pages show at most" is ', 'wpdx');echo get_option('posts_per_page'); ?></p>
                      </div>
                      <?php
                      cmp_options(array(
                        "name" => __( 'Pagination Type' , 'wpdx' ),
                        "id" => "archive_pagination_type",
                        "type" => "radio",
                        "options" => array(
                          "pagination"=>__( 'Pagination' , 'wpdx' ),
                          "ajax"=>__( 'Ajax loading more' , 'wpdx' )
                        )
                      ));
                      cmp_options(array(
                        "name" => __( 'Turn to click ajax loading after scroll' , 'wpdx' ),
                        "id" => "archive_ajax_num",
                        "help" => __('Turn to click ajax loading after scrolling how many times, default is 5. If you want never turn to click ajax loading, just set a large enough number, for example: 999999.','wpdx'),
                        "type" => "short-text"
                      ));
                      // cmp_options(array(
                      //   "name" => __( 'Turn to pagination after ajax' , 'wpdx' ),
                      //   "id" => "archive_no_ajax_num",
                      //   "help" => __('Turn to pagination after ajax loading how many times , including scroll and click, default is 10. If you want never turn to pagination, just set a large enough number, for example: 999999.','wpdx'),
                      //   "type" => "short-text"
                      // ));
                      cmp_options(array(
                        "name" => __( 'Default Archive Style' , 'wpdx' ),
                        "id" => "archive_style",
                        "type" => "radio",
                        "options" => array(
                          "simple_title"=>__( 'Simple Title List' , 'wpdx' ),
                          "row_thumb"=>__( 'Row Thumb' , 'wpdx' ),
                          "small_thumb"=>__( 'Small Thumb + Excerpt' , 'wpdx' ),
                          "big_thumb"=>__( 'Big Thumb + Excerpt' , 'wpdx' ),
                          "original_image"=>__( 'Original Image + Excerpt' , 'wpdx' ) )
                      ));
                      cmp_options(array(
                        "name" => __( '-- Default Posts Per Page' , 'wpdx' ),
                        "id" => "default_number",
                        "type" => "short-text",
                        "help" => __('Posts Per Page of [Simple Title List] and other archives pages','wpdx')
                      ));
                      cmp_options(array(
                        "name" => __( 'Excerpt Length' , 'wpdx' ),
                        "id" => "exc_length",
                        "type" => "short-text"
                      ));
                      cmp_options(array(
                        "name" => __( 'Little thumb + Title on Mobile' , 'wpdx' ),
                        "id" => "archive_mobile",
                        "type" => "checkbox",
                        "help" => __('Check this if you want display Little thumb + title on mobile.','wpdx')
                      ));
                      cmp_options(array(
                        "name" => __( '-- Posts Per Page' , 'wpdx' ),
                        "id" => "archive_mobile_number",
                        "type" => "short-text",
                        "help" => __('Posts Per Page of [Little thumb + Title on Mobile]','wpdx')
                      ));
                      cmp_options(array(
                        "name" => __( 'Small Thumb + Excerpt' , 'wpdx' ),
                        "id" => "small_thumb",
                        "type" => "text"
                      ));
                      cmp_options(array(
                        "name" => __( '-- Thumb Float Left' , 'wpdx' ),
                        "id" => "thumb_left",
                        "type" => "checkbox",
                        "help" => __('The thumbnail is right-floating default , you can choose left-floating if you want.','wpdx')
                      ));
                      cmp_options(array(
                        "name" => __( '-- Posts Per Page' , 'wpdx' ),
                        "id" => "small_thumb_number",
                        "type" => "short-text",
                        "help" => __('Posts Per Page of [Small Thumb + Excerpt]','wpdx')
                      ));
                      cmp_options(array(
                        "name" => __( 'Big Thumb + Excerpt' , 'wpdx' ),
                        "id" => "big_thumb",
                        "type" => "text"
                      ));
                      cmp_options(array(
                        "name" => __( '-- Posts Per Page' , 'wpdx' ),
                        "id" => "big_thumb_number",
                        "type" => "short-text",
                        "help" => __('Posts Per Page of [Big Thumb + Excerpt]','wpdx')
                      ));
                      cmp_options(array(
                        "name" => __( 'Original Image + Excerpt' , 'wpdx' ),
                        "id" => "original_image",
                        "type" => "text"
                      ));
                      cmp_options(array(
                        "name" => __( '-- Posts Per Page' , 'wpdx' ),
                        "id" => "original_image_number",
                        "type" => "short-text",
                        "help" => __('Posts Per Page of [Original Image + Excerpt]','wpdx')
                      ));
                      cmp_options(array(
                        "name" => __( 'Row Thumb' , 'wpdx' ),
                        "id" => "row_thumb",
                        "type" => "text"
                      ));
                      cmp_options(array(
                        "name" => __( '-- Posts Per Page' , 'wpdx' ),
                        "id" => "row_thumb_number",
                        "type" => "short-text",
                        "help" => __('Posts Per Page of [Row Thumb]','wpdx')
                      ));
                      cmp_options(array(
                        "name" => __( 'Categories List + Row Thumb' , 'wpdx' ),
                        "id" => "cats_row_thumb",
                        "type" => "text"
                      ));
                      cmp_options(array(
                        "name" => __( '-- Posts Per Page' , 'wpdx' ),
                        "id" => "cats_row_thumb_number",
                        "type" => "short-text",
                        "help" => __('Posts Per Page of [Categories List + Row Thumb]','wpdx')
                      ));
                      cmp_options(array(
                        "name" => __( '-- Posts Order By' , 'wpdx' ),
                        "id" => "cats_row_thumb_by",
                        "type" => "radio",
                        "options" => array(
                          "rand"=>__( 'Random ' , 'wpdx' ),
                          "ID"=>__( 'Recent ' , 'wpdx' )
                          )
                      ));
                      cmp_options(array(
                        "name" => __( 'Simple Title List' , 'wpdx' ),
                        "id" => "simple_title",
                        "type" => "text"
                      ));
                      cmp_options(array(
                        "name" => __( '-- Posts Per Page' , 'wpdx' ),
                        "id" => "simple_title_number",
                        "type" => "short-text",
                        "help" => __('Posts Per Page of [Row Thumb]','wpdx')
                      ));
                      ?>
                    </div>
                    <div class="cmppanel-item">
                    <h3><?php _e( 'Links Open Method', 'wpdx' ); ?></h3>
                      <?php
                      cmp_options(array(
                        "name" => __( 'Open link in new tab' , 'wpdx' ),
                        "id" => "target_blank",
                        "type" => "checkbox",
                        "help" => __('If you choose this option, all posts will open the link in a new tab','wpdx')
                      ));
                      ?>
                      <h3><?php _e( 'Archive Meta Settings', 'wpdx' ); ?></h3>
                      <?php
                      cmp_options(array(
                        "name" => __( 'Archive Meta' , 'wpdx' ),
                        "id" => "archive_meta",
                        "type" => "checkbox",
                        "help" => __('Effective only for [Big Thumb + Excerpt] and [Small Thumb + Excerpt] Archives.','wpdx')
                      ));
                      cmp_options(array(
                        "name" => __( 'Author Meta' , 'wpdx' ),
                        "id" => "archive_author",
                        "type" => "checkbox"
                      ));
                      cmp_options(array(
                        "name" => __( 'Category Meta' , 'wpdx' ),
                        "id" => "archive_category",
                        "type" => "checkbox"
                      ));
                      cmp_options(array(
                        "name" => __( 'Date Meta' , 'wpdx' ),
                        "id" => "archive_date",
                        "type" => "checkbox"
                      ));
                      cmp_options(array(
                        "name" => __( 'Views Meta' , 'wpdx' ),
                        "id" => "archive_views",
                        "type" => "checkbox"
                      ));
                      cmp_options(array(
                        "name" => __( 'Comments Meta' , 'wpdx' ),
                        "id" => "archive_comments",
                        "type" => "checkbox"
                      ));
                      cmp_options(array(
                        "name" => __( 'Tags Meta' , 'wpdx' ),
                        "id" => "archive_tags",
                        "type" => "checkbox"
                      ));
                      ?>
                    </div>
                    <div class="cmppanel-item">
                      <h3><?php _e( 'Category Page Settings', 'wpdx' ); ?></h3>
                        <?php
                        cmp_options(
                          array(  "name" => __( 'Category Description' , 'wpdx' ),
                            "id" => "category_desc",
                            "type" => "checkbox"));
                        cmp_options(
                          array(  "name" => __( 'RSS Icon' , 'wpdx' ),
                            "id" => "category_rss",
                            "type" => "checkbox"));
                        ?>
                      </div>
                      <div class="cmppanel-item">
                        <h3><?php _e( 'Tag Page Settings', 'wpdx' ); ?></h3>
                        <?php
                        cmp_options(
                          array(  "name" => __( 'RSS Icon' , 'wpdx' ),
                          "id" => "tag_rss",
                          "type" => "checkbox"));
                        ?>
                      </div>
                      <div class="cmppanel-item">
                        <h3><?php _e( 'Author Page Settings', 'wpdx' ); ?></h3>
                        <?php
                        cmp_options(
                          array(  "name" => __( 'Author Bio' , 'wpdx' ),
                            "id" => "author_bio",
                            "type" => "checkbox"));
                        cmp_options(
                          array(  "name" => __( 'RSS Icon' , 'wpdx' ),
                            "id" => "author_rss",
                            "type" => "checkbox"));
                        cmp_options(
                          array(  "name" => __( 'Redirect Author To User Center' , 'wpdx' ),
                            "id" => "redirect_author_uc",
                            "type" => "checkbox",
                            "help" => __("Redirect user to user center if he/she try to access his/her archive page.",'wpdx')
                            ));
                        cmp_options(array(
                        "name" => __( 'Author Archive Style' , 'wpdx' ),
                        "id" => "author_archive_style",
                        "type" => "radio",
                        "options" => array(
                          "simple_title"=>__( 'Simple Title List' , 'wpdx' ),
                          "small_thumb"=>__( 'Small Thumb + Title' , 'wpdx' ) )
                      ));
                        ?>
                      </div>
                      <div class="cmppanel-item">
                        <h3><?php _e( 'Choose Post Types', 'wpdx' ); ?></h3>
                        <?php 
                        cmp_options(array(
                          "name" => __( 'Choose Post Types for Homepage' , 'wpdx' ),
                          "help" => __( 'Choose post types for homepage main loop, use the Ctrl key to multi-select or deselect.' , 'wpdx' ),
                          "id" => "post_types_for_home",
                          "type" => "multiple",
                          "options" => $options_post_types
                          ));
                        cmp_options(array(
                          "name" => __( 'Choose Post Types for author archive' , 'wpdx' ),
                          "help" => __( 'Choose post types for author archive loop, use the Ctrl key to multi-select or deselect.' , 'wpdx' ),
                          "id" => "post_types_for_author_archive",
                          "type" => "multiple",
                          "options" => $options_post_types
                          ));
                        cmp_options(array(
                          "name" => __( 'Choose Post Types for search' , 'wpdx' ),
                          "help" => __( 'Choose post types for search results loop, use the Ctrl key to multi-select or deselect.' , 'wpdx' ),
                          "id" => "post_types_for_search",
                          "type" => "multiple",
                          "options" => $options_post_types
                          ));
                        cmp_options(array(
                          "name" => __( 'Choose Post Types for RSS feed' , 'wpdx' ),
                          "help" => __( 'Choose post types for rss feed loop, use the Ctrl key to multi-select or deselect.' , 'wpdx' ),
                          "id" => "post_types_for_feed",
                          "type" => "multiple",
                          "options" => $options_post_types
                          ));
                        ?>
                      </div>
                    </div> <!-- Archives -->
                    <div id="tab8" class="tab_content tabs-wrap">
                      <div class="mo-panel-top">
                        <h2><i class="dashicons dashicons-art"></i><?php _e( 'Styling', 'wpdx' ); ?></h2> <?php echo $save ?>
                        <div class="clear"></div>
                      </div>
                      <div class="cmppanel-item">
                        <h3><?php _e( 'Theme Layout & Color', 'wpdx' ); ?></h3>
                        <?php
                        cmp_options(array(
                          "name" => __( 'Theme Layout' , 'wpdx' ),
                          "id" => "theme_layout",
                          "type" => "select",
                          "options" => array( "default"=>__( 'Horizontal - Main menu on the left' , 'wpdx' ),
                          "vertical"=>__( 'Vertical - Main menu at the top' , 'wpdx' )
                          )
                        ));
                        cmp_options(array(
                          "name" => __( 'Theme Color' , 'wpdx' ),
                          "id" => "theme_color",
                          "type" => "select",
                          "options" => array( "default"=>__( 'Black Green(default)' , 'wpdx' ),
                          "black-blue"=>__( 'Black Blue' , 'wpdx' ),
                          "brown-red"=>__( 'Brown red' , 'wpdx' ),
                          "blue"=>__( 'Blue' , 'wpdx' ),
                          "purple"=>__( 'Purple' , 'wpdx' )
                          )
                        ));
                        ?>
                      </div>
                      <div class="cmppanel-item">
                        <h3><?php _e( 'Custom CSS', 'wpdx' ); ?></h3>
                        <?php
                        cmp_options(
                          array(  "name" => __( 'Enable Custom CSS' , 'wpdx' ),
                            "id" => "custom_css",
                            "type" => "checkbox"));
                        ?>
                        <div class="option-item">
                          <p><strong><?php _e( 'Add your css:', 'wpdx' ); ?></strong></p>
                            <textarea id="cmp_css" name="cmp_options[css]" style="width:100%" rows="7"><?php echo cmp_get_option('css');  ?></textarea>
                        </div>
                      </div>
                    </div> <!-- Styling -->
                    <div id="tab13" class="tab_content tabs-wrap">
                      <div class="mo-panel-top">
                        <h2><i class="dashicons dashicons-admin-settings"></i><?php _e( 'Advanced Settings', 'wpdx' ); ?></h2> <?php echo $save ?>
                        <div class="clear"></div>
                      </div>
                <div class="cmppanel-item">
                  <h3><?php _e( 'Optional Features', 'wpdx' ); ?></h3>
                  <?php
/*                  cmp_options(array(
                    "name" => __( 'Notify On Theme Updates' , 'wpdx' ),
                    "id" => "notify_theme",
                    "type" => "checkbox"
                  ));*/
                  cmp_options(array(
                    "name" => __( 'Show IDs In The Manage Column' , 'wpdx' ),
                    "id" => "show_ids",
                    "type" => "checkbox",
                    "help" => __('Show ids in  the manage column of posts, pages, comments, attachments, users and categories.','wpdx')
                  ));
                  // cmp_options(array(
                  //   "name" => __( 'Cache Avatar' , 'wpdx' ),
                  //   "id" => "cache_avatar",
                  //   "type" => "checkbox",
                  //   "help" => __('Important: To cache avatar, you must create a new folder named [avatar] in the site\'s root directory, add a default avatar named [default.jpg] and make sure it is writable (0755).','wpdx')
                  // ));
                  // Check if avatar directory exists --add 1.3
                  // todo: find a better way to check
                  //$default_avatar = preg_replace('/wordpress\//', '', ABSPATH) .'avatar/default.jpg';
                  //if (!is_file($default_avatar) ) :
                  ?>
                  <!-- <div class="option-item">
                    <p style="color:#DA542E;"><?php _e( 'Important: To cache avatar, you must create a new folder named [avatar] in the site\'s root directory, add a default avatar named [default.jpg] and make sure it is writable (0755).', 'wpdx'); ?></p>
                  </div> -->
                  <?php
                  //endif;
                  cmp_options(array(
                    "name" => __( 'Lightbox' , 'wpdx' ),
                    "id" => "lightbox",
                    "type" => "checkbox",
                    "help"=>__( 'Lightbox allows users to view larger versions of images.' , 'wpdx' )
                  ));
                  cmp_options(array(
                    "name" => __( 'LazyLoad' , 'wpdx' ),
                    "id" => "lazyload",
                    "type" => "checkbox",
                    "help"=>__( 'Using LazyLoad on long web pages containing many large images makes the page load faster.' , 'wpdx' )
                  ));
                  cmp_options(array(
                    "name" => __( 'jQuery CDN' , 'wpdx' ),
                    "id" => "jquery_cdn",
                    "type" => "select",
                    "options" => array( "default"=>__( 'WordPress(default)' , 'wpdx' ),
                    "google"=>__( 'Google' , 'wpdx' ) ,
                    "mrosoft"=>__( 'Mrosoft' , 'wpdx' ),
                    "baidu"=>__( 'Baidu' , 'wpdx' ),
                    "sae"=>__( 'SAE' , 'wpdx' ),
                    "qiniu"=>__( 'Qiniu' , 'wpdx' ),
                    "upyun"=>__( 'Upyun' , 'wpdx' ),
                    "jquery"=>__( 'jQuery Official' , 'wpdx' )
                    ),
                    "help"=>__( 'Choose a jquery CDN service.' , 'wpdx' )
                  ));
                  cmp_options(array(
                    "name" => __( 'Excerpt Length Repair' , 'wpdx' ),
                    "id" => "exc_length_repair",
                    "type" => "checkbox",
                    "help"=>__( 'If the excerpt echo the full content, You can use this repair function.' , 'wpdx' )
                  ));
                  cmp_options(array(
                    "name" => __( 'Add Additional columns to User list' , 'wpdx' ),
                    "id" => "add_user_columns",
                    "type" => "checkbox",
                    "help"=>__( 'Add additional columns to user list, E.g registration time, last login, ip and so on.' , 'wpdx' )
                  ));
                  cmp_options(array(
                    "name" => __( 'Enable Post views statistics' , 'wpdx' ),
                    "id" => "post_views_enable",
                    "type" => "checkbox",
                    "help"=>__( 'Add post views statistics function, just like the WP-PostViews plugin do.' , 'wpdx' )
                  ));
                  ?>
                </div>
                      <?php
                      global $array_options ;
                        $current_options = array();
                        foreach( $array_options as $option ){
                          if( get_option( $option ) )
                            $current_options[$option] =  get_option( $option ) ;
                        }
                      ?>
                      <div class="cmppanel-item">
                        <h3><?php _e( 'Export', 'wpdx' ); ?></h3>
                        <div class="option-item">
                          <textarea style="width:100%" rows="7"><?php echo $currentsettings = base64_encode( serialize( $current_options )); ?></textarea>
                        </div>
                      </div>
                      <div class="cmppanel-item">
                        <h3><?php _e( 'Import', 'wpdx' ); ?></h3>
                        <div class="option-item">
                          <textarea id="cmp_import" name="cmp_import" style="width:100%" rows="7"></textarea>
                        </div>
                      </div>
                    </div> <!-- Advanced -->
                    <div class="mo-footer">
                      <?php echo $save; ?>
                    </form>
                    <form method="post">
                      <div class="mpanel-reset">
                        <input type="hidden" name="resetnonce" value="<?php echo wp_create_nonce('reset-action-code'); ?>" />
                        <input name="reset" class="mpanel-reset-button" type="submit" onClick="if(confirm('<?php _e( 'All settings will be rest .. Are you sure ?', 'wpdx' ); ?>')) return true ; else return false; " value="<?php _e( 'Reset Settings', 'wpdx' ); ?>" />
                        <input type="hidden" name="action" value="reset" />
                      </div>
                    </form>
                  </div>
                </div><!-- .mo-panel-content -->
                <div class="clear"></div>
              </div><!-- .mo-panel -->
              <?php
            }
            ?>
