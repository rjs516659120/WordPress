<?php
$enable_passcomplex = cmp_get_option( 'password_complexity' );
$emailvalidation = cmp_get_option('email_validation');
$emailnotification = cmp_get_option( 'email_notification' );
?>
<div class="cmpuser-container cmpuser-full-width">
<?php do_action( 'cmpuser_register_form_top' ); ?>
	<form class="cmpuser-form" method="post" action="#">

		<fieldset>
			<div class="cmpuser-field">
				<?php _e('<p>All of the following fields are required:</p>','wpdx'); ?>
			</div>
			<div class="cmpuser-field">
				<label for='username'><?php _e( 'Username', 'wpdx' ); ?></label>
				<input class="cmpuser-field-username" type="text" name="username" value="">
				<p class="cmpuser-form-description"><?php _e( 'Note: User name does not support Chinese characters, please use letters and numbers', 'wpdx' ); ?></p>
			</div>

			<div class="cmpuser-field">
				<label for='nickname'><?php _e( 'Nickname', 'wpdx' ); ?></label>
				<input class="cmpuser-field-nickname" type="text" name="nickname" value="">
			</div>
			
			<div class="cmpuser-field">
				<label for='email'><?php _e( 'E-mail', 'wpdx' ); ?></label>
				<input class="cmpuser-field-email" type="email" name="email" value="">
				<p class="cmpuser-form-description"><?php _e( 'Please type your usual e-mail address in order to activate the account and receive notifications.', 'wpdx' ); ?></p>
			</div>

			<?php if ( cmp_get_option( 'first_last_name' )) : ?>
				<div class="cmpuser-field">
					<label for='name'><?php _e( 'First name', 'wpdx' ); ?></label>
					<input class="cmpuser-field-name" type="text" name="first_name" value="">
				</div>
				<div class="cmpuser-field">
					<label for='surname'><?php _e( 'Last name', 'wpdx' ); ?></label>
					<input class="cmpuser-field-surname" type="text" name="last_name" value="">
				</div>
			<?php endif; ?>

			<div class="cmpuser-field-website">
				<label for='website'><?php _e( 'Website', 'wpdx' ); ?></label>
				<input type='text' name='website' value="1">
			</div>
			
			
			<div class="cmpuser-field">
				<label for='password'><?php _e( 'Password', 'wpdx' ); ?></label>
				<input class="cmpuser-field-password" type="password" name="pass1" value="" autocomplete="off">
				<?php if($enable_passcomplex): ?>
					<p class="cmpuser-form-description"><?php _e( 'Your password must contain at least 1 uppercase, 1 lowercase letter and at least 1 number. Passwords should not contain the user\'s username, email, or first/last name.', 'wpdx' ); ?></p>
				<?php endif; ?>
			</div>
			
			<?php if ( !cmp_get_option( 'single_password' )) : ?>
				<div class="cmpuser-field">
					<label for='password'><?php _e( 'Confirm password', 'wpdx' ); ?></label>
					<input class="cmpuser-field-password" type="password" name="pass2" value="" autocomplete="off">
				</div>
			<?php endif; ?>

			<?php if ( cmp_get_option( 'choose_role' )) : ?>
				<?php if ($param['role']) : ?>
					<input type="text" name="role" value="<?php echo $param['role']; ?>" hidden >
				<?php else : ?> 
					<div class="cmpuser-field cmpuser-field-role">
						<label for='role'><?php _e( 'Choose your role', 'wpdx' ); ?></label>
						<select name="role" id="role">
							<?php
							$newuserroles = cmp_get_option( 'new_user_roles' );
							global $wp_roles;
							foreach($newuserroles as $role){
								$roleName = translate_user_role( $wp_roles->roles[ $role ]['name'] );
								echo '<option value="'.$role.'">'. $roleName .'</option>';
							}
							?>
						</select>
					</div>
				<?php  endif; ?>
			<?php endif; ?>

			<?php if ( cmp_get_option( 'antispam' )) : ?>
				<div class="cmpuser-field">
					<label for='captcha'><?php _e( 'Captcha', 'wpdx' ); ?></label>
					<img src="<?php echo get_template_directory_uri().'/cmpuser/captcha/'; ?>"/>
					<input class="cmpuser-field-spam" type="text" name="captcha" value="" autocomplete="off" placeholder="<?php _e( 'Type the captcha above', 'wpdx' ); ?>">
				</div>
			<?php endif; ?>

			<?php if ( cmp_get_option( 'terms_conditions' )) : ?>
				<div class="cmpuser-field">
					<label class="cmpuser-terms">
						<input name="termsconditions" type="checkbox" id="termsconditions">
						<a href="<?php echo cmp_get_option( 'terms_conditions_url' ); ?>" target="_blank"><?php echo cmp_get_option( 'terms_conditions_msg' ); ?></a>
					</label>
				</div>
			<?php endif; ?>
		</fieldset>
		<div>
			<input type="submit" value="<?php _e( 'Register', 'wpdx' ); ?>" name="submit" onclick="this.form.submit(); this.disabled = true;">
			<input type="hidden" name="action" value="register">	
		</div>
				
	</form>
	<?php do_action( 'cmpuser_register_form_bottom' ); ?>
</div>