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
define('DB_NAME', 'calamara-06-2012');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         '4*J7YUbL{AO2AM~P-J90{d]Mb-~krK#PL-Hp%1!]s_Itv~IXTh3_/(5h5xyf_kH ');
define('SECURE_AUTH_KEY',  'X9r7l 2Bw#E3;tUmPsr=Z$ZC?&LP$AHz2U!.H$8Ov|M!EJPL >22rRY<><LE90@B');
define('LOGGED_IN_KEY',    'eW9Q}e&nC9aUKc7qW9O<>?J01G1Z(+eU+&-#9qeasne/nIIl]s?WBEfBzCBF&@Yt');
define('NONCE_KEY',        '-vG=ZF;@PE3Qgtgt}H^$| U*oF9}s-pi[$L:%^rtC.)Lz::8-$*`WP+lRe6% +p/');
define('AUTH_SALT',        'Cd4eVI]=)z~$bL$VT-poFsr(5<L|*MzyGQ<Qr%%xS|V4*19;-a.^hZ|r[~Zp3=*B');
define('SECURE_AUTH_SALT', 'xhX=-|(-+FUpCJ<6*5]WBtzpD7Roc~3y5JuZhGsrm]l)BR$Lt#4eDa)m;-jr- -L');
define('LOGGED_IN_SALT',   '*BhcV_r~C%;M=5BDQn9&(se+v9eP|9R)bPWyKc~-cq%<^2x-V%v|}V^%=$;o4t.#');
define('NONCE_SALT',       '$(Wa1(-ab5H+Gs z(y]LVny9@e#O~&es9S]CE(ji[G6P5w{v;uE_]`ynQrC1Zz.,');

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

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
