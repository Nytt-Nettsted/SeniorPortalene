<?php
class PP_Portaler_Widget extends WP_Widget {
	const FILE_FOLDER = 'images';
	const FILE_SUFFIX = 'png';
	const FILE_XYSIZE = 98;

	public function __construct() {
		$src = '';
		$sites = pp_sites( $src );
		$text = 'våre portaler';
		parent::__construct( 'pp-portaler-widget', 'Besøk ' . ( is_null( $sites[ get_current_blog_id() ] ) ?  $sites[1]->blogname : $text ), array( 'description' => 'Lenker til ' . $text ) );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		$src = '';
		echo PHP_EOL, '		<aside id="', esc_attr( $this->id ) , '" class="widget ', esc_attr( $this->id_base ), '">';
		echo PHP_EOL, '			<h1 class="widget-title">', esc_attr( $this->name ), '</h1>';
		foreach ( pp_sites( $src ) as $site_id => $site ) {
			$name = esc_attr( $site->blogname );
			$img  = esc_attr( $site->domain ) . '.' . SELF::FILE_SUFFIX;
			$href = ' href="' . esc_url( $site->siteurl, array( 'http', 'https' ) ) . '/" rel="bookmark"';
			if ( get_current_blog_id() == $site_id ) {
				$href = is_home() || is_front_page() ? '' : $href;
				$her  = ' (du er her nå)';
			} else {
				$her  = '';
			}
			echo PHP_EOL, '			<a', $href, ' title="', mb_strtoupper( $name ), $her, '">';
			echo PHP_EOL, '				<img src="', get_stylesheet_directory_uri(), '/', SELF::FILE_FOLDER , '/', $img, '" alt="Logo for ', $name , '" class="size-medium wp-image-site-', $site_id , '" width="', SELF::FILE_XYSIZE , '" height="', SELF::FILE_XYSIZE , '" />';
			echo PHP_EOL, '			</a>';
		}
		echo PHP_EOL, '		</aside><!-- ', esc_attr( $this->id ), ' ', $src, ' -->';
		echo PHP_EOL, PHP_EOL;
	}

	/**
	 * Ouputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
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
		return $instance;
	}
}
?>