var showing;
jQuery(document).ready(function($){
	jQuery('.pointer').click(function(evt){
		var clicked = jQuery(this).attr('rel');
		if ( showing ) {
			jQuery('.ansatt-' + clicked).fadeOut(100);
			jQuery('.vismin.ansatt-' + clicked).fadeOut(100);
			jQuery('.vismer.ansatt-' + clicked).fadeIn(100);
			showing = false;
		} else {
			jQuery('.ansatt-' + clicked).fadeIn(100);
			jQuery('.vismin.ansatt-' + clicked).fadeIn(100);
			jQuery('.vismer.ansatt-' + clicked).fadeOut(100);
			showing = true;
		}
		evt.preventDefault();
	})
})
