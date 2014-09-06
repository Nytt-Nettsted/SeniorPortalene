<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'pensjmhc_wp_multi');

/** MySQL database username */
define('DB_USER', 'pensjmhc_nn');

/** MySQL database password */
define('DB_PASSWORD', 'Q%l1oD@z}qn@');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', 'utf8_danish_ci');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'cfRU{Bp )@OP@=yfnCm}vZrIy0wJequ#-%C|^?0M/_{|$a_.+k(MI5-dRWUx~sWO');
define('SECURE_AUTH_KEY',  '+U},[@-BxZevb5BP^>!F|><)-h@-DnLbTAc@MCw<~g<+Ak[K<Yd[=t6}G:!4>~sN');
define('LOGGED_IN_KEY',    'I05:aGd+vKl-h{4#OKa+kUx=]Xma+{]htX*O{ZxVv-7~wWX%M~sY0]L!x<H$ P,y');
define('NONCE_KEY',        '&S4Q3Ct:++WKsd8Fm#-B+vvyrCvnHt9[xg$4d;ePp,QK%Y| d[Kkm$o9@}/*Q #]');
define('AUTH_SALT',        'Xw9!`wU1*-wMM<_*XnB8K$X?[>Dd; i4p|h+W@Fy,$ZA5S2?Cx;{<f*?)|pg`x-e');
define('SECURE_AUTH_SALT', ' 9)k9*+S=x+$k0o<<kHG68}87WP7):<OP]@PYI85k70lo_,O@2#1A8DeHQ[p&FO]');
define('LOGGED_IN_SALT',   '@Ai5&KcfH/8Klo<5:xQ[r-IOCKeCoR5O}#(%6v+K1P`+&1]=>hvJN<+fl|Xj|RE{');
define('NONCE_SALT',       '&/11/rYu4KB}Ul#@wX1fL<2{jNB,`g3]xjBIXpE9{z)D2u~i}<s>YDiJ.J[GHb}/');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'pp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define ('WPLANG', 'nb_NO');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */

define('WPCACHEHOME', '/home/pensjmhc/public_html/wp-content/plugins/wp-super-cache/'); //Added by WP-Cache Manager
define('WP_DEBUG', false);
if ( WP_DEBUG ) {
	define('JETPACK_DEV_DEBUG', true);
	define('SAVEQUERIES', true);
	define('CONCATENATE_SCRIPTS', false);
	define('SCRIPT_DEBUG', true);
	define('WP_DEBUG_LOG', true);
	define('WP_DEBUG_DISPLAY', false);
	@ini_set('display_errors', 0);
} else {
	@ini_set('error_log', '/home/pensjmhc/php-errors.log');
	define('WP_POST_REVISIONS', 10);
	define('DISALLOW_FILE_EDIT', true);
	define('WP_CACHE', true); //Added by WP-Cache Manager
	setlocale(LC_TIME, WPLANG.'.utf8',WPLANG,'Norway','nor','no','nb');
}

/**
 * Multisite
 */
define('WP_ALLOW_MULTISITE', true);
if ( WP_ALLOW_MULTISITE ) {
	define('MULTISITE', true);
	if ( MULTISITE ) {
		define('SUBDOMAIN_INSTALL', true);
		define('DOMAIN_CURRENT_SITE', 'seniorportalene.no');
		define('PATH_CURRENT_SITE', '/');
		define('SITE_ID_CURRENT_SITE', 1);
		define('BLOG_ID_CURRENT_SITE', 1);
		define('SUNRISE', 'on');
	}
}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
