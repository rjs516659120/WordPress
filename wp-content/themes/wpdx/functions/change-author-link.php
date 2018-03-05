<?php
/**
 * 修改url重写后的作者存档页的链接变量
 * @since yundanran-3 beta 2
 * 2013年10月8日23:23:49
 */
add_filter( 'author_link', 'cmp_author_link', 10, 2 );
function cmp_author_link( $link, $author_id) {
    global $wp_rewrite;
    $author_id = (int) $author_id;
    $link = $wp_rewrite->get_author_permastruct();
 
    if ( empty($link) ) {
        $file = home_url( '/' );
        $link = $file . '?author=' . $author_id;
    } else {
        $link = str_replace('%author%', $author_id, $link);
        $link = home_url( user_trailingslashit( $link ) );
    }
 
    return $link;
}

/**
 * 替换作者的存档页的用户名，防止被其他用途
 * 作者存档页链接有2个查询变量，
 * 一个是author（作者用户id），用于未url重写
 * 另一个是author_name（作者用户名），用于url重写
 * 此处做的是，在url重写之后，把author_name替换为author
 * @version 1.0
 * @since yundanran-3 beta 2
 * 2013年10月8日23:19:13
 * @link https://www.wpdaxue.com/use-nickname-for-author-slug.html
 */
 
add_filter( 'request', 'cmp_author_link_request' );
function cmp_author_link_request( $query_vars ) {
    if ( array_key_exists( 'author_name', $query_vars ) ) {
        global $wpdb;
        $author_id=$query_vars['author_name'];
        if ( $author_id ) {
            $query_vars['author'] = $author_id;
            unset( $query_vars['author_name'] );    
        }
    }
    return $query_vars;
}


/**
 *（全网独家）如何正确的避免你的 WordPress 管理员登录用户名被暴露 - 龙笑天下
 * http://www.ilxtx.com/further-hide-your-wordpress-admin-username.html
 * 说明：直接去掉函数 comment_class() 和 body_class() 中输出的 "comment-author-" 和 "author-"
 */
function cmp_comment_body_class($content){ 
    $pattern = "/(.*?)([^>]*)author-([^>]*)(.*?)/i";
    $replacement = '$1$4';
    $content = preg_replace($pattern, $replacement, $content);  
    return $content;
}
add_filter('comment_class', 'cmp_comment_body_class');
add_filter('body_class', 'cmp_comment_body_class');
