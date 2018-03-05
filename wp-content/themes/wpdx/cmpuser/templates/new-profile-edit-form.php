<?php
/**
 * @Author: Changmeng Hu
 * @Date:   2016-07-10 21:39:16
 * @Last Modified time: 2018-02-11 21:25:39
 * @From: https://markwilkinson.me/2014/12/wordpress-front-end-profile-plugin/
 */
require_once(TEMPLATEPATH . '/cmpuser/includes/array_column.php' );
/**
 * function cmpuser_scripts()
 * register the plugins scripts ready for enqueing
 */
function cmpuser_scripts() {
    /* make sure that jquery is enqueued */
    wp_enqueue_script( 'jquery' );
    /* make a filter to allow turning off tab js */
    $tab_js_output = apply_filters( 'cmpuser_js', true );
    /* if we should output styles - enqueue them */
    $pageId = cmp_get_page_id_by_shortcode('cmpuser-edit-profile');
    if( $tab_js_output == true && is_page($pageId) ){
        wp_enqueue_script( 'cmpuser_js', get_template_directory_uri() . '/assets/js/cmpuser.js','jquery', THEME_VER, 1 ,true );
    }
}
add_action( 'wp_enqueue_scripts', 'cmpuser_scripts' );
/**
 * function cmpuser_add_profile_tab_meta_fields()
 * adds the default wordpress profile fields (major ones) to the profile tab
 * @param (array) $fields are the current array of fields added to this filter.
 * @return (array) $fields are the modified array of fields to pass back to the filter
 */
function cmpuser_add_profile_tab_meta_fields( $fields ) {
    
    $fields[] = array(
        'id' => 'user_email', 
        'label' => __('Email Address','wpdx'),
        'desc' => __('Edit your email address - used for resetting your password etc.','wpdx'),
        'type' => 'email', 
        'classes' => 'user_email',
    );

    $fields[] = array(
        'id' => 'nickname', 
        'label' => __('Nickname','wpdx'),
        'type' => 'text', 
        'classes' => 'nickname',
    );

    $fields[] = array(
        'id' => 'user_url', 
        'label' => __('Website URL','wpdx'),
        'type' => 'text', 
        'classes' => 'user_url',
    );
        
    $fields[] = array(
        'id' => 'description',
        'label' => __('Description/Bio','wpdx'),
        'type' => 'textarea',
        'classes' => 'description',
    );

    $fields[] = array(
        'id' => 'qq', 
        'label' => __('QQ','wpdx'),
        'type' => 'text', 
        'classes' => 'qq',
    );

    $fields[] = array(
        'id' => 'sina_weibo', 
        'label' => __('Sina Weibo','wpdx'),
        'desc' => __('Please type your Sina Weibo, including http://','wpdx'),
        'type' => 'text', 
        'classes' => 'sina_weibo',
    );

    $fields[] = array(
        'id' => 'qq_weibo', 
        'label' => __('Tencent Weibo','wpdx'),
        'desc' => __('Please type your Tencent Weibo, including http://','wpdx'),
        'type' => 'text', 
        'classes' => 'qq_weibo',
    );

    $fields[] = array(
        'id' => 'twitter', 
        'label' => __('Twitter','wpdx'),
        'desc' => __('Please type your Twitter, including https://','wpdx'),
        'type' => 'text', 
        'classes' => 'twitter',
    );

    $fields[] = array(
        'id' => 'google_plus', 
        'label' => __('Google+','wpdx'),
        'desc' => __('Please type your Google+, including https://','wpdx'),
        'type' => 'text', 
        'classes' => 'google_plus',
    );

    return $fields;
    
}
add_filter( 'cmpuser_fields_profile', 'cmpuser_add_profile_tab_meta_fields', 10 );
/**
 * cmpuser_add_password_tab_fields()
 * adds the password update fields to the passwords tab
 * @param (array) $fields are the current array of fields added to this filter.
 * @return (array) $fields are the modified array of fields to pass back to the filter
 */
function cmpuser_add_password_tab_fields( $fields ) {
    if(cmp_get_option('password_complexity')){
        $desc = __( 'Password must contain at least 1 uppercase, 1 lowercase letter and at least 1 number. Passwords should not contain the user\'s username, email, or first/last name.', 'wpdx' );
    }else{
        $desc = __( 'It is strongly recommended to set a complex password, including upper/lowercase letter, special/symbol character and alphanumeric characters.  Passwords should not contain the user\'s username, email, or first/last name.', 'wpdx' );
    }

    if( isset($_GET['reset']) && $_GET['reset'] = 'password' ) {

        $fields[] = array(
            'id' => 'resetpass_note',
            'label' => __('Please enter your new password below:','wpdx'),
            'type' => 'note',
            'classes' => 'resetpass_note',
        );

    }
    
    $fields[] = array(
        'id' => 'user_pass',
        'label' => __('Password','wpdx'),
        'desc' => $desc,
        'type' => 'password',
        'classes' => 'user_pass',
    );
    
    return $fields;
}
add_filter( 'cmpuser_fields_password', 'cmpuser_add_password_tab_fields', 10 );
/**
 * function cmpuser_add_profile_tab
 * adds the profile tab to the profile output
 * @param (array) current array of tabs in the filter
 * @return (array) the newly modified array of tabs
 */
function cmpuser_add_profile_tab( $tabs ) {
    
    /* add our tab to the tabs array */
    $tabs[] = array(
        'id' => 'profile', // used for the callback function, if declared or exists and the tab content wrapper id
        'label' => __('Profile','wpdx'),
        'tab_class' => 'profile-tab',
        'content_class' => 'profile-content',
        /**
         * (callback) this is used to display the tab output.
         * if not declared or the function declared does not exist the default cmpuser_default_tab_content() function is used instead.
         */
        'callback' => 'cmpuser_profile_tab_content'
    );
    
    /* return all the tabs */
    return $tabs;
    
}
add_filter( 'cmpuser_tabs', 'cmpuser_add_profile_tab', 10 );
/**
 * function cmpuser_add_password_tab
 * adds the password tab to the profile output
 * @param (array) current array of tabs in the filter
 * @return (array) the newly modified array of tabs
 */
function cmpuser_add_password_tab( $tabs ) {
    
    /* add our tab to the tabs array */
    $tabs[] = array(
        'id' => 'password',
        'label' => __('Password','wpdx'),
        'tab_class' => 'password-tab',
        'content_class' => 'password-content',
    );
    
    /* return all the tabs */
    return $tabs;
    
}
add_filter( 'cmpuser_tabs', 'cmpuser_add_password_tab', 20 );
/**
 * function cmpuser_tab_list_item()
 * generates the list item for a tab heading (the actual tab!)
 * @param (array) $tab the tab array
 */
function cmpuser_tab_list_item( $tab ) {
    
    /* build the tab class */
    $tab_class = 'tab';
    
    /* if we have a tab class to add */
    if( $tab[ 'tab_class' ] != '' ) {
        
        /* add the tab class to our variable */
        $tab_class .= ' ' . $tab[ 'tab_class' ];
        
    }
    ?>
    <li class="<?php echo esc_attr( $tab_class ); ?>">
        <a href="#<?php echo esc_attr( $tab[ 'id' ] ); ?>"><?php echo esc_html( $tab[ 'label' ] ); ?></a>
    </li>
    <?php
    
}
/**
 * function cmpuser_default_tab_content()
 * outputs the fields for a tab inside a tab
 * this function is only used if a specific callback is not declared when filtering cmpuser_tabs
 * @param (array) $tab is the array of tab args
 */
function cmpuser_default_tab_content( $tab ) {
    /**
     * @hook cmpuser_before_tab_fields
     * fires before the fields of the tab are outputted
     * @param (array) $tab the array of tab args.
     * @param (int) $current_user_id the user if of the current user to add things targetted to a specific user only.
     */
    do_action( 'cmpuser_before_tab_fields', $tab, get_current_user_id() );
    
    /**
     * build an array of fields to output
     * @hook - cmpuser_profile_fields
     * each field should added with as an arrray with the following elements
     * id - used for the input name and id attributes - should also be the user meta key
     * label - used for the inputs label
     * desc - the description to go with the input
     * type - the type of input to render - valid are email, text, select, checkbox, textarea, wysiwyg
     * @param (integer) current user id - this can be used to add fields to certain users only
    */
    $fields = apply_filters(
        'cmpuser_fields_' . $tab[ 'id' ],
        array(),
        get_current_user_ID()
    );
    
    /* check we have some fields */
    if( ! empty( $fields ) ) {
        
        /* output a wrapper div and form opener */
        ?>
        
            <div class="cmpuser-fields">
                
                <?php
                    
                    /* start a counter */
                    $counter = 1;
                    
                    /* get the total number of fields in the array */
                    $total_fields = count( $fields );
            
                    /* lets loop through our fields array */
                    foreach( $fields as $field ) {
                        
                        /* set a base counting class */
                        $count_class = ' cmpuser-' . $field[ 'type' ] . '-field cmpuser-field-' . $counter;
                        
                        /* build our counter class - check if the counter is 1 */
                        if( $counter == 1 ) {
                            
                            /* this is the first field element */
                            $counting_class = $count_class . ' field-first';
                        
                        /* is the counter equal to the total number of fields */
                        } elseif( $counter == $total_fields ) {
                            
                            /* this is the last field element */
                            $counting_class = $count_class . ' field-last';
                        
                        /* if not first or last */
                        } else {
                            
                            /* set to base count class only */
                            $counting_class = $count_class;
                        }
                        
                        /* build a var for classes to add to the wrapper */
                        $classes = ( empty( $field[ 'classes' ] ) ) ? '' : ' ' . $field[ 'classes' ];
                        
                        /* build ful classe array */
                        $classes = $counting_class  . $classes;
                        
                        /* output the field */
                        cmpuser_field( $field, $classes, $tab[ 'id' ], get_current_user_id() );
                            
                        /* increment the counter */
                        $counter++;
                    
                    } // end for each field
                    
                    /* output a closing wrapper div */
                ?>
            
            </div>
        
        <?php
    
    } // end if have fields
    
    /**
     * @hook cmpuser_after_tab_fields
     * fires after the fields of the tab are outputted
     * @param (array) $tab the array of tab args.
     * @param (int) $current_user_id the user if of the current user to add things targetted to a specific user only.
     */
    do_action( 'cmpuser_after_tab_fields', $tab, get_current_user_id() );
    
}
/**
 * function cmpuser_field()
 * outputs the an input field
 * @param (array) $field the array of field data including id, label, desc and type
 * @return markup for the field input depending on type set in $field
 */
function cmpuser_field( $field, $classes, $tab_id, $user_id ) {
        
    ?>
    
    <div class="cmpuser-field<?php echo esc_attr( $classes ); ?>" id="cmpuser-field-<?php echo esc_attr( $field[ 'id' ] ); ?>">
                
        <?php
            
            /* get the reserved meta ids */
            $reserved_ids = apply_filters(
                'cmpuser_reserved_ids',
                array(
                    'user_email',
                    'user_url',
                    'qq',
                    'sina_weibo',
                    'qq_weibo',
                    'google_plus',
                    'twitter'
                )
            );
            
            /* if the current field id is in the reserved list */
            if( in_array( $field[ 'id' ], $reserved_ids ) ) {
                
                $userdata = get_userdata( $user_id );
                $field_id = $field[ 'id' ];
                $current_field_value = $userdata->$field_id;
            
            /* not a reserved id - treat normally */
            } else {
                
                /* get the current value */
                $current_field_value = get_user_meta( get_current_user_id(), $field[ 'id' ], true );
                
            }
            
            /* output the input label */
                ?>
                <label for="<?php echo esc_attr( $tab_id ); ?>[<?php echo esc_attr( $field[ 'id' ] ); ?>]"><?php echo esc_html( $field[ 'label' ] ); ?></label>
                <?php
                                
            /* being a switch statement to alter the output depending on type */
            switch( $field[ 'type' ] ) {
                
                /* if this is a wysiwyg setting */
                case 'wysiwyg':
                        
                    $editor_id = $field[ 'id' ];
                    /* set some settings args for the editor */
                    $editor_settings = array(
                        'textarea_rows' => apply_filters( 'cmpuser_wysiwyg_textarea_rows', '5', $field[ 'id' ] ),
                        'media_buttons' => apply_filters( 'cmpuser_wysiwyg_media_buttons', false, $field[ 'id' ] ),
                        'textarea_name' => $tab_id . '[' .$editor_id. ']',
                    );
                                                
                    /* display the wysiwyg editor */
                    wp_editor(
                        $current_field_value, // default content
                        $editor_id, // id to give the editor element
                        $editor_settings // edit settings from above
                    );
                
                    break;
                
                /* if this should be rendered as a select input */
                case 'select':
                                            
                    ?>
                    <select name="<?php echo esc_attr( $tab_id ); ?>[<?php echo $field[ 'id' ]; ?>]" id="<?php echo $field[ 'id' ]; ?>">
                    
                    <?php
                    /* get the setting options */
                    $options = $field[ 'options' ];
                    
                    /* loop through each option */
                    foreach( $options as $option ) {
                        ?>
                        <option value="<?php echo esc_attr( $option[ 'value' ] ); ?>" <?php selected( $current_field_value, $option[ 'value' ] ); ?>><?php echo esc_html( $option[ 'name' ] ); ?></option>
                        <?php
                    }
                    ?>
                    </select>
                    <?php
                    
                    break;
                
                /* if the type is set to a textarea input */  
                case 'textarea':
                    
                    ?>
                    
                    <textarea name="<?php echo esc_attr( $tab_id ); ?>[<?php echo $field[ 'id' ]; ?>]" rows="<?php echo apply_filters( 'cmpuser_textarea_rows', '5', $field[ 'id' ] ); ?>" cols="50" id="<?php echo esc_attr( $field[ 'id' ] ); ?>" class="regular-text"><?php echo esc_textarea( $current_field_value ); ?></textarea>
                    
                    <?php
                        
                    /* break out of the switch statement */
                    break;
                
                /* if the type is set to a textarea input */  
                case 'checkbox':
                
                    ?>
                    <input type="checkbox" name="<?php echo esc_attr( $tab_id ); ?>[<?php echo $field[ 'id' ]; ?>]" id="<?php echo esc_attr( $field[ 'id' ] ); ?>" value="1" <?php checked( $current_field_value, '1' ); ?> />
                    <?php
                    
                    /* break out of the switch statement */
                    break;
                   
                /* if the type is set to a textarea input */  
                case 'email':
                
                    ?>
                    <input type="email" name="<?php echo esc_attr( $tab_id ); ?>[<?php echo $field[ 'id' ]; ?>]" id="<?php echo esc_attr( $field[ 'id' ] ); ?>" class="regular-text" value="<?php echo esc_attr( $current_field_value ); ?>" />
                    <?php
                    
                    /* break out of the switch statement */
                    break;
                   
                /* if the type is set to a textarea input */  
                case 'password':
                
                    ?>
                    <input type="password" name="<?php echo esc_attr( $tab_id ); ?>[<?php echo $field[ 'id' ]; ?>]" id="<?php echo esc_attr( $field[ 'id' ] ); ?>" class="regular-text" value="" placeholder="<?php _e('New Password','wpdx') ?>" />
                    
                    <input type="password" name="<?php echo esc_attr( $tab_id ); ?>[<?php echo $field[ 'id' ]; ?>_check]" id="<?php echo esc_attr( $field[ 'id' ] ); ?>_check" class="regular-text" value="" placeholder="<?php _e('Repeat New Password','wpdx') ?>" />
                    <?php
                    
                    /* break out of the switch statement */
                    break;

                /* if the type is set to a textarea input */  
                case 'note':
                    
                    /* break out of the switch statement */
                    break;
                
                /* any other type of input - treat as text input */ 
                default:
                
                    ?>
                    <input type="text" name="<?php echo esc_attr( $tab_id ); ?>[<?php echo $field[ 'id' ]; ?>]" id="<?php echo esc_attr( $field[ 'id' ] ); ?>" class="regular-text" value="<?php echo esc_attr( $current_field_value ); ?>" />
                    <?php   
                
            }
            
            /* if we have a description lets output it */
            if( isset($field[ 'desc' ]) ) {
                
                ?>
                <p class="description"><?php echo esc_html( $field[ 'desc' ] ); ?></p>
                <?php
                
            } // end if have description
        
        ?>
        
    </div>
    
    <?php
    
}
/**
 * function cmpuser_tab_content_save
 */
function cmpuser_tab_content_save( $tab, $user_id ) {
    
    ?>
    
    <div class="cmpuser-save">
        <input type="submit" class="cmpuser_save" name="<?php echo esc_attr( $tab[ 'id' ] ); ?>[cmpuser_save]" value="<?php _e('Update ','wpdx'); echo esc_attr( $tab[ 'label' ] ); ?>" />
    </div>
    
    <?php
    
}
add_action( 'cmpuser_after_tab_fields', 'cmpuser_tab_content_save', 10, 2 );
/**
 * function cmpuser_save_fields()
 * saves the fields from a tab (except password tab) to user meta
 * @param (array) $tabs is an array of all of the current tabs
 * @param (int) $user_id is the current logged in users id
 */
function cmpuser_save_fields( $tabs, $user_id ) {
    
    /* check the nonce */
    if( ! isset( $_POST[ 'cmpuser_nonce_name' ] ) || ! wp_verify_nonce( $_POST[ 'cmpuser_nonce_name' ], 'cmpuser_nonce_action' ) )
        return;
    
    /* set an array to store messages in */
    $messages = array();
    
    /* get the POST data */
    $tabs_data = $_POST;
    
    /**
     * remove the following array elements from the data
     * password
     * nonce name
     * wp refere - sent with nonce
     */
    unset( $tabs_data[ 'password' ] );
    unset( $tabs_data[ 'cmpuser_nonce_name' ] );
    unset( $tabs_data[ '_wp_http_referer' ] );
    
    /* lets check we have some data to save */
    if( empty( $tabs_data ) )
        return;
        
    /**
     * setup an array of reserved meta keys
     * to process in a different way
     * they are not meta data in wordpress
     * reserved names are user_url and user_email as they are stored in the users table not user meta
     */
    $reserved_ids = apply_filters(
        'cmpuser_reserved_ids',
        array(
            'user_email',
            'user_url',
        )
    );
    /**
     * set an array of registered fields
     */
    $registered_fields = array();
    foreach( $tabs as $tab ) {
        $tab_fields = apply_filters(
            'cmpuser_fields_' . $tab[ 'id' ],
            array(),
            $user_id
        );
        $registered_fields = array_merge( $registered_fields, $tab_fields );
    }

    /* set an array of registered keys */
    $registered_keys = wp_list_pluck( $registered_fields, 'id' );
    /* loop through the data array - each element of this will be a tabs data */
    foreach( $tabs_data as $tab_data ) {
        
        /**
         * loop through this tabs array
         * the ket here is the meta key to save to
         * the value is the value we want to actually save
         */
        foreach( $tab_data as $key => $value ) {
            
            /* if the key is the save sumbit - move to next in array */
            if( $key == 'cmpuser_save' || $key == 'cmpuser_nonce_action' )
                continue;

            /* if the key is not in our list of registered keys - move to next in array */
            if ( ! in_array( $key, $registered_keys ) )
                continue;
            
            /* check whether the key is reserved - handled with wp_update_user */
            if( in_array( $key, $reserved_ids ) ) {
                
                $user_id = wp_update_user(
                    array(
                        'ID' => $user_id,
                        $key => $value
                    )
                );
                
                /* check for errors */
                if ( is_wp_error( $user_id ) ) {
                    
                    /* update failed */
                    $messages[ 'update_failed' ] = __('<p class="error">There was a problem with updating your profile.</p>','wpdx');
                
                }
            
            /* just standard user meta - handle with update_user_meta */
            } else {

                /* lookup field options by key */
                $registered_field_key = array_search( $key, array_column( $registered_fields, 'id' ) );

                /* sanitize user input based on field type */
                switch ( $registered_fields[$registered_field_key]['type'] ) {
                    case 'wysiwyg':
                        $value = wp_filter_post_kses( $value );
                        break;
                    case 'select':
                        $value = sanitize_text_field( $value );
                        break;
                    case 'textarea':
                        $value = wp_filter_nohtml_kses( $value );
                        break;
                    case 'checkbox':
                        $value = isset( $value ) && '1' === $value ? true : false;
                        break;
                    case 'email':
                        $value = sanitize_email( $value );
                        break;
                    default:
                        $value = sanitize_text_field( $value );
                }
                
                /* update the user meta data */
                $meta = update_user_meta( $user_id, $key, $value );
                
                /* check the update was succesfull */
                if( $meta == false ) {
                    
                    /* update failed */
                    $messages[ 'update_failed' ] = __('<p class="error">There was a problem with updating your profile.</p>','wpdx');
                    
                }
                
            }
            
        } // end tab loop
        
    } // end data loop
    
    /* check if we have an messages to output */
    if( empty( $messages ) ) {
        
        ?>
        <div class="messages">
        <?php
        
        /* lets loop through the messages stored */
        foreach( $messages as $message ) {
            
            /* output the message */
            echo $message;
            
        }
        
        ?>
        </div><!-- // messages -->
        <?php
        
    } else {
        
        ?>
        <div class="messages"><p class="updated"><?php _e('Your profile was updated successfully!','wpdx') ?></p></div>
        <?php
        
    }
    
}
add_action( 'cmpuser_before_tabs', 'cmpuser_save_fields', 5, 2 );
/**
 * function cmpuser_save_password()
 * saves the change of password on the profile password tab
 * check for length (filterable with cmpuser_password_length) and complexity (upper/lower/numbers)
 * user is logged out on success with a message to login back in with new password.
 *
 * @param (array) $tabs is an array of all of the current tabs
 * @param (int) $user_id is the current logged in users id
 */
function cmpuser_save_password( $tabs, $user_id ) {
    
    /* set an array to store messages in */
    $messages = array();
    
    /* get the posted data from the password tab */
    if(isset($_POST[ 'password' ])){
        $data = $_POST[ 'password' ];
    }else{
        return;
    }
    
    
    /* store both password for ease of access */
    $password = $data[ 'user_pass' ];
    $password_check = $data[ 'user_pass_check' ];
    
    /* first lets check we have a password added to save */
    if( empty( $password ) )
        return;
    
    /* now lets check the password match */
    if( $password != $password_check ) {
        
        /* add message indicating no match */
        $messages[ 'password_mismatch' ] = __('<p class="error">Please make sure the passwords match.</p>','wpdx');
        
    }
    
    /* get the length of the password entered */
    $pass_length = strlen( $password );
    $enable_passcomplex = cmp_get_option( 'password_complexity' );
    
    /* check the password match the correct length */
    if( $enable_passcomplex && $pass_length < apply_filters( 'cmpuser_password_length', 8 ) ) {
        
        /* add message indicating length issue!! */
        $messages[ 'password_length' ] = __('<p class="error">Please make sure your password is a minimum of ','wpdx') . apply_filters( 'cmpuser_password_length', 8 ) . __(' characters long.</p>','wpdx');
        
    }
    
    /**
     * match the password against a regex of complexity
     * at least 1 upper, 1 lower case letter and 1 number
     */
    $pass_complexity = preg_match( apply_filters( 'cmpuser_password_regex', '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d,.;:]).+$/' ), $password );
    
    /* check whether the password passed the regex check of complexity */
    if( $enable_passcomplex && $pass_complexity == false ) {
        
        /* add message indicating complexity issue */
        $messages[ 'password_complexity' ] = __('<p class="error">Your password must contain at least 1 uppercase, 1 lowercase letter and at least 1 number</p>','wpdx');
        
    }
    
    /* check we have any messages in the messages array - if we have password failed at some point */
    if( empty( $messages ) ) {
        
        /**
         * ok if we get this far we have passed all the checks above
         * the password can now be updated and redirect the user to the login page
         */
        if(cmp_get_option('login_url')){
            $login_url = htmlspecialchars_decode(cmp_get_option('login_url')) ;
        }else{
            $login_url = wp_login_url();
        }
        wp_set_password( $password, $user_id );
        echo sprintf( __('<div class="messages"><p class="updated">You\'re password was successfully changed and you have been logged out. Please <a href="%s">login again here</a>.</p></div>', 'wpdx'), esc_url( $login_url ) );
    
    /* messages not empty therefore password failed */
    } else {
        
        ?>
        <div class="messages">
        <?php
        
        /* lets loop through the messages stored */
        foreach( $messages as $message ) {
            
            /* output the message */
            echo $message;
            
        }
        
        ?>
        </div><!-- // messages -->
        <?php
        
    }
    
}
add_action( 'cmpuser_before_tabs', 'cmpuser_save_password', 10, 2 );
/**
 * function wp_frontend_profile_output()
 *
 * provides the front end output for the front end profile editing
 */
add_shortcode( 'cmpuser-edit-profile', 'cmpuser_show_profile' );
function cmpuser_show_profile() {
    
    /* first things first - if no are not logged in move on! */
    if( ! is_user_logged_in() )
        return;
    
    /* if you're an admin - too risky to allow front end editing */
    // if( current_user_can( 'manage_options' ) )
    //  return;
    ob_start();
    ?>
    
    <div class="cmpuser-wrapper">
        
        <?php
            
            /* get the tabs that have been added - see below */
            $cmpuser_tabs = apply_filters(
                'cmpuser_tabs',
                array() 
            );
            
            /**
             * @hook cmpuser_before_tabs
             * fires before the tabs list items are outputted
             * @param (array) $tabs is all the tabs that have been added
             * @param (int) $current_user_id the user if of the current user to add things targetted to a specific user only.
             */
            do_action( 'cmpuser_before_tabs', $cmpuser_tabs, get_current_user_id() );   
            
        ?>
        
        <ul class="cmpuser-tabs" id="cmpuser-tabs">
            
            <?php
                
                /**
                * set an array of tab titles and ids
                * the id set here should match the id given to the content wrapper
                * which has the class tab-content included in the callback function
                * @hooked cmpuser_add_profile_tab - 10
                * @hooked cmpuser_add_password_tab - 20
                */
                $cmpuser_tabs = apply_filters(
                    'cmpuser_tabs',
                    array()
                );
                
                /* check we have items to show */
                if( ! empty( $cmpuser_tabs ) ) {
                    /* loop through each item */
                    foreach( $cmpuser_tabs as $cmpuser_tab ) {
                        
                        /* output the tab name as a tab */
                        cmpuser_tab_list_item( $cmpuser_tab );
                    }
                }
                
            ?>  
            
        </ul><!-- // cmpuser-tabs -->
        
        <?php
                                    
            /* loop through each item */
            foreach( $cmpuser_tabs as $cmpuser_tab ) {
                
                /* build the content class */
                $content_class = '';
                
                /* if we have a class provided */
                if( $cmpuser_tab[ 'content_class' ] != '' ) {
                    
                    /* add the content class to our variable */
                    $content_class .= ' ' . $cmpuser_tab[ 'content_class' ];
                    
                }
                
                /**
                 * @hook cmpuser_before_tab_content
                 * fires before the contents of the tab are outputted
                 * @param (string) $tab_id the id of the tab being displayed. This can be used to target a particular tab.
                 * @param (int) $current_user_id the user if of the current user to add things targetted to a specific user only.
                 */
                do_action( 'cmpuser_before_tab_content', $cmpuser_tab[ 'id' ], get_current_user_id() );
                
                ?>
                
                <div class="tab-content<?php echo esc_attr( $content_class ); ?>" id="<?php echo esc_attr( $cmpuser_tab[ 'id' ] ); ?>">

                <?php if($cmpuser_tab[ 'id' ] == 'avatar'): ?>
                    <form id="cmp-user-avatar-form" action="#" method="post" enctype="multipart/form-data" class="cmpuser-form-<?php echo esc_attr( $cmpuser_tab[ 'id' ] ); ?>">
                <?php else: ?>
                    <form method="post" action="#" class="cmpuser-form-<?php echo esc_attr( $cmpuser_tab[ 'id' ] ); ?>">
                <?php endif; ?>
                        <?php
                            
                            /* check if callback function exists */
                            if( function_exists( @$cmpuser_tab[ 'callback' ] ) ) {
                                
                                /* use custom callback function */
                                $cmpuser_tab[ 'callback' ]( $cmpuser_tab );
                            
                            /* custom callback does not exist */
                            } else {
                                
                                /* use default callback function */
                                cmpuser_default_tab_content( $cmpuser_tab );
                                
                            }
                        
                        ?>
                        <?php
                            wp_nonce_field(
                                'cmpuser_nonce_action',
                                'cmpuser_nonce_name'
                            );
                        
                        ?>
                    </form>
                </div>
                <?php
  
                /**
                 * @hook cmpuser_after_tab_content
                 * fires after the contents of the tab are outputted
                 * @param (string) $tab_id the id of the tab being displayed. This can be used to target a particular tab.
                 * @param (int) $current_user_id the user if of the current user to add things targetted to a specific user only.
                 */
                do_action( 'cmpuser_after_tab_content', $cmpuser_tab[ 'id' ], get_current_user_id() );      
            } // end tabs loop
        ?>
    </div><!-- // cmpuser-wrapper -->
    <?php
    return ob_get_clean();
}

//require_once(TEMPLATEPATH . '/cmpuser/includes/user-avatars.php' );

function cmp_add_avatar_tab( $tabs ) {
    /* add our tab to the tabs array */
    $tabs[] = array(
        'id' => 'avatar',
        'label' => __('Avatar','wpdx'),
        'tab_class' => 'avatar-tab',
        'content_class' => 'avatar-content',
        'callback' => 'cmpuser_avatar_tab_content'
        );
    /* return all the tabs */
    return $tabs;
}
add_filter( 'cmpuser_tabs', 'cmp_add_avatar_tab', 30 );

function cmpuser_avatar_tab_content(){

    $cmp_user_avatars = new cmp_user_avatars();
    $user_id     = get_current_user_id();
    $profileuser = get_userdata( $user_id );

    if ( isset( $_POST['manage_avatar_submit'] ) ){
        $cmp_user_avatars->edit_user_profile_update( $user_id );
    }

    //ob_start();
    ?>
    <form id="cmp-user-avatar-form" action="<?php the_permalink(); ?>" method="post" enctype="multipart/form-data">
        <?php
        do_action( 'user_profile_update_errors');
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
        <div class="cmpuser-save">
            <input type="submit" class="cmpuser-save" name="manage_avatar_submit" value="<?php _e( 'Update Avatar', 'wpdx' ); ?>" />
        </div>
    </form>
    <?php
    //return ob_get_clean();
}

/**
 * function cmpuser_save_avatar()
 * saves the change of avatar on the profile avatar tab
 * @param (array) $tabs is an array of all of the current tabs
 * @param (int) $user_id is the current logged in users id
 */
function cmpuser_save_avatar( $tabs, $user_id ) {

    if ( isset( $_POST['manage_avatar_submit'] ) ){
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="1">' ;
        echo '<div class="messages"><p class="updated">'.__('Your avatar was updated successfully!','wpdx').'</p></div>';
    }
}
add_action( 'cmpuser_before_tabs', 'cmpuser_save_avatar', 10, 2 );

