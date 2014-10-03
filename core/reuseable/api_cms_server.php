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
    }
}