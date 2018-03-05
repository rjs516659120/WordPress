<div class="cmpuser-container">
	<form class="cmpuser-form">
		
		<fieldset>
			<div class="cmpuser-field">
				<label><?php _e( 'Your new password is', 'wpdx' ); ?></label>
				<input type="text" name="pass" value="<?php echo $new_password; ?>">
			</div>
			
		</fieldset>
		
		<div class="cmpuser-form-bottom">
			
			<?php
			if(cmp_get_option('login_url')){
				$login_url = htmlspecialchars_decode(cmp_get_option('login_url')) ;
			}else{
				$login_url = wp_login_url();
			}

			if ( $login_url )
				echo "<a href='$login_url' class='cmpuser-form-login-link'>". __( 'Log in', 'wpdx') ."</a>";
			?>
			
		</div>
	</form>
</div>