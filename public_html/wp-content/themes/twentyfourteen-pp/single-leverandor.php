<?php
/**
 * The template for displaying leverandor pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'content', pp_lev_type() );

				endwhile;
			?>
			<?php get_sidebar( 'content-after' ); ?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();
