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
define('DB_NAME', 'appvcoin');

/** MySQL database username */
define('DB_USER', 'dbpublic');

/** MySQL database password */
define('DB_PASSWORD', 'wcZWhw3WenH78LYp');

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
define('AUTH_KEY', '&OhTBun.2n(O$]h,yH,d)Q|$m:IupHQHr<j1Rad#ah^<xOA] R_hbD[YEq*v|Xdk');
define('SECURE_AUTH_KEY', '*U=PI*p+(WI;lx<)*J@6@mEwL[kL%iixraJP|FF><Sc:,BJOatT|p)}X(LP}^6lx');
define('LOGGED_IN_KEY', '+%Y.Tjn%@lM&ENKqAATF$LO[eIu5(@A4 U1]_T=fPa,(I*J9-cS(KARlk+[I`+Ny');
define('NONCE_KEY', '*+G=C +56Zxh1J4d7FepqF_QfE4%N^*jw`S02>>7-#5C1!MJ#esH2jsXUS?rS5ht');
define('AUTH_SALT', 'r]!H~4}|<y:g{-]BYBv6Ixjd2>K)+jIQeeFs8-6y58G}E+:<Ss6i1w;}zcuVxQIV');
define('SECURE_AUTH_SALT', 'NqBA%t#${T >6XMrD&_BOln(Ppa##9Is_FtI?gMx)2eTAA;:oAdkZHoxSq)cVuIN');
define('LOGGED_IN_SALT', 'F2&wA(QH)1<By|RV&#WQF0L:vsdVBtO&lGpa%ef-tcCHWyxI[O|1WRh*VCJtM5D8');
define('NONCE_SALT', 'g/aK6m;sr4Jf.MY=zFp{@cXDdN)3&cs-lPyI6fwy!Rz@IJ4e+k.b<qr-g91eg2nP');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'vapp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/* That's all, stop editing! Happy blogging. */

define('FTP_HOST', '54.191.0.137');
define('FTP_USER', 'ftpuser');
define('FTP_PASS', '35832186');
define('FS_TIMEOUT', 900);
//define('FS_METHOD', 'direct');
define('FTP_SSL', false);

define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH'))
    define('ABSPATH', dirname(__FILE__) . '/');
define('FTP_BASE', '/var/www/html/app');
define('FTP_CONTENT_DIR', '/var/www/html/app/wp-content');
define('FTP_PLUGIN_DIR', '/var/www/html/app/wp-content/plugins');
/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
/** 3.9.1 core bug hot patch fix */
if (is_admin()) {
    add_filter('filesystem_method', create_function('$a', 'return "direct";'));
    define('FS_CHMOD_DIR', 0755);
}
