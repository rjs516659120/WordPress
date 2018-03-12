<?php get_header(); ?>
<div id="content-header">
    <?php cmp_breadcrumbs();?>
</div>
<div class="container-fluid">
    <?php get_template_part('includes/ad-top' );?>
    <div class="row-fluid">
        <section class="span8 archive-list">
            <div class="widget-box" role="main">
                <header id="archive-head">
                    <h1>
                        <?php echo __('Archive: ','wpdx') .trim(wp_title('',0)); ?>
                    </h1>
                </header>
                <?php
                if( cmp_get_option('archive_mobile') &&  cmp_is_mobile()){
                    query_posts($query_string . "&posts_per_page=".cmp_get_option('archive_mobile_number'));
                }else{
                    query_posts($query_string . "&posts_per_page=".cmp_get_option('default_number'));
                }
                get_template_part( 'loop', 'category' );  ?>
            </div>
        </section>
        <?php get_sidebar(); ?>
    </div>
    <?php get_template_part('includes/ad-bottom' );?>
</div>
<?php get_footer(); ?>
</div>
