<?php
/**
 * The template for displaying Fylke aktiviteter.
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

			<header class="archive-header">
				<h1 class="archive-title">Aktiviteter i <?php single_cat_title(); ?></h1>
			</header><!-- .archive-header -->
			<table id="<?php pp_akt_type(); ?>-table">
<?php
	get_template_part( get_post_type(), 'thead' );
?>
				<tbody>
<?php
					$aktiviteter = array();
					while ( have_posts() ) {//echo $post->ID.' x ';
						the_post();
						$date = date( 'Y-m-d', strtotime( get_post_meta( $post->ID, 'startdato', true ) ) );
						$aktiviteter[ $date . '-' . $post->ID ] = $post->ID;
						$dates = get_post_meta( $post->ID, '_date', false );
						foreach ( $dates as $date ) {
							$aktiviteter[ $date . '-' . $post->ID ] = $post->ID;
						}
					}
					ksort( $aktiviteter, SORT_STRING );
					foreach ( $aktiviteter as $adate => $aktivitet_id  ) {
						$date = substr( $adate, 0, 10 );
						if ( time() - DAY_IN_SECONDS <= mysql2date( 'U', $date ) ) {
							$post = get_post( $aktivitet_id );
							setup_postdata( $post );
							$post->akt_date = $date;
							get_template_part( 'content', pp_akt_type() );
						}
					}
?>
				</tbody>
			</table>
<?php
//					twentyfourteen_paging_nav();

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
