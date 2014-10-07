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
        private $uuid_vcoin, $uuid_gen, $developer_id;

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
         *
         * @param $query
         * @throws Exception
         */
        private function api_registration_actions($query)
        {

            /**
             * step 1.
             * generate VCoin account UUID
             */
            $get_uuid = api_handler::curl_post(VCOIN_SERVER . '/api/account/createdev', array(
                "developer_user_id" => $this->developer_id,
                "app_id" => $query->store_id
            ));
            $get_uuid = json_decode($get_uuid);
            if (intval($get_uuid->result) > 0) throw new Exception($get_uuid->msg, $get_uuid->result);
            //Create the new Key for this account
            $this->uuid_vcoin = isset($get_uuid->data->accountid) ? $get_uuid->data->accountid : "";
            //self::withUpdateFieldN($post_id, 'uuid_key', $get_uuid->data->accountid);
            /**
             * END VCoin account UUID
             */
            $return = api_cms_server::crosscms("addnewapp", array(
                "_icon" => $query->icon,
                "_storeid" => $query->store_id,
                "_description" => $query->textdesc,
                "_platform" => $query->platform,
                "_developer" => $this->developer_id,
                "_appname" => $query->appname,
                "_uuid" => $this->uuid_vcoin,
            ), false);


            $this->app_post_id = $return;

        }

        /**
         * the start of the registration process
         * @param $query
         * @return mixed
         * @throws Exception
         */
        public function reg($query)
        {

            $desc = $query->textdesc;
            $platform = $query->platform;
            $id = $query->store_id;
            $icon = $query->icon;
            // add consumer
            $uuid = new UUID();
            $this->uuid_gen = $uuid->v4();
            try {
                //inno_log_db::log_vcoin_error(-1, 3232, "consumer");
                if ($this->check_app_exist($id)) throw new Exception("exist application store ID", 4022);
                $this->api_registration_actions($query);
                $this->addKeys($id, $desc, $platform, $icon, intval($this->app_post_id));
                return $this->store_row_data;
            } catch (Exception $e) {
                //$note .= "<strong>" . __('Failure!!', $this->textdomain) . "</strong>: " . __($e->getMessage(), $this->textdomain);
                //$error++;
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
            global $wpdb;
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
                "vcoin_account" => $this->uuid_vcoin,
                "status" => "dead",
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