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
                        <?php 
                        $edd_archive_title = cmp_get_option( 'edd_archive_title' ) ? cmp_get_option( 'edd_archive_title' ) : wp_title('',0);
                        echo trim($edd_archive_title); ?>
                    </h1>
                    <?php
                    if(cmp_get_option( 'edd_archive_description' ) ){
                        $category_description = cmp_get_option( 'edd_archive_description' );
                        if(!empty( $category_description ) && !is_paged() )
                            echo '<div class="archive-description">' . $category_description . '</div>';
                    }
                    ?>
                </header>
                <?php get_template_part( 'loop', 'edd' );  ?>
            </div>
        </section>
        <?php get_sidebar('edd'); ?>
    </div>
    <?php get_template_part('includes/ad-bottom' );?>
</div>
</div>
<?php get_footer(); ?>