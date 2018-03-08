<div id="top-bar" class="navbar navbar-inverse">
  <!-- <div id="logo">
    <hgroup>
      <?php if (is_home()) { ?>
      <h1 class="logoimg"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ) ?></a></h1>
      <?php  } else { ?>
      <div class="logoimg"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ) ?></a></div>
      <?php } ?>
    </hgroup>
  </div> -->
  <ul class="nav user-nav">
    <?php
    $protocol = is_ssl() ? 'https://' : 'http://';
    $redirect_to = $protocol.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
    if(function_exists('cmpuser_url_cleaner')) $redirect_to = cmpuser_url_cleaner($redirect_to);
    // $login_url = cmp_get_option('login_url') ? cmp_get_option('login_url') : wp_login_url($redirect_to);
    if(cmp_get_option('login_url') && !defined('CMP_REMOVE_LOGIN_URL')){
      $login_url = cmp_get_option('login_url');
    }else{
      $login_url = wp_login_url($redirect_to);
    }
    $password_url = cmp_get_option('password_url') ? cmp_get_option('password_url') : wp_lostpassword_url();
    $register_url = cmp_get_option('register_url') ? cmp_get_option('register_url') :wp_registration_url();

    if( cmp_get_option('show_login')):
      if(is_user_logged_in()){
        ?>
        <li class="dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="fa fa-fw fa-user"></i> <span class="text"><?php _e('Manage','wpdx'); ?></span><b class="caret"></b></a>
          <ul class="dropdown-menu user-dashboard">
            <?php
            if(function_exists('wp_nav_menu') && has_nav_menu('user-menu')){
              wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'user-menu', 'fallback_cb' => 'cmp_nav_fallback','walker' => new wp_bootstrap_navwalker()));
            }else{
              ?>
              <li><a href="<?php echo get_home_url(); ?>/wp-admin/index.php" rel="nofollow"><i class="fa fa-tachometer"></i><?php _e('Dashboard','wpdx') ?></a></li>
              <li><a href="<?php echo get_home_url(); ?>/wp-admin/post-new.php" rel="nofollow"><i class="fa fa-pencil-square-o"></i><?php _e('New Post','wpdx') ?></a></li>
              <li><a href="<?php echo get_home_url(); ?>/wp-admin/post-new.php?post_type=page" rel="nofollow"><i class="fa fa-file-text"></i><?php _e('New Page','wpdx') ?></a></li>
              <li><a href="<?php echo get_home_url(); ?>/wp-admin/edit-comments.php" rel="nofollow"><i class="fa fa-comments-o"></i><?php _e('Edit Comments','wpdx') ?></a></li>
              <li><a href="<?php echo get_home_url(); ?>/wp-admin/profile.php" rel="nofollow"><i class="fa fa-cog"></i><?php _e('Edit Profile','wpdx') ?></a></li>
              <?php } ?>
            </ul>
          </li>
          <?php
            if(class_exists('Fep_Message') && is_user_logged_in()){
              $new_message = fep_get_new_message_number();
              $new_announcement = fep_get_new_announcement_number();
              $pm_id = cmp_get_page_id_by_shortcode('front-end-pm');
              if($new_message || $new_announcement) echo '<li class="fep"><div class="fep-notice">';
              if($new_message && $new_message > 0){
                $message = sprintf( __( 'You have %s unread message.', 'wpdx' ), $new_message );
                echo '<a href="'.get_permalink($pm_id).'?fepaction=messagebox" title="'.$message.'"><i class="fa fa-envelope-o"></i></a>';
              }
              if($new_announcement && $new_announcement > 0){
                $announcement = sprintf( __( 'You have %s unread announcement.', 'wpdx' ), $new_announcement );
                echo '<a href="'.get_permalink($pm_id).'?fepaction=announcements" title="'.$announcement.'"><i class="fa fa-bell-o"></i></a>';
              }
              if($new_message || $new_announcement) echo '</div></li>';
            }

            $protocol = is_ssl() ? 'https://' : 'http://';
            $redirect_to = $protocol.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];

            ?>
          <li class="user-btn"><a href="<?php echo wp_logout_url($redirect_to);  ?>" title="<?php _e('Logout','wpdx'); ?>" rel="nofollow"><i class="fa fa-sign-out fa-fw"></i><span class="text"><?php _e('Logout','wpdx'); ?></span></a></li>
          <?php
        } else {
          ?>
          <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle popup-login"><i class="fa fa-sign-in fa-fw"></i><span class="text"><?php _e('Login','wpdx'); ?></span><b class="caret"></b></a>
            <ul class="dropdown-menu">
            <?php do_action( 'popup_login_form_top' ); ?>
              <form class="user-login" name="loginform" action="<?php echo $login_url; ?>" method="post">
                <li><i class="fa fa-user fa-fw"></i><input class="ipt" placeholder="<?php _e('Username','wpdx') ?>" type="text" name="log" value="" size="18"></li>
                <li><i class="fa fa-lock fa-fw"></i><input class="ipt" placeholder="<?php _e('Password','wpdx') ?>" type="password" name="pwd" value="" size="18"></li>
                <li><input name="rememberme" id="rememberme" type="checkbox" checked="checked" value="forever" /> <?php _e( 'Remember Me' , 'wpdx'); ?></li>
                <li class="btn"><input class="login-btn" type="submit" name="submit" value="<?php _e('Login','wpdx'); ?>"></li>
                <li><a class="pw-reset" rel="nofollow" href="<?php echo $password_url; ?>"><i class="fa fa-lightbulb-o fa-fw"></i> <?php _e('Lost password ?','wpdx'); ?></a></li>
                <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
                <input type="hidden" name="wpuf_login" value="true" />
                <input type="hidden" name="action" value="login" />
                <?php wp_nonce_field( 'wpuf_login_action' ); ?>
              </form>
              <?php do_action( 'popup_login_form_bottom' ); ?>
              <?php if(function_exists('open_social_login_form')) open_social_login_form(); ?>
            </ul>
          </li>
          <?php
          if(get_option('users_can_register') == '1' ) :?>
          <li class="user-btn user-reg"><a class="popup-register" href="<?php echo $register_url; ?>" title="<?php _e('Register','wpdx'); ?>" rel="nofollow"><i class="fa fa-key fa-fw"></i><span class="text"><?php _e('Register','wpdx'); ?></span></a></li>
        <?php endif; ?>
        <?php }
        endif; ?>
        <?php
        if( cmp_get_option('show_qqqun')){
          echo '<li id="qqqun" class="other-nav"><a target="_blank" title="'.cmp_get_option('qqqun_title').'" href="'.cmp_get_option('qqqun_url').'" rel="nofollow"><i class="fa fa-group fa-fw"></i> '.__('Join QQ group','wpdx').'</a></li>';
        }
        if( cmp_get_option('show_qq')){
          echo '<li id="qq" class="other-nav">'.htmlspecialchars_decode( cmp_get_option('qq_code') ).'</li>';
        }
        if( cmp_get_option('show_wx') && cmp_get_option('weixin_img')){
          echo '<li class="wx other-nav"><a href="#" rel="nofollow"><i class="fa fa-qrcode fa-fw"></i> '.__('Weixin','wpdx').'<span class="weixin"><img src="'.cmp_get_option('weixin_img').'" alt="'.__('Weixin','wpdx').'"></span></a></li>';
        }
        if( cmp_get_option('show_weibo')){
          echo '<li id="swb" class="other-nav"><wb:follow-button uid="'.cmp_get_option('weibo_uid').'" type="'.cmp_get_option('weibo_type').'" height="24"></wb:follow-button></li>';
        }
        if( cmp_get_option('show_qqweibo')){
          echo'<li id="qwb" class="other-nav"><iframe src="http://follow.v.t.qq.com/index.php?c=follow&a=quick&name='.cmp_get_option('qqweibo_name').'&style=5&t='.cmp_get_option('qqweibo_t').'&f='.cmp_get_option('qqweibo_f').'" frameborder="0" scrolling="auto" width="150" height="24" marginwidth="0" marginheight="0" allowtransparency="true"></iframe></li>';
        }
        ?>
      </ul>
      <?php
        $action = home_url();
        $search_id = '';
        $name = 's';
      ?>
      <div id="search"<?php if( cmp_get_option('theme_layout') =='vertical' && cmp_get_option('nav_fixed')) echo ' class="nav-fixed"'; ?>>
        <div class="toggle-search">
          <i class="fa fa-search fa-white fa-fw"></i>
        </div>
        <div class="search-expand">
          <div class="search-expand-inner">
            <form method="get" class="searchform themeform" action="<?php echo $action ?>" <?php if(cmp_get_option( 'search_target' )) echo 'target="_blank"'; ?>>
              <div>
                <?php echo $search_id ?>
                <input type="text" class="search" name="<?php echo $name ?>" onblur="if(this.value=='')this.value='<?php _e('Input and press Enter','wpdx'); ?>';" onfocus="if(this.value=='<?php _e('Input and press Enter','wpdx'); ?>')this.value='';" value="<?php _e('Input and press Enter','wpdx'); ?>" x-webkit-speech />
                <button type="submit" id="submit-bt" title="<?php _e('Search' , 'wpdx'); ?>"><i class="fa fa-search"></i></button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php if(cmp_get_option('theme_layout') == 'vertical' ) get_template_part('includes/ad-top-right' );?>
    </div>