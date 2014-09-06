<?php
/**
 * Template Name: Velkommen
 *
 * @package Seniorportalene
 */
	get_header();
?>

<div id="main-content" class="main-content">

<?php
	if ( pp_featured_content_pos()[ get_current_blog_id() ] == 'top' && is_front_page() && twentyfourteen_has_featured_posts() ) {
		// Include the featured content template.
		get_template_part( 'featured-content' );
	}
?>
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

<?php
	if ( have_posts() ) {
		the_post();
?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php
		twentyfourteen_post_thumbnail();
		the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header><!-- .entry-header -->' );
	?>
				<div class="entry-content">
	<?php
		echo PHP_EOL, '<blockquote><p><strong>';
		bloginfo( 'description' );
		echo PHP_EOL, '</strong><p></blockquote>';
		the_content();
		$src = '';
		echo PHP_EOL, '<ul style="list-style-type: none; text-indent: 0; margin-left: 0; padding-left: 0;">';
		foreach ( pp_sites( $src ) as $site_id => $site ) {
			if ( BLOG_ID_CURRENT_SITE != $site_id ) {
				$name = esc_attr( $site->blogname );
				$img  = esc_attr( $site->domain ) . '.png';
				$href = ' href="' . esc_url( $site->siteurl, array( 'http', 'https' ) ) . '/" rel="bookmark"';
				echo PHP_EOL, '<li>';
				echo PHP_EOL, ' <h2 title="', $src, '">', $name, '</h2>';
				echo PHP_EOL, ' <p><a', $href, '><img src="', get_stylesheet_directory_uri(), '/images/', $img, '" class="alignleft size-thumbnail wp-image-0" width="150" height="81" style="width: 83px" title="Til ', $name, '"></a></p>';
				$desc = get_blog_option( $site_id, 'pp_description' );
				if ( false === $desc || '' == $desc && current_user_can( 'edit_published_posts' ) )
					$desc = '<a href="' . $site->siteurl . '/wp-admin/options-general.php?page=pp-settings' . '">Innstillinger -> Beskrivelse</a>';
				echo PHP_EOL, ' <p>', trim( $desc ), '</p>';
				echo PHP_EOL, '</li>';
			}
		}
		echo PHP_EOL, '</ul>';
//		echo do_shortcode( '[jetpack_subscription_form]' );
		edit_post_link( __( 'Edit', 'twentyfourteen' ) . ' introduksjonen', '<p>&nbsp;</p><p><span class="edit-link">', '</span></p>' );
		?>
				</div><!-- .entry-content -->
			</article><!-- #post-## -->
<?php
	}
?>
		<?php get_sidebar( 'content-after' ); ?>
		</div><!-- #content -->
	</div><!-- #primary -->
	<?php get_sidebar( 'content' ); ?>
</div><!-- #main-content -->
<?php
	get_sidebar();
	get_footer();
