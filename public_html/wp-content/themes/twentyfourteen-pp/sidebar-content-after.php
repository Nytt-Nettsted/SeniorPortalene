<?php
/**
 * The Content After Sidebar
 *
 * @subpackage Seniorportalene
 */

if ( ! is_active_sidebar( 'content-after' ) ) {
	return;
}
?>
	<div id="jp-relatedposts" class="jp-relatedposts" style="display: block;" role="complementary">
        <?php dynamic_sidebar( 'content-after' ); ?>
	</div>
