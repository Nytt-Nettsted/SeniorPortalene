<?php
function pp_get_nav_menu_items( $items, $menu ) {
	if ( 7 != get_current_blog_id() && $menu->name == wp_get_nav_menu_object( get_nav_menu_locations()['primary'] )->name ) {
		$main_title = strtolower( BLOG_ID_CURRENT_SITE == get_current_blog_id() || 10 == get_current_blog_id() ? 'Portalene' : 'Hjem' );
		$main_id = false;
		foreach ( $items as $item )
			if ( empty( $item->menu_item_parent ) && 'custom' == $item->object && 'custom' == $item->type && $main_title == strtolower( $item->title ) )
				$main_id = $item->ID;
		if ( $main_id ) {
			$base = count( $items );
			$arr = 10 == get_current_blog_id() ? array( '', true ) : array( '' );
			foreach ( $arr as $src ) {
				foreach ( pp_sites( $src ) as $site_id => $site ) {
					if ( $site_id != get_current_blog_id() ) {
						$name = esc_attr( $site->blogname );
						$url  = esc_url( $site->siteurl, array( 'http', 'https' ) );
						$item = new stdClass;
						$item->ID               = -$site_id;
						$item->db_id            = -$site_id;
						$item->object_id        = -$site_id;
						$item->object           = 'custom';
						$item->type             = 'custom';
						$item->classes          = '';
						$item->title            = $name;
						$item->url              = $url;
						$item->menu_item_parent = $main_id;
						$item->menu_order       = $base + $site_id;
						$item->attr_title       = 'Til ' . $name . ' (' . $src . ')';
						$items[] = $item;
					}
				}
			}
		}
	}
	return $items;
}
