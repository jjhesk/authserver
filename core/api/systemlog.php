<?php
/*
  Controller name: System Log
  Controller description: Backend API for presenting the JSON of system log. <br>Detail please refer to our Google Drive documentation. <br>Author: Ryo
 */
if (!class_exists('JSON_API_Systemlog_Controller')) {
    class JSON_API_Systemlog_Controller
    {
        /**
         * API Name: api get sliders
         */

        public static function login_log()
        {
            global $wpdb;
            $table_app_log = $wpdb->prefix . 'app_log';
            $myrows = $wpdb->get_results("SELECT * FROM $table_app_log WHERE event_code = 521");

            foreach ($myrows->data as $my_field) {
                if ($my_field == "comments") {
                    $my_field = "";
                }
            }
            api_handler::outSuccessDataTable($myrows);
        }

        public static function email_log()
        {
            global $wpdb;
            $table_app_log = $wpdb->prefix . 'app_log';
            $myrows = $wpdb->get_results("SELECT * FROM $table_app_log WHERE event_code = 522");
            api_handler::outSuccessDataTable($myrows);
        }

        public static function vcoin_app_log()
        {
            global $wpdb;
            $table_app_log = $wpdb->prefix . 'app_log';
            $myrows = $wpdb->get_results("SELECT * FROM $table_app_log WHERE event_code = 523");
            api_handler::outSuccessDataTable($myrows);
        }

        public static function new_account_log()
        {
            global $wpdb;
            $table_app_log = $wpdb->prefix . 'app_log';
            $myrows = $wpdb->get_results("SELECT * FROM $table_app_log WHERE event_code = 524");
            api_handler::outSuccessDataTable($myrows);
        }

        public static function redemption_verification_log()
        {
            global $wpdb;
            $table_app_log = $wpdb->prefix . 'app_log';
            $myrows = $wpdb->get_results("SELECT * FROM $table_app_log WHERE event_code = 525");
            api_handler::outSuccessDataTable($myrows);
        }

        public static function redemption_verify_log()
        {
            global $wpdb;
            $table_app_log = $wpdb->prefix . 'app_log';
            $myrows = $wpdb->get_results("SELECT * FROM $table_app_log WHERE event_code = 526");
            api_handler::outSuccessDataTable($myrows);
        }

        public static function third_party_app_transaction_log()
        {
            global $wpdb;
            $table_app_log = $wpdb->prefix . 'app_log';
            $myrows = $wpdb->get_results("SELECT * FROM $table_app_log WHERE event_code = 527");
            api_handler::outSuccessDataTable($myrows);
        }

        public static function check_point_mission_log()
        {
            global $wpdb, $json_api;

            $action_id = $json_api->query->aid;
            $table_checkpoint_log = $wpdb->prefix . 'action_reward';
            $query = $wpdb->prepare("SELECT ID,user,reference,triggered FROM $table_checkpoint_log
             WHERE action = %d AND rewarded= %d", $action_id, 1);
            $myrows = $wpdb->get_results($query);
            api_handler::outSuccessDataTable($myrows);
        }

        /**
         * used by the developer only
         */
        public static function app_reg_log()
        {
            global $wpdb, $current_user;
            $user_id = $current_user->ID;
            $table_app_reg_log = $wpdb->prefix . 'post_app_registration';

            $myrows = $wpdb->get_results("SELECT * FROM $table_app_reg_log WHERE devuser = $user_id");
            api_handler::outSuccessDataTable($myrows);
        }

        /**
         * used by the admin
         */
        public static function app_reg_admin_processing()
        {
            global $wpdb, $current_user, $json_api;
            try {
                //     $user_id = $current_user->ID;
                $user_role = $current_user->roles[0];
                if ($user_role != "administrator") throw new Exception("you are not permitted to use this API", 101011);

                $primaryKey = 'ID';

                $columns = array(
                    array('db' => 'ID', 'dt' => 'ID'),
                    array('db' => 'devuser', 'dt' => 'devuser'),
                    array('db' => 'devname', 'dt' => 'devname'),
                    array('db' => 'status', 'dt' => 'status'),
                    array('db' => 'store_id', 'dt' => 'store_id'),
                    array('db' => 'app_key', 'dt' => 'app_key'),
                    array('db' => 'app_secret', 'dt' => 'app_secret'),
                    array('db' => 'platform', 'dt' => 'platform'),
                    array('db' => 'post_id', 'dt' => 'post_id'),
                    array('db' => 'deposit', 'dt' => 'deposit'),
                    array('db' => 'payout', 'dt' => 'payout'),
                    array('db' => 'description', 'dt' => 'description'),
                    array('db' => 'vcoin_account', 'dt' => 'vcoin_account'),
                    array('db' => 'app_title', 'dt' => 'app_title'),
                    array('db' => 'icon', 'dt' => 'icon'),
                    array('db' => 'image_urls', 'dt' => 'image_urls'),
                );

                if (isset($json_api->query->sort)) {
                    if ($json_api->query->sort != "all")
                        $condition = "status " . " LIKE " . "'%" . $json_api->query->sort . "%'";
                    else {
                        $condition = "";
                    }
                }else $condition = "";

                $data_result = sspclass::simple($_GET, $wpdb, $wpdb->prefix . "post_app_registration",
                    $primaryKey, $columns, $condition);

                api_handler::outSuccessPagingDataTable($data_result);

            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }

        }
    }
}
