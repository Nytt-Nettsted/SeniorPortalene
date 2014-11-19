<?php
/*	Annonser for SeniorPortalene
	Av: Knut Sparhell og Ingebjørg Thoresen
*/
	global $post;
	if ( is_single() ) {
		$qobj = new stdClass;
		$qobj->taxonomy = 'category';
		$cats = get_categories();
		$qobj->slug = $cats[0]->slug;
		if ( 1 > count( $cats ) && $cats[0]->ID == intval( get_option( 'default_category' ) ) )
			$qobj->slug = $cats[1]->slug;
	} elseif ( is_category() )
		$qobj   = get_queried_object();
	$parent = get_term_by( 'id', get_term_by( 'id', $qobj->term_id, $qobj->taxonomy )->parent, $qobj->taxonomy );
	$idsx = array();	// Annonser allerede vist, skal ekskluderes
	$apos_terms = array( 'bred' );
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
	if ( $qobj && $qobj->taxonomy && 'category' == $qobj->taxonomy ) {
		$transient = pp_ann_type() . '_' . pp_head_term() . '_category_' . $qobj->slug;
		$annonser = get_transient( $transient );
		if ( $annonser && is_array( $annonser ) && count( $annonser ) ) {
			$source = 'transient';
		} else {
			$source = 'fresh';
			$termsx = get_terms( 'category', array( 'fields' => 'ids' ) );		// Kategori-termer som ikke skal være med når kategori ikke er med i kriteriene
			$annonser = array();
			for ( $apos = 0; $apos < count( $apos_terms ); $apos++ ) {
				if ( is_page() && ! is_front_page() ) {
					$alev = 0;
					while( empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
						$annonse = get_posts( array(
							'posts_per_page' => -1,
							'post_type' => pp_ann_type(),
							'tax_query' => array( 'relation' => 'AND',
								array( 'taxonomy' => $qobj->taxonomy, 'field' => 'id',   'terms' => $termsx, 'operator' => 'NOT IN', 'include_children' => false ),
								array( 'taxonomy' => pp_apos_tax(),   'field' => 'slug', 'terms' => 'pos-' . ( $apos + 1 ) ),
								array( 'taxonomy' => pp_alev_tax(),   'field' => 'slug', 'terms' => $alev_terms[ $alev ]   )
							),
							'meta_query' => $meta_query,
							'exclude' => array_unique( $idsx ),
							'orderby' => 'rand'
						) );
						if ( count( $annonse ) ) {
							foreach ( $annonse as $ann ) {
								if ( $post->ID == intval( get_post_meta( $ann->ID, 'side', true ) ) ) {
									$ann->src = $ann->ID . ' side ' . $post->ID;
									$annonser[ $apos_terms[ $apos ] ] = $ann;
									break;
								}
							}
							$idsx[] = intval( $annonse[0]->ID );
						}
						$alev++;	// Fra pri-1 til pri-3 via $alev_terms
					}
				}
				$alev = 0; //Prøv først med både kategori, posisjon og prioritet
				while( empty( $annonser[ $apos_terms[ $apos ] ] ) && $alev < count( $alev_terms ) ) {
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
					if ( count( $annonse ) ) {
						$annonse[0]->src = $annonse[0]->ID . ' pos-' . ( $apos + 1 ) . ' ' . $alev_terms[ $alev ] . ' ' . $qobj->name;
						$annonser[ $apos_terms[ $apos ] ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
				$alev = 0; //Prøv først med både kategori, posisjon og prioritet
				while( empty( $annonser[ $apos_terms[ $apos ] ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => $qobj->taxonomy, 'field' => 'slug', 'terms' => $parent->slug, 'include_children' => false ),
							array( 'taxonomy' => pp_apos_tax(),   'field' => 'slug', 'terms' => $apos_terms[ $apos ] ),
							array( 'taxonomy' => pp_alev_tax(),   'field' => 'slug', 'terms' => $alev_terms[ $alev ] )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						$annonse[0]->src = $annonse[0]->ID . ' pos-' . ( $apos + 1 ) . ' ' . $alev_terms[ $alev ] . ' ' . $qobj->name;
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
							array( 'taxonomy' => 'category'   , 'field' => 'id',   'terms' => $termsx, 'operator' => 'NOT IN', 'include_children' => false ),
							array( 'taxonomy' => pp_apos_tax(), 'field' => 'slug', 'terms' => $apos_terms[ $apos ] ),
							array( 'taxonomy' => pp_alev_tax(), 'field' => 'slug', 'terms' => $alev_terms[ $alev ] )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						$annonse[0]->src = $annonse[0]->ID . ' pos-' . $apos_terms[ $apos ] . '-' . $alev_terms[ $alev ];
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
							array( 'taxonomy' => $qobj->taxonomy, 'field' => 'id',   'terms' => $termsx, 'operator' => 'NOT IN', 'include_children' => false ),
							array( 'taxonomy' => pp_apos_tax(),   'field' => 'slug', 'terms' => pp_head_term()       ),
							array( 'taxonomy' => pp_alev_tax(),   'field' => 'slug', 'terms' => $alev_terms[ $alev ] )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						$annonse[0]->src = $annonse[0]->ID . ' pos-' . pp_head_term() . '-' . $alev_terms[ $alev ];
						$annonser[ $apos_terms[ $apos ] ] = $annonse[0];
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
			$termsx = get_terms( 'category', array( 'fields' => 'ids' ) );		// Kategori-termer som ikke skal være med når kategori ikke er med i kriteriene
			$annonser = array();
			for ( $apos = 0; $apos < count( $apos_terms ); $apos++ ) {
				$alev = 0;
				while( empty( $annonser[ $apos_terms[ $apos ] ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => -1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => 'category'   , 'field' => 'id',   'terms' => $termsx, 'operator' => 'NOT IN', 'include_children' => false ),
							array( 'taxonomy' => pp_apos_tax(), 'field' => 'slug', 'terms' => $apos_terms[ $apos ] ),
							array( 'taxonomy' => pp_alev_tax(), 'field' => 'slug', 'terms' => $alev_terms[ $alev ] )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						foreach ( $annonse as $ann ) {
							if ( empty( get_post_meta( $ann->ID, 'side', true ) ) ) {
								$ann->src = $ann->ID . ' pos-' . $apos_terms[ $apos ] . '-' . $alev_terms[ $alev ];
								$annonser[ $apos_terms[ $apos ] ] = $ann;
								break;
							}
						}
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
		pp_widget_annonser_li( $annonser, false, 'large', $source );
	}
	echo PHP_EOL, ' </ul>';
	echo PHP_EOL;
?>