<?php
/*
  Controller name: Vcoin transaction
  Controller description: Backend API for presenting the JSON of system log. <br>Detail please refer to our <a href="https://docs.google.com/document/d/1ZJbHnUr7lj6lvds62Qcpu7FTZF-Oh61UGt6xLmT4st0/pub">documentation</a>. <br>Author: Hesk
 */
if (!class_exists('JSON_API_Vcoin_Controller')) {
    class JSON_API_Vcoin_Controller
    {
        public static function watchvideo()
        {
            global $json_api, $current_user, $app_client;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    $Q = $json_api->query;
                    if ($app_client->isVcoinApp()) {
                        if (!isset($Q->post_id)) throw new Exception("post_id is missing.", 1712);
                        $obj = api_cms_server::crosscms("reward_transaction_metadata", array(
                            "post_id" => $Q->post_id
                        ), false);


                        $coin = new vcoinBase();
                        $coin->setSender($obj->uuid);
                        $coin->setCoinGainer($current_user);
                        $coin->setAmount($obj->amount);
                        $coin->setTransactionReference("video watch payout ID:" . $Q->post_id);
                        $coin->CommitTransaction();


                        api_handler::outSuccessDataWeSoft(array(
                            "trace_id" => $coin->get_tranaction_reference()
                        ));
                    } else throw new Exception("this is not vcoinapp", 1777);
                } else throw new Exception("module not installed", 1007);
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

        /**
         * triggered from the SDK App and the vcoin App
         */
        public static function movecoins()
        {
            global $json_api, $current_user, $app_client;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    //  TokenAuthentication::init($json_api->query->token);

                    $money = $json_api->query->process_amount;


                    $coin = new vcoinBase();
                    $coin->setSender($app_client->getVcoinId());
                    $coin->setCoinGainer($current_user);
                    $coin->setAmount($money);
                    $coin->setTransactionReference("in-app move coin");
                    $coin->CommitTransaction();


                    /**
                     * going thru the vcoin server API to get transaction request
                     */
                    userBase::update_app_user_coin($current_user);
                    /**
                     * push the message to vcoin server
                     */
                    $string = messagebox::successMessage(77009, array(
                        "app_name" => $app_client->isVcoinApp() ? "Vcoin App" : $app_client->getAppTitle(),
                        "amount" => $money,
                        "trace_id" => $coin->get_tranaction_reference()
                    ));
                    api_cms_server::push_user($current_user->ID, $string);

                    api_handler::outSuccessDataWeSoft(array(
                        "user" => $current_user->ID,
                        "amount" => $money,
                        "merchant" => $app_client->getappID(),
                        "transactionid" => $coin->get_tranaction_reference()
                    ));

                } else {
                    throw new Exception("module not installed", 1007);
                }
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }


        /**
         * get the balance from the Vcoin Server
         */
        public static function getbalance()
        {
            global $json_api, $current_user, $app_client;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();

                    $uuid = userBase::getVal($current_user->ID, "uuid_key");
                    if ($uuid == "") throw new Exception("the current user does not have valid vcoin account, please go back and with the settings", 1079);
                    $coinscount = api_cms_server::vcoin_account("balance", array("accountid" => $uuid));
                    $name = $current_user->user_firstname . " " . $current_user->user_lastname . " (" . $current_user->display_name . ")";
                    $devname = $app_client->get_developer_name();
                    api_handler::outSuccessDataWeSoft(array(
                        "account_id" => $uuid,
                        "coin" => intval($coinscount->coinscount),
                        "account_user_name" => $name,
                        "developer_name" => $devname,
                    ));
                    // api_handler::outSuccessDataWeSoft($devname);
                }
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

        /**
         * display vcoin history and listing
         *
         * url input:
         *
         * app_uuid
         * feature
         *
         */
        public static function vcoin_history()
        {
            global $json_api, $current_user, $app_client;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    $Q = $json_api->query;

                    //  if (!isset($Q->accountid)) throw new Exception("account is missing.", 1001);
                    if (!isset($Q->feature)) throw new Exception("missing feature for vcoin history", 10006);
                    if (isset($Q->app_uuid)) $uuid = $Q->app_uuid; else $uuid = userBase::getAppUserVcoinUUID($current_user);
                    $data_final = app_transaction_history::get_history_api($uuid, $Q);
                    api_handler::outSuccessDataWeSoft($data_final);
                } else {
                    throw new Exception("module not installed", 1007);
                }
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

        /**
         * testing CP
         */
        public static function testcp()
        {
            global $json_api, $current_user;

            try {

                $user = $current_user;
                $trigger = new actionBaseWatcher($json_api->query);
                //  return array("status" => "okay", "result" => "done.");
                $query = $json_api->query;
                if (!isset($query->aid)) throw new Exception("missing action id", 10007);
                $trigger->run_test();

                api_handler::outSuccessDataWeSoft(array(
                    "change_result" => "done"
                ));
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

        /**
         * @return array
         */
        public static function testing_sdk()
        {

            global $json_api, $current_user;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();

                    api_handler::outSuccessDataWeSoft(array(
                        "user" => json_auth_central::display_user_data($current_user)
                    ));
                } else {
                    throw new Exception("module not installed", 1007);
                }
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

    }
}