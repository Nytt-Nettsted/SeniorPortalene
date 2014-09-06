jQuery(document).ready(function($){
	jQuery('.widget .produktkategori>a').click(function(evt){
		var clicked = jQuery(this).attr('rel');
		jQuery('.widget .produktkategori .underkategori').hide();
		jQuery('.widget .produktkategori #pkat-' + clicked).fadeIn(100);
		evt.preventDefault();
	})
})
