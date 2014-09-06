<?php
/**
 * The template used for displaying aktivitet content
 *
 * @package Pensjonistportalen
 * @subpackage SeniorAktiviteter
 */

function pp_edit_single_aktivitet_page_id() {
	return '1406';
}
get_header();
?>
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
<?php
	// Start the Loop.
	while ( have_posts() ) : the_post();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
<?php
	// Aktivitet thumbnail and title.
	twentyfourteen_post_thumbnail();
	the_title( '<h1 class="entry-title">', '</h1>' );
?>
		<div class="entry-meta">
<?php
//	twentyfourteen_posted_on();
	$pod = pods( get_post_type(), get_the_ID() );
	$fields = array();
	foreach( get_object_taxonomies( get_post_type(), 'objects' ) as $name => $field ) {
		$fields[ $name ]['type']  = 'taxonomy';
		$fields[ $name ]['label'] = $field->label;
	}
	//$fields['date']['type']  = 'query';
	//$fields['date']['label'] = 'Startdato';
	$fields['startdate']['type'] = 'compiled';
	$fields['startdate']['label'] = 'Kalender';
	$fields = array_merge( $fields, $pod->fields() ); //var_dump( $fields );
	// Detect website field(s), set previous field as linked and remove website field:
	foreach ( $fields as $name => $field ) {
		if ( 'website' == $field['type'] && $prename ) {
			$fields[ $prename ]['link'] = $name;
			unset ( $fields[ $name ] );
		}
		$prename = $name;
	}
	$multi  = ! empty( $pod->field( 'gjenta', true ) ) || ! empty( $pod->field( 'periode', true ) );
	$nwday  = $multi && ! empty( $pod->field( 'ukedag', true ) );
	unset( $fields['ganger' ] );
	unset( $fields['periode'] );
	unset( $fields['interval'] );
	unset( $fields['gjentagelse'] );
	unset( $fields['gjenta'] );
	unset( $fields['ukedager'] );
	unset( $fields['n_ukedag'] );
	if ( $multi || $pod->field( 'sluttdato', true ) == $pod->field( 'startdato', true ) )
		unset( $fields['sluttdato'] );
	if ( ! $multi )
		add_filter( 'option_wpuf_general', function( $value ) { $value['edit_page_id'] = pp_edit_single_aktivitet_page_id(); return $value; } );
	edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' );
	if ( current_user_can( 'edit_others_' . pp_akt_type(). 's'  ) ) {
		remove_all_filters( 'get_edit_post_link' );
		edit_post_link( __( 'Edit', 'twentyfourteen' ) . ' i admin', '<span class="edit-link">', '</span>' );
	}
?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php
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
					echo PHP_EOL, ' <dl class="pod-fields">';
					$dlist = true;
				}
				if ( 'taxonomy' == $field['type'] ) {
					$title = 'Vis alle';
					$title = $title ? ' title="' . $title . '"' : '';
					$class = $field['type'] . '-' . $name;
					$class = $class ? ' class="' . $class . '"' : '';
					$value = get_the_term_list( get_the_ID(), $name, '', ', ', '' );
					echo PHP_EOL, '  <dt', $class, '>', $field['label'], '</dt>';
					echo PHP_EOL, '  <dd', $class, $title, '>', $value, '</dd>';
				} elseif ( 'query' == $field['type'] ) {
					$class = $field['type'] . '-' . $name;
					$class = $class ? ' class="' . $class . '"' : '';
//					$title = 'Viser aktuell dato';
					$title = $title ? ' title="' . $title . '"' : '';
					$value = mysql2date( get_option( 'date_format' ), esc_attr( $_GET[ $name ] ), true ) ;
					echo PHP_EOL, '  <dt', $class, '>', $field['label'], '</dt>';
					echo PHP_EOL, '  <dd', $class, $title, '>', $value, '</dd>';
				} elseif ( 'compiled' == $field['type'] ) {
					$class = $field['type'] . '-' . $name;
					$class = $class ? ' class="' . $class . '"' : '';
					$title = $field[ 'label'];
					$title = $title ? ' title="' . $title . '"' : '';
					$hver = $pod->field( 'interval', true );
					$hver = 1 >= intval( $hver ) ? '' : $hver . '. ';
					$ukedager = $pod->field( 'ukedager' );
					$ukedager = is_array( $ukedager ) ? $ukedager : array( $ukedager );
					$ganger = ( intval( $multi ) + 1 ) . ' ganger';
					$periode = $pod->field( 'ukedager', true ) ? strrev( implode( strrev( ' og'), explode( ',', strrev( implode( ', ', $ukedager ) ), 2) ) ) : date_i18n( 'l', strtotime( $pod->field( 'startdato', true ) ) );//$pod->field( 'periode', true );
					$value = $multi ? 'Hver ' . $hver . $periode . ' fra ' . $pod->display( 'startdato' ) : ( $pod->field( 'sluttdato', true ) > $pod->field( 'startdato', true ) ? 'Flerdagers' . pp_akt_type() : 'Enkelt' . pp_akt_type() );
					if ( $nwday )
						$value = $pod->field( 'n_ukedag', true ) . '. ' . $pod->field( 'ukedag', true ) . ' i hver '. $hver . ' måned fra ' . $pod->display( 'startdato' );
					echo PHP_EOL, '  <dt', $class, '>', $field['label'], '</dt>';
					echo PHP_EOL, '  <dd', $class, $title, '>', $value, '</dd>';
				} else {
					if ( 'date' == $field['type'] ) {
						if ( 'startdato' == $field['name'] && isset ( $_GET['date'] ) )
							$date = $_GET['date'];
						else
							$date = $pod->display( $name );
						$value = intval( $pod->field( $name, true ) ) ? ucfirst( date_i18n( 'l', strtotime( $date ) ) ) . ' ' . mysql2date( get_option( 'date_format' ), $date ) : '';
					} else
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
//							if ( 'pick' == $field['type'] ) {
//								$rel = $pod->field( $name );
//								$link   = get_permalink( $rel['ID'] );
//								$img    = get_the_post_thumbnail( $rel['ID'] );
//								$terms  = '';
//							} else {
								$link  = isset( $field['link'] ) ? esc_url( $pod->field( $field['link'], true, true ) ) : false;
								$img   = '';
								$terms = '';
//							}
							echo PHP_EOL, '  <dt', $class, $title, '>', $pod->fields( $name, 'label' ), $terms, '</dt>';
							if ( $link ) {
								$linkh = $link ? ' href="' . $link . '"' : '';
								$linkc = $pod->fields( $field['link'], 'class' );
								$linkc = $linkc ? ' class="' . $linkc . '"' : '';
								$linkt = $pod->fields( $field['link'], 'description' );
								$linkt = $linkt ? ' title="' . $linkt . '"' : '';
								echo '<a ', $linkh, ' target="_blank">', $img, '</a>';
								echo PHP_EOL, '  <dd', $class, $title, '><a', $linkh, $linkc, $linkt, ' target="_blank">', $value, '</a></dd>';
							} else {
								echo $img;
								echo '  <dd', $class, $title, '>', make_clickable( $value ), '</dd>';
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
			the_content();
			//echo '<p>' . $post->post_content . '</p>';
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
<?php
					// Previous/next post navigation.
					if ( function_exists( 'pp_cpt_nav' ) )
						pp_cpt_nav();
					else
						twentyfourteen_post_nav();
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();
?>