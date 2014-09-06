<?php
// Denne klassen gir meg mulighet til å legge inn teksten "Min første egenkomponerte widget :-)" i en widget - Laget av Ingebjørg Synnøve Thoresen med Knut Sparhell som instruktør 20.3.2014

class PP_Inspirasjon_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct( 'pp-inspirasjon-widget', 'Inspirasjon', array( 'description' => 'Her kommer det en beskrivelse av hva denne widget kan gjøre.' ) );
	}

	public function widget( $args, $instance ) {
		echo '<aside class="widget widget_inspirasjon">';
		echo '<h1 class="widget-title">', $instance['title'], '</h1>';
		echo PHP_EOL, '<p>Min første egenkomponerte widget :-)</p>';
		echo '</aside>';
	}

	public function form( $instance ) {
		?>
			<p>
				<label for="pp-inspirasjon-title">Tittel:</label>
				<input id="pp-inspirasjon-title" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		return $instance;
	}
}
