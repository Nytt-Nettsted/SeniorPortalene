<?php
/**
 * The template for displaying aktivitet Archive pages
 *
 * @package Seniorportalene
 * @subpackage Senioraktivitet
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) :
?>
			<header class="archive-header">
				<h1 class="page-title">Aktiviteter <?php single_term_title( '', true ); ?></h1>
			</header><!-- .page-header -->
<?php
					$aktiviteter = array();
					while ( have_posts() ) {
						the_post();
//						$date = date( 'Y-m-d', strtotime( get_post_meta( $post->ID, 'startdato', true ) ) );
//						$aktiviteter[ $date . '-' . $post->ID ] = $post->ID;
//						$dates = get_post_meta( $post->ID, '_date', false );
//						foreach ( $dates as $date ) {
//							$aktiviteter[ $date . '-' . $post->ID ] = $post->ID;
//						}
//					}
//					ksort( $aktiviteter );
//					echo PHP_EOL, '<dl>';
//					foreach ( $aktiviteter as $adate => $aktivitet_id  ) {
//						$date = substr( $adate, 0, 10 );
//						$post = get_post( $aktivitet_id );
//						setup_postdata( $post );
//						$post->akt_date = $date;
						$date = strtotime( substr( get_post_meta( get_the_ID(), 'startdato', true ), 0, 10 ) );
						if ( $date < time() + ( 12 * HOUR_IN_SECONDS ) ) {
							$nextdates = get_post_meta( get_the_ID(), '_date' );
							sort( $nextdates );
							foreach ( $nextdates as $nextdate ) {
								$dndate = strtotime( $nextdate );
								if ( time() < $dndate )
									break;
							}
							$date = 0 == $dndate ? $date : $dndate;
						}
						$post->akt_date = date( 'Y-m-d', $date );
						get_template_part( 'liste', get_post_type() );
					}
					//echo PHP_EOL, '</dl>';
					echo PHP_EOL, '<br style="height: 0; visibility: hidden; clear: left" />';
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
