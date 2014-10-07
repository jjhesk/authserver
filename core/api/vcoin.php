<?php
/*
  Controller name: Vcoin transaction
  Controller description: Backend API for presenting the JSON of system log. <br>Detail please refer to our <a href="https://docs.google.com/document/d/1ZJbHnUr7lj6lvds62Qcpu7FTZF-Oh61UGt6xLmT4st0/pub">documentation</a>. <br>Author: Hesk
 */
if (!class_exists('JSON_API_Vcoin_Controller')) {
    class JSON_API_Vcoin_Controller
    {


        /**
         * transaction api
         * @return array
         */
        public static function transact()
        {
            global $json_api, $current_user;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    TokenAuthentication::init($json_api->query->token);


                    $th = new vcoinBase();
                    $t = explode($json_api->query->data, ":");
                    $th
                        ->setSender($t[0])
                        ->setReceive($t[1])
                        ->setAmount($t[2])
                        ->CommitTransaction();


                    //your code in here....


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

        /**
         * triggered from the SDK App
         */
        public static function movecoins()
        {
            global $json_api, $current_user, $app_merchan;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    TokenAuthentication::init($json_api->query->token);

                    $money = $json_api->query->process_amount;


                    /*         $th = new vcoinBase();
                             $t = explode($json_api->query->data, ":");
                             $th
                                 ->setSender($t[0])
                                 ->setReceive($t[1])
                                 ->setAmount($t[2])
                                 ->CommitTransaction();*/


                    $coin = new vcoinBase();
                    $coin->setSender($app_merchan->getVcoinId());
                    $coin->setCoinGainer($current_user);
                    $coin->setAmount($money);
                    $coin->setTransactionReference("move coin api");
                    $coin->CommitTransaction();


                    /**
                     * going thru the vcoin server API to get transaction request
                     *
                     */
                    api_handler::outSuccessDataWeSoft(array(
                        "user" => $current_user->ID,
                        "amount" => $money,
                        "merchant" => $app_merchan->getappID(),
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
            global $json_api, $current_user, $app_merchan;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    $uuid = userBase::getVal($current_user->ID, "uuid_key");
                    if ($uuid == "") throw new Exception("the current user does not have valid vcoin account, please go back and with the settings", 1079);
                    $coinscount = api_cms_server::vcoin_account("balance", array("accountid" => $uuid));
                    api_handler::outSuccessDataWeSoft(array(
                        "account_id" => $uuid,
                        "coin" => intval($coinscount->coinscount),
                        "account_user_name" => "(" . $current_user->user_firstname . " " . $current_user->user_lastname . ")  " . $current_user->display_name,
                    ));
                }
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

        /**
         * display vcoin history and listing
         */
        public static function vcoin_history()
        {
            global $json_api, $current_user, $app_merchan;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();

                    $json_api_query = $json_api->query;
                    //  if (!isset($json_api_query->accountid)) throw new Exception("account is missing.", 1001);
                    if (!isset($json_api_query->start)) throw new Exception("start is missing.", 1002);
                    if (!isset($json_api_query->end)) throw new Exception("end is missing.", 1003);
                    if (!isset($json_api_query->index)) throw new Exception("index is missing.", 1004);

                    $uuid = userBase::getVal($current_user->ID, "uuid_key");
                    if ($uuid == "") throw new Exception("user account key is missing.", 1020);
                    $json = api_handler::curl_post(VCOIN_SERVER . "/api/account/history",
                        array(
                            "accountid" => $uuid,
                            "start" => $json_api_query->start,
                            "end" => $json_api_query->end,
                            "index" => $json_api_query->index
                        ));
                    $res = json_decode($json);
                    unset($json);
                    if (intval($res->result) > 0) {
                        throw new Exception($res->msg, intval($res->result));
                    } else {
                        /**
                         * going thru the vcoin server API to get transaction request
                         * todo: after the holiday on the ticket issued on 0010883
                         */
                        foreach ($res->coins as $history) {

                        }
                        api_handler::outSuccessDataWeSoft($res->coins);
                    }
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
                if (!isset($query->aid)) throw new Exception("missing action id", 1001);
                $trigger->run_test();

                api_handler::outSuccessDataWeSoft(array(
                    "change_result" => "done"
                ));
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

    }
}