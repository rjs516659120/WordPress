<?php if(is_home() && cmp_get_option('footer_links') && !cmp_is_mobile()): ?>
  <div class="span12 home-links">
    <div class="widget-box">
      <div class="widget-title"><span class="icon"><i class="fa fa-link fa-fw"></i></span><h3><?php _e('Links','wpdx'); ?></h3></div>
      <div class="widget-content">
       <ul>
         <?php if(function_exists('wp_nav_menu')) wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'foot-link', 'fallback_cb' => 'cmp_nav_fallback')); ?>
       </ul>
       <div class="clear"></div>
     </div>
   </div>
 </div>
<?php endif; ?>