jQuery(document).ready(function($){
	jQuery('#bbpress-forums .forum-titles .bbp-forum-freshness').html('Oppdatert');

	jQuery('#bbp_search_submit').attr('value','Søk');
	jQuery('.bbp-search .entry-title').html('Søk');
	jQuery('.bbp-search .bbp-breadcrumb-current').html('Søk');

	jQuery('.bbp-user-page #bbp-user-profile .entry-title').html('Profil');
	jQuery('.bbp-user-page .bbp-user-profile-link a').html('Profil');
	jQuery('.bbp-user-page .bbp-user-replies-created-link a').html('Svar skrevet');

	var obj = jQuery('.bbp-user-page .bbp-user-forum-role');
	if (obj.html()){
		var string = obj.html().replace('Forum Role','Rolle');
		jQuery('.bbp-user-page .bbp-user-forum-role').html(string);
	}

	var obj = jQuery('.bbp-user-profile .bbp-user-topic-count');
	if(obj.html()){
		var string = obj.html().replace('Topics Started','Emner startet');
		jQuery('.bbp-user-profile .bbp-user-topic-count').html(string);
	}

	var obj = jQuery('.bbp-user-profile .bbp-user-reply-count');
	if(obj.html()){
		var string = obj.html().replace('Replies Created','Svar skrevet');
		jQuery('.bbp-user-profile .bbp-user-reply-count').html(string);
	}

	var obj = jQuery('#bbp-single-user-details .bbp-user-replies-created-link a');
	if(obj.html()){
		var string = obj.html().replace('Replies Created','Svar skrevet');
		jQuery('#bbp-single-user-details .bbp-user-replies-created-link a').html(string);
	}

	var obj = jQuery('.bbp-user-profile .bbp-user-replies-created-link a');
	if(obj.html()){
		var string = obj.html().replace('Replies Created','Svar skrevet');
		jQuery('.bbp-user-profile .bbp-user-replies-created-link a').html(string);
	}

	var obj = jQuery('#bbp-your-profile label[for="display_name"]');
	if(obj.html()){
		var string = obj.html().replace('Display Name','Visnings&shy;navn');
		jQuery('#bbp-your-profile label[for="display_name"]').html(string);
	}

	var obj = jQuery('c');
	if(obj.html()){
		var string = obj.html().replace('Replies Created','Svar skrevet');
		jQuery('#bbp-single-user-details .bbp-user-replies-created-link a').html(string);
	}
	var obj = jQuery('#bbp-single-user-details .bbp-user-profile-link a');
	if(obj.html()){
		var string = obj.html().replace('Profile','Profil');
		jQuery('#bbp-single-user-details .bbp-user-profile-link a').html(string);
	}

	var obj = jQuery('.forum-archive .entry-title');
	if(obj.html()){
		var string = obj.html().replace('Forum','Diskusjonsforum');
		jQuery('.forum-archive .entry-title').html(string);
	}

	for( var c = 1; c < 6; ++c ) {
		var obj = jQuery('.widget_display_topics li:nth-child('+c+')');
		if(obj.html()) {
			var string = obj.html().replace('by','av');
			jQuery('.widget_display_topics li:nth-child('+c+')').html(string);
		}
	}
})
