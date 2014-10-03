<?php
/*
  Controller name: CMS Use Only
  Controller description: These APIs are dedicated to be used by the CMS only<br>Author: Heskemo
 */
if (!class_exists('JSON_API_Cms_Controller')) {
    class JSON_API_Cms_Controller
    {
        /**
         * API Name: api get sliders
         */
        public static function apiAppListing()
        {
            global $json_api;
            $op = "https://dashboard.tapjoy.com/dashboard/apps/search/";
            echo api_handler::curl_get($op, array(
                "term" => $json_api->query->term,
                "platform" => $json_api->query->platform
            ));
        }

        /**
         *  use on the CMS only
         */
        public static function register()
        {
            global $json_api;
            $reg = new app_register();
            try {
                $d = $reg->reg($json_api->query);
                return api_handler::outSuccessData($d);
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }


        /**
         * CMS
         * not in use for now.
         */


        /**
         * @internal param $transaction_vcoin_id
         * @internal param $status
         * 1: complete* 1: complete
         * 2: error - incomplete
         * 3: unknown
         */
        public static function send_sms_to_user()
        {
            global $json_api;
            try {
                $jsonq = $json_api->query;
                if (!isset($jsonq->user_id)) throw new Exception("user is not is exist", 1001);
                if (!isset($jsonq->sms_message)) throw new Exception("sms_message is not exist", 1002);
                if (intval(get_user_meta($jsonq->user_id, "setting_push_sms", true)) > 0) {
                    if (intval(get_user_meta($jsonq->user_id, "sms_number", true)) > 0) {
                        SMSmd::InitiateSMS(array(
                            "number" => intval(get_user_meta($jsonq->user_id, "sms_number", true)),
                            "content" => $jsonq->sms_message
                        ));
                        api_handler::outSuccess();
                    }
                    throw new Exception(" there is no sms number configured", 1003);
                }
                throw new Exception("setting is not allow", 1004);
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        public static function available_coins()
        {
            global $current_user;
            try {
                $c = get_user_meta($current_user->ID, "app_coins", true);
                return api_handler::outSuccessData($c);
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        /**
         * add vcoin by plan
         * cms use only
         * to adding new plan to cms service
         */
        public static function add_plan()
        {
            global $json_api;
            try {
                $jsonq = $json_api->query;
                $user_id = $jsonq->user_id;
                $vcoin_plan = $jsonq->plan;
                if (!isset($user_id)) throw new Exception("user is not is exist", 1001);
                if (!isset($vcoin_plan)) throw new Exception("plan is not exist", 1002);

                $plan_settings = array(
                    "plan_100" => 10000,
                    "plan_300" => 50000,
                    "plan_900" => 120000,
                );
                $transaction = false;
                foreach ($plan_settings as $plan => $amount) {
                    if ($plan == $vcoin_plan) {
                        userBase::update_single($user_id, "app_coins", $amount);
                        $transaction = true;
                        break;
                    }
                }
                return api_handler::outSuccessData($transaction);
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        /**
         * this is related to dashboard cross cms data request
         */
        public static function dashboard_request_pending_post_num()
        {
            global $current_user;
            try {
                if (!is_user_logged_in()) throw new Exception("please login", 1010);

                $dashboard_meta = new dashboardMeta($current_user);
                $result_count = $dashboard_meta->get_pending_post_num();

                return api_handler::outSuccessData($result_count);
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        /**
         * this is related to dashboard cross cms data request
         */
        public static function dashboard_request_listed_post_num()
        {
            global $current_user;
            try {
                if (!is_user_logged_in()) throw new Exception("please login", 1010);

                $dashboard_meta = new dashboardMeta($current_user);
                $result_count = $dashboard_meta->get_listed_post_num();

                return api_handler::outSuccessData($result_count);
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }
    }
}