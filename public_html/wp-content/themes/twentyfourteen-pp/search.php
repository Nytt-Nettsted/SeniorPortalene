<?php
/**
 * The template for displaying Search Results pages
 *
 * @package SeniorPortalene
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentyfourteen' ), get_search_query() ); ?></h1>
			</header><!-- .page-header -->

				<?php
					if ( 3 == get_current_blog_id() ) {
						echo PHP_EOL, '<dl>';
						while ( have_posts() ) {
							the_post();
							get_template_part( 'content', pp_pro_type() );
						}
						echo PHP_EOL, '</dl>';
						echo PHP_EOL, '<br style="height: 0; visibility: hiden; clear: left" />';
					} elseif ( in_array( get_current_blog_id(), array( 4, 7 ) ) ) {
						get_template_part( pp_lev_type(), 'thead' );
						while ( have_posts() ) {
							the_post();
							get_template_part( 'content', pp_lev_type() );
						}
						echo PHP_EOL, ' </tbody>';
						echo PHP_EOL, '</table>';

					} elseif ( 5 == get_current_blog_id() ) {
							echo PHP_EOL, '<table id="', pp_akt_type(), '-table">';
							get_template_part( pp_akt_type(), 'thead' );
							echo PHP_EOL, ' <tbody>';
							while ( have_posts() ) {
								the_post();
								$date = date( 'Y-m-d', strtotime( get_post_meta( $post->ID, 'startdato', true ) ) );
								$aktiviteter[ $date . '-' . $post->ID ] = $post->ID;
								$dates = get_post_meta( $post->ID, '_date', false );
								foreach ( $dates as $date ) {
									$aktiviteter[ $date . '-' . $post->ID ] = $post->ID;
								}
							}
							ksort( $aktiviteter );
							foreach ( $aktiviteter as $adate => $aktivitet_id  ) {
								$date = substr( $adate, 0, 10 );
								$post = get_post( $aktivitet_id );
								setup_postdata( $post );
								$post->akt_date = $date;
								get_template_part( 'content', pp_akt_type() );
							}
							echo PHP_EOL, ' </tbody>';
							echo PHP_EOL, '</table>';
					} else {
						while ( have_posts() ) {
							the_post();
							get_template_part( 'content', get_post_format() );
						}
					}
					// Previous/next post navigation.
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
