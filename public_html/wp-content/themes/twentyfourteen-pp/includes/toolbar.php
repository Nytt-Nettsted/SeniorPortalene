<?php
function pp_toolbar_links( $wp_admin_bar ) {
	global $post;
	remove_all_filters( 'get_edit_post_link' );
	$items = array();
	if (     2 == get_current_blog_id() )
		$items = array( pp_uke_type(), pp_opp_type() );
	elseif ( 3 == get_current_blog_id() )
		$items = array( pp_pro_type() );
	elseif ( 4 == get_current_blog_id() )
		$items = array( pp_lev_type() );
	elseif (     5 == get_current_blog_id() )
		$items = array( pp_akt_type() );
	elseif (     7 == get_current_blog_id() )
		$items = array( pp_akt_type(), pp_lev_type() );
	elseif ( 8 == get_current_blog_id() )
		$items = array( pp_prd_type() );

	if ( in_array( get_current_blog_id(), pp_sidebar_head_sites() ) || pp_sidebar_primary_num_ann()[ get_current_blog_id() ] )
		$items[] = pp_ann_type();
	if ( post_type_exists( 'feedback' ) )
		$items[] = 'feedback';
	$items = array_merge( array( 'post', 'page' ), $items );
	foreach ( $items as $item ) {
		$cpt = get_post_type_object( $item );
		if ( current_user_can( $cpt->cap->edit_posts ) ) {
			$args = array( 'parent' => 'site-name', 'id' => 'pp-' . $cpt->name, 'title' => $cpt->label, 'href' => admin_url( 'edit.php?post_type=' . $cpt->name ) );
			$wp_admin_bar->add_node( $args );
		}
	}
	if ( is_super_admin() ) {
		$wp_admin_bar->add_node( array( 'parent' => 'site-name', 'id' => 'users', 'title' => __( 'Users' ) ) );
		$wp_admin_bar->add_node( array( 'parent' => 'users', 'id' => 'pp-site-users', 'title' => get_bloginfo( 'name' ), 'href' => admin_url( 'users.php' ) ) );
		$wp_admin_bar->add_node( array( 'parent' => 'users', 'id' => 'pp-network-users', 'title' => get_current_site( BLOG_ID_CURRENT_SITE )->site_name, 'href' => get_blog_details( 1 )->siteurl . '/wp-admin/network/users.php' ) );
	} else {
		if ( current_user_can( 'list_users' ) )
			$wp_admin_bar->add_node( array( 'parent' => 'site-name', 'id' => 'users', 'title' => __( 'Users' ), 'href' => admin_url( 'users.php' ) ) );
	}
	$wp_admin_bar->remove_node( 'themes' );
	$wp_admin_bar->remove_node( 'header' );
	$wp_admin_bar->remove_node( 'background' );
	if ( current_user_can( 'edit_posts' ) && ! is_404() &&	is_page() && is_front_page() && 'default' != get_post_meta( $post->ID, '_wp_page_template', true ) ) {
		$wp_admin_bar->remove_node( 'edit' );
		if     ( BLOG_ID_CURRENT_SITE == get_current_blog_id() ) {
			$src = '';
			$wp_admin_bar->add_node( array( 'id' => 'edit', 'title' => 'Rediger introduksjonen', 'href' => '/wp-admin/post.php?post=' . $post->ID . '&amp;action=edit' ) );
			$wp_admin_bar->add_node( array( 'id' => 'pp-desc-edit', 'title' =>'Rediger portalbeskrivelser' ) );
			foreach ( pp_sites( $src ) as $site_id => $site ) {
				if ( BLOG_ID_CURRENT_SITE != $site_id ) {
//					$site = get_blog_details( $site_id );
					$name = esc_attr( $site->blogname );
					$url = esc_url( $site->siteurl, array( 'http', 'https' ) );
					$wp_admin_bar->add_node( array( 'id' => 'pp-desc-edit-' . $site_id, 'parent' => 'pp-desc-edit', 'title' => $name, 'href' => $url . '/wp-admin/options-general.php?page=pp-settings' ) );
				}
			}
		}
		elseif ( 2 == get_current_blog_id() && current_user_can( get_post_type_object( pp_uke_type() )->cap->edit_posts ) )
			$wp_admin_bar->add_node( array( 'id' => 'pp-' . pp_uke_type() . '-edit', 'title' => 'Rediger ' . strtolower( get_post_type_object( pp_uke_type() )->label ), 'href' => admin_url( 'edit.php?post_status=publish&amp;post_type=' . pp_uke_type() ) ) );
		elseif ( 5 == get_current_blog_id() && current_user_can( get_post_type_object( pp_akt_type() )->cap->edit_posts ) )
			$wp_admin_bar->add_node( array( 'id' => 'pp-' . pp_akt_type() . '-edit', 'title' => 'Rediger ' . strtolower( get_post_type_object( pp_akt_type() )->label ), 'href' => admin_url( 'edit.php?post_status=publish&amp;post_type=' . pp_akt_type() ) ) );
	}
	if ( current_user_can( 'edit_posts' ) && ! is_404() &&	is_tax( pp_kom_tax() ) ) {
		$wp_admin_bar->remove_node( 'edit' );
		if     ( 3 == get_current_blog_id() )
			$wp_admin_bar->add_node( array( 'id' => 'pp-' . pp_pro_type() . '-edit', 'title' => 'Rediger disse ' . strtolower( get_post_type_object( pp_pro_type() )->label ), 'href' => admin_url( 'edit.php?post_type=' . pp_pro_type() . '&amp;' . pp_kom_tax() . '=' . get_queried_object()->slug ) ) );
		elseif ( 5 == get_current_blog_id() )
			$wp_admin_bar->add_node( array( 'id' => 'pp-' . pp_akt_type() . '-edit', 'title' => 'Rediger disse ' . strtolower( get_post_type_object( pp_akt_type() )->label ), 'href' => admin_url( 'edit.php?post_type=' . pp_akt_type() . '&amp;' . pp_kom_tax() . '=' . get_queried_object()->slug ) ) );
		else
			$wp_admin_bar->add_node( array( 'id' => 'pp-' . pp_lev_type() . '-edit', 'title' => 'Rediger disse ' . strtolower( get_post_type_object( pp_lev_type() )->label ), 'href' => admin_url( 'edit.php?post_type=' . pp_lev_type() . '&amp;' . pp_kom_tax() . '=' . get_queried_object()->slug ) ) );
	}
	if ( is_super_admin() ) {
		$wp_admin_bar->add_node( array( 'parent' => 'site-name', 'id' => 'pp-plugins', 'title' => __( 'Plugins' ) ) );
		$wp_admin_bar->add_node( array( 'parent' => 'pp-plugins', 'id' => 'pp-site-plugins', 'title' => get_bloginfo( 'name' ), 'href' => admin_url( 'plugins.php' ) ) );
		$wp_admin_bar->add_node( array( 'parent' => 'pp-plugins', 'id' => 'pp-network-plugins', 'title' => get_current_site( BLOG_ID_CURRENT_SITE )->site_name, 'href' => get_blog_details( 1 )->siteurl . '/wp-admin/network/plugins.php' ) );
	} else {
		if ( current_user_can( 'activate_plugins' ) )
			$wp_admin_bar->add_node( array( 'parent' => 'site-name', 'id' => 'pp-plugins', 'title' => __( 'Plugins' ), 'href' => admin_url( 'plugins.php' ) ) );
	}
	if ( is_page( 'ansatte' ) && current_user_can( 'edit_users' ) )
		$wp_admin_bar->add_node( array( 'id' => 'pp-edit-users', 'title' => 'Rediger ansatte', 'href' => admin_url( 'users.php?role=editor' ) ) );
}
