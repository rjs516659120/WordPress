<?php
/*
Template Name: 用户列表（只显示有文章的用户）
*/
get_header(); ?>
<?php
function authors_with_posts( $query ) {
    global $wpdb;
    if ( isset( $query->query_vars['query_id'] ) && 'authors_with_posts' == $query->query_vars['query_id'] ) {
        $query->query_from = $query->query_from . ' LEFT OUTER JOIN (
            SELECT post_author, COUNT(*) as post_count
            FROM '.$wpdb->prefix.'posts
            WHERE post_type = "post" AND (post_status = "publish" OR post_status = "private")
            GROUP BY post_author
            ) p ON ('.$wpdb->prefix.'users.ID = p.post_author)';
$query->query_where = $query->query_where . ' AND post_count  > 0';
}
}
add_action('pre_user_query','authors_with_posts');

function cmp_get_user_latest_posts($user_id,$number=3){
    $args = array(
        'posts_per_page' => $number,
        'author'    => $user_id,
        );
    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) {
        ob_start();
        echo '<ul>';
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            echo '<li><a href="'.get_permalink().'" target="_blank">' . get_the_title() . '</a></li>';
        }
        echo '</ul>';
        ob_end_flush();
    } else {
    // no posts found
    }
    wp_reset_postdata();
}

$no=10;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
if($paged==1){
    $offset=0;
} else {
 $offset= ($paged-1)*$no;
}

$args = array(
    'number' => $no,
    'offset' => $offset,
    'query_id' => 'authors_with_posts',
    'orderby' => 'ID',
    'order' => 'ASC'
    );
$user_query = new WP_User_Query( $args );
$authors = $user_query->get_results();
?>
<div id="content-header">
    <?php cmp_breadcrumbs();?>
</div>
<div class="container-fluid">
    <?php get_template_part('includes/ad-top' );?>
    <div class="row-fluid">
        <div class="span12">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php if(function_exists('cmp_setPostViews')) cmp_setPostViews(); ?>
                <div class="widget-box">
                    <article class="widget-content single-post">
                        <header id="post-header">
                            <h1 class="page-title"><?php the_title(); ?></h1>
                        </header>
                        <div class="entry users-page">
                            <?php the_content(); ?>
                            <?php if(!empty($authors)): ?>
                                <div class="user-list">
                                    <?php foreach($authors as $author) : ?>
                                        <div class="span12 user-info">
                                            <div class="span6">
                                                <div class="span2">
                                                    <p><a class="user-avatar" rel="nofollow" href="<?php echo get_author_posts_url($author->ID); ?>"><?php echo get_avatar($author->ID, 80); ?></a></p>
                                                    <p><a class="user-name" href="<?php echo get_author_posts_url($author->ID); ?>">
                                                    <?php echo $author->display_name; ?></a></p>
                                                </div>
                                                <div class="span10">
                                                    <p><?php if($author->description){
                                                        echo $author->description;
                                                    }else{
                                                        $current_user = wp_get_current_user();
                                                        if(class_exists("WP_User_Frontend") && $current_user->ID == $author->ID){
                                                            $profile_url = get_home_url().'/user/profile';
                                                            $description = sprintf(__('Please visit <a href="%s">Your Profile</a> to fill in Biographical Info.','wpdx'),$profile_url);
                                                        }elseif($current_user->ID == $author->ID){
                                                            $profile_url = get_home_url().'/wp-admin/profile.php';
                                                            $description = sprintf(__('Please visit <a href="%s">Your Profile</a> to fill in Biographical Info.','wpdx'),$profile_url);
                                                        }else{
                                                            $description = __('The user is lazy, not fill in his Biographical Info.','wpdx');
                                                        }
                                                        echo $description;
                                                    }
                                                    ?>
                                                </p>
                                                <ul class="author-social follows nb">
                                                    <?php
                                                    ?>
                                                    <li class="archive">
                                                        <a target="_blank" href="<?php echo get_author_posts_url($author->ID); ?>" title="<?php echo sprintf( __( "Read more articles of %s", 'wpdx' ), $author->display_name); ?>"><?php echo sprintf( __( "Read more articles of %s", 'wpdx' ), $author->display_name); ?></a>
                                                    </li>
                                                    <?php if ( $author->user_url ) : ?>
                                                        <li class="website">
                                                            <a target="_blank" rel="nofollow" href="<?php echo $author->user_url; ?>" title="<?php echo sprintf( __( "Visit %s's site", 'wpdx' ), $author->display_name); ?>"><?php echo sprintf( __( "Visit %s's site", 'wpdx' ), $author->display_name); ?></a>
                                                        </li>
                                                    <?php endif ?>
                                                    <?php if ( $author->qq ) : ?>
                                                        <li class="qq">
                                                            <a target="_blank" rel="nofollow" href="http://wpa.qq.com/msgrd?v=3&amp;site=qq&amp;menu=yes&amp;uin=<?php echo $author->qq; ?>" title="<?php echo sprintf( __( "Contact %s by QQ", 'wpdx' ), $author->display_name); ?>"><?php echo sprintf( __( "Contact %s by QQ", 'wpdx' ), $author->display_name); ?></a>
                                                        </li>
                                                    <?php endif ?>
                                                    <?php if(class_exists("fep_main_class")){ ?>
                                                    <li class="email">
                                                        <a target="_blank" rel="nofollow" href="<?php echo get_home_url(); ?>/user/pm?fepaction=newmessage&amp;to=<?php echo $author->user_login; ?>" title="<?php echo sprintf( __( "Contact %s by Private Messages", 'wpdx' ), $author->display_name); ?>"><?php echo sprintf( __( "Contact %s by Private Messages", 'wpdx' ), $author->display_name); ?></a>
                                                    </li>
                                                    <?php } elseif($author->qm_mailme) { ?>
                                                    <li class="email">
                                                        <a target="_blank" rel="nofollow" href="<?php echo $author->qm_mailme; ?>" title="<?php echo sprintf( __( "Contact %s by Email", 'wpdx' ), $author->display_name); ?>"><?php echo sprintf( __( "Contact %s by Email", 'wpdx' ), $author->display_name); ?></a>
                                                    </li>
                                                    <?php } ?>
                                                    <?php if ( $author->sina_weibo ) : ?>
                                                        <li class="sina_weibo">
                                                            <a target="_blank" rel="nofollow" href="<?php echo $author->sina_weibo; ?>" title="<?php echo sprintf( __( "Follow %s on Sina Weibo", 'wpdx' ), $author->display_name); ?>"><?php echo sprintf( __( "Follow %s on Sina Weibo", 'wpdx' ), $author->display_name); ?></a>
                                                        </li>
                                                    <?php endif ?>
                                                    <?php if ( $author->qq_weibo ) : ?>
                                                        <li class="qq_weibo">
                                                            <a target="_blank" rel="nofollow" href="<?php echo $author->qq_weibo; ?>" title="<?php echo sprintf( __( "Follow %s on QQ Weibo", 'wpdx' ), $author->display_name); ?>"><?php echo sprintf( __( "Follow %s on QQ Weibo", 'wpdx' ), $author->display_name); ?></a>
                                                        </li>
                                                    <?php endif ?>
                                                    <?php if ( $author->twitter ) : ?>
                                                        <li class="twitter">
                                                            <a target="_blank" rel="nofollow" href="<?php echo $author->twitter; ?>" title="<?php echo sprintf( __( "Follow %s on Twitter", 'wpdx' ), $author->display_name); ?>"><?php echo sprintf( __( "Follow %s on Twitter", 'wpdx' ), $author->display_name); ?></a>
                                                        </li>
                                                    <?php endif ?>
                                                    <?php if ( $author->google_plus ) : ?>
                                                        <li class="google_plus">
                                                            <a target="_blank" href="<?php echo $author->google_plus; ?>" rel="author" title="<?php echo sprintf( __( "Follow %s on Google+", 'wpdx' ), $author->display_name); ?>"><?php echo sprintf( __( "Follow %s on Google+", 'wpdx' ), $author->display_name); ?></a>
                                                        </li>
                                                    <?php endif ?>
                                                </ul>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <div class="span6 user-posts">
                                        <p class="user-posts-head"><?php echo  $author->display_name . sprintf( __( " has published <span> %s </span> posts, latest posts: ", 'wpdx' ), count_user_posts( $author->ID )); ?></p>
                                        <?php cmp_get_user_latest_posts($author->ID,4); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="clear"></div>
                            <?php
                            $total_user = $user_query->total_users;
                            $total_pages=ceil($total_user/$no);
                            $page_links = paginate_links( array(
                                'base' => get_pagenum_link(1) . '%_%',
                                'format' => '?paged=%#%',
                                'prev_text' => __( '&laquo;', 'wpdx' ),
                                'next_text' => __( '&raquo;', 'wpdx' ),
                                'total' => $total_pages,
                                'current' => $paged
                                ) );
                            if ( $page_links ) {
                                echo '<div class="page-nav"><span class="pages">'.$paged.'/'.$total_pages.'</span>' . $page_links . '</div>';
                            }
                            ?>
                        <?php endif; ?>
                    </div>
                    <div class="clear"></div>
                </div>
            </article>
        </div>
    <?php endwhile;?>
</div>
</div>
<?php get_template_part('includes/ad-bottom' );?>
</div>
<?php get_footer(); ?>
</div>
