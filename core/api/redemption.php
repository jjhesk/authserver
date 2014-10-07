<?php
/*
  Controller name: Redemption Controller
  Controller description: mobile API use for redemption and related list actions. <br>Detail please refer to our <a href="https://docs.google.com/document/d/1ZJbHnUr7lj6lvds62Qcpu7FTZF-Oh61UGt6xLmT4st0/pub">documentation</a>. <br>Author: Hesk
 */
if (!class_exists('JSON_API_Redemption_Controller')) {
    class JSON_API_Redemption_Controller
    {
        /**
         * Triggered from the Vcoin app
         */
        public static function redeem_submission()
        {
            global $json_api, $current_user, $app_merchan;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    $redeem_submission = new Redemption();
                    $redeem_submission->submission($json_api->query);
                    /**
                     * going thru the vcoin server API to get transaction request
                     */
                    api_handler::outSuccessDataWeSoft($redeem_submission->get_result());
                } else {
                    throw new Exception("module not installed", 1007);
                }
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

        /**
         * coupon redemption in here
         *
         */
        public static function redeem_coupon_submission()
        {
            global $json_api, $current_user, $app_merchan;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    $redeem_submission = new Redemption();
                    $redeem_submission->redeem_coupon($json_api->query);
                } else {
                    throw new Exception("module not installed", 1007);
                }
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

        //mi_rewards_list_history
        public static function redeem_list()
        {
            global $json_api, $current_user, $app_merchan;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    $list = new Redemption();
                    /**
                     * going thru the vcoin server API to get transaction request
                     */
                    api_handler::outSuccessDataWeSoft($list->listing($json_api->query));
                } else {
                    throw new Exception("module not installed", 1007);
                }
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

        /**
         * redeem obtained a list in here
         */
        public static function redeem_obtain()
        {
            global $json_api, $current_user, $app_merchan;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    $list = new Redemption();
                    $list->pickup($json_api->query);
                    /**
                     * going thru the vcoin server API to get transaction request
                     */
                    api_handler::outSuccessDataWeSoft($list->get_result());
                } else {
                    throw new Exception("module not installed", 1007);
                }
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }

        }


        /**
         * list the e-coupon list in history
         */
        public static function mi_e_coupon_list_history()
        {
            global $json_api, $current_user;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    $obj = api_cms_server::crosscms("user_redempted_e_coupon_list", array("user" => $current_user->ID));
                    api_handler::outSuccessDataWeSoft($obj);
                } else {
                    throw new Exception("module not installed", 1007);
                }
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }


    }
}