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

            foreach($myrows->data as $my_field)
            {
                if($my_field == "comments")
                {
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
            global $wpdb,$json_api;

            $action_id = $json_api->query->aid;
            $table_checkpoint_log = $wpdb->prefix . 'action_reward';
            $query = $wpdb->prepare("SELECT ID,user,reference,triggered FROM $table_checkpoint_log
             WHERE action = %d AND rewarded= %d", $action_id, 1);
            $myrows = $wpdb->get_results($query);
            api_handler::outSuccessDataTable($myrows);
        }

        public static function app_reg_log()
        {
            global $wpdb,$current_user;
            $user_id = $current_user->ID;
            $user_role = $current_user->roles[0];
            $table_app_reg_log = $wpdb->prefix . 'oauth_api_consumers';

            if ($user_role == "administrator")
                $myrows = $wpdb->get_results("SELECT * FROM $table_app_reg_log");
            else if ($user_role == "developer")
                $myrows = $wpdb->get_results("SELECT * FROM $table_app_reg_log WHERE user = $user_id");
            else return;

            api_handler::outSuccessDataTable($myrows);
        }

    }
}
