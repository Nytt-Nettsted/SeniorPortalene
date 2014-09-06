<?php
function kcath_oa_links( $wp_admin_bar ) {
	$icon = '<div class="blavatar"></div>';
	$ms   = 'my-sites-list';
	$ht   = 'http://';
	$stem = 'optimalassistanse';
	$no   = '.no';
	$wa   = '/wp-admin/';
	$i    = 'id';
	$p    = 'parent';
	$t    = 'title';
	$h    = 'href';

	$sites = array(
		'kcd'   => array( 'site' => 'Taburetten i Dahlesunivers', 'url' => $ht . 'dahlesunivers'      . $no . '/kirsti' ),
		'il'    => array( 'site' => 'Independent Living'        , 'url' => $ht . 'independent-living' . $no ),
		'hk'    => array( 'site' => 'Handikonsult'              , 'url' => $ht . 'handikonsult'       . $no ),
		'oa-of' => array( 'site' => 'OA Østfold'                , 'url' => $ht . 'ostfold.' . $stem   . $no ),
		'oa-vr' => array( 'site' => 'OA Vestegionen'            , 'url' => $ht . 'vestreg.' . $stem   . $no ),
		'oa'    => array( 'site' => 'OptimalAssistanse',          'url' => $ht              . $stem   . $no ),
	);

	foreach ( $sites as $id => $site ) {
		$wp_admin_bar->add_menu( array( $i => $id       , $p => $ms, $t => $icon . $site['site']  , $h => $site['url'] . $wa ) );
		$wp_admin_bar->add_node( array( $i => $id . '-d', $p => $id, $t => __( 'Dashboard' )      , $h => $site['url'] . $wa ) );
		$wp_admin_bar->add_node( array( $i => $id . '-n', $p => $id, $t => __( 'New Post' )       , $h => $site['url'] . $wa . 'post-new.php' ) );
	if ( 'bpa' == $id )
		$wp_admin_bar->add_node( array( $i => $id . '-e', $p => $id,  $t =>    'Nytt emne'        , $h => $site['url'] . $wa . 'post-new.php?post_type=topic' ) );
		$wp_admin_bar->add_node( array( $i => $id . '-c', $p => $id, $t => __( 'Manage Comments' ), $h => $site['url'] . $wa . 'edit-comments.php' ) );
	if ( in_array( substr( $id, 0, 2 ), array( 'oa', 'hk' ) ) )
		$wp_admin_bar->add_node( array( $i => $id . '-t', $p => $id,  $t =>    'Timeregistrering' , $h => $site['url'] . $wa . ( 'hk' == $id ? 'users' : 'admin' ) . '.php?page=timeregistrering' ) );
		$wp_admin_bar->add_node( array( $i => $id . '-v', $p => $id, $t => __( 'Visit Site' )     , $h => $site['url'] . '/' ) );
	}
}
