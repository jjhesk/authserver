<?php

/**
 * Created by HKM Corporation.
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
    //  TitanPanelSetup::setup();


    $adminPanel = $titan->createAdminPanel(array(
        'name' => __('V-COIN', HKM_LANGUAGE_PACK),
        'icon' => 'dashicons-chart-area'
    ));


    /*
     * Create our normal options tab
     */

    $tab = $adminPanel->createTab(array(
        'name' => __('User Profiles', HKM_LANGUAGE_PACK),
    ));

    $tab->createOption(array(
        'name' => 'Male Default',
        'type' => 'upload',
        'desc' => 'Upload your image for the logo for the default profile picture',
        'id' => 'default_male_profile_pic'
    ));

    $tab->createOption(array(
        'name' => 'Female Default',
        'type' => 'upload',
        'desc' => 'Upload your image for the default profile picture',
        'id' => 'default_female_profile_pic'
    ));

    $tab->createOption(array(
        'name' => 'Vcoin Free on Registration',
        //cp_{{role name}}_{{option key to be applied}}
        'id' => 'vcoin_registration',
        'type' => 'number',
        'max' => 1000,
        'min' => 10,
        'step' => 10,
        'desc' => 'This is given on the first time registration only.',
        'default' => false,
    ));

    $tab->createOption(array(
        'type' => 'save'
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
        'name' => 'vCoin Server Gateway',
        'type' => 'text',
        'id' => 'vcoin_service',
        'desc' => 'The API vcoin service domain'
    ));
    $tab->createOption(array(
        'name' => 'CMS Server Gateway',
        'type' => 'text',
        'id' => 'cms_service',
        'desc' => 'The API cms service domain'
    ));
    $tab->createOption(array(
        'name' => 'Push Server Gateway',
        'type' => 'text',
        'id' => 'push_service',
        'desc' => 'The API push service domain'
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
        'name' => 'Support Features',
    ));

    $tab->createOption(array(
        'name' => 'Allow developer to edit vcoin',
        'id' => 'cp_developer_app_coins',
        'type' => 'checkbox',
        'desc' => 'Enable the user with developer level to change the vcoin amount on the user profile',
        'default' => false,
    ));

    $tab->createOption(array(
        'name' => 'Allow app user to edit vcoin',
        'id' => 'cp_appuser_coin',
        'type' => 'checkbox',
        'desc' => 'Enable the user with application user level to change the vcoin amount on the user profile',
        'default' => false,
    ));

    $tab->createOption(array(
        'name' => 'uuid switch for app user',
        'id' => 'cp_appuser_uuid_key',
        'type' => 'checkbox',
        'desc' => 'Expose uuid key field for user to view',
        'default' => false,
    ));

    $tab->createOption(array(
        'name' => 'Enable push download app event for success notification',
        'id' => 'push_dl',
        'type' => 'checkbox',
        'desc' => 'ON: Enable this feature. OFF: disable.',
        'default' => false
    ));


    $tab->createOption(array(
        'name' => 'Message for push download',
        'id' => 'push_dl_message',
        'type' => 'text',
        'desc' => 'The text template for success download and open app message. {{coin}}',
        'default' => 'You have gained {{coin}}'
    ));

    $tab->createOption(array(
        'name' => 'Enable Email notification from administration approve and disapprove application registrations',
        'id' => 'admin_email_reg_nfn',
        'type' => 'checkbox',
        'desc' => 'ON: Enable this feature. OFF: disable.',
        'default' => false
    ));

    $tab->createOption(array(
        'name' => 'Beta Testing Feature',
        'id' => 'feature_beta',
        'type' => 'checkbox',
        'desc' => 'ON: This switch allow the activation of the beta testing when after the admin has once approved the pending application submission from the developer. OFF: Instead, the status will go directly to launched, no beta testing feature is available for the developer app.',
        'default' => true,
    ));

    $tab->createOption(array(
        'name' => 'Does not involve CMS server',
        'id' => 'cms_connect',
        'type' => 'checkbox',
        'desc' => 'ON: This server will be on stand alone mode and there will be communication to the CMS server. 1. Application registration will not list on the cms server. 2. This server will not support vcoinapp what-so-ever and no transactions from cms server to the vcoin server. 3. This server will not applications to be on launched but only to stay on Beta mode entirely. OFF: Everything will work as normal and there will be transactions involve with CMS server. Vcoinapp will be supported',
        'default' => false,
    ));

    $tab->createOption(array(
        'name' => 'Token expiry dates',
        'id' => 'token_exp_limit',
        'type' => 'select',
        'desc' => 'choose the limits for different periods for the expiration',
        'options' => array(
            '1209600' => '14 Days',
            '2419200' => '28 Days',
            '4838400' => '56 Days',
        ),
        'default' => '1209600'
    ));


    $tab->createOption(array(
        'type' => 'save'
    ));


    $tab = $adminPanel->createTab(array(
        'name' => 'Product and pricing',
    ));
    $tab->createOption(array(
        'name' => 'cost of coins for new developer account',
        //cp_{{role name}}_{{option key to be applied}}
        'id' => 'app_coin_new_cost',
        'type' => 'number',
        'max' => 500,
        'min' => 80,
        'step' => 1,
        'desc' => 'This is the price displayed in USD',
        'default' => false,
    ));
    $tab->createOption(array(
        'name' => 'cost of coins for new developer account',
        //cp_{{role name}}_{{option key to be applied}}
        'id' => 'app_coin_new_cost_hk',
        'type' => 'number',
        'max' => 5000,
        'min' => 500,
        'step' => 10,
        'desc' => 'This is the price displayed in HKD',
        'default' => false,
    ));
    $tab->createOption(array(
        'name' => 'coins for new developer account',
        //cp_{{role name}}_{{option key to be applied}}
        'id' => 'app_coin_new_dev',
        'type' => 'number',
        'max' => 20000,
        'min' => 5000,
        'step' => 100,
        'desc' => 'When first the developer account is created from the membershippro module',
        'default' => false,
    ));

    $tab->createOption(array(
        'name' => 'cost of refill developer account',
        //cp_{{role name}}_{{option key to be applied}}
        'id' => 'app_coin_refill_cost',
        'type' => 'number',
        'max' => 1000,
        'min' => 10,
        'step' => 2,
        'desc' => 'When first the developer account is created from the membershippro module',
        'default' => false,
    ));
    $tab->createOption(array(
        'name' => 'refill developer account',
        //cp_{{role name}}_{{option key to be applied}}
        'id' => 'app_coin_refill',
        'type' => 'number',
        'max' => 20000,
        'min' => 5000,
        'step' => 100,
        'desc' => 'When the payment is made the existing developer account with membershippro module',
        'default' => false,
    ));

    $tab->createOption(array(
        'name' => 'Beta Coin',
        'id' => 'app_coin_beta',
        'type' => 'number',
        'max' => 200,
        'min' => 0,
        'step' => 10,
        'desc' => 'The Beta Coin is the value of the coin to be given when first the pending application is approved as a beta app. The Beta Coin will be assigned to the beta app and the deposit amount is deducted by the Beta Coin amount. When the beta app is finally advanced to launched state, the rest of the amount in the deposit will be added to the beta app account and it will be launched app. This value will be only used when the Beta Feature is enabled. For detail please refer to the Support Features.',
        'default' => false,
    ));


    $tab->createOption(array(
        'type' => 'save'
    ));


    $tab = $adminPanel->createTab(array(
        'name' => 'Text Templates',
    ));
    $tab->createOption(array(
        'name' => 'Rewards note 1-4',
        'type' => 'heading',
    ));
    $tab->createOption(array(
        'name' => 'note template 1',
        //cp_{{role name}}_{{option key to be applied}}
        'id' => 'note_template_1_r',
        'type' => 'textarea',
        'desc' => 'This note template will allow the user to insert the supporting template tag as listed.',

    ));

    $tab->createOption(array(
        'name' => 'note template 2',
        //cp_{{role name}}_{{option key to be applied}}
        'id' => 'note_template_2_r',
        'type' => 'textarea',
        'desc' => 'This note template will allow the user to insert the supporting template tag as listed.',

    ));
    $tab->createOption(array(
        'name' => 'note template 3',
        //cp_{{role name}}_{{option key to be applied}}
        'id' => 'note_template_3_r',
        'type' => 'textarea',
        'desc' => 'This note template will allow the user to insert the supporting template tag as listed.',

    ));
    $tab->createOption(array(
        'name' => 'note template 4',
        //cp_{{role name}}_{{option key to be applied}}
        'id' => 'note_template_4_r',
        'type' => 'textarea',
        'desc' => 'This note template will allow the user to insert the supporting template tag as listed.',
    ));

    $tab->createOption(array(
        'name' => 'Reward Email Templates',
        'type' => 'heading',
    ));

    $tab->createOption(array(
        'name' => 'Redemption Email Template',
        'type' => 'textarea',
        'id' => 'email_reward_r',
        'desc' => 'The email template will be used for sending out the single email to person when the successful redemption of the product/gift/reward is triggered. There are a several keys can be called and they are listed as below:<br>
  {{username}} {{useremail}} {{choiceofaddress}} {{productname}} {{qr1}} {{qr2}} {{expirydate}} {{costofvcoin}}
  '
    ));

    $tab->createOption(array(
        'name' => 'Claim Email User Template',
        'type' => 'textarea',
        'id' => 'email_claim_r1',
        'desc' => 'The email template will be used for sending out the single email to person when the successful redemption of the product/gift/reward is triggered. There are a several keys can be called and they are listed as below:<br>
  {{username}} {{useremail}} {{choiceofaddress}} {{productname}} {{qr1}} {{qr2}} {{expirydate}} {{costofvcoin}}
  '
    ));

    $tab->createOption(array(
        'name' => 'Claim Email Vendor Template',
        'type' => 'textarea',
        'id' => 'email_claim_r2',
        'desc' => 'The email template will be used for sending out the single email to person when the successful redemption of the product/gift/reward is triggered. There are a several keys can be called and they are listed as below:<br>
  {{username}} {{useremail}} {{choiceofaddress}} {{productname}} {{qr1}} {{qr2}} {{expirydate}} {{costofvcoin}}
  '
    ));


    $tab->createOption(array(
        'name' => 'Coupon Email Templates',
        'type' => 'heading',
    ));


    $tab->createOption(array(
        'name' => 'Coupon - Default template',
        'type' => 'textarea',
        'id' => 'email_con_0',
        'desc' => 'The email template will be used for sending out the single email to person when the successful redemption of the product/gift/reward is triggered. There are a several keys can be called and they are listed as below:<br>
  {{username}} {{useremail}} {{choiceofaddress}} {{productname}} {{qr1}} {{qr2}} {{expirydate}} {{costofvcoin}}
  '
    ));

    $tab->createOption(array(
        'name' => 'Coupon - Lucky Draw template',
        'type' => 'textarea',
        'id' => 'email_con_1',
        'desc' => 'The email template will be used for sending out the single email to person when the successful redemption of the product/gift/reward is triggered. There are a several keys can be called and they are listed as below:<br>
  {{username}} {{useremail}} {{choiceofaddress}} {{productname}} {{qr1}} {{qr2}} {{expirydate}} {{costofvcoin}}
  '
    ));

    $tab->createOption(array(
        'name' => 'Coupon - Simple Web Redemption template',
        'type' => 'textarea',
        'id' => 'email_con_2',
        'desc' => 'The email template will be used for sending out the single email to person when the successful redemption of the product/gift/reward is triggered. There are a several keys can be called and they are listed as below:<br>
  {{username}} {{useremail}} {{choiceofaddress}} {{productname}} {{qr1}} {{qr2}} {{expirydate}} {{costofvcoin}}
  '
    ));

    $tab->createOption(array(
        'name' => 'Coupon - FIFO Web Redemption template',
        'type' => 'textarea',
        'id' => 'email_con_3',
        'desc' => 'The email template will be used for sending out the single email to person when the successful redemption of the product/gift/reward is triggered. There are a several keys can be called and they are listed as below:<br>
  {{username}} {{useremail}} {{choiceofaddress}} {{productname}} {{qr1}} {{qr2}} {{expirydate}} {{costofvcoin}}
  '
    ));


    $tab->createOption(array(
        'name' => 'Rejection App Registration Email Template',
        'type' => 'textarea',
        'id' => 'email_deny_app_reg',
        'desc' => 'This will be used for rejecting the application approval process:<br>
  {{username}} {{useremail}} {{reason}} {{reason_extra}} {{date}} {{app_title}} {{store_id}} {{platform}}
  '
    ));

    $tab->createOption(array(
        'name' => 'Reward Procedure Centralized',
        'type' => 'heading',
    ));
    $tab->createOption(array(
        'name' => 'template in zh',
        'id' => 'rcprocedure_zh',
        'type' => 'textarea',
        'desc' => 'Please use the template accordingly. {{user}}, {{amount}}, {{qr_a}}, {{qr_b}}, {{trace_id}}, {{handle}}, {{username}}',
    ));
    $tab->createOption(array(
        'name' => 'template en',
        'id' => 'rcprocedure_en',
        'type' => 'textarea',
        'desc' => 'Please use the template accordingly.  {{user}}, {{amount}}, {{qr_a}}, {{qr_b}}, {{trace_id}}, {{handle}}, {{username}}',
    ));
    $tab->createOption(array(
        'name' => 'template ja',
        'id' => 'rcprocedure_ja',
        'type' => 'textarea',
        'desc' => 'Please use the template accordingly.  {{user}}, {{amount}}, {{qr_a}}, {{qr_b}}, {{trace_id}}, {{handle}}, {{username}}',
    ));

    $tab->createOption(array(
        'name' => 'Reward Procedure Decentralized',
        'type' => 'heading',
    ));
    $tab->createOption(array(
        'name' => 'template in zh',
        'id' => 'rdprocedure_zh',
        'type' => 'textarea',
        'desc' => 'Please use the template accordingly. {{user}}, {{amount}}, {{qr_a}}, {{qr_b}}, {{trace_id}}, {{handle}}, {{username}}',
    ));
    $tab->createOption(array(
        'name' => 'template en',
        'id' => 'rdprocedure_en',
        'type' => 'textarea',
        'desc' => 'Please use the template accordingly.  {{user}}, {{amount}}, {{qr_a}}, {{qr_b}}, {{trace_id}}, {{handle}}, {{username}}',
    ));
    $tab->createOption(array(
        'name' => 'template ja',
        'id' => 'rdprocedure_ja',
        'type' => 'textarea',
        'desc' => 'Please use the template accordingly.  {{user}}, {{amount}}, {{qr_a}}, {{qr_b}}, {{trace_id}}, {{handle}}, {{username}}',
    ));


    $tab->createOption(array(
        'name' => 'Success Reward Submission Message Templates',
        'type' => 'heading',
    ));

    $tab->createOption(array(
        'name' => 'template in zh',
        'id' => 'success_reward_note_zh',
        'type' => 'textarea',
        'desc' => 'Please use the template accordingly. {{user}}, {{amount}}, {{qr_a}}, {{qr_b}}, {{trace_id}}, {{handle}}, {{username}}',
    ));
    $tab->createOption(array(
        'name' => 'template en',
        'id' => 'success_reward_note_en',
        'type' => 'textarea',
        'desc' => 'Please use the template accordingly.  {{user}}, {{amount}}, {{qr_a}}, {{qr_b}}, {{trace_id}}, {{handle}}, {{username}}',
    ));
    $tab->createOption(array(
        'name' => 'template ja',
        'id' => 'success_reward_note_ja',
        'type' => 'textarea',
        'desc' => 'Please use the template accordingly.  {{user}}, {{amount}}, {{qr_a}}, {{qr_b}}, {{trace_id}}, {{handle}}, {{username}}',
    ));

    $tab->createOption(array(
        'name' => 'Success Coupon Submission Message Templates',
        'type' => 'heading',
    ));

    $tab->createOption(array(
        'name' => 'template in zh',
        'id' => 'success_coupon_note_zh',
        'type' => 'textarea',
        'desc' => 'Please use the template accordingly. {{user}}, {{amount}}, {{qr_a}}, {{qr_b}}, {{trace_id}}, {{handle}}, {{username}}',
    ));
    $tab->createOption(array(
        'name' => 'template en',
        'id' => 'success_coupon_note_en',
        'type' => 'textarea',
        'desc' => 'Please use the template accordingly.  {{user}}, {{amount}}, {{qr_a}}, {{qr_b}}, {{trace_id}}, {{handle}}, {{username}}',
    ));
    $tab->createOption(array(
        'name' => 'template ja',
        'id' => 'success_coupon_note_ja',
        'type' => 'textarea',
        'desc' => 'Please use the template accordingly.  {{user}}, {{amount}}, {{qr_a}}, {{qr_b}}, {{trace_id}}, {{handle}}, {{username}}',
    ));

    $tab->createOption(array(
        'name' => 'Pick Up Reward Item Message Templates',
        'type' => 'heading',
    ));

    $tab->createOption(array(
        'name' => 'template in zh',
        'id' => 'pickup_reward_note_zh',
        'type' => 'textarea',
        'desc' => 'Please use the template accordingly. {{trace_id}}',
    ));
    $tab->createOption(array(
        'name' => 'template en',
        'id' => 'pickup_reward_note_en',
        'type' => 'textarea',
        'desc' => 'Please use the template accordingly. {{trace_id}}',
    ));
    $tab->createOption(array(
        'name' => 'template ja',
        'id' => 'pickup_reward_note_ja',
        'type' => 'textarea',
        'desc' => 'Please use the template accordingly. {{trace_id}}',
    ));
    $tab->createOption(array(
        'name' => 'Mission Alerts',
        'type' => 'heading',
    ));
    $tab->createOption(array(
        'name' => 'mission alert zh',
        //cp_{{role name}}_{{option key to be applied}}
        'id' => 'mission_alert_zh',
        'type' => 'textarea',
        'desc' => 'This note template will allow the user to insert the supporting template tag as listed.',
    ));
    $tab->createOption(array(
        'name' => 'mission alert en',
        //cp_{{role name}}_{{option key to be applied}}
        'id' => 'mission_alert_en',
        'type' => 'textarea',
        'desc' => 'This note template will allow the user to insert the supporting template tag as listed.',
    ));
    $tab->createOption(array(
        'name' => 'mission alert ja',
        //cp_{{role name}}_{{option key to be applied}}
        'id' => 'mission_alert_ja',
        'type' => 'textarea',
        'desc' => 'This note template will allow the user to insert the supporting template tag as listed.',
    ));

    $tab->createOption(array(
        'name' => 'Push Notifications',
        'type' => 'heading',
    ));

    $tab->createOption(array(
        'name' => 'gain coin zh',
        'id' => 'gain_coin_zh',
        'type' => 'text',
        'desc' => '{{amount}} {{app_name}} {{trace_id}}.'
    ));
    $tab->createOption(array(
        'name' => 'gain coin en',

        'id' => 'gain_coin_en',
        'type' => 'text',
        'desc' => '{{amount}} {{app_name}} {{trace_id}}.'
    ));
    $tab->createOption(array(
        'name' => 'gain coin ja',
        'id' => 'gain_coin_ja',
        'type' => 'text',
        'desc' => '{{amount}} {{app_name}} {{trace_id}}.'
    ));


    $tab->createOption(array(
        'name' => 'SMS Notifications',
        'type' => 'heading',
    ));


    $tab->createOption(array(
        'name' => 'sms to vendor zh',
        'id' => 'sms_to_vendor_zh',
        'type' => 'text',
        'desc' => '{{product_name}} {{vendor_name}} {{trace_id}}.'
    ));
    $tab->createOption(array(
        'name' => 'sms to vendor en',
        'id' => 'sms_to_vendor_en',
        'type' => 'text',
        'desc' => '{{product_name}} {{vendor_name}} {{trace_id}}.'
    ));
    $tab->createOption(array(
        'name' => 'sms to vendor ja',
        'id' => 'sms_to_vendor_ja',
        'type' => 'text',
        'desc' => '{{product_name}} {{vendor_name}} {{trace_id}}.'
    ));


    //你已在達到 之中 的獎賞
    $tab->createOption(array(
        'type' => 'save'
    ));
    /*
        $tab = $adminPanel->createTab(array(
            'name' => 'Gravity Form Control',
        ));
        $tab->createOption(array(
            'name' => 'E-coupon registration',
            'type' => 'heading',
        ));
        $tab->createOption(array(
            'name' => 'form ID',
            'id' => 'gf_ecoupon_app',
            'type' => 'number',
            'max' => 20,
            'min' => 0,
            'step' => 1,
            'desc' => 'The GF form id for this application.',

        ));
        $tab->createOption(array(
            'type' => 'save'
        ));*/

    /*******************************************************
     * TITAN FRAMEWORK CODE END
     *******************************************************/

}

