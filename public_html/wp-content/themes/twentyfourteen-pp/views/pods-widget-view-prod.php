<?php
$hoved_kategorier = get_terms( pp_pkat_tax(), array( 'parent' => 0, 'hide_empty' => false ) );
foreach ( $hoved_kategorier as $hoved_kategori ) {
	$under_kategorier = get_terms( pp_pkat_tax(), array( 'parent' => $hoved_kategori->term_id, 'hide_empty' => false ) );
	echo PHP_EOL, '<div class="produktkategori">';
	echo PHP_EOL, ' <a style="cursor: pointer;" rel="', $hoved_kategori->slug, '">', $hoved_kategori->name, '</a>';
	echo PHP_EOL, ' <ul style="display: none; text-indent: 1em;" class="underkategori" id="pkat-', $hoved_kategori->slug, '">';
	foreach ( $under_kategorier as $produkt_kategori ) {
			$url = get_term_link( $produkt_kategori->slug, pp_pkat_tax() );
			echo PHP_EOL, '  <li><a href="', $url , '" title="Se oversikt over E-Produkter av kategorien ', $produkt_kategori->name , ' ', pp_pkat_tax(), '">', $produkt_kategori->name, '</a></li>';
		}
	echo PHP_EOL, ' </ul>';
	echo PHP_EOL, '</div>';
}
?>