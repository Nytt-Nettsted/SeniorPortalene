<?php
/**
 * Template Name: Aktiviteter
 *
 * @package PensjonistPortalen
 * @subpackage Senioraktivitet
 */

// UTGÅR:
function pp_aktivitet_orderby( $orderby ) {
	global $wpdb;
	return "$wpdb->postmeta.meta_value ASC";
}
remove_filter( 'loop_start', 'pp_loop_start' );
get_header(); ?>

<div id="main-content" class="main-content">

<?php
	if ( is_front_page() && twentyfourteen_has_featured_posts() ) {
		// Include the featured content template.
		get_template_part( 'featured-content' );
	}
?>
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
<?php
	pp_loop_start();
?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php twentyfourteen_post_thumbnail(); ?>
			<header class="entry-header"><h1 class="entry-title"><?php the_title(); ?></h1></header>
<?php
	$post = get_post( get_the_ID() );
	$content = $post->post_content;
	echo PHP_EOL, '<div class="entry-content">';
	echo wpautop( $content );
	echo PHP_EOL, '</div>';
	$ym = isset( $_GET['aym'] ) ? intval( $_GET['aym'] ) : false;
	if ( $ym )
		$ym = $ym > date( 'Y' ) * 100 ? $ym : false;
	if ( $ym ) {
		$y  = intval( substr( $ym, 0, 4 ) );
		$m  = intval( substr( $ym, 4, 2 ) );
		$t  = mktime( 0, 0, 0, $m, 1, $y );
		$fromdate = date( 'Y-m-d', $t );
	} else
		$fromdate = date( 'Y-m-d' );
	$weeks = 7 == get_current_blog_id() ? 10 : 3;
			echo 'Vis &nbsp; ';
			for ( $dt = time(); $dt < strtotime( '+6 months' ); $dt = strtotime( '+1 month', $dt ) ) {
				if ( date( 'Y-m', $dt ) != date( 'Y-m', $t ) )
					echo '<a href="', add_query_arg( 'aym', date( 'Ym', $dt ) ) ,'" title="Hele ', date_i18n( 'F', $dt ), ' ', date( 'Y', $dt ), '">', date_i18n( 'F', $dt ), '</a> &nbsp;';
			}
?><br />
			<table id="<?php echo pp_akt_type(); ?>-table">
<? if ( $ym ) echo '<caption style="text-align: center; font-weight: 900; font-size: 16px; margin-bottom: 1em;">', ucfirst( date_i18n( 'F', $t ) ), '</caption>'; ?>
<?php get_template_part( pp_akt_type(), 'thead' ); ?>
				<tbody>
<?php
//					add_filter( 'posts_orderby', 'pp_aktivitet_orderby' );
//					$query = new WP_Query( array( 'post_type' => pp_akt_type(), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'dato', 'value' => date( 'Y-m-d' ), 'compare' => '>=', 'type' => 'DATETIME' ) ), 'meta_key' => 'dato', 'orderby' => 'meta_value', 'order' => ' ASC' ) );
					$query = new WP_Query( array( 'post_type' => pp_akt_type(), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => '_last_date', 'value' => $fromdate, 'compare' => '>=', 'type' => 'DATETIME' ) ), 'meta_key' => 'startdato', 'orderby' => 'meta_value', 'order' => 'ASC' ) );
					$aktiviteter = array();
					while ( $query->have_posts() ) {
						$query->the_post();
						$date = date( 'Y-m-d', strtotime( get_post_meta( $post->ID, 'startdato', true ) ) );
						$komm = get_the_terms( $post->ID, pp_kom_tax() );
						$komm = is_array( $komm ) ? array_values( $komm )[0] : new stdClass;
						$komm = $komm->term_group . '-' . $komm->slug;
						$aktiviteter[ $date . '-' . $komm . '-' . $post->ID ] = $post->ID;
						$dates = get_post_meta( $post->ID, '_date', false );
						foreach ( $dates as $date ) {
							$aktiviteter[ $date . '-' . $komm . '-' . $post->ID ] = $post->ID;
						}
					}
					ksort( $aktiviteter );
//					echo '<pre>'; print_r( $aktiviteter ); echo '</pre>';
					foreach ( $aktiviteter as $adate => $aktivitet_id ) {
						$date = substr( $adate, 0, 10 );
						$tminus = time() - DAY_IN_SECONDS + HOUR_IN_SECONDS;
						if ( $ym )
							$limit = max( $t, $tminus ) <= mysql2date( 'U', $date ) && mysql2date( 'U', $date ) < date( 'U', strtotime( '+1 month', $t ) );
						else
							$limit = $tminus <= mysql2date( 'U', $date ) && mysql2date( 'U', $date ) <= time() + ( $weeks * WEEK_IN_SECONDS );
						if ( $limit ) {
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
			echo 'Vis &nbsp; ';
			for ( $dt = time(); $dt < strtotime( '+6 months' ); $dt = strtotime( '+1 month', $dt ) ) {
				if ( date( 'Y-m', $dt ) != date( 'Y-m', $t ) )
					echo '<a href="', add_query_arg( 'aym', date( 'Ym', $dt ) ) ,'" title="Hele ', date_i18n( 'F', $dt ), ' ', date( 'Y', $dt ), '">', date_i18n( 'F', $dt ), '</a> &nbsp;';
			}
?>
			</article>
		</div><!-- #content -->
	</div><!-- #primary -->
	<?php get_sidebar( 'content' ); ?>
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();
