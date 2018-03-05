<?php
class Cmp_Lazy_Load
{
    protected $_placeholder_url;
    protected $_skip_images_classes;
    protected static $_instance;
    function __construct() {
        // Disable for Dashboard
        if ( is_admin() ) {
            return;
        }

        // Disable when viewing printable page from WP-Print
        if ( intval( get_query_var( 'print' ) ) == 1 || intval( get_query_var( 'printpage' ) ) == 1 ) {
            return;
        }
        // Disable on Opera Mini
        if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mini' ) !== false ) {
            return;
        }

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 11 );

        add_filter( 'Cmp_lazy_load_html', array( $this, 'filter_html' ), 10, 2 );
        $this->_placeholder_url = get_template_directory_uri(). '/assets/images/grey.gif';
        // Apply for Images
        $skip_images_classes = apply_filters( 'lazyload_skip_classes', cmp_get_option('skip_classes') );
        if ( strlen( trim( $skip_images_classes ) ) ) {
            $this->_skip_images_classes = array_map( 'trim', explode( ',', $skip_images_classes ) );
        }
        if ( is_array( $this->_skip_images_classes ) ) {
            $this->_skip_images_classes = array_merge( array('cmp-notlazy'), $this->_skip_images_classes );
        } else {
            $this->_skip_images_classes = array('cmp-notlazy');
        }
            //add_filter( 'cmp_lazyload_images', array( $this, 'filter_images' ), 10, 2 );
            add_filter( 'the_content', array( $this, 'filter_content_images' ), 10 );
            add_filter( 'widget_text', array( $this, 'filter_images' ), 200 );
            add_filter( 'post_thumbnail_html', array( $this, 'filter_images' ), 200 );
            add_filter( 'get_avatar', array( $this, 'filter_images' ), 200 );
    }
    static function _instance() {
        if ( ! isset( self::$_instance ) ) {
            $className = __CLASS__;
            self::$_instance = new $className;
        }
        return self::$_instance;
    }
    static function enqueue_scripts() {
        wp_register_script( 'cmp-lazyload', get_template_directory_uri() .'/assets/js/lazyload.min.js', array( 'jquery' ), THEME_VER , 1,true );
        wp_enqueue_script( 'cmp-lazyload' );
    }
    static function filter_html( $content ) {
        if ( is_admin() ) {
            return $content;
        }
        $run_filter = true;
        if ( ! $run_filter ) {
            return $content;
        }
        $Cmp_Lazy_Load = Cmp_Lazy_Load::_instance();
        $content = $Cmp_Lazy_Load->filter_images( $content );
        return $content;
    }
    static function filter_images( $content ) {
        if ( is_admin() ) {
            return $content;
        }
        $run_filter = true;
        if ( ! $run_filter ) {
            return $content;
        }
        $Cmp_Lazy_Load = Cmp_Lazy_Load::_instance();
        $content = $Cmp_Lazy_Load->_filter_images( $content );
        return $content;
    }
    static function filter_content_images( $content ) {
        $Cmp_Lazy_Load = Cmp_Lazy_Load::_instance();
        add_filter( 'wp_get_attachment_image_attributes', array( $Cmp_Lazy_Load, 'get_attachment_image_attributes' ), 200 );
        return $Cmp_Lazy_Load->filter_images( $content );
    }
    static function get_attachment_image_attributes( $attr ) {
        $Cmp_Lazy_Load = Cmp_Lazy_Load::_instance();
        $attr['lazydata-src'] = $attr['src'];
        $attr['src'] = $Cmp_Lazy_Load->_placeholder_url;
        $attr['class'] = 'lazy-hidden '. $attr['class'];
        $attr['data-lazy-type'] = 'image';
        if ( isset( $attr['srcset'] ) ) {
            $attr['data-srcset'] = $attr['srcset'];
            $attr['srcset'] = '';
            unset( $attr['srcset'] );
        }
        return $attr;
    }
    protected function _filter_images( $content ) {
        $matches = array();
        preg_match_all( '/<img[\s\r\n]+.*?>/is', $content, $matches );
        $search = array();
        $replace = array();
        if ( is_array( $this->_skip_images_classes ) ) {
            $skip_images_preg_quoted = array_map( 'preg_quote', $this->_skip_images_classes );
            $skip_images_regex = sprintf( '/class=".*(%s).*"/s', implode( '|', $skip_images_preg_quoted ) );
        }
        $i = 0;
        foreach ( $matches[0] as $imgHTML ) {
            // don't to the replacement if a skip class is provided and the image has the class, or if the image is a data-uri
            if ( ! ( is_array( $this->_skip_images_classes ) && preg_match( $skip_images_regex, $imgHTML ) ) && ! preg_match( "/src=['\"]data:image/is", $imgHTML ) && ! preg_match( "/src=.*grey.gif['\"]/s", $imgHTML ) ) {
                $i++;
                // replace the src and add the data-src attribute
                $replaceHTML = '';
                $replaceHTML = preg_replace( '/<img(.*?)src=/is', '<img$1src="' . $this->_placeholder_url . '" data-lazy-type="image" lazydata-src=', $imgHTML );
                $replaceHTML = preg_replace( '/<img(.*?)srcset=/is', '<img$1srcset="" data-srcset=', $replaceHTML );
                // add the lazy class to the img element
                if ( preg_match( '/class=["\']/i', $replaceHTML ) ) {
                    $replaceHTML = preg_replace( '/class=(["\'])(.*?)["\']/is', 'class=$1lazy lazy-hidden $2$1', $replaceHTML );
                } else {
                    $replaceHTML = preg_replace( '/<img/is', '<img class="lazy lazy-hidden"', $replaceHTML );
                }
                $replaceHTML .= '<noscript>' . $imgHTML . '</noscript>';
                array_push( $search, $imgHTML );
                array_push( $replace, $replaceHTML );
            }
        }
        $search = array_unique( $search );
        $replace = array_unique( $replace );
        $content = str_replace( $search, $replace, $content );
        return $content;
    }
}
add_action( 'wp', 'Cmp_lazy_load_instance', 10, 0 );
function Cmp_lazy_load_instance() {
    $allow_instance = true;
    if ( is_feed() ) {
        $allow_instance = false;
    }
    if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
        $allow_instance = false;
    }
    if ( $allow_instance ) {
        Cmp_Lazy_Load::_instance();
    }
}