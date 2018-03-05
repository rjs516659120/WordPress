<?php
/**
 * Change EDD_SLUG
 * http://docs.easydigitaldownloads.com/article/594-edd-slug
 * @return EDD_SLUG
 */
if(cmp_get_option('edd_slug')){
    $edd_slug = esc_html(cmp_get_option('edd_slug'));
    if ( ! defined( 'EDD_SLUG' ) ) {
        define( 'EDD_SLUG', $edd_slug );
    }
}
/**
 * Change Global Download Labels
 * http://docs.easydigitaldownloads.com/article/269-change-global-download-labels
 * @return $labels
 */
function cmp_edd_product_labels( $labels ) {
    if(cmp_get_option('edd_labels')){
        $edd_labels = cmp_get_option('edd_labels');
        $edd_label = explode("|", $edd_labels );
        $singular = esc_html($edd_label[0]);
        $plural = $edd_label[1] ? esc_html($edd_label[1]) : esc_html($edd_label[0]);
    
        $labels = array(
           'singular' => $singular,
           'plural' => $plural
        );
    }
    return $labels;
}
add_filter('edd_default_downloads_name', 'cmp_edd_product_labels');

/**
 * [cmp_edd_posts_per_page description]
 * @param  [type] $query [description]
 * @return [type]        [description]
 */
function cmp_edd_posts_per_page( $query ) {
    if ( is_admin() || !$query->is_main_query() )
        return;

    if ( is_post_type_archive( 'download' ) && cmp_get_option('edd_per_page') ) {
        $edd_per_page = esc_html(cmp_get_option('edd_per_page'));
        $query->set( 'posts_per_page', $edd_per_page );
        return;
    }

}
add_action( 'pre_get_posts', 'cmp_edd_posts_per_page', 1 );


/*
 * Adds comment support to the download post type
 */
function cmp_edd_comments() {
    if(cmp_get_option('edd_open_comments')) {
        add_post_type_support( 'download', 'comments' );
    }
}
add_action( 'init', 'cmp_edd_comments', 999 );

/**
 * [cmp_get_post_type_name description]
 * @param  [type] $post_type [description]
 * @return [type]            [description]
 */
if(!function_exists('cmp_get_post_type_name')){
    function cmp_get_post_type_name($post_type){
        $obj = get_post_type_object( $post_type );
        return $obj->labels->singular_name;
    }
}

/*
 * add_name_to_purchase_history
 */
function cmp_add_name_to_purchase_history() {
    ?><th class="edd_purchase_name"><?php echo cmp_get_post_type_name('download'); ?></th><?php
}
function cmp_add_downloads_to_purchase_history() {
    global $edd_receipt_args;

    $payment   = get_post( $edd_receipt_args['id'] );
    $cart      = edd_get_payment_meta_cart_details( $payment->ID, true );

    ?><td class="edd_purchase_name">
    <?php if( $cart ) : ?>
        <?php foreach ( $cart as $key => $item ) :
            $price_id = edd_get_cart_item_price_id( $item ); ?>
            <a href="<?php echo get_permalink($item['id']); ?>" target="_blank">
            <?php echo esc_html( $item['name'] ); ?>
            </a>
            <?php if( ! is_null( $price_id ) ) : ?>
                <span class="edd_purchase_receipt_price_name"> – <?php echo edd_get_price_option_name( $item['id'], $price_id, $payment->ID ); ?></span>
            <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </td><?php
}
if(cmp_get_option('edd_add_product_name')){
    add_action( 'edd_purchase_history_header_before', 'cmp_add_name_to_purchase_history' );
    add_action( 'edd_purchase_history_row_start', 'cmp_add_downloads_to_purchase_history' );
}
/**
 * [cmp_edd_widgets_init description]
 * @return [type] [description]
 */
function cmp_edd_widgets_init() {
    /*=================Widgets For Sidebars Right===========================*/
    $before_widget =  '<div id="%1$s" class="widget-box widget %2$s">';
    $after_widget  =  '</div></div>';
    $before_title  =  '<div class="widget-title"><span class="icon"><i class="fa fa-list fa-fw"></i></span><h3>';
    $after_title   =  '</h3></div><div class="widget-content">';
    register_sidebar( array(
    'name' =>  __( 'EDD Archive Widget Area', 'wpdx' ),
    'id' => 'edd-archive-widget-area',
    'description' => __( 'EDD Archive Widget Area', 'wpdx' ),
    'before_widget' => $before_widget , 'after_widget' => $after_widget ,
    'before_title' => $before_title , 'after_title' => $after_title ,
    ) );
    register_sidebar( array(
    'name' =>  __( 'EDD Single Download Widget Area', 'wpdx' ),
    'id' => 'edd-download-widget-area',
    'description' => __( 'EDD Single Download Widget Area', 'wpdx' ),
    'before_widget' => $before_widget , 'after_widget' => $after_widget ,
    'before_title' => $before_title , 'after_title' => $after_title ,
    ) );
}
add_action( 'widgets_init', 'cmp_edd_widgets_init' );