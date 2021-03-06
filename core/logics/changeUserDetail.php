<?php
defined('ABSPATH') || exit;

/**
 * Created by PhpStorm.
 * User: hesk
 * Date: 8/23/14
 * Time: 3:04 PM
 */
class changeUserDetail
{
    protected $user, $name_field, $f_container;

    public static function get_pattern()
    {
        //this old combinations
        //  $pattern = '/^(?=.{8,}$)(?=[^A-Z]*[A-Z][^A-Z]*$)\w*\W\w*$/g';
        //this new requirements only allow at least 8 or more characters
        $pattern = '/^.{8,35}$/';
        return $pattern;
    }

    /**
     * @param $query
     * @param WP_User $user
     * @throws Exception
     */
    public function __construct($query, WP_User $user)
    {
        $this->f_container = array();
        $this->user = $user;

        if (isset($query->password)) {
            if (!isset($query->old_password)) {
                throw new Exception("the old password is not presented", 1031);
            }
            if ($user && wp_check_password($query->old_password, $user->data->user_pass, $user->ID)) {
                $this->name_field = "password";
                if (!preg_match(self::get_pattern(), $query->password, $matches, PREG_OFFSET_CAPTURE))
                    throw new Exception("Password must have between 8-35 characters/digits/symbols. ", 1034);
                /*  throw new Exception("Not a secured password. Please make sure that the password 1) must contain at least one digit, 2) must contain at least one uppercase character, 3) must contain at least one special symbol", 1034);*/
                inno_log_db::log_vcoin_third_party_app_transaction($this->user->ID, 3923, "change password: " . $query->password);
                $this->f_container[] = array(
                    "field" => $this->name_field,
                    "value" => $query->password,
                );
            } else {
                throw new Exception("Your old password is not matched", 1032);
            }

        }

        if (isset($query->firstname)) {
            $this->name_field = "first_name";
            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->firstname,
            );
        }
        if (isset($query->lastname)) {
            $this->name_field = "last_name";
            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->lastname,
            );
        }
        if (isset($query->nickname)) {
            $this->name_field = "nickname";
            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->nickname,
            );
        }
        if (isset($query->description)) {
            $this->name_field = "description";
            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->description,
            );
        }
        if (isset($query->user_url)) {
            $this->name_field = "user_url";
            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->user_url,
            );
        }
        if (isset($query->email)) {
            $this->name_field = "email";
            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->email,
            );
        }


        if (isset($query->gender)) {
            $this->name_field = "gender";
            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->gender,
            );
        }

        if (isset($query->birthday)) {
            $this->name_field = "birthday";
            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->birthday,
            );
        }

        if (isset($query->country)) {
            $this->name_field = "country";
            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->country,
            );
        }

        if (isset($query->countrycode)) {
            $this->name_field = "countrycode";
            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->countrycode,
            );
        }

        if (isset($query->setting_push_sms)) {
            $this->name_field = "setting_push_sms";
            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->setting_push_sms,
            );
        }
        if (isset($query->sms_number)) {
            $this->name_field = "sms_number";
            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->sms_number,
            );
        }
        if (isset($query->language)) {
            $this->name_field = "language";
            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->language,
            );
        }
        $this->commit_changes();
    }

    /**
     * committing the changes
     */
    private
    function commit_changes()
    {
        $f = array(
            "password", "email", "description", "nickname", "first_name", "last_name", "user_url"
        );
        foreach ($this->f_container as $ob) {
            if (in_array($ob["field"], $f)) {
                $this->change_wp($ob["field"], $ob["value"]);
            } else {
                $this->change($ob["field"], $ob["value"]);
            }
        }
        unset($f);
    }

    /**
     * @return array
     */
    public
    function get_change_field_results()
    {
        return $this->f_container;
    }

    /**
     * @return string
     */
    public
    function get_field()
    {
        return $this->name_field;
    }

    /**
     * @param $name_field
     * @param $input
     */
    private
    function change_wp($name_field, $input)
    {
        if ($name_field == "password") {
            wp_set_password($input, $this->user->ID);
            $this->change("password", $input);
        } else if ($name_field == "email") {
            $user_id = wp_update_user(array('ID' => $this->user->ID, 'user_email' => $input));
        } else if ($name_field == "user_url") {
            $user_id = wp_update_user(array('ID' => $this->user->ID, 'user_url' => $input));
        } else {
            $user_id = wp_update_user(array('ID' => $this->user->ID, $name_field => $input));
        }
    }

    /**
     * @param $name_field
     * @param $input
     */
    private
    function change($name_field, $input)
    {
        // update_user_meta($this->user->ID, $name_field, $input);
        if (get_user_meta($this->user->ID, $name_field, true) == "") {
            add_user_meta($this->user->ID, $name_field, $input, false);
            // inno_log_db::log_vcoin_third_party_app_transaction(-1, 10101, "change field on add user meta: " . $input);
        } else {
            // inno_log_db::log_vcoin_third_party_app_transaction(-1, 10101, "change field on update user meta: " . $input);
            update_user_meta($this->user->ID, $name_field, $input);
        }
    }
} 