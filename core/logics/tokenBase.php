<?php
defined('ABSPATH') || exit;
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年8月6日
 * Time: 下午4:41
 */
if (!class_exists('tokenBase')):
    class tokenBase
    {
        /**
         * initialization of using authentication module under the folder
         * /app/wp-content/plugins/jsonapiauth
         */
        public function __construct()
        {
            add_filter("api_token_authen", array(__CLASS__, "authen"), 10, 1);
            add_filter("token_auth_api_check", array("TokenAuthentication", "get_user_id"), 9, 2);
            add_filter("gen_token_SDK", array(__CLASS__, "api_token_sdk_oauth"), 10, 3);
            add_filter("display_user_data_after_auth", array(__CLASS__, "display_auth_data"), 15, 3);
            // add_action("after_token_verified", array("TokenAuthentication", "init"), 10, 1);
        }

        /**
         * @param $output
         * @param $user_id
         * @param $login_method
         * @return array
         */
        public static function display_auth_data($output, $user_id, $login_method)
        {
            global $wpdb;
            $output["age"] = 0;
            $output["gender"] = "M";
            $output["profile_picture"] = isset($output['avatar']) ? $output['avatar'] : "";
            unset($output['url']);
            unset($output['role']);
            unset($output['avatar']);
            if ($login_method == "generate_auth_token") {
                //$user_ID = $output['user']['id'];
                $expiration = $output['exp'];
                $table = $wpdb->prefix . "app_login_token_banks";
                // $verbose = $wpdb->prepare("SELECT * FROM $table WHERE id=%d AND token=%s", $user_ID, $token);
                // $result = $wpdb->get_row($verbose);
                $newtoken = self::get_new_token($expiration . '.');
                $insert = array(
                    "token" => $newtoken,
                    "exp" => $expiration,
                    "user" => $user_id
                );
                $rs = $wpdb->insert($table, $insert);
                $output['token'] = $newtoken;
                return array("data" => $output);
            } elseif ($login_method == "generate_auth_token_third_party") {
                $output['country'] = array(
                    "ID" => "hk",
                    "name" => "hong kong"
                );
                $output['birthday'] = "";


                return $output;
            } else {

                $output['country'] = array(
                    "ID" => "hk",
                    "name" => "hong kong"
                );

                $output['birthday'] = "2/3/98";
                return $output;
            }
        }


        /**
         * authen for 3rd party
         * @param $user
         * @param $key
         * @param $hash
         * @internal param $user_token
         * @return int
         */
        public static function api_token_sdk_oauth(WP_User $user, $key, $hash)
        {
            global $wpdb;
            $table = $wpdb->prefix . "oauth_api_consumers";
            $verbose = $wpdb->prepare("SELECT * FROM $table WHERE oauthkey=%s", $key);
            $result_r = $wpdb->get_row($verbose);
            if (!$result_r) {
                return -1;
            } else {
                if ($result_r->status == 'dead') {
                    return -3;
                } else if (self::hashMatch($hash, $key, $result_r->secret)) {
                    return self::success_auth_sdk($result_r, $user);
                } else {
                    return -2;
                }
            }
        }

        /**
         * @param $result
         * @param WP_User $user
         * @return string
         */
        private static function success_auth_sdk($result, WP_User $user)
        {
            global $wpdb;
            $table = $wpdb->prefix . "app_login_token_banks";
            $expiration = time() + 1209600;
            // inno_log_db::log_vcoin_error(-1, 19920, "testing i52");
            $token = self::get_new_token($expiration . $result->secret);
            // inno_log_db::log_vcoin_error(-1, 19920, "testing i21");
            $insert = array(
                "consumerid" => $result->id,
                "exp" => $expiration,
                "token" => $token,
                "user" => $user->ID
            );
            $result_of_the_row = $wpdb->insert($table, $insert);
            return $token;
        }

        /**
         * @param $token
         * @return mixed
         * @throws Exception
         */
        public static function authen($token)
        {
            global $wpdb;
            //inno_log_db::log_vcoin_error(-1, 19919, "testing i1");
            $table = $wpdb->prefix . "app_login_token_banks";
            $verbose = $wpdb->prepare("SELECT * FROM $table WHERE token=%s", $token);
            //  $wpdb->select();
            $result_r = $wpdb->get_row($verbose);
            if (!$result_r) throw new Exception("Invalid authentication token. Use the `generate_auth_cookie` Auth API method.", 1001);
            $exp = $result_r->exp;
            // inno_log_db::log_vcoin_error(-1, 19920, "testing i2");
            if ($exp > time()) throw new Exception("Invalid, expired token.", 1002);
            // $verbose_2 = $wpdb->prepare("SELECT * FROM $table WHERE token=%s", $token);

            return $result_r->user;
        }

        /**
         * @param $str
         * @return int
         */
        private static function sha1_64bitInt($str)
        {
            $u = unpack('N2', sha1($str, true));
            return ($u[1] << 32) | $u[2];
        }

        /**
         * @param $hash
         * @param $key
         * @param $secret
         * @return bool
         */
        public static function hashMatch($hash, $key, $secret)
        {
            //inno_log_db::log_vcoin_error(-1, 19921, "testing i3");
            $gen_hash = hash('sha512', $key . $secret);
            inno_log_db::log_vcoin_login(-1, 19922, "check hash: input: " + $hash . " , calculated: " . $gen_hash);
            return $gen_hash == $hash;
        }

        /**
         * @param $str
         * @return string
         */
        private static function get_new_token($str)
        {
            return hash_hmac('ripemd160', $str, LOGGED_IN_SALT);
        }

    }
endif;