<?php
function pp_int_export() {
	foreach ( pp_int_users() as $interessent ) {
		$kommuner = get_user_meta( $interessent->ID, pp_pro_kom_meta(), true );
		$fylke    = get_user_meta( $interessent->ID, pp_pro_fyl_meta(), true );
		echo '"', $interessent->display_name, '","', $interessent->user_email, '","', $interessent->user_registered, '","', get_user_meta( $interessent->ID, pp_tel_meta(), true ), '","', $fylke, '","', $kommuner, '"', "\r\n";
	}
}

require( '../../../../wp-load.php' );
$search = esc_attr( $_REQUEST[ pp_kom_tax() ] );
$slug = get_term_by( 'name', $search, pp_kom_tax() )->slug;
header( 'Content-Type: text/csv; charset=utf-8' );
header( 'Content-Disposition: attachment; filename=' . pp_int_name() . '-google-' . ( $search ? $slug : 'alle' ) . '-' . date( 'Y-m-d' ) . '.csv' );
pp_int_export();
?>