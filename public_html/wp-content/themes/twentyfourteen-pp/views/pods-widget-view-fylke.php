<?php
$fylker = get_terms( pp_kom_tax(), array( 'parent' => 0, 'hide_empty' => false, 'orderby' => 'term_group', 'hide_empty' => false ) );
$pregroup = -1;
$first = true;
foreach ( $fylker as $fylke ) {
	$group = $fylke->term_group;
	if ( !$first && $pregroup != $group )
		echo PHP_EOL, '</div>';
	if( $pregroup != $group )
		echo PHP_EOL, '<div class="group-', $group, '">';
	echo PHP_EOL, ' <div class="fylke">';
	echo PHP_EOL, '  <a href="', get_term_link( $fylke->slug, pp_kom_tax() ), '">', $fylke->name, '</a>';
	echo PHP_EOL, ' </div>';
	$pregroup = $group;
	$first = false;
}
echo PHP_EOL, '</div>';
?>