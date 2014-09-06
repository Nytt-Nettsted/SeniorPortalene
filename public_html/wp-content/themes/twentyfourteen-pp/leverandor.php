<?php
/**
 * The template for displaying Leverandør.
 *
 * @package Seniorportalene
 * @subpackage Frittbrukervalgportalen
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

		</div><!-- #content -->
	</div><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();
