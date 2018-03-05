<?php
/**
 * Plugin Name: Basic User Avatars
 */

class cmp_user_avatars {

    /**
     * User ID
     *
     * @since 1.0.0
     * @var int
     */
    private $user_id_being_edited;

    /**
     * Initialize all the things
     *
     * @since 1.0.0
     */
    public function __construct() {

        add_action( 'show_user_profile',         array( $this, 'edit_user_profile'        )        );
        add_action( 'edit_user_profile',         array( $this, 'edit_user_profile'        )        );
        add_action( 'personal_options_update',   array( $this, 'edit_user_profile_update' )        );
        add_action( 'edit_user_profile_update',  array( $this, 'edit_user_profile_update' )        );
        add_action( 'bbp_user_edit_after_about', array( $this, 'bbpress_user_profile'     )        );

        // Filters
        add_filter( 'get_avatar',                array( $this, 'get_avatar'               ), 10, 5 );
        add_filter( 'avatar_defaults',           array( $this, 'avatar_defaults'          )        );
    }


    /**
     * Filter the avatar WordPress returns
     *
     * @since 1.0.0
     * @param string $avatar 
     * @param int/string/object $id_or_email
     * @param int $size 
     * @param string $default
     * @param boolean $alt 
     * @return string
     */
    public function get_avatar( $avatar = '', $id_or_email, $size = 200, $default = '', $alt = false ) {

        // Determine if we recive an ID or string
        if ( is_numeric( $id_or_email ) )
            $user_id = (int) $id_or_email;
        elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) )
            $user_id = $user->ID;
        elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) )
            $user_id = (int) $id_or_email->user_id;

        if ( empty( $user_id ) )
            return $avatar;

        $local_avatars = get_user_meta( $user_id, 'user_avatar', true );

        if ( empty( $local_avatars ) || empty( $local_avatars['full'] ) )
            return $avatar;

        $size = (int) $size;

        if ( empty( $alt ) )
            $alt = get_the_author_meta( 'display_name', $user_id );

        // Generate a new size
        if ( empty( $local_avatars[$size] ) ) {

            $upload_path      = wp_upload_dir();
            $avatar_full_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $local_avatars['full'] );
            $image            = wp_get_image_editor( $avatar_full_path );
            $image_sized      = null;

            if ( ! is_wp_error( $image ) ) {
                $image->resize( $size, $size, true );
                $image_sized = $image->save();
            }

            // Deal with original being >= to original image (or lack of sizing ability).
            if ( empty( $image_sized ) || is_wp_error( $image_sized ) ) {
                $local_avatars[ $size ] = $local_avatars['full'];
            } else {
                $local_avatars[ $size ] = str_replace( $upload_path['basedir'], $upload_path['baseurl'], $image_sized['path'] );
            }

            // Save updated avatar sizes
            update_user_meta( $user_id, 'user_avatar', $local_avatars );

        } elseif ( substr( $local_avatars[$size], 0, 4 ) != 'http' ) {
            $local_avatars[$size] = home_url( $local_avatars[$size] );
        }

        if ( is_ssl() ) {
            $local_avatars[ $size ] = str_replace( 'http://', 'https://', $local_avatars[ $size ] );
        }

        $author_class = is_author( $user_id ) ? ' current-author' : '' ;
        $avatar       = "<img alt='" . esc_attr( $alt ) . "' src='" . $local_avatars[$size] . "' class='avatar avatar-{$size}{$author_class} photo' height='{$size}' width='{$size}' />";

        return apply_filters( 'cmp_user_avatar', $avatar );
    }

    /**
     * Form to display on the user profile edit screen
     *
     * @since 1.0.0
     * @param object $profileuser
     * @return
     */
    public function edit_user_profile( $profileuser ) {

        // bbPress will try to auto-add this to user profiles - don't let it.
        // Instead we hook our own proper function that displays cleaner.
        if ( function_exists( 'is_bbpress') && is_bbpress() )
            return;
        ?>

        <h3><?php _e( 'Avatar', 'wpdx' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><label for="cmp-user-avatar"><?php _e( 'Upload Avatar', 'wpdx' ); ?></label></th>
                <td style="width: 50px;" valign="top">
                    <?php echo get_avatar( $profileuser->ID ); ?>
                </td>
                <td>
                <?php
                
                if ( !cmp_get_option( 'user_avatars_caps' ) || current_user_can( 'upload_files' ) ) {
                    // Nonce security ftw
                    wp_nonce_field( 'cmp_user_avatar_nonce', '_cmp_user_avatar_nonce', false );
                    
                    // File upload input
                    echo '<input type="file" name="cmp-user-avatar" id="cmp-local-avatar" /><br />';

                    if ( empty( $profileuser->cmp_user_avatar ) ) {
                        echo '<span class="description">' . __( 'No local avatar is set. Use the upload field to add a local avatar.', 'wpdx' ) . '</span>';
                    } else {
                        echo '<input type="checkbox" name="cmp-user-avatar-erase" value="1" /> ' . __( 'Delete local avatar', 'wpdx' ) . '<br />';
                        echo '<span class="description">' . __( 'Replace the local avatar by uploading a new avatar, or erase the local avatar (falling back to a gravatar) by checking the delete option.', 'wpdx' ) . '</span>';
                    }

                } else {
                    if ( empty( $profileuser->cmp_user_avatar ) ) {
                        echo '<span class="description">' . __( 'No local avatar is set. Set up your avatar at Gravatar.com.', 'wpdx' ) . '</span>';
                    } else {
                        echo '<span class="description">' . __( 'You do not have media management permissions. To change your local avatar, contact the site administrator.', 'wpdx' ) . '</span>';
                    }   
                }
                ?>
                </td>
            </tr>
        </table>
        <script type="text/javascript">var form = document.getElementById('your-profile');form.encoding = 'multipart/form-data';form.setAttribute('enctype', 'multipart/form-data');</script>
        <?php
    }

    /**
     * Update the user's avatar setting
     *
     * @since 1.0.0
     * @param int $user_id
     */
    public function edit_user_profile_update( $user_id ) {

        // Check for nonce otherwise bail
        if ( ! isset( $_POST['_cmp_user_avatar_nonce'] ) || ! wp_verify_nonce( $_POST['_cmp_user_avatar_nonce'], 'cmp_user_avatar_nonce' ) )
            return;

        if ( ! empty( $_FILES['cmp-user-avatar']['name'] ) ) {

            // Allowed file extensions/types
            $mimes = array(
                'jpg|jpeg|jpe' => 'image/jpeg',
                'gif'          => 'image/gif',
                'png'          => 'image/png',
            );

            // Front end support - shortcode, bbPress, etc
            if ( ! function_exists( 'wp_handle_upload' ) ){
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }

            // Delete old images if successful
            $this->avatar_delete( $user_id );

            // Need to be more secure since low privelege users can upload
            if ( strstr( $_FILES['cmp-user-avatar']['name'], '.php' ) )
                wp_die( 'For security reasons, the extension ".php" cannot be in your file name.' );

            // Make user_id known to unique_filename_callback function
            $this->user_id_being_edited = $user_id; 
            $avatar = wp_handle_upload( $_FILES['cmp-user-avatar'], array( 'mimes' => $mimes, 'test_form' => false, 'unique_filename_callback' => array( $this, 'unique_filename_callback' ) ) );

            // Handle failures
            if ( empty( $avatar['file'] ) ) {  
                switch ( $avatar['error'] ) {
                case 'File type does not meet security guidelines. Try another.' :
                    add_action( 'user_profile_update_errors', create_function( '$a', '$a->add("avatar_error",__("Please upload a valid image file for the avatar.","wpdx"));' ) );
                    break;
                default :
                    add_action( 'user_profile_update_errors', create_function( '$a', '$a->add("avatar_error","<strong>".__("There was an error uploading the avatar:","wpdx")."</strong> ' . esc_attr( $avatar['error'] ) . '");' ) );
                }
                return;
            }

            // Save user information (overwriting previous)
            update_user_meta( $user_id, 'user_avatar', array( 'full' => $avatar['url'] ) );

        } elseif ( ! empty( $_POST['cmp-user-avatar-erase'] ) ) {
            // Nuke the current avatar
            $this->avatar_delete( $user_id );
        }
    }

    /**
     * Enable avatar management on the frontend via this shortocde.
     *
     * @since 1.0.0
     */
    function shortcode() {

        // Don't bother if the user isn't logged in
        if ( ! is_user_logged_in() )
            return;

        $user_id     = get_current_user_id();
        $profileuser = get_userdata( $user_id );

        if ( isset( $_POST['manage_avatar_submit'] ) ){
            $this->edit_user_profile_update( $user_id );
        }

        ob_start();
        ?>
        <form id="cmp-user-avatar-form" action="<?php the_permalink(); ?>" method="post" enctype="multipart/form-data">
            <?php
            echo get_avatar( $profileuser->ID );

            if ( !cmp_get_option( 'user_avatars_caps' ) || current_user_can( 'upload_files' ) ) {
                // Nonce security ftw
                wp_nonce_field( 'cmp_user_avatar_nonce', '_cmp_user_avatar_nonce', false );
                
                // File upload input
                echo '<p><input type="file" name="cmp-user-avatar" id="cmp-local-avatar" /></p>';

                if ( empty( $profileuser->cmp_user_avatar ) ) {
                    echo '<p class="description">' . __( 'No local avatar is set. Use the upload field to add a local avatar.', 'wpdx' ) . '</p>';
                } else {
                    echo '<input type="checkbox" name="cmp-user-avatar-erase" value="1" /> ' . __( 'Delete local avatar', 'wpdx' ) . '<br />';
                    echo '<p class="description">' . __( 'Replace the local avatar by uploading a new avatar, or erase the local avatar (falling back to a gravatar) by checking the delete option.', 'wpdx' ) . '</p>';
                }

            } else {
                if ( empty( $profileuser->cmp_user_avatar ) ) {
                    echo '<p class="description">' . __( 'No local avatar is set. Set up your avatar at Gravatar.com.', 'wpdx' ) . '</p>';
                } else {
                    echo '<p class="description">' . __( 'You do not have media management permissions. To change your local avatar, contact the site administrator.', 'wpdx' ) . '</p>';
                }   
            }
            ?>
            <input type="submit" name="manage_avatar_submit" value="<?php _e( 'Update Avatar', 'wpdx' ); ?>" />
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Form to display on the bbPress user profile edit screen
     *
     * @since 1.0.0
     */
    public function bbpress_user_profile() {

        if ( !bbp_is_user_home_edit() )
            return;

        $user_id     = get_current_user_id();
        $profileuser = get_userdata( $user_id );

        echo '<div>';
            echo '<label for="cmp-local-avatar">' . __( 'Avatar', 'wpdx' ) . '</label>';
            echo '<fieldset class="bbp-form avatar">';

                echo get_avatar( $profileuser->ID );
                
                if ( !cmp_get_option( 'user_avatars_caps' ) || current_user_can( 'upload_files' ) ) {
                    // Nonce security ftw
                    wp_nonce_field( 'cmp_user_avatar_nonce', '_cmp_user_avatar_nonce', false );
                    
                    // File upload input
                    echo '<br /><input type="file" name="cmp-user-avatar" id="cmp-local-avatar" /><br />';

                    if ( empty( $profileuser->cmp_user_avatar ) ) {
                        echo '<span class="description" style="margin-left:0;">' . __( 'No local avatar is set. Use the upload field to add a local avatar.', 'wpdx' ) . '</span>';
                    } else {
                        echo '<input type="checkbox" name="cmp-user-avatar-erase" value="1" style="width:auto" /> ' . __( 'Delete local avatar', 'wpdx' ) . '<br />';
                        echo '<span class="description" style="margin-left:0;">' . __( 'Replace the local avatar by uploading a new avatar, or erase the local avatar (falling back to a gravatar) by checking the delete option.', 'wpdx' ) . '</span>';
                    }

                } else {
                    if ( empty( $profileuser->cmp_user_avatar ) ) {
                        echo '<span class="description" style="margin-left:0;">' . __( 'No local avatar is set. Set up your avatar at Gravatar.com.', 'wpdx' ) . '</span>';
                    } else {
                        echo '<span class="description" style="margin-left:0;">' . __( 'You do not have media management permissions. To change your local avatar, contact the site administrator.', 'wpdx' ) . '</span>';
                    }   
                }

            echo '</fieldset>';
        echo '</div>';
        ?>
        <script type="text/javascript">var form = document.getElementById('bbp-your-profile');form.encoding = 'multipart/form-data';form.setAttribute('enctype', 'multipart/form-data');</script>
        <?php
    }

    /**
     * Remove the custom get_avatar hook for the default avatar list output on 
     * the Discussion Settings page.
     *
     * @since 1.0.0
     * @param array $avatar_defaults
     * @return array
     */
    public function avatar_defaults( $avatar_defaults ) {
        remove_action( 'get_avatar', array( $this, 'get_avatar' ) );
        return $avatar_defaults;
    }

    /**
     * Delete avatars based on user_id
     *
     * @since 1.0.0
     * @param int $user_id
     */
    public function avatar_delete( $user_id ) {
        $old_avatars = get_user_meta( $user_id, 'user_avatar', true );
        $upload_path = wp_upload_dir();

        if ( is_array( $old_avatars ) ) {
            foreach ( $old_avatars as $old_avatar ) {
                $old_avatar_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $old_avatar );
                @unlink( $old_avatar_path );
            }
        }

        delete_user_meta( $user_id, 'user_avatar' );
    }

    /**
     * File names are magic
     *
     * @since 1.0.0
     * @param string $dir
     * @param string $name
     * @param string $ext
     * @return string
     */
    public function unique_filename_callback( $dir, $name, $ext ) {
        $user = get_user_by( 'id', (int) $this->user_id_being_edited );
        $name = $base_name = sanitize_file_name( 'user'.$user->ID . '-avatar' );
        $number = 1;

        while ( file_exists( $dir . "/$name$ext" ) ) {
            $name = $base_name . '-' . $number;
            $number++;
        }

        return $name . $ext;
    }
}
$cmp_user_avatars = new cmp_user_avatars;
