<?php

/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年9月11日
 * Time: 上午11:37
 */
defined('ABSPATH') || exit;
if (!class_exists('api_cms_server')) {
    class api_cms_server
    {
        /**
         * @param $method
         * @param array $params
         * @param bool $associated
         * @param bool $check_bool
         * @param $option
         * @throws Exception
         * @internal param bool $out_in_array
         * @return bool
         */
        public static function crosscms($method, $params = array(), $associated = true, $check_bool = false, $option = array())
        {
            $settings = TitanFramework::getInstance('vcoinset');
            if ($settings->getOption("cms_connect")) throw new Exception("cms server is not available", 75910);

            //except using the wesoft data output format
            $d = api_handler::curl_post(CMS_SERVER . "/api/crosscms/" . $method, $params, $option);
            $object = json_decode($d);
            if (intval($object->result) > 1) throw new Exception($object->msg, $object->result);
            //inno_log_db::log_vcoin_third_party_app_transaction(-1, 839284, CMS_SERVER);
            if (!$check_bool) {
                if (!isset($object->data)) {
                    inno_log_db::log_vcoin_third_party_app_transaction(-1, 839284, print_r($object, true));
                    throw new Exception("api done but there is no return on the key \"data\", please go back and check the source code. Check https or http for valid connections.", 1079);
                }
            } else {
                return true;
            }


            if ($associated) {
                $obj = json_decode($d, true);
                return $obj["data"];
            } else {


                return $object->data;
            }
        }

        /**
         * @param $method
         * @param array $params
         * @param bool $associated
         * @param bool $check_bool
         * @param array $option
         * @return bool
         * @throws Exception
         */
        public static function crosscms_get($method, $params = array(), $associated = true, $check_bool = false, $option = array())
        {
            //except using the wesoft data output format
            $d = api_handler::curl_get(CMS_SERVER . "/api/crosscms/" . $method, $params, $option);
            $object = json_decode($d);
            if (intval($object->result) > 1) throw new Exception($object->msg, $object->result);


            if (!$check_bool) {
                if (!isset($object->data)) {
                    inno_log_db::log_vcoin_third_party_app_transaction(-1, 839284, print_r($object, true));
                    throw new Exception("api done but there is no return on the key \"data\", please go back and check the source code. Check https or http for valid connections.", 1079);
                }
            } else {
                return true;
            }
            if ($associated) {
                $obj = json_decode($d, true);
                return $obj["data"];
            } else {


                return $object->data;
            }
        }

        /**
         * vcoin server account display API
         * according to vcoin server API 1.4
         * @param $method
         * @param array $param
         * @return mixed
         * @throws Exception
         */
        public static function vcoin_account($method, $param = array())
        {
            $d_row = api_handler::curl_gets(VCOIN_SERVER . "/api/account/" . $method, $param);
            $res = json_decode($d_row);
            $d_row = NULL;
            if (intval($res->result) > 0) {
                throw new Exception($res->msg, intval($res->result));
            } else {
                return $res->data;
            }
        }

        /**
         * vcoin server account display API
         * according to vcoin server API 1.4
         * @param $method
         * @param array $param
         * @return mixed
         * @throws Exception
         */
        public static function vcoin_accountp($method, $param = array())
        {
            $d_row = api_handler::curl_post(
                VCOIN_SERVER . "/api/account/" . $method,
                $param,
                array(
                    CURLOPT_TIMEOUT => 30,
                ));
            $res = json_decode($d_row);
            $d_row = NULL;
            if (intval($res->result) > 0) {
                throw new Exception($res->msg, intval($res->result));
            } else {
                return $res->data;
            }
        }

        /**
         * adding vcoin to the account ID UUID
         * according to vcoin server API 1.9
         * @param $uuid_account
         * @param $amount
         * @param string $reference_code
         * @throws Exception
         * @return bool
         */
        public static function add_vcoin($uuid_account, $amount, $reference_code = "")
        {
            $d_row = api_handler::curl_posts(VCOIN_SERVER . "/api/account/addcoin", array(
                "accountid" => $uuid_account,
                "count" => $amount,
                "reference" => $reference_code
            ));
            $res = json_decode($d_row);
            $d_row = NULL;
            if (intval($res->result) > 0) {
                throw new Exception($res->msg, intval($res->result));
            } else {
                return true;
            }
        }

        /**
         * @param $user_id
         * @param $enabled
         */
        public static function enable_account($user_id, $enabled)
        {
            //  $user = new WP_User($user_id);
            $uuid = get_user_meta($user_id, "uuid_key", true);
            if ($uuid != "") {
                $d_row = api_handler::curl_posts(VCOIN_SERVER . "/api/account/enable", array(
                    "accountid" => $uuid,
                    "enable" => $enabled ? 1 : 0
                ));
            }
        }

        /**
         * @param $email
         * @param $message
         * @return bool
         * @throws Exception
         */
        public static function push_user_email($email, $message)
        {
            $d = api_handler::curl_post(PUSH_SERVER . "/api/push/pushuser/", array(
                "email" => $email,
                "message" => $message
            ));
            $object = json_decode($d);
            if (intval($object->result) > 1) throw new Exception($object->msg, $object->result);
            inno_log_db::log_vcoin_email(-1, 10011, "push server has sent the message.");
            return true;
        }

        /**
         * @param $user_id
         * @param $message
         * @return bool
         */
        public static function push_user($user_id, $message)
        {
            $user = new WP_User($user_id);
            $emailstr = $user->user_email;
            return self::push_user_email($emailstr, $message);
        }
    }
}