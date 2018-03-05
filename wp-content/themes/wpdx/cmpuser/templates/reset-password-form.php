<div class="cmpuser-container">
<?php do_action( 'cmpuser_reset_form_top' ); ?>
	<form class="cmpuser-form" method="post" action="#">
		<fieldset>
			<div class="cmpuser-field">
			<label for='website'><?php _e( 'Type your username or E-mail:','wpdx' ); ?></label>
				<input class="cmpuser-field-username" type="text" name="username" value="">
			</div>
			<div class="cmpuser-field-website">
				<label for='website'><?php _e('Website','wpdx')?></label>
	    		<input type='text' name='website' value="1">
	    	</div>
		</fieldset>
		<div>	
			<input type="submit" value="<?php _e( 'Reset password', 'wpdx' ); ?>" name="submit">
			<input type="hidden" name="action" value="reset_password">		
		</div>
	</form>
	<?php do_action( 'cmpuser_reset_form_bottom' ); ?>
</div>