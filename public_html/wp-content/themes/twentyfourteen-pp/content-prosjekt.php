<?php
/**
 * The template used for displaying Prosjekter
 *
 * @package Seniorportalen
 * @subpackage Seniorboportalen
 */

if ( is_single() ) {
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php
	twentyfourteen_post_thumbnail();
?>
	<header class="entry-header">
<?php
	the_title( '<h1 class="entry-title">', '</h1>' );
?>
		<div class="entry-meta">
<?php
	twentyfourteen_posted_on();
	edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' );
	if ( current_user_can( 'edit_others_' . pp_pro_type(). 's'  ) ) {
		remove_all_filters( 'get_edit_post_link' );
		edit_post_link( __( 'Edit', 'twentyfourteen' ) . ' i admin', '<span class="edit-link">', '</span>' );
	}
?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
	<div class="entry-content">
<?php
	$pod = pods( get_post_type(), get_the_ID() );
	$fields = array();
	foreach( get_object_taxonomies( get_post_type(), 'objects' ) as $name => $field ) {
		$fields[ $name ]['type']  = 'taxonomy';
		$fields[ $name ]['label'] = $field->label;
	}
	$fields = array_merge( $pod->fields(), $fields );
	// Detect website field(s), set previous field as linked and remove website field:
	foreach ( $fields as $name => $field ) {
		if ( ( 'website' == $field['type'] || 'email' == $field['type'] ) && $prename ) {
			$fields[ $prename ]['link'] = $name;
			unset ( $fields[ $name ] );
		}
		$prename = $name;
	}
	$dlist  = false;
	$ipick = 0;
	if ( 894 != get_the_ID() ) {
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
			$value = $value ?: '&nbsp;';
			echo PHP_EOL, '  <dt', $class, '>', $field['label'], '</dt>';
			echo PHP_EOL, '  <dd', $class, $title, '>', $value, '</dd>';
		} else {
			$value = $pod->display( $name );
			$value = $value || ! $field['link'] ? $value : ( 'email' == $field['type'] ? '(epost)' : '(ekstern lenke)' );
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
						$img    = get_the_post_thumbnail( $rel['ID'] );
//						$tomorr = strtotime( PP_UKEMENY_ADJUST, get_the_time( 'U' ) );
//						$year   = date( 'Y', $tomorr );
//						$week   = date( 'W', $tomorr );
//						$monday = strtotime( $year . 'W' . $week );
//						$terms  = ' ' . date( 'j.', strtotime( '+' . $ipick . 'days', $monday ) );
//						$terms .= get_the_term_list( $rel['ID'], 'ravare', ' - ', ', ', '' );
//						$terms .= get_the_term_list( $rel['ID'], 'vanskelighetsgrad', ' - ', ', ', '' );
//						$terms .= ' - ' . get_post_meta( $rel['ID'], 'tidsbruk', true );
					} else {
						$link  = isset( $field['link'] ) ? $pod->field( $field['link'], true, true ) : false;
						$img   = '';
						$terms = '';
					}
					echo PHP_EOL, '  <dt', $class, $title, '>', $pod->fields( $name, 'label' ), $terms, '</dt>';
					if ( $link ) {
						$linkm = esc_url( strpos( $link, '@' ) === false && strpos( $link, ':' ) ? $link : 'mailto:' . $link );
						$linkh = ' href="' . $linkm . '"';
						$linkc = $pod->fields( $field['link'], 'class' );
						$linkc = $linkc ? ' class="' . $linkc . '"' : '';
						$linkt = $pod->fields( $field['link'], 'description' );
						$linkt = $linkt ? ' title="' . $linkt . '"' : '';
						echo '<a ', $linkh, '>', $img, '</a>';
						echo PHP_EOL, '  <dd', $class, $title, '><a', $linkh, $linkc, $linkt, ' target="_blank">', $value, '</a></dd>';
					} else {
						echo $img;
						echo '  <dd', $class, $title, '>', $value, '</dd>';
					}
				}
			}
			if ( 'pick' == $field['type'] )
				$ipick ++;
		}
	}
	// Do we need to close the dl element?
	if ( $dlist )
		echo PHP_EOL, ' </dl>';
	}
	the_content();
?>
<br /><a href="/2013/registrer-interessent/"><button class="interessentknapp">Sett deg på interessentliste!</button></a>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
<?php
} else {
	echo PHP_EOL, ' <dt class="prosjekt-image">';
	$terms = array_values( get_the_terms( get_the_ID(), pp_kom_tax() ) );
	echo PHP_EOL, '  <h2 class="tax-kommune">', 1 < count( $terms ) ? single_cat_title() : $terms[0]->name, '</h2>';
	if ( has_post_thumbnail() ) {
		echo PHP_EOL, '  <a href="', get_permalink( get_the_ID() ), '" title="Les videre og se større bilde">';
		the_post_thumbnail( 'medium' );
	echo '</a>';
	} else
		echo '<p style="width: 300px;">Bilde mangler</p>';
	echo PHP_EOL, ' </dt>';
	echo PHP_EOL, ' <dd class="prosjekt-desc">';
	echo PHP_EOL, '  <h3 class="entry-title">';
	echo PHP_EOL, '  <a href="', get_permalink( get_the_ID() ), '" rel="bookmark" title="Les hele prosjektbeskrivelesen">';
	the_title();
	echo '</a>';
	echo PHP_EOL, '  </h3>';
	the_excerpt();
	if ( 894 != get_the_ID() )
		echo PHP_EOL, '  <a class="read-more" href="', get_permalink( get_the_ID() ), '">Les videre</a>';
	echo PHP_EOL, ' </dd>';
}
?>