<?php
function pp_int_export() {
	echo '<xml version="1.0" encoding="utf-8">';
	echo PHP_EOL, '<', pp_int_name(), 'er>';
	foreach ( pp_int_users() as $interessent ) {
		$interessent->tlf = get_user_meta( $interessent->ID, pp_tel_meta(), true );
		echo PHP_EOL, ' <', pp_int_name(),
			' id="', $interessent->user_nicename, '"',
			' user_email="', $interessent->user_email, '"',
			$interessent->tlf      ? ' ' . pp_tel_meta() . '="' . $interessent->tlf      . '"' : '',
			$interessent->user_url ? ' user_url="'             . $interessent->user_url . '"' : '',
			' user_registered="', $interessent->user_registered,
		'">';
		echo PHP_EOL, '  <first_name>'  , $interessent->first_name  , '</first_name>';
		echo PHP_EOL, '  <last_name>'   , $interessent->last_name   , '</last_name>';
		echo PHP_EOL, '  <display_name>', $interessent->display_name, '</display_name>';
		echo PHP_EOL, '  <', pp_kom_tax(), 'r>';
		$kommuner = explode( PP_KOM_DELIM, get_user_meta( $interessent->ID, pp_pro_kom_meta(), true ) );
		foreach ( $kommuner as $kommune ) {
			$term = get_term_by( 'name', $kommune, pp_kom_tax() );
			echo PHP_EOL, '   <', pp_kom_tax(), ' id="', $term->slug, '">', $kommune, '</', pp_kom_tax(), '>';
		}
		echo PHP_EOL, '  </', pp_kom_tax(), 'r>';
		echo PHP_EOL, ' </', pp_int_name(), '>';
	}
	echo PHP_EOL, '</', pp_int_name(), 'er>';
}

require( '../../../../wp-load.php' );
$search = esc_attr( $_REQUEST[ pp_kom_tax() ] );
$slug = get_term_by( 'name', $search, pp_kom_tax() )->slug;
header( 'Content-Type: application/xml; charset=utf-8' );
header( 'Content-Disposition: attachment; filename=' . pp_int_name() . '-' . ( $search ? $slug : 'alle' ) . '-' . date( 'Y-m-d' ) . '.xml' );
pp_int_export();
?>