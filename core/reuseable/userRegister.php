<?php

/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年8月6日
 * Time: 上午11:59
 */

defined('ABSPATH') || exit;

if (!class_exists('userRegister')) {
    class userRegister
    {
        private $reg_user, $vcoin_transaction, $titan;

        public function __construct()
        {

        }

        function __destruct()
        {
            $this->reg_user = NULL;
            $this->vcoin_transaction = NULL;
            gc_collect_cycles();
        }

        public static function user_reg()
        {
            add_action("user_register", array(__CLASS__, "new_user_reg"), 11, 1);
            add_action("delete_user", array(__CLASS__, "my_delete_user"), 11, 1);
        }

        public static function my_delete_user($user_id)
        {
            global $wpdb;
            $user_obj = get_userdata($user_id);
            $email = $user_obj->user_email;
            $headers = 'From: ' . get_bloginfo("name") . ' <' . get_bloginfo("admin_email") . '>' . "\r\n";
            wp_mail($email, 'You are being deleted, brah', 'Your account at ' . get_bloginfo("name") . ' is being deleted right now.', $headers);
            api_cms_server::enable_account($user_id, false);
        }

        /**
         * register action callback in here
         * @param $user_id
         * @internal param $ID
         */
        public static function new_user_reg($user_id)
        {
            $user = new WP_User($user_id);
            if (is_array($user->roles)) {
                if ($user->roles[0] == "appuser" || $user->roles == "appuser") {
                    if (get_user_meta($user_id, "uuid_key", true) == "") {
                        $re = new userRegister();
                        $re->admin_create_vcoin_key_user($user);
                    }
                }
            }
            $user = NULL;
        }

        /**
         * totally independent function to create new user from the admin panel
         * @param WP_User $user
         */
        public function admin_create_vcoin_key_user(WP_User $user)
        {
            $this->reg_user = $user;
            $this->create_vcoin_key_for_user();
        }

        /**
         * create a vcoin key for the user
         * and create an user for the application
         */
        private function create_vcoin_key_for_user()
        {
            $this->titan = TitanFramework::getInstance('vcoinsetting');
            $this->vcoin_transaction = new vcoinBase();
            $data = get_userdata($this->reg_user->ID);
            $bind_id = $this->vcoin_transaction->create_new_user($data->user_email);
            update_user_meta($this->reg_user->ID, "uuid_key", $bind_id);
            $free_v_coin = (int)$this->titan->getOption("vcoin_registration");
            if ($free_v_coin > 0) {
                $this->vcoin_transaction = new vcoinBase();
                $this->vcoin_transaction->setReceive($bind_id);
                $this->vcoin_transaction->setSender(IMUSIC_UUID);
                $this->vcoin_transaction->setAmount($free_v_coin);
                $this->vcoin_transaction->setTransactionReference("free coin");
                $this->vcoin_transaction->CommitTransaction();
            }
            inno_log_db::log_vcoin_email(-1, 901201,
                "new free coin from opening account. added (v)" . $free_v_coin . " to the account user " . $this->reg_user->ID .
                " with trace_id" . $this->vcoin_transaction->get_tranaction_reference() . " and the uuid key is " . $bind_id
            );

        }

        /**
         * @param $login_name
         * @param $user_email
         * @param $role
         * @param array $extra_fields
         * @param $pwd
         * @throws Exception
         * @internal param array $extrafields
         */
        public function newUser($login_name, $user_email, $role, $extra_fields = array(), $pwd)
        {
            try {
                if ($role == "appuser") {
                    $extra_fields["birthday"] = "1/1/2000";
                    $extra_fields["country"] = "Hong Kong";
                    $extra_fields["countrycode"] = "HKG";
                    $extra_fields["setting_push_sms"] = "0";
                    $extra_fields["sms_number"] = "";
                    $extra_fields["gender"] = "M";
                    $extra_fields["uuid_key"] = "";
                    $extra_fields["app_token"] = "";
                    $extra_fields["password"] = $pwd;
                    $extra_fields["coin"] = 0;
                    $extra_fields["coin_update"] = "";
                    $extra_fields["profile_picture_uploaded"] = "";
                } else if ($role == "developer") {
                    $extra_fields["service_plan"] = 100;
                    $extra_fields["app_coins"] = 0;
                }
                //  inno_log_db::log_vcoin_login(-1, 101222, "new user create_user_account.");
                $this->reg_user = $this->create_user_account($login_name, $user_email, $role, $extra_fields, $pwd);
                if ($role == "appuser") {
                    //  inno_log_db::log_vcoin_login(-1, 101222, "new user and add uuid to the vcoin server.");
                    $this->create_vcoin_key_for_user();
                }
            } catch (Exception $e) {
                throw $e;
            }
        }

        /**
         * @param $login_name
         * @param $user_email
         * @param $role
         * @param array $extra_fields
         * @throws Exception
         */
        public function newUserRandomPass($login_name, $user_email, $role, $extra_fields = array())
        {
            try {
                $this->reg_user = $this->create_user_account($login_name, $user_email, $role, $extra_fields, wp_generate_password($length = 12, $include_standard_special_chars = TRUE));
                if ($role == "appuser") {
                    $this->create_vcoin_key_for_user();
                }
            } catch (Exception $e) {
                throw $e;
            }
        }
        /*
            public static function UpdateUserMeta($user_id, $data)
            {
                if (is_array($data)) {
                    foreach ($data as $key => $value) {
                        update_user_meta($user_id, $key, $value);
                    }
                }
            }

            public static function AddUserMeta($user_id, $data)
            {
                foreach ($data as $key => $value) {
                    add_user_meta($user_id, $key, $value);
                }
            }*/

        /**
         * @param $user_name
         * @return string
         */
        protected static function get_unique_new_username($user_name)
        {
            $returnUsername = '';
            $check_same_id = 0;
            if (!is_null(username_exists($user_name))) {
                $check_same_id++;
                $returnUsername = $user_name . $check_same_id;
            } else {
                $returnUsername = $user_name;
            }
            return $returnUsername;
        }


        /**
         * create a user account in here now
         * @param $login_name
         * @param $user_email
         * @param $role
         * @param array $extra_fields
         * @param $pwd
         * @throws Exception
         * @return WP_User
         */

        protected static function create_user_account($login_name, $user_email, $role, $extra_fields = array(), $pwd)
        {
            try {
                // $pwd = !$generate_password? wp_generate_password($length = 12, $include_standard_special_chars = TRUE);
                //  add_action("user_register", array(__CLASS__, "user_reg_action_cb"), 10, 1);
                remove_action("user_register", array(__CLASS__, "new_user_reg"));
                $namelist = explode(" ", $login_name);
                $login_name_final = $role . gformBase::gen_order_num() . join("_", $namelist);
                $user_id = wp_create_user($login_name_final, $pwd, $user_email);
                if (is_wp_error($user_id)) {
                    throw new Exception($user_id->get_error_message(), 10129);
                }
                add_action("user_register", array(__CLASS__, "new_user_reg"));
                //   remove_action("user_register", array(__CLASS__, "user_reg_action_cb"));
                $user = new WP_User($user_id);

                foreach ($extra_fields as $key => $val) {
                    // update_user_meta($user_id, $key, $val, false);
                    add_user_meta($user_id, $key, $val, false);
                }

                update_user_meta($user_id, 'display_name', $login_name);
                update_user_meta($user_id, "nickname", $login_name);
                update_user_meta($user_id, "first_name", $namelist[0]);
                update_user_meta($user_id, "last_name", $namelist[1]);

                //  debugoc::upload_bmap_log(print_r($args, true), 29291);
                //wp_insert_user($args);
                $user->remove_role('subscriber');
                $user->add_role($role);
                return $user;
            } catch (Exception $e) {
                throw $e;
            }
        }


        /**
         * Handles sending password retrieval email to user.
         *
         * @uses $wpdb WordPress Database object
         * @param string $user_login User Login or Email or User ID
         * @return bool true on success false on error
         */
        public static function retrieve_password($user_login)
        {
            global $wpdb, $current_site;
            if (empty($user_login)) {
                //
                return false;
            } else if (is_numeric($user_login)) {
                $user_data = get_user_by('id', $user_login);
            } else if (strpos($user_login, '@')) {
                $user_data = get_user_by('email', trim($user_login));
                if (empty($user_data))
                    return false;
            } else {
                $login = trim($user_login);
                $user_data = get_user_by('login', $login);
            }

            do_action('lostpassword_post');

            if (!$user_data)
                return false;

            // redefining user_login ensures we return the right case in the email
            $user_login = $user_data->user_login;
            $user_email = $user_data->user_email;

            //  do_action('retreive_password', $user_login);
            // Misspelled and deprecated
            do_action('retrieve_password', $user_login);

            $allow = apply_filters('allow_password_reset', true, $user_data->ID);

            if (!$allow)
                return false;
            else if (is_wp_error($allow))
                return false;

            $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
            if (empty($key)) {
                // Generate something random for a key...
                $key = wp_generate_password(20, false);
                do_action('retrieve_password_key', $user_login, $key);
                // Now insert the new md5 key into the db
                $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
            }
            $message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
            $message .= network_home_url('/') . "\r\n\r\n";
            $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
            $message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
            $message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
            $message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";

            if (is_multisite())
                $blogname = $GLOBALS['current_site']->site_name;
            else
                // The blogname option is escaped with esc_html on the way into the database in sanitize_option
                // we want to reverse this for the plain text arena of emails.
                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

            $title = sprintf(__('[%s] Password Reset'), $blogname);

            $title = apply_filters('retrieve_password_title', $title);
            $message = apply_filters('retrieve_password_message', $message, $key);

            if ($message && !wp_mail($user_email, $title, $message))
                wp_die(__('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function...'));

            return true;
        }

    }
}