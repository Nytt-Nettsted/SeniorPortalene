<?php
/**
 * Template Name: Ukemeny
 *
 * @package PensjonistPortalen
 */
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
		<div id="content" class="site-content" role="main"><br />
<?php
	pp_loop_start();
?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="margin-top: 56px;">
			<?php //twentyfourteen_post_thumbnail(); // Fjernet fordi IST ikke vil ha den, og må da legge på 56px margin ?>
<?php
				$query = new WP_Query( array( 'post_type' => pp_uke_type(), 'posts_per_page' => 1 ) );
				while ( $query->have_posts() ) {
					$query->the_post();
					setup_postdata( $post );
					get_template_part( 'content', pp_uke_type() );
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
