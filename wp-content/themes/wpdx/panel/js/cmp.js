jQuery(document).ready(function() {

    jQuery('.tooltip').tipsy({fade: true, gravity: 's'});


// Del Preview Image ##############################################
	jQuery(".del-img").live("click" , function() {
		jQuery(this).parent().fadeOut(function() {
			jQuery(this).hide();
			jQuery(this).parent().find('input[class="img-path"]').attr('value', '' );
		});
	});


// Breaking News options ##############################################
	var selected_breaking = jQuery("input[name='cmp_options[breaking_type]']:checked").val();

	jQuery('#breaking_cat-item , #breaking_tag-item , #breaking_custom-item , #breaking_number-item').hide();

	if (selected_breaking == 'category') {jQuery('#breaking_cat-item , #breaking_number-item').show();}
	if (selected_breaking == 'tag') {jQuery('#breaking_tag-item , #breaking_number-item').show();}
	if (selected_breaking == 'custom') { jQuery('#breaking_custom-item').show(); }

	jQuery("input[name='cmp_options[breaking_type]']").change(function(){
		var selected_breaking = jQuery("input[name='cmp_options[breaking_type]']:checked").val();
		if (selected_breaking == 'category') {
			jQuery('#breaking_tag-item , #breaking_custom-item').hide();
			jQuery('#breaking_cat-item , #breaking_number-item').fadeIn();
		}
		if (selected_breaking == 'tag') {
			jQuery('#breaking_cat-item , #breaking_custom-item').hide();
			jQuery('#breaking_tag-item , #breaking_number-item').fadeIn();
		}
		if (selected_breaking == 'custom') {
			jQuery('#breaking_cat-item , #breaking_tag-item , #breaking_number-item').hide();
			jQuery('#breaking_custom-item').fadeIn();
		}

	 });



// Single Post Head ##############################################
	var selected_item = jQuery("select[name='cmp_post_head'] option:selected").val();

	if (selected_item == 'video') {jQuery('#cmp_video_url-item, #cmp_embed_code-item').show();}
	if (selected_item == 'audio') {jQuery('#cmp_audio_mp3-item, #cmp_audio_m4a-item, #cmp_audio_oga-item').show();}
	if (selected_item == 'soundcloud') {jQuery('#cmp_audio_soundcloud-item, #cmp_audio_soundcloud_play-item').show();}
	if (selected_item == 'slider') {jQuery('#cmp_post_slider-item').show();}
	if (selected_item == 'map') {jQuery('#cmp_googlemap_url-item').show();}

	jQuery("select[name='cmp_post_head']").change(function(){
		var selected_item = jQuery("select[name='cmp_post_head'] option:selected").val();
		if (selected_item == 'video') {
			jQuery('#cmp_post_slider-item, #cmp_googlemap_url-item, #cmp_audio_mp3-item, #cmp_audio_m4a-item, #cmp_audio_oga-item, #cmp_audio_soundcloud-item, #cmp_audio_soundcloud_play-item').hide();
			jQuery('#cmp_video_url-item, #cmp_embed_code-item').fadeIn();
		}
		if (selected_item == 'audio') {
			jQuery('#cmp_video_url-item, #cmp_embed_code-item, #cmp_post_slider-item, #cmp_googlemap_url-item, #cmp_audio_soundcloud-item, #cmp_audio_soundcloud_play-item').hide();
			jQuery('#cmp_audio_mp3-item, #cmp_audio_m4a-item, #cmp_audio_oga-item').fadeIn();
		}
		if (selected_item == 'soundcloud') {
			jQuery('#cmp_video_url-item, #cmp_embed_code-item, #cmp_post_slider-item, #cmp_googlemap_url-item, #cmp_audio_mp3-item, #cmp_audio_m4a-item, #cmp_audio_oga-item').hide();
			jQuery('#cmp_audio_soundcloud-item, #cmp_audio_soundcloud_play-item').fadeIn();
		}
		if (selected_item == 'slider') {
			jQuery('#cmp_video_url-item, #cmp_embed_code-item, #cmp_googlemap_url-item, #cmp_audio_mp3-item, #cmp_audio_m4a-item, #cmp_audio_oga-item, #cmp_audio_soundcloud-item, #cmp_audio_soundcloud_play-item').hide();
			jQuery('#cmp_post_slider-item').fadeIn();
		}
		if (selected_item == 'map') {
			jQuery('#cmp_video_url-item, #cmp_embed_code-item, #cmp_post_slider-item, #cmp_audio_mp3-item, #cmp_audio_m4a-item, #cmp_audio_oga-item, #cmp_audio_soundcloud-item, #cmp_audio_soundcloud_play-item').hide();
			jQuery('#cmp_googlemap_url-item').fadeIn();
		}
		if (selected_item == 'thumb' || selected_item == 'none' || selected_item == '') {
			jQuery('#cmp_video_url-item, #cmp_embed_code-item, #cmp_post_slider-item, #cmp_googlemap_url-item, #cmp_audio_mp3-item, #cmp_audio_m4a-item, #cmp_audio_oga-item, #cmp_audio_soundcloud-item, #cmp_audio_soundcloud_play-item').hide();
		}
	 });


// Display on Home ##############################################
	var selected_radio = jQuery("input[name='cmp_options[on_home]']:checked").val();
	if (selected_radio == 'latest') {	jQuery('#Home_Builder').hide();	}
	if (selected_radio == 'boxes') {	jQuery('#Home_blog').hide();	}
	jQuery("input[name='cmp_options[on_home]']").change(function(){
		var selected_radio = jQuery("input[name='cmp_options[on_home]']:checked").val();
		if (selected_radio == 'latest') {
			jQuery('#Home_blog').fadeIn();
			jQuery('#Home_Builder').hide();
		}else{
			jQuery('#Home_Builder').fadeIn();
			jQuery('#Home_blog').hide();
		}
	 });

// Choose thumbnails resize method  ##############################################
	var selected_radio = jQuery("input[name='cmp_options[thumb_cut]']:checked").val();
	if (selected_radio == 'aq' || selected_radio == 'otf' ) {
		jQuery('#thumb_zc-item').hide();
		jQuery('#thumb_q-item').hide();
	}
	if (selected_radio == 'qiniu') {
		jQuery('#thumb_zc-item').hide();
	}
	jQuery("input[name='cmp_options[thumb_cut]']").change(function(){
		var selected_radio = jQuery("input[name='cmp_options[thumb_cut]']:checked").val();
		if (selected_radio == 'tim') {
			jQuery('#thumb_zc-item').fadeIn();
			jQuery('#thumb_q-item').fadeIn();
		}else if (selected_radio == 'qiniu') {
			jQuery('#thumb_zc-item').hide();
			jQuery('#thumb_q-item').fadeIn();
		}else{
			jQuery('#thumb_zc-item').hide();
			jQuery('#thumb_q-item').hide();
		}
	 });

//##############################################
	var selected_radio = jQuery("input[name='cmp_options[blog_pagination_type]']:checked").val();
	if ( !selected_radio || selected_radio == 'pagination') {
		jQuery('#blog_ajax_num-item').hide();
		jQuery('#blog_no_ajax_num-item').hide();
	}

	jQuery("input[name='cmp_options[blog_pagination_type]']").change(function(){
		var selected_radio = jQuery("input[name='cmp_options[blog_pagination_type]']:checked").val();
		if (selected_radio == 'pagination') {
			jQuery('#blog_ajax_num-item').hide();
			jQuery('#blog_no_ajax_num-item').hide();
		}else{
			jQuery('#blog_ajax_num-item').fadeIn();
			jQuery('#blog_no_ajax_num-item').fadeIn();
		}
	 });
////////////////////////////////
	var selected_radio = jQuery("input[name='cmp_options[archive_pagination_type]']:checked").val();
	if ( !selected_radio || selected_radio == 'pagination') {
		jQuery('#archive_ajax_num-item').hide();
		jQuery('#archive_no_ajax_num-item').hide();
	}

	jQuery("input[name='cmp_options[archive_pagination_type]']").change(function(){
		var selected_radio = jQuery("input[name='cmp_options[archive_pagination_type]']:checked").val();
		if (selected_radio == 'pagination') {
			jQuery('#archive_ajax_num-item').hide();
			jQuery('#archive_no_ajax_num-item').hide();
		}else{
			jQuery('#archive_ajax_num-item').fadeIn();
			jQuery('#archive_no_ajax_num-item').fadeIn();
		}
	 });
/////////////////////////////////
	var selected_radio = jQuery("input[name='cmp_options[choose_role]']:checked").val();
	if ( !selected_radio ) {
		jQuery('#new_user_roles-item').hide();
	}

	jQuery("input[name='cmp_options[choose_role]']").change(function(){
		var selected_radio = jQuery("input[name='cmp_options[choose_role]']:checked").val();
		if (selected_radio) {
			jQuery('#new_user_roles-item').fadeIn();
		}else{
			jQuery('#new_user_roles-item').hide();
		}
	 });

/////////////////////////////////
	var selected_radio = jQuery("input[name='cmp_options[automatic_login]:checked").val();
	if ( !selected_radio ) {
		jQuery('#register_redirect_url-item').hide();
	}

	jQuery("input[name='cmp_options[automatic_login]']").change(function(){
		var selected_radio = jQuery("input[name='cmp_options[automatic_login]']:checked").val();
		if (selected_radio) {
			jQuery('#register_redirect_url-item').fadeIn();
		}else{
			jQuery('#register_redirect_url-item').hide();
		}
	 });

/////////////////////////////////
	var selected_radio = jQuery("input[name='cmp_options[email_notification_user]']:checked").val();
	if ( !selected_radio ) {
		jQuery('#email_notification_content-item').hide();
		jQuery('.email_notification_content_tips').hide();
	}

	jQuery("input[name='cmp_options[email_notification_user]']").change(function(){
		var selected_radio = jQuery("input[name='cmp_options[email_notification_user]']:checked").val();
		if (selected_radio) {
			jQuery('#email_notification_content-item').fadeIn();
			jQuery('.email_notification_content_tips').fadeIn();
		}else{
			jQuery('#email_notification_content-item').hide();
			jQuery('.email_notification_content_tips').hide();
		}
	 });

/////////////////////////////////
	var selected_radio = jQuery("input[name='cmp_options[terms_conditions]']:checked").val();
	if ( !selected_radio ) {
		jQuery('#terms_conditions_msg-item').hide();
		jQuery('#terms_conditions_url-item').hide();
	}

	jQuery("input[name='cmp_options[terms_conditions]']").change(function(){
		var selected_radio = jQuery("input[name='cmp_options[terms_conditions]']:checked").val();
		if (selected_radio) {
			jQuery('#terms_conditions_msg-item').fadeIn();
			jQuery('#terms_conditions_url-item').fadeIn();
		}else{
			jQuery('#terms_conditions_msg-item').hide();
			jQuery('#terms_conditions_url-item').hide();
		}
	 });

/////////////////////////////////
	var selected_radio = jQuery("input[name='cmp_options[post_note_type]']:checked").val();
	if ( selected_radio =='none'|| !selected_radio ) {
		jQuery('#original_url_nofollow-item').hide();
		jQuery('#post_note-item').hide();
	}

	jQuery("input[name='cmp_options[post_note_type]']").change(function(){
		var selected_radio = jQuery("input[name='cmp_options[post_note_type]']:checked").val();
		if (selected_radio =='static') {
			jQuery('#original_url_nofollow-item').hide();
			jQuery('#post_note-item').fadeIn();
		}else if(selected_radio =='dynamic'){
			jQuery('#original_url_nofollow-item').fadeIn();
			jQuery('#post_note-item').hide();
		}else{
			jQuery('#original_url_nofollow-item').hide();
			jQuery('#post_note-item').hide();
		}
	 });

// Reviews On or Off ##############################################
	var reviews_on = jQuery("select[name='cmp_review_position'] option:selected ").val();
	if (reviews_on != '') {	jQuery('#reviews-options').show();	}
	jQuery("select[name='cmp_review_position']").change(function(){
		var reviews_on = jQuery("select[name='cmp_review_position'] option:selected ").val();
		if (reviews_on == '') {
			jQuery('#reviews-options').fadeOut();
		}else{
			jQuery('#reviews-options').fadeIn();
		}
	 });


// Slider Position ##############################################
	var selected_pos = jQuery("input[name='cmp_options[slider_type]']:checked").val();

	if (selected_pos == 'slideshow') {jQuery('#slideshow').show();}
	if (selected_pos == 'carousel') {jQuery('#carousel').show();}

	jQuery("input[name='cmp_options[slider_type]']").change(function(){
		var selected_pos = jQuery("input[name='cmp_options[slider_type]']:checked").val();
		if (selected_pos == 'slideshow') {
			jQuery('#carousel').hide();
			jQuery('#slideshow').fadeIn();
		}
		if (selected_pos == 'carousel') {
			jQuery('#slideshow').hide();
			jQuery('#carousel').fadeIn();
		}

	 });


// Slider Query Type ##############################################
	var selected_type = jQuery("input[name='cmp_options[slider_query]']:checked").val();

	if (selected_type == 'category') {jQuery('#slider_cat-item').show();}
	if (selected_type == 'tag') {jQuery('#slider_tag-item').show();}
	if (selected_type == 'post') {jQuery('#slider_posts-item').show();}
	if (selected_type == 'page') {jQuery('#slider_pages-item').show();}
	if (selected_type == 'custom') {jQuery('#slider_custom-item').show();}

	jQuery("input[name='cmp_options[slider_query]']").change(function(){
		var selected_type = jQuery("input[name='cmp_options[slider_query]']:checked").val();
		if (selected_type == 'category') {
			jQuery('#slider_tag-item ,#slider_posts-item ,#slider_pages-item,#slider_custom-item').hide();
			jQuery('#slider_cat-item').fadeIn();
		}
		if (selected_type == 'tag') {
			jQuery('#slider_cat-item ,#slider_posts-item ,#slider_pages-item,#slider_custom-item').hide();
			jQuery('#slider_tag-item').fadeIn();
		}
		if (selected_type == 'post') {
			jQuery('#slider_cat-item ,#slider_tag-item ,#slider_pages-item,#slider_custom-item').hide();
			jQuery('#slider_posts-item').fadeIn();
		}
		if (selected_type == 'page') {
			jQuery('#slider_cat-item ,#slider_posts-item ,#slider_tag-item,#slider_custom-item').hide();
			jQuery('#slider_pages-item').fadeIn();
		}
		if (selected_type == 'custom') {
			jQuery('#slider_cat-item ,#slider_posts-item ,#slider_tag-item,#slider_pages-item').hide();
			jQuery('#slider_custom-item').fadeIn();
		}
	 });


// Save Settings Alert	##############################################
	jQuery(".mpanel-save").click( function() {
		jQuery('#save-alert').fadeIn();
	});


// HomeBuilder
	var htm1l = jQuery('#cats_defult').html();
	var htm2l = jQuery('#post_type_defult').html();
	var htm3l = jQuery('#edd_cats_defult').html();

	jQuery("#add-cat").click(function() {
		jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head"> '+ cmp_var.news_box +' <a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div style="display:block" class="widget-content"><label for="cmp_home_cats['+ nextCell +'][who]"><span>'+ cmp_var.who +'</span><select id="cmp_home_cats['+ nextCell +'][who]" name="cmp_home_cats['+ nextCell +'][who]"><option value="anyone">'+ cmp_var.anyone +'</option><option value="logged">'+ cmp_var.logged +'</option><option value="anonymous">'+ cmp_var.anonymous +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][post_ids]"><span>'+ cmp_var.post_ids +'</span><a class="mo-help" title="'+ cmp_var.post_ids_tip +'"></a><textarea id="cmp_home_cats['+ nextCell +'][post_ids]" name="cmp_home_cats['+ nextCell +'][post_ids]" placeholder="'+ cmp_var.post_ids_tip +'"></textarea></label><label><span style="float:left;">'+ cmp_var.choose_cat +'</span><a class="mo-help" title="'+ cmp_var.choose_cat_tip +'"></a><select multiple="multiple" name="cmp_home_cats['+ nextCell +'][id][]" id="cmp_home_cats['+ nextCell +'][id][]">'+htm1l+'</select></label><label><span>'+ cmp_var.order +'</span><select name="cmp_home_cats['+ nextCell +'][order]" id="cmp_home_cats['+ nextCell +'][order]"><option value="latest" selected="selected">'+ cmp_var.latest +'</option><option value="rand">'+ cmp_var.random +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][number]"><span>'+ cmp_var.number +'</span><input style="width:50px;" id="cmp_home_cats['+ nextCell +'][number]" name="cmp_home_cats['+ nextCell +'][number]" value="6" type="text" /></label><label><span style="float:left; width:200px">'+ cmp_var.bstyle +'</span><ul class="cmp-cats-options cmp-options"><li class="selected"><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="3c" checked="checked"/><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/3c.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="2c1" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/2c1.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="2c" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/2c.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="li1" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/li1.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="li" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/li.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="1c1" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/1c1.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="1c" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/1c.png" /></a></li></ul></label><label><span>'+ cmp_var.thumb +'</span><select name="cmp_home_cats['+ nextCell +'][thumb]" id="cmp_home_cats['+ nextCell +'][thumb]"><option value="n" selected="selected">'+ cmp_var.thumb_n +'</option><option value="t">'+ cmp_var.thumb_t +'</option><option value="a">'+ cmp_var.thumb_a +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][title]"><span>'+ cmp_var.box_title +'</span><input id="cmp_home_cats['+ nextCell +'][title]" name="cmp_home_cats['+ nextCell +'][title]" value="" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][icon]"><span>'+ cmp_var.icon+'</span><input id="cmp_home_cats['+ nextCell +'][icon]" name="cmp_home_cats['+ nextCell +'][icon]" value="fa-list" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_text]"><span>'+ cmp_var.more_text +'</span><input id="cmp_home_cats['+ nextCell +'][more_text]" name="cmp_home_cats['+ nextCell +'][more_text]" value="'+ cmp_var.more_text_detail +'" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_url]"><span>'+ cmp_var.more_url +'</span><input id="cmp_home_cats['+ nextCell +'][more_url]" name="cmp_home_cats['+ nextCell +'][more_url]" value="" type="text" /></label><input id="cmp_home_cats['+ nextCell +'][type]" name="cmp_home_cats['+ nextCell +'][type]" value="n" type="hidden" /><div class="clear"></div><a class="del-cat" title="'+ cmp_var.del_cat +'"></a></div></li>');
		jQuery('#listItem_'+ nextCell).hide().fadeIn();
		nextCell ++ ;
	});
	jQuery("#add-cat-edd").click(function() {
		jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head"> '+ cmp_var.edd_news_box +' <a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div style="display:block" class="widget-content"><label for="cmp_home_cats['+ nextCell +'][who]"><span>'+ cmp_var.who +'</span><select id="cmp_home_cats['+ nextCell +'][who]" name="cmp_home_cats['+ nextCell +'][who]"><option value="anyone">'+ cmp_var.anyone +'</option><option value="logged">'+ cmp_var.logged +'</option><option value="anonymous">'+ cmp_var.anonymous +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][post_ids]"><span>'+ cmp_var.post_ids +'</span><a class="mo-help" title="'+ cmp_var.post_ids_tip +'"></a><textarea id="cmp_home_cats['+ nextCell +'][post_ids]" name="cmp_home_cats['+ nextCell +'][post_ids]" placeholder="'+ cmp_var.post_ids_tip +'"></textarea></label><label><span style="float:left;">'+ cmp_var.choose_cat +'</span><a class="mo-help" title="'+ cmp_var.choose_cat_tip +'"></a><select multiple="multiple" name="cmp_home_cats['+ nextCell +'][id][]" id="cmp_home_cats['+ nextCell +'][id][]">'+htm3l+'</select></label><label><span>'+ cmp_var.order +'</span><select name="cmp_home_cats['+ nextCell +'][order]" id="cmp_home_cats['+ nextCell +'][order]"><option value="latest" selected="selected">'+ cmp_var.latest +'</option><option value="rand">'+ cmp_var.random +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][number]"><span>'+ cmp_var.number +'</span><input style="width:50px;" id="cmp_home_cats['+ nextCell +'][number]" name="cmp_home_cats['+ nextCell +'][number]" value="6" type="text" /></label><label><span style="float:left; width:200px">'+ cmp_var.bstyle +'</span><ul class="cmp-cats-options cmp-options"><li class="selected"><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="3c" checked="checked"/><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/3c.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="2c1" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/2c1.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="2c" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/2c.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="li1" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/li1.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="li" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/li.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="1c1" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/1c1.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="1c" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/1c.png" /></a></li></ul></label><label><span>'+ cmp_var.thumb +'</span><select name="cmp_home_cats['+ nextCell +'][thumb]" id="cmp_home_cats['+ nextCell +'][thumb]"><option value="n" selected="selected">'+ cmp_var.thumb_n +'</option><option value="t">'+ cmp_var.thumb_t +'</option><option value="a">'+ cmp_var.thumb_a +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][title]"><span>'+ cmp_var.box_title +'</span><input id="cmp_home_cats['+ nextCell +'][title]" name="cmp_home_cats['+ nextCell +'][title]" value="" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][icon]"><span>'+ cmp_var.icon+'</span><input id="cmp_home_cats['+ nextCell +'][icon]" name="cmp_home_cats['+ nextCell +'][icon]" value="fa-list" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_text]"><span>'+ cmp_var.more_text +'</span><input id="cmp_home_cats['+ nextCell +'][more_text]" name="cmp_home_cats['+ nextCell +'][more_text]" value="'+ cmp_var.more_text_detail +'" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_url]"><span>'+ cmp_var.more_url +'</span><input id="cmp_home_cats['+ nextCell +'][more_url]" name="cmp_home_cats['+ nextCell +'][more_url]" value="" type="text" /></label><input id="cmp_home_cats['+ nextCell +'][type]" name="cmp_home_cats['+ nextCell +'][type]" value="n-edd" type="hidden" /><div class="clear"></div><a class="del-cat" title="'+ cmp_var.del_cat +'"></a></div></li>');
		jQuery('#listItem_'+ nextCell).hide().fadeIn();
		nextCell ++ ;
	});
	jQuery("#add-tabs").click(function() {
		jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head"> '+ cmp_var.tabs_box +' <a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div style="display:block" class="widget-content"><label for="cmp_home_cats['+ nextCell +'][who]"><span>'+ cmp_var.who +'</span><select id="cmp_home_cats['+ nextCell +'][who]" name="cmp_home_cats['+ nextCell +'][who]"><option value="anyone">'+ cmp_var.anyone +'</option><option value="logged">'+ cmp_var.logged +'</option><option value="anonymous">'+ cmp_var.anonymous +'</option></select></label><label><span style="float:left;">'+ cmp_var.choose_cat +'</span><a class="mo-help" title="'+ cmp_var.choose_cat_tip +'"></a><select multiple="multiple" name="cmp_home_cats['+ nextCell +'][id][]" id="cmp_home_cats['+ nextCell +'][id][]">'+htm1l+'</select></label><label><span>'+ cmp_var.order +'</span><select name="cmp_home_cats['+ nextCell +'][order]" id="cmp_home_cats['+ nextCell +'][order]"><option value="latest" selected="selected">'+ cmp_var.latest +'</option><option value="rand">'+ cmp_var.random +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][number]"><span>'+ cmp_var.number +'</span><input style="width:50px;" id="cmp_home_cats['+ nextCell +'][number]" name="cmp_home_cats['+ nextCell +'][number]" value="6" type="text" /></label><label><span style="float:left; width:200px">'+ cmp_var.bstyle +'</span><ul class="cmp-cats-options cmp-options"><li class="selected"><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="3c" checked="checked"/><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/3c.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="2c1" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/2c1.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="2c" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/2c.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="li1" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/li1.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="li" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/li.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="1c1" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/1c1.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="1c" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/1c.png" /></a></li></ul></label><label><span>'+ cmp_var.thumb +'</span><select name="cmp_home_cats['+ nextCell +'][thumb]" id="cmp_home_cats['+ nextCell +'][thumb]"><option value="n" selected="selected">'+ cmp_var.thumb_n +'</option><option value="t">'+ cmp_var.thumb_t +'</option><option value="a">'+ cmp_var.thumb_a +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][title]"><span>'+ cmp_var.box_title +'</span><input id="cmp_home_cats['+ nextCell +'][title]" name="cmp_home_cats['+ nextCell +'][title]" value="" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][icon]"><span>'+ cmp_var.icon+'</span><input id="cmp_home_cats['+ nextCell +'][icon]" name="cmp_home_cats['+ nextCell +'][icon]" value="fa-list" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_text]"><span>'+ cmp_var.more_text +'</span><input id="cmp_home_cats['+ nextCell +'][more_text]" name="cmp_home_cats['+ nextCell +'][more_text]" value="'+ cmp_var.more_text_detail +'" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_url]"><span>'+ cmp_var.more_url +'</span><input id="cmp_home_cats['+ nextCell +'][more_url]" name="cmp_home_cats['+ nextCell +'][more_url]" value="" type="text" /></label><input id="cmp_home_cats['+ nextCell +'][type]" name="cmp_home_cats['+ nextCell +'][type]" value="tabs" type="hidden" /><div class="clear"></div><a class="del-cat" title="'+ cmp_var.del_cat +'"></a></div></li>');
		jQuery('#listItem_'+ nextCell).hide().fadeIn();
		nextCell ++ ;
	});
	jQuery("#add-tabs-edd").click(function() {
		jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head"> '+ cmp_var.edd_tabs_box +' <a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div style="display:block" class="widget-content"><label for="cmp_home_cats['+ nextCell +'][who]"><span>'+ cmp_var.who +'</span><select id="cmp_home_cats['+ nextCell +'][who]" name="cmp_home_cats['+ nextCell +'][who]"><option value="anyone">'+ cmp_var.anyone +'</option><option value="logged">'+ cmp_var.logged +'</option><option value="anonymous">'+ cmp_var.anonymous +'</option></select></label><label><span style="float:left;">'+ cmp_var.choose_cat +'</span><a class="mo-help" title="'+ cmp_var.choose_cat_tip +'"></a><select multiple="multiple" name="cmp_home_cats['+ nextCell +'][id][]" id="cmp_home_cats['+ nextCell +'][id][]">'+htm3l+'</select></label><label><span>'+ cmp_var.order +'</span><select name="cmp_home_cats['+ nextCell +'][order]" id="cmp_home_cats['+ nextCell +'][order]"><option value="latest" selected="selected">'+ cmp_var.latest +'</option><option value="rand">'+ cmp_var.random +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][number]"><span>'+ cmp_var.number +'</span><input style="width:50px;" id="cmp_home_cats['+ nextCell +'][number]" name="cmp_home_cats['+ nextCell +'][number]" value="6" type="text" /></label><label><span style="float:left; width:200px">'+ cmp_var.bstyle +'</span><ul class="cmp-cats-options cmp-options"><li class="selected"><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="3c" checked="checked"/><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/3c.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="2c1" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/2c1.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="2c" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/2c.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="li1" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/li1.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="li" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/li.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="1c1" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/1c1.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="1c" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/1c.png" /></a></li></ul></label><label><span>'+ cmp_var.thumb +'</span><select name="cmp_home_cats['+ nextCell +'][thumb]" id="cmp_home_cats['+ nextCell +'][thumb]"><option value="n" selected="selected">'+ cmp_var.thumb_n +'</option><option value="t">'+ cmp_var.thumb_t +'</option><option value="a">'+ cmp_var.thumb_a +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][title]"><span>'+ cmp_var.box_title +'</span><input id="cmp_home_cats['+ nextCell +'][title]" name="cmp_home_cats['+ nextCell +'][title]" value="" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][icon]"><span>'+ cmp_var.icon+'</span><input id="cmp_home_cats['+ nextCell +'][icon]" name="cmp_home_cats['+ nextCell +'][icon]" value="fa-list" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_text]"><span>'+ cmp_var.more_text +'</span><input id="cmp_home_cats['+ nextCell +'][more_text]" name="cmp_home_cats['+ nextCell +'][more_text]" value="'+ cmp_var.more_text_detail +'" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_url]"><span>'+ cmp_var.more_url +'</span><input id="cmp_home_cats['+ nextCell +'][more_url]" name="cmp_home_cats['+ nextCell +'][more_url]" value="" type="text" /></label><input id="cmp_home_cats['+ nextCell +'][type]" name="cmp_home_cats['+ nextCell +'][type]" value="tabs-edd" type="hidden" /><div class="clear"></div><a class="del-cat" title="'+ cmp_var.del_cat +'"></a></div></li>');
		jQuery('#listItem_'+ nextCell).hide().fadeIn();
		nextCell ++ ;
	});
	jQuery("#add-slider").click(function() {
		jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head">'+ cmp_var.scroll_box +' <a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div class="widget-content" style="display:block"><label for="cmp_home_cats['+ nextCell +'][who]"><span>'+ cmp_var.who +'</span><select id="cmp_home_cats['+ nextCell +'][who]" name="cmp_home_cats['+ nextCell +'][who]"><option value="anyone">'+ cmp_var.anyone +'</option><option value="logged">'+ cmp_var.logged +'</option><option value="anonymous">'+ cmp_var.anonymous +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][post_ids]"><span>'+ cmp_var.post_ids +'</span><a class="mo-help" title="'+ cmp_var.post_ids_tip +'"></a><textarea id="cmp_home_cats['+ nextCell +'][post_ids]" name="cmp_home_cats['+ nextCell +'][post_ids]" placeholder="'+ cmp_var.post_ids_tip +'"></textarea></label><label><span style="float:left;">'+ cmp_var.choose_cat +'</span><a class="mo-help" title="'+ cmp_var.choose_cat_tip +'"></a><select multiple="multiple" name="cmp_home_cats['+ nextCell +'][id][]" id="cmp_home_cats['+ nextCell +'][id][]">'+htm1l+'</select></label><label><span>'+ cmp_var.order +'</span><select name="cmp_home_cats['+ nextCell +'][order]" id="cmp_home_cats['+ nextCell +'][order]"><option value="latest" selected="selected">'+ cmp_var.latest +'</option><option value="rand">'+ cmp_var.random +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][number]"><span>'+ cmp_var.number +'</span><input style="width:50px;" id="cmp_home_cats['+ nextCell +'][number]" name="cmp_home_cats['+ nextCell +'][number]" value="12" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][title]"><span>'+ cmp_var.box_title +'</span><input id="cmp_home_cats['+ nextCell +'][title]" name="cmp_home_cats['+ nextCell +'][title]" value="" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][icon]"><span>'+ cmp_var.icon+'</span><input id="cmp_home_cats['+ nextCell +'][icon]" name="cmp_home_cats['+ nextCell +'][icon]" value="fa-list" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_text]"><span>'+ cmp_var.more_text +'</span><input id="cmp_home_cats['+ nextCell +'][more_text]" name="cmp_home_cats['+ nextCell +'][more_text]" value="'+ cmp_var.more_text_detail +'" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_url]"><span>'+ cmp_var.more_url +'</span><input id="cmp_home_cats['+ nextCell +'][more_url]" name="cmp_home_cats['+ nextCell +'][more_url]" value="" type="text" /></label><input id="cmp_home_cats['+ nextCell +'][type]" name="cmp_home_cats['+ nextCell +'][type]" value="s" type="hidden" /><div class="clear"></div><a class="del-cat" title="'+ cmp_var.del_cat +'"></a></div></li>');
		jQuery('#listItem_'+ nextCell).hide().fadeIn();
		nextCell ++ ;
	});
	jQuery("#add-slider-edd").click(function() {
		jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head">'+ cmp_var.edd_scroll_box +' <a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div class="widget-content" style="display:block"><label for="cmp_home_cats['+ nextCell +'][who]"><span>'+ cmp_var.who +'</span><select id="cmp_home_cats['+ nextCell +'][who]" name="cmp_home_cats['+ nextCell +'][who]"><option value="anyone">'+ cmp_var.anyone +'</option><option value="logged">'+ cmp_var.logged +'</option><option value="anonymous">'+ cmp_var.anonymous +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][post_ids]"><span>'+ cmp_var.post_ids +'</span><a class="mo-help" title="'+ cmp_var.post_ids_tip +'"></a><textarea id="cmp_home_cats['+ nextCell +'][post_ids]" name="cmp_home_cats['+ nextCell +'][post_ids]" placeholder="'+ cmp_var.post_ids_tip +'"></textarea></label><label><span style="float:left;">'+ cmp_var.choose_cat +'</span><a class="mo-help" title="'+ cmp_var.choose_cat_tip +'"></a><select multiple="multiple" name="cmp_home_cats['+ nextCell +'][id][]" id="cmp_home_cats['+ nextCell +'][id][]">'+htm3l+'</select></label><label><span>'+ cmp_var.order +'</span><select name="cmp_home_cats['+ nextCell +'][order]" id="cmp_home_cats['+ nextCell +'][order]"><option value="latest" selected="selected">'+ cmp_var.latest +'</option><option value="rand">'+ cmp_var.random +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][number]"><span>'+ cmp_var.number +'</span><input style="width:50px;" id="cmp_home_cats['+ nextCell +'][number]" name="cmp_home_cats['+ nextCell +'][number]" value="12" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][title]"><span>'+ cmp_var.box_title +'</span><input id="cmp_home_cats['+ nextCell +'][title]" name="cmp_home_cats['+ nextCell +'][title]" value="" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][icon]"><span>'+ cmp_var.icon+'</span><input id="cmp_home_cats['+ nextCell +'][icon]" name="cmp_home_cats['+ nextCell +'][icon]" value="fa-list" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_text]"><span>'+ cmp_var.more_text +'</span><input id="cmp_home_cats['+ nextCell +'][more_text]" name="cmp_home_cats['+ nextCell +'][more_text]" value="'+ cmp_var.more_text_detail +'" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_url]"><span>'+ cmp_var.more_url +'</span><input id="cmp_home_cats['+ nextCell +'][more_url]" name="cmp_home_cats['+ nextCell +'][more_url]" value="" type="text" /></label><input id="cmp_home_cats['+ nextCell +'][type]" name="cmp_home_cats['+ nextCell +'][type]" value="s-edd" type="hidden" /><div class="clear"></div><a class="del-cat" title="'+ cmp_var.del_cat +'"></a></div></li>');
		jQuery('#listItem_'+ nextCell).hide().fadeIn();
		nextCell ++ ;
	});
	jQuery("#add-news-picture").click(function() {
		jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head">'+ cmp_var.new_pic +'<a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div class="widget-content" style="display:block"><label for="cmp_home_cats['+ nextCell +'][who]"><span>'+ cmp_var.who +'</span><select id="cmp_home_cats['+ nextCell +'][who]" name="cmp_home_cats['+ nextCell +'][who]"><option value="anyone">'+ cmp_var.anyone +'</option><option value="logged">'+ cmp_var.logged +'</option><option value="anonymous">'+ cmp_var.anonymous +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][post_ids]"><span>'+ cmp_var.post_ids +'</span><a class="mo-help" title="'+ cmp_var.post_ids_tip +'"></a><textarea id="cmp_home_cats['+ nextCell +'][post_ids]" name="cmp_home_cats['+ nextCell +'][post_ids]" placeholder="'+ cmp_var.post_ids_tip +'"></textarea></label><label><span style="float:left;">'+ cmp_var.choose_cat +'</span><a class="mo-help" title="'+ cmp_var.choose_cat_tip +'"></a><select multiple="multiple" name="cmp_home_cats['+ nextCell +'][id][]" id="cmp_home_cats['+ nextCell +'][id][]">'+htm1l+'</select></label><label><span>'+ cmp_var.order +'</span><select name="cmp_home_cats['+ nextCell +'][order]" id="cmp_home_cats['+ nextCell +'][order]"><option value="latest" selected="selected">'+ cmp_var.latest +'</option><option value="rand">'+ cmp_var.random +'</option></select></label><input id="cmp_home_cats['+ nextCell +'][type]" name="cmp_home_cats['+ nextCell +'][type]" value="news-pic" type="hidden" /><label><span style="float:left;">'+ cmp_var.bstyle +'</span><ul class="cmp-cats-options cmp-options"><li class="selected"><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="default" checked="checked"/><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/news-in-pic1.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="row" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/news-in-pic2.png" /></a></li></ul></label><div class="clear"></div><label for="cmp_home_cats['+ nextCell +'][show_title]"><span>'+ cmp_var.show_title +'</span><input id="cmp_home_cats['+ nextCell +'][show_title]" name="cmp_home_cats['+ nextCell +'][show_title]" value="true" type="checkbox"/></label><label for="cmp_home_cats['+ nextCell +'][title]"><span>'+ cmp_var.box_title +'</span><input id="cmp_home_cats['+ nextCell +'][title]" name="cmp_home_cats['+ nextCell +'][title]" value="'+ cmp_var.new_pic +'" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][icon]"><span>'+ cmp_var.icon+'</span><input id="cmp_home_cats['+ nextCell +'][icon]" name="cmp_home_cats['+ nextCell +'][icon]" value="fa-list" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_text]"><span>'+ cmp_var.more_text +'</span><input id="cmp_home_cats['+ nextCell +'][more_text]" name="cmp_home_cats['+ nextCell +'][more_text]" value="'+ cmp_var.more_text_detail +'" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_url]"><span>'+ cmp_var.more_url +'</span><input id="cmp_home_cats['+ nextCell +'][more_url]" name="cmp_home_cats['+ nextCell +'][more_url]" value="" type="text" /></label><div class="clear"></div><a class="del-cat" title="'+ cmp_var.del_cat +'"></a></div></li>');
		jQuery('#listItem_'+ nextCell).hide().fadeIn();
		nextCell ++ ;
	});
	jQuery("#add-news-picture-edd").click(function() {
		jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head">'+ cmp_var.edd_new_pic +'<a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div class="widget-content" style="display:block"><label for="cmp_home_cats['+ nextCell +'][who]"><span>'+ cmp_var.who +'</span><select id="cmp_home_cats['+ nextCell +'][who]" name="cmp_home_cats['+ nextCell +'][who]"><option value="anyone">'+ cmp_var.anyone +'</option><option value="logged">'+ cmp_var.logged +'</option><option value="anonymous">'+ cmp_var.anonymous +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][post_ids]"><span>'+ cmp_var.post_ids +'</span><a class="mo-help" title="'+ cmp_var.post_ids_tip +'"></a><textarea id="cmp_home_cats['+ nextCell +'][post_ids]" name="cmp_home_cats['+ nextCell +'][post_ids]" placeholder="'+ cmp_var.post_ids_tip +'"></textarea></label><label><span style="float:left;">'+ cmp_var.choose_cat +'</span><a class="mo-help" title="'+ cmp_var.choose_cat_tip +'"></a><select multiple="multiple" name="cmp_home_cats['+ nextCell +'][id][]" id="cmp_home_cats['+ nextCell +'][id][]">'+htm3l+'</select></label><label><span>'+ cmp_var.order +'</span><select name="cmp_home_cats['+ nextCell +'][order]" id="cmp_home_cats['+ nextCell +'][order]"><option value="latest" selected="selected">'+ cmp_var.latest +'</option><option value="rand">'+ cmp_var.random +'</option></select></label><input id="cmp_home_cats['+ nextCell +'][type]" name="cmp_home_cats['+ nextCell +'][type]" value="news-pic-edd" type="hidden" /><label><span style="float:left;">'+ cmp_var.bstyle +'</span><ul class="cmp-cats-options cmp-options"><li class="selected"><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="default" checked="checked"/><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/news-in-pic1.png" /></a></li><li><input id="cmp_home_cats['+ nextCell +'][style]" name="cmp_home_cats['+ nextCell +'][style]" type="radio" value="row" /><a class="checkbox-select" href="#"><img src="'+ templatePath +'/panel/images/news-in-pic2.png" /></a></li></ul></label><div class="clear"></div><label for="cmp_home_cats['+ nextCell +'][show_title]"><span>'+ cmp_var.show_title +'</span><input id="cmp_home_cats['+ nextCell +'][show_title]" name="cmp_home_cats['+ nextCell +'][show_title]" value="true" type="checkbox"/></label><label for="cmp_home_cats['+ nextCell +'][title]"><span>'+ cmp_var.box_title +'</span><input id="cmp_home_cats['+ nextCell +'][title]" name="cmp_home_cats['+ nextCell +'][title]" value="'+ cmp_var.new_pic +'" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][icon]"><span>'+ cmp_var.icon+'</span><input id="cmp_home_cats['+ nextCell +'][icon]" name="cmp_home_cats['+ nextCell +'][icon]" value="fa-list" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_text]"><span>'+ cmp_var.more_text +'</span><input id="cmp_home_cats['+ nextCell +'][more_text]" name="cmp_home_cats['+ nextCell +'][more_text]" value="'+ cmp_var.more_text_detail +'" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_url]"><span>'+ cmp_var.more_url +'</span><input id="cmp_home_cats['+ nextCell +'][more_url]" name="cmp_home_cats['+ nextCell +'][more_url]" value="" type="text" /></label><div class="clear"></div><a class="del-cat" title="'+ cmp_var.del_cat +'"></a></div></li>');
		jQuery('#listItem_'+ nextCell).hide().fadeIn();
		nextCell ++ ;
	});
	jQuery("#add-recent").click(function() {
		jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head">'+ cmp_var.rrh_posts +' <a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div style="display:block" class="widget-content"><label for="cmp_home_cats['+ nextCell +'][who]"><span>'+ cmp_var.who +'</span><select id="cmp_home_cats['+ nextCell +'][who]" name="cmp_home_cats['+ nextCell +'][who]"><option value="anyone">'+ cmp_var.anyone +'</option><option value="logged">'+ cmp_var.logged +'</option><option value="anonymous">'+ cmp_var.anonymous +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][post_ids]"><span>'+ cmp_var.post_ids +'</span><a class="mo-help" title="'+ cmp_var.post_ids_tip +'"></a><textarea id="cmp_home_cats['+ nextCell +'][post_ids]" name="cmp_home_cats['+ nextCell +'][post_ids]" placeholder="'+ cmp_var.post_ids_tip +'"></textarea></label><label><span style="float:left;">'+ cmp_var.post_type +' </span><a class="mo-help" title="'+ cmp_var.post_type_tip +'"></a><select multiple="multiple" name="cmp_home_cats['+ nextCell +'][post_type][]" id="cmp_home_cats['+ nextCell +'][post_type][]">'+htm2l+'</select></label><label><span style="float:left;">'+ cmp_var.exclude +'</span><a class="mo-help" title="'+ cmp_var.exclude_tip +'"></a><select multiple="multiple" name="cmp_home_cats['+ nextCell +'][exclude][]" id="cmp_home_cats['+ nextCell +'][exclude][]">'+htm1l+'</select></label><label for="cmp_home_cats['+ nextCell +'][number]"><span>'+ cmp_var.number +'</span><input style="width:50px;" id="cmp_home_cats['+ nextCell +'][number]" name="cmp_home_cats['+ nextCell +'][number]" value="8" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][order]"><span>'+ cmp_var.order +'</span><select id="cmp_home_cats['+ nextCell +'][order]" name="cmp_home_cats['+ nextCell +'][order]"><option value="latest">'+ cmp_var.latest +'</option><option value="modified">'+ cmp_var.modified +'</option><option value="random">'+ cmp_var.random +'</option><option value="stick">'+ cmp_var.stick +'</option><option value="most_comment">'+ cmp_var.most_comment +'</option><option value="most_viewed">'+ cmp_var.most_viewed +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][days]"><span>'+ cmp_var.days +'</span><a class="mo-help" title="'+ cmp_var.days_tip +'"></a><input style="width:50px;" id="cmp_home_cats['+ nextCell +'][days]" name="cmp_home_cats['+ nextCell +'][days]" value="30" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][hours]"><span>'+ cmp_var.hours +'</span><a class="mo-help" title="'+ cmp_var.hours_tip +'"></a><input style="width:50px;" id="cmp_home_cats['+ nextCell +'][hours]" name="cmp_home_cats['+ nextCell +'][hours]" value="24" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][title]"><span>'+ cmp_var.box_title +'</span><input id="cmp_home_cats['+ nextCell +'][title]" name="cmp_home_cats['+ nextCell +'][title]" value="'+ cmp_var.recent +'" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][icon]"><span>'+ cmp_var.icon+'</span><input id="cmp_home_cats['+ nextCell +'][icon]" name="cmp_home_cats['+ nextCell +'][icon]" value="fa-list" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_text]"><span>'+ cmp_var.more_text +'</span><input id="cmp_home_cats['+ nextCell +'][more_text]" name="cmp_home_cats['+ nextCell +'][more_text]" value="'+ cmp_var.more_text_detail +'" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_url]"><span>'+ cmp_var.more_url +'</span><input id="cmp_home_cats['+ nextCell +'][more_url]" name="cmp_home_cats['+ nextCell +'][more_url]" value="" type="text" /></label><input id="cmp_home_cats['+ nextCell +'][type]" name="cmp_home_cats['+ nextCell +'][type]" value="recent" type="hidden" /><div class="clear"></div><a class="del-cat" title="'+ cmp_var.del_cat +'"></a></div></li>');
		jQuery('#listItem_'+ nextCell).hide().fadeIn();
		nextCell ++ ;
	});

	jQuery("#add-divider").click(function() {
		jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head divider">'+ cmp_var.divider +' <a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div class="widget-content" style="display:block"><label style="display:none" for="cmp_home_cats[<?php echo jQueryi ?>][height]"><span>Height :</span><input id="cmp_home_cats['+ nextCell +'][type]" name="cmp_home_cats['+ nextCell +'][type]" value="divider" type="hidden" />  <input id="cmp_home_cats['+ nextCell +'][height]" name="cmp_home_cats['+ nextCell +'][height]" value="10" type="text" style="width:50px;" /> px</label><p>'+ cmp_var.divider_tip +'</p><div class="clear"></div><a class="del-cat" title="'+ cmp_var.del_cat +'"></a></div></li>');
		jQuery('#listItem_'+ nextCell).hide().fadeIn();
		nextCell ++ ;
	});

	jQuery("#add-ads").click(function() {
		jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head">'+ cmp_var.ads +'<a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div class="widget-content" style="display:block"><label for="cmp_home_cats['+ nextCell +'][who]"><span>'+ cmp_var.who +'</span><select id="cmp_home_cats['+ nextCell +'][who]" name="cmp_home_cats['+ nextCell +'][who]"><option value="anyone">'+ cmp_var.anyone +'</option><option value="logged">'+ cmp_var.logged +'</option><option value="anonymous">'+ cmp_var.anonymous +'</option></select></label><textarea name="cmp_home_cats['+ nextCell +'][text]" id="cmp_home_cats['+ nextCell +'][text]"></textarea><input id="cmp_home_cats['+ nextCell +'][type]" name="cmp_home_cats['+ nextCell +'][type]" value="ads" type="hidden" /><div class="clear"></div><a class="del-cat" title="'+ cmp_var.del_cat +'"></a></div></li>');
		jQuery('#listItem_'+ nextCell).hide().fadeIn();
		nextCell ++ ;
	});

	jQuery("#add-users").click(function() {
		jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head">'+ cmp_var.users +'<a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div class="widget-content" style="display:block"><label for="cmp_home_cats['+ nextCell +'][who]"><span>'+ cmp_var.who +'</span><select id="cmp_home_cats['+ nextCell +'][who]" name="cmp_home_cats['+ nextCell +'][who]"><option value="anyone">'+ cmp_var.anyone +'</option><option value="logged">'+ cmp_var.logged +'</option><option value="anonymous">'+ cmp_var.anonymous +'</option></select></label><label for="cmp_home_cats['+ nextCell +'][user]"><span>'+ cmp_var.users +'</span><a class="mo-help" title="'+ cmp_var.users_tip +'"></a><textarea id="cmp_home_cats['+ nextCell +'][user]" name="cmp_home_cats['+ nextCell +'][user]" placeholder="'+ cmp_var.users_tip +'"></textarea></label><label for="cmp_home_cats['+ nextCell +'][title]"><span>'+ cmp_var.box_title +'</span><input id="cmp_home_cats['+ nextCell +'][title]" name="cmp_home_cats['+ nextCell +'][title]" value="" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][icon]"><span>'+ cmp_var.icon+'</span><input id="cmp_home_cats['+ nextCell +'][icon]" name="cmp_home_cats['+ nextCell +'][icon]" value="fa-list" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_text]"><span>'+ cmp_var.more_text +'</span><input id="cmp_home_cats['+ nextCell +'][more_text]" name="cmp_home_cats['+ nextCell +'][more_text]" value="'+ cmp_var.more_text_detail +'" type="text" /></label><label for="cmp_home_cats['+ nextCell +'][more_url]"><span>'+ cmp_var.more_url +'</span><input id="cmp_home_cats['+ nextCell +'][more_url]" name="cmp_home_cats['+ nextCell +'][more_url]" value="" type="text" /></label><input id="cmp_home_cats['+ nextCell +'][type]" name="cmp_home_cats['+ nextCell +'][type]" value="users" type="hidden" /><div class="clear"></div><a class="del-cat" title="'+ cmp_var.del_cat +'"></a></div></li>');
		jQuery('#listItem_'+ nextCell).hide().fadeIn();
		nextCell ++ ;
	});


	jQuery(".toggle-open").live("click" ,function () {
		jQuery(this).parent().parent().find(".widget-content").slideToggle(300);
		jQuery(this).hide();
		jQuery(this).parent().find(".toggle-close").show();
    });

	jQuery(".toggle-close").live("click" ,function () {
		jQuery(this).parent().parent().find(".widget-content").slideToggle("fast");
		jQuery(this).hide();
		jQuery(this).parent().find(".toggle-open").show();
    });


	jQuery("#expand-all").live("click" ,function () {
		jQuery("#cat_sortable .widget-content").slideDown(300);
		jQuery("#cat_sortable .toggle-close").show();
		jQuery("#cat_sortable .toggle-open").hide();
    });
	jQuery("#collapse-all").live("click" ,function () {
		jQuery("#cat_sortable .widget-content").slideUp(300);
		jQuery("#cat_sortable .toggle-close").hide();
		jQuery("#cat_sortable .toggle-open").show();
    });


// Del Cats ##############################################
	jQuery(".del-cat").live("click" , function() {
		jQuery(this).parent().parent().addClass('removered').fadeOut(function() {
			jQuery(this).remove();
		});
	});


// Delete Sidebars Icon ##############################################
	jQuery(".del-sidebar").live("click" , function() {
		var option = jQuery(this).parent().find('input').val();
		jQuery(this).parent().parent().addClass('removered').fadeOut(function() {
			jQuery(this).remove();
			jQuery('#custom-sidebars select').find('option[value="'+option+'"]').remove();

		});
	});


// Delete Custom Text Icon ##############################################
	jQuery(".del-custom-text").live("click" , function() {
		var option = jQuery(this).parent().find('input').val();
		jQuery(this).parent().parent().addClass('removered').fadeOut(function() {
			jQuery(this).remove();
		});
	});


// Sidebar Builder ##############################################
	jQuery("#sidebarAdd").click(function() {
		var SidebarName = jQuery('#sidebarName').val();
		if( SidebarName.length > 0){
			jQuery('#sidebarsList').append('<li><div class="widget-head">'+SidebarName+' <input id="cmp_sidebars" name="cmp_options[sidebars][]" type="hidden" value="'+SidebarName+'" /><a class="del-sidebar"></a></div></li>');
			jQuery('#custom-sidebars select').append('<option value="'+SidebarName+'">'+SidebarName+'</option>');
		}
		jQuery('#sidebarName').val('');

	});


// Custom Breaking News Text ##############################################
	jQuery("#TextAdd").click(function() {
		var customlink = jQuery('#custom_link').val();
		var customtext = jQuery('#custom_text').val();
		if( customtext.length > 0 && customlink.length > 0  ){
			jQuery('#customList').append('<li><div class="widget-head"><a href="'+customlink+'" target="_blank">'+customtext+'</a> <input name="cmp_options[breaking_custom]['+customnext+'][link]" type="hidden" value="'+customlink+'" /> <input name="cmp_options[breaking_custom]['+customnext+'][text]" type="hidden" value="'+customtext+'" /><a class="del-custom-text"></a></div></li>');
		}
		customnext ++ ;
		jQuery('#custom_link , #custom_text').val('');

	});


// Background Type ##############################################
	var bg_selected_radio = jQuery("input[name='cmp_options[background_type]']:checked").val();
	if (bg_selected_radio == 'custom') {	jQuery('#pattern-settings').hide();	}
	if (bg_selected_radio == 'pattern') {	jQuery('#bg_image_settings').hide();	}
	jQuery("input[name='cmp_options[background_type]']").change(function(){
		var bg_selected_radio = jQuery("input[name='cmp_options[background_type]']:checked").val();
		if (bg_selected_radio == 'pattern') {
			jQuery('#pattern-settings').fadeIn();
			jQuery('#bg_image_settings').hide();
		}else{
			jQuery('#bg_image_settings').fadeIn();
			jQuery('#pattern-settings').hide();
		}
	 });




	jQuery('a[rel=tooltip]').mouseover(function(e) {
		var tip = jQuery(this).attr('title');
		jQuery(this).attr('title','');
		jQuery(this).append('<div id="tooltip"><div class="tipHeader"></div><div class="tipBody">' + tip +'</div><div class="tipFooter"></div></div>');

		jQuery('#tooltip').css('top', e.pageY -10 );
		jQuery('#tooltip').css('left', e.pageX - 20 );

		jQuery('#tooltip').fadeIn('500');
		jQuery('#tooltip').fadeTo('10',0.8);

	}).mousemove(function(e) {

		jQuery('#tooltip').css('top', e.pageY -10 );
		jQuery('#tooltip').css('left', e.pageX - 20 );

	}).mouseout(function() {

		jQuery(this).attr('title',jQuery('.tipBody').html());
		jQuery(this).children('div#tooltip').remove();

	});


	jQuery(".tabs-wrap").hide();
	jQuery(".mo-panel-tabs ul li:first").addClass("active").show();
	jQuery(".tabs-wrap:first").show();
	jQuery("li.cmp-tabs").click(function() {
		jQuery(".mo-panel-tabs ul li").removeClass("active");
		jQuery(this).addClass("active");
		jQuery(".tabs-wrap").hide();
		var activeTab = jQuery(this).find("a").attr("href");
		jQuery(activeTab).fadeIn();
		return false;
	});



	jQuery("#theme-skins input:checked").parent().addClass("selected");
	jQuery("#theme-skins .checkbox-select").click(
		function(event) {
			event.preventDefault();
			jQuery("#theme-skins li").removeClass("selected");
			jQuery(this).parent().addClass("selected");
			jQuery(this).parent().find(":radio").attr("checked","checked");
		}
	);


	jQuery("#theme-pattern input:checked").parent().addClass("selected");
	jQuery("#theme-pattern .checkbox-select").click(
		function(event) {
			event.preventDefault();
			jQuery("#theme-pattern li").removeClass("selected");
			jQuery(this).parent().addClass("selected");
			jQuery(this).parent().find(":radio").attr("checked","checked");
		}
	);


	jQuery("#sidebar-position-options input:checked").parent().addClass("selected");
	jQuery("#sidebar-position-options .checkbox-select").click(
		function(event) {
			event.preventDefault();
			jQuery("#sidebar-position-options li").removeClass("selected");
			jQuery(this).parent().addClass("selected");
			jQuery(this).parent().find(":radio").attr("checked","checked");
		}
	);


	jQuery("#footer-widgets-options input:checked").parent().addClass("selected");
	jQuery("#footer-widgets-options .checkbox-select").click(
		function(event) {
			event.preventDefault();
			jQuery("#footer-widgets-options li").removeClass("selected");
			jQuery(this).parent().addClass("selected");
			jQuery(this).parent().find(":radio").attr("checked","checked");
		}
	);


	jQuery(".cmp-cats-options input:checked").parent().addClass("selected");
	jQuery(".cmp-cats-options .checkbox-select").live("click" , function(event) {
		event.preventDefault();
		jQuery(this).parent().parent().find("li").removeClass("selected");
		jQuery(this).parent().addClass("selected");
		jQuery(this).parent().find(":radio").attr("checked","checked");

	});


	jQuery("#tabs_cats input:checked").parent().addClass("selected");
	jQuery("#tabs_cats span").click(
		function(event) {
			event.preventDefault();
			if( jQuery(this).parent().find(":checkbox").is(':checked') ){
				jQuery(this).parent().removeClass("selected");
				jQuery(this).parent().find(":checkbox").removeAttr("checked");
			}else{
				jQuery(this).parent().addClass("selected");
				jQuery(this).parent().find(":checkbox").attr("checked","checked");
			}
		}
	);


});

// image Uploader Functions ##############################################
    function cmp_set_uploader(field, styling ) {
        var cmp_bg_uploader;
        jQuery(document).on("click", "#upload_"+field+"_button" , function( event ){
            event.preventDefault();
            cmp_bg_uploader = wp.media.frames.cmp_bg_uploader = wp.media({
                title: cmp_var.choose_image,
                library: {type: 'image'},
                button: {text: cmp_var.select},
                multiple: false
            });

            cmp_bg_uploader.on( 'select', function() {
                var selection = cmp_bg_uploader.state().get('selection');
                selection.map( function( attachment ) {
                    attachment = attachment.toJSON();
                    
                    if( styling )
                        jQuery('#'+field+'-img').val(attachment.url);
                    else
                        jQuery('#'+field).val(attachment.url);

                    jQuery('#'+field+'-preview').show();
                    jQuery('#'+field+'-preview img').attr("src", attachment.url );
                });
            });
            cmp_bg_uploader.open();
        });
    }

function toggleVisibility(id) {
	var e = document.getElementById(id);
    if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
}

// tipsy, version 1.0.0a
(function(a){function b(a,b){return typeof a=="function"?a.call(b):a}function c(a){while(a=a.parentNode){if(a==document)return true}return false}function d(b,c){this.$element=a(b);this.options=c;this.enabled=true;this.fixTitle()}d.prototype={show:function(){var c=this.getTitle();if(c&&this.enabled){var d=this.tip();d.find(".tipsy-inner")[this.options.html?"html":"text"](c);d[0].className="tipsy";d.remove().css({top:0,left:0,visibility:"hidden",display:"block"}).prependTo(document.body);var e=a.extend({},this.$element.offset(),{width:this.$element[0].offsetWidth,height:this.$element[0].offsetHeight});var f=d[0].offsetWidth,g=d[0].offsetHeight,h=b(this.options.gravity,this.$element[0]);var i;switch(h.charAt(0)){case"n":i={top:e.top+e.height+this.options.offset,left:e.left+e.width/2-f/2};break;case"s":i={top:e.top-g-this.options.offset,left:e.left+e.width/2-f/2};break;case"e":i={top:e.top+e.height/2-g/2,left:e.left-f-this.options.offset};break;case"w":i={top:e.top+e.height/2-g/2,left:e.left+e.width+this.options.offset};break}if(h.length==2){if(h.charAt(1)=="w"){i.left=e.left+e.width/2-15}else{i.left=e.left+e.width/2-f+15}}d.css(i).addClass("tipsy-"+h);d.find(".tipsy-arrow")[0].className="tipsy-arrow tipsy-arrow-"+h.charAt(0);if(this.options.className){d.addClass(b(this.options.className,this.$element[0]))}if(this.options.fade){d.stop().css({opacity:0,display:"block",visibility:"visible"}).animate({opacity:this.options.opacity})}else{d.css({visibility:"visible",opacity:this.options.opacity})}}},hide:function(){if(this.options.fade){this.tip().stop().fadeOut(function(){a(this).remove()})}else{this.tip().remove()}},fixTitle:function(){var a=this.$element;if(a.attr("title")||typeof a.attr("original-title")!="string"){a.attr("original-title",a.attr("title")||"").removeAttr("title")}},getTitle:function(){var a,b=this.$element,c=this.options;this.fixTitle();var a,c=this.options;if(typeof c.title=="string"){a=b.attr(c.title=="title"?"original-title":c.title)}else if(typeof c.title=="function"){a=c.title.call(b[0])}a=(""+a).replace(/(^\s*|\s*$)/,"");return a||c.fallback},tip:function(){if(!this.$tip){this.$tip=a('<div class="tipsy"></div>').html('<div class="tipsy-arrow"></div><div class="tipsy-inner"></div>');this.$tip.data("tipsy-pointee",this.$element[0])}return this.$tip},validate:function(){if(!this.$element[0].parentNode){this.hide();this.$element=null;this.options=null}},enable:function(){this.enabled=true},disable:function(){this.enabled=false},toggleEnabled:function(){this.enabled=!this.enabled}};a.fn.tipsy=function(b){function e(c){var e=a.data(c,"tipsy");if(!e){e=new d(c,a.fn.tipsy.elementOptions(c,b));a.data(c,"tipsy",e)}return e}function f(){var a=e(this);a.hoverState="in";if(b.delayIn==0){a.show()}else{a.fixTitle();setTimeout(function(){if(a.hoverState=="in")a.show()},b.delayIn)}}function g(){var a=e(this);a.hoverState="out";if(b.delayOut==0){a.hide()}else{setTimeout(function(){if(a.hoverState=="out")a.hide()},b.delayOut)}}if(b===true){return this.data("tipsy")}else if(typeof b=="string"){var c=this.data("tipsy");if(c)c[b]();return this}b=a.extend({},a.fn.tipsy.defaults,b);if(!b.live)this.each(function(){e(this)});if(b.trigger!="manual"){var h=b.live?"live":"bind",i=b.trigger=="hover"?"mouseenter":"focus",j=b.trigger=="hover"?"mouseleave":"blur";this[h](i,f)[h](j,g)}return this};a.fn.tipsy.defaults={className:null,delayIn:0,delayOut:0,fade:false,fallback:"",gravity:"n",html:false,live:false,offset:0,opacity:.8,title:"title",trigger:"hover"};a.fn.tipsy.revalidate=function(){a(".tipsy").each(function(){var b=a.data(this,"tipsy-pointee");if(!b||!c(b)){a(this).remove()}})};a.fn.tipsy.elementOptions=function(b,c){return a.metadata?a.extend({},c,a(b).metadata()):c};a.fn.tipsy.autoNS=function(){return a(this).offset().top>a(document).scrollTop()+a(window).height()/2?"s":"n"};a.fn.tipsy.autoWE=function(){return a(this).offset().left>a(document).scrollLeft()+a(window).width()/2?"e":"w"};a.fn.tipsy.autoBounds=function(b,c){return function(){var d={ns:c[0],ew:c.length>1?c[1]:false},e=a(document).scrollTop()+b,f=a(document).scrollLeft()+b,g=a(this);if(g.offset().top<e)d.ns="n";if(g.offset().left<f)d.ew="w";if(a(window).width()+a(document).scrollLeft()-g.offset().left<b)d.ew="e";if(a(window).height()+a(document).scrollTop()-g.offset().top<b)d.ns="s";return d.ns+(d.ew?d.ew:"")}}})(jQuery)