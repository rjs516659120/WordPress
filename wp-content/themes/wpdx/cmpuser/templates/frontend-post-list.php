<div class="post-count"><?php printf( __( 'You have created <span>%d</span> %s', 'wpdx' ), $dashboard_query->found_posts, $post_type_obj->label ); ?></div>

<?php if ( $dashboard_query->have_posts() ) { ?>

    <table class="wpuf-table <?php echo $post_type; ?>" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="uf-title"><?php _e( 'Title', 'wpdx' ); ?></th>
                <th class="uf-status"><?php _e( 'Status', 'wpdx' ); ?></th>
                <?php do_action( 'cmpuser_dashboard_head_col', $args ) ?>
                <th class="uf-options"><?php _e( 'Options', 'wpdx' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $post;

            while ( $dashboard_query->have_posts() ) {
                $dashboard_query->the_post();
                $show_link = !in_array( $post->post_status, array('draft', 'future', 'pending') );
                ?>
                <tr>
                    <td class="p-title">
                        <?php if ( !$show_link ) { ?>

                            <?php the_title(); ?>

                        <?php } else { ?>

                            <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdx' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>

                        <?php } ?>
                    </td>
                    <td>
                        <?php cmpuser_show_post_status( $post->post_status ); ?>
                    </td>

                    <?php do_action( 'cmpuser_dashboard_row_col', $args, $post ) ?>

                    <td>
                        <?php
                        if ( cmp_get_option( 'enable_post_edit')) {
                            $disable_pending_edit = cmp_get_option( 'disable_pending_edit');
                            $edit_page = (int) cmp_get_option( 'edit_page_id');
                            $url = add_query_arg( array('post_id' => $post->ID), get_permalink( $edit_page ) );

                            if ( $post->post_status == 'pending' && $disable_pending_edit == 'on' ) {
                                // don't show the edit link
                            } else {
                                ?>
                                <a href="<?php echo wp_nonce_url( $url, 'cmpufp_edit' ); ?>"><?php _e( 'Edit', 'wpdx' ); ?></a>
                                <?php
                            }
                        }
                        ?>

                        <?php
                        if ( cmp_get_option( 'enable_post_del') ) {
                            $del_url = add_query_arg( array('action' => 'del', 'post_id' => $post->ID) );
                            $confirm = __('Are you sure to delete?','wpdx');
                            ?>
                            <a href="<?php echo wp_nonce_url( $del_url, 'cmpufp_del' ) ?>" onclick="return confirm('<?php echo $confirm ?>');"><span style="color: red;"><?php _e( 'Delete', 'wpdx' ); ?></span></a>
                        <?php } ?>
                    </td>
                </tr>
                <?php
            }

            wp_reset_postdata();
            ?>

        </tbody>
    </table>

    <div class="wpuf-pagination page-nav">
        <?php
        $pagination = paginate_links( array(
            'base'      => add_query_arg( 'pagenum', '%#%' ),
            'format'    => '',
            'prev_text' => __( '&laquo;', 'wpdx' ),
            'next_text' => __( '&raquo;', 'wpdx' ),
            'total'     => $dashboard_query->max_num_pages,
            'current'   => $pagenum,
            'add_args'  => false
        ) );

        if ( $pagination ) {
            echo $pagination;
        }
        ?>
    </div>
    <div class="clear"></div>

    <?php
} else {
    printf( '<div class="wpuf-message">' . __( 'No %s found', 'wpdx' ) . '</div>', $post_type_obj->label );
}

/**
 * Format the post status for user dashboard
 *
 * @param string $status
 * @since version 0.1
 * @author Changmeng Hu
 */
function cmpuser_show_post_status( $status ) {

    if ( $status == 'publish' ) {

        $title = __( 'Published', 'wpdx' );
        $fontcolor = '#51C332';
    } else if ( $status == 'draft' ) {

        $title = __( 'Draft', 'wpdx' );
        $fontcolor = '#bbbbbb';
    } else if ( $status == 'pending' ) {

        $title = __( 'Pending', 'wpdx' );
        $fontcolor = '#C00202';
    } else if ( $status == 'future' ) {
        $title = __( 'Scheduled', 'wpdx' );
        $fontcolor = '#bbbbbb';

    } else if ( $status == 'private' ) {
        $title = __( 'Private', 'wpdx' );
        $fontcolor = '#bbbbbb';
    }

    $show_status = '<span style="color:' . $fontcolor . ';">' . $title . '</span>';
    echo apply_filters( 'cmpuser_show_post_status', $show_status, $status );
}