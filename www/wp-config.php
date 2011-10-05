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
define('DB_NAME', 'remodelapple_cms');

/** MySQL database username */
define('DB_USER', 'remodelapple');

/** MySQL database password */
define('DB_PASSWORD', 'o6mBKxS3PklL21ysOEY9nqZ5n5oo17Km');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'pX^9M{Ehl*|eDH,92mvasI7e+:zfulSPW_T;nCQX_;*#cM,PD0R!s2.*?)6)bD$9');
define('SECURE_AUTH_KEY',  'ukqbEKP%`aRWk0`vi0?Je<%hEn2<};^](tG^+@sdK[bAD>bb7MBkDS[|h0>^8o54');
define('LOGGED_IN_KEY',    'lst4lx@VGkqmPHA;9]?ZtDI;Fa<F$^cu0{sXs*.HY-p`Ni`2Ar(0Z!H%nC1^8CG@');
define('NONCE_KEY',        '_fIS-VNr&+T/4r`4W,MJ88&s[Z7.=j@uBHz1|6ow^)yiDZhoXLa0]|{3r^PUzkU%');
define('AUTH_SALT',        ' qRafYyYL G/5NQa6:X$&(/<*`SNL>/I4O1sSuh10j:bZGnD8~](VBZ?%HfrT.}G');
define('SECURE_AUTH_SALT', '6[ZW)TiS%$4_JT;F`@*@x| pmC)Nj;7OMN7>g~R{u9 z^4fDv<-zEzt1/MlU9w4<');
define('LOGGED_IN_SALT',   'xtc/Lz:9fIsQQ(5jAm}~XC6BTOF&gcZ*_SF@t@]DD9rzIO=*WKz$@T!e3a8ri~Rp');
define('NONCE_SALT',       ',WM8]|H~Mijyj3>DS0h*a8f0{xrj5D^z,3g+3{Oh^J,nH!]+}j~y3m,vXz7Sdo1I');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

// Determine whether we're working on a local server
// or on the real server:
if (get_cfg_var('IS_LIVE') == 1) {
  define('IS_LIVE', true);
} else {
  define('IS_LIVE', false);
}
// Determine location of files and the URL of the site:
// Allow for development on different servers.

@ini_set('display_errors',0);
if(!IS_LIVE){
  define('WP_DEBUG',         true);  // Turn debugging ON
  define('WP_DEBUG_DISPLAY', false); // Turn forced display OFF
  define('WP_DEBUG_LOG',     true);  // Turn logging to wp-content/debug.log ON
}

// This will create a debug.log file in the wp-content folder
if(!function_exists('_log')){
  function _log( $message ) {
	if( WP_DEBUG === true ){
	  if( is_array( $message ) || is_object( $message ) ){
		error_log( print_r( $message, true ) );
	  } else {
		error_log( $message );
	  }
	}
  }
}

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');


/** Override default file permissions */
if(is_admin()) {
add_filter('filesystem_method', create_function('$a', 'return "direct";' ));
define( 'FS_CHMOD_DIR', 0751 );
}