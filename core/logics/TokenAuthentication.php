<?php
defined('ABSPATH') || exit;
if (!class_exists('TokenAuthentication')) {
    /**
     * Created by PhpStorm.
     * User: hesk
     * Date: 8/23/14
     * Time: 3:26 PM
     */
    class TokenAuthentication
    {

        //$app_consumerid => the ID of the table from vapp app login token bank
        protected $auth_user, $app_consumerid, $access_token, $app_id, $app_vcoin_id, $app_secret, $app_post_id;

        public function __construct($query_result)
        {
            $this->app_consumerid = $query_result->consumerid;
            $this->auth_user = $query_result->user;
            $this->access_token = $query_result->token;
            $this->bind_uuid();
        }

        public function isVcoinApp()
        {
            return intval($this->app_consumerid) === -1;
        }

        /**
         * not ready to use now
         * @param $token
         * @param $app_hash
         * @param $app_key
         * @return mixed
         */
        public static function init_secured($token, $app_hash, $app_key)
        {
            global $wpdb, $app_merchan;

            $table = $wpdb->prefix . "app_login_token_banks";
            $verbose = $wpdb->prepare("SELECT * FROM $table WHERE token=%s", $token);
            $result_r = $wpdb->get_row($verbose);
            //app registration table
            $table = $wpdb->prefix . "oauth_api_consumers";
            $verbose = $wpdb->prepare("SELECT * FROM $table WHERE token=%s", $token);
            $consumerid = $verbose->ID;
            tokenBase::hashMatch($app_hash, $app_key, $verbose->secret);


            return $token;
        }

        /**
         * it is ready to use...
         * @param $token
         */
        public static function init($token)
        {
            global $wpdb, $app_merchan;
            $table = $wpdb->prefix . "app_login_token_banks";
            $verbose = $wpdb->prepare("SELECT * FROM $table WHERE token=%s", $token);
            $result_r = $wpdb->get_row($verbose);
            // $log = print_r($result_r, true);
            //  inno_log_db::log_vcoin_third_party_app_transaction(-1, 10201, "token add" . $log);
            $app_merchan = new TokenAuthentication($result_r);
        }

        /**
         * json auth central filter method
         * @param $token_input
         * @param $app_key
         * @return int
         */
        public static function get_user_id($token_input, $app_key)
        {
            global $wpdb;
            $table = $wpdb->prefix . "app_login_token_banks";
            $table2 = $wpdb->prefix . "oauth_api_consumers";
            $sql = "SELECT t1.exp,t1.user,t2.oauthkey FROM $table AS t1 LEFT JOIN $table2 AS t2 ON t1.consumerid = t2.id WHERE t1.token=%s";
            $verbose = $wpdb->prepare($sql, $token_input);
            $result_r = $wpdb->get_row($verbose);
            //  $log = print_r($result_r, true);
            if (!$result_r) {
                // token is invalid
                return -1;
            } else {
                if ($result_r->exp < time()) {
                    //  the token is expired
                    return -2;
                } else {
                    if ($result_r->oauthkey != $app_key) {
                        return -3;
                    } else {
                        //set merchant role or developer role account
                        //  inno_log_db::log_vcoin_third_party_app_transaction(-1, 10204, "TokenAuthentication is initiated." . $log . " / user: " . $result_r->user);
                        return $result_r->user;
                    }
                }
            }
        }

        private function bind_uuid()
        {
            global $wpdb;
            $table = $wpdb->prefix . "oauth_api_consumers";
            $sql = $wpdb->prepare("SELECT * FROM $table WHERE id=%d", $this->app_consumerid);
            $result = $wpdb->get_row($sql);
            $this->app_post_id = $result->post_id;
            $this->app_id = $result->oauthkey;
            $this->app_vcoin_id = $result->vcoin_account;
            $this->app_secret = $result->secret;
        }

        public function getPostID()
        {
            return $this->app_post_id;
        }

        public function getappID()
        {
            return $this->app_id;
        }

        public function getVcoinId()
        {
            return $this->app_vcoin_id;
        }

    }
}