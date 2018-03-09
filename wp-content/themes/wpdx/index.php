<?php get_header(); ?>
<?php if(cmp_get_option( 'announcement' )):?>
<div id="content-header">
        <div id="top-announce">
            <i class="fa fa-bullhorn fa-fw"></i><?php echo htmlspecialchars_decode(cmp_get_option( 'announcement' ));?>
        </div>
</div>
<?php endif;?>
<div class="container-fluid home-fluid">
    <?php get_template_part('includes/ad-top' );?>
    <?php
    if( is_home() && !is_paged() && cmp_get_option( 'slider' )) {
        if(cmp_get_option( 'slider_style' ) == '1') cmp_include( 'slider-wide' );
        elseif (cmp_get_option( 'slider_style' ) == '2') cmp_include( 'slider' );
    } ?>
    <div class="row-fluid">
        <?php if( cmp_get_option('on_home') != 'boxes' ){ ?>
        <section class="span8">
            <?php
            if( is_home() && !is_paged() && cmp_get_option( 'slider' ) && cmp_get_option( 'slider_style' ) == '3') cmp_include( 'slider-blog' );

            $do_not_duplicate = explode(',',cmp_get_option( 'exclude_posts' ));
            $posts_per_page = (cmp_get_option( 'blog_posts_number' )) ? cmp_get_option( 'blog_posts_number' ) : 10;
            if(cmp_get_option( 'ignore_sticky' )) {
                $args = array('ignore_sticky_posts' => 1,'post__not_in' =>$do_not_duplicate,'cat' => cmp_get_option( 'exclude_categories' ),'paged' => $paged,'posts_per_page' => $posts_per_page ); 
            } else {
                $args = array('post__not_in' =>$do_not_duplicate,'cat' => cmp_get_option( 'exclude_categories' ),'paged' => $paged,'posts_per_page' => $posts_per_page ); 
            }
            if(cmp_get_option('post_types_for_home')){
                $main_types = cmp_get_option('post_types_for_home');
                $args['post_type'] = $main_types;
            }
            query_posts($args);
            get_template_part( 'loop', 'blog' );
            ?>
        </section>
        <?php
        get_sidebar();
    }else{
        $cats = get_option( 'cmp_home_cats' ) ;
        if($cats){
            foreach ($cats as $cat) cmp_get_home_cats($cat);
        }else{
            _e( 'You can use Homepage builder to build your homepage' , 'wpdx' );
        } 
    }
    ?>
</div>
<?php get_template_part('includes/ad-bottom' );?>
<?php get_template_part('includes/home-links' );?>
</div>
<?php get_footer(); ?>
</div>
