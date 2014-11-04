<?php
// Custom post types and taxonomies definitions for global use

function pp_sites( &$src ) {
	$sites = false;
	if ( false !== $src && true !== $src && 'fresh' !== $src && 'admin' !== $src )
		$sites = get_site_transient( PP_SITES_TRANS );	// Network transient
	if ( false === $sites || ! is_array( $sites ) || 0 == count( $sites ) ) {
		$args = array(
			'archived' => 0,
			'spam'     => 0,
			'deleted'  => 0,
		);
		if ( ! WP_DEBUG )
			$args['public'] = 1;
		if ( ! is_admin() && 'admin' !== $src )
			$args['mature'] = ( true === $src ? 1 : 0 );
		$site_ids = array_map( 'intval', wp_list_pluck( wp_get_sites( $args ), 'blog_id' ) );
		$sites = array();
		foreach ( $site_ids as $site_id )
			$sites[ $site_id ] = get_blog_details( $site_id );
		if ( ! is_admin() && true !== $src && 'admin' !== $src )
			set_site_transient( PP_SITES_TRANS, $sites, PP_SITES_TRANS_EXP );
		$src = 'fresh';
	} else
		$src = 'trans';
	return $sites;
}

function pp_kom_tax() {
	if     ( 3 == get_current_blog_id() )
		return 'prosjektkommune';
	elseif ( 5 == get_current_blog_id() )
		return 'fylke';
	else
		return 'kommune';
}

function pp_head_term() {
	return 'sidehode';
}

function pp_side_term() {
	return 'sidestolpe';
}

function pp_innh_tax() {
	return 'innhold';
}

function pp_aktt_tax() {
	return 'aktivitetstype';
}

function pp_apos_tax() {
	return 'annonseplass';
}

function pp_alev_tax() {
	return 'annonseleverandor';
}

function pp_pkat_tax() {
	return 'produktkategori';
}

function pp_forh_tax() {
	return 'forhandler';
}

function pp_ann_type() {
	return 'annonser';
}

function pp_lev_type() {
	return 'leverandor';
}

function pp_pro_type() {
	return 'prosjekt';
}

function pp_uke_type() {
	return 'ukemeny';
}

function pp_opp_type() {
	return 'oppskrift';
}

function pp_akt_type() {
	return 'aktivitet';
}

function pp_int_name() {
	return 'interessent';
}

function pp_prd_type() {
	return 'produkt';
}

function pp_cpts() {
	return array( pp_lev_type(), pp_pro_type(), pp_uke_type(), pp_opp_type(), pp_akt_type(), pp_prd_type() );
}

function pp_acpt() {
	return array_merge( pp_cpts(), array( pp_ann_type() ) );
}

function pp_tel_meta() {
	return 'pp_telefon';
}
function pp_pro_kom_meta() {
	return 'pp_' . pp_kom_tax();
}

function pp_pro_fyl_meta() {
	return 'pp_' . pp_kom_tax() . '_fylke';
}

function pp_jqscripts() {
	return array(
		'functions'        => range( 1, 10 ),
		'ansatte'          => range( 1, 10 ),
		'bbpress-localize' => array( 2, 7, 9 ),
		'kommuner-widget'  => array( 4, 7 ),
		'qpicker-localize' => array( 5 ),
		'produkter-widget' => array( 8 )
	);
}
