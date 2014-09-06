<?php
class PP_After_Content_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		// widget actual processes
		parent::__construct( 'pp-after-content-widget', 'Populære innlegg (Portalene)', array( 'description' => 'Viser populære innlegg fra Portalene' ) );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		global $post;
		echo PHP_EOL, '<h3 class="jp-relatedposts-headline">', $instance['title'], '</h3>';
		echo PHP_EOL, '<div class="jp-relatedposts-items jp-relatedposts-items-visual">';
		$h = idate( 'H' );
		$ant = intval( $instance['antall'] );
		$getant = 4 * $ant;
		$src = 'admin';
		$sites = pp_sites( $src );
//		krsort( $sites );
		shuffle( $sites );
		foreach ( $sites as $site_id => $site ) {
			if ( PP_DOMAIN_SITE != $site_id && function_exists( 'stats_get_csv' ) ) {
				$post->blog_id = get_current_blog_id(); // Signal til switch_blog action
				switch_to_blog( $site_id );
				$post_ids = get_transient( 'pp_stats' );
				$npids = count( $post_ids );
				if ( false === $post_ids || ( $npids < $getant && ( $h < 9 || $h > 1 ) ) || $h < 1 ) {
					$stats = stats_get_csv( 'postviews', array( 'days' => $getant, 'limit' => $getant + $ant ) );
					$post_ids = array_values( array_filter( wp_list_pluck( $stats, 'post_id' ) ) );
					$npids = count( $post_ids );
					set_transient( 'pp_stats', $post_ids, DAY_IN_SECONDS );
					$src = 'fresh';
				} else
					$src = 'trans';
				$pof = get_option( 'page_on_front' );
				$i = 0;
				foreach( $post_ids as $post_id ) {
					$post = get_post( $post_id );
					if ( $post && ( 'page' != $post->post_type || $pof != $post_id ) && has_post_thumbnail( $post_id ) ) {
						$i++;
						$post->featured = true;
						$post->src = $src . ' ' . ( $i + 1 ) . ' av ' . $npids . ' max ' . $ant;
						setup_postdata( $post );
						get_template_part( 'content', 'after' );
					}
					if ( $i >= $ant )
						break;
				}
				restore_current_blog();
			}
		}
		echo PHP_EOL, '</div>';
		echo PHP_EOL;
	}

	/**
	 * Ouputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		?>
			<p>
				<label for="pp-after-content-title">Tittel:</label>
				<input id="pp-after-content-title" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width: 100%;" />
			</p><p>
				<label for="pp-after-content-antall">Antall fra hver portal:</label>
				<select id="pp-after-content-antall" name="<?php echo $this->get_field_name( 'antall' ); ?>">
<?php
		for ( $i = 1; $i <= 5; $i++ ) {
			echo PHP_EOL, '<option value="', $i, '"', $i == $instance['antall'] ? ' selected="selected"' : '', '>', $i. '</option>';
		}
?>
				</select>
			</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();
		$instance['title']  = $new_instance['title'];
		$instance['antall'] = $new_instance['antall'];
		return $instance;
	}
}
?>