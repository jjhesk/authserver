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

    public function __construct($query, WP_User $user)
    {
        $this->f_container = array();
        $this->user = $user;
        if (isset($query->firstname)) {
            $this->name_field = "first_name";

            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->value,
            );
        }
        if (isset($query->lastname)) {
            $this->name_field = "last_name";

            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->value,
            );
        }
        if (isset($query->nickname)) {
            $this->name_field = "nickname";

            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->value,
            );
        }
        if (isset($query->description)) {
            $this->name_field = "description";

            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->value,
            );
        }
        if (isset($query->user_url)) {
            $this->name_field = "user_url";

            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->value,
            );
        }
        if (isset($query->email)) {
            $this->name_field = "email";

            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->value,
            );
        }
        if (isset($query->password)) {
            if (!isset($query->old_password)) {
                throw new Exception("the old password is not presented", 1031);
            }
            if ($user && wp_check_password($query->old_password, $user->data->user_pass, $user->ID)) {
                $this->name_field = "password";
                $this->f_container[] = array(
                    "field" => $this->name_field,
                    "value" => $query->value,
                );
            } else {
                throw new Exception("the password does not match to set the new password", 1032);
            }

        }

        if (isset($query->gender)) {
            $this->name_field = "gender";

            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->value,
            );
        }

        if (isset($query->birthday)) {
            $this->name_field = "birthday";

            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->value,
            );
        }

        if (isset($query->country)) {
            $this->name_field = "country";

            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->value,
            );
        }

        if (isset($query->countrycode)) {
            $this->name_field = "countrycode";

            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->value,
            );
        }

        if (isset($query->setting_push_sms)) {
            $this->name_field = "setting_push_sms";

            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->value,
            );
        }
        if (isset($query->sms_number)) {
            $this->name_field = "sms_number";

            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->value,
            );
        }
        if (isset($query->language)) {
            $this->name_field = "language";

            $this->f_container[] = array(
                "field" => $this->name_field,
                "value" => $query->value,
            );
        }
        $this->commit_changes();
    }

    private function commit_changes()
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
    }

    public function get_change_field_results()
    {
        return $this->f_container;
    }

    public function get_field()
    {
        return $this->name_field;
    }

    private function change_wp($name_field, $input)
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

    private function change($name_field, $input)
    {
        if (get_user_meta($this->user->ID, $name_field, true) == "") add_user_meta($this->user->ID, $name_field, $input, false);
        else  update_user_meta($this->user->ID, $name_field, $input, get_user_meta($this->user->ID, $name_field, true));
    }
} 