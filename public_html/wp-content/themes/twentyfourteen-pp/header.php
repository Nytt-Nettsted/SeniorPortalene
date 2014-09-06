<?php
/**
 * The Header for our child theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Seniorportalen
 * @by Nytt Nettsted - Knut Sparhell & Ingebjørg Synnøve Thoresen
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/images/<?php echo $_SERVER['HTTP_HOST']; ?>.ico" type="image/ico">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
	<link rel="stylesheet" id="fourteen-colors" href="<?php echo get_stylesheet_directory_uri(); ?>/css/colors-<?php echo $_SERVER['HTTP_HOST']; ?>.css" type="text/css" media="all">
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php if ( get_header_image() ) : ?>
	<div id="site-header"<?php echo false && in_array( get_current_blog_id(), pp_sidebar_head_sites() ) ? ' style="float: left;"' : ''; ?>>
	<!-- Styled in functions.php depending on sidebar-head -->
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
		</a>
	</div>
	<div class="socialbox">
		<a class="social facebook" href="http://www.facebook.com/<?php echo 7 == get_current_blog_id() ? 'bpaportalen' : 'SeniorPortalene'; ?>" title="Vår Facebook-side"></a>
		<a class="social epost" href="/kontakt-oss/" title="Send oss epost"></a>
		<a class="social rss" href="/feed/atom/" title="Nyheter for RSS/Atom-abonnement"></a>
	</div>
	<?php get_sidebar( 'head' ); ?>
	<?php endif; ?>

	<header id="masthead" class="site-header" role="banner">
		<div class="header-main">
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>

			<div class="search-toggle" title="<?php _e( 'Search', 'twentyfourteen' ); ?>">
				<a href="#search-container" class="screen-reader-text"><?php _e( 'Search', 'twentyfourteen' ); ?></a>
			</div>

			<nav id="primary-navigation" class="site-navigation primary-navigation" role="navigation">
				<h1 class="menu-toggle"><?php _e( 'Primary Menu', 'twentyfourteen' ); ?></h1>
				<a class="screen-reader-text skip-link" href="#content"><?php _e( 'Skip to content', 'twentyfourteen' ); ?></a>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
			</nav>
		</div>

		<div id="search-container" class="search-box-wrapper hide">
			<div class="search-box">
				<?php get_search_form(); ?>
			</div>
		</div>
	</header><!-- #masthead -->

	<div id="main" class="site-main">
