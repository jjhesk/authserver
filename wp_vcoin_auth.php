<?php
/*
  Plugin Name: VCOIN Authentication Server 2014
  Plugin URI: https://github.com/jjhesk/authserver
  Description: In order to run this module the server will need to activate the mentioned modules as list below: Titan Framework, WordPress Importer, Meta Box, JSON API, JSON API Auth, Email Login, Gravity Forms
  Version: 1.01
  Author: Heskeyo Kam, Ryo
  Author URI:
  License: GPLv3
 */
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
define('AUTH_SERVER_PATH', dirname(__FILE__));
define('AUTH_SERVER_URI', plugins_url("wp_vcoin_auth_server"));

//this will be used in the curl peer server request
define("CERT_PATH", "/etc/pki/tls/cert.pem");
//this is the vcoin server domain for staging without cert SSL connection

define("HKM_LANGUAGE_PACK", "vcoin_context");
define("HKM_LIBJS", AUTH_SERVER_URI . "/js/");
define("LIBJS_ADMIN_MODEL", AUTH_SERVER_URI . "/js/adminmodel/");
define("LIBJS_ADMIN", AUTH_SERVER_URI . "/js/admin/");
define("HKM_LIBCSS", AUTH_SERVER_URI . "/css/");
define("HKM_LIBFONTS", AUTH_SERVER_URI . "/fonts/");
define("HKM_IMG_PATH", AUTH_SERVER_URI . "/images/");
define("HKM_ART_PATH", AUTH_SERVER_URI . "/hkm/art/");
define('EXIMAGE', AUTH_SERVER_URI . '/extensions/art/');
define('INNO_IMAGE_DIR', AUTH_SERVER_URI . '/images/');
define("EXTENSIONS_PATH", AUTH_SERVER_PATH . DIRECTORY_SEPARATOR . 'thirdparty' . DIRECTORY_SEPARATOR);
define("CORE_PATH", AUTH_SERVER_PATH . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR);
define("JSONAPI_PATH", CORE_PATH . "api/");
require_once EXTENSIONS_PATH . 'Mustache/Autoloader.php';

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
    foreach (glob(AUTH_SERVER_PATH . "/" . $folder . "/*.php") as $filename) {
        //  echo $filename . "\n";
        require_once $filename;
    }
}

define("GF_FORM_USER_REG", 1);
define("gf_user_registration_token", 6);
define("gf_field_email_token", 6);
define("gf_field_email", 3);
define("gf_field_login_name", 2);
define("gf_field_company", 1);
define("gf_field_password", 4);
define("gf_field_role", 8);
global $system_script_manager;
function child_create_objects()
{
    global $system_script_manager;
    TitanPanelSetup::setup();
    install_db::reg_hook(__FILE__);
    $system_script_manager = new system_frontend();
    $m1 = new connect_json_api();
    $m2 = new GF_notification();
    $m3 = new gfUserRegistration();
    $m4 = new app_transaction_history();
    $m5 = new application_user_profile();
    $m6 = new tokenBase();
    $m7 = new system_log_display();
    $m8 = new app_check_point();
    $m9 = new app_registration();
    $m12 = new app_user_claim();
    $m10 = new dashboard();
    $m11 = new PaymentMembershipSupport();
    $m13 = new EmailTrigger();
    userRegister::user_reg();
    //add_filter("email_activation_label", "email_activation_custom_label", 10, 1);
    $m1 = $m2 = $m13 = $m3 = $m4 = $m5 = $m6 = $m7 = $m8 = $m9 = $m10 = $m11 = $m12 = $system_script_manager = NULL;
    gc_collect_cycles();
}

add_action('wp_loaded', 'child_create_objects', 11);
$destinations = NULL;
?>