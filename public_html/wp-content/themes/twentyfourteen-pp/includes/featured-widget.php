<?php
class PP_Featured_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		// widget actual processes
		parent::__construct( 'pp-featured-widget', 'Fremhevede nyheter', array( 'description' => 'Siste nytt fra portalene' ) );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		global $post;
		$src = '';
		$site_id      = intval(   $instance['site'] );
		$post_type = esc_attr( $instance['post_type'] );
		$which     = esc_attr( $instance['which'] );
		$current_site = get_current_blog_id();
 		add_filter( 'the_category', 'pp_the_category', 10, 1 );
		switch_to_blog( $site_id );
		$featured_posts = get_transient( PP_FEAT_TRANS );
		if ( $featured_posts === false || ! is_array( $featured_posts ) || count ( $featured_posts ) == 0 ) {
			if ( 'last' == $which )
				$featured_posts = get_posts( array( 'post_type' => $post_type, 'posts_per_page' => 1, 'suppress_filters' => false ) );
			else
				$featured_posts = array_slice( twentyfourteen_get_featured_posts(), 0, 1 );
			set_transient( PP_FEAT_TRANS, $featured_posts, PP_FEAT_TRANS_EXP );
			$src = 'fresh';
		} else
			$src = 'trans';
		$post = $featured_posts[0];
		$post->featured = true;
		$post->src = $src;
		$post->site = $current_site;
		setup_postdata( $post );
		set_transient( 'twentyfourteen_category_count', count( pp_sites( $src ) ) );
		get_template_part( 'content', 'widget-featured' );
		restore_current_blog();
		wp_reset_postdata();
	}

	/**
	 * Ouputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$src = 'fresh';
		$the_site  = $instance['site']      ? intval(   $instance['site'] )      : 1;
		$post_type = $instance['post_type'] ? esc_attr( $instance['post_type'] ) : 'post';
		$which     = $instance['which']     ? esc_attr( $instance['which'] )     : 'last';
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'site' ); ?>">Portal:</label>
		<select class="widefat" id="<?php echo $this->get_field_id( 'site' ); ?>" name="<?php echo $this->get_field_name( 'site' ); ?>">
<?php
		foreach( pp_sites( $src ) as $site_id => $site ) {
			$name = esc_attr( $site->blogname );
?>
			<option value="<?php echo $site_id; ?>"<?php echo $site_id == $the_site ? ' selected="selected"' : ''; ?>><?php echo $name; ?></option>
<?php
		}
?>
	</select>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'post_type' ); ?>">Post Type:</label>
		<select for="<?php echo $this->get_field_id( 'post_type' );?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>">
<?php
		foreach ( array_merge( array( 'post', 'page' ), pp_cpts() ) as $cpt ) {
?>
			<option value="<?php echo $cpt; ?>"<?php echo $post_type == $cpt ? ' selected="selected"' : ''; ?>><?php echo ucfirst( $cpt ); ?></option>
<?php
		}
?>
	</select>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'which' );  ?>">Hvilke:</label>
		<select for="<?php echo $this->get_field_id( 'which' ); ?>" name="<?php echo $this->get_field_name( 'which' ); ?>">
			<option value="last"<?php echo 'last' == $which ? ' selected="selected"' : ''; ?>>Siste</option>
			<option value="featured"<?php echo 'featured' == $which ? ' selected="selected"' : ''; ?>>Fremhevet</option>
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
		$instance['site']      = $new_instance['site']      ? intval(   $new_instance['site'] )      : 1;
		$instance['post_type'] = $new_instance['post_type'] ? esc_attr( $new_instance['post_type'] ) : 'post';
		$instance['which']     = $new_instance['which']     ? esc_attr( $new_instance['which'] )     : 'last';
		return $instance;
	}
}
?>