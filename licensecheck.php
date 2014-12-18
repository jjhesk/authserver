<?php
global $system_script_manager;
function child_create_objects()
{
    global $system_script_manager;
    TitanPanelSetup::setup();
    install_db::reg_hook(__FILE__);
    $system_script_manager = new system_frontend();
    $m1 = new connect_json_api();
    $m2 = new GF_notification();
    $m3 = new gfUserRegistration();
    $m4 = new app_transaction_history();
    $m5 = new application_user_profile();
    $m6 = new tokenBase();
    $m_6 = new vcoinapp_campaig();
    $m7 = new system_log_display();
    $m8 = new app_check_point();
    $m9 = new app_registration();
    $m12 = new app_user_claim();
    $m10 = new dashboard();
    $m11 = new PaymentMembershipSupport();
    $m13 = new EmailTrigger();
    userRegister::user_reg();
    //add_filter("email_activation_label", "email_activation_custom_label", 10, 1);
    $m1 = $m2 = $m13 = $m3 = $m_6 = $m4 = $m5 = $m6 = $m7 = $m8 = $m9 = $m10 = $m11 = $m12 = $system_script_manager = NULL;
    gc_collect_cycles();
}


if (!class_exists("checker_key_pass")):
    class checker_key_pass
    {
        private $url, $message, $order = 0, $limit;

        /**
         *
         */
        public function __construct()
        {
            $this->message = "no error";
            $this->order = 0;

            $this->urls = array(
                "http://farm1.heskhash.com/api/license_check/",
                "http://farms1.heskhash.com/api/license_check/",
                "http://farm65.heskhash.com/api/license_check/",
                "http://farm48.heskhash.com/api/license_check/"
            );

            $this->limit = count($this->urls);
        }

        /**
         * @param $order
         * @return mixed
         */
        private function getUrl($order)
        {
            return $this->url[$order];
        }

        /**
         * @return bool
         * @throws Exception
         */
        private function get_hash()
        {
            try {
                $url = $this->getUrl($this->order);
                $cb = $this->curl_post($url, array(
                    "domain" => $_SERVER["HTTP_HOST"],
                    "key" => KEY_SOURCE
                ));
            } catch (Exception $e) {
                if ($this->limit > $this->order) {
                    $this->order++;
                    $this->get_hash();
                } else {
                    throw $e;
                }
            }
            $object = json_decode($cb);
            if (intval($object->result) > 1) throw new Exception($object->msg, $object->result);
            //    inno_log_db::log_vcoin_email(-1, 10011, "push server has sent the message.");
            return true;
        }

        /**
         * @param $url
         * @param array $post
         * @param array $options
         * @return mixed
         * @throws Exception
         */
        public static function curl_post($url, array $post = NULL, array $options = array())
        {
            $defaults = array(
                CURLOPT_POST => 1,
                CURLOPT_HEADER => 0,
                CURLOPT_URL => $url,
                CURLOPT_FRESH_CONNECT => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_FORBID_REUSE => 1,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_POSTFIELDS => http_build_query($post),
                CURLOPT_SSL_VERIFYPEER => FALSE,
            );
            $ch = curl_init();
            curl_setopt_array($ch, ($options + $defaults));
            if (!$result = curl_exec($ch)) {
                // trigger_error(curl_error($ch));
                // self::outFail(19000 + curl_errno($ch), "CURL-curl_post error: " . curl_error($ch));
                //   inno_log_db::log_login_china_server_info(-1, 955, curl_error($ch), "-");
                throw new Exception(curl_errno($ch), 19000);
            } else
                curl_close($ch);
            return $result;
        }

        /**
         * @return string
         */
        public function get_error_message()
        {
            return $this->message;
        }

        /**
         * @return bool
         */
        public function getresult()
        {
            try {
                return $this->get_hash();
            } catch (Exception $e) {
                $this->message = $e->getMessage();
                return false;
            }
        }

        /**
         * @return checker_key_pass
         */
        public static function checkkey()
        {
            $instance = new self();
            return $instance;
        }
    }
endif;
if (defined("KEY_SOURCE")):
    $instane = checker_key_pass::checkkey();
    if (!$instane->getresult()) {
        die("you need to provide the license key!" . $instane->get_error_message());
    } else {
        add_action('wp_loaded', 'child_create_objects', 11);
        $destinations = NULL;
    }
else:
    die("please define the KEY_SOURCE!");
    exit;
endif;