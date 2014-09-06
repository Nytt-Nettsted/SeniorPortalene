<?php
define( 'WPUA_URL', plugin_dir_url( WP_PLUGIN_DIR . '/wp-user-avatar/wp-user-avatar.php' ) );

global $avatar_default,
       $show_avatars,
       $wpua_avatar_default,
       $wpua_disable_gravatar,
       $wpua_edit_avatar,
       $mustache_original,
       $mustache_medium,
       $mustache_thumbnail,
       $mustache_avatar,
       $mustache_admin,
       $all_sizes;

$avatar_default        = get_option( 'avatar_default' );
$wpua_avatar_default   = get_blog_option( BLOG_ID_CURRENT_SITE, 'avatar_default_wp_user_avatar' );
$show_avatars          = get_option( 'show_avatars' );
$wpua_disable_gravatar = get_blog_option( BLOG_ID_CURRENT_SITE, 'wp_user_avatar_disable_gravatar' );
$mustache_original     = WPUA_URL . 'images/wpua.png';
$mustache_medium       = WPUA_URL . 'images/wpua-300x300.png';
$mustache_thumbnail    = WPUA_URL . 'images/wpua-150x150.png';
$mustache_avatar       = WPUA_URL . 'images/wpua-96x96.png';
$mustache_admin        = WPUA_URL . 'images/wpua-32x32.png';
$all_sizes = array_merge( get_intermediate_image_sizes(), array( 'original' ) );

function pp_has_gravatar( $id_or_email = '', $has_gravatar = 0, $user = '', $email = '' ) {
	if ( ! is_object( $id_or_email ) && ! empty( $id_or_email ) ) {
		$user = is_numeric( $id_or_email ) ? get_user_by( 'id', $id_or_email ) : get_user_by( 'email', $id_or_email) ;
		$email = ! empty( $user ) ? $user->user_email : '';
	}
	$hash = md5( strtolower( trim( $email ) ) );
	$gravatar = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
	$data = wp_cache_get( $hash );
	if ( false === $data ) {
		$response = wp_remote_head( $gravatar );
		$data = is_wp_error( $response ) ? 'not200' : $response['response']['code'];
		wp_cache_set( $hash, $data, $group = '', $expire = 60 * 5 );
	}
	return ( $data == '200' );
}

function pp_get_attachment_image_src( $attachment_id, $size = 'thumbnail', $icon = 0 ) {
	switch_to_blog( BLOG_ID_CURRENT_SITE );
	$image_src_array = wp_get_attachment_image_src( $attachment_id, $size, $icon );
	restore_current_blog();
	return $image_src_array;
}


function pp_has_user_avatar( $id_or_email='', $has_wpua = false, $user = '', $user_id = 0) {
	global $wpdb;
	if ( ! is_object( $id_or_email ) && ! empty( $id_or_email ) ) {
		$user = is_numeric( $id_or_email ) ? get_user_by( 'id', $id_or_email ) : get_user_by( 'email', $id_or_email );
		$user_id = empty( $user ) ? 0 : $user->ID;
	}
	$ua = get_user_meta( $user_id, $wpdb->get_blog_prefix( BLOG_ID_CURRENT_SITE ) . 'user_avatar', true );
	return ! empty( $ua );
}

function pp_get_user_avatar( $id_or_email = '', $size = '96', $align = '', $alt = '' ) {
	global $all_sizes, $avatar_default, $blog_id, $post, $wpdb, $wpua_avatar_default, $_wp_additional_image_sizes;
	$email = 'unknown@gravatar.com';
	if ( is_object( $id_or_email ) ) {
		if( $id_or_email->user_id != 0) {
			$email = $id_or_email->user_id;
		} elseif ( ! empty( $id_or_email->comment_author_email ) ) {
			$user = get_user_by( 'email', $id_or_email->comment_author_email );
			$email = ! empty( $user ) ? $user->ID : $id_or_email->comment_author_email;
		}
		$alt = $id_or_email->comment_author;
	} else {
		if( ! empty( $id_or_email ) ) {
			$user = is_numeric( $id_or_email ) ? get_user_by( 'id', $id_or_email ) : get_user_by( 'email', $id_or_email );
		} else {
			$author_name = get_query_var( 'author_name' );
			if ( is_author() ) {
				$user = get_user_by( 'slug', $author_name );
			} else {
				$user_id = get_the_author_meta( 'ID' );
				$user = get_user_by( 'id', $user_id );
			}
		}
		if ( ! empty( $user ) ) {
			$email = $user->ID;
			$alt = $user->display_name;
		}
	}
	$wpua_meta = get_the_author_meta( $wpdb->get_blog_prefix( BLOG_ID_CURRENT_SITE ) . 'user_avatar', $email );
	$alignclass = ! empty( $align ) && ( $align == 'left' || $align == 'right' || $align == 'center') ? ' align' . $align : ' alignnone';
	if ( ! empty( $wpua_meta ) ) {
		$get_size = is_numeric( $size ) ? array( $size, $size ) : $size;
		$wpua_image = pp_get_attachment_image_src( $wpua_meta, $get_size );
		$dimensions = is_numeric( $size ) ? ' width="' . $wpua_image[1] . '" height="' . $wpua_image[2] . '"' : '';
		$avatar = '<img src="' . $wpua_image[0].'"'.$dimensions.' alt="'.$alt.'" class="avatar avatar-'.$size.' wp-user-avatar wp-user-avatar-'.$size.$alignclass.' photo" />';
	} else {
		if ( in_array( $size, $all_sizes ) ) {
			if ( in_array( $size, array( 'original', 'large', 'medium', 'thumbnail' ) ) ) {
				$get_size = ( $size == 'original' ) ? get_option( 'large_size_w' ) : get_option( $size . '_size_w' );
			} else {
				$get_size = $_wp_additional_image_sizes[ $size ]['width'];
			}
		} else {
			$get_size = $size;
		}
		$avatar = get_avatar( $email, $get_size, $default = '', $alt = '');
		if ( in_array( $size, array( 'original', 'large', 'medium', 'thumbnail' ) ) ) {
			$avatar = preg_replace( '/(width|height)=\"\d*\"\s/', '', $avatar );
			$avatar = preg_replace( '/(width|height)=\'\d*\'\s/', '', $avatar );
		}
		$replace = array( 'wp-user-avatar ', 'wp-user-avatar-' . $get_size . ' ', 'wp-user-avatar-' . $size . ' ', 'avatar-' . $get_size, 'photo' );
		$replacements = array( '', '', '', 'avatar-' . $size, 'wp-user-avatar wp-user-avatar-' . $size . $alignclass . ' photo' );
		$avatar = str_replace( $replace, $replacements, $avatar );
	}
	return $avatar;
}

function pp_get_avatar( $avatar, $id_or_email = '', $size = '', $default = '', $alt = '' ) {
	global $avatar_default, $mustache_admin, $mustache_avatar, $mustache_medium, $mustache_original, $mustache_thumbnail, $post, $wpua_avatar_default, $wpua_disable_gravatar;

	if ( is_object( $id_or_email ) ) {
		if ( ! empty( $id_or_email->comment_author_email ) ) {
			$avatar = pp_get_user_avatar( $id_or_email, $size, $default, $alt );
		} else {
			$avatar = pp_get_user_avatar( 'unknown@gravatar.com', $size, $default, $alt );
	  }
	} else {
		if ( pp_has_user_avatar( $id_or_email ) ) {
			$avatar = pp_get_user_avatar( $id_or_email, $size, $default, $alt );
		} elseif ( $wpua_disable_gravatar != 1 && pp_has_gravatar( $id_or_email ) ) {
			$avatar = $avatar;
		} elseif ( $avatar_default == 'wp_user_avatar' ) {
			if ( ! empty( $wpua_avatar_default ) ) {
				$wpua_avatar_default_image = pp_get_attachment_image_src( $wpua_avatar_default, array( $size, $size ) );
				$default = $wpua_avatar_default_image[0];
				$dimensions = ' width="' . $wpua_avatar_default_image[1] . '" height="' . $wpua_avatar_default_image[2] . '"';
			} else {
				if ( $size > get_option( 'medium_size_w' ) ) {
					$default = $mustache_original;
			} elseif ( $size <= get_option( 'medium_size_w' ) && $size > get_option( 'thumbnail_size_w' ) ) {
				$default = $mustache_medium;
			} elseif( $size <= get_option( 'thumbnail_size_w' ) && $size > 96) {
				$default = $mustache_thumbnail;
			} elseif ( $size <= 96 && $size > 32 ) {
				$default = $mustache_avatar;
			} elseif( $size <= 32 ) {
				$default = $mustache_admin;
			}
			$dimensions = ' width="' . $size . '" height="' . $size . '"';
			}
			$avatar = '<img src="' . $default . '"' . $dimensions . ' alt="' . $alt . '" class="avatar avatar-' . $size . ' wp-user-avatar wp-user-avatar-' . $size . ' photo avatar-default" />';
		}
	}
	return $avatar;
}

function pp_is_author_or_above( $is_author_or_above ) {
	if ( BLOG_ID_CURRENT_SITE == get_current_blog_id() && current_user_can( 'upload_files' ) )
		$is_author_or_above = true;
	return $is_author_or_above;
}

if ( BLOG_ID_CURRENT_SITE != get_current_blog_id() && ! class_exists( 'WP_User_Avatar' ) )
	add_filter( 'get_avatar', 'pp_get_avatar', 10, 5 );

if ( is_admin() ) {
	add_filter( 'wpua_is_author_or_above', 'pp_is_author_or_above' );
}