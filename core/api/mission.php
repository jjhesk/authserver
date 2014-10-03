<?php
/*
  Controller name: Mission Controller
  Controller description: mobile API use for mission related  actions. <br>Detail please refer to our <a href="https://docs.google.com/document/d/1ZJbHnUr7lj6lvds62Qcpu7FTZF-Oh61UGt6xLmT4st0/pub">documentation</a>. <br>Author: Hesk
 */
if (!class_exists('JSON_API_Mission_Controller')) {
    class JSON_API_Mission_Controller
    {


        /**
         * check point for each coin movement
         *
         */
        public static function checkpoint()
        {
            global $json_api, $current_user;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    TokenAuthentication::init($json_api->query->token);
                    $trigger = new actionBaseWatcher($json_api->query);
                    $trigger->record($current_user);

                    api_handler::outSuccessDataWeSoft($trigger->reward_result());
                } else {
                    throw new Exception("module not installed", 1007);
                }
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }


        /**
         * get all success histories of that user
         */
        public static function get_unfinished_missions()
        {
            global $json_api, $current_user;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    TokenAuthentication::init($json_api->query->token);
                    $history = new missionList();
                    $history->get_unfinished_missions($current_user->ID);

                    api_handler::outSuccessDataWeSoft($history->getList());
                } else {
                    throw new Exception("module not installed", 1007);
                }
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        /**
         * get all success histories of that user
         */
        public static function get_finished_missions()
        {
            global $json_api, $current_user;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    TokenAuthentication::init($json_api->query->token);
                    $history = new missionList();
                    $history->get_finished_missions($current_user->ID);

                    api_handler::outSuccessDataWeSoft($history->getList());
                } else {
                    throw new Exception("module not installed", 1007);
                }
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        /**
         * to retrieve a list of mission including complete and incomplete.
         *
         */
        public static function get_mission_list()
        {
            global $json_api, $current_user;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    TokenAuthentication::init($json_api->query->token);
                    $history = new missionList();
                    $history->get_mix_list($current_user->ID);
                    api_handler::outSuccessDataWeSoft($history->getList());
                } else {
                    throw new Exception("module not installed", 1007);
                }
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

    }
}
