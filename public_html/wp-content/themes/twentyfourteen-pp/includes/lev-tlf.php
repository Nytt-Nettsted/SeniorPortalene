<?php
function pp_lev_tlf() {
	global $wpdb;
	switch_to_blog( 4 );
	$flevs = get_posts( array( 'posts_per_page' => -1, 'post_type' => pp_lev_type() ) );
	$fpt = $wpdb->posts;
	restore_current_blog();
	switch_to_blog( 7 );
	echo '<pre>';
	foreach( $flevs as $flev ) {
		//$tlf = get_post_meta( $flev->ID, 'tlf', true );
		if ( true || $tlf ) {
			$bpt = $wpdb->posts;
			wp_cache_flush();
			//$blevs = get_posts( array( 'posts_per_page' => 1, 'post_type' => pp_lev_type(), 'name' => $flev->post_name ) );
			$blev = $wpdb->get_row( "SELECT * FROM $wpdb->posts WHERE post_name='{$flev->post_name}' LIMIT 1" );
			if ( count( $blev ) ) {
				echo PHP_EOL, $fpt, ' ', $flev->guid, ' ', $bpt, ' ', $blev->guid;
			}
		}
	}
	echo '</pre>';
	restore_current_blog();
	switch_to_blog( 7 );
}

if ( is_admin() && 7 == get_current_blog_id() )
	add_management_page( 'Telefonkopiering', 'Telefonkopiering', 'publish_posts', 'lev-tlf', 'pp_lev_tlf' );