<?php
/**
 * The template for displaying Prosjekt Archive pages
 *
 * @package Seniorportalene
 * @subpackage SeniorBoPortalen
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) :
?>
			<header class="archive-header">
				<h1 class="page-title">Alle boliger</h1>
			</header><!-- .archive-header -->
<?php
					echo PHP_EOL, '<dl>';
					while ( have_posts() ) : the_post();
						if ( 894 != get_the_ID() )
							get_template_part( 'content', get_post_type() );
					endwhile;
					echo PHP_EOL, '</dl>';
					echo PHP_EOL, '<br style="height: 0; visibility: hiden; clear: left" />';
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
