<?php
/**
 * The template for displaying Produkt pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">E-Produkter i kategorien <?php single_cat_title(); ?></h1>
			</header><!-- .page-header -->

			<?php
					while ( have_posts() ) :
						the_post();
						echo PHP_EOL, '<dl>';
						get_template_part( 'content', pp_prd_type() );
						echo PHP_EOL, '</dl>';
					endwhile;
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
