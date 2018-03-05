<?php
function cmp_head_metas(){
    global $post, $page, $paged;
    //description & keywords
    $description ='';
    $keywords ='';
    if (is_home() || is_front_page()){
        $description = strip_tags(trim(cmp_get_option('homepage_description')));
        $keywords = cmp_get_option('homepage_keywords');
    }elseif( is_post_type_archive('download')) {
        $description = strip_tags(trim(cmp_get_option('edd_archive_description')));
        $keywords = cmp_get_option('edd_archive_keywords');
    }elseif( is_post_type_archive('product')) {
        $description = strip_tags(trim(cmp_get_option('wc_archive_description')));
        $keywords = cmp_get_option('wc_archive_keywords');
    }elseif (is_category() ){
        $category_id = get_query_var('cat');
        $tax_keywords = get_option('cm_tax_keywords'.$category_id);
        if($tax_keywords){
            $keywords = strip_tags(trim($tax_keywords));
        }else{
            $keywords = single_cat_title('', false);
        }
        $category_description = category_description();
        if(!empty($category_description)){
            $description = strip_tags(trim(wp_trim_words($category_description,130,"") ));
        }else{
            $description = strip_tags(trim(sprintf( __( 'The following articles associated with the category: %s', 'wpdx' ), single_cat_title('', false)) ));
        }
    }elseif (is_tax()){
        $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        $tax_keywords = get_option('cm_tax_keywords'. $current_term->term_id);
        if($tax_keywords){
            $keywords = strip_tags(trim($tax_keywords));
        }else{
            $keywords = single_term_title('', false);
        }
        $description = strip_tags(trim(wp_trim_words(category_description(),130,"") ));
        $term_description = category_description();
        if(!empty($term_description)){
            $description = strip_tags(trim(wp_trim_words($term_description,130,"") ));
        }else{
            $description = strip_tags(trim(sprintf( __( 'The following articles associated with the term: %s', 'wpdx' ), single_term_title('', false)) ));
        }
    }elseif (is_tag()){
        $tag_keywords = get_option('cm_tax_keywords' . get_query_var('tag_id'));
        if($tag_keywords){
            $keywords = strip_tags(trim($tag_keywords));
        }else{
            $keywords = single_cat_title('', false);
        }
        $tag_description = category_description();
        if(!empty($tag_description)){
            $description = strip_tags(trim(wp_trim_words($tag_description,130,"") ));
        }else{
            $description = strip_tags(trim(sprintf( __( 'The following articles associated with the tag: %s', 'wpdx' ), single_tag_title('', false)) ));
        }
    }elseif (is_singular("portfolio")) {
        if(get_post_meta($post->ID, "_cmp_seo_description", true)) {
            $description = strip_tags(trim(get_post_meta($post->ID, "_cmp_seo_description", true)));
        } elseif ($post->post_excerpt) {
            $description = strip_tags(trim($post->post_excerpt));
        } else {
            $content = $post->post_content;
            $content = preg_replace("/\[caption.*\[\/caption\]/", '', $content);
            $description = strip_tags(trim(wp_trim_words($content, 130,"") ));
        };
        if ($terms = wp_get_object_terms( $post->ID, 'portfolio_tags' )){
            $the_tags_post = '';
            $terms_array = array();
            foreach ($terms as $term) {
                $the_tags_post .= $term->name . ',';
            }
            $keywords = strip_tags(trim($the_tags_post, ','));
        }
    }elseif (is_singular("download")) {
        if(get_post_meta($post->ID, "_cmp_seo_description", true)) {
            $description = strip_tags(trim(get_post_meta($post->ID, "_cmp_seo_description", true)));
        } elseif ($post->post_excerpt) {
            $description = strip_tags(trim($post->post_excerpt));
        } else {
            $content = $post->post_content;
            $content = preg_replace("/\[caption.*\[\/caption\]/", '', $content);
            $description = strip_tags(trim(wp_trim_words($content, 130,"") ));
        };
        if ($terms = wp_get_object_terms( $post->ID, 'download_tag' )){
            $the_tags_post = '';
            $terms_array = array();
            foreach ($terms as $term) {
                $the_tags_post .= $term->name . ',';
            }
            $keywords = strip_tags(trim($the_tags_post, ','));
        }
    }elseif (is_singular("product")) {
        if(get_post_meta($post->ID, "_cmp_seo_description", true)) {
            $description = strip_tags(trim(get_post_meta($post->ID, "_cmp_seo_description", true)));
        } elseif ($post->post_excerpt) {
            $description = strip_tags(trim($post->post_excerpt));
        } else {
            $content = $post->post_content;
            $content = preg_replace("/\[caption.*\[\/caption\]/", '', $content);
            $description = strip_tags(trim(wp_trim_words($content, 130,"") ));
        };
        if ($terms = wp_get_object_terms( $post->ID, 'product_tag' )){
            $the_tags_post = '';
            $terms_array = array();
            foreach ($terms as $term) {
                $the_tags_post .= $term->name . ',';
            }
            $keywords = strip_tags(trim($the_tags_post, ','));
        }
    }elseif (is_single()){
        if(get_post_meta($post->ID, "_cmp_seo_description", true)) {
            $description = strip_tags(trim(get_post_meta($post->ID, "_cmp_seo_description", true)));
        } elseif ($post->post_excerpt) {
            $description = strip_tags(trim($post->post_excerpt));
        } else {
            $content = $post->post_content;
            $content = preg_replace("/\[caption.*\[\/caption\]/", '', $content);
            $description = strip_tags(trim(wp_trim_words($content, 130,"") ));
        };
        if(get_post_meta($post->ID, "_cmp_seo_keywords", true)) {
            $keywords = strip_tags(trim(get_post_meta($post->ID, "_cmp_seo_keywords", true)));
        } else{
            $tags = wp_get_post_tags($post->ID);
            if(function_exists('dwqa_plugin_init') && 'dwqa-question' == get_post_type()){
                $tags = wp_get_object_terms( $post->ID,  'dwqa-question_tag' );
            }
            if(!empty($tags) && !is_wp_error( $tags ) ){
                $keyword = '';
                foreach ($tags as $tag ) {
                    $keyword = $keyword . $tag->name . ",";
                    $keywords =  trim($keyword,',');
                }
            }
        }
    }elseif (is_page()){
        if(get_post_meta($post->ID, "_cmp_seo_description", true)) {
            $description = strip_tags(trim(get_post_meta($post->ID, "_cmp_seo_description", true)));
        } else {
            $content = $post->post_content;
            $content = preg_replace("/\[caption.*\[\/caption\]/", '', $content);
            $description = strip_tags(trim(wp_trim_words($content, 130,"") ));
        };
        if(get_post_meta($post->ID, "_cmp_seo_keywords", true)) {
            $keywords = strip_tags(trim(get_post_meta($post->ID, "_cmp_seo_keywords", true)));
        } else{
            $keywords = single_post_title('', false);
        }
    }elseif( is_search() ){
        $keywords = strip_tags(get_search_query());
        $description = sprintf( __( 'Posts of search results for: %s', 'wpdx' ), get_search_query() );
    }elseif( is_author() ){
        $userdata = get_user_by( 'slug', get_query_var( 'author_name' ) );
        if(!$userdata) $userdata = get_user_by( 'ID', get_query_var( 'author' ) );
        $keywords = strip_tags($userdata->display_name);
        $description = sprintf( __( 'Posts of author: %s', 'wpdx' ),  $userdata->display_name ).'('.strip_tags(trim($userdata->description)).')';
    }
//description paged
    $desc_page = '';
    if ( $paged >= 2 || $page >= 2 ){
        $dPage = sprintf( __( 'Page %s:', 'wpdx' ), max( $paged, $page ) );
        $desc_page = $dPage;
    }
    $description = $desc_page.$description;
    echo '<meta name="keywords" content="'. $keywords.'" />'."\n";
    echo '<meta name="description" content="'. $description.'" />'."\n";
}
add_action('wp_head','cmp_head_metas',1);

function theme_slug_setup() {
   add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'theme_slug_setup' );
/**
* Filter the separator for the document title.
*
* @since 4.4.0
*
* @param string $sep Document title separator. Default '-'.
* @website: www.developersq.com
* @author: Aakash Dodiya
*/
add_filter('document_title_separator', 'cmp_document_title_separator',10);
function cmp_document_title_separator($sep){
    $sep = cmp_get_option('separator')?cmp_get_option('separator'):'|';
    return $sep; 
}
/*
 * Override default post/page title - example
 * @param array $title {
 *     The document title parts.
 *
 *     @type string $title   Title of the viewed page.
 *     @type string $page    Optional. Page number if paginated.
 *     @type string $tagline Optional. Site description when on home page.
 *     @type string $site    Optional. Site title when not on home page.
 * }
 *     @since WordPress 4.4
 *     @website: www.developersq.com
 *     @author: Aakash Dodiya
*/
add_filter('document_title_parts', 'cmp_override_post_title', 10);
function cmp_override_post_title($title){
    global $post;
    if ( is_home() || is_front_page() ) {
        if(cmp_get_option('homepage_title')){
            $title['title'] = cmp_get_option('homepage_title');
            unset($title['tagline']);
        }
    }elseif( is_post_type_archive('download')) {
        if(cmp_get_option('edd_archive_title')){
            $title['title'] = cmp_get_option('edd_archive_title');
            //unset($title['tagline']);
        }
    }elseif( is_post_type_archive('product')) {
        if(cmp_get_option('wc_archive_title')){
            $title['title'] = cmp_get_option('wc_archive_title');
            //unset($title['tagline']);
        }
    }elseif (is_category()) {
        if(get_option('cm_tax_title' . get_query_var('cat'))){
            $title['title'] = strip_tags(trim(get_option('cm_tax_title' . get_query_var('cat'))));
        }
    }elseif (is_tag()) {
        if(get_option('cm_tax_title' . get_query_var('tag_id'))){
            $title['title'] = strip_tags(trim(get_option('cm_tax_title' . get_query_var('tag_id'))));
        }
    }elseif (is_tax()) {
        $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        if(get_option('cm_tax_title' . $current_term->term_id)){
            $title['title'] = strip_tags(trim(get_option('cm_tax_title' .$current_term->term_id)));
        }
    }elseif(is_singular() && get_post_meta($post->ID, "_cmp_seo_title", true)){
        $title['title'] = strip_tags(trim(get_post_meta($post->ID, "_cmp_seo_title", true)));
    }else{
        if(!cmp_get_option('title_suffix')){
            unset($title['site']);
        }
    }
    return $title; 
}