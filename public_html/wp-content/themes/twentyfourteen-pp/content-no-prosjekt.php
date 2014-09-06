<?php
/**
 * The template for displaying a "No posts found" message
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<header class="page-header">
	<h1 class="page-title"><?php _e( 'Nothing Found', 'twentyfourteen' ); ?></h1>
</header>

<div class="page-content">

	<?php if ( is_search() ) : ?>

	<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'twentyfourteen' ); ?></p>
	<?php get_search_form(); ?>

	<?php else : ?>

	<p>Det er ikke lagt inn noen boliger i dette fylket ennå. Du kan sette deg på <a href="/2013/registrer-interessent/">interessentliste her</a>.</p>

	<?php endif; ?>
</div><!-- .page-content -->
