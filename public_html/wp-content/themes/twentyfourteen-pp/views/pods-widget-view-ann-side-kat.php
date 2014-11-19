<?php
/*	Annonser for FrittbrukervalgPortalen m.fl
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
		$qobj = get_queried_object();
//	var_dump( $qobj );
	$parent = get_term_by( 'slug', pp_side_term(), pp_apos_tax() );
	$num_annonser = intval( get_terms( pp_apos_tax(), array( 'parent' => $parent->term_id, 'fields' => 'count', 'hide_empty' => false ) ) );
	$idsx = array();	// Annonser allerede vist, skal ekskluderes
	$alev_termobs = get_terms( pp_alev_tax(), array( 'orderby' => 'term_group', 'order' => 'ASC', 'hide_empty' => false ) );
	$alev_terms = array();
	foreach ( $alev_termobs as $alev_term )
		$alev_terms[] = $alev_term->slug;
//	var_dump( $alev_terms );
	$meta_query = array( 'relation' => 'OR',
		array( 'key' => 'annonsesluttdato', 'compare' => 'NOT EXISTS' ),
		array( 'key' => 'annonsesluttdato', 'compare' =>  '=', 'value' => 0, 'type' => 'UNSIGNED' ),
		array( 'key' => 'annonsesluttdato', 'compare' => '>=', 'value' => current_time( 'mysql' ), 'type' => 'DATETIME' )
		);
	echo PHP_EOL, ' <ul class="', pp_ann_type(), '">';
	if ( $qobj && $qobj->taxonomy && 'category' == $qobj->taxonomy ) {
		$transient = pp_ann_type() . '_' . pp_side_term() . '_category_' . $qobj->slug;
//		delete_transient( $transient );
		$annonser = get_transient( $transient );
		if ( $annonser && is_array( $annonser ) && count( $annonser ) ) {
			$source = 'transient';
		} else {
			$source = 'fresh';
			$parent = get_term_by( 'id', get_term_by( 'slug', $qobj->slug, $qobj->taxonomy )->parent, $qobj->taxonomy );
			$annonser = array();
			for ( $apos = 0; $apos < $num_annonser; $apos++ ) {
				if ( is_page() && ! is_front_page() ) {
					$alev = 0;
					while( empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
						$annonse = get_posts( array(
							'posts_per_page' => -1,
							'post_type' => pp_ann_type(),
							'tax_query' => array( 'relation' => 'AND',
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
									$annonser[ $apos ] = $ann;
									break;
								}
							}
							$idsx[] = intval( $annonse[0]->ID );
						}
						$alev++;	// Fra pri-1 til pri-3 via $alev_terms
					}
				}
//				echo $num_annonser, ' '; if ( $apos == 2 ) { echo $apos, ' '; var_dump( $annonser ); echo '<br/><br/>';}
				$alev = 0; //Prøv først med både kategori, posisjon og prioritet
				while( $qobj->slug && empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => $qobj->taxonomy, 'field' => 'slug', 'terms' => $qobj->slug            ),
							array( 'taxonomy' => pp_apos_tax(),   'field' => 'slug', 'terms' => 'pos-' . ( $apos + 1 ) ),
							array( 'taxonomy' => pp_alev_tax(),   'field' => 'slug', 'terms' => $alev_terms[ $alev ]   )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
//						$annonse[0]->src = $annonse[0]->ID . ' pos-' . ( $apos + 1 ) . ' ' . $alev_terms[ $alev ] . ' ' . $qobj->name;
						$annonser[ $apos ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
//				if ( $apos == 1 ) { var_dump( $annonse ); }
//				if ( $apos == 1 ) { var_dump ( $terms ); echo $apos, ' (', $annonse->ID, ') ', $alev, ' ', $alev_terms[ $alev ], '<br/>';}
				$alev = 0; //Prøv først med både kategori, posisjon og prioritet
				while( $parent && empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => $qobj->taxonomy, 'field' => 'slug', 'terms' => $parent->slug, 'include_children' => false ),
							array( 'taxonomy' => pp_apos_tax(),   'field' => 'slug', 'terms' => 'pos-' . ( $apos + 1 ) ),
							array( 'taxonomy' => pp_alev_tax(),   'field' => 'slug', 'terms' => $alev_terms[ $alev ]   )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
//						$annonse[0]->src = $annonse[0]->ID . ' pos-' . ( $apos + 1 ) . ' ' . $alev_terms[ $alev ] . ' ' . $qobj->name;
						$annonser[ $apos ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
//				if ( $apos == 1 ) { var_dump( $annonse ); }
//				if ( $apos == 1 ) { echo $apos, ' (', $annonse->ID, ') ', $alev, ' ', $alev_terms[ $alev ], '<br/>';}
				$alev = 0;	// Prøv igjen med kategori og posisjon, nå uten prioritet
				while( empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => $qobj->taxonomy, 'field' => 'slug', 'terms' => $qobj->slug            ),
							array( 'taxonomy' => pp_apos_tax(),   'field' => 'slug', 'terms' => 'pos-' . ( $apos + 1 ) )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						$annonse[0]->src = $annonse[0]->ID . ' pos-' . ( $apos + 1 ) . ' ' . $qobj->name;
						$annonser[ $apos ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
				$alev = 0;	// Prøv igjen med kategori og posisjon, nå uten prioritet
				while( empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => $qobj->taxonomy, 'field' => 'slug', 'terms' => $parent->slug, 'include_children' => false ),
							array( 'taxonomy' => pp_apos_tax(),   'field' => 'slug', 'terms' => 'pos-' . ( $apos + 1 ) )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						$annonse[0]->src = $annonse[0]->ID . ' pos-' . ( $apos + 1 ) . ' ' . $qobj->name;
						$annonser[ $apos ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
				$alev = 0;	// Prøv igjen uten å forlange kategori, men med posisjon og prioritet
				while( empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => pp_apos_tax(),   'field' => 'slug', 'terms' => 'pos-' . ( $apos + 1 ) ),
							array( 'taxonomy' => pp_alev_tax(),   'field' => 'slug', 'terms' => $alev_terms[ $alev ]   )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						$annonse[0]->src = $annonse[0]->ID . ' pos-' . ( $apos + 1 ) . ' ' . $alev_terms[ $alev ];
						$annonser[ $apos ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
				$alev = 0;	// Prøv igjen uten å forlange kategori, uten prioritet, men med posisjon. Skal ikke være aktuelt, da alle normalt skal ha prioritet
				while( empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => pp_apos_tax(),   'field' => 'slug', 'terms' => 'pos-' . ( $apos + 1 ) ),
							array( 'taxonomy' => pp_alev_tax(),   'field' => 'slug', 'terms' => pp_side_term()         )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						$annonse[0]->src = $annonse[0]->ID . ' pos-' . ( $apos + 1 );
						$annonser[ $apos ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
				$alev = 0;	// Prøv igjen med kategori, uten å forlange posisjon, med prioritet
				while( empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => $qobj->taxonomy, 'field' => 'slug', 'terms' => $qobj->slug          ),
							array( 'taxonomy' => pp_apos_tax(),   'field' => 'slug', 'terms' => pp_side_term()       ),
							array( 'taxonomy' => pp_alev_tax(),   'field' => 'slug', 'terms' => $alev_terms[ $alev ] )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						$annonse[0]->src = $annonse[0]->ID . ' ' . $alev_terms[ $alev ] . ' ' . $qobj->name;
						$annonser[ $apos ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
				$alev = 0;	// Prøv igjen med kategori, uten å forlange posisjon, med prioritet
				while( empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => $qobj->taxonomy, 'field' => 'slug', 'terms' => $parent->slug, 'include_children' => false ),
							array( 'taxonomy' => pp_apos_tax(),   'field' => 'slug', 'terms' => pp_side_term()       ),
							array( 'taxonomy' => pp_alev_tax(),   'field' => 'slug', 'terms' => $alev_terms[ $alev ] )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						$annonse[0]->src = $annonse[0]->ID . ' ' . $alev_terms[ $alev ] . ' ' . $qobj->name;
						$annonser[ $apos ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
				$alev = 0;	// Prøv igjen uten kategori, uten å forlange posisjon, med prioritet
				while( empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => pp_apos_tax(),   'field' => 'slug', 'terms' => pp_side_term()       ),
							array( 'taxonomy' => pp_alev_tax(),   'field' => 'slug', 'terms' => $alev_terms[ $alev ] )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						$annonse[0]->src = $annonse[0]->ID . ' ' . $alev_terms[ $alev ];
						$annonser[ $apos ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
				$alev = 0;	// Prøv igjen med kategori, uten posisjon, uten prioritet
				while( empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => $qobj->taxonomy, 'field' => 'slug', 'terms' => $qobj->slug    ),
							array( 'taxonomy' => pp_alev_tax(),   'field' => 'slug', 'terms' => pp_side_term() )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						$annonse[0]->src = $annonse[0]->ID . ' ' . $qobj->name;
						$annonser[ $apos ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
				$alev = 0;	// Prøv igjen med kategori, uten posisjon, uten prioritet
				while( empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => $qobj->taxonomy, 'field' => 'slug', 'terms' => $parent->slug  ),
							array( 'taxonomy' => pp_alev_tax(),   'field' => 'slug', 'terms' => pp_side_term() )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						$annonse[0]->src = $annonse[0]->ID . ' ' . $qobj->name;
						$annonser[ $apos ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
				// Prøv igjen uten kategori, uten prioritet, uten posisjon. Skal ikke være aktuelt, da alle normalt skal ha prioritet og posisjon
				if ( empty( $annonser[ $apos ] ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array(
							array( 'taxonomy' => pp_apos_tax(), 'field' => 'slug', 'terms' => pp_side_term() )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						$annonse[0]->src = $annonse[0]->ID . ' ' . pp_side_term();
						$annonser[ $apos ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
				}
			}
			set_transient( $transient, $annonser, PP_ANN_TRANS_EXP );
		}
		pp_widget_annonser_li( $annonser, false, null, $source );
	} else {
		$transient = pp_ann_type() . '_' . pp_side_term();
		$annonser = get_transient( $transient );
		if ( $annonser && is_array( $annonser ) && count( $annonser ) ) {
			$source = 'transient';
		} else {
			$termsx = get_terms( 'category', array( 'fields' => 'ids' ) );		// Kategori-termer som ikke skal være med når kategori ikke er med i kriteriene
			$source = 'fresh';
			$annonser = array();
			for ( $apos = 0; $apos < $num_annonser; $apos++ ) {
				$alev = 0;
				while( empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => 'category',    'field' => 'id'  , 'terms' => $termsx, 'operator' => 'NOT IN', 'include_children' => false ),
							array( 'taxonomy' => pp_apos_tax(), 'field' => 'slug', 'terms' => 'pos-' . ( $apos + 1 ) ),
							array( 'taxonomy' => pp_alev_tax(), 'field' => 'slug', 'terms' => $alev_terms[ $alev ]   )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						$annonse[0]->src = 'pos-' . ( $apos + 1 );
						$annonser[ $apos ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
				while( empty( $annonser[ $apos ] ) && $alev < count( $alev_terms ) ) {
					$annonse = get_posts( array(
						'posts_per_page' => 1,
						'post_type' => pp_ann_type(),
						'tax_query' => array( 'relation' => 'AND',
							array( 'taxonomy' => 'category',    'field' => 'id',   'terms' => $termsx, 'operator' => 'NOT IN', 'include_children' => false ),
							array( 'taxonomy' => pp_apos_tax(), 'field' => 'slug', 'terms' => pp_side_term() ),
							array( 'taxonomy' => pp_alev_tax(), 'field' => 'slug', 'terms' => $alev_terms[ $alev ]   )
						),
						'meta_query' => $meta_query,
						'exclude' => array_unique( $idsx ),
						'orderby' => 'rand'
					) );
					if ( count( $annonse ) ) {
						$annonse[0]->src = 'pos-' . ( $apos + 1 );
						$annonser[ $apos ] = $annonse[0];
						$idsx[] = intval( $annonse[0]->ID );
					}
					$alev++;	// Fra pri-1 til pri-3 via $alev_terms
				}
			}
			set_transient( $transient, $annonser, PP_ANN_TRANS_EXP );
		}
		pp_widget_annonser_li( $annonser, false, 'medium', $source );
	}
	echo PHP_EOL, ' </ul>';
	echo PHP_EOL;
?>