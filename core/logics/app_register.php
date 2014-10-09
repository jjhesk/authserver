<?php






/*
include_once(WP_PLUGIN_URL . '/oauth-provider/include/class-wp-oauth-datastore.php');
include_once(WP_PLUGIN_URL . '/oauth-provider/include/class-wp-oauth-datastore.php');
*/
//require_once WP_PLUGIN_URL . '/oauth-provider/include/class-wp-oauth-datastore.php';
defined("ABSPATH") || exit;
if (!class_exists("app_register")) {
    /**
     * Created by PhpStorm.
     * User: Hesk
     * Date: 14年8月20日
     * Time: 上午10:33
     */
    class app_register
    {
        private $datastore;
        private $textdomain;
        private $uuid_vcoin, $uuid_gen, $developer_id, $pendingmeta;

        protected $store_row_data, $app_post_id;

        public function __construct()
        {
            //  $this->datastore = new WP_OAuthDataStore();
            //  $this->server = new WP_OAuthServer($this->datastore);
            //  $hmac_method = new OP_OAuthSignatureMethod_HMAC_SHA1();
            //  $this->uuid_unit = new UUID();
            //  $this->server->add_signature_method($hmac_method);
            $this->textdomain = HKM_LANGUAGE_PACK;
            $this->developer_id = wp_get_current_user()->ID;
        }

        /**
         * the return from the cms server after the first registration.
         * @param $json_apiq
         * @throws Exception
         */
        private function api_registration_actions($json_apiq)
        {


            $return = api_cms_server::crosscms("addnewapp", array(
                "_icon" => $json_apiq->icon,
                "_storeid" => $json_apiq->store_id,
                "_description" => $json_apiq->textdesc,
                "_platform" => $json_apiq->platform,
                "_developer" => $this->developer_id,
                "_appname" => $json_apiq->appname,
                "_vcoin" => $json_apiq->single_vcoin,
                // "_uuid" => $this->uuid_vcoin,
            ), false);
            $this->app_post_id = $return;

            unset($json_apiq);
            unset($return);
        }

        protected $pre_registered_data;

        /**
         * approved by the admin or staff from the vcoin system
         *
         * @param $json_apiq
         * @throws Exception
         * @internal param $developer_id
         * @internal param $store_id
         */
        public function approve_app($json_apiq)
        {
            if (!isset($json_apiq->app_id)) throw new Exception("post_id is not exist", 7099);
            if (!isset($json_apiq->developer)) throw new Exception("developer is not exist", 7098);
            if (!isset($json_apiq->action)) throw new Exception("action is not exist", 7097);
            if (!isset($json_apiq->app_name)) throw new Exception("app_name is not exist", 7095);

            global $wpdb;
            $Table = $wpdb->prefix . "oauth_api_consumers";
            $post_id = $json_apiq->app_id;
            $developer_id = $json_apiq->developer;
            $app_name = $json_apiq->app_name;

            if ($json_apiq->action == "approve") {
                /**
                 * need to look up the row of the data that is store previously on this table
                 */
                $row_pass = $wpdb->get_row($wpdb->prepare("SELECT * FROM $Table WHERE post_id=%d", $post_id));
                if ($row_pass) {
                    $d = explode(":", $row_pass->registration_data);
                    $this->pre_registered_data = array(
                        "total" => intval($d[0]),
                        "each" => intval($d[1])
                    );
                } else {
                    throw new Exception("the row is unfound", 7096);
                }

                /**
                 * step 1.
                 * generate VCoin account UUID
                 */
                $request = api_handler::curl_posts(VCOIN_SERVER . "/api/account/createdev",
                    array(
                        "developer_user_id" => $developer_id,
                        "app_id" => $post_id,
                        "displayname" => $app_name
                    ),
                    array(
                        CURLOPT_TIMEOUT => 30,
                    ));

                $result = json_decode($request);
                $get_uuid = $result->data->accountid;

                if (empty($get_uuid)) throw new Exception("failured to retrieve vcoin server UUID. please check with the vcoin server programmer", 7097);

                /**
                 * adding vcoin for the user
                 */
                if ($this->pre_registered_data["total"] > 0) {
                    api_cms_server::add_vcoin($get_uuid, $this->pre_registered_data["total"]);
                }

                /**
                 * END VCoin account UUID
                 */

                $wpdb->update(
                    $Table,
                    array(
                        "vcoin_account" => $get_uuid,
                        "status" => 'alive'
                    ),
                    array(
                        "post_id" => $post_id,
                        "user" => $developer_id
                    ),
                    array(
                        '%s',
                        '%s'
                    ), array('%d', '%d')
                );
            } else if ($json_apiq->action == "denied") {
                $wpdb->update(
                    $Table,
                    array(
                        "status" => 'denied'
                    ),
                    array(
                        "post_id" => $post_id,
                        "user" => $developer_id
                    ),
                    array(
                        '%s',
                        '%s'
                    ), array('%d', '%d')
                );

            }
            unset($uuid_vcoin);
            unset($post_id);
            unset($developer_id);
            unset($Table);
            unset($get_uuid);
            unset($app_name);
        }

        /**
         * remove the pending app on the auth server
         * @param $json_apiq
         * @throws Exception
         */
        public function remove_app_list($json_apiq, $how)
        {
            global $wpdb;
            if (!isset($json_apiq->post_id)) throw new Exception("post_id is not exist", 7089);
            $post_id = intval($json_apiq->post_id);

            $Table = $wpdb->prefix . "oauth_api_consumers";
            $prepared = $wpdb->prepare("SELECT * FROM $Table WHERE post_id=%d", $post_id);
            $r = $wpdb->get_row($prepared);

            if ($r) {
                if ($how == "pending") $interface = "remove_app_pending";
                if ($how == "dead") $interface = "remove_app_dead";
                if (empty($interface)) throw new Exception("`how` is not defined", 7088);
                api_cms_server::crosscms($how, array("post_id" => $post_id));
                $wpdb->delete($Table, array("post_id" => $post_id), array("post_id" => "%d"));
            } else {
                throw new Exception("the row is not found, post ID:" . $post_id, 7087);
            }
        }


        /**
         * the start of the registration process. submission of the new pending app
         * @param $json_apiq
         * @return mixed
         * @throws Exception
         */
        public function reg($json_apiq)
        {
            try {

                if (!isset($json_apiq->total_vcoin)) throw new Exception("total vcoin - missing", 88197);
                if (!isset($json_apiq->single_vcoin)) throw new Exception("single_vcoin - missing", 88197);
                if (!isset($json_apiq->textdesc)) throw new Exception("textdesc - missing", 88197);
                if (!isset($json_apiq->platform)) throw new Exception("platform - missing", 88197);
                if (!isset($json_apiq->store_id)) throw new Exception("store_id - missing", 88197);
                if (!isset($json_apiq->icon)) throw new Exception("icon - missing", 88197);
                if (!isset($json_apiq->appname)) throw new Exception("appname - missing", 88197);

                $desc = $json_apiq->textdesc;
                $platform = $json_apiq->platform;
                $id = $json_apiq->store_id;
                $icon = $json_apiq->icon;

                $this->pendingmeta = $json_apiq->total_vcoin . ":" . $json_apiq->single_vcoin;
                // add consumer
                $uuid = new UUID();
                $this->uuid_gen = $uuid->v4();

                //inno_log_db::log_vcoin_error(-1, 3232, "consumer");
                if ($this->check_app_exist($id)) throw new Exception("exist application store ID", 4022);
                userBase::deductDeveloperReservedCoins($json_apiq->total_vcoin);
                $this->api_registration_actions($json_apiq);
                $this->addKeys($id, $desc, $platform, $icon, intval($this->app_post_id));
                return $this->store_row_data;

            } catch (Exception $e) {
                throw new Exception ($e->getMessage(), $e->getCode());
            }
            // delete consumer
        }

        /**
         * @param $id
         * @return bool
         * remark: check the existence of register app
         */
        private function check_app_exist($id)
        {
            global $wpdb;
            $table = $wpdb->prefix . "oauth_api_consumers";
            $prepared = $wpdb->prepare("SELECT * FROM $table WHERE name=%s", $id);
            $r = $wpdb->get_row($prepared);
            return $r ? true : false;
        }

        /**
         *  make random key
         * @param $length
         * @param bool $useupper
         * @param bool $usenumbers
         * @param bool $usespecial
         * @return string
         */
        private function str_makerand($length, $useupper = true, $usenumbers = true, $usespecial = false)
        {
            $charset = "abcdefghijklmnopqrstuvwxyz";
            if ($useupper)
                $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            if ($usenumbers)
                $charset .= "0123456789";
            if ($usespecial)
                $charset .= "~@#$%^*()_+-={}|][";
            // Note: using all special characters this reads: "~!@#$%^&*()_+`-={}|\\]?[\":;'><,./";

            $key = '';
            for ($i = 0; $i < $length; $i++) {
                $key .= $charset[mt_rand(0, strlen($charset) - 1)];
            }
            return $key;
        }

        /**
         *  make token key & secret
         * @param $key_type
         * @param int $key_length
         * @param int $secret_length
         * @return array
         */
        private function generate_key($key_type, $key_length = 8, $secret_length = 16)
        {
            $key = $this->str_makerand($key_length);
            $secret = $this->str_makerand($secret_length);
            return array($key, $secret);
        }

        /**
         * @param $id
         * @param $description
         * @param $platform
         * @param $icon
         * @param $post_id
         */
        private function addKeys($id, $description, $platform, $icon, $post_id)
        {

            list($key, $secret) = $this->generate_key('consumer_key');
            global $wpdb, $current_user;
            $table = $wpdb->prefix . "oauth_api_consumers";
            $data_insert = array(
                "name" => $id,
                "post_id" => $post_id,
                "description" => $description,
                "oauthkey" => $key,
                "secret" => $secret,
                "platform" => $platform,
                "user" => $this->developer_id,
                "icon" => $icon,
                // "vcoin_account" => $this->uuid_vcoin,
                "status" => "pending",
                "registration_data" => $this->pendingmeta
                //'alive','dead','pending','removed','rejected'
            );

            $wpdb->insert($table, $data_insert);

            $this->store_row_data = array(
                "key" => $key,
                "secret" => $secret,
                "appid" => $id
            );


        }
    }
}