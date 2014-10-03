<?php
//will be used in the curl request domain api
define("DOMAIN_API", "http://devlogin.vcoinapp.com/api/");
//will be used in the curl request domain
define("CMS_SERVER", "http://devcms.vcoinapp.com");
//this will be used in the curl peer server request
define("CERT_PATH", "/etc/pki/tls/cert.pem");
//this is the vcoin server domain for staging
define("VCOIN_SERVER", "https://54.186.64.145:8057");
//this is the vcoin server domain for staging without cert SSL connection
define("VCOIN_SERVER_NONSECURED", "http://54.186.64.145:8056");
//this is the imusictec vcoin account ID
define("IMUSIC_UUID", "13EFFA66-052D-E411-8F85-3085A9B355FC");
//this is the imusictec vcoin app key
define("KEY_VCOINAPP", "yoqzLezk");


define("HKM_LANGUAGE_PACK", "hkm_app_vcoin");
define("HKM_LIBJS", get_stylesheet_directory_uri() . "/js/");
define("LIBJS_ADMIN_MODEL", get_stylesheet_directory_uri() . "/js/adminmodel/");
define("LIBJS_ADMIN", get_stylesheet_directory_uri() . "/js/admin/");
define("HKM_LIBCSS", get_stylesheet_directory_uri() . "/css/");
define("HKM_LIBFONTS", get_stylesheet_directory_uri() . "/fonts/");
define("HKM_IMG_PATH", get_stylesheet_directory_uri() . "/images/");
define("HKM_ART_PATH", get_stylesheet_directory_uri() . "/hkm/art/");
define('EXIMAGE', get_stylesheet_directory_uri() . '/extensions/art/');
define('INNO_IMAGE_DIR', get_stylesheet_directory_uri() . '/images/');
define("EXTENSIONS_PATH", get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'thirdparty' . DIRECTORY_SEPARATOR);
define("CORE_PATH", get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR);
define("JSONAPI_PATH", CORE_PATH . "api/");
require_once EXTENSIONS_PATH . 'Mustache/Autoloader.php';
/*
require_once(EXTENSIONS_PATH . 'facebook/sdk/facebook.php');
require_once(EXTENSIONS_PATH . 'h2o/h2o.php');
//require_once(EXTENSIONS_PATH . 'jsonapi/innojson.php');
/*require_once(EXTENSIONS_PATH . 'metabox/init_metabox.php');
require_once(EXTENSIONS_PATH . 'sendgrid-google-php/SendGrid_loader.php');
require_once(EXTENSIONS_PATH . 'front_end_scripts.php');
require_once(EXTENSIONS_PATH . 'helpers.php');
require_once(EXTENSIONS_PATH . 'plugins-compat.php');
require_once(EXTENSIONS_PATH . 'video-functions.php');
require_once(EXTENSIONS_PATH . 'dp-render-query.php');
require_once(EXTENSIONS_PATH . 'dp-post-likes.php');
require_once(EXTENSIONS_PATH . 'dp-jplayer.php');
*/
define("INN_VIEW_VIDEO_ACTION", 1001);
define("INN_VIEW_OFFICIAL_VIDEO_ACTION", 1010);
define("INN_INVITE_FRIEND_ACTION", 1020);
define("INN_CAMERA_ROLL_ACTION", 1030);

define("INN_MISSION_RYAN_ACTION", 2000);
define("INN_MISSION_ADD_ACTION", 2001);
define("INN_MISSION_REMOVE_ACTION", 2002);

/**
 * DEFINED THE POST TYPE HERE
 */
define("HKM_ACTION", "act");
define("APPTOKENMGM", "appcfg");
//define("INNGIFTS", "innocator_products");
//define("HKM_COUPON", "icoupon");
//define("HKM_EVENT", "hkmevent");
//settings
//define("HKM_LOCATION", "innoactor_loc");
//define("HKM_BLOG", "blog");
define("LOC_STOCK_COUNT_MAX", 10);
/**
 * production vcoin server domain with the slash
 */
/*// production ..
if ($_SERVER['SERVER_NAME'] == 'www.innoactor.com') {
    define("VCOIN_SERVER", "https://testvcoin.innoactor.com/");
    //settings
    define("SERVER_TYPE_INNO", "production");
}
// testing..
if ($_SERVER['SERVER_NAME'] == 'devwp.innoactor.com') {
    define("VCOIN_SERVER", "https://devvcoin.innoactor.com/");
    define("SERVER_TYPE_INNO", "development");
}

if ($_SERVER['SERVER_NAME'] == 'awswp.innoactor.com') {
    define("VCOIN_SERVER", "https://devvcoin.innoactor.com/");
    //settings
    define("SERVER_TYPE_INNO", "production");
}*/
//if ($_SERVER["SERVER_NAME"] == "localhost") {
// Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
// Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYHOST] = 2;
//}

//in response from autoloading Mustache module.
Mustache_Autoloader::register();

/**
 * load all the modules
 */
$destinations = array(
    'core', 'core/reuseable', 'core/shortcodes',
    'core/logics', 'core/cms', 'core/gflogics',
    'core/api');
foreach ($destinations as $folder) {
    foreach (glob(get_stylesheet_directory() . "/" . $folder . "/*.php") as $filename) {
        //  echo $filename . "\n";
        require_once $filename;
    }
}
unset($destinations);
define("GF_FORM_USER_REG", 1);
define("gf_user_registration_token", 6);
define("gf_field_email_token", 6);
define("gf_field_email", 3);
define("gf_field_login_name", 2);
define("gf_field_company", 1);
define("gf_field_password", 4);
define("gf_field_role", 8);
global $system_script_manager, $app_merchan;
function child_create_objects()
{
    global $system_script_manager;
    $system_script_manager = new system_frontend();
    new connect_json_api();
    new GF_notification();
    new gfUserRegistration();
    new application_user_profile();
    new tokenBase();
    new system_log_display();
    new app_check_point();
    new app_registration();
    new dashboard();
    userRegister::user_reg();
    //add_filter("email_activation_label", "email_activation_custom_label", 10, 1);
}

add_action('wp_loaded', 'child_create_objects', 11);

?>