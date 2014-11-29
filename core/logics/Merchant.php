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
        private $merchant_table;
        private $db;

        public function __construct()
        {
            global $wpdb;
            $this->db = $wpdb;
            $this->merchant_table = $this->db->prefix . "merchants";
        }

        public function add_merchant_account($query_result)
        {


            /*    $this->app_consumerid = $query_result->item_id;
                $this->auth_user = $query_result->vendor_id;
                $this->access_token = $query_result->vcoin_account;
                $this->access_token = $query_result->nature;*/


            $data = array(
                "item_id" => $query_result->item_id,
                "vendor_id" => $query_result->vendor_id,
                "vcoin_account" => $query_result->vcoin_account,
                "nature" => $query_result->nature
            );
            $this->db->insert($this->merchant_table, $data);
        }

        public function result()
        {
            return "done";
        }

        public function get_merchant_vcoin_id($stock_id)
        {
            $sql = $this->db->prepare("SELECT vcoin_account FROM $this->merchant_table WHERE item_id=%d", $stock_id);
            $vcoin_id = $this->db->get_var($sql);
            return $vcoin_id;
        }


    }
}