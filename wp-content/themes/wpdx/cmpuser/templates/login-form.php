<?php
    $protocol = is_ssl() ? 'https://' : 'http://';
    $redirect_to = $protocol.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
    if(function_exists('cmpuser_url_cleaner')) $redirect_to = cmpuser_url_cleaner($redirect_to);
    $login_url = wp_login_url($redirect_to);
    $password_url = wp_lostpassword_url();
    $register_url = wp_registration_url();
?>
<div class="cmpuser-container">
<?php do_action( 'cmpuser_login_form_top' ); ?>
	<form class="cmpuser-form" method="post" action="<?php echo $login_url; ?>">
		
		<fieldset>
			<div class="cmpuser-field">
				<label><?php _e( 'Username/Email', 'wpdx' ); ?></label>
				<input class="cmpuser-field-username" type="text" name="log">
			</div>

			<div class="cmpuser-field">
				<label><?php _e( 'Password', 'wpdx' ); ?></label>
				<input class="cmpuser-field-password" type="password" name="pwd">
			</div>
			<?php do_action( 'login_form' ); ?>
			<div class="cmpuser-field cmpuser-field-remember">
				<input type="checkbox" name="rememberme" value="forever">
				<label><?php _e( 'Remember Me', 'wpdx' ); ?></label>
			</div>
			<input class="cmpuser-field" type="submit" value="<?php _e( 'Log in', 'wpdx' ); ?>" name="submit">
			<input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
			<input type="hidden" name="wpuf_login" value="true" />
			<input type="hidden" name="action" value="login">
			<?php wp_nonce_field( 'wpuf_login_action' ); ?>

		</fieldset>

		<div class="cmpuser-form-bottom">

			<?php if ( $password_url )
			echo '<a href="'.$password_url.'" class="cmpuser-form-pwd-link">'. __( 'Lost your password?', 'wpdx' ) .'</a>';
			?>
			<?php if ( $register_url && get_option('users_can_register') == '1')
			echo '<a href="'.$register_url.'" class="cmpuser-form-register-link">'. __( 'Register an account', 'wpdx' ) .'</a>';
			?>
		</div>
	</form>
	<?php do_action( 'cmpuser_login_form_bottom' ); ?>
</div>
