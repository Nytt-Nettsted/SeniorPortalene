<?php
/**
 * The template used for displaying produkt content
 *
 * @package Seniorportalene
 * @subpackage E-seniorportalen
 */
?>

	<dt id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php the_post_thumbnail(); ?>
	</dt>
	<dd class="entry-content type-<?php echo get_post_type(); ?>">
		<?php
			$pod = pods( get_post_type(), get_the_ID() );
			$taxname = get_taxonomy( pp_forh_tax() )->labels->singular_name;
			$terms = wp_get_object_terms( get_the_ID(), pp_forh_tax(), array( 'fields' => 'names' ) );
			the_title( ' <header class="entry-header "><h1 class="entry-title">', '</h1></header><!-- .entry-header -->' );
			the_content();
			echo PHP_EOL, $taxname, ': <a href="', $pod->display( 'website' ), '" title="', $pod->fields( 'website', 'description' ), '" class="', $pod->fields( 'website', 'class' ), '">', $terms[0], '</a>'; 

			$subprods = get_posts( array( 'post_type' => pp_prd_type(), 'post_parent' => get_the_ID() ) );
			if ( count( $subprods ) ) {
				echo PHP_EOL, ' <br /><h2 class="pod-fields list-title">Tilleggsprodukter</h2>';
				echo PHP_EOL, ' <ol class="sub-list">';
				foreach ( $subprods as $subprod ) {
					$subpod = pods( get_post_type(), $subprod->ID );
					echo PHP_EOL, '  <li>';
					echo PHP_EOL, '   <h3 class="sub-title">', $subpod->display( 'post_title' ), '</h3>';
					echo PHP_EOL, $subpod->display( 'post_content' );
					$terms = wp_get_object_terms( $subprod->ID, pp_forh_tax(), array( 'fields' => 'names' ) );
					echo PHP_EOL, $taxname, ': <a href="', $subpod->display( 'website' ), '" title="', $subpod->fields( 'website', 'description' ), '" class="', $subpod->fields( 'website', 'class' ), '">', $terms[0], '</a>';
					echo PHP_EOL, '  </li>';
				}
				echo PHP_EOL, ' </ol>';
			}
			edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' );
		?>
		<hr />
	</dd><!-- .entry-content -->
