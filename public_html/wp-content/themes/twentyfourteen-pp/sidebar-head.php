<?php
/**
 * The Head Sidebar
 *
 * @subpackage Seniorportalene
 */

if ( ! is_active_sidebar( 'head' ) ) {
	return;
}
//add_filter( 'widget_title', 'pp_widget_title_empty' );//Finnes ikke
?>

<div id="introductory">
	<div id="head-sidebar" class="head-sidebar widget-area" role="complementary">
		<?php dynamic_sidebar( 'head' ); ?>
	</div><!-- #head-sidebar -->
</div><!-- #introductory -->

<?php
//remove_filter( 'widget_title', 'pp_widget_title_empty' );
?>