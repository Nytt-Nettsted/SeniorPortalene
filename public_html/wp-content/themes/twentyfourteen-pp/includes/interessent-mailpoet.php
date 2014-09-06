<?php
function pp_int_export() {
	echo 'Email, First Name, Last Name', "\r\n";
	foreach ( pp_int_users() as $interessent ) {
		echo $interessent->user_email, ', ', $interessent->first_name, ', ', $interessent->last_name, "\r\n";
	}
}

require( '../../../../wp-load.php' );
$search = esc_attr( $_REQUEST[ pp_kom_tax() ] );
$slug = get_term_by( 'name', $search, pp_kom_tax() )->slug;
header( 'Content-Type: text/csv; charset=utf-8' );
header( 'Content-Disposition: attachment; filename=' . pp_int_name() . '-mailpoet-' . ( $search ? $slug : 'alle' ) . '-' . date( 'Y-m-d' ) . '.csv' );
pp_int_export();
?>