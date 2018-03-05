<?php
/**
 * http://code.tutsplus.com/tutorials/how-to-create-custom-wordpress-writemeta-boxes--wp-20336
 * http://themefoundation.com/wordpress-meta-boxes-guide/
 */
add_action( 'add_meta_boxes', 'cmp_meta_box_add' );
function cmp_meta_box_add()
{
    $screens = array( 'post', 'page' );
    foreach ( $screens as $screen ) {
        add_meta_box(
            'cmp-seo',
            __('SEO Settings','wpdx'),
            'cmp_meta_box_cb',
            $screen,
            'normal',
            'high'
            );
    }
}

function cmp_meta_box_cb( $post )
{
    $values = get_post_custom( $post->ID );
    $seo_title = isset( $values['_cmp_seo_title'] ) ? esc_attr( $values['_cmp_seo_title'][0] ) : '';
    $seo_keywords = isset( $values['_cmp_seo_keywords'] ) ? esc_attr( $values['_cmp_seo_keywords'][0] ) : '';
    $seo_description = isset( $values['_cmp_seo_description'] ) ? esc_attr( $values['_cmp_seo_description'][0] ) : '';
    $original_author = isset( $values['_cmp_original_author'] ) ? esc_attr( $values['_cmp_original_author'][0] ) : '';
    $original_website = isset( $values['_cmp_original_website'] ) ? esc_attr( $values['_cmp_original_website'][0] ) : '';
    $original_url = isset( $values['_cmp_original_url'] ) ? esc_attr( $values['_cmp_original_url'][0] ) : '';
    wp_nonce_field( 'cmp_seo_meta_box_nonce', 'seo_meta_box_nonce' );
    ?>
    <table class="form-table">
        <tbody>
            <tr>
                <th style="width:20%;">
                    <label for="cmp_seo_title"><?php _e('SEO title','wpdx'); ?></label>
                </th>
                <td>
                    <input type="text" name="cmp_seo_title" id="cmp_seo_title" size="30" tabindex="30" style="width: 97%;" value="<?php echo $seo_title; ?>" />
                    <p class="description"><?php _e('You can set a different title for the page &lt;title&gt; label. Its priority is higher than the default title of this article.','wpdx'); ?></p>
                </td>
            </tr>
            <tr>
                <th style="width:20%;">
                    <label for="cmp_seo_keywords"><?php _e( 'SEO Keywords', 'wpdx' )?></label>
                </th>
                <td>
                    <textarea name="cmp_seo_keywords" id="cmp_seo_keywords" cols="60" rows="4" tabindex="30" style="width: 97%;"><?php echo $seo_keywords; ?></textarea>
                    <p class="description"><?php _e('Contents set here will be used as the value of the page keywords meta, and multiple keywords separated by commas. Its priority is higher than the tags of this article.','wpdx'); ?></p>
                </td>
            </tr>
            <tr>
                <th style="width:20%;">
                    <label for="cmp_seo_description"><?php _e( 'SEO Description', 'wpdx' )?></label>
                </th>
                <td>
                    <textarea name="cmp_seo_description" id="cmp_seo_description" cols="60" rows="4" tabindex="30" style="width: 97%;"><?php echo $seo_description; ?></textarea>
                    <p class="description"><?php _e('Contents set here will be used as the value of the page description meta. Its priority is higher than the excerpt of this article.','wpdx'); ?></p>
                </td>
            </tr>
            <tr>
                <th style="width:20%;">
                    <label for="cmp_original_website"><?php _e('Original website','wpdx'); ?></label>
                </th>
                <td>
                    <input type="text" name="cmp_original_website" id="cmp_original_website" size="30" tabindex="30" style="width: 97%;" value="<?php echo $original_website; ?>" />
                    <p class="description"><?php _e('If this is an original article, please leave blank. Otherwise, please fill in the name of the original website.','wpdx'); ?></p>
                </td>
            </tr>
            <tr>
                <th style="width:20%;">
                    <label for="cmp_original_author"><?php _e('Original author','wpdx'); ?></label>
                </th>
                <td>
                    <input type="text" name="cmp_original_author" id="cmp_original_author" size="30" tabindex="30" style="width: 97%;" value="<?php echo $original_author; ?>" />
                    <p class="description"><?php _e('If this is an original article, please leave blank. Otherwise, please fill in the name of the original author. If you don\'t know the name of the author, please fill in the name of the original website, the same as above.','wpdx'); ?></p>
                </td>
            </tr>
            <tr>
                <th style="width:20%;">
                    <label for="cmp_original_url"><?php _e('Original URL','wpdx'); ?></label>
                </th>
                <td>
                    <input type="text" name="cmp_original_url" id="cmp_original_url" size="30" tabindex="30" style="width: 97%;" value="<?php echo $original_url; ?>" />
                    <p class="description"><?php _e('If this is an original article, please leave blank. Otherwise, please fill in the original url of the article, including http:// or https:// .','wpdx'); ?></p>
                </td>
            </tr>
        </tbody>
    </table>
    <?php
}


add_action( 'save_post', 'cmp_meta_box_save' );
function cmp_meta_box_save( $post_id )
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['seo_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['seo_meta_box_nonce'], 'cmp_seo_meta_box_nonce' ) ) return;

    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;

    // Probably a good idea to make sure your data is set
    if( isset( $_POST['cmp_seo_title'] ) )
        update_post_meta( $post_id, '_cmp_seo_title', wp_kses_post( $_POST['cmp_seo_title'] ) );

    if( isset( $_POST['cmp_seo_keywords'] ) )
        update_post_meta( $post_id, '_cmp_seo_keywords', esc_attr( $_POST['cmp_seo_keywords'] ) );

    if( isset( $_POST['cmp_seo_description'] ) )
        update_post_meta( $post_id, '_cmp_seo_description', esc_attr( $_POST['cmp_seo_description'] ) );

    if( isset( $_POST['cmp_original_author'] ) )
        update_post_meta( $post_id, '_cmp_original_author', esc_attr( $_POST['cmp_original_author'] ) );

    if( isset( $_POST['cmp_original_website'] ) )
        update_post_meta( $post_id, '_cmp_original_website', esc_attr( $_POST['cmp_original_website'] ) );

    if( isset( $_POST['cmp_original_url'] ) )
        update_post_meta( $post_id, '_cmp_original_url', esc_attr( $_POST['cmp_original_url'] ) );
}