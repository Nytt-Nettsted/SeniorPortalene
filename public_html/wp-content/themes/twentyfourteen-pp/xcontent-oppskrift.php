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
	<?php
		// Page thumbnail and title.
		twentyfourteen_post_thumbnail();
		the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header><!-- .entry-header -->' );
	?>

	<div class="entry-content">
		<?php
			echo PHP_EOL, '<h2 class="ernfyskom">Ernæringsfysiologens kommentar</h2>';
			if ( function_exists( 'sharing_display' ) )
				remove_filter( 'the_excerpt', 'sharing_display', 19 );
			the_excerpt();
			if ( is_single() ) {
				$pod = pods( get_post_type(), get_the_ID() );
				$fields = array();
				foreach( get_object_taxonomies( get_post_type(), 'objects' ) as $name => $field ) {
					if ( 'post_tag' != $name ) {
						$fields[ $name ]['type']  = 'taxonomy';
						$fields[ $name ]['label'] = $field->label;
					}
				}
				$fields = array_merge( $fields, $pod->fields() );
				// Detect website field(s), set previous field as linked and remove website field:
				foreach ( $fields as $name => $field ) {
					if ( 'website' == $field['type'] && $prename ) {
						$fields[ $prename ]['link'] = $name;
						unset ( $fields[ $name ] );
					}
					$prename = $name;
				}
				$dlist  = false;
				foreach ( $fields as $name => $field ) {
					$wysi = 'wysiwyg' == $field['type'];
					if ( $dlist ) {
						if ( $wysi ) {
							echo PHP_EOL, ' </dl>';
							$dlist = false;
							$div = true;
						}
					} elseif ( ! $wysi ) {
						echo PHP_EOL, ' <dl class="pod-fields">';
						$dlist = true;
					}
					if ( 'taxonomy' == $field['type'] ) {
						$title = 'Vis alle';
						$title = $title ? ' title="' . $title . '"' : '';
						$class = $name . '-' . $name;
						$class = $class ? ' class="' . $class . '"' : '';
						$value = get_the_term_list( get_the_ID(), $name, '', ', ', '' );
						echo PHP_EOL, '  <dt', $class, '>', $field['label'], '</dt>';
						echo PHP_EOL, '  <dd', $class, $title, '>', $value, '</dd>';
					} else {
						$value = $pod->display( $name );
						if ( $value ) {
							$class = $pod->fields( $name, 'class' );
							$class = $class ? ' class="' . $class . '"' : '';
							$title = $pod->fields( $name, 'description' );
							$title = $title ? ' title="' . $title . '"' : '';
							if ( $wysi ) {
								echo PHP_EOL, ' <div id="', $name , '"', $class, '>';
								echo PHP_EOL, '  <h2', $class, $title, '>', $pod->fields( $name, 'label' ), '</h2>';
								echo PHP_EOL, $value;
								echo PHP_EOL, ' </div>';
							} else {
								echo PHP_EOL, '  <dt', $class, $title, '>', $pod->fields( $name, 'label' ), '</dt>';
								$link  = isset( $field['link'] ) ? esc_url( $pod->field( $field['link'], true, true ) ) : false;
								if ( $link ) {
									$linkh = $link ? ' href="' . $link . '"' : '';
									$linkc = $pod->fields( $field['link'], 'class' );
									$linkc = $linkc ? ' class="' . $linkc . '"' : '';
									$linkt = $pod->fields( $field['link'], 'description' );
									$linkt = $linkt ? ' title="' . $linkt . '"' : '';
									echo PHP_EOL, '  <dd', $class, $title, '><a', $linkh, $linkc, $linkt, '>', $value, '</a></dd>';
								} else
									echo '  <dd', $class, $title, '>', $value, '</dd>';
							}
						}
					}
				}
				// Do we need to close the dl element?
				if ( $dl )
					echo PHP_EOL, ' </dl>';
				if ( function_exists( 'sharing_display' ) )
					echo sharing_display();
				edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' );
			} else	// Brukes ikke
				echo PHP_EOL, '<p style="clear: both;"><a href="', get_permalink(),'">Les mer om oppskriften</a></p>';
		?>
	</div><!-- .entry-content -->
	<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
</article><!-- #post-## -->
