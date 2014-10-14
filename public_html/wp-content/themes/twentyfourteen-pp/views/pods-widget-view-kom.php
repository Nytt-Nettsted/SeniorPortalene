<?php
function pp_get_fylker( $terms, $taxonomies ) {
	if ( 7 == get_current_blog_id() && pp_kom_tax() == $taxonomies[0] )
		foreach ( $terms as $i => $term )
			if ( 0 != $term->term_group )
				unset( $terms[ $i ] );
	return $terms;
}

add_filter( 'get_terms', 'pp_get_fylker', 10, 2 );
$number = 10;
$col = 0;
do {
	echo PHP_EOL, '<div style="float: left; padding-right: 1em;">';
	$fylker = get_terms( pp_kom_tax(), array( 'parent' => 0, 'hide_empty' => false, 'number' => $number, 'offset' => $col * $number ) );
	foreach ( $fylker as $fylke ) {
		echo PHP_EOL, '<div class="fylke">';
		echo PHP_EOL, ' <a style="cursor: pointer;" rel="', $fylke->slug, '" title="Klikk for å vise kommuner i ', $fylke->name, '">', $fylke->name, '</a>';
		$kommuner = get_terms( pp_kom_tax(), array( 'parent' => $fylke->term_id, 'hide_empty' => false ) );
		echo PHP_EOL, ' <ul style="display: none; text-indent: 1em;" class="kommune" id="fylke-', $fylke->slug , '">';
		foreach ( $kommuner as $kommune ) {
			$url = get_term_link( $kommune->slug, pp_kom_tax() );
			$small = 15 < mb_strlen( $kommune->name );
			$kutt  = 20 < mb_strlen( $kommune->name );
			echo PHP_EOL, '  <li>', $small ? '<small>' : '', '<a href="', $url , '" title="Se oversikt over leverandører i ', $kommune->name, ' ', pp_kom_tax(), '">', $kutt ? mb_substr( $kommune->name, 0, 19 ) . '&hellip;' : $kommune->name, '</a>', $small? '</small>' : '', '</li>';
		}
		echo PHP_EOL, ' </ul>';
		echo PHP_EOL, '</div>';
	}
	echo PHP_EOL, '</div>';
	$col++;
} while ( count( $fylker ) );
?>