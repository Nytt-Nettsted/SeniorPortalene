<?php
function pp_options() {
	$src = '';
	$site = pp_sites( $src )[1];
	return array(
		array( 'section' => 'pp_welcome_section', 'Label' => 'Bidrag til siden Velkommen på <a href="' . $site->siteurl . '">' . $site->blogname . '</a>', 'fields' => array(
			array( 'name' => 'pp_description', 'Label' => 'Beskrivelse', 'id' => 'pp-desc', 'type' => 'text' ),
		) ),
	);
}

function pp_settings_field( $arg ) {
	if ( $arg['type'] == 'options' ) {
?>
		<select id="<?php echo $arg['label_for']; ?>" name="<?php echo $arg['name']; ?>">
<?php
		foreach ( $arg['options'] as $key => $option ) {
?>
			<option value="<?php echo $key; ?>"<?php echo get_option($arg['name']) == $key ? ' selected="selected"' : ''; ?>><?php echo $option; ?></option>
<?php
		}
?>
		</select><?php echo strlen($key) > strlen($option) ? ' &nbsp; <span class="description">' . get_option( $arg['name'] ) . '</span>' : ''; ?>
<?php
	} elseif ( $arg['type'] == 'checkbox' ) {
?>
		<input id="<?php echo $arg['label_for']; ?>" name="<?php echo $arg['name']; ?>" type="<?php echo $arg['type']; ?>"<?php echo get_option( $arg['name'] ) ? ' checked="checked"' : '';?>"/>
<?php
	} elseif ( $arg['type'] == 'text' ) {
?>
		<textarea id="<?php echo $arg['label_for']; ?>" name="<?php echo $arg['name']; ?>" cols="100" rows="5"><?php echo trim( get_option( $arg['name'] ) ); ?></textarea>
<?php
	} else {
?>
		<input id="<?php echo $arg['label_for']; ?>" name="<?php echo $arg['name']; ?>" value="<?php echo get_option( $arg['name'] ) ;?>"/><?php echo ' ', $arg['unit']; ?>
<?php
	}
}

function pp_settings_section( $arg ) {
?>
	<p class="description">Her angir du innstillinger og tekst for <?php echo mb_strtolower( $arg['title'] ); ?></p>
<?php
}

function pp_settings_page() {
?>
	<div class="wrap">
		<?php screen_icon( 'tools' ); ?> <h2><?php _e( 'Settings' ); ?> for portalen</h2>
		<form action="options.php" method="post">
			<?php settings_fields     ( 'pp-settings' ); ?>
			<?php do_settings_sections( 'pp-settings' ); ?>
			<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php echo __( 'Save Changes' ); ?>"/></p>
		</form>
	</div>
<?php
}

function pp_whitelist_options( $whitelist_options ) {
	$names = array();
	foreach ( pp_options() as $section )
		foreach ( $section['fields'] as $field )
			$names[] = $field['name'];
	$whitelist_options[ 'pp-settings' ] = $names;
	return $whitelist_options;
}

function pp_settings() {
	foreach ( pp_options() as $section ) {
		add_settings_section( $section['section'], $section['Label'], 'pp_settings_section', 'pp-settings' );
		foreach ( $section['fields'] as $field ) {
			add_settings_field( $field['name'], $field['Label'], 'pp_settings_field', 'pp-settings', $section['section'], array( 'name' => $field['name'], 'label_for' => $field['id'], 'type' => $field['type'], 'options' => isset( $field['options'] ) ? $field['options'] : array(), 'unit' => isset ( $field['unit'] ) ? $field['unit'] : '' ) );
			register_setting( $section['section'], $field['name'], isset( $field['cb'] ) ? $field['cb'] : null );
		}
	}
	add_filter( 'whitelist_options', 'pp_whitelist_options' );
}

function pp_admin_settings_style( $hook) {
	if ( 'pp_page_pp-settings' == $hook ) {
		wp_register_style( 'pp_admin_style', 'css/admin-pp-settings.css', false, '1' );
		wp_enqueue_style( 'pp_admin_style' );
	}

}
