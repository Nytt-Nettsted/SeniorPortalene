<?php
/**
 * The template for displaying featured cusom posts on the front page
 *
 * @package Seniorportalene
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<a class="post-thumbnail" href="<?php the_permalink(); ?>">
	<?php
		// Output the featured image.
		if ( has_post_thumbnail() ) :
			if ( 'grid' == get_theme_mod( 'featured_content_layout' ) ) {
				the_post_thumbnail();
			} else {
				the_post_thumbnail( 'twentyfourteen-full-width' );
			}
		endif;
	?>
	</a>

	<header class="entry-header">
		<?php if ( true ) : ?>
		<div class="entry-meta">
			<span class="cat-links"><?php 
			$cats = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'twentyfourteen' ) );
			if ( 3 == get_current_blog_id() )
				$cats = str_replace( ' title="', ' data="', str_replace( '</a>', '</span>', str_replace( '<a ', '<span ', $cats ) ) );
			echo $cats ?></span>
		</div><!-- .entry-meta -->
		<?php endif; ?>

		<?php the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">','</a></h1>' ); ?>
	</header><!-- .entry-header -->
</article><!-- #post-## -->
