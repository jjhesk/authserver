<?php
/*
  Controller name: CMS Use Only
  Controller description: These APIs are dedicated to be used by the CMS only<br>Author: Heskemo
 */
if (!class_exists('JSON_API_Cms_Controller')) {
    class JSON_API_Cms_Controller
    {
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

        public static function admin_approve_app()
        {
            global $json_api;
            $reg = new app_register();
            try {

                $reg->action_on_status_change($json_api->query);
                api_handler::outSuccessData("1");
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        public static function app_developer_launch_app()
        {
            global $json_api;
            $reg = new app_register();
            try {
                $reg->action_on_status_change($json_api->query);
                api_handler::outSuccessData("1");
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        /**
         * application listing and change status
         *
         */
        /*public static function change_app_status()
        {
            global $json_api;
            $reg = new app_register();
            try {
                $d = $reg->change_status($json_api->query);
                api_handler::outSuccessData($d);
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }*/

        /**
         * application's status change from alive to dead
         */
        public static function app_alive_to_dead()
        {
            global $json_api;

            try {
                $rg = new app_register();
                $rg->change_app_status($json_api->query, "alive_to_dead");
                api_handler::outSuccessData("1");
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        /**
         * application's status change from alive to dead
         */
        public static function app_dead_to_alive()
        {
            global $json_api;

            try {
                $rg = new app_register();
                $rg->change_app_status($json_api->query, "dead_to_alive");
                api_handler::outSuccessData("1");
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        /**
         * application remove the pending submission on the app
         */
        public static function remove_dead_app()
        {
            global $json_api;

            try {
                $rg = new app_register();
                $rg->remove_dead_app_list($json_api->query);
                api_handler::outSuccessData("1");
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
                $rg->remove_app_list($json_api->query);
                api_handler::outSuccessData("1");
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        /**
         * this is the request from the cms server
         * approve or reject
         *
         * public static function update_pending_app()
         * {
         * global $json_api;
         * try {
         * $rg = new app_register();
         * $rg->approve_app($json_api->query);
         * api_handler::outSuccess();
         * } catch (Exception $e) {
         * api_handler::outFail($e->getCode(), $e->getMessage());
         * }
         * }

         */
        /**
         * it should received from the cms server..
         */
        public static function send_push_to_user()
        {
            global $json_api;
            try {
                $Q = $json_api->query;
                if (!isset($Q->user_id)) throw new Exception("user is not is exist", 1001);
                if (!isset($Q->push_message)) throw new Exception("sms_message is not exist", 1002);
                api_cms_server::push_user($Q->user_id, $Q->push_message);
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
                $Q = $json_api->query;
                if (!isset($Q->user_id)) throw new Exception("user is not is exist", 1001);
                if (!isset($Q->sms_message)) throw new Exception("sms_message is not exist", 1002);
                if (intval(get_user_meta($Q->user_id, "setting_push_sms", true)) > 0) {
                    if (intval(get_user_meta($Q->user_id, "sms_number", true)) > 0) {
                        SMSmd::InitiateSMS(array(
                            "number" => intval(get_user_meta($Q->user_id, "sms_number", true)),
                            "content" => $Q->sms_message
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
                if ($uuid == "") throw new Exception("the current user does not have valid vcoin account, please go back and with the settings", 10979);
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
                $Q = $json_api->query;
                $user_id = $Q->user_id;
                $vcoin_plan = $Q->plan;
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


        /**
         *  coin history tab data source
         */
        public static function coin_history()
        {
            global $current_user, $json_api;
            try {
                $Q = $json_api->query;
                if (!is_user_logged_in()) throw new Exception(__("please login", HKM_LANGUAGE_PACK), 1010);
                if (isset($Q->appuser)) {
                    if ($current_user->roles[0] != "appuser") throw new Exception("no permission to use this api", 19101);
                    $uuid = userBase::getAppUserVcoinUUID($current_user);
                } else if (isset($Q->developer)) {
                    if ($current_user->roles[0] != "developer") throw new Exception("no permission to use this api", 19102);
                    if (!isset($Q->uuid)) throw new Exception("please provide the uuid of for the app", 19103); else
                        $uuid = $Q->uuid;
                } else if (isset($Q->administrator)) {
                    if ($current_user->roles[0] != "administrator") throw new Exception("no permission to use this api", 19102);
                    if (!isset($Q->uuid)) throw new Exception("please provide the uuid of for the app", 19103); else
                        $uuid = $Q->uuid;
                } else throw new Exception("please set the key for usage", 19104);

                $data_final = app_transaction_history::get_history_api($uuid, $Q);
                if (!isset($data_final)) throw new Exception("error from the vcoin server:" . print_r(array(
                        "accountid" => $uuid,
                        "start" => $Q->start,
                        "end" => $Q->end,
                    ), true), 10682);
                api_handler::outSuccessDataWeSoft($data_final);
                //  api_handler::outSuccessDataWeSoft($uuid);
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        /**
         * CMS use only
         */
        public static function getappbalance()
        {
            global $json_api, $wpdb;
            try {

                $Q = $json_api->query;
                //  inno_log_db::log_vcoin_third_party_app_transaction(-1, 10101, VCOIN_SERVER);

                $post_app_registration = $wpdb->prefix . "post_app_registration";
                userBase::check_permission_cms("administrator");
                if (!isset($Q->_id)) throw new Exception("invalid ID", 1601);
                $pass = $wpdb->get_row($wpdb->prepare("SELECT * FROM $post_app_registration WHERE ID=%d", (int)$Q->_id));
                if ($pass->vcoin_account == "") throw new Exception("invalid account uuid, please go back and with the settings", 1602);
                $coinscountdata = api_cms_server::vcoin_account("balance", array("accountid" => $pass->vcoin_account));


                api_handler::outSuccessData(array(
                    "account_id" => $pass->vcoin_account,
                    "coin" => (int)$coinscountdata->coinscount,
                    "deposit" => (int)$pass->deposit
                ));

            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

        /**
         * allow user to add coins and deduct coins
         */
        public static function cmsappbalanceoperate()
        {
            global $json_api, $wpdb;
            try {
                $Q = $json_api->query;
                $post_app_registration = $wpdb->prefix . "post_app_registration";
                userBase::check_permission_cms("administrator");
                $titan = TitanFramework::getInstance('vcoinset');
                if (!isset($Q->_id)) throw new Exception("invalid ID", 1601);
                if (!isset($Q->mt)) throw new Exception("invalid amount", 1603);
                $pass = $wpdb->get_row($wpdb->prepare("SELECT * FROM $post_app_registration WHERE ID=%d", (int)$Q->_id));
                if ($pass->vcoin_account == "") throw new Exception("invalid account uuid, please go back and with the settings", 1602);
                $amount = (int)$Q->mt;
                $coin = new vcoinBase();
                if ($amount > 0) {
                    $coin->setSender($titan->getOption("imusic_uuid"));
                    $coin->setReceive($pass->vcoin_account);
                } else {
                    $coin->setSender($pass->vcoin_account);
                    $coin->setReceive($titan->getOption("imusic_uuid"));
                }
                $amount = abs($amount);
                $coin->setAmount($amount);
                $coin->setTransactionReference("Officier coin adjustment");
                $coin->CommitTransaction();


                api_handler::outSuccessData(array(
                    "trace_id" => $coin->get_tranaction_reference(),
                    "coin" => $amount
                ));
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

        public static function testing()
        {
            global $json_api;
            $testing = new actionBaseWatcher($json_api->query);
            $chart_data_label = $testing->get_checkpoint_data();
            api_handler::outSuccessData($chart_data_label);

        }

        public static function get_app_info()
        {
            global $json_api;
            try {
                if (!isset($json_api->query->url)) throw new Exception("url is missing", 9919);
                $app_meta = api_handler::curl_gets($json_api->query->url, "", array(CURLOPT_TIMEOUT => 30));
                api_handler::outSuccessData(json_decode($app_meta));
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        public static function cms_get_claim_list_game_play_2()
        {
            try {
                $user = userBase::check_permission_cms("appuser");
                $obj = api_cms_server::crosscms("user_redemption_e_coupon_fifo", array("user" => $user->ID), false, false, array(
                    CURLOPT_TIMEOUT => 30
                ));
                api_handler::outSuccessDataTable($obj);
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        public static function coupon_verify_web()
        {
            global $json_api;
            try {
                $Q = $json_api->query;
                $user = userBase::check_permission_cms("appuser");
                $obj = api_cms_server::crosscms("user_coupon_fifo_claim",
                    array(
                        "user" => $user->ID,
                        "post_id" => $Q->post_id,
                        "phone_cell" => $Q->phone_cell,
                        "hk_id" => $Q->hk_id,
                        "id_name" => $Q->id_name,
                        "code" => $Q->code
                    ),
                    false, false, array(
                        CURLOPT_TIMEOUT => 30
                    ));
                api_handler::outSuccessDataTable($obj);
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        public static function update_app_info()
        {
            global $json_api;
            try {
                $app = new app_register();
                $app->update_app($json_api->query);
                api_handler::outSuccessData(1);
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }
    }
}