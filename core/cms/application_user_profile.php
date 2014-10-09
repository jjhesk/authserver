<?php
/**
 * Created by PhpStorm.
 * User: ryo
 * Date: 14年8月6日
 * Time: 下午12:42
 */

defined('ABSPATH') || exit;
if (!class_exists('application_user_profile')):
    class application_user_profile
    {
        protected $user_profile_render;
        protected $fields;
        protected $profile_config;

        /**
         * fields for profile settings.
         *
         * @param array $meta_list_input
         */
        function __construct($meta_list_input = array())
        {

            $this->profile_config = array(
                array(
                    "title" => "App User Profile Settings",
                    "role" => "appuser",
                    "editable_field" => array(
                        "company_name",
                        "sms_number",
                        "setting_push_sms",
                        "account_enable",
                        "uuid_key",
                    ),
                    "display_fields" => array(
                        "company_name" => "Company Name",
                        "account_enable" => "Account Activation",
                        "sms_number" => "SMS phone number",
                        "setting_push_sms" => "Switch Notification SMS",
                        "app_token" => "App Token",
                        "uuid_key" => "vcoin UUID",
                        "last_login_lastlogintime" => "Last Login",
                    )
                ),
                array(
                    "title" => "Developer Profile Settings",
                    "role" => "developer",
                    "editable_field" => array("company_name", "app_token", "app_coins"),
                    "display_fields" => array(
                        "company_name" => "Company Name",
                        "app_token" => "App Token",
                        "service_plan" => "Plan Level",
                        "app_coins" => "Available coins to assign",
                        "last_login_lastlogintime" => "Last Login",
                    )
                ),
            );
            $this->profile_config = wp_parse_args($meta_list_input, $this->profile_config);
            add_filter("UserFieldRender", array(__CLASS__, "app_user_filter"), 10, 3);
            add_action('show_user_profile', array(&$this, "add_profile_options"), 10, 1);
            add_action('edit_user_profile', array(&$this, "add_profile_options"), 10, 1);
            add_action('edit_user_profile_update', array(&$this, 'update'), 99, 1);
            add_action('personal_options_update', array(&$this, 'update'), 99, 1);
        }


        /**
         * @param $user_id
         */
        public function update($user_id)
        {
            $this->creation_profile(new WP_User($user_id));
            foreach ($this->fields as $field_id) {
                inno_log_db::log_vcoin_third_party_app_transaction($user_id, 10122, "line 333 can edit add field and val");
                user_profile_editor::withUpdateField($user_id, $field_id);
                if ($field_id == "account_enable") {
                    $this->enable_account($field_id);
                }
            }
        }

        private function enable_account($field_id)
        {
            global $current_user;
            if (isset($_POST[$field_id])) {
                $field_val = $_POST[$field_id];
                $uuid = get_user_meta($current_user->ID, "uuid_key", true);
                if ($uuid == "") throw new Exception("the current user does not have valid vcoin account, please go back and with the settings", 1079);
                api_cms_server::vcoin_account("enable", array(
                    "accountid" => $uuid,
                    "enable" => $field_val
                ));

               // inno_log_db::log_vcoin_third_party_app_transaction($current_user->ID, 12172, "uuid accountid change enable");
            }
        }

        /**
         *
         * @param WP_User $user
         */
        private function creation_profile(WP_User $user)
        {
            //user - previewing user object
            $this->user_profile_render = new user_profile_editor();
            foreach ($this->profile_config as $profile_setting) {
                if (in_array($profile_setting["role"], $user->roles)) {
                    $this->user_profile_render->init($user, $profile_setting["title"], array($profile_setting["role"]), $profile_setting["editable_field"]);
                    $this->user_profile_render->add_box($profile_setting["display_fields"], true);
                    break;
                }
            }
            $this->fields = $this->user_profile_render->get_fields();
        }

        public function add_profile_options($user_object)
        {
            $this->creation_profile($user_object);
            $this->user_profile_render->render_end();
        }

        public static function app_user_filter(user_profile_editor $editor, $key, $var)
        {
            switch ($key) {
                case "company_name":
                    return $editor->input_field($var, $key);
                    break;
                case "app_token":
                    return $editor->input_field($var, $key);
                    break;
                case "uuid_key":
                    try {
                        $option = $editor->get_panel_control($key);
                        inno_log_db::log_vcoin_third_party_app_transaction(-1, 777, print_r($option, true));
                        if ($option != 0) return $editor->input_field($var, $key);
                        else return $editor->input_field($var, $key, true);
                    } catch (Exception $e) {
                        if ($editor->can_view()) return $editor->input_field($var, $key); else return $var;
                    }
                    break;
                //temporarily using input_field for testing
                case "app_coins":
                    return $editor->input_field($var, $key);
                    break;
                case "service_plan":
                    return $editor->input_field($var, $key);
                    break;
                case "last_login_lastlogintime":
                    return $editor->apply_built_in_filter($var, $key);
                    break;
                case "sms_number":
                    return $editor->input_field($var, $key);
                    break;
                case "account_enable":
                    return $editor->input_switch($var, $key, "profile_button", "admin_profile");
                    break;
                case "setting_push_sms":
                    return $editor->input_switch($var, $key, "profile_button", "admin_profile");
                    break;
                default:
                    return "";
            }
        }
    }
endif;