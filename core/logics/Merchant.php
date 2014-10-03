<?php
defined('ABSPATH') || exit;
if (!class_exists('Merchant')) {
    /**
     * Created by PhpStorm.
     * User: Hesk
     * Date: 14年9月3日
     * Time: 下午3:45
     */
    class Merchant
    {

        public function __construct()
        {

        }

        public function add_merchant_account($query_result)
        {
            global $wpdb;

            /*    $this->app_consumerid = $query_result->item_id;
                $this->auth_user = $query_result->vendor_id;
                $this->access_token = $query_result->vcoin_account;
                $this->access_token = $query_result->nature;*/

            $table = $wpdb->prefix . "merchants";
            $data = array(
                "item_id" => $query_result->item_id,
                "vendor_id" => $query_result->vendor_id,
                "vcoin_account" => $query_result->vcoin_account,
                "nature" => $query_result->nature
            );
            $wpdb->insert($table, $data);


        }

        public function result()
        {
            return "done";
        }

        public function get_merchant_vcoin_id($stock_id)
        {
            global $wpdb;
            $table = $wpdb->prefix . "merchants";
            $sql = $wpdb->prepare("SELECT vcoin_account FROM $table WHERE item_id=%d", $stock_id);
            $vcoin_id = $wpdb->get_var($sql);
            return $vcoin_id;
        }


    }
}