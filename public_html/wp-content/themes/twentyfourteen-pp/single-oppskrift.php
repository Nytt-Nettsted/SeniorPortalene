<?php
/**
 * The Template for displaying all single oppskrift
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();
					get_template_part( 'content', get_post_type() );

					// Previous/next post navigation.
					if ( function_exists( 'pp_cpt_nav' ) )
						pp_cpt_nav();
					else
						twentyfourteen_post_nav();
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
			?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();
