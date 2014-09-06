<?php
/*	Annonser for PensjonistPortalen
	Av: Knut Sparhell og Ingebjørg Thoresen
*/
//	$sidebar_primary_num_ann = pp_sidebar_primary_num_ann();
//	$num_annonser = $sidebar_primary_num_ann[ get_current_blog_id() ];			// Antall annonser som skal vises totalt pr side
	global $wpdb;

	echo PHP_EOL, ' <ul class="', pp_ann_type(), '">';
	$parent = get_term_by( 'slug', pp_side_term(), pp_apos_tax() );
	$num_annonser = intval( get_terms( pp_apos_tax(), array( 'parent' => $parent->term_id, 'fields' => 'count', 'hide_empty' => false ) ) );
	//intval( get_terms( pp_apos_tax(), array( 'slug'   => pp_side_term(),   'fields' => 'count' ) ) );
	$idsx = array();	// Annonser allerede vist, skal ekskluderes
	$alev_termobs = get_terms( pp_alev_tax(), array( 'orderby' => 'term_group', 'order' => 'ASC', 'hide_empty' => false ) );
	$alev_terms = array();
	foreach ( $alev_termobs as $alev_term )
		$alev_terms[] = $alev_term->slug;
	$meta_query = array( 'relation' => 'OR',
		array( 'key' => 'annonsesluttdato', 'compare' => 'NOT EXISTS' ),
		array( 'key' => 'annonsesluttdato', 'compare' =>  '=', 'value' => 0, 'type' => 'UNSIGNED' ),
		array( 'key' => 'annonsesluttdato', 'compare' => '>=', 'value' => current_time( 'mysql' ), 'type' => 'DATETIME' )
	);
	$transient = pp_ann_type() . '_' . pp_side_term();
	$annonser = get_transient( $transient );
	if ( $annonser && is_array( $annonser ) && count( $annonser ) ) {
		$source = 'transient';
	} else {
		$source = 'fresh';
		$annonser = array();
		for ( $apos = 0; $apos < $num_annonser; $apos++ ) {
			$alev = 0;
			while( empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
//				$wpdb->flush();
				$annonse = get_posts( array(
					'posts_per_page' => 1,
					'post_type' => pp_ann_type(),
					'tax_query' => array( 'relation' => 'AND',
						array( 'taxonomy' => pp_apos_tax(), 'field' => 'slug', 'terms' => 'pos-' . ( $apos + 1 ) ),
						array( 'taxonomy' => pp_alev_tax(), 'field' => 'slug', 'terms' => $alev_terms[ $alev ]   )
					),
					'meta_query' => $meta_query,
					'exclude' => $idsx
				) );
//				if( $apos+1 == 2 ) echo PHP_EOL, '<pre>! '; print_r( $wpdb->queries); echo ' !</pre>';
				if ( count( $annonse ) ) {
					$annonse[0]->src = $annonse[0]->ID . ' pos-' . ( $apos + 1 ) . ' ' . $alev_terms[ $alev ];
					$annonser[ $apos ] = $annonse[0];
					$idsx[] = intval( $annonse[0]->ID );
				}
//				echo 'pos-' . ( $apos + 1 ) . ' '. $alev_terms[ $alev ] . ' '; var_dump( $annonse ); echo '<br/>';
				$alev++;	// Fra pri-1 til pri-3 via $alev_terms
			}
			$alev = 0;	// Prøv igjen uten å forlange posisjon, kun sidestolpe definert
			while( empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
				$annonse = get_posts( array(
					'posts_per_page' => 1,
					'post_type' => pp_ann_type(),
					'tax_query' => array( 'relation' => 'AND',
						array( 'taxonomy' => pp_apos_tax(), 'field' => 'slug', 'terms' => pp_side_term() ),
						array( 'taxonomy' => pp_alev_tax(), 'field' => 'slug', 'terms' => $alev_terms[ $alev ] )
					),
					'meta_query' => $meta_query,
					'exclude' => $idsx
				) );
				if ( count( $annonse ) ) {
					$annonse[0]->src = $annonse[0]->ID . ' pos-any ' .	$alev_terms[ $alev ];
					$annonser[ $apos ] = $annonse[0];
					$idsx[] = intval( $annonse[0]->ID );
				}
				$alev++;	// Fra pri-1 til pri-3 via $alev_terms
			}
			if ( empty( $annonser[ $apos ] ) ) {
				$annonse = get_posts( array(
					'posts_per_page' => 1,
					'post_type' => pp_ann_type(),
					'tax_query' => array(
						array( 'taxonomy' => pp_apos_tax(), 'field' => 'slug', 'terms' => pp_side_term() )
					),
					'meta_query' => $meta_query,
					'exclude' => $idsx
				) );
				if ( count( $annonse ) ) {
					$annonse[0]->src = $annonse[0]->ID . ' pos-any ' . pp_side_term();
					$annonser[ $apos ] = $annonse[0];
					$idsx[] = intval( $annonse[0]->ID );
				}
			}
		}
		set_transient( $transient, $annonser, PP_ANN_TRANS_EXP );
	}
	pp_widget_annonser_li( $annonser, false, 'medium', $source );
	echo PHP_EOL, ' </ul>';
	echo PHP_EOL;
?>