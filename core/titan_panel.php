<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年10月3日
 * Time: 下午12:36
 */


/**
 * This script is not used within Titan Framework itself.
 *
 * This script is meant to be used with your Titan Framework-dependent theme or plugin,
 * so that your theme/plugin can verify whether the framework is installed.
 *
 * If Titan is not installed, then the script will display a notice with a link to
 * Titan.
 *
 * To use this script, just copy it into your theme/plugin directory and do a
 * require_once( 'titan-framework-checker.php' );
 */
if (!class_exists('TitanFramework')) {
    if (!class_exists('TitanFrameworkThemeChecker')) {
        class TitanFrameworkThemeChecker
        {
            function __construct()
            {
                if (!is_admin()) {
                    add_action('init', array($this, 'displaySiteNotification'));
                } else {
                    add_filter('admin_notices', array($this, 'displayAdminNotification'));
                }
            }

            public function displaySiteNotification()
            {
                die(__("This theme requires the plugin Titan Framework. Please install it in the admin first before continuing.", "default"));
            }

            public function displayAdminNotification()
            {
                echo "<div class='error'><p><strong>"
                    . __("This theme requires the Titan Framework plugin.", "default")
                    . sprintf(" <a href='%s'>%s</a>",
                        admin_url("plugin-install.php?tab=search&type=term&s=titan+framework"),
                        __("Click here to search for the plugin.", "default"))
                    . "</strong></p></div>";
            }
        }

        new TitanFrameworkThemeChecker();
    }
    return;
} else {

    /*
     * Create our admin page
     */

    $titan = TitanFramework::getInstance('vcoinset');
    $adminPanel = $titan->createAdminPanel(array(
        'name' => __('V-COIN', HKM_LANGUAGE_PACK),
        'icon' => 'dashicons-chart-area'
    ));


    /*
     * Create our normal options tab
     */
    $tab = $adminPanel->createTab(array(
        'name' => __('Social Options', HKM_LANGUAGE_PACK),
    ));

    $tab->createOption(array(
        'name' => 'twitter english',
        'type' => 'text',
        'id' => 'twitter_en',
    ));

    $tab->createOption(
        array(
            'name' => 'twitter japanese',
            'type' => 'text',
            'id' => 'twitter_ja',

        ));

    $tab->createOption(array(
        'name' => 'twitter chinese',
        'type' => 'text',
        'id' => 'twitter_cn',

    ));

    $tab->createOption(array(
        'name' => 'twitter others',
        'type' => 'text',
        'id' => 'twitter_extra',

    ));
    $tab->createOption(
        array(
            'name' => 'facebook english',
            'type' => 'text',
            'id' => 'fb_en',
        ));
    $tab->createOption(
        array(
            'name' => 'facebook japanese',
            'type' => 'text',
            'id' => 'fb_ja',
        ));
    $tab->createOption(array(
        'name' => 'facebook chinese',
        'type' => 'text',
        'id' => 'fb_cn',
    ));
    $tab->createOption(array(
        'name' => 'facebook others',
        'type' => 'text',
        'id' => 'fb_extra',
    ));
    $tab->createOption(
        array(
            'name' => 'g+ english',
            'type' => 'text',
            'id' => 'gplus_en', 'desc' => 'demo .. '
        ));
    $tab->createOption(
        array(
            'name' => 'g+ japanese',
            'type' => 'text',
            'id' => 'gplus_ja', 'desc' => 'demo .. '
        ));
    $tab->createOption(array(
        'name' => 'g+ chinese',
        'type' => 'text',
        'id' => 'gplus_cn', 'desc' => 'demo .. '
    ));
    $tab->createOption(array(
        'name' => 'g+ others',
        'type' => 'text',
        'id' => 'gplus_extra',
        'desc' => 'demo .. '
    ));
    $tab->createOption(array(
        'type' => 'save'
    ));
    $tab = $adminPanel->createTab(array(
        'name' => 'Server EndPoint',
    ));
    $tab->createOption(array(
        'name' => 'vCoin server now',
        'type' => 'text',
        'id' => 'vcoin_service',
        'desc' => 'The API service domain'
    ));
    $tab->createOption(array(
        'name' => 'CMS server now',
        'type' => 'text',
        'id' => 'cms_service',
        'desc' => 'The API service domain'
    ));
    $tab->createOption(array(
        'name' => 'imusic vcoin account',
        'type' => 'text',
        'id' => 'imusic_uuid',
        'desc' => 'IMUSIC_UUID'
    ));
    $tab->createOption(array(
        'name' => 'imusic app key account',
        'type' => 'text',
        'id' => 'imusic_ak',
        'desc' => 'imusic app key'
    ));
    $tab->createOption(array(
        'name' => 'imusic app secret account',
        'type' => 'text',
        'id' => 'imusic_as',
        'desc' => 'imusic app secret'
    ));
    $tab->createOption(array(
        'name' => 'CERT_PATH certification path for SSL',
        'type' => 'text',
        'id' => 'cert_path',
        'desc' => 'CERT_PATH'
    ));

    $tab->createOption(array(
        'type' => 'save'
    ));

    $tab = $adminPanel->createTab(array(
        'name' => 'Misc.',
    ));

    $tab->createOption(array(
        'name' => 'uuid switch for app user',
        //control_panel_{{role name}}_{{option key to be applied}}
        'id' => 'control_panel_appuser_uuid_key',
        'type' => 'checkbox',
        'desc' => 'Expose uuid key field for user to edit',
        'default' => false,
    ));

    $tab->createOption(array(
        'name' => 'uuid switch for administrator',
        //control_panel_{{role name}}_{{option key to be applied}}
        'id' => 'control_panel_administrator_uuid_key',
        'type' => 'checkbox',
        'desc' => 'Expose uuid key field for user to edit',
        'default' => false,
    ));

    $tab->createOption(array(
        'name' => 'Logo Uploader',
        'id' => 'login_logo',
        'type' => 'upload',
        'desc' => 'Upload your image for the logo for login'
    ));

    $tab->createOption(array(
        'type' => 'save'
    ));
    $tab = $adminPanel->createTab(array(
        'name' => 'Redemption',
    ));
    $tab->createOption(array(
        'name' => 'Rewards note 1-4',
        'type' => 'heading',
    ));
    $tab->createOption(array(
        'name' => 'note template 1',
        //control_panel_{{role name}}_{{option key to be applied}}
        'id' => 'note_template_1_r',
        'type' => 'textarea',
        'desc' => 'This note template will allow the user to insert the supporting template tag as listed.',

    ));

    $tab->createOption(array(
        'name' => 'note template 2',
        //control_panel_{{role name}}_{{option key to be applied}}
        'id' => 'note_template_2_r',
        'type' => 'textarea',
        'desc' => 'This note template will allow the user to insert the supporting template tag as listed.',

    ));
    $tab->createOption(array(
        'name' => 'note template 3',
        //control_panel_{{role name}}_{{option key to be applied}}
        'id' => 'note_template_3_r',
        'type' => 'textarea',
        'desc' => 'This note template will allow the user to insert the supporting template tag as listed.',

    ));
    $tab->createOption(array(
        'name' => 'note template 4',
        //control_panel_{{role name}}_{{option key to be applied}}
        'id' => 'note_template_4_r',
        'type' => 'textarea',
        'desc' => 'This note template will allow the user to insert the supporting template tag as listed.',

    ));
    $tab->createOption(array(
        'name' => 'Coupon note 1-3',
        'type' => 'heading',
    ));
    $tab->createOption(array(
        'name' => 'note template 1',
        //control_panel_{{role name}}_{{option key to be applied}}
        'id' => 'note_template_1_c',
        'type' => 'textarea',
        'desc' => 'This note template will allow the user to insert the supporting template tag as listed.',

    ));
    $tab->createOption(array(
        'name' => 'note template 2',
        //control_panel_{{role name}}_{{option key to be applied}}
        'id' => 'note_template_2_c',
        'type' => 'textarea',
        'desc' => 'This note template will allow the user to insert the supporting template tag as listed.',

    ));
    $tab->createOption(array(
        'name' => 'note template 3',
        //control_panel_{{role name}}_{{option key to be applied}}
        'id' => 'note_template_3_c',
        'type' => 'textarea',
        'desc' => 'This note template will allow the user to insert the supporting template tag as listed.',

    ));
    $tab->createOption(array(
        'type' => 'save'
    ));
    /*******************************************************
     * TITAN FRAMEWORK CODE END
     *******************************************************/

}

