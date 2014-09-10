<?php
/**
 * The main functions file of this child theme
 *
 * @package Seniorportalene
 */

// KONFIGURERING START ===============================================================================
define( 'PP_SITES_TRANS', 'pp_sites' );						// Transient name to use
define( 'PP_FEAT_TRANS', 'pp_featured_posts' );				// Transient name to use
if ( WP_DEBUG ) {
	define( 'PP_SITES_TRANS_EXP', 2 * MINUTE_IN_SECONDS );
	define( 'PP_FEAT_TRANS_EXP', 8 );
	define( 'PP_ANN_TRANS_EXP', 16 );
	define( 'PP_PRIVATE', true );								// Overstyrer "Members" plugin "private site"
} else {
	define( 'PP_SITES_TRANS_EXP', 6 *   HOUR_IN_SECONDS );
	define( 'PP_FEAT_TRANS_EXP',  4 * MINUTE_IN_SECONDS );
	define( 'PP_ANN_TRANS_EXP',   2 * MINUTE_IN_SECONDS );
//	define( 'PP_PRIVATE', false );								// Overstyrer "Members" plugin "private site"
}
define( 'PP_DOMAIN_SITE', 10 );
define( 'PP_UKEMENY_ADJUST', '+1 days' );						// For PHP strtotime(), dager meny kan publiseres før ukestart, autogenererering av post_title (og post_name). Ukemeny kan f.eks publiseres på søndag med '+1 day'. Ellers brukes Planlegg-funksjonen.
define( 'PP_NUM_HEAD_ANN', 2 );									// Antall annonser i topp
define( 'PP_KOM_DELIM', '|' );

function pp_featured_content_pos() {							// Hvilke nettsteder (sites) som skal ha fremhevet innhold hvor (top/sidebar). Brukes av index.php og featured-content.php
	return array( BLOG_ID_CURRENT_SITE => 'sidebar', 2 => 'top', 3 => 'top', 6 => 'top', 7 => 'top', 9 => 'top' );
}

function pp_forum_thumbnails() {								// Attachment_ID for fremhevet bilde på featued content for bbPress forums.  Brukes av content-featured-forums.php
	return array( 2 => 1433, 7 => 758, 9 => 56 );
}

function pp_sidebar_head_sites() {								// Sites med annonser i topp
	return range( BLOG_ID_CURRENT_SITE, 9 );					// array( BLOG_ID_CURRENT_SITE, 2, 3, 4, 5, 6, 7, 8, 9 );
}

function pp_sidebar_primary_num_ann() {							// Antall annonser maks. i sidebar. site => number (Brukes trolig ikke)
	return array_fill( BLOG_ID_CURRENT_SITE, 9, 4 );			// array( BLOG_ID_CURRENT_SITE => 4, 2 => 4, 3 => 4, 4 => 4, 5 => 4, 6 => 4, 8 => 4, 9 => 4 );
}

function pp_credits() {
//	$site    = get_blog_details( BLOG_ID_CURRENT_SITE );
	$site  = get_blog_details( get_blog_details( get_current_blog_id() )->mature ? 10 : BLOG_ID_CURRENT_SITE );
	$year    = intval( mysql2date( 'Y', get_blog_details( get_current_blog_id() )->registered ) );
	$theme   = wp_get_theme();
	$dev     = $theme->get( 'Tags' )[0];
	$names   = explode( ', ', strip_tags( $theme->get( 'Author') ) );
	$user    = get_user_by( 'login', $names[0] );
	if ( $user ) {
		$authors = $user->data->display_name;
		if ( count( $names > 1 ) ) {
			$user = get_user_by( 'login', $names[1] );
			if ( $user )
				$authors .= ', ' . $user->data->display_name;
		}
		$authors = str_lreplace( ', ', ' og ', $authors );
	}
	$version  = floatval  ( $theme->get( 'Version'   ) );
	$url      = esc_url   ( $theme->get( 'AuthorURI' ) );
	echo PHP_EOL, '<p class="alignright">Portalene AS, Strykerveien 10, 1658 Torp, tlf  94 15 23 00 &nbsp; | &nbsp; ';
	if ( in_array( get_current_blog_id(), array( 1, 5, 7 ) ) || 'page' != get_option( 'show_on_front' ) )
		echo '<span title="', $theme, ' versjon ', $version, '">Utviklet</span> av <a href="', $url, '" title="', $authors, '">', $dev, '</a> ', $year, '.';
	echo PHP_EOL, ' &nbsp; | &nbsp; <span title="Opphavsrett etter norsk lov">&copy;</span> <a href="', $site->siteurl, '/om/" title="', get_blog_option( BLOG_ID_CURRENT_SITE, 'blogdescription' ), '">', get_blog_option( 10, 'blogname' ), '</a> ', $year != intval( date( 'Y' ) ) ?  $year . '-' . date( 'Y' ) : $year, '</p>';
}

function pp_admin_footer() {
	$site  = get_blog_details( get_blog_details( get_current_blog_id() )->mature ? 10 : BLOG_ID_CURRENT_SITE );
	$year  = intval( mysql2date( 'Y', get_blog_details( get_current_blog_id() )->registered ) );
	$theme = wp_get_theme();
	$dev   = $theme->get( 'Tags' )[0];
	$devs  = explode( ', ', strip_tags( $theme->get( 'Author') ) );
	$dev1  = get_user_by( 'login', $devs[0] );
	$tlf   = get_user_meta( $dev1->ID, pp_tel_meta(), true );
	$dev1->tlf  = $tlf ? ' (' . esc_attr( $tlf ) . ')' : '';
	if ( count( $devs > 1 ) ) {
		$dev2 = get_user_by( 'login', $devs[1] );
		$tlf  = get_user_meta( $dev2->ID, pp_tel_meta(), true );
		$dev2->tlf = $tlf ? ' (' . esc_attr( $tlf ) . ')' : '';
	}
	$url   = esc_url( $dev1 && $dev1->user_url ? $dev1->user_url : ( $dev2 && $dev2->user_url ? $dev2->user_url : $theme->get( 'AuthorURI' ) ) );
	echo PHP_EOL, '<p style="font-size: 90%;"><em>Du bruker tilpassede innholdstyper og tilrettelagt funksjonalitet, en del <a href="', $site->siteurl, '/wp-admin/" title="', get_blog_option( BLOG_ID_CURRENT_SITE, 'blogdescription' ), '">', $site->blogname, '</a> v', pp_theme_version(), ', utviklet av <a href="', $url, '" title="', $dev1->display_name, ' og ', $dev2->display_name, '">', $dev, '</a> ', $year, '.';
	if ( $dev1 && $dev2 && current_user_can( 'publish_posts' ) )
		echo ' &nbsp; Eventuell brukerstøtte: <a href="mailto:', $dev2->user_email, '" title="Send da en epost til ', $dev2->display_name, ' <', $dev2->user_email, '>', '">', $dev2->display_name, '</a>', $dev2->tlf, ' eller <a href="mailto:', $dev1->user_email, '" title="Send da en epost til ', $dev1->display_name, ' <', $dev1->user_email, '>', '">', $dev1->display_name, '</a>', $dev1->tlf, '.';
	echo '</em></p>';
}

// KONFIGURERING SLUTT ===============================================================================

include( 'includes/cpt-tax-defs.php' );
include( 'includes/library.php' );

function pp_widget_annonser_li( &$annonser, $show_title = false, $image_size = 'post-thumbnail', $cache = false, $li = 'li' ) {
	$num = 0;
	foreach ( $annonser as $key => $annonse ) {
		$id = is_numeric( $key ) ? $key + 1 : $key;
		$url = esc_url( get_post_meta( $annonse->ID, 'website' , true ), array( 'http', 'https' ) );
		$title = esc_attr( 'Besøk nettsiden til ' . $annonse->post_title );
		echo PHP_EOL, '  <', $li, ' id="' . pp_ann_type() . '-' . $id . '" title="' . $cache . ' ' . $annonse->src . '">';
		if ( $show_title )
			echo PHP_EOL, '   <h2><a href="', $url, '" title="', $title, $cache ? ' (' . $cache . ' ' . $annonse->src . ')' : '', '" target="_blank">', esc_html( $annonse->post_title ), '</a></h2>';
		if ( has_post_thumbnail( $annonse->ID ) ) {
			if ( $url )
				echo PHP_EOL, '   <a href="', $url, '" title="', $title, $cache ? ' (' . $cache . ' ' . $annonse->src . ')' : '', '" target="_blank">', get_the_post_thumbnail( $annonse->ID, $image_size ), '</a>';
			else
				echo PHP_EOL, '   <span title="', $cache ? ' (' . $cache . ' ' . $annonse->src . ')' : '', '">', get_the_post_thumbnail( $annonse->ID, $image_size ), '</span>';
			$excerpt = strip_tags( $annonse->post_excerpt );
			if ( $excerpt )
				echo PHP_EOL, '   <p>', $excerpt, '</p>';
		} else {
			if ( $url )
				echo PHP_EOL, '<a href="', $url, '" target="_blank">', $annonse->post_excerpt, '</a>';
			else
				echo PHP_EOL, $annonse->post_excerpt;
		}
		if ( $li )
			echo PHP_EOL, '  </', $li, '>';
		$num ++;
	}
	return $num;
}

function pp_cpt_nav() {		//Used in templates
	// Don't print empty markup if there's nowhere to navigate.
	$previous = get_adjacent_post( false, '', true  );
	$next     = get_adjacent_post( false, '', false );
	if ( ! $next && ! $previous ) {
		return;
	}
?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'twentyfourteen' ); ?></h1>
		<div class="nav-links">
<?php
	previous_post_link( '%link', '<span class="meta-nav">Forrige ' . get_post_type() . '</span>%title' );
	    next_post_link( '%link', '<span class="meta-nav">Neste '   . get_post_type() . '</span>%title' );
?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
<?php
}

function pp_init() {
	global $wp_post_types, $wp_rewrite;
	setlocale( LC_TIME, WPLANG . '.utf8', WPLANG );
	$wp_rewrite->author_base = 'forfatter';
	add_theme_support( 'post-thumbnails', array( 'post', 'page', pp_pro_type(), pp_ann_type(), pp_opp_type(), pp_uke_type(), pp_akt_type(), pp_prd_type(), 'forums', 'forum' ) );
	remove_theme_support( 'post-formats' );
	$feedback_label = 'Tilbakemeldinger';
	$wp_post_types['feedback']->labels->name      = $feedback_label;
	$wp_post_types['feedback']->labels->menu_name = $feedback_label;
	$wp_post_types['feedback']->label             = $feedback_label;
	add_post_type_support( 'forum', 'thumbnail' );
	if ( ! defined( 'JETPACK_DEV_DEBUG' ) && 0 == get_blog_details( get_current_blog_id() )->public )
		define( 'JETPACK_DEV_DEBUG', true );
}

function pp_body_classes( $classes ) {
	$classes[] = 'site-' . get_current_blog_id();
	return $classes;
}

function pp_get_featured_posts( $featured_posts ) {
	$src = '';
	if ( BLOG_ID_CURRENT_SITE == get_current_blog_id() )
		return range( 1, count( pp_sites( $src ) ) );	// Fake it
	else
		return $featured_posts;
}

function pp_order_alpha( $orderby ) {
	global $wp_query;
	if ( is_admin() && $wp_query->is_post_type_archive( pp_lev_type() ) ) {
		if ( isset( $_GET['order'] ) )
			return str_replace( 'post_date', 'post_title', $orderby );
		else
			return str_replace( 'DESC', 'ASC', str_replace( 'post_date', 'post_title', $orderby ) );
	}// elseif ( $wp_query->is_tax( pp_kom_tax() ) || $wp_query->is_post_type_archive( pp_lev_type() ) || $wp_query->is_tax( pp_pkat_tax() ) )
//		return 'post_title ASC, ID ASC';
	else
		return $orderby;
}

function pp_order_aktiviteter( $orderby ) {
	global $wpdb, $wp_query;
	if ( in_array( get_current_blog_id(), array( 5, 7 ) ) && $wp_query->is_tax( pp_kom_tax() ) )
		$orderby = "start.meta_value ASC, $orderby";
	return $orderby;
}

function pp_order_leverandor( $orderby ) {
	global $wpdb, $wp_query;
	if ( ( 4 == get_current_blog_id() || 7 == get_current_blog_id() ) && ( $wp_query->is_post_type_archive( pp_lev_type() ) || $wp_query->is_tax( pp_kom_tax() ) /*|| $wp_query->is_search() */) )
		$orderby = "privat.meta_value DESC, post_title ASC";
	return $orderby;
}

function x_pp_limits_lev( $limits ) {	// Ikke i bruk
	global $wp_query;
//	if ( ( in_array( get_current_blog_id(), array( 4, 7 ) ) && ( $wp_query->is_tax( pp_kom_tax() ) || $wp_query->is_search() ) ) || $wp_query->is_post_type_archive( pp_lev_type() ) )
	if ( in_array( get_current_blog_id(), array( 4, 7 ) ) && ( $wp_query->is_tax( pp_kom_tax() ) || $wp_query->is_post_type_archive( pp_lev_type() ) || $wp_query->is_search() ) )
		$limits = 'LIMIT 0, 40';
	return $limits;
}

function pp_pre_get_posts( $query ) {
	if ( $query->is_main_query( $query ) ) {
		if ( in_array( get_current_blog_id(), array( 5, 7 ) ) && $query->is_tax( pp_kom_tax() ) )
			$query->set( 'posts_per_page', 999 );
		elseif ( ( in_array( get_current_blog_id(), array( 4, 7 ) ) && ( $query->is_tax( pp_kom_tax() ) || $query->is_search() ) ) || $query->is_post_type_archive( pp_lev_type() ) )
			$query->set( 'posts_per_page', 40 );
		elseif ( 2 == get_current_blog_id() && $query->is_tag() )
			$query->set ( 'post_type', pp_opp_type() );
		elseif ( $query->is_tax( pp_pkat_tax() ) || $query->is_post_type_archive( pp_prd_type() ) )
			$query->set( 'post_parent', 0 );
	}
}

function pp_lev_columns( $columns ) {
    unset( $columns['date'] );
	if ( 4 == get_current_blog_id() )
		return array_merge( $columns, array( 'website' => 'Nettside', 'tlf' => 'Telefon', 'hjemmesykepleie' => 'Hsp', 'praktisk_bistand' => 'Prb', 'privat' => 'Prv' ) );
	else
		return array_merge( $columns, array( 'website' => 'Nettside', 'tlf' => 'Telefon' ) );
}

function pp_ann_columns( $columns ) {
	$columns['date'] = 'Dato start';
    return array_merge( $columns, array( 'annonsesluttdato' => 'Dato slutt' ) );
}

function pp_akt_columns( $columns ) {
//    unset( $columns['date'] ); // 03.06.14: Ville ha tilbake dato, BTH
	return array_merge( array_slice( $columns, 0, 2, true ), array( 'startdato' => 'Start', '_last_date' => 'Siste gang', 'arrangor' => 'Arrangør', 'gjentagelse' => 'Gjentas' ), array_slice( $columns, 2, null, true ) ) ;
}

function pp_post_columns( $columns ) {
	global $typenow;
	if ( pp_akt_type() == $typenow )
		return $columns;
	else
		return array_merge( $columns, array( 'gb_admin_note' => 'Notat' ) );
}

function pp_custom_columns( $column, $post_id ) {
	if ( function_exists( 'pods_field' ) ) {
		if     ( get_post_type() == pp_lev_type() && ( 'website' == $column || 'tlf' == $column || 'hjemmesykepleie' == $column || 'praktisk_bistand' == $column || 'privat' == $column ) )
			echo pp_shorten( pods_field_display( pp_lev_type(), $post_id, $column, true ), '/', 8 );
		elseif ( get_post_type() == pp_akt_type() && ( 'startdato' == $column || '_last_date' == $column || 'arrangor' == $column ) )
			echo '_last_date' == $column ? date( 'd.m.Y', strtotime( get_post_meta( $post_id, $column, true ) ) ) : pods_field_display( pp_akt_type(), $post_id, $column, true );
		elseif ( get_post_type() == pp_akt_type() && 'gjentagelse' == $column )
			echo pods_field_display( pp_akt_type(), $post_id, $column, true ) || pods_field_display( pp_akt_type(), $post_id, 'gjenta', true ) || pods_field_display( pp_akt_type(), $post_id, 'periode', true ) ? ' &nbsp; <span style="color: orangered">Ja</span>' : 'Nei' ;
		elseif ( get_post_type() == pp_ann_type() && ( 'annonsesluttdato' == $column ) ) {
			$raw = pods_field( pp_ann_type(), $post_id, $column, true );
			echo $raw == 0 ? 'Evig' : $raw, '<br />', $raw == 0 || $raw >= current_time( 'mysql' ) ? 'Aktiv' : '<span class="trash"><a>Inaktiv</a></span>';
		}
	}
	if ( 'gb_admin_note' == $column )
		echo '<small title="' . esc_attr( get_post_meta( $post_id, 'gb_admin_note', true ) ) . '">' . substr( get_post_meta( $post_id, 'gb_admin_note', true ), 0, 8 ) . '</small>';
}

function pp_admin_style( $hook ) {
	global $typenow;
	if ( in_array( $hook, array( 'edit.php', 'post.php', 'post-new.php' ) ) ) {
		if ( in_array( $typenow, pp_acpt() ) ) {
			wp_register_style( 'pp_admin_style', get_stylesheet_directory_uri() . '/css/admin-edit-' . $typenow . '.css', false, pp_theme_version() );
			wp_enqueue_style( 'pp_admin_style' );
		}
	} elseif ( ! is_super_admin() && 'users.php' == $hook )
			wp_register_style( 'pp_admin_style', get_stylesheet_directory_uri() . '/css/admin-users.css', false, pp_theme_version() );
			wp_enqueue_style( 'pp_admin_style' );
}

function pp_cust_sortable_columns( $sortable ) {
	unset ( $sortable['date'] );
	unset ( $sortable['title'] );
	$sortable['title'] = array( 'title' => 'title', 1 => false );
	return $sortable;
}

function pp_pro_sortable_columns( $sortable ) {
	unset ( $sortable['title'] );
	$sortable['title'] = array( 'title' => 'title', 1 => false );
	return $sortable;
}

function pp_update_post_data( $postdata ) {
    if ( pp_uke_type() == $postdata['post_type'] && 'auto-draft' != $postdata['post_status'] ) {
		$tomorrow = strtotime( PP_UKEMENY_ADJUST, mysql2date( 'U', $postdata['post_date'] ) );
        $postdata['post_name'] = date( 'Y', $tomorrow ) . '-' . date( 'W', $tomorrow );
		$postdata['post_title'] = 'Menyforslag uke ' . intval( date( 'W', $tomorrow ) );
    }
    return $postdata;
}

function pp_contactmethods( $contactmethods ) {
	global $user_ID, $wpdb;
	$userid = isset( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : ( isset( $_GET['user_id'] ) ? intval( $_GET['user_id'] ) : $user_ID );
	if ( current_user_can( 'edit_users' ) ) {
//		$contactmethods[ $wpdb->prefix . 'funksjon' ] = 'Funksjon <span class="description" title="La stå tomt for brukere som ikke skal vises under &laquo;medarbeidere&raquo;">(innledende spesialtegn angir fremhevet ansatt, f.eks <code>+Teamleder</code>)</span>';
		$contactmethods[ 'pp_funksjon' ] = 'Funksjon <span class="description" title="La stå tomt for brukere som ikke skal vises under &laquo;medarbeidere&raquo;">(innledende spesialtegn angir fremhevet ansatt, f.eks <code>+Teamleder</code>)</span>';
	}
	if ( 5 != get_current_blog_id() )
		$contactmethods[ pp_tel_meta() ] = 'Telefon';
	if ( 3 == get_current_blog_id() ) {
		if ( user_can( $userid, 'utbygger' ) )
			$contactmethods['pp_firma']   = 'Firma <span class="description">(for utbygger)</span>';
		elseif ( user_can( $userid, 'subscriber' ) )
			$contactmethods[ pp_pro_fyl_meta() ]   = 'Fylke <span class="description">(hvis flere fylker, adskilt med ' . PP_KOM_DELIM . ' )</span>';
			$contactmethods[ pp_pro_kom_meta() ]   = 'Kommune <span class="description">(hvis flere kommuner, adskilt med ' . PP_KOM_DELIM . ' )</span>';
	}
	return $contactmethods;
}

function pp_theme_setup() {
	if ( in_array( get_current_blog_id(), pp_sidebar_head_sites() ) )
		register_sidebar( array( 'id' => 'head', 'name' => 'Widget-område i sidehodet', 'class' => 'top', 'before_widget' => '', 'after_widget' => '' ) );
	register_sidebar( array( 'id' => 'content-after', 'name' => 'Widget-område under innholdet', 'class' => 'after', 'before_widget' => '', 'after_widget' => '' ) );
}

function pp_posts_join( $join ) {
	global $wpdb, $wp_query;
	if ( $wp_query->is_post_type_archive( pp_akt_type() ) || $wp_query->is_post_type_archive( pp_lev_type() ) || $wp_query->is_tax( pp_kom_tax() ) ) {
		if     ( 4 == get_current_blog_id() )
			$join .= "LEFT JOIN $wpdb->postmeta privat ON $wpdb->posts.ID = privat.post_id AND privat.meta_key = 'privat'";
		elseif ( 5 == get_current_blog_id() )
			$join .= "LEFT JOIN $wpdb->postmeta start ON $wpdb->posts.ID = start.post_id AND start.meta_key = 'startdato' LEFT JOIN $wpdb->postmeta slutt ON $wpdb->posts.ID = slutt.post_id AND slutt.meta_key = '_last_date'";
		elseif ( 7 == get_current_blog_id() )
			$join .= "LEFT JOIN $wpdb->postmeta privat ON $wpdb->posts.ID = privat.post_id AND privat.meta_key = 'privat' LEFT JOIN $wpdb->postmeta start ON $wpdb->posts.ID = start.post_id AND start.meta_key = 'startdato' LEFT JOIN $wpdb->postmeta slutt ON $wpdb->posts.ID = slutt.post_id AND slutt.meta_key = '_last_date'";
	}
	return $join;
}

function pp_posts_where( $where ) {
	global $wpdb, $wp_query;
	if ( 5 == get_current_blog_id() && $wp_query->is_tax( pp_kom_tax() ) ) {	// Ikke BPA, fordi kommune brukes til leevrandører, ikke aktiviteter
		$where .= " AND slutt.meta_key = '_last_date' AND slutt.meta_value >= CURDATE()";
	}
	return $where;
}

function pp_archive_link() {
	global $current_screen;
	if ( in_array( pp_get_current_post_type(), pp_cpts() ) && 'edit' == $current_screen->base ) {
		$cpt = get_post_type_object( pp_get_current_post_type() );
		$right_em = 28.3;
		$right_em = $right_em - floatval( strlen( $cpt->labels->name ) );
		echo '<div style="position: absolute; right: ', $right_em ,'em; top: 2.2em; z-index: 1;">';
		echo '<a class="add-new-h2" style="text-decoration: none;" href="', get_post_type_archive_link( $cpt->name ), '" title="Vis arkivet ', $cpt->labels->name, '">Vis alle ' . strtolower( $cpt->labels->name ) . '</a>';
		echo '</div>';
	}
}

function pp_list_terms_exclusions( $exclusions, $args, $taxonomies ) {
	if ( 3 == get_current_blog_id() && function_exists( 'get_current_screen' ) && pp_pro_type() == get_current_screen()->id && ( ( is_array( $taxonomies) && in_array( pp_kom_tax(), $taxonomies ) ) || pp_kom_tax() == $taxonomies ) )
		$exclusions = ' AND tt.parent <> 0';
	return $exclusions;
}

function pp_dashboard_setup() {
	if ( ! is_super_admin() )
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
}

function pp_the_category( $thelist ) {
	global $post; //echo 's'.$post->site.' b'.get_current_blog_id() .' '.pp_featured_content_pos()[ $post->site ];
	if ( $post->featured && isset( $post->site ) ) {
		if ( get_post_type() == 'post' && 'sidebar' == pp_featured_content_pos()[ $post->site ] )
			$thelist = '<span title="' . $post->src . '">Siste fra</span> &nbsp; &laquo;<a href="' . get_home_url() . '" title="Gå til forsiden av ' . get_bloginfo( 'name' ) . '. Trykk enten på bildet over eller på  tittelen under for å lese hele denne artikkelen der.">' . get_bloginfo( 'name' ) . '</a>&raquo;';
		elseif ( 'sidebar' == pp_featured_content_pos()[ $post->site ] )
			$thelist = '<span title="' . $post->src . '">Siste ' . get_post_type() . ' fra</span> &nbsp; &laquo;<a href="' . get_home_url() . '" title="Gå til forsiden av ' . get_bloginfo( 'name' ) . '. Trykk enten på bildet over eller på  tittelen under for å lese hele denne artikkelen der.">' . get_bloginfo( 'name' ) . '</a>&raquo;';
	} elseif ( $post->featured && 'post' != get_post_type() )
		$thelist = '<span>Siste ' . get_post_type() . '</span>';
	return $thelist;
}

function pp_dfi_thumbnail_id( $thumbnail_id, $post_id ) {
	if ( get_post_type( $post_id ) != pp_ann_type() ) {
		if ( get_post_type( $post_id ) == pp_akt_type() ) {
			$atts = get_children( array( 'post_parent' => get_option( 'page_on_front' ), 'post_type' => 'attachment', 'post_mime_type' => 'image' ) );
			$ids = array_merge( array_keys( $atts ), array( $thumbnail_id ) );
			return $ids[ $post_id % ( count( $ids ) + 1 ) ];
		} else
			return $thumbnail_id;
	} else
		return false;
}

function pp_meta_boxes() {
	foreach ( get_post_types() as $type ) {
		if ( class_exists( 'WP_User_Frontend' ) ) {
			remove_meta_box( 'wpuf-custom-fields', $type, 'normal' );
			remove_meta_box( 'wpuf-select-form'  , $type, 'side'   );
		}
		if ( ! is_super_admin() ) {
			remove_meta_box( 'slugdiv',   $type, 'normal' );
//			remove_meta_box( 'authordiv', $type, 'normal' );
			if ( ! current_user_can( 'list_users' ) || 'page' != $type )
				remove_meta_box( 'content-permissions-meta-box', $type, 'advanced' );
		}
		remove_meta_box( 'aiosp', $type, 'advanced' );
		remove_meta_box( 'aiosp_tabbed', $type, 'advanced' );
	}
	if ( is_super_admin() )
		foreach ( pp_acpt() as $type )
			remove_meta_box( 'sharing_meta', $type, 'advanced' );
	else
		foreach ( array_merge( pp_acpt(), array( 'page' ) ) as $type )
			remove_meta_box( 'sharing_meta', $type, 'advanced' );
	foreach ( array( 'post', 'page' ) as $type ) {
		remove_meta_box( 'postcustom', $type, 'normal' );
	}
	remove_meta_box( 'trackbacksdiv',    'post', 'normal' );
	remove_meta_box( 'commentsdiv',      'page', 'normal' );
	if ( ! is_super_admin() )
		remove_meta_box( 'commentstatusdiv', 'page', 'normal' );
}

function pp_int_users() {
	if ( empty( $_REQUEST[ pp_kom_tax() ] ) ) {
		$meta_query = array( 'relation' => 'AND',
			array( 'key' => pp_pro_kom_meta(), 'compare' => 'EXISTS' ),
			array( 'key' => pp_pro_kom_meta(), 'compare' => '!=', 'value' => '' )
		);
		$subscribers = get_users( array( 'role' => 'subscriber' , 'meta_query' => $meta_query ) );
		return $subscribers;
	} else {
		$meta_query_fyl = array(
			array( 'key' => pp_pro_fyl_meta(), 'compare' => 'LIKE', 'value' => mb_convert_case( esc_attr( $_REQUEST[ pp_kom_tax() ] ), MB_CASE_TITLE, 'UTF-8' ) )
		);
		$meta_query_kom = array(
			array( 'key' => pp_pro_kom_meta(), 'compare' => 'LIKE', 'value' => mb_convert_case( esc_attr( $_REQUEST[ pp_kom_tax() ] ), MB_CASE_TITLE, 'UTF-8' ) )
		);
		$subscribers_fyl = get_users( array( 'role' => 'subscriber' , 'meta_query' => $meta_query_fyl ) );
		$subscribers_kom = get_users( array( 'role' => 'subscriber' , 'meta_query' => $meta_query_kom ) );
		return array_merge( $subscribers_fyl, $subscribers_kom );
	}
	//$contributors = get_users( array( 'role' => 'contributor', 'meta_query' => $meta_query ) );
	//return array_merge( $subscribers, $contributors );
	//return $subscribers;
}

function pp_int_page() {
?>
	<div class="wrap">
		<h2>Eksport av interessenter <span class="description">(Alle abonnenter og bidragsytere med kommune[r] registrert)</span></h2>
		<form action="">
			<input type="hidden" name="page" value="export-interessent" />
			<label>Velg fylke: <input name="<?php echo pp_kom_tax(); ?>" value="<?php echo mb_convert_case( esc_attr( $_REQUEST[ pp_kom_tax() ] ),MB_CASE_TITLE, 'UTF-8' ); ?>" /></label>
			<?php submit_button( 'Søk', 'primary', null, false ); ?>
			<input type="reset" value="Tilbakestill" onclick="window.location='<?php echo remove_query_arg( pp_kom_tax(), $_SERVER['REQUEST_URI'] ); ?>'" class="button">
	</form>
		<hr />
<?php
	$search = esc_attr( $_REQUEST[ pp_kom_tax() ] );
	$interessenter = pp_int_users();
	if ( count( $interessenter ) ) {
?>
		<h3>Data funnet, tabell</h3>
		<table>
		<tr><th scope="col" style="text-align: left;">Navn</th><th scope="col" style="text-align: left;">Epost</th><th scope="col" style="text-align: left;">Dato</th><th scope="col" style="text-align: left; padding-left: 1em;">Telefon</th><th scope="col" style="text-align: left; padding-left: 2em;">Fylke</th><th scope="col" style="text-align: left; padding-left: 2em;">Kommuner</th></tr>
<?php
		foreach ( $interessenter as $interessent ) {
			$kommuner = get_user_meta( $interessent->ID, pp_pro_kom_meta(), true );
			$fylke    = get_user_meta( $interessent->ID, pp_pro_fyl_meta(), true );
			echo PHP_EOL, '<tr><th scope="row" style="text-align: left; min-width: 8em; vertical-align: top;">', $interessent->display_name, '</th><td style="min-width: 18em; vertical-align: top;">', $interessent->user_email, '</td><td style="vertical-align: top;">', substr( $interessent->user_registered, 0, 10 ), '<td style="vertical-align: top; padding-left: 1em;">', get_user_meta( $interessent->ID, pp_tel_meta(), true ), '</td><td style="vertical-align: top; padding-left: 2em;">', $fylke, '</td><td style="padding-left: 2em; white-space: nowrap; max-width: 95%; display: block; overflow: auto;">', $kommuner, '</td></tr>';
		}
		$query = isset( $_REQUEST[ pp_kom_tax() ] ) ? '?' . pp_kom_tax() . '=' . $search : '';
?>
		</table>
		<hr />
		<h3>Eksporter til fil</h3>
		<p>
			<br />
			<a href="<?php echo get_stylesheet_directory_uri(), '/includes/', pp_int_name(), '-excel.php', $query; ?>">
				<button class="button">Last ned <code>.csv</code>-fil til åpning i, eller import til, <strong>Microsoft Excel</strong></button></a>
				&mdash; med navn, epost, dato, tlf, fylke og kommuner (skilletegn <em>semikolon</em><?php echo $search ? ', kun for ' . $search : ''; ?>)
		</p>
		<p>
			<br />
			<a href="<?php echo get_stylesheet_directory_uri(), '/includes/', pp_int_name(), '-google.php', $query; ?>">
				<button class="button">Last ned <code>.csv</code>-fil til import til f.eks <strong>Google Disk</strong> Regneark</button></a>
				&mdash; med navn, epost, dato, tlf, fylke og kommuner (skilletegn <em>komma</em><?php echo $search ? ', kun for ' . $search : ''; ?>)
		</p>
		<p>
			<br />
			<a href="<?php echo get_stylesheet_directory_uri(), '/includes/', pp_int_name(), '-xml.php', $query; ?>">
				<button class="button">Last ned <code>.xml</code>-fil til generell <strong>XML</strong>-import</button></a>
				&mdash; med navn, epost, dato, tlf, fylke og kommuner<?php echo $search ? ' (kun for ' . $search . ')' : ''; ?>
		</p>
<?php
		if ( class_exists( 'WYSIJA_object' ) ) {
?>
		<p>
			<br />
			<a href="<?php echo get_stylesheet_directory_uri(), '/includes/', pp_int_name(), '-mailpoet.php', $query; ?>">
				<button class="button">Last ned <code>.csv</code>-fil for import til <strong>MailPoet</strong></button></a> <a href="admin.php?page=wysija_subscribers&action=import" title="Importer abonnenter">&raquo; Abonnenter &raquo; Importer</a> &raquo; Last opp en fil
				&mdash; med epost og navn (skilletegn <em>komma</em><?php echo $search ? ', kun for ' . $search : ''; ?>)
		</p>
		<hr />
		<h4><em>Alternativt:</em> Tekstboks for kopiering til <a href="admin.php?page=wysija_subscribers&action=import" title="Importer abonnenter">MailPoet &raquo; Abonnenter &raquo; Importer</a> &raquo; Lim inn i tekstboks</h4>
		<textarea cols="80" readonly="readonly" rows="<?php echo count( pp_int_users() ) + 1; ?>" style="height: <?php echo 2 * ( count( pp_int_users() ) + 1 ); ?>em; z-index: 9; margin-bottom: .2em;" title="Merk og kopier alt, tast: Ctrl+a Ctrl+c">
<?php
		echo 'email; first name; last name', PHP_EOL;
		foreach ( pp_int_users() as $interessent ) {
			echo $interessent->user_email, '; ', $interessent->first_name && $interessent->last_name ? $interessent->first_name . '; ' . $interessent->last_name : $interessent->display_name . ';', PHP_EOL;
		}

?>
		</textarea>
	</div>
<?php
		}
	} else
	echo '<p>Intet funnet for ', $search, '.</p>';
}

function pp_admin_menu() {
	if ( 3 == get_current_blog_id() ) {
		add_users_page( 'Export', 'Eksport', 'edit_posts', 'export-' . pp_int_name(), 'pp_int_page' );
	}
	if ( ! current_user_can( 'switch_themes' ) ) {
		remove_menu_page( 'pods' );
	}
	if ( BLOG_ID_CURRENT_SITE != get_current_blog_id() && 7 != get_current_blog_id() ) {
		$settings_id = 'pp-settings';
		add_options_page( 'Beskrivelse', 'Beskrivelse', 'edit_users', $settings_id, 'pp_settings_page' );
		add_filter( 'option_page_capability_' . $settings_id, function( $cap ) { return 'publish_pages'; } );
	}

}

function pp_add_user_to_registering_blog( $user_id ) {
	$blog_id = get_current_blog_id();
	if ( ! is_user_member_of_blog( $user_id, $blog_id ) )
		add_user_to_blog( $blog_id, $user_id, get_option( 'default_role' ) );
}

function pp_register_extra_fields( $user_id, $password, $meta ) {
	$userdata = array();
	$userdata['ID'] = $user_id;
	$userdata['first_name'] = ucwords( strtolower( esc_attr( trim( $_POST['first'] ) ) ) );
	$userdata['last_name']  = ucwords( strtolower( esc_attr( trim( $_POST['last' ] ) ) ) );
	$userdata['display_name'] = $userdata['first_name'] . ' ' . $userdata['last_name'];
	wp_update_user( $userdata );
//	update_user_meta( $user_id, 'closedpostboxes_dashboard', array('dashboard_quick_press','dashboard_serverinfo') );
}

function pp_update_user_fields( $user_id ) {
	if ( current_user_can( 'edit_user', $user_id ) ) {
		$user = get_userdata( $user_id );
		if ( $user->user_login == get_user_meta( $user_id, 'nickname', true ) && ( $user->display_name == trim( $_POST['display_name'] ) || empty( $user->display_name ) ) ) {
			$_POST['display_name'] = ucfirst( trim( $_POST['first_name' ] ) ) . ' ' . ucfirst( trim( $_POST['last_name' ] ) );
		}
	}
}

function pp_login_redirect( $redirect_to, $request, $user ){
	global $user;
	// TODO: Sentraliser redirect-sider til config-array
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		if (     3 == get_current_blog_id() && in_array( 'subscriber', $user->roles ) )
			return get_page_link( 415 );
		elseif ( 3 == get_current_blog_id() && ( in_array( 'contributor', $user->roles ) || in_array( 'utbygger', $user->roles ) ) )
			return get_page_link( 430 );
		elseif ( 5 == get_current_blog_id() && ( in_array( 'subscriber', $user->roles ) || in_array( 'contributor', $user->roles ) || in_array( 'aktivitor', $user->roles ) ) )
			return get_page_link( 106 );
		elseif ( 7 == get_current_blog_id() && ( in_array( 'subscriber', $user->roles ) || in_array( 'contributor', $user->roles ) || in_array( 'aktivitor', $user->roles ) ) )
			return get_page_link( 1248 );
		else
			return $redirect_to;;
	} else
		return $redirect_to;
}

function pp_logout_redirect() {
  wp_redirect( home_url() );
  exit;
}

function pp_change_comment_status() {
	$post_type = pp_get_current_post_type();
	if ( 'post' != $post_type && pp_uke_type() != $post_type ) {
		add_filter( 'option_default_comment_status', '__return_false' );
		add_filter( 'option_default_ping_status', '__return_false' );
	}
}

function pp_remove_comments_website( $fields ) {
	unset( $fields['url'] );
	return $fields;
}

function pp_ann_excerpt( $output ) {
	if ( empty( $output ) )
		$output = get_the_post_thumbnail( null, 'thumbnail' );
	return str_replace( ' href="', ' xref="', $output );
}

function pp_force_excerpt() {	// View mode on list (edit.php)
	$post_type = pp_get_current_post_type();
	if ( pp_ann_type() == $post_type && $_REQUEST['mode'] != 'list' ) {
		$_REQUEST['mode'] = 'excerpt';
		add_filter( 'get_the_excerpt', 'pp_ann_excerpt' );
	}
}

function pp_remove_dashboard_widgets() {
	global $wp_meta_boxes;
	if ( ! current_user_can( 'publish_posts' ) ) {
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
	}
}

function pp_one_term_only( $content ) {
	global $one_term_tax;
		$content = str_replace( ' type="checkbox" name="tax_input[' . $one_term_tax . ']',            ' type="radio" name="tax_input[' . $one_term_tax . ']',            $content );
	foreach ( get_terms( $one_term_tax, array( 'fields' => 'ids' ) ) as $id ) {
		$content = str_replace( ' value="' . $id . '" type="checkbox"',                               ' value="' . $id . '" type="radio"',                               $content );
		$content = str_replace( ' id="in-'         . $one_term_tax . '-' . $id . '" type="checkbox"', ' id="in-'         . $one_term_tax . '-' . $id . '" type="radio"', $content );
		$content = str_replace( ' id="in-popular-' . $one_term_tax . '-' . $id . '" type="checkbox"', ' id="in-popular-' . $one_term_tax . '-' . $id . '" type="radio"', $content );
	}
	return $content;
}

function x_pp_welcome_user_notification() {
	if ( current_user_can( 'create_users' ) && current_user_can( 'add_users' ) )
		return false;
	else
		return true;
}

function pp_site_option_site_admins( $users ) {
	global $user_login;
	get_currentuserinfo();
	$uadms = get_users( array( 'role' => 'useradmin' ) );
	$adms = array();
	foreach ( $uadms as $user )
		$adms[] = $user->user_login;
	if ( in_array( $user_login, $adms ) )
		$users = array_merge( $users, array( $user_login ) );
	return $users;
}

function pp_roles_dropdown( $roles ) {
	global $user_login;
	get_currentuserinfo();

	if ( ! in_array( $user_login, array( explode( ', ', strip_tags( wp_get_theme()->get( 'Author') ) ) ) ) ) {
		unset ( $roles['administrator'] );
		unset ( $roles['useradmin'] );
	}
	return $roles;
}

// For at det bare skal vises fylker for annonser på SeniorBoPortalen og BPA-Portalen, samt aktiviteter på BPA-Portalen
function pp_get_terms_args( $args, $taxonomies ) {
	if ( in_array( pp_kom_tax(), $taxonomies ) )
		$args['parent']  = 0;
	return $args;
}

function pp_admin_notes_caps( $allcaps, $cap, $args ) {
	if ( 'activate_plugins' == $args[0] && $allcaps[ 'edit_posts'] ) {
		$post = get_post( $args[2] );
		if ( current_user_can( 'edit_post', get_post( $args[2] )->ID ) )
			$allcaps[ $cap[0] ] = true;
	}
	return $allcaps;
}

function pp_admin_init( $task ) {
	global $pagenow;
	if     ( 'user-new.php' == $pagenow )
		add_filter( 'site_option_site_admins', 'pp_site_option_site_admins' );
	elseif ( in_array( get_current_blog_id(), array( 3 ) ) && in_array( pp_get_current_post_type(), array( pp_akt_type(), pp_ann_type() ) ) && ( 'edit.php' == $pagenow || 'post.php' == $pagenow || 'post-new.php' == $pagenow ) )
		add_filter( 'get_terms_args', 'pp_get_terms_args', 10, 2 );
	if     ( in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) )
		add_filter( 'user_has_cap', 'pp_admin_notes_caps', 10, 3 );
}

function pp_user_new_form_tag() {
	$script = "if( this.name == 'adduser' ) var e = getElementById( 'adduser-noconfirmation' ); else var e = getElementById( 'noconfirmation' ); if ( ! e.checked ) return( confirm( 'Vil du virkelig at det først skal sendes epost for å få bekreftelse fra den nye brukeren? ( Avbryt = Nei, jeg har bare glemt krysse av for «Hopp over ...» )' ) )";
	echo ' onsubmit="', $script, '"';
	add_filter( 'editable_roles', 'pp_roles_dropdown' );
}

function pp_is_type( $site, $type ) {	// Helper function for pp_admin_catcher()
	return ( empty( $site ) || get_current_blog_id() == $site ) && ( trim( esc_attr( $_GET['post_type'] ) ) == $type || ( 'edit' == trim( esc_attr( $_GET['action'] ) ) && get_post_type( intval( $_GET['post'] ) ) == $type ) );
}

function pp_admin_catcher() {
	global $one_term_tax;
	$path  = strtolower( untrailingslashit( esc_attr( $_SERVER['PHP_SELF'] ) ) );
	if ( in_array( substr( substr( $path, 10 ), 0, -4 ), array( 'post-new', 'post', 'edit' ) ) ) {
		$one_term_tax = false;
		if     ( pp_is_type( 0, pp_ann_type() ) )
			$one_term_tax = pp_alev_tax();
		elseif ( pp_is_type( 3, pp_pro_type() ) || pp_is_type( 5, pp_akt_type() )  || pp_is_type( 7, pp_akt_type() ) )
			$one_term_tax = pp_kom_tax();
		elseif ( pp_is_type( 8, pp_prd_type() ) )
			$one_term_tax = pp_forh_tax();
		if ( $one_term_tax )
			ob_start( 'pp_one_term_only' );
	}
}

function pp_option_members_settings( $value ) {
	if ( defined( 'PP_PRIVATE' ) )
		$value['private_blog'] = PP_PRIVATE;
	return $value;
}

function pp_post_class( $classes ) {
	global $post;
	$tags = array();
	foreach ( wp_get_post_tags( $post->ID ) as $tag )
		$tags[] = $tag->slug;
	if ( in_array( 'fremhevet', $tags ) )
		$classes[] = 'tag-fremhevet';
	return $classes;
}

function pp_terms_orderby( $orderby, $args, $taxonomies ) {
	if ( 'term_group' == $args['orderby'] && pp_kom_tax() == $taxonomies[0] ) {
		$orderby = 't.term_group, t.name';
	}
	return $orderby;
}

function pp_loop_start( $query ) {
	if ( $query && $query->is_main_query() && ! $query->is_feed() ) {
		$alev_termobs = get_terms( pp_alev_tax(), array( 'orderby' => 'term_group', 'order' => 'ASC' ) );
		$alev_terms = array();
		foreach ( $alev_termobs as $alev_term )
			$alev_terms[] = $alev_term->slug;
		$meta_query = array( 'relation' => 'OR',
			array( 'key' => 'annonsesluttdato', 'compare' => 'NOT EXISTS' ),
			array( 'key' => 'annonsesluttdato', 'compare' =>  '=', 'value' => 0, 'type' => 'UNSIGNED' ),
			array( 'key' => 'annonsesluttdato', 'compare' => '>=', 'value' => current_time( 'mysql' ), 'type' => 'DATETIME' )
		);
		if ( $annonser && is_array( $annonser ) && count( $annonser ) ) {
			$source = 'transient';
		} else {
			$source = 'fresh';
			$annonser = array();
			$alev = 0;
			while( empty( $annonser['mobi'] ) && $alev < count( $alev_terms ) ) {
				$annonse = get_posts( array(
					'posts_per_page' => 1,
					'post_type' => pp_ann_type(),
					'tax_query' => array( 'relation' => 'AND',
						array( 'taxonomy' => pp_apos_tax(), 'field' => 'slug', 'terms' => 'smal' ),
						array( 'taxonomy' => pp_alev_tax(), 'field' => 'slug', 'terms' => $alev_terms[ $alev ] )
					),
					'meta_query' => $meta_query,
					'exclude' => $idsx
				) );
				if ( count( $annonse ) ) {
					$annonse[0]->src = $annonse[0]->ID . ' smal-' . $alev_terms[ $alev ];
					$annonser['mobi'] = $annonse[0];
					$idsx[] = intval( $annonse[0]->ID );
				}
				$alev++;	// Fra pri-1 til pri-3 via $alev_terms
			}
			while( empty( $annonser['mobi'] ) && $alev < count( $alev_terms ) ) {
				$annonse = get_posts( array(
					'posts_per_page' => 1,
					'post_type' => pp_ann_type(),
					'tax_query' => array( 'relation' => 'AND',
						array( 'taxonomy' => pp_apos_tax(), 'field' => 'slug', 'terms' => pp_head_term()       ),
						array( 'taxonomy' => pp_alev_tax(), 'field' => 'slug', 'terms' => $alev_terms[ $alev ] )
					),
					'meta_query' => $meta_query,
					'exclude' => $idsx
				) );
				if ( count( $annonse ) ) {
					$annonse[0]->src = $annonse[0]->ID . ' ' . pp_head_term() . '-' . $alev_terms[ $alev ];
					$annonser['mobi'] = $annonse[0];
					$idsx[] = intval( $annonse[0]->ID );
				}
				$alev++;	// Fra pri-1 til pri-3 via $alev_terms
			}
		}
		echo PHP_EOL, '<hr class="annonse-mobi" />';
		pp_widget_annonser_li( $annonser, false, null, $source, 'p' );
		echo PHP_EOL, '<hr class="annonse-mobi" />';
	}
}

function pp_admin_notice() {
?>
        <h3><?php bloginfo( 'name' ) ?></h3>
<?php
}

function pp_dfi_skip_forum ( $dfi_id, $post_id ) {
	if( 'forum' == get_post_type( $post_id ) ) {
		$att_ids = pp_forum_thumbnails();
		$dfi_id = $att_ids[ get_current_blog_id() ];
	}
	return $dfi_id;
}

function pp_default_styles( $styles ) {
	$styles->default_version .= '-' . date( 'd' );
}

function pp_no_morelink_scroll( $link ) {
	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;
}

function pp_request( $qv ) {
	if ( ! in_array( get_current_blog_id(), array( 1, 2, 4, 10 ) ) ) {	// 2 Midlertidig, ikke oppskrifter og ukemenyer ennå
		if ( isset( $qv['feed'] ) ) {
			$qv['post_type'] = array_merge( array( 'post' ), pp_cpts() );
		}
	}
	return $qv;
}

function pp_change_welcome_mail_loginlink( $welcome_email, $user_id, $password, $meta ) {
//	if ( BLOG_ID_CURRENT_SITE != get_current_blog_id() ) {
		$welcome_email = str_replace( 'LOGINLINK', get_current_blog_id(), $welcome_email );
//	}
	return $welcome_email;
}

function pp_head() {
	if ( in_array( get_current_blog_id(), array( 1, 5, 7 ) ) || 'page' != get_option( 'show_on_front' ) ) {
?>
 <meta name="designer" content="<?php echo esc_url( wp_get_theme()->get( 'AuthorURI' ) ); ?>">
<?php
	}
	if ( pp_akt_type() != get_post_type() ) {
?>
 <meta name="og:latitude" content="59.2343052">
 <meta name="og:longitude" content="10.9999803">
 <meta name="og:street-address" content="Strykerveien 10">
 <meta name="og:locality" content="Torp">
 <meta name="og:region" content="Østfold">
 <meta name="og:postal-code" content="1658">
 <meta name="og:country-name" content="Norway">
 <meta name="og:email" content="post@portalene.no">
 <meta name="og:phone_number" content="+47 94152300 ">
<?php
	}
}

function pp_register_url( $link ) {
	if     ( 2 == get_current_blog_id() )
		return site_url( '/registrering/', 'login' );
	elseif ( 3 == get_current_blog_id() )
		return site_url( '/2013/registrer-interessent/', 'login' );
	elseif ( 5 == get_current_blog_id() )
		return site_url( '/publiser/registrering/', 'login' );
	elseif ( 7 == get_current_blog_id() )
		return site_url( '/bpa-aktivitetskalender/registrering-som-aktivitor/', 'login' );
	else
		return $link;
}

function pp_fix_register_urls( $url, $path, $orig_scheme ) {
	if ( $orig_scheme == 'login' && $path == 'wp-login.php?action=register' ) {
		if     ( 2 == get_current_blog_id() )
			return site_url( '/registrering/', 'login' );
		elseif ( 3 == get_current_blog_id() )
			return site_url( '/2013/registrer-interessent/', 'login' );
		elseif ( 5 == get_current_blog_id() )
			return site_url( '/publiser/registrering/', 'login' );
		elseif ( 7 == get_current_blog_id() )
			return site_url( '/bpa-aktivitetskalender/registrering-som-aktivitor/', 'login' );
	}
	return $url;
}

function pp_mail_from( $original ) {
	$domain = get_blog_details( PP_DOMAIN_SITE );
	$domain = explode( '/', $domain->siteurl );
	return 'post@' . $domain[2];
}

function pp_mail_from_name( $original ) {
	return get_bloginfo( 'name' );
}

function pp_single_template( $single_template ) {
	if ( 3 == get_current_blog_id() && is_single( 'registrer-interessent' ) )
		$single_template = dirname( __FILE__ ) . '/registrer-interessent.php';
	return $single_template;
}

function pp_shorter_pass( $password ) {
	return substr( $password, 0, 6 );	// Fails on Jetpack connect!
//	return $password;	// Jetpack will only connect with this return
}

function pp_login_logo() { ?>
    <style type="text/css">
		body.login div#login { padding-top: 2%; }
		body.login div#login h1 {font-size: 1.6em; }
		body.login div#login h1:after { content: "Logg inn på <?php bloginfo( 'name' ); ?>"; line-height: 120%; }
		body.login div#login h1 a {
		background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/<?php echo $_SERVER['HTTP_HOST'] ;?>.png);
		width: 150px; height: 150px;
		background-size: 150px;
		padding-bottom: 0px;
        }
    </style>
<?php
}

function pp_edit_profile_url( $url ) {
	/*  */if ( 2 == get_current_blog_id() ) {
		if ( ! current_user_can( 'edit_posts' ) )
			$url = site_url( '/forums/users/' );
	} elseif ( 3 == get_current_blog_id() ) {
		if ( ! current_user_can( 'publish_posts' ) )
			$url = site_url( '/din-profil-som-utbygger/' );
		if ( ! current_user_can( 'edit_posts' ) )
			$url = site_url( '/din-profil/' );
	} elseif ( 5 == get_current_blog_id() ) {
		if ( ! current_user_can( 'publish_posts' ) )
			$url = site_url( '/publiser/profil/' );
	} elseif ( 7 == get_current_blog_id() ) {
		if ( ! current_user_can( 'publish_posts' ) )
			$url = site_url( '/bpa-aktivitetskalender/din-profil-som-aktivitetsforfatter/' );
	}
	return $url;
}

function pp_registered_taxonomy( $taxonomy ) {
	global $wp_taxonomies;
	if ( in_array( get_current_blog_id(), array( 5, 7 ) ) && in_array( $taxonomy, array( pp_aktt_tax(), pp_kom_tax() ) ) )
		$wp_taxonomies[ $taxonomy ]->cap->assign_terms = 'edit_' . pp_akt_type();
}

function pp_user_query( $user_query ) {
	global $wpdb;
	if ( ! empty( $user_query->query_vars['search'] ) ) {
		$term = str_replace( '*', '%', $user_query->query_vars['search'] );
		$user_query->query_from .=
			" INNER JOIN {$wpdb->usermeta} u1 ON {$wpdb->users}.ID=u1.user_id AND u1.meta_key='first_name'" .
			" INNER JOIN {$wpdb->usermeta} u2 ON {$wpdb->users}.ID=u2.user_id AND u2.meta_key='last_name' ";
		$name_where = $wpdb->prepare( "u1.meta_value LIKE '%s' OR u2.meta_value LIKE '%s' OR {$wpdb->users}.display_name LIKE '%s'", $term, $term, $term );
		$user_query->query_where = str_replace( 'WHERE 1=1 AND (', "WHERE 1=1 AND ({$name_where} OR ", $user_query->query_where );
	}
}

function pp_add_registered_column( $columns ) {
	if ( isset( $_GET['role'] ) && ! empty( $_GET['role'] ) ) {
		unset( $columns['role'] );
		$role = get_role( esc_attr( $_GET['role'] ) );
		if ( ! $role->has_cap( 'edit_posts' ) )
			unset( $columns['posts'] );
	}
	if     ( 2 == get_current_blog_id() )
		;
	elseif ( 3 == get_current_blog_id() && 'utbygger' == $_GET['role'] ) {
		$columns['firma'] = 'Firma';
		$columns[ pp_pro_type() ] = 'Prosjekter';
	} elseif ( in_array( get_current_blog_id(), array( 5, 7 ) ) ) {
		if ( 5 == get_current_blog_id() )
			$columns['nickname'] = 'Forening';
		$columns[ pp_akt_type() ] = 'Aktiviteter';
	}
	$columns['registered'] = 'Registrert';
 	return $columns;
}

function pp_manage_users_custom_columns( $value='', $column_name, $user_id ) {
	global $wp_roles;
	$roles = $wp_roles->get_names();
	$roles['subscriber']  = 'Abonnent';
	$roles['contributor'] = 'Bidragsyter';
	$roles['author']      = 'Forfatter';
	$roles['editor']      = 'Redaktør';
	$user = new WP_User( $user_id );
	$role = implode( ', ', $user->roles );
	if ( 'nickname' == $column_name )
		$value = get_user_meta( $user_id, 'nickname', true );
	elseif ( 'registered' == $column_name )
		$value = mysql2date( get_option( 'date_format'), $user->user_registered, true );
	elseif ( 'firma' == $column_name )
		$value = get_user_meta( $user_id, 'pp_firma', true );
	foreach ( pp_cpts() as $post_type ) {
		if ( $post_type == $column_name ) {
			$value = count_user_posts_by_type( $user_id, $post_type );
			if ( $value )
				$value = '<a class="edit" title="Vis alle ' . $post_type . 'er av denne ' . ( $roles && $role ? strtolower( $roles[ $role ] ) : 'forfatter' ) . 'en (' . $user->display_name . ')" href="edit.php?post_type=' . $post_type . '&amp;author=' . $user_id . '">' . $value . '</a>';
		}
	}
	return $value;
}

function pp_ukedager( $post_id, $post ) {
	if ( pp_akt_type() == $post->post_type ) {
//		add_post_meta( $post_id, '_did', 'pp_ukedager', true );
		$ukedager = get_post_meta( $post_id, '_ukedager', false );
		add_post_meta( $post_id, '_aukedager', $ukedager, true );
		if ( is_array( $ukedager ) && count( $ukedager ) ) {
			delete_post_meta( $post_id, 'ukedager', '' );
			foreach ( $ukedager as $ukedag )
				add_post_meta( $post_id, 'ukedager', $ukedag, false );
		}
	}
}

function pp_save_aktivitet( $post_id, $post, $updated ) {
//	var_dump( $_POST );exit;
	$periods  = array( 68 => 'days', 77 => 'months', 85 => 'weeks', 195 => 'years' );
	$weekdays = array( 'ma' => 'Monday', 'ti' => 'Tuesday', 'on' => 'Wednesday', 'to' => 'Thursday', 'fr' => 'Friday', 'lo' => 'Saturday', 'so' => 'Sunday' );

	if ( isset( $_POST['lib-image'] ) && ! empty( $_POST['lib-image'] ) )
		set_post_thumbnail( $post_id, intval( $_POST['lib-image'] ) );

	delete_post_meta( $post_id, '_date' );
	if ( 'editpost' == $_POST['action'] ) {
		$ukedag  = $weekdays[ substr( mb_str_replace( 'ø', 'o', trim( $_POST['pods_meta_ukedag'   ] ) ), 0, 2 ) ];
		$nukedag = intval( trim( $_POST['pods_meta_n_ukedag'   ] ) );
//		$times   = intval( trim( $_POST['pods_meta_ganger'   ] ) );
		$every   = intval( trim( $_POST['pods_meta_interval' ] ) );
		if ( isset( $_POST['pods_meta_gjenta'] ) ) {
			$gjenta = trim( $_POST['pods_meta_gjenta'] );
			$every = substr( $gjenta, 0, 1 );
			if ( is_numeric( $every ) )
				$every = intval( $every );
			else
				$every = 1;
			$_POST['pods_meta_interval'] = $every;
		}
		$period  = trim( $_POST['pods_meta_periode'  ] );
		if ( 'uke' == $period && isset( $_POST['pods_meta_ukedager'] ) ) {
			$weekd = $_POST['pods_meta_ukedager'];
			$wdays = array();
			if ( is_array( $weekd ) ) {
				foreach ( $weekd as $day ) {
					$wdays[] = $weekdays[ substr( mb_str_replace( 'ø', 'o', $day ), 0, 2 ) ];
				}
			} else
				$wdays[] = $weekdays[ substr( mb_str_replace( 'ø', 'o', $weekd ), 0, 2 ) ];
		}
		$start_date = date( 'Y-m-d', strtotime( esc_attr( $_POST['pods_meta_startdato'] ) ) );
		if ( isset( $_POST['pods_meta_sluttdato'] ) && $_POST['pods_meta_sluttdato'] == $_POST['pods_meta_startdato'] )
			unset( $_POST['pods_meta_sluttdato'] );
		if ( isset( $_POST['pods_meta_sluttdato'] ) )
			$end_date = date( 'Y-m-d', strtotime( esc_attr( $_POST['pods_meta_sluttdato'] ) ) );
	} else {
		if ( is_array( $_POST['ukedag'   ] ) )
			$ukedag  = $weekdays[ substr( mb_str_replace( 'ø', 'o', trim( esc_attr( $_POST['ukedag'   ][0] ) ) ), 0, 2 ) ];
		else
			$ukedag  = $weekdays[ substr( mb_str_replace( 'ø', 'o', trim( esc_attr( $_POST['ukedag'   ]    ) ) ), 0, 2 ) ];
		if ( is_array( $_POST['n_ukedag'   ] ) )
			$nukedag = intval( trim( $_POST['n_ukedag'   ][0] ) );
		else
			$nukedag = intval( trim( $_POST['n_ukedag'   ]    ) );
//		if ( is_array( $_POST['ganger'   ] ) )
//			$times   = intval( trim( $_POST['ganger'   ][0]       ) );
//		else
//			$times   = intval( trim( $_POST['ganger'   ]          ) );
//		if ( is_array( $_POST['interval'] ) )
//			$every   = intval( trim( $_POST['interval' ][0], '. ' ) );
//		else
//			$every   = intval( trim( $_POST['interval' ]   , '. ' ) );
		if ( is_array( $_POST['gjenta'] ) )
			$gjenta   = trim( $_POST['gjenta' ][0], '. ' );
		else
			$gjenta   = trim( $_POST['gjenta' ]   , '. ' );
		if ( ! empty( $gjenta ) ) {
			$every = substr( $gjenta, 0, 1 );
			if ( is_numeric( $every ) )
				$every = intval( $every );
			else
				$every = 1;
			if ( 0 >= $every )
				$every = 1;
			$_POST['interval'] = $every;
			$_POST['pods_meta_interval'] = $every;
		}
		if ( is_array( $_POST['periode'] ) )
			$period  =         trim( $_POST['periode'  ][0]       );
		else
			$period  =         trim( $_POST['periode'  ]          );
		if ( 'uke' == $period && isset( $_POST['ukedager'] ) ) {
			$weekd = $_POST['ukedager'];
			$wdays = array();
			if ( is_array( $weekd ) ) {
				$_POST['pods_meta_ukedager'] = array();
				foreach ( $weekd as $day ) {
					$wdays[] = $weekdays[ substr( mb_str_replace( 'ø', 'o', $day ), 0, 2 ) ];
					if     ( 'mandag'  == $day )
						$_POST['pods_meta_ukedager'][0] = $day;
					elseif ( 'tirsdag' == $day )
						$_POST['pods_meta_ukedager'][1] = $day;
					elseif ( 'onsdag'  == $day )
						$_POST['pods_meta_ukedager'][2] = $day;
					elseif ( 'torsdag' == $day )
						$_POST['pods_meta_ukedager'][3] = $day;
					elseif ( 'fredag'  == $day )
						$_POST['pods_meta_ukedager'][4] = $day;
					elseif ( 'lørdag'  == $day )
						$_POST['pods_meta_ukedager'][5] = $day;
					elseif ( 'søndag'  == $day )
						$_POST['pods_meta_ukedager'][6] = $day;
					add_post_meta( $post_id, '_ukedager', $day, false );
				}
			} else
				$wdays[] = $weekdays[ substr( mb_str_replace( 'ø', 'o', $weekd ), 0, 2 ) ];
			unset( $_POST['ukedager'] );
			//add_action( 'save_post', 'pp_ukedager', 32767, 2 );
		}
		if ( isset ( $_POST['n_ukedag'] ) )
			$_POST['pods_meta_n_ukedag'] = $_POST['n_ukedag'];
		if ( isset( $_POST['ukedag'] ) )
			$_POST['pods_meta_ukedag']   = $_POST['ukedag'];
		update_post_meta( $post_id, 'gb_admin_note', 'Opprinnelig ' . esc_attr( $_POST['gjenta'] . ' ' . $_POST['periode'] . ' ' . implode( ', ', $_POST['ukedager'] ) . ( ! empty( $_POST['ukedag'] ) ? ' ' . $nukedag . '. ' . $_POST['ukedag'] : '' ) ) );
		$start_date = date( 'Y-m-d', strtotime( esc_attr( $_POST['startdato'] ) ) );
		if ( isset( $_POST['sluttdato'] ) && $_POST['sluttdato'] == $_POST['startdato'] )
			unset( $_POST['sluttdato'] );
		if ( isset( $_POST['sluttdato'] ) ) {
			if ( is_array( $_POST['sluttdato'   ] ) )
				$end_date = date( 'Y-m-d', strtotime( esc_attr( $_POST['sluttdato'][0] ) ) );
			else
				$end_date = date( 'Y-m-d', strtotime( esc_attr( $_POST['sluttdato']    ) ) );
		}
	}//var_dump($weekd);echo '<br/>';var_dump( $wdays );exit;
	update_post_meta( $post_id, '_debug', 'POST Hver ' . $every . '. ' . $period . ' ' . implode( ', ', $wdays ) . $nukedag . '. ' . $ukedag . ' til ' . $end_date, ' img ' . intval( $_POST['lib-image'] ) ); // DEBUG INFO!
//	update_post_meta( $post_id, '_post', 'POST ' . print_r( $weekd, true ) . ' ' . print_r( $_POST, true ) ); // DEBUG INFO!
	if ( $every && $period ) {
		$n = 0;
		if ( 'M' == substr( ucfirst( $period ), 0, 1 ) && 'ukedag' == substr( $period, -6 ) && $nukedag ) {
			$first = strtotime( '+1 month', mysql2date( 'U', $start_date ) );
			for ( $d = $first; $n < 13 && $d <= mysql2date( 'U', $end_date ) + WEEK_IN_SECONDS; $d = strtotime( '+' . $every . ' month', $d ) ) {
				$d = mktime( 0, 0, 0, idate( 'm', $d ), 1, idate( 'Y', $d ) );	// Første i måneden
				$d = strtotime( '-1 day', $d );	// Siste i måneden foran
				for ( $i = 1; $i <= $nukedag; $i++ )
					$d = strtotime( 'next ' . $ukedag, $d );	// Neste ukedag, n ganger
				$last_date = date( 'Y-m-d', $d );
				add_post_meta( $post_id, '_date', $last_date, false );
				$n++;
			}
		} elseif ( 'uke' == $period && is_array( $wdays ) && count( $wdays ) ) {
			foreach ( $wdays as $wday ) {
				$first = strtotime( 'Next ' . $wday, mysql2date( 'U', $start_date ) );
				if ( 1 < $every )
					for ( $e = 2; $e <= $every; $e++ )
						$first = strtotime( 'Next ' . $wday, $first );
//				add_post_meta( $post_id, '_debug2', $wday, false );
				for ( $d = $first; $n < 366 && $d <= mysql2date( 'U', $end_date ); $d = strtotime( '+' . $every . ' week', $d ) ) {
					$last_date = date( 'Y-m-d', $d );
					add_post_meta( $post_id, '_date', $last_date, false );
					$n++;
				}
			}
//		} elseif ( $times ) {
//			for ( $i = 1; $n < 99 && $i <= $times; $i++ ) {
	//			$p = ord( ucfirst( $period ) );	// TODO
//				$last_date = date( 'Y-m-d', strtotime( '+' . ( $i * $every ) . ' ' . $periods[ ord( ucfirst( $period ) ) ], mysql2date( 'U', $start_date ) ) );
//				add_post_meta( $post_id, '_date', $last_date, false );
//				$n++;
//			}
		} elseif ( $end_date ) {
			$first = strtotime( '+' . $every . ' ' . $periods[ ord( ucfirst( $period ) ) ], mysql2date( 'U', $start_date ) );
			for ( $d = $first; $n < 99 && $d <= mysql2date( 'U', $end_date ); $d = strtotime( '+' . $every . ' ' . $periods[ ord( ucfirst( $period ) ) ], $d ) ) {
				$last_date = date( 'Y-m-d', $d );
				add_post_meta( $post_id, '_date', $last_date, false );
				$n++;
			}
		}
	} else
		$last_date = $start_date;
	if ( $last_date )
		update_post_meta( $post_id, '_last_date', $last_date );
	else
		delete_post_meta( $post_id, '_last_date' );
}

function pp_widget_display( $instance, $this ) {
	$class = get_class( $this );
	if ( is_post_type_archive( 'forum' ) && (
		  'Jetpack_Subscriptions_Widget' == $class ||
		  'WP_Widget_Archives'           == $class ||
		( 'PodsWidgetView'               == $class && 'views/pods-widget-view-kom.php' == $instance['view'] )
		)
	)
		return false;
	return $instance;
}

function pp_title( $title, $post_id ) {
	if ( pp_akt_type() == get_post_type( $post_id ) ) {
		$fylker = get_the_terms( $post_id, pp_kom_tax() );
		if ( is_single() && is_array( $fylker ) ) {
			$fylker = array_values( $fylker );
			$title .= ' - ' . $fylker[0]->name;
		}
   }
   return $title;
}

function pp_jetpack_open_graph_output( $tag ) {
	global $post;
	$fylker = get_the_terms( $post->ID, pp_kom_tax() );
	if ( is_single() && is_array( $fylker ) && count( is_array( $fylker ) ) ) {
		$fylker = array_values( $fylker );
		if ( '<meta property="og:title"' == substr( $tag, 0, 25 ) )
			$tag = str_replace( '" />', ' - ' . $fylker[0]->name . '" />', $tag );
	}
	return $tag;
}

function pp_user_sortable_columns( $columns ) {
	$columns['registered'] = 'Registrert';
	return $columns;
}

function pp_user_column_orderby( $query ) {
	$vars = $query->query_vars;
	if ( isset( $vars['orderby'] ) && 'Registrert' == $vars['orderby'] ) {
		$query->query_orderby = "ORDER BY user_registered " . strtoupper( $vars['order'] );
	}
	return $vars;
}

function pp_user_register( $user_id, $password, $meta ) {
	$userdata = array();
	$userdata['ID'] = $user_id;
	$userdata['first_name'] = ucwords( strtolower( trim( esc_attr( $_POST['first'] ) ) ) );
	$userdata['last_name']  = ucwords( strtolower( trim( esc_attr( $_POST['last' ] ) ) ) );
	$userdata['display_name'] = $userdata['first_name'] . ' ' . $userdata['last_name'];
	wp_update_user( $userdata );
}

function pp_add_custom_post_types_to_activity_widget( $query ) {
	if ( 'dashboard' == get_current_screen()->id && 'draft' != $query->get( 'post_status' ) && 'note' != $query->get( 'post_type' ) ) {
		$query->set( 'post_type', get_post_types( array( 'public' => true ) ) );
	}
}

function pp_get_post_metadata( $nul, $object_id, $meta_key ) {
	if ( '_thumbnail_id' == $meta_key && 'topic' == get_post_type( $object_id ) )
		return 0;
	return null;
}

function pp_switch_site_rewrite( $site_id ) {
	global $post;
	if ( $post->blog_id && $post->blog_id != $site_id ) {
		if (     2 == $site_id ) {
			register_post_type( pp_opp_type(), array( 'rewrite' => true ) );
		} elseif ( 3 == $site_id ) {
			register_post_type( pp_pro_type(), array( 'rewrite' => array( 'slug' => 'bolig' ) ) );
		} elseif ( 4 == $site_id ) {
			register_post_type( pp_lev_type(), array( 'rewrite' => true ) );
		} elseif ( 5 == $site_id ) {
			register_post_type( pp_akt_type(), array( 'rewrite' => true ) );
		} elseif ( 7 == $site_id ) {
			register_post_type( pp_lev_type(), array( 'rewrite' => true ) );
		}
	}
}

include( 'includes/featured-widget.php' );
include( 'includes/portaler-widget.php' );
include( 'includes/non-portaler-widget.php' );
require( 'includes/portaler-submenu.php' );		// class
require( 'includes/inspirasjon-widget.php' );	// class
require( 'includes/after-content-widget.php' );	// Class
include( 'includes/settings.php' );
include( 'includes/wpuf-kommuner.php' );
include( 'includes/adminbar.php' );

if ( is_admin() ) {
	add_action( 'admin_menu', 'pp_admin_menu', 9999 );
	add_filter( 'manage_' . pp_lev_type() . '_posts_columns', 'pp_lev_columns' );
	add_filter( 'manage_' . pp_ann_type() . '_posts_columns', 'pp_ann_columns' );
	add_filter( 'manage_' . pp_akt_type() . '_posts_columns', 'pp_akt_columns' );
	add_filter( 'manage_posts_columns', 'pp_post_columns' );
	add_action( 'manage_posts_custom_column' , 'pp_custom_columns', 10, 2 );
	add_filter( 'manage_edit-' . pp_lev_type() . '_sortable_columns', 'pp_cust_sortable_columns' );
	add_action( 'admin_enqueue_scripts', 'pp_admin_style' );
	add_filter( 'wp_insert_post_data', 'pp_update_post_data', 99 );
	add_filter( 'user_contactmethods', 'pp_contactmethods' );
	add_action( 'in_admin_header', 'pp_archive_link' );
	add_action( 'wp_dashboard_setup', 'pp_dashboard_setup' );
	add_action( 'do_meta_boxes', 'pp_meta_boxes' );
	add_action( 'edit_user_profile_update', 'pp_update_user_fields' );
	add_action( 'personal_options_update' , 'pp_update_user_fields' );
	add_action( 'load-post-new.php', 'pp_change_comment_status' );
	add_action( 'load-edit.php', 'pp_force_excerpt' );
	add_action( 'wp_dashboard_setup', 'pp_remove_dashboard_widgets' );
	add_action( 'init', 'pp_admin_catcher' );
	add_action( 'in_admin_footer', 'pp_admin_footer' );
///	add_filter( 'wpmu_welcome_user_notification', 'pp_welcome_user_notification');
	add_action( 'admin_init', 'pp_admin_init' );
	add_action( 'admin_init', 'pp_settings' );
	add_action( 'user_new_form_tag', 'pp_user_new_form_tag' );
//	add_filter( 'list_terms_exclusions', 'pp_list_terms_exclusions', 10, 3 );
	add_action( 'pre_user_query', 'pp_user_query' );
	add_filter( 'manage_users_columns', 'pp_add_registered_column' );
	add_filter( 'manage_users_custom_column', 'pp_manage_users_custom_columns', 10, 3 );
	add_filter( 'manage_users_sortable_columns', 'pp_user_sortable_columns' );
	add_action( 'pre_user_query', 'pp_user_column_orderby' );
	add_action( 'pre_get_posts', 'pp_add_custom_post_types_to_activity_widget' );
	if ( wp_is_mobile() )
		add_action( 'admin_notices', 'pp_admin_notice' );
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		add_filter( 'site_option_site_admins', 'pp_site_option_site_admins' );
	}
//	add_filter( 'load_textdomain_mofile', function( $mofile ) {
//		$local_mofile = str_replace( WP_LANG_DIR . '/plugins/', WP_LANG_DIR . '/local-plugins/', $mofile );
//		$local_mofile = str_replace( WP_LANG_DIR . '/themes/', WP_LANG_DIR . '/local-themes/', $mofile );
//		if ( file_exists( $local_mofile ) ) {
//			return $local_mofile;
//		}
//		return $mofile;
//	});
} else {
	include( 'includes/toolbar.php' );
	remove_filter( 'the_content', 'wpuf_show_custom_fields' );
	add_filter( 'twentyfourteen_get_featured_posts', 'pp_get_featured_posts', 11, 1 );
	add_filter( 'body_class','pp_body_classes' );
//	add_filter( 'post_limits', 'pp_limits_lev' );
	add_action( 'twentyfourteen_credits', 'pp_credits' );
	add_filter( 'posts_join', 'pp_posts_join' );
	add_filter( 'posts_where', 'pp_posts_where' );
	add_action( 'admin_bar_menu', 'pp_toolbar_links', 71 );
	add_filter( 'dfi_thumbnail_id', 'pp_dfi_thumbnail_id', 10, 2 );
	add_filter( 'comment_form_default_fields', 'pp_remove_comments_website' );
	add_action( 'pre_get_posts', 'pp_pre_get_posts', 1 );
	pp_reg_jqs( pp_jqscripts() );
	add_action( 'wp_print_scripts', 'pp_add_jqscripts' );
	add_filter( 'wp_get_nav_menu_items', 'pp_get_nav_menu_items', 10, 2 );
	add_filter( 'option_members_settings', 'pp_option_members_settings' );
	add_filter( 'post_class', 'pp_post_class' );
	add_filter( 'get_terms_orderby', 'pp_terms_orderby', 10, 3 );
	add_filter( 'dfi_thumbnail_id', 'pp_dfi_skip_forum', 10 , 2 );
	add_filter( 'the_content_more_link', 'pp_no_morelink_scroll' );
	add_filter( 'request', 'pp_request' );
	add_action( 'wp_head', 'pp_head' );
	add_filter( 'register_url', 'pp_register_url' );
	add_filter( 'site_url', 'pp_fix_register_urls', 10, 3 );
	add_filter( 'single_template', 'pp_single_template' );
	add_filter( 'posts_orderby', 'pp_order_aktiviteter' );
	add_filter( 'posts_orderby', 'pp_order_leverandor' );
	add_filter( 'widget_display_callback', 'pp_widget_display', 11, 3);
	add_filter( 'the_title', 'pp_title', 10, 2 );
	add_filter( 'jetpack_open_graph_output', 'pp_jetpack_open_graph_output' );
	add_filter( 'get_post_metadata', 'pp_get_post_metadata', 10, 3 );
//	wp_enqueue_style( 'jetpack_related-posts', plugins_url( 'jetpack/modules/related-posts/related-posts.css') );
	add_action( 'switch_blog', 'pp_switch_site_rewrite' );
	if ( WP_DEBUG )
		add_filter( 'wp_default_styles', 'pp_default_styles' );
//	if( wp_is_mobile() )
		add_filter( 'loop_start', 'pp_loop_start', 10, 1 );
}

add_action( 'admin_bar_menu', 'pp_adminbar_links', 81 );
add_filter( 'posts_orderby', 'pp_order_alpha' );
add_action( 'init', 'pp_init', 11 );
add_action( 'after_setup_theme', 'pp_theme_setup' );
add_action( 'widgets_init', function() { register_widget( 'PP_Portaler_Widget' ); } );
add_action( 'widgets_init', function() { register_widget( 'PP_Non_Portaler_Widget' ); } );
add_action( 'widgets_init', function() { register_widget( 'PP_Featured_Widget' ); } );
//add_action( 'widgets_init', function() { register_widget( 'PP_Inspirasjon_Widget' ); } );
add_action( 'widgets_init', function() { register_widget( 'PP_After_Content_Widget' ); } );
add_action( 'bp_core_activated_user', 'pp_add_user_to_registering_blog' );
add_filter( 'update_welcome_user_email', 'pp_change_welcome_mail_loginlink', 10, 4 );
add_filter( 'wp_mail_from', 'pp_mail_from' );
add_filter( 'wp_mail_from_name', 'pp_mail_from_name' );
add_filter( 'random_password', 'pp_shorter_pass' );
add_action( 'login_enqueue_scripts', 'pp_login_logo' );
add_filter( 'edit_profile_url', 'pp_edit_profile_url' );
add_action( 'registered_taxonomy', 'pp_registered_taxonomy', 20, 1 );
add_action( 'user_register','pp_register_extra_fields', 99 );
add_filter( 'login_redirect', 'pp_login_redirect', 10, 3);
add_action( 'wp_logout', 'pp_logout_redirect' );
add_action( 'save_post_' . pp_akt_type(), 'pp_save_aktivitet', 20, 3 );
add_action( 'user_register','pp_user_register', 10, 3 );

include( 'includes/user-capabilities.php' );
include( 'includes/wpua.php' );	// User avatar extras
include( 'includes/lev-tlf.php' );	// Kopiere leverandør-tlf fra BPA til Frittbrukervalg