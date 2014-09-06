<?php
/**
 * The template for displaying Fylke aktiviteter.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 */

get_header();
remove_filter( 'loop_start', 'pp_loop_start' );
?>
	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

			<header class="archive-header">
				<h1 class="archive-title">Alle leverandører</h1>
			</header><!-- .archive-header -->
<?php
					pp_loop_start();
					get_template_part( pp_lev_type(), 'thead' );
					while ( have_posts() ) {
						the_post();
						get_template_part( 'content', pp_lev_type() );
					}
?>
				</tbody>
			</table>
<?php
					twentyfourteen_paging_nav();

				else :
					get_template_part( 'content', 'none' );

				endif;
			?>
		</div><!-- #content -->
	</section><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();
