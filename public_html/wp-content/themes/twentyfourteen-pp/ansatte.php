<?php
/**
 * Template Name: Ansatte
 *
 * @package Seniorportalene
 */
function pp_user_sort( $ua, $ub ) {
//	global $wpdb;
	$f =  'pp_funksjon';
	$af = mb_substr( $ua->$f, 0, 1 );
	$bf = mb_substr( $ub->$f, 0, 1 );
	return strcasecmp( (  'A' > $af || '§' == $af ? $af : '' ) . $ua->last_name, ( 'A' > $bf  || '§' == $bf ? $bf : '' ) . $ub->last_name );
}
global $pp_user, $wpdb;
load_textdomain( 'default', WP_CONTENT_DIR . '/languages/admin-' . str_replace( '-xx', '_', get_bloginfo( 'language', 'raw' ) ) . '.mo' );
get_header();
the_post();
?>

<div id="main-content" class="main-content">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php twentyfourteen_post_thumbnail(); ?>
			<header class="entry-header"><h1 class="entry-title"><?php the_title(); ?></h1></header>
			<div class="entry-content">
			<?php
				$args = array( 'meta_key' =>  'pp_funksjon', 'meta_value' => '', 'meta_compare' => '!=', 'fields' => 'all_with_meta', 'orderby' => 'display_name' );
				$users = get_users( $args );
				usort( $users, 'pp_user_sort' );
				foreach ( $users as $pp_user ) {
					$f = 'pp_funksjon';
					if ( $pp_user->$f && count( $pp_user->roles ) && $pp_user->has_cap( 'publish_posts' ) ) {
						echo PHP_EOL, '<hr />';
						get_template_part( 'content', 'ansatte' );
					}
				}
				the_content();
			?>
			</div>
			</article>
		</div><!-- #content -->
	</div><!-- #primary -->
	<?php get_sidebar( 'content' ); ?>
</div><!-- #main-content -->

<?php

get_sidebar();
get_footer();
