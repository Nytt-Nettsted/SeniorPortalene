<?php
/**
 * The template used for displaying ukemeny content
 *
 * @package Pensjonistportalen
 * @subpackage Ernæringsportalen
 */
//setlocale( LC_TIME, WPLANG . '.utf8', WPLANG );
$sep = ' - ';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		// Ukemeny thumbnail and title.
//		twentyfourteen_post_thumbnail();
		if ( is_page() || is_single() )
			the_title( '<header class="entry-header "><h1 class="entry-title">', '</h1></header><!-- .entry-header -->' );
		else
			the_title( '<header class="entry-header"><h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1></header>' );
	?>

	<div class="entry-content type-<?php echo get_post_type(); ?>">
		<?php
			the_excerpt();
			if ( is_page() || is_single() ) {
				$pod = pods( get_post_type(), get_the_ID() );
				$fields = array();
				foreach( get_object_taxonomies( get_post_type(), 'objects' ) as $name => $field ) {
					$fields[ $name ]['type']  = 'taxonomy';
					$fields[ $name ]['label'] = $field->label;
				}
				$fields = array_merge( $fields, $pod->fields() ); //var_dump( $fields );
				// Detect website field(s), set previous field as linked and remove website field:
	//			foreach ( $fields as $name => $field )
	//				if ( 'website' == $field['type'] && $prename ) {
	//					$fields[ $prename ]['link'] = $name;
	//					unset ( $fields[ $name ] );
	//				}
	//				$prename = $name;
	//			}
				$dlist  = false;
				$ipick = 0;
				foreach ( $fields as $name => $field ) {
					$wysi = 'wysiwyg' == $field['type'];
					if ( $dlist ) {
						if ( $wysi ) {
							echo PHP_EOL, ' </dl>';
							$dlist = false;
							$div = true;
						}
					} elseif ( ! $wysi ) {
						echo PHP_EOL, ' <dl claas="pod-fields">';
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
								if ( 'pick' == $field['type'] ) {
									$rel = $pod->field( $name );
									$link   = get_permalink( $rel['ID'] );
									$linko  = 'perma';
									$img    = get_the_post_thumbnail( $rel['ID'], 'thumbnail' );
									$tomorr = strtotime( PP_UKEMENY_ADJUST, get_the_time( 'U' ) );
									$year   = date( 'Y', $tomorr );
									$week   = date( 'W', $tomorr );
									$monday = strtotime( $year . 'W' . $week );
									$date   = strftime( '%e. %B', strtotime( '+' . $ipick . 'days', $monday ) );
									$terms  = get_the_term_list( $rel['ID'], 'ravare', '', ', ', '' );
									$terms .= get_the_term_list( $rel['ID'], 'vanskelighetsgrad', $sep, ', ', $sep );
									$terms .= get_post_meta( $rel['ID'], 'tidsbruk', true );
								} else {
									$link  = isset( $field['link'] ) ? esc_url( $pod->field( $field['link'], true, true ) ) : false;
									$linko = 'field';
									$img   = '';
									$terms = '';
								}
								if ( $link ) {
									$linkh = $link ? ' href="' . $link . '"' : '';
									if ( 'field' == $linko ) {
										$linkc = $pod->fields( $field['link'], 'class' );
										$linkt = $pod->fields( $field['link'], 'description' );
									} else {
										$linkc = $rel['post_type'] . '-title';
										$linkt = 'Vis ' . $rel['post_type'] . ' &laquo;'. $value . '&raquo;';
									}
									$linkc = $linkc ? ' class="' . $linkc . '"' : '';
									$linkt = $linkt ? ' title="' . $linkt . '"' : '';
									$img = '<a ' . $linkh . '>' . $img . '</a>';
									$value = '<a' . $linkh . $linkc . $linkt . '>' . $value . '</a>';
								}
								echo PHP_EOL, '  <dt', $class, $title, '>', $img, '</dt>';
								echo PHP_EOL, '  <dd', $class, $title, '><h2>', $pod->fields( $name, 'label' ), ' ', $date, '</h2><p>', $terms, '</p><p>', $value, '</p></dd>';
							}
						}
						if ( 'pick' == $field['type'] )
							$ipick ++;
					}
				}
			}
			// Do we need to close the dl element?
			if ( $dlist )
				echo PHP_EOL, ' </dl>';
			edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
