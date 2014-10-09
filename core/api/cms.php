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
        public static function android_playstore_api()
        {
            global $json_api;
            /*  $op = "https://dashboard.tapjoy.com/dashboard/apps/search/";
              echo api_handler::curl_get($op, array(
                  "term" => $json_api->query->term,
                  "platform" => $json_api->query->platform
              ));*/
            try {
                if (!class_exists("WPPlayStoreAPI")) throw new Exception("WPPlayStoreAPI is not activated", 9080);

                $result = WPPlayStoreAPI::search_item(array(
                        "search_query" => $json_api->query->keyword,
                    )
                );
                api_handler::outSuccessData($result);
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        public static function android_playstore_getinfo()
        {
            global $json_api;
            /*  $op = "https://dashboard.tapjoy.com/dashboard/apps/search/";
              echo api_handler::curl_get($op, array(
                  "term" => $json_api->query->term,
                  "platform" => $json_api->query->platform
              ));*/
            try {
                if (!class_exists("WPPlayStoreAPI")) throw new Exception("WPPlayStoreAPI is not activated", 9080);

                $result = WPPlayStoreAPI::get_info($json_api->query->packageid);
                api_handler::outSuccessData($result);
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        /**
         *  application registration panel
         *  use on the CMS only
         */
        public static function register()
        {
            global $json_api;
            $reg = new app_register();
            try {
                $d = $reg->reg($json_api->query);
                api_handler::outSuccessData($d);
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        /**
         * application listing and change status
         *
         */
        public static function change_app_status()
        {

        }

        /**
         * application remove the pending submission on the app
         */
        public static function remove_dead_app()
        {
            global $json_api;

            try {
                $rg = new app_register();
                $rg->remove_app_list($json_api->query, "dead");
                api_handler::outSuccess();
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        /**
         * application remove the pending submission on the app
         */
        public static function remove_pending_app()
        {
            global $json_api;
            try {
                $rg = new app_register();
                $rg->remove_app_list($json_api->query, "pending");
                api_handler::outSuccess();
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        /**
         * this is the request from the cms server
         * approve or reject
         */
        public static function update_pending_app()
        {
            global $json_api;
            try {
                $rg = new app_register();
                $rg->approve_app($json_api->query);
                api_handler::outSuccess();
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        /**
         * @internal param $transaction_vcoin_id
         * @internal param $status
         * 1: complete*
         * 1: complete
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

        /**
         * show me the money in here.
         */
        public static function get_coin_by_user()
        {

            global $current_user;
            try {
                $uuid = get_user_meta($current_user->ID, "uuid_key", true);
                if ($uuid == "") throw new Exception("the current user does not have valid vcoin account, please go back and with the settings", 1079);
                $coinscount = api_cms_server::vcoin_account("balance", array("accountid" => $uuid));
                api_handler::outSuccessData(array(
                    "account_id" => $uuid,
                    "coin" => intval($coinscount->coinscount),
                    "account_user_name" => "(" . $current_user->user_firstname . " " . $current_user->user_lastname . ")  " . $current_user->display_name,
                ));
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        /**
         * the available coin in the server
         */
        public static function available_coins()
        {
            global $current_user;
            try {
                $c = get_user_meta($current_user->ID, "app_coins", true);
                api_handler::outSuccessData($c);
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
                api_handler::outSuccessData($transaction);
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

                api_handler::outSuccessData($result_count);
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

                api_handler::outSuccessData($result_count);
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }


        public static function testing()
        {
            global $json_api;

            $testing = new actionBaseWatcher($json_api->query);

            $chart_data_label = $testing->get_checkpoint_data();

            api_handler::outSuccessData($chart_data_label);


        }
    }
}