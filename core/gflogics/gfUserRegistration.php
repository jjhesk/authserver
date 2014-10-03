<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年7月31日
 * Time: 上午11:47
 */
defined('ABSPATH') || exit;
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
if (!class_exists('gfUserRegistration')) {
    class gfUserRegistration extends gformBase
    {

        public function __construct()
        {
            add_action('gform_pre_submission_' . GF_FORM_USER_REG, array(__CLASS__, 'userRegistrationInit'), 10, 1);
            //add_filter('gform_pre_submission_filter_'.GF_FORM_USER_REG, array(__CLASS__, "userRegistrationInit"), 10, 1);
            add_action("gform_enqueue_scripts_" . GF_FORM_USER_REG, array(__CLASS__, 'enqueue_custom_script'), 10, 2);
            // add_filter("gform_field_validation_" . GF_FORM_USER_REG, array(__CLASS__, 'valid_fields'), 10, 4);
            add_filter("gform_validation", array(__CLASS__, "check_form"));

        }

        public static function check_form($validation_result)
        {
            $result = false;
            $form = $validation_result["form"];
            $formID = $form['id'];
            $current_page = rgpost('gform_source_page_number_' . $formID) ? rgpost('gform_source_page_number_' . $formID) : 1;
            if ($formID == GF_FORM_USER_REG) {
                $result = gfchecking::check_field_email_only($form['fields'], gf_field_email);
            }

            if ($result) {
                $validation_result["form"]["fields"][$result["watch_order"]]["failed_validation"] = true;
                $validation_result["form"]["fields"][$result["watch_order"]]["validation_message"] = $result["message"];
                $validation_result["is_valid"] = false;
            }
            return $validation_result;
        }

        public static function enqueue_custom_script($form, $is_ajax)
        {
            if ($is_ajax) {
                wp_enqueue_script("gflogins");
            }
        }

        public static function isEmailVerified($confirmation_token, $entry_id)
        {
            $token = self::gf_get_entry_value(GF_FORM_USER_REG, $entry_id, gf_user_registration_token);
            return $token == $confirmation_token;
        }

        public static function userRegistrationInit($form)
        {

            /*
            $gf_field_email_token = parent::getPostVal(gf_field_email_token);
            $gf_field_password = parent::getPostVal(gf_field_password);
            $gf_field_email = parent::getPostVal(gf_field_email);
            $gf_field_login_name = parent::getPostVal(gf_field_login_name);
            $gf_field_company = parent::getPostVal(gf_field_company);*/
            //parent::filter_form_value($form, gf_field_email_token, parent::gen_order_num());
            parent::set_input_value(gf_field_email_token, parent::gen_order_num());
        }
    }
}