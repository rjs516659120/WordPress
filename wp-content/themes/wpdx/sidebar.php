<aside class="span4 sidebar-right <?php if(cmp_get_option( 'hide_sidebar' )) echo 'hide-sidebar'; ?>" role="complementary">
  <?php
  wp_reset_query();
  global $post;
  $archive_question_page = 0;
  $submit_question_page = 0; 
  if(class_exists('DW_Question_Answer')) {
    global $dwqa_general_settings;
    if(isset( $dwqa_general_settings['pages']['archive-question'] )) $archive_question_page = $dwqa_general_settings['pages']['archive-question']; 
    if(isset( $dwqa_general_settings['pages']['submit-question'] )) $submit_question_page = $dwqa_general_settings['pages']['submit-question']; 
  }
  if ( is_home() || is_404()){
    $sidebar_home = cmp_get_option( 'sidebar_home' );
    if( $sidebar_home ){
      dynamic_sidebar ( sanitize_title( $sidebar_home ) );
    } elseif (function_exists('dynamic_sidebar') && dynamic_sidebar('primary-widget-area')){
    } else {
      echo '<div class="the_tips">'.__('Please go to "<a target="_blank" href="/wp-admin/widgets.php"> Appearance > Widgets </a>" to set "Primary Widget Area" , Or "<a target="_blank" href="/wp-admin/admin.php?page=panel"> Theme Setting </a>" to Set [Sidebars] option .','wpdx').'</div>';
    }
  }elseif ( has_shortcode( $post->post_content, 'dwqa-list-questions') || has_shortcode( $post->post_content, 'my-dwqa-list-questions') || has_shortcode( $post->post_content, 'dwqa-submit-question-form') || ($archive_question_page != 0  &&  is_page($archive_question_page)) || ($submit_question_page != 0  && is_page($submit_question_page)) ){
    if (function_exists('dynamic_sidebar') && dynamic_sidebar('dwqa-widget-area')){
    } else {
      echo '<div class="the_tips">'.__('Please go to "<a target="_blank" href="/wp-admin/widgets.php"> Appearance > Widgets </a>" to set "DWQA Widget Area".','wpdx').'</div>';
    }
  }elseif( is_page() ){
    global $get_meta;
    $cmp_sidebar_pos = $get_meta["cmp_sidebar_pos"][0];
    if( $cmp_sidebar_pos != 'full' ){
      $cmp_sidebar_post = sanitize_title($get_meta["cmp_sidebar_post"][0]);
      $sidebar_page = cmp_get_option( 'sidebar_page' );
      if( $cmp_sidebar_post ){
        dynamic_sidebar($cmp_sidebar_post);
      }
      elseif( $sidebar_page ){
        dynamic_sidebar ( sanitize_title( $sidebar_page ) );
      }
      elseif (function_exists('dynamic_sidebar') && dynamic_sidebar('primary-widget-area')){
      } else {
        echo '<div class="the_tips">'.__('Please go to "<a target="_blank" href="/wp-admin/widgets.php"> Appearance > Widgets </a>" to set "Primary Widget Area" , Or "<a target="_blank" href="/wp-admin/admin.php?page=panel"> Theme Setting </a>" to Set [Sidebars] option .','wpdx').'</div>';
      }
    }
  }elseif ( is_single() ){
    $post_type = get_post_type($post->ID);
    if ( $post_type == 'dwqa-question' ){
      if (function_exists('dynamic_sidebar') && dynamic_sidebar('dwqa-widget-area')){
      } else {
        echo '<div class="the_tips">'.__('Please go to "<a target="_blank" href="/wp-admin/widgets.php"> Appearance > Widgets </a>" to set "DWQA Widget Area".','wpdx').'</div>';
      }
    }else{
      global $get_meta;
      $cmp_sidebar_pos = $get_meta["cmp_sidebar_pos"][0];
      if( $cmp_sidebar_pos != 'full' ){
        $cmp_sidebar_post = sanitize_title($get_meta["cmp_sidebar_post"][0]);
        $sidebar_post = cmp_get_option( 'sidebar_post' );
        if( $cmp_sidebar_post )
          dynamic_sidebar($cmp_sidebar_post);
        elseif( $sidebar_post )
          dynamic_sidebar ( sanitize_title( $sidebar_post ) );
        elseif (function_exists('dynamic_sidebar') && dynamic_sidebar('primary-widget-area')){
        } else {
          echo '<div class="the_tips">'.__('Please go to "<a target="_blank" href="/wp-admin/widgets.php"> Appearance > Widgets </a>" to set "Primary Widget Area" , Or "<a target="_blank" href="/wp-admin/admin.php?page=panel"> Theme Setting </a>" to Set [Sidebars] option .','wpdx').'</div>';
        }
      }
    }
  }elseif ( is_tax('dwqa-question_category') || is_tax('dwqa-question_tag') ){
    if (function_exists('dynamic_sidebar') && dynamic_sidebar('dwqa-widget-area')){
    } else {
      echo '<div class="the_tips">'.__('Please go to "<a target="_blank" href="/wp-admin/widgets.php"> Appearance > Widgets </a>" to set "DWQA Widget Area".','wpdx').'</div>';
    }
  }elseif ( is_category() ){
    $category_id = get_query_var('cat') ;
    $cat_sidebar = cmp_get_option( 'sidebar_cat_'.$category_id ) ;
    $sidebar_archive = cmp_get_option( 'sidebar_archive' );
    if( $cat_sidebar )
      dynamic_sidebar ( sanitize_title( $cat_sidebar ) );
    elseif( $sidebar_archive )
      dynamic_sidebar ( sanitize_title( $sidebar_archive ) );
    elseif (function_exists('dynamic_sidebar') && dynamic_sidebar('primary-widget-area')){
    } else {
      echo '<div class="the_tips">'.__('Please go to "<a target="_blank" href="/wp-admin/widgets.php"> Appearance > Widgets </a>" to set "Primary Widget Area" , Or "<a target="_blank" href="/wp-admin/admin.php?page=panel"> Theme Setting </a>" to Set [Sidebars] option .','wpdx').'</div>';
    }
  }else{
    $sidebar_archive = cmp_get_option( 'sidebar_archive' );
    if( $sidebar_archive ){
      dynamic_sidebar ( sanitize_title( $sidebar_archive ) );
    }
    elseif (function_exists('dynamic_sidebar') && dynamic_sidebar('primary-widget-area')){
    } else {
      echo '<div class="the_tips">'.__('Please go to "<a target="_blank" href="/wp-admin/widgets.php"> Appearance > Widgets </a>" to set "Primary Widget Area" , Or "<a target="_blank" href="/wp-admin/admin.php?page=panel"> Theme Setting </a>" to Set [Sidebars] option .','wpdx').'</div>';
    }
  }
  ?>
</aside>