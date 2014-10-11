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
            //except using the wesoft data output format
            $d = api_handler::curl_post(CMS_SERVER . "/api/crosscms/" . $method, $params, $option);
            $object = json_decode($d);
            if (intval($object->result) > 1) throw new Exception($object->msg, $object->result);
            if (!$check_bool) {
                if (!isset($object->data))
                    throw new Exception("api done but there is no return on the key \"data\", please go back and check the source code. Check https or http for valid connections.", 1079);
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
            $d_row = api_handler::curl_get(VCOIN_SERVER . "/api/account/" . $method, $param);
            $res = json_decode($d_row);
            unset($d_row);
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
            unset($d_row);
            if (intval($res->result) > 0) {
                throw new Exception($res->msg, intval($res->result));
            } else {
                return $res->data;
            }
        }

        /**
         * adding vcoin to the account ID UUID
         * according to vcoin server API 1.6
         * @param $uuid_account
         * @param $amount
         * @return bool
         * @throws Exception
         */
        public static function add_vcoin($uuid_account, $amount)
        {
            $d_row = api_handler::curl_posts(VCOIN_SERVER . "/api/account/addcoin", array(
                "accountid" => $uuid_account,
                "count" => $amount,
            ));
            $res = json_decode($d_row);
            unset($d_row);
            if (intval($res->result) > 0) {
                throw new Exception($res->msg, intval($res->result));
            } else {
                return true;
            }
        }
    }
}