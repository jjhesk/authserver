<?php
/*
  Controller name: Personal Info Management
  Controller description: API delivery output for mobile applications. Detail please refer to our <a href="https://docs.google.com/document/d/1ZJbHnUr7lj6lvds62Qcpu7FTZF-Oh61UGt6xLmT4st0/pub">documentation</a>.
 */
if (!class_exists('JSON_API_Personal_Controller')) {
    class JSON_API_Personal_Controller
    {

        public static function upload_personal_image_profile()
        {
            global $json_api, $current_user;
            try {
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    TokenAuthentication::init($json_api->query->token);
                    $upload = new uploadfiles(-1, -1, "profile");
                    $upload->setUser($current_user);

                    api_handler::outSuccessDataWeSoft(array(
                        "change_result" => "done"
                    ));
                } else {
                    throw new Exception("module not installed", 1007);
                }
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

        /**
         * User setting change request
         *
         */
        public static function change_request()
        {
            global $json_api, $current_user;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    TokenAuthentication::init($json_api->query->token);
                    $user = $current_user;
                    //User setting change request


                    api_handler::outSuccessDataWeSoft(array(
                        "change_result" => "done"
                    ));

                } else {
                    throw new Exception("module not installed", 1007);
                }
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }

        }


        /**
         * change the user detail
         * User Info Details change request
         * @return array
         */
        public static function changedetail()
        {
            global $json_api, $current_user;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    TokenAuthentication::init($json_api->query->token);
                    $change = new changeUserDetail($json_api->query, $current_user);
                    //  return array("status" => "okay", "result" => "done.");
                    api_handler::outSuccessDataWeSoft($change->get_change_field_results());
                } else {
                    throw new Exception("module not installed", 1007);
                }
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

        /**
         * display the user detail
         * User Info Details (Thumbnails, personal details) List
         * @return array
         */
        public static function display_user()
        {
            {
                global $json_api, $current_user;
                try {
                    // do_action('auth_api_token_check');
                    if (class_exists("json_auth_central")) {
                        json_auth_central::auth_check_token_json();
                        TokenAuthentication::init($json_api->query->token);
                        api_handler::outSuccessDataWeSoft(json_auth_central::display_user_data($current_user, array(
                            "image_thumb" => "",
                            "about" => "",
                            "latest_action" => "",
                            "prefer_language" => get_user_meta($current_user->ID, "language", true)
                        )));

                    } else {
                        throw new Exception("module not installed", 1007);
                    }
                } catch (Exception $e) {
                    api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
                }
            }
        }

        /**
         * add comments for applications or review
         *
         * @return array
         */
        public static function flagcomment()
        {
            global $json_api, $current_user;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    TokenAuthentication::init($json_api->query->token);
                    $user = $current_user;

                    //  return array("status" => "okay", "result" => "done.");
                    $query = $json_api->query;

                    if (!isset($query->comment_id)) throw new Exception("missing comment id", 1001);
                    if (!isset($query->flag)) throw new Exception("missing flag", 1003);

                    api_cms_server::crosscms("flagcomment", array(
                        "comment_id" => $query->comment_id,
                        "flag" => $query->flag,
                    ));



                    api_handler::outSuccess();
                } else {
                    throw new Exception("module not installed", 1007);
                }
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

        /**
         *
         */
        public static function removecomment()
        {
            global $json_api, $current_user;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    $query_ls = $json_api->query;
                    TokenAuthentication::init($query_ls->token);
                    if (!isset($query_ls->comment_id)) throw new Exception("missing comment id", 1051);
                    if (!isset($query_ls->reference_id)) throw new Exception("missing reference id", 1052);
                    $user = $current_user->ID;
                    api_cms_server::crosscms("remove_comment", array(
                        "comment_id" => $query_ls->comment_id,
                        "reference_id" => $query_ls->reference_id,
                        "user" => $user,
                    ));
                    api_handler::outSuccess();
                }
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

        /**
         * add comments for applications or review
         *
         * @return array
         */
        public static function addcomment()
        {
            global $json_api, $current_user;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    json_auth_central::auth_check_token_json();
                    TokenAuthentication::init($json_api->query->token);
                    $user = $current_user;

                    //  return array("status" => "okay", "result" => "done.");
                    $query = $json_api->query;

                    if (!isset($query->objectid)) throw new Exception("missing object id", 1001);
                    if (!isset($query->comment)) throw new Exception("missing comment", 1003);
                    /* api_handler::curl_post(CMS_SERVER . "/api/crosscms/makingcomment",
                         array(
                             "appid" => $query->appid,
                             "userid" => $user->ID,
                             "comment" => $query->comment,
                             "name" => $user->name,
                         )
                     );*/
                    api_cms_server::crosscms("makingcomment", array(
                        "object_id" => $query->objectid,
                        "userid" => $user->ID,
                        "comment" => $query->comment,
                        "name" => $user->name,
                    ), true, true);
                    /*
                                        api_handler::outSuccessDataWeSoft(array(
                                            "change_result" => "done"
                                        ));*/
                    api_handler::outSuccess();
                } else {
                    throw new Exception("module not installed", 1007);
                }
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

        /**
         *
         * get social urls api for app
         */
        public static function get_social_urls()
        {
            global $admin_settings;
            try {
                if (!isset($admin_settings)) {
                    $admin_settings = TitanFramework::getInstance('vcoinset');
                    if (!isset($admin_settings)) throw new Exception("TitanFramework not active", 1030);
                }
                //    $titan = settings_vcoin::get_instance();
                $a1 = array(
                    "fb", "twitter", "gplus"
                );
                $a2 = array(
                    "en", "ja", "cn", "extra"
                );
                $arr = array();
                foreach ($a1 as $d1) {
                    foreach ($a2 as $d2) {
                        $field = $d1 . "_" . $d2;
                        $arr[] = array(
                            "field" => $field,
                            "val" => $admin_settings->getOption($field),
                        );
                    }
                }
                api_handler::outSuccessDataWeSoft($arr);
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

        /**
         * this is the demo sms trigger
         */
        public static function demosms()
        {
            try {
                SMSmd::InitiateSMS(array(
                    "number" => "56923181",
                    "content" => "submission successfully. vcoin has been deducted.  贖回成功 "
                ));
                api_handler::outSuccess();
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());
            }
        }

        /**
         * this is the testing app sdk for login
         */
        public static function test_initiate_app_sdk()
        {
            global $json_api, $current_user, $app_merchan;
            try {
                // do_action('auth_api_token_check');
                if (class_exists("json_auth_central")) {
                    //plugin
                    json_auth_central::auth_check_token_json();
                    TokenAuthentication::init($json_api->query->token);
                    //theme
                    // TokenAuthentication::token_initiate($json_api->query->token);
                    $t = isset($app_merchan) ? "set" : "not set";
                    api_handler::outSuccessDataWeSoft(array(
                        // "appId" => $app_merchan->getappID(),
                        "app_merchan" => $t,
                    ));

                    api_handler::outSuccess();
                }
            } catch (Exception $e) {
                api_handler::outFailWeSoft($e->getCode(), $e->getMessage());

            }
        }
    }
}