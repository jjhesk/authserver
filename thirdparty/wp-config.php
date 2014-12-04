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
//define('WP_MAX_MEMORY_LIMIT', '256MB');

//define('WP_CACHE', true);

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'vapp_';
/*
define( 'CUSTOM_USER_TABLE', $table_prefix.'my_users' );
define( 'CUSTOM_USER_META_TABLE', $table_prefix.'my_usermeta' )
 */
/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/*define( 'WPLANG', 'de_DE' );
define( 'WP_LANG_DIR', dirname(__FILE__) . 'wordpress/languages' );*/
/* That's all, stop editing! Happy blogging. */

define('FTP_HOST', '54.191.0.137');
//define('FTP_HOST', 'localhost');
define('FTP_USER', 'ftpuser');
define('FTP_PASS', '35832186');
define('FS_TIMEOUT', 900);
//define('FS_METHOD', 'direct');
define('FTP_SSL', false);

define('WP_DEBUG', true);
define('WP_MEMORY_LIMIT', '1000M');
/* That's all, stop editing! Happy blogging.


$WP_DIR = substr_replace(WP_CONTENT_DIR, '', -10);
if (!defined('WP_ADMIN_DIR')) define('WP_ADMIN_DIR', $WP_DIR . 'backend/');
*/
/**
 *
 *
 */

/*

#CUSTOM ADMIN URL REWRITE FOR HTACCESS
#<IfModule mod_rewrite.c>
#RewriteEngine On
#RewriteBase /
#RewriteRule ^platform[^/]*$ platform/ [R=301,L]
#RewriteCond %{QUERY_STRING} (.*)$
#RewriteRule ^platform(.*)$ wp-admin$1? [QSA,L,NE]
#RewriteCond %{QUERY_STRING} (.*)$
#RewriteRule ^wp-admin/?$ / [NE,R=404,L]
#RewriteCond %{QUERY_STRING} (.*)$
#RewriteRule ^wp-admin/(.*)$ platform/$1 [QSA,R=301,L,NE]
#</IfModule>
#CUSTOM ADMIN URL REWRITE


function my_custom_admin_url($path) {
    return str_replace('wp-admin', 'platform', $path);
}
add_filter('admin_url', 'my_custom_admin_url');
*/
/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH'))
    define('ABSPATH', dirname(__FILE__) . '/');


define('FTP_BASE', '/var/www/html/app/');
define('FTP_CONTENT_DIR', '/var/www/html/app/wp-content/');
define('FTP_PLUGIN_DIR', '/var/www/html/app/wp-content/plugins/');

/**
 * Change Admin URL
 *
 * Copyright (C) 2010  hakre <http://hakre.wordpress.com/>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * USAGE:
 *
 * Copy the file into  wp-content/mu-plugins  directory and add the
 * following RewriteRule to your apache configuration or .htaccess:
 *
 *  RewriteRule ^admin/(.*)$ wp-admin/$1 [QSA,L]
 *
 * It will rewrite the wordpress admin-URL
 *
 *   from: http://example.com/wp-admin/ ...
 *   to  : http://example.com/admin/ ...
 *
 * @author hakre <http://hakre.wordpress.com>
 * @see http://wordpress.stackexchange.com/questions/4037/how-to-redirect-rewrite-all-wp-login-requests/4063
 * @todo mod_rewrite_rules - filter to insert into .htacces on plugin activation
 */
/*
return ChangeAdminUrlPlugin::bootstrap();

class ChangeAdminUrlPlugin
{
    private $renameFrom = 'wp-admin';
    private $renameTo = 'admin';
    static $instance;

    static public function bootstrap()
    {
        null === self::$instance && self::$instance = new self();
        return self::$instance;
    }

    private function setCookiePath()
    {
        defined('SITECOOKIEPATH') || define('SITECOOKIEPATH', preg_replace('|https?://[^/]+|i', '', get_option('siteurl') . '/'));
        defined('ADMIN_COOKIE_PATH') || define('ADMIN_COOKIE_PATH', SITECOOKIEPATH . $this->renameTo);
    }

    public function __construct()
    {
        $this->setCookiePath();
        add_action('init', array($this, 'init'));
    }

    public function init()
    {
        add_filter('admin_url', array($this, 'admin_url'), 10, 3);
    }

    public function admin_url($url, $path, $blog_id)
    {
        $renameFrom = $this->renameFrom;
        $renameTo = $this->renameTo;
        $scheme = 'admin';
        $find = get_site_url($blog_id, $renameFrom . '/', $scheme);
        $replace = get_site_url($blog_id, $renameTo . '/', $scheme);
        (0 === strpos($url, $find))
        && $url = $replace . substr($url, strlen($find));
        return $url;
    }
}*/

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
/** 3.9.1 core bug hot patch fix
if (is_admin()) {
    add_filter('filesystem_method', create_function('$a', 'return "direct";'));
    define('FS_CHMOD_DIR', 0755);
}
*/