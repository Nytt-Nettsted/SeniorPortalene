<?php
// Helper functions (library)

function pp_theme_version() {
	return wp_get_theme()->Version;
}

function pp_shorten( $long, $delim = ' ', $length = 25 ) {
	$long = trim( $long );
	$delip = strpos( $long, $delim, $length );
	return $delip ? substr( $long, 0 , $delip + 1 ) . ( $delip + 1 < strlen( $long ) ? '&hellip;' : '' ) : $long;
}

function str_lreplace( $search, $replace, $subject ) {
    $pos = strrpos( $subject, $search );
    if ( $pos !== false )
        $subject = substr_replace( $subject, $replace, $pos, strlen( $search ) );
    return $subject;
}

function mb_str_replace( $search, $replace, $subject, &$count = 0 ) {
	if ( is_array( $subject ) )
		foreach ( $subject as $key => $value )
			$subject[ $key ] = mb_str_replace( $search, $replace, $value, $count );
	else {
		$searches     = is_array ( $search  ) ? array_values( $search  ) : array( $search  );
		$replacements = is_array ( $replace ) ? array_values( $replace ) : array( $replace );
		$replacements = array_pad( $replacements, count( $searches ), '' );
		foreach ( $searches as $key => $search ) {
			$parts   = mb_split( preg_quote( $search ), $subject );
			$count  += count( $parts ) - 1;
			$subject = implode( $replacements[ $key ], $parts );
		}
	}
	return $subject;
}

function pp_hide_text( $text, $id ) {
	$ps = explode( '</p>' . PHP_EOL . '<p>', $text );
	$text = '';
	$n = count( $ps );
	foreach ( $ps as $i => $p ) {
		$text .= $p;
		if ( $n > 1 && $i == 0 )
			$text .= '&nbsp; <a class="pointer vismer ansatt-' . $id . '" rel="' . $id . '">Vis mer</a>';
		if ( $n > 1 && $i == $n - 1 )
			$text .= '<p class="vismin ansatt-' . $id . ' hidden"><a class="pointer" rel="' . $id . '">Vis mindre</a></p>';
		if ( $i < $n - 1 )
			$text .= '</p>' . PHP_EOL . '<p class="ansatt-' . $id . ' hidden">';
	}
	return $text;
}

function pp_reg_jqs( $scripts ) {
	foreach ( array_keys( $scripts ) as $script )
		wp_register_script( $script, get_stylesheet_directory_uri() . '/js/' . $script . '.js', array( 'jquery' ), pp_theme_version(), true );
}

function pp_add_jqscripts() {
	wp_dequeue_script( 'twentyfourteen-script' );	// js/functions.js
	foreach ( pp_jqscripts() as $script => $sites )
		if ( in_array ( get_current_blog_id(), $sites ) )
			wp_enqueue_script( $script );
}

function pp_get_current_post_type() {
	global $post, $typenow, $current_screen;
	if ( $post && $post->post_type )
		return $post->post_type;
	elseif( $typenow )
		return $typenow;
	elseif( $current_screen && $current_screen->post_type )
		return $current_screen->post_type;
	elseif( isset( $_REQUEST['post_type'] ) )
		return sanitize_key( $_REQUEST['post_type'] );
	elseif ( isset( $_GET['post'] ) )
		return get_post_type( intval( $_GET['post'] ) );
	return null;
}

function count_user_posts_by_type( $userid, $post_type = 'post' ) {
	global $wpdb;
	$where = get_posts_by_author_sql( $post_type, true, $userid );
	$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );
  	return apply_filters( 'get_usernumposts', $count, $userid );
}

function shuffle_assoc( $array ) {
	$shuffled_array = array();
	$shuffled_keys  = array_keys( $array );
	shuffle( $shuffled_keys );
	foreach ( $shuffled_keys as $shuffled_key )
		$shuffled_array[ $shuffled_key ] = $array[ $shuffled_key ];
	return $shuffled_array;
}
