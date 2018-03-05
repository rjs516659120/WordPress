<?php
	$current_user = wp_get_current_user();
?>

<div class="cmpuser-container cmpuser-full-width">
	<form class="cmpuser-form" method="post" action="#">

		<h4><?php _e( 'General information', 'wpdx' ); ?></h4>

		<fieldset>
		
			<div class="cmpuser-field">
				<label><?php _e( 'First name', 'wpdx' ); ?></label>
				<input type="text" name="first_name" value="<?php echo $current_user->user_firstname; ?>">
			</div>
			
			<div class="cmpuser-field">
				<label><?php _e( 'Last name', 'wpdx' ); ?></label>
				<input type="text" name="last_name" value="<?php echo $current_user->user_lastname; ?>">
			</div>
			
			<div class="cmpuser-field">
				<label><?php _e( 'E-mail', 'wpdx' ); ?></label>
				<input type="text" name="email" value="<?php echo $current_user->user_email; ?>">
			</div>
			
		</fieldset>

		<h4><?php _e( 'Change password', 'wpdx' ); ?></h4>
		
		<p class="cmpuser-form-description"><?php _e( "If you would like to change the password type a new one. Otherwise leave this blank.", 'wpdx' ); ?></p>
		
		<fieldset>
		
			<div class="cmpuser-field">
				<label><?php _e( 'New password', 'wpdx' ); ?></label>
				<input type="password" name="pass1" value="" autocomplete="off">
			</div>
			
			<div class="cmpuser-field">
				<label><?php _e( 'Confirm password', 'wpdx' ); ?></label>
				<input type="password" name="pass2" value="" autocomplete="off">
			</div>
		
		</fieldset>
		
		<div>	
			<input type="submit" value="<?php _e( 'Update profile', 'wpdx' ); ?>" name="submit">
			<input type="hidden" name="action" value="edit">		
		</div>

	</form>
</div>