<?php
/**
 * The template for displaying prosjekt Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Twenty Fourteen
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 */

add_filter( 'excerpt_length', function(){ return 40; } );
get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

			<header class="archive-header">
				<h1 class="page-title">Prosjekter i <?php single_cat_title(); ?></h1>
			</header><!-- .page-header -->
			<?php
					echo '<dl>';
					$some = false;
					while ( have_posts() ) {
						$some |= 894 != get_the_ID();
						the_post();
						if ( ! $some || 894 != get_the_ID() )
							get_template_part( 'content', pp_pro_type() );
					}
					echo '</dl>';
					twentyfourteen_paging_nav();

				else :
					if ( 3 == get_current_blog_id() )
						get_template_part( 'content', 'no-' . pp_pro_type() );
					else
						get_template_part( 'content', 'none' );

				endif;
			?>
		</div><!-- #content -->
	</section><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();
