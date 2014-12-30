<?php
/**
 * Created by .
 * User: Hesk
 * Date: 14年8月20日
 * Time: 上午10:33
 * use by the developer only
 */
defined("ABSPATH") || exit;
if (!class_exists("app_register")) {
    class app_register
    {
        private $datastore;
        private $textdomain;
        private $uuid_vcoin, $uuid_gen, $developer_id, $pendingmeta, $developer_name;
        protected $store_row_data, $app_post_id;
        protected $table_old, $table_new, $db;
        protected $role, $settings, $no_cms_standalone;


        public function __construct()
        {
            global $wpdb, $current_user;
            $this->textdomain = HKM_LANGUAGE_PACK;
            $user = wp_get_current_user();
            if ($user->roles[0] == "developer") {
                $this->developer_id = wp_get_current_user()->ID;
                $this->developer_name = wp_get_current_user()->user_login;
            }
            $this->db = $wpdb;
            $this->table_new = $this->db->prefix . "post_app_registration";
            $this->role = $current_user->roles[0];
            $this->settings = TitanFramework::getInstance('vcoinset');
            $this->no_cms_standalone = $this->settings->getOption("cms_connect");
        }

        public function __destruct()
        {
            $this->settings = NULL;
            $this->db = NULL;
            $this->role = NULL;
            $this->table_new = NULL;
            $this->table_old = NULL;
            $this->developer_name = NULL;
            $this->developer_id = NULL;
            $this->textdomain = NULL;
        }

        /**
         * this action is taken by the administrator
         * the return from the cms server after the first registration.
         * @param $app_meta
         */
        private function first_approve_app($app_meta)
        {
            $this->developer_id = $app_meta->devuser;
            $user = new WP_User($this->developer_id);
            $_developer_name = $user->display_name;
            if (!$this->no_cms_standalone) {
                $return = api_cms_server::crosscms("addnewapp", array(
                    "_icon" => $app_meta->icon,
                    "_storeid" => $app_meta->store_id,
                    "_description" => $app_meta->description,
                    "_platform" => $app_meta->platform,
                    "_developer" => $this->developer_id,
                    "_appname" => $app_meta->app_title,
                    "_appkey" => $app_meta->app_key,
                    "_vcoin" => $app_meta->payout,
                    "_developer_name" => $_developer_name,
                    "_screen_shot" => $app_meta->image_urls
                ), false);
            } else {
                $return = -1;
            }
            $this->app_post_id = $return;
            $return = NULL;
            $user = NULL;
            $_developer_name = NULL;
        }

        /**
         *
         * @param $post_id
         */
        private function unlist_cms_app($post_id)
        {
            if (!$this->no_cms_standalone) {
                $return = api_cms_server::crosscms("change_app_status_alive_to_dead", array(
                    "post_id" => $post_id,
                    "status" => "pending"
                ), false);
                $this->app_post_id = $return;
            } else {
                $this->app_post_id = -1;
            }
        }

        protected $pre_registered_data;

        private function change_status($status_new, $uuid_vcoin, $post_id = 0, $ID)
        {
            $t = array(
                'vcoin_account' => $uuid_vcoin,
                'status' => $status_new
            );
            if ($post_id > 0) {
                $t['post_id'] = $post_id;
            }
            $this->db->update(
                $this->table_new,
                $t,
                array(
                    'ID' => $ID
                ),
                array(
                    '%s',
                    '%s'
                ),
                array(
                    '%d'
                )
            );
        }

        private function developer_launch_app($rID)
        {

            $this->db->update(
                $this->table_new,
                array(
                    'status' => 'launched',
                    'deposit' => 0
                ),
                array(
                    'ID' => (int)$rID
                ),
                array(
                    '%s',
                    '%d'
                ),
                array(
                    '%d'
                )
            );
        }

        /**
         * this is version
         * @param $request
         * @throws Exception
         */
        public function action_on_status_change($request)
        {
            if (!isset($request->action)) throw new Exception("action is not exist", 7097);
            if (!isset($request->id)) throw new Exception("id is not exist", 7095);
            //if (!isset($request->app_key)) throw new Exception("app_key is not exist", 7098);

            try {
                $R = $this->db->get_row($this->db->prepare("SELECT * FROM $this->table_new WHERE ID=%d", intval($request->id)));

                if (!$R)
                    throw new Exception("the row is unfound", 7096);

                if ($this->role == "administrator") {
                    if (strtolower($request->action) == "approve") {
                        if ($this->settings->getOption("feature_beta")) {
                            $this->developer_id = $R->devuser;
                            $beta_coin = (int)$this->settings->getOption("app_coin_beta");
                            // $user = new WP_User($this->developer_id);
                            /**
                             * according to the email from jerry: after approve the application -> (status = beta) + 1000 vcoin
                             */

                            /**
                             * create vcoin account for the app
                             */
                            $uuid_vcoin = $this->create_vcoin_account_app($request->id, $R->app_title, $R->app_key);
                            /**
                             * adding vcoin for the user
                             * 1000 is set from the email disucssion
                             */
                            api_cms_server::add_vcoin($uuid_vcoin, $beta_coin, "Beta Testing Coin Preparation");
                            // inno_log_db::log_vcoin_third_party_app_transaction(-1, 100010, "api_cms_server::add_vcoin($uuid_vcoin, 1000);");
                            /**
                             * convert values in to the proper keys
                             */

                            /**
                             * send data to CMS server
                             */
                            $this->first_approve_app($R);
                            $post_id = $this->app_post_id;
                            inno_log_db::log_vcoin_third_party_app_transaction(-1, 100010, "first_approve_app::post_id($post_id);");
                            /**
                             * with the return post ID from the CMS server
                             */
                            $this->change_status("beta", $uuid_vcoin, $post_id, $R->ID);
                            unset($uuid_vcoin);
                            unset($post_id);
                            unset($R);
                        } else {
                            $uuid_vcoin = $this->create_vcoin_account_app($request->id, $R->app_title, $R->app_key);
                            api_cms_server::add_vcoin($uuid_vcoin, (int)$this->settings->getOption("app_coin_new_dev"), "Launch APP Coin Preparation");
                            $this->change_status("launched", $uuid_vcoin, 0, $R->ID);
                        }
                    }
                } else if ($this->role == "developer") {
                    if (strtolower($request->action) == "finallyalive") {
                        if ($this->settings->getOption("feature_beta")) {
                            /**
                             * you need to go ask we soft: david to give u the API
                             *
                             * At testing stage, the system give certain amount of (testing) VCoin (say 1000) into this VCoin account of this app for test.
                             * the system will assign an testing "app user account" for testing move coin.
                             * This testing user account have restriction. It can't be used to redemption. (cannot be reduced coin). And have expiry time. (say 1month)
                             * After 1 month and/or at the time of successful changing status to launch,
                             * - testing VCoin remained in VCoin account of this app ----> transfer back to imusic VCoin Account
                             * - testing app user account removed
                             * - Transferred VCoin inside VCoin account of testing app user ----> transfer back to imusic VCoin Account
                             *
                             *
                             * todo: to reverse the coin number from left amount to ZERO
                             *
                             */
                            if (!isset($R->vcoin_account) || $R->vcoin_account == "") {
                                throw new Exception("vcoin uuid is not found.", 78493);
                            }


                            /**
                             * get the balance of the current account of the application
                             */
                            $coinscountdata = api_cms_server::vcoin_account("balance", array("accountid" => $R->vcoin_account));
                            // throw new Exception("injection |" . $coinscountdata->coinscount . "|" . $this->settings->getOption("imusic_uuid"), 78493);
                            /**
                             * testing VCoin remained in VCoin account of this app ----> transfer back to imusic VCoin Account
                             *
                             * $coin = new vcoinBase();
                             * $coin->setSender($R->vcoin_account);
                             * $coin->setReceive($this->settings->getOption("imusic_uuid"));
                             * $coin->setAmount((int)$coinscountdata->coinscount);
                             * $coin->setTransactionReference("Launch App");
                             * $coin->CommitTransaction();
                             * $coin = NULL;
                             */
                            if (!$this->no_cms_standalone) {
                                //share same condition with dead-to-alive
                                api_cms_server::crosscms("change_beta_to_launched", array("post_id" => (int)$R->post_id), array(
                                    CURLOPT_TIMEOUT => 30
                                ));
                            }
                            /**
                             * adding the coin back from the original setting of the deposit
                             */
                            if (intval($R->deposit) > 0) {
                                api_cms_server::add_vcoin($R->vcoin_account, (int)$R->deposit, "Launch App");
                            }
                            $this->developer_launch_app($R->ID);
                        }
                    }

                } else {
                    throw new Exception("you need a proper permission to make this action", 9011101);
                }
                /**
                 * since there is a remove row on the disapproval. there is no need to change the status to "denied"
                 **/
                /*
                if (strtolower($request->action) == "denied") {
                        $this->db->update(
                            $this->table_new,
                            array(
                                "status" => 'denied'
                            ),
                            array(
                                "id" => $post_id,
                                "user" => $this->developer_id
                            ),
                            array(
                                '%s',
                                '%s'
                            ), array('%d', '%d')
                        );
                    }
                */
            } catch (Exception $e) {
                throw $e;
            }
        }

        /**
         * @param $_id
         * @param $_name
         * @param $_appkey
         * @throws Exception
         * @return mixed
         */
        private function create_vcoin_account_app($_id, $_name, $_appkey)
        {
            /**
             * step 1.
             * generate VCoin account UUID
             */

            //to create unique reference code pass to vcoin server
            $ref_code = $_id . ":" . $_appkey;

            if (!isset($this->developer_id)) throw new Exception("developer ID is not set", 7792);

            $req = array(
                "developer_user_id" => $this->developer_id,
                "app_id" => $_id,
                "displayname" => $ref_code
            );

            $request = api_handler::curl_posts(VCOIN_SERVER . "/api/account/createdev", $req,
                array(
                    CURLOPT_TIMEOUT => 30,
                ));

            $result = json_decode($request);
            $get_uuid = $result->data->accountid;
            if (empty($get_uuid)) throw new Exception("Failured to retrieve vcoin server UUID. please check with the vcoin server programmer", 7797);
            $result = $request = NULL;
            return $get_uuid;
        }


        private function testing_server_initalization()
        {
            $secret = $this->store_row_data["app_secret"];
            $prepared = $this->db->prepare("SELECT * FROM $this->table_new WHERE app_secret=%s", $secret);
            $R = $this->db->get_row($prepared);
            $uuid_vcoin = $this->create_vcoin_account_app($R->ID, $R->app_title, $R->app_key);
            $this->db->update($this->table_new, array("status" => "launched", "vcoin_account" => $uuid_vcoin, "deposit" => 0), array("ID" => $R->ID), array('%s', '%s', '%d'), array('%d'));
            $this->first_approve_app($R);
            api_cms_server::add_vcoin($uuid_vcoin, (int)$R->deposit, "Launch APP in Testing Server");
        }

        public function change_app_status($Q, $how)
        {

            if (!isset($Q->post_id)) throw new Exception("post_id is not exist", 7089);
            $post_id = (int)$Q->post_id;

            // $Table = $wpdb->prefix . "post_app_registration";
            $prepared = $this->db->prepare("SELECT * FROM $this->table_new WHERE post_id=%d", $post_id);
            $R = $this->db->get_row($prepared);

            if ($R) {
                if ($how == "alive_to_dead") {
                    $interface = "change_app_status_alive_to_dead";
                    $app_status = "dead";
                }
                if ($how == "dead_to_alive") {
                    $interface = "change_app_status_dead_to_alive";
                    $app_status = "launched";
                }
                if (empty($interface)) throw new Exception("`how` is not defined", 7088);
                if (!$this->no_cms_standalone) {
                    api_cms_server::crosscms($interface, array("post_id" => $post_id), array(
                        CURLOPT_TIMEOUT => 30
                    ));
                }
                $this->db->update($this->table_new,
                    array("status" => $app_status),
                    array("post_id" => $post_id),
                    array("%s"),
                    array("%d")
                );
            } else {
                throw new Exception("the row is not found, post ID:" . $post_id, 7087);
            }
        }

        /**
         * reverse account balance to the developer
         * @param $R
         * @throws Exception
         */
        private function account_balance_reverse_zero($R)
        {
            try {

                $app_key = $R->app_key;
                $app_title = $R->app_title;
                $developerUser = $R->devuser;
                $uuid = $R->vcoin_account;
                /**
                 * account balance adjustments
                 *
                 */
                if ($R->status == "pending") {
                    $deposit_recover = ($this->settings->getOption("feature_beta")) ? (int)$R->deposit + (int)$this->settings->getOption("app_coin_beta") : (int)$R->deposit;
                    userBase::addDeveloperIDCoin($R->devuser, $deposit_recover);

                } else {

                    /**
                     * get the balance of the current account of the application
                     */
                    $coinscountdata = api_cms_server::vcoin_account("balance", array("accountid" => $uuid));
                    $left_amount = (int)$coinscountdata->coinscount;

                    /**
                     * testing VCoin remained in VCoin account of this app ----> transfer back to imusic VCoin Account
                     */
                    if ($left_amount > 0) {
                        $coin = new vcoinBase();
                        $coin->setSender($uuid);
                        $coin->setReceive($this->settings->getOption("imusic_uuid"));
                        $coin->setAmount($left_amount);
                        $coin->setTransactionReference("close fill zero");
                        $coin->CommitTransaction();
                        userBase::addDeveloperIDCoin($R->devuser, $left_amount);
                        $coin = NULL;
                    }

                }


            } catch (Exception $e) {
                throw $e;
            }
        }

        /**
         * remove the pending app on the auth server
         * @param $Q
         * @throws Exception
         * @internal param $how
         */
        public function remove_app_list($Q)
        {
            if (!isset($Q->id)) throw new Exception("id is not exist", 7089);
            $app_id = intval($Q->id);
            $prepared = $this->db->prepare("SELECT * FROM $this->table_new  WHERE ID=%d", $app_id);
            $R = $this->db->get_row($prepared);
            if (isset($this->developer_id)) {

            } else {
                if ($this->settings->getOption("admin_email_reg_nfn")) {
                    if (!isset($Q->written)) throw new Exception("written reasons are not exist", 7087);
                    if (!isset($Q->choice)) throw new Exception("the choices are not exist", 7086);
                    $developer = new WP_User($R->devuser);
                    $msg = messagebox::getEmailTemplate("email_deny_app_reg", array(
                        "username" => userBase::getName($R->devuser),
                        "useremail" => $developer->user_email,
                        "reason" => $Q->written,
                        "reason_extra" => $Q->choice,
                        "date" => date("Ymd"),
                        "app_title" => $R->app_title,
                        "store_id" => $R->store_id,
                        "platform" => $R->platform
                    ));
                    $headers = 'From: VcoinSys <admin@vcoinapp.com>' . "\r\n";
                    $from = get_bloginfo("admin_email");
                    wp_mail($from, 'vcoin platform application rejections', $msg, $headers);
                }
            }
            $this->account_balance_reverse_zero($R);
            if ($R) {
                $this->db->delete($this->table_new, array("id" => $app_id), array("id" => "%d"));
            } else {
                throw new Exception("the row is not found, app ID:" . $app_id, 7087);
            }
        }

        /**
         * remove the dead app on both the auth and cms server
         * @param $Q
         * @throws Exception
         * @internal param $how
         */
        public function remove_dead_app_list($Q)
        {

            if (!isset($Q->post_id)) throw new Exception("post id is not exist", 7089);
            $post_id = intval($Q->post_id);

            $prepared = $this->db->prepare("SELECT * FROM $this->table_new WHERE post_id=%d", $post_id);
            $r = $this->db->get_row($prepared);

            if ($r) {
                if (!$this->no_cms_standalone) {
                    api_cms_server::crosscms("remove_app_dead", array("post_id" => $post_id), array(
                        CURLOPT_TIMEOUT => 30
                    ));
                }
                $this->db->delete($this->table_new, array("post_id" => $post_id), array("post_id" => "%d"));
            } else {
                throw new Exception("the row is not found, post ID:" . $post_id, 7087);
            }
        }


        /**
         * the start of the registration process. submission of the new pending app
         * @param $Q
         * @return mixed
         * @throws Exception
         */
        public function reg($Q)
        {
            try {
                if (!isset($Q->total_vcoin)) throw new Exception("total vcoin - missing", 88197);
                if (!isset($Q->single_vcoin)) throw new Exception("single_vcoin - missing", 88197);
                if (!isset($Q->textdesc)) throw new Exception("textdesc - missing", 88197);
                if (!isset($Q->platform)) throw new Exception("platform - missing", 88197);
                if (!isset($Q->store_id)) throw new Exception("store_id - missing", 88197);
                if (!isset($Q->icon)) throw new Exception("icon - missing", 88197);
                if (!isset($Q->appname)) throw new Exception("appname - missing", 88197);
                if (!isset($Q->images)) throw new Exception("images - missing", 88198);

                $desc = $Q->textdesc;
                $platform = $Q->platform;
                $id = $Q->store_id;
                $icon = $Q->icon;
                $app_name = $Q->appname;
                $total_vcoin = $Q->total_vcoin;
                $single_vcoin = $Q->single_vcoin;

                // add consumer
                $uuid = new UUID();
                $this->uuid_gen = $uuid->v4();

                //inno_log_db::log_vcoin_error(-1, 3232, "consumer");
                if ($this->check_app_exist($id)) throw new Exception("exist application store ID", 4022);
                userBase::deductDeveloperReservedCoins($total_vcoin);
                //$this->api_registration_actions($Q);
                $this->addKeys($id, $desc, $platform, $icon, $this->app_post_id, $app_name, $total_vcoin, $single_vcoin, $Q->images);
                if (!$this->settings->getOption("feature_beta"))
                    $this->testing_server_initalization();

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
            $prepared = $this->db->prepare("SELECT * FROM $this->table_new WHERE store_id=%s", $id);
            $r = $this->db->get_row($prepared);
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
            // Note: using all special characters this reads: "~!@#$%^&*()_+`-={}|\\]?[\":;"><,./";

            $key = "";
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
         * @param $app_name
         * @param $deposit
         * @param $payout
         */
        private function addKeys($id, $description, $platform, $icon, $post_id, $app_name, $deposit, $payout, $images)
        {

            list($key, $secret) = $this->generate_key("consumer_key");
            global $current_user;
            $deposit_post = ($this->settings->getOption("feature_beta")) ? (int)$deposit - (int)$this->settings->getOption("app_coin_beta") : (int)$deposit;
            $data_insert = array(
                //"ID" => $id,
                "post_id" => (int)$post_id,
                "description" => stripslashes($description),
                //"oauthkey" => $key,
                "app_key" => $key,
                "app_secret" => $secret,
                "platform" => $platform,
                "devuser" => $this->developer_id,
                "devname" => $this->developer_name,
                "icon" => stripslashes($icon),
                "store_id" => stripslashes($id),
                // "vcoin_account" => $this->uuid_vcoin,
                "status" => "pending",
                //"registration_data" => $this->pendingmeta,
                "deposit" => $deposit_post,
                "payout" => (int)$payout,
                "app_title" => stripslashes($app_name),
                "image_urls" => stripslashes($images)
                //"alive","dead","pending","removed","rejected"
            );

            $this->db->insert($this->table_new, $data_insert);

            $this->store_row_data = array(
                "app_key" => $key,
                "app_secret" => $secret,
                "appid" => $id
            );


        }

        public function update_app($Q)
        {
            if (!isset($Q->ID)) throw new Exception("app id is missing", 9703);
            if (!isset($Q->app_title)) throw new Exception("app name is missing", 9704);
            if (!isset($Q->icon)) throw new Exception("app id is missing", 9705);
            if (!isset($Q->description)) throw new Exception("description is missing", 9706);
            if (!isset($Q->image_urls)) throw new Exception("image url is missing", 9707);
            if (!isset($Q->store_id)) throw new Exception("image url is missing", 9708);

            $this->db->update($this->table_new,
                array(
                    "app_title" => stripslashes($Q->app_title),
                    "icon" => stripslashes($Q->icon),
                    "description" => stripslashes($Q->description),
                    "image_urls" => stripslashes($Q->image_urls),
                    "store_id" => stripslashes($Q->store_id)
                ),
                array("ID" => (int)$Q->ID),
                array("%s", "%s", "%s", "%s", "%s"),
                array("%d")
            );
        }
    }
}