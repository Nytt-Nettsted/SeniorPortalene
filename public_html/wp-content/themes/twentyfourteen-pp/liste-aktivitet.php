<?php
/**
 * The template used for displaying Aktiviteter
 *
 * @package Seniorportalene
 * @subpackage Senioraktivetet
 */

	echo PHP_EOL, ' <dt class="', get_post_type(), '-image">';
	$terms = array_values( get_the_terms( get_the_ID(), pp_kom_tax() ) );
	echo PHP_EOL, '  <h2 class="tax-', pp_kom_tax(), '">', $terms[0]->name, '</h2>';
	if ( has_post_thumbnail() ) {
		echo PHP_EOL, '  <a href="', get_permalink( get_the_ID() ), '?date=', $post->akt_date, '" title="Les videre og se større bilde">';
		the_post_thumbnail( 'medium' );
	echo '</a>';
	} else
		echo '<p style="width: 300px;">Bilde mangler</p>';
	echo ' </dt>';
	echo PHP_EOL, ' <dd class="', get_post_type(), '-desc">';
	echo PHP_EOL, '  <h3 class="entry-title">';
	echo PHP_EOL, '  <a href="', get_permalink( get_the_ID() ), '?date=', $post->akt_date, '" rel="bookmark" title="Les hele ', get_post_type(), 'beskrivelesen">';
	the_title();
	echo '</a>';
	echo PHP_EOL, '  </h3>';
	echo PHP_EOL;
	the_excerpt();
	echo PHP_EOL, '  <a class="read-more" href="', get_permalink( get_the_ID() ), '?date=', $post->akt_date, '">Les videre</a>';
	echo PHP_EOL, ' </dd>';
	echo PHP_EOL;
