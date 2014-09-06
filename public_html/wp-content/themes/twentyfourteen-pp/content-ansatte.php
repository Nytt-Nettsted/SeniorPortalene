<?php
/**
 * The template used for displaying page content
 *
 * @package PensjonistPortalen
 */
global $pp_user, $wp_roles;
if ( is_super_admin( $pp_user->ID ) )
	$pp_user->roles = array( 'Superadmin' );
else
	foreach ( $pp_user->roles as $key => $role )
		$pp_user->roles[ $key ] = translate_user_role( $wp_roles->role_names[ $role ] );
?>

<article id="user-<?php echo $pp_user->ID; ?>">
	<?php
//		$f =  $wpdb->prefix . 'funksjon';
		$f =  'pp_funksjon';
		$first = mb_substr( $pp_user->$f, 0, 1 );
		$funksjon =  'A' > $first || '§' == $first ? mb_substr( $pp_user->$f, 1 ) : $pp_user->$f;
		echo PHP_EOL, ' <header class="ansatt-header">';
		echo PHP_EOL, '  ', get_avatar( $pp_user->ID );
		if ( current_user_can( 'edit_user', $pp_user->ID ) )
			echo PHP_EOL, '  <h2 class="ansatt-name"><a href="', admin_url( 'user-edit.php?user_id=' . $pp_user->ID ), '#pp_funksjon" style="text-decoration: none; color: inherit;" title="Rediger bruker &laquo;', $pp_user->first_name, ' ', $pp_user->last_name, '&raquo; (', $pp_user->user_login, ')">', $pp_user->display_name, '</a></h2>';
		else
			echo PHP_EOL, '  <h2 class="ansatt-name" title="', $pp_user->first_name, ' ', $pp_user->last_name, '">', $pp_user->display_name, '</h2>';
		echo PHP_EOL, '  <p>';
		if ( current_user_can( 'edit_user', $pp_user->ID ) )
			echo PHP_EOL, '   <span class="stilling" title="', implode( ', ', $pp_user->roles ), '">', $funksjon, '</span><br />';
		else
			echo PHP_EOL, '   <span class="stilling">', $funksjon, '</span><br />';
		if ( function_exists( 'wpml_mailto' ) )
			echo PHP_EOL, '   <span class="epost">', wpml_mailto( $pp_user->user_email, array( 'href' => 'mailto:' . esc_attr( $pp_user->user_email ) ) ), '</span>';
		else
			echo PHP_EOL, '   <span class="epost"><a href="mailto:', $pp_user->user_email, '">', $pp_user->user_email, '</a></span>';
		echo PHP_EOL, '  </p>';
		echo PHP_EOL, ' </header>';
	?>

	<div class="ansatt-content">
		<?php
		add_filter( 'excerpt_length', function() { return 1000; }, 999 );
		echo PHP_EOL, $pp_user->description ? pp_hide_text( wpautop( $pp_user->description ), $pp_user->ID ) : '<p><em>(beskrivelse kommer)</em></p>';
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
