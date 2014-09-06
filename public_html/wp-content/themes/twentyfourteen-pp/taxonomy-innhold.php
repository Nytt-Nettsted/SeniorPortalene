<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Twenty Fourteen
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

$cpt = get_post_type_object( pp_opp_type() );
$cat = single_cat_title( '', false );
get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php echo ( 'er' == substr( $cat, -2 ) ? str_replace( 'er', 'r', $cat ) : $cat ) . 'e ' . strtolower( $cpt->labels->name ); ?></h1>
			</header><!-- .page-header -->

			<?php
					// Start the Loop.
					while ( have_posts() ) : the_post();
						get_template_part( 'content', get_post_type() );
						//echo PHP_EOL, '<div class="entry-content">';
						//echo PHP_EOL, '<h2 class="ernfyskom">Ernæringsfysiologens kommentar</h2>';
						//the_excerpt();
						//echo PHP_EOL, '<p style="clear: both;"><a href="', get_permalink(),'">Les mer om oppskriften</a></p>';
						echo PHP_EOL, '<hr />';
						//echo PHP_EOL, '</div>';
					endwhile;
					// Previous/next page navigation.
					twentyfourteen_paging_nav();

				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;
			?>
		</div><!-- #content -->
	</section><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();
