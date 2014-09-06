<?php
function pp_adminbar_links( $toolbar ) {
	global $current_user;
//	load_textdomain( 'default', WP_CONTENT_DIR . '/languages/admin-' . str_replace( '-xx', '_', get_bloginfo( 'language', 'raw' ) ) . '.mo' );
	$src = 'admin';
	foreach ( pp_sites( $src ) as $site_id => $site ) {
		$toolbar->remove_node( 'blog-' . $site_id . '-v' );
		switch_to_blog( $site_id );
		if ( current_user_can( 'upload_files' ) )
			$toolbar->add_node( array( 'parent' => 'blog-' . $site_id, 'id' => 'blog-' . $site_id . '-n-attachment', 'title' => 'Last opp bilder', 'href' => admin_url( 'media-new.php' ) ) );
		if ( current_user_can( 'edit_posts' ) ) {
			$items = array();
			if (     2 == $site_id )
				$items = array( pp_uke_type(), pp_opp_type() );
			elseif ( 3 == $site_id )
				$items = array( pp_pro_type() );
			elseif ( 4 == $site_id )
				$items = array( pp_lev_type() );
			elseif ( 5 == $site_id)
				$items = array( pp_akt_type() );
			elseif ( 7 == $site_id  )
				$items = array( pp_lev_type(), pp_akt_type() );
			elseif ( 8 == $site_id )
				$items = array( pp_prd_type() );
			if ( in_array( $site_id, pp_sidebar_head_sites() ) || pp_sidebar_primary_num_ann()[ $site_id ] )
				$items[] = pp_ann_type();
			foreach ( $items as $item )
				$toolbar->add_node( array( 'parent' => 'blog-' . $site_id, 'id' => 'blog-' . $site_id . '-n-' . $item, 'title' => 'Ny' . ( pp_pro_type() == $item || pp_prd_type() == $item ? 'tt' : '' ) . ' ' . str_replace( array( 'dor', 'ser'), array( 'dør', 'se'), $item ), 'href' => admin_url( 'post-new.php?post_type=' . $item ) ) );
		}
		if ( current_user_can( 'list_users' ) )
			$toolbar->add_node( array( 'parent' => 'blog-' . $site_id, 'id' => 'blog-' . $site_id . '-n-user', 'title' => 'Ny bruker', 'href' => admin_url( 'user-new.php' ) ) );
		if ( current_user_can( 'edit_others_posts' ) )
			$toolbar->add_node( array( 'parent' => 'blog-' . $site_id, 'id' => 'blog-' . $site_id . '-w', 'title' => __( 'Nytt redaksjonelt varsel' ), 'href' => admin_url( 'tools.php?page=woa' ) ) );
		if ( current_user_can( 'read_private_posts' ) )
			$toolbar->add_node( array( 'parent' => 'blog-' . $site_id, 'id' => 'blog-' . $site_id . '-f', 'title' => 'Tilbakemeldinger', 'href' => admin_url( 'edit.php?post_type=feedback' ) ) );
		if ( current_user_can( 'list_users' ) )
			$toolbar->add_node( array( 'parent' => 'blog-' . $site_id, 'id' => 'blog-' . $site_id . '-n-users', 'title' => 'Brukere', 'href' => admin_url( 'users.php' ) ) );
		if ( current_user_can( 'update_plugins' ) )
			$toolbar->add_node( array( 'parent' => 'blog-' . $site_id, 'id' => 'blog-' . $site_id . '-p', 'title' => __( 'Plugins' ), 'href' => admin_url( 'plugins.php' ) ) );
		if ( current_user_can( 'editor' ) )
			$toolbar->add_node( array( 'parent' => 'blog-' . $site_id, 'id' => 'blog-' . $site_id . '-s', 'title' => 'Statistikk', 'href' => admin_url( 'admin.php?page=stats' ) ) );
		$toolbar->add_node( array( 'parent' => 'blog-' . $site_id, 'id' => 'blog-' . $site_id . '-v', 'title' => __( 'Visit Site' ), 'href' => home_url( '/' ) ) );
		//$src = true;
		//$toolbar->add_node( array( 'parent' => 'blog-' . $site_id, 'id' => 'blog-' . $site_id . '-w', 'title' => count( pp_sites( $src ) ), 'href' => home_url( '/' ) ) );
		restore_current_blog();
	}
	if ( current_user_can( 'edit_posts' ) && post_type_exists( 'wp-help' ) )
		$toolbar->add_node( array( 'parent' => 'new-content', 'id' => 'add-woa', 'title' => 'Hjelpedokument', 'href' => admin_url( 'post-new.php?post_type=wp-help' ) ) );
	if ( current_user_can( 'edit_others_pages' ) && function_exists( 'showAdminMessages' ) )
		$toolbar->add_node( array( 'parent' => 'new-content', 'id' => 'add-wp-help', 'title' => 'Varsel', 'href' => admin_url( 'tools.php?page=woa' ) ) );
	if ( current_user_can( 'install_plugins' ) )
		$toolbar->add_node( array( 'parent' => 'new-content', 'id' => 'add-plugin', 'title' => 'Utvidelse', 'href' => network_admin_url( 'plugin-install.php' ) ) );
	if     ( 2 == get_current_blog_id() ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			$toolbar->remove_node( 'wp-logo' );
			$toolbar->remove_node( 'my-sites' );
			$toolbar->remove_node( 'site-name' );
		}
	} elseif ( 3 == get_current_blog_id() ) {
		if ( ! current_user_can( 'publish_posts' ) ) {
			$toolbar->remove_node( 'my-sites' );
			$toolbar->remove_node( 'dashboard' );
			$toolbar->remove_node( 'pp-post' );
			$toolbar->remove_node( 'pp-annonser' );
			$toolbar->remove_node( 'comments' );
//			$toolbar->remove_node( 'new-content' );
			$toolbar->remove_node( 'new-post' );
			$toolbar->remove_node( 'new-media' );
			$toolbar->remove_node( 'new-annonser' );
			$toolbar->remove_node( 'add-woa' );
		}
		if ( ! current_user_can( 'edit_posts' ) ) {
			$toolbar->remove_node( 'wp-logo' );
			$toolbar->remove_node( 'my-sites' );
			$toolbar->remove_node( 'site-name' );
		}
	} elseif ( 5 == get_current_blog_id() ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			$toolbar->remove_node( 'wp-logo' );
			$toolbar->remove_node( 'my-sites' );
			$toolbar->remove_node( 'dashboard' );
			$toolbar->remove_node( 'pp-post' );
			$toolbar->remove_node( 'pp-annonser' );
			$toolbar->remove_node( 'comments' );
			$toolbar->remove_node( 'new-content' );
			$toolbar->remove_node( 'new-media' );
			$toolbar->add_node( array( 'id' => 'new-content', 'title' => 'Legg til', 'href' => admin_url( 'post-new.php?post_type=' . pp_akt_type() ) ) );
			$toolbar->add_node( array( 'parent' => 'new-content', 'id'=> 'new-' . pp_akt_type(), 'title' => ucfirst(pp_akt_type()), 'href' => admin_url( 'post-new.php?post_type=' . pp_akt_type() ) ) );
		}
	}
	if ( 7 == get_current_blog_id() && in_array( $current_user->user_login, array( 'knutsp', 'kcath' ) ) ) {
		include ( 'kcath.php' );
		kcath_oa_links( $toolbar );
	}
}