jQuery(document).ready(function($){
	jQuery('.widget .fylke>a').click(function(evt){
		var clicked = jQuery(this).attr('rel');
		jQuery('.widget .fylke .kommune').hide();
		jQuery('.widget .fylke #fylke-' + clicked).fadeIn(100);
		evt.preventDefault();
	})
})
