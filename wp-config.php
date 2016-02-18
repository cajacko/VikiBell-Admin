<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //

/** MySQL database username */
define('DB_USER', 'vikibell');

/** MySQL database password */
define('DB_PASSWORD', 't7eWXgO0HfeO6dPs');

/** MySQL hostname */
define('DB_HOST', '52.49.247.254');

if('vikibell.com' === $_SERVER['HTTP_HOST']) {
	define('DB_NAME', 'vikibell');
} else {
	define('DB_NAME', 'vikibell_local');
}

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '~XXaN!^NeP~K3yY&e2 *][.k*<i-p|SoL}rB}1(cmF6R|BvR-.k[W3L N<$#pI_6');
define('SECURE_AUTH_KEY',  '? seoSTRlvS~aPwofFlG3$cxa+lILyY*]ras5b2iv/#s%Euv}:GtQdKyph{`ezT]');
define('LOGGED_IN_KEY',    '!fI+yILflvR)vA^sn[!~X8O|R70|dO!tX3!Y<`E^woD4+WkZ^hb1Nah*Gx#@^7E6');
define('NONCE_KEY',        '-F[{|F[84/Tgd~c`&;MPV`>&E>ixzqxb!*</m_Avu-}9eY{TW5]G4j+R@H &2eVE');
define('AUTH_SALT',        'q-oq+C8{ %!,3^Y-SjAX<P<nqml6+ibyRz ^v-8c^+n|wK0l);q>~f}:3|-c? {p');
define('SECURE_AUTH_SALT', 'Useo+bN-~/R|H^tK!#DuZ{S)aGWBu+&?]b#>sfg&Gc#5&ezgJm}z953|P$l*kk:6');
define('LOGGED_IN_SALT',   'iY|TTn<P.[k{-slKq)CK9jD+dvN>DE#.u,SVTklCW7oA-&&pkoOu4Q=_X.!M|a$V');
define('NONCE_SALT',       'FdP~D-P_-%R &cUUSGCO::hXg-/ch:K}YRWlF7-jAf&a^5rfcj(Y3%L~pEz?4bG2');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
