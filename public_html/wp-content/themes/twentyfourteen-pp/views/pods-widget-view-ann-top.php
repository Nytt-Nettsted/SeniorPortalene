<?php
/*	Annonser for SeniorPortalene
	Av: Knut Sparhell og Ingebjørg Thoresen
*/
	$qobj   = get_queried_object();
	$idsx = array();	// Annonser allerede vist, skal ekskluderes
//	$parent = get_term_by( 'slug', pp_head_term(), pp_apos_tax() );
//	$apos_terms = array( 'smal', 'bred' );
	$apos_terms = array( 'bred' );
//	$num_annonser = intval( get_terms( pp_apos_tax(), array( 'parent' => $parent->term_id, 'fields' => 'count', 'hide_empty' => false ) ) );
//	$num_annonser = PP_NUM_HEAD_ANN;
	$alev_termobs = get_terms( pp_alev_tax(), array( 'orderby' => 'term_group', 'order' => 'ASC' ) );
	$alev_terms = array();
	foreach ( $alev_termobs as $alev_term )
		$alev_terms[] = $alev_term->slug;
	$meta_query = array( 'relation' => 'OR',
		array( 'key' => 'annonsesluttdato', 'compare' => 'NOT EXISTS' ),
		array( 'key' => 'annonsesluttdato', 'compare' =>  '=', 'value' => 0, 'type' => 'UNSIGNED' ),
		array( 'key' => 'annonsesluttdato', 'compare' => '>=', 'value' => current_time( 'mysql' ), 'type' => 'DATETIME' )
		);
	echo PHP_EOL, ' <ul class="', pp_ann_type(), ' ann-num-', PP_NUM_HEAD_ANN , '">';
	if ( $qobj && $qobj->taxonomy && $qobj->taxonomy == pp_kom_tax() ) {
		$transient = pp_ann_type() . '_' . pp_head_term() . '_' . pp_kom_tax() . '_' . $qobj->slug;
		$annonser = get_transient( $transient );
		if ( $annonser && is_array( $annonser ) && count( $annonser ) ) {
			$source = 'transient';
		} else {
			$source = 'fresh';
			$annonser = array();
			for ( $apos = 0; $apos < count( $apos_terms ); $apos++ ) {
				$alev = 0; //Prøv først med både kommune, posisjon og prioritet
				while( empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => $qobj->taxonomy, 'field' => 'slug', 'terms' => $qobj->slug          ),
							array( 'taxonomy' => pp_apos_tax(),   'field' => 'slug', 'terms' => $apos_terms[ $apos ] ),
							array( 'taxonomy' => pp_alev_tax(),   'field' => 'slug', 'terms' => $alev_terms[ $alev ] )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
//					if ( $apos+1 == 1 ) {echo 'mkom ', $apos+1, ' ', $alev, ' ', ($annonse[0]->ID ? $annonse[0]->ID : 0), ' '; print_r( $idsx ); echo '<br/>';}
					if ( count( $annonse ) ) {
//						$annonse[0]->src = $annonse[0]->ID . ' pos-' . ( $apos + 1 ) . ' ' . $alev_terms[ $alev ] . ' ' . $qobj->name;
						$annonser[ $apos ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
				$alev = 0;
				while( empty( $annonser[ $apos_terms[ $apos ] ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => pp_apos_tax(), 'field' => 'slug', 'terms' => $apos_terms[ $apos ] ),
							array( 'taxonomy' => pp_alev_tax(), 'field' => 'slug', 'terms' => $alev_terms[ $alev ] )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
	//					$annonse[0]->src = $annonse[0]->ID . ' pos-' . $apos_terms[ $apos ] . '-' . $alev_terms[ $alev ];
						$annonser[ $apos_terms[ $apos ] ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
				$alev = 0;	// Forsøk også annonser uten under-term som bred eller smal, bare hode
				while( empty( $annonser[ $apos_terms[ $apos ] ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => pp_apos_tax(), 'field' => 'slug', 'terms' => pp_head_term()       ),
							array( 'taxonomy' => pp_alev_tax(), 'field' => 'slug', 'terms' => $alev_terms[ $alev ] )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						$annonse[0]->src = $annonse[0]->ID . ' pos-' . pp_head_term() . '-' . $alev_terms[ $alev ];
						$annonser[ $apos_terms[1] ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
			}
			set_transient( $transient, $annonser, PP_ANN_TRANS_EXP );
		}
		pp_widget_annonser_li( $annonser, false, 'large', $source );
	} else {
		$transient = pp_ann_type() . '_' . pp_head_term();
//		delete_transient( $transient );
		$annonser = get_transient( $transient );
		if ( $annonser && is_array( $annonser ) && count( $annonser ) ) {
			$source = 'transient';
		} else {
			$source = 'fresh';
			$annonser = array();
			for ( $apos = 0; $apos < count( $apos_terms ); $apos++ ) {
				$alev = 0;
				while( empty( $annonser[ $apos_terms[ $apos ] ] ) && $alev < count( $alev_terms ) ) {
					if ( taxonomy_exists( pp_kom_tax() ) ) {
						$termsx = get_terms( pp_kom_tax(), array( 'fields' => 'ids' ) );		// Kommune-termer som ikke skal være med når kommune ikke er med i kriteriene
						$annonse = get_posts( array(
							'posts_per_page' => 1,
							'post_type' => pp_ann_type(),
							'tax_query' => array( 'relation' => 'AND',
								array( 'taxonomy' => pp_kom_tax(),  'field' => 'id',   'terms' => $termsx, 'operator' => 'NOT IN', 'include_children' => false ),
								array( 'taxonomy' => pp_apos_tax(), 'field' => 'slug', 'terms' => $apos_terms[ $apos ] ),
								array( 'taxonomy' => pp_alev_tax(), 'field' => 'slug', 'terms' => $alev_terms[ $alev ] )
							),
							'meta_query' => $meta_query,
							'exclude' => array_unique( $idsx ),
							'orderby' => 'rand'
						) );
					} else {
						$annonse = get_posts( array(
							'posts_per_page' => 1,
							'post_type' => pp_ann_type(),
							'tax_query' => array( 'relation' => 'AND',
								array( 'taxonomy' => pp_apos_tax(), 'field' => 'slug', 'terms' => $apos_terms[ $apos ] ),
								array( 'taxonomy' => pp_alev_tax(), 'field' => 'slug', 'terms' => $alev_terms[ $alev ] )
							),
							'meta_query' => $meta_query,
							'exclude' => array_unique( $idsx ),
							'orderby' => 'rand'
						) );
					}
					if ( count( $annonse ) ) {
	//					$annonse[0]->src = $annonse[0]->ID . ' pos-' . $apos_terms[ $apos ] . '-' . $alev_terms[ $alev ];
						$annonser[ $apos_terms[ $apos ] ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
				$alev = 0;
				while( empty( $annonser[ $apos_terms[ $apos ] ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => pp_apos_tax(), 'field' => 'slug', 'terms' => pp_head_term()       ),
							array( 'taxonomy' => pp_alev_tax(), 'field' => 'slug', 'terms' => $alev_terms[ $alev ] )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						$annonse[0]->src = $annonse[0]->ID . ' pos-' . pp_head_term() . '-' . $alev_terms[ $alev ];
						$annonser[ $apos_terms[1] ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
			}
			set_transient( $transient, $annonser, PP_ANN_TRANS_EXP );
		}
//		$annonser = get_posts( array( 'posts_per_page' => PP_NUM_HEAD_ANN, 'post_type' => pp_ann_type(), pp_apos_tax() => pp_head_term(), 'orderby' => 'rand' ) );
//		shuffle( $annonser );
//		$annonser = array_slice( $annonser, 0, PP_NUM_HEAD_ANN );
//		var_dump( $annonser );exit;
		pp_widget_annonser_li( $annonser, false, 'large', $source );
	}
	echo PHP_EOL, ' </ul>';
	echo PHP_EOL;
?>