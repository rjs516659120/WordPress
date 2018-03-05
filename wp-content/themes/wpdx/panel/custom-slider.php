<?php
add_action('init', 'cmp_slider_register');

function cmp_slider_register() {

    $labels = array(
        'name' => __('Custom Sliders','wpdx'),
        'singular_name' => __('Slider','wpdx'),
        'add_new' => __('Add New Slider','wpdx'),
        'add_new_item' => __('Add New Slider','wpdx'),
        'all_items' => __('All Sliders','wpdx'),
        'edit' => __('Edit','wpdx'),
        'edit_item' => __('Edit Slider','wpdx'),
        'new_item' => __('New Slider','wpdx'),
        'view_item' => __('View Slider','wpdx')
        );

    $args = array(
        'labels' => $labels,
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-images-alt2',
        'can_export' => true,
        'exclude_from_search' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 6,
        'rewrite' => array('slug' => 'slider'),
        'supports' => array('title')
        );

    register_post_type( 'cmp_slider' , $args );
}


add_action("admin_init", "cmp_slider_init");

function cmp_slider_init(){
    add_meta_box("cmp_slider_slides", __('Slides','wpdx'), "cmp_slider_slides", "cmp_slider", "normal", "high");
}


function cmp_slider_slides(){
    global $post;
    $slider = '';
    $custom = get_post_custom($post->ID);

    if( !empty($custom["custom_slider"][0]) )
        $slider = unserialize( $custom["custom_slider"][0] );

    wp_enqueue_media();
?>
  <script>
  jQuery(document).ready(function() {
  
    jQuery(function() {
        jQuery( "#cmp-slider-items" ).sortable({placeholder: "ui-state-highlight"});
    });

    /* Uploading files */
    var cmp_uploader;
    jQuery('#upload_add_slide').live('click', function( event ){
 
        event.preventDefault();
        cmp_uploader = wp.media.frames.cmp_uploader = wp.media({
            title: '<?php _e( 'Choose Images | Hold CTRL to Multi Select .', 'wpdx' ) ?>',
            library: {
                type: 'image'
            },
            button: {
                text: '<?php _e( 'Select', 'wpdx' ) ?>'
            },
            multiple: true
        });
 
        cmp_uploader.on( 'select', function() {
            var selection = cmp_uploader.state().get('selection');
            
            selection.map( function( attachment ) {
                attachment = attachment.toJSON();
                jQuery('#cmp-slider-items').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-content option-item"><div class="slider-img"><img src="'+attachment.url+'" alt=""></div><label for="custom_slider['+ nextCell +'][title]"><span><?php _e( 'Slide Title:', 'wpdx' ) ?></span><input id="custom_slider['+ nextCell +'][title]" name="custom_slider['+ nextCell +'][title]" value="" type="text" /></label><label for="custom_slider['+ nextCell +'][caption]"><span class="slide-caption"><?php _e( 'Slide Caption:', 'wpdx' ) ?></span><textarea name="custom_slider['+ nextCell +'][caption]" id="custom_slider['+ nextCell +'][caption]"></textarea></label><label for="custom_slider['+ nextCell +'][link]"><span><?php _e( 'Slide Link:', 'wpdx' ) ?></span><input id="custom_slider['+ nextCell +'][link]" name="custom_slider['+ nextCell +'][link]" value="" type="text" /></label><label for="custom_slider['+ nextCell +'][target]"><span><?php _e('Open The Link In a new Tab','wpdx'); ?></span><input id="custom_slider['+ nextCell +'][target]" name="custom_slider['+ nextCell +'][target]" value="" type="checkbox" /></label><input id="custom_slider['+ nextCell +'][id]" name="custom_slider['+ nextCell +'][id]" value="'+attachment.id+'" type="hidden" /><a class="del-cat"><?php _e('Delete','wpdx'); ?></a></div></li>');
                nextCell ++ ;
            });
        });
        
        cmp_uploader.open();
    });
    
});

  </script>
  
 <input id="upload_add_slide" type="button" class="button button-large button-primary builder_active" value="<?php _e( 'Add New Slide', 'wpdx' ) ?>">

    <ul id="cmp-slider-items">
    <?php
    $i=0;
    if( !empty( $slider ) ){
    foreach( $slider as $slide ):
        $image = wp_get_attachment_image_src( $slide['id'], 'full' );
        $image = aq_resize( $image[0], '230', '140', true , true , true );
        $i++; ?>
        <li id="listItem_<?php echo $i ?>"  class="ui-state-default">
            <div class="widget-content option-item">
                <div class="slider-img"><img src="<?php echo $image; ?>" alt="listItem_<?php echo $i ?>"></div>
                <label for="custom_slider[<?php echo $i ?>][title]"><span><?php _e( 'Slide Title:', 'wpdx' ) ?> </span><input id="custom_slider[<?php echo $i ?>][title]" name="custom_slider[<?php echo $i ?>][title]" value="<?php  echo stripslashes( $slide['title'] )  ?>" type="text" /></label>
                <label for="custom_slider[<?php echo $i ?>][caption]"><span class="slide-caption"><?php _e( 'Slide Caption:', 'wpdx' ) ?></span><textarea name="custom_slider[<?php echo $i ?>][caption]" id="custom_slider[<?php echo $i ?>][caption]"><?php echo stripslashes($slide['caption']) ; ?></textarea></label>
                <label for="custom_slider[<?php echo $i ?>][link]"><span><?php _e( 'Slide Link:', 'wpdx' ) ?></span><input id="custom_slider[<?php echo $i ?>][link]" name="custom_slider[<?php echo $i ?>][link]" value="<?php  echo stripslashes( $slide['link'] )  ?>" type="text" /></label>
                <?php if( isset($slide['target']) && $slide['target'] =='on'){$checked = 'checked="checked"';  } else {$checked = '';} ?>
                <label for="custom_slider[<?php echo $i ?>][target]"><span><?php _e('Open The Link In a new Tab','wpdx'); ?></span><input class="on-of" type="checkbox" id="custom_slider[<?php echo $i ?>][target]" name="custom_slider[<?php echo $i ?>][target]" <?php echo $checked; ?> /></label>
                <input id="custom_slider[<?php echo $i ?>][id]" name="custom_slider[<?php echo $i ?>][id]" value="<?php  echo $slide['id']  ?>" type="hidden" />
                <a class="del-cat"><?php _e('Delete','wpdx'); ?></a>
            </div>
        </li>
    <?php endforeach; 
    }else{
        echo '<p>'. __( 'Use the button above to add slides.', 'wpdx' ).'</p>';
    }?>
    </ul>
    <script> var nextCell = <?php echo $i+1 ?> ;</script>

<?php
}



add_action('save_post', 'cmp_save_slide');
function cmp_save_slide(){
  global $post;
  
    if( !empty( $_POST['custom_slider'] ) && $_POST['custom_slider'] != "" ){
        update_post_meta($post->ID, 'custom_slider' , $_POST['custom_slider']);     
    }
    else{
        if( isset($post->ID) )
            delete_post_meta($post->ID, 'custom_slider' );
    }
}


add_filter("manage_edit-cmp_slider_columns", "cmp_slider_edit_columns");
function cmp_slider_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => __( 'Title', 'wpdx' ),
    "slides" => __( 'Number of slides', 'wpdx' ),
    "id" => __( 'ID', 'wpdx' ),
    "date" => __( 'Date', 'wpdx' ),
  );
 
  return $columns;
}


add_action("manage_cmp_slider_posts_custom_column",  "cmp_slider_custom_columns");
function cmp_slider_custom_columns($column){
    global $post;
    
    $original_post = $post;

    switch ($column) {
        case "slides":
            $custom_slider_args = array( 'post_type' => 'cmp_slider', 'p' => $post->ID, 'no_found_rows' => 1  );
            $custom_slider = new WP_Query( $custom_slider_args );
            while ( $custom_slider->have_posts() ) {
                $number =0;
                $custom_slider->the_post();
                $custom = get_post_custom($post->ID);
                if( !empty($custom["custom_slider"][0])){
                    $slider = unserialize( $custom["custom_slider"][0] );
                    echo $number = count($slider);
                }
                else echo 0;
            }

            $post = $original_post;
            wp_reset_query();
        break;
        case "id":
            echo $post->ID;
        break;
    }
}

function cmp_slider_remove_menu_items() {
    if( !current_user_can( 'manage_options' ) ):
        remove_menu_page( 'edit.php?post_type=cmp_slider' );
    endif;
}
add_action( 'admin_menu', 'cmp_slider_remove_menu_items' );
