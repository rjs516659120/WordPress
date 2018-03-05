<?php
	$current_user = wp_get_current_user();
	if(cmp_get_option( 'profile_url')){
		$profile_url = cmp_get_option( 'profile_url');
	}else{
		$profile_url = get_edit_user_link();
	}
?>

<div class="cmpuser-container" >
	<div class="cmpuser-preview">
		
		<?php echo get_avatar( $current_user->ID, 128 ); ?>

		<?php // Since 1.1 (show username or not) ?>

		<h4><?php echo $current_user->display_name; ?></h4>
		<?php if(has_nav_menu('user-menu')){
			echo '<ul class="user-view-menu">';
          wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'user-menu', 'fallback_cb' => '','walker' => new wp_bootstrap_navwalker()));
          echo '</ul>';
      	}else{ ?>
      		<div class="cmpuser-preview-top">
			<a href="<?php echo esc_url( add_query_arg( 'action', 'logout' ) ); ?>" class="cmpuser-preview-logout-link"><?php _e( 'Log out', 'wpdx' ); ?></a>	
			<?php if ( $profile_url )
				echo "<a href='$profile_url' class='cmpuser-preview-edit-link'>". __( 'Edit my profile', 'wpdx' ) ."</a>";
			?>
		</div>
      	<?php } ?>
	</div>		
</div>