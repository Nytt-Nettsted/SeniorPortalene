<?php
/**
 * The template used for displaying oppskrift content
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php twentyfourteen_post_thumbnail(); ?>
	<header class="entry-header">
		<?php if ( in_array( 'innhold', get_object_taxonomies( get_post_type() ) ) ) : ?>
		<div class="entry-meta">
			<span class="cat-links"><?php echo get_the_term_list( get_the_ID(), 'innhold', '', ', ' ); ?></span>
		</div>
		<?php
		endif;
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
		endif;
	?>

	<div class="entry-content">
		<?php
			echo PHP_EOL, '<h2 class="ernfyskom">Ernæringsfysiologens kommentar</h2>';
			if ( function_exists( 'sharing_display' ) )
				remove_filter( 'the_excerpt', 'sharing_display', 19 );
			the_excerpt();
			if ( is_single() ) {
				$pod = pods( get_post_type(), get_the_ID() );
				$porsjoner = $pod->field( 'porsjoner', true );
				// Oppskriftinfo
				echo PHP_EOL, ' <div id="oppskrift-info"><dl class="pod-fields">';
				echo PHP_EOL, '<dt class="ant-porsjoner">Porsjoner</dt>';
				echo PHP_EOL, '<dd class="ant-porsjoner">';
				echo $pod->display( 'porsjoner' );
				echo PHP_EOL, '</dd>';
				echo PHP_EOL, '<dt class="ant-minutter">Tidsbruk</dt>';
				echo PHP_EOL, '<dd class="ant-minutter">';
				echo $pod->display( 'tidsbruk' );
				echo PHP_EOL, '</dd>';
				echo PHP_EOL, ' </dl></div> ';

				// Ingredienser
				echo PHP_EOL, '<div id="ingredienser"><h2 class="ingredienser-title">Ingredienser</h2>';
				echo PHP_EOL, '<dl><dt class="n-ingredienser">';
				echo $pod->display( 'ingredienser' );
				echo PHP_EOL, '</dt>';
				echo PHP_EOL, '<dd class="naering"><h3 class="n_title">Næringsinnhold</h3> ';
				echo PHP_EOL, '<table>';
				echo '<tr><th id="beskrivelse"></th><th>Hele retten</th><th>Pr. porsjon</th>';
				echo '</tr>';
				echo '</tr><tr><td class="n-innhold">Energi (kcal)</td>';
				echo PHP_EOL, '<td class="n-verdi">';
				echo $pod->display( 'energi' );
				echo PHP_EOL, '</td>';
				echo PHP_EOL, '<td class="p-verdi">';
				echo  number_format( floatval( $pod->field( 'energi', true ) ) / $porsjoner, 1, ',', ' ' );
				echo PHP_EOL, '</td>
				</tr>
				<tr><td class="n-innhold">Protein (g)</td> ';
				echo PHP_EOL, '<td class="n-verdi">';
				echo $pod->display( 'protein' );
				echo PHP_EOL, '</td>';
				echo PHP_EOL, '<td class="p-verdi">';
				echo number_format( floatval( $pod->field( 'protein', true ) ) / $porsjoner, 1, ',', ' ' );
				echo PHP_EOL, '</td>
				</tr>
				<tr><td class="n-innhold">Fett (g)</td> ';
				echo PHP_EOL, '<td class="n-verdi">';
				echo $pod->display( 'fett' );
				echo PHP_EOL, '</td>';
				echo PHP_EOL, '<td class="p-verdi">';
				echo number_format( floatval( $pod->field( 'fett', true ) ) / $porsjoner, 1, ',', ' ' );
				echo PHP_EOL, '</td>
				</tr>
				<tr><td class="n-innhold">Karbohydrat&nbsp;(g)</td> ';
				echo PHP_EOL, '<td class="n-verdi">';
				echo $pod->display( 'karbo' );
				echo PHP_EOL, '</td>';
				echo PHP_EOL, '<td class="p-verdi">';
				echo number_format ( floatval($pod->field( 'karbo', true ) ) / $porsjoner, 1, ',', ' ' );
				echo PHP_EOL, '</td>
				</tr>
				<tr><td class="n-innhold">Fiber (g)</td> ';
				echo '<td class="n-verdi">';
				echo $pod->display( 'fiber' );
				echo '</td>';
				echo '<td class="p-verdi">';
				echo number_format( floatval($pod->field( 'fiber', true ) ) / $porsjoner, 1, ',', ' ' );
				echo PHP_EOL, '</td>';
				echo '</tr>';
				echo PHP_EOL, '</table></dd></dl></div>';

				// Fremgangsmåte
				echo PHP_EOL, ' <h2 class="fremgangsmate-title">Fremgangsmåte</h2> ';
					echo $pod->field( 'fremgangsmate' );

				if ( function_exists( 'sharing_display' ) )
					echo sharing_display();
				//edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' );
			} else	// Brukes ikke
				echo PHP_EOL, '<p style="clear: both;"><a href="', get_permalink(),'">Les mer om oppskriften</a></p>';
		?>
	</div><!-- .entry-content -->
	<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
</article><!-- #post-## -->
