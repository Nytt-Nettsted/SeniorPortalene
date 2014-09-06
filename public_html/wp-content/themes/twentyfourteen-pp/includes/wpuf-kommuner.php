<?php
function pp_wpuf_kommuner( $form_id, $post_id, $form_settings ) {
	global $user_ID;
?>
<script>
jQuery(document).ready(function($){
	jQuery('.wpuf-form .wpuf-el .wpuf-fields > li > label').click(function(evt){
		var fylke = jQuery(this).attr('rel');
		jQuery('.fylke').hide();
		jQuery('#fylke-' + fylke).fadeIn(100);
		evt.preventDefault();
	})
})
</script>
<?php
	$fylker = get_terms( pp_kom_tax(), array( 'parent' => 0, 'hide_empty' => false ) );
	echo PHP_EOL, '<ul class="wpuf-fields" style="list-style-type: none;">';
	foreach ( $fylker as $fylke ) {
		echo PHP_EOL, ' <li><label rel="', $fylke->slug, '" style="cursor: pointer;">', $fylke->name, '</label>';
		echo PHP_EOL, '  <ul class="fylke" id="fylke-', $fylke->slug, '" style="display: none; list-style-type: none;">';
		$terms = get_terms( pp_kom_tax(), array( 'parent' => $fylke->term_id, 'hide_empty' => false ) );
		foreach( $terms as $term ) {
			$checked = strpos( get_user_meta( $user_ID, pp_pro_kom_meta(), true ), $term->name ) !== false ? ' checked="checked"' : '';
			echo PHP_EOL, '   <li class="kommune"><label><input type="checkbox" name="kommune[]" value="', $term->name, '"', $checked, ' /> ', $term->name, '</label></li>';
		}
		echo PHP_EOL, '  </ul></li>';
	}
	echo PHP_EOL, '</ul>';
}
/**
 * Update the custom field when the form submits
 *
 * @param type $post_id
 */
function pp_wpuf_update_kommune( $user_id, $userdata, $form_id, $form_settings ) {
    if ( isset( $_POST['kommune'] ) ) {
		if ( is_array( $_POST['kommune'] ) ) {
			$kommuner = $_POST['kommune'];
			update_user_meta( $user_id, pp_pro_kom_meta(), esc_attr( implode( PP_KOM_DELIM, $_POST['kommune'] ) ) );
		} else {
			$kommuner = array( $_POST['kommune'] );
			update_user_meta( $user_id, pp_pro_kom_meta(), esc_attr( $_POST['kommune'] ) );
		}
    }
	$fylker = array();
	foreach ( $kommuner as $kommune ) {
		$kom = get_term_by( 'name', esc_attr( $kommune ), pp_kom_tax() );
		if ( $kom ) {
			$fylke = get_term_by( 'id', $kom->parent, pp_kom_tax() );
			if ( $fylke )
				$fylker[] = $fylke->name;
		}
	}
	if ( count( $fylker ) )
		update_user_meta( $user_id, pp_pro_fyl_meta(), implode( PP_KOM_DELIM, array_unique( $fylker ) ) );

}

function pp_uf_add_aktivitet( $post_id ) {
	if ( 5 == current_bog_id() && pp_akt_type() == get_post_type( $post_id ) )
		pp_save_aktivitet( $post_id, null, false );
}

function pp_uf_edit_aktivitet( $post_id ) {
	if ( 5 == current_bog_id() && pp_akt_type() == get_post_type( $post_id ) )
		pp_save_aktivitet( $post_id, null, true );
}

function pp_wpuf_bpa_fylker( $form_id, $post_id, $form_settings ) {
	add_filter( 'get_terms_args', 'pp_get_terms_args', 10, 2 );
}

add_action( 'pp_wpuf_kommuner', 'pp_wpuf_kommuner', 10, 3 );
add_action( 'wpuf_after_register', 'pp_wpuf_update_kommune', 10, 4 );
add_action( 'wpuf_update_profile', 'pp_wpuf_update_kommune', 10, 3 );
//add_action( 'wpuf_add_post_after_insert', 'pp_uf_add_aktivitet', 10, 1 );
//add_action( 'wpuf_edit_post_after_update', 'pp_uf_edit_aktivitet', 10, 1 );
add_action( 'pp_wpuf_bpa_kom', 'pp_wpuf_bpa_fylker', 10, 3 );

// Test:


function pp_akt_image( $form_id, $post_id, $settings ) {
?>
<script>
jQuery(document).ready(function($){
	$('#image-selection').change(function(){
		var selectedIndex = $('#image-selection')[0].selectedIndex;

		$('ul#images-list li').each(function(index) {
			if ( index === selectedIndex ) { jQuery(this).show(); }
			else { jQuery(this).hide(); }
		});
		var featuredImage = $('.wpuf-el.featured_image');
		if ( selectedIndex == 0 ) {
			featuredImage.show();
		} else {
			featuredImage.hide();
		}
	});
	$('.wpuf-el').has('#n-ukedag').hide();
	$('.wpuf-el.periode .wpuf-fields input[name="periode"]').change(function(){
		var radioButtons = $('input:radio[name="periode"]');
		var radioIndex = radioButtons.index(radioButtons.filter(':checked'));
		if ( radioIndex == 0 ) {
			$('.wpuf-el').has('#n-ukedag').hide();
			$('.wpuf-el.ukedager').hide();
		} else if ( radioIndex == 1 ) {
			$('.wpuf-el').has('#n-ukedag').hide();
			$('.wpuf-el.ukedager').show();
		} else if ( radioIndex == 2 ) {
			$('.wpuf-el').has('#n-ukedag').hide();
			$('.wpuf-el.ukedager').hide();
		} else if ( radioIndex == 3 ) {
			$('.wpuf-el').has('#n-ukedag').show();
			$('.wpuf-el.ukedager').hide();
		}
	});
});
</script>
<?php
	$images = get_attached_media( 'image', 1137 );
	echo PHP_EOL, '<div class="wpuf-label"><label for="image-selection">Enten: Velg bilde fra galleri</label></div>';
	echo PHP_EOL, '<div class="wpuf-fields">';
	echo PHP_EOL, '<select id="image-selection" name="lib-image">';
	echo PHP_EOL, '<option>-- velg --</option>';
	foreach ( $images as $image ) {
		echo PHP_EOL, '<option value="', $image->ID, '">', $image->post_title, '</option>';

	}
	echo PHP_EOL, '</select>';
	echo PHP_EOL, '<ul id="images-list" style="list-style-type: none;">';
	echo '<li></li>';
	foreach ( $images as $image ) {
		echo PHP_EOL, '<li style="display: none;">', wp_get_attachment_image( $image->ID ), '</li>';
	}
	echo PHP_EOL, '</ul>';
	echo PHP_EOL, '<span class="wpuf-help">Ditt valgte bilde vises. Velg «--velg--» ovenfor for å laste opp eget bilde.</span>';
	echo PHP_EOL, '</div>';
}

add_action( 'akt_image', 'pp_akt_image', 10, 3 );

function pp_akt_ukedag( $form_id, $post_id, $settings ) {
	echo PHP_EOL, '<div class="wpuf-label"><label for="n-ukedag">Ukedag</label></div>';
	echo PHP_EOL, '<div class="wpuf-fields">';
	echo PHP_EOL, '<select id="n-ukedag" name="n_ukedag" style="min-width: 0; width: 2.8em;">';
	echo PHP_EOL, '<option value="1">1.</option>';
	echo PHP_EOL, '<option value="2">2.</option>';
	echo PHP_EOL, '<option value="3">3.</option>';
	echo PHP_EOL, '</select>';
	echo PHP_EOL, '<select id="ukedag" name="ukedag" style="min-width: 0; width: auto">';
	echo PHP_EOL, '<option>-- velg --</option>';
	echo PHP_EOL, '<option value="mandag">mandag</option>';
	echo PHP_EOL, '<option value="tirsdag">tirsdag</option>';
	echo PHP_EOL, '<option value="onsdag">onsdag</option>';
	echo PHP_EOL, '<option value="torsdag">torsdag</option>';
	echo PHP_EOL, '<option value="fredag">fredag</option>';
	echo PHP_EOL, '<option value="lørdag">lørdag</option>';
	echo PHP_EOL, '<option value="søndag">søndag</option>';
	echo PHP_EOL, '</select> i måneden';
//	echo PHP_EOL, '<span class="wpuf-help">Hjelpetekst</span>';
	echo PHP_EOL, '</div>';
}

add_action( 'akt_ukedag', 'pp_akt_ukedag', 10, 3 );