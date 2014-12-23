<?php
defined('ABSPATH') || exit;
if (!class_exists('Redemption')) {
    /**
     * Created by PhpStorm.
     * User: Hesk
     * Date: 14年8月29日
     * Time: 上午10:07
     */
    class Redemption
    {
        protected $reward_item_id;
        protected $qr_code;
        protected $terminal_id;
        protected $extra_note;
        protected $redemption_base;
        protected $user_object;
        protected $transaction_result_success;

        public function __construct()
        {
            global $current_user;
            $this->user_object = $current_user;
        }

        /**
         * redemption for coupon code
         * @param $Q
         * @return bool
         * @throws Exception
         */
        public function redeem_coupon($Q)
        {
            global $app_merchan, $wpdb;
            try {
                $uuid = get_user_meta($this->user_object->ID, "uuid_key", true);
                if ($uuid == "") throw new Exception("user uuid is not set", 1088);

                $params = array(
                    "user_id" => $this->user_object->ID,
                    "consumer_id" => $uuid,
                    "couponid" => $Q->couponid
                );
                $data = api_cms_server::crosscms("redeemcoupon", $params, false, false, array(CURLOPT_TIMEOUT => 30));
                $data->message = messagebox::successMessage(77006, $data);

                do_action("on_redeem_coupon", $data);
                $this->transaction_result_success = $data;
            } catch (Exception $e) {
                throw $e;
            }
        }

        /**
         * redemption for rewards
         * @param $Q
         * @return bool
         * @throws Exception
         */
        public function submission($Q)
        {

            try {

                $uuid = get_user_meta($this->user_object->ID, "uuid_key", true);
                if ($uuid == "") throw new Exception("user uuid is not set", 1088);

                $params = array(
                    "user_id" => $this->user_object->ID,
                    "user_uuid" => $uuid,
                    "product_id" => $Q->product_id,
                    "checkdoubles" => $Q->checkdoubles,
                    "offer_expiry_date" => $Q->offer_expiry_date,
                    "price" => $Q->price,
                    "address_id" => $Q->address_id,
                    "distribution" => $Q->distribution,
                    "extension_id" => $Q->extension_id,
                    "lang" => $Q->lang
                );

                inno_log_db::log_vcoin_third_party_app_transaction(-1, 839284, print_r($params, true));
                $data = api_cms_server::crosscms("redeemrewardsubmission", $params, false, false);
                inno_log_db::log_vcoin_third_party_app_transaction(-1, 839285, print_r($data, true));

                /**
                 * record transaction
                 */
                /*
                  $this->transaction_result_success = array(
                        "user" => $this->user_object->ID,
                        "amount" => $money,
                        "qr_a" => $q1,
                        "qr_b" => $q2,
                        "trace_id" => $reference,
                        "handle" => intval($cms_data->redemption_procedure)
                  );
                */

                $data->message = messagebox::successMessage(77005, $data);

                do_action("on_redeem_reward_submission", $data);
                $this->transaction_result_success = $data;

            } catch (Exception $e) {
                throw $e;
            }
        }


        /**
         * @return mixed
         */
        public function get_result()
        {
            return $this->transaction_result_success;
        }

        /**
         * list
         * @param $Q
         * @return bool
         * @throws Exception
         */
        public function listing($Q)
        {
            $conf = (array)$Q;
            $conf["user_id"] = $this->user_object->ID;
            unset($conf["appkey"]);
            unset($conf["token"]);
            $return = api_cms_server::crosscms("get_claim_history_rewards", $conf, true);
            return $return;
        }

        /**
         * @param $Q
         * @throws Exception
         * @return bool
         */
        public function pickup($Q)
        {
            try {
                if (!isset($Q->redemption_procedure)) throw new Exception("redemption_procedure is missing", 10101);
                $procedure = (int)$Q->redemption_procedure;
                if ($procedure == 2) {
                    $data = api_cms_server::crosscms("redeemobtain_user_scan", array(
                        "user" => $this->user_object->ID,
                        "qr" => $Q->qr
                    ), true);
                }
                if ($procedure == 1) {
                    $data = api_cms_server::crosscms("redeemobtain_user_scan", array(
                        "user" => $this->user_object->ID,
                        "qr" => $Q->qr,
                        "note" => $Q->note
                    ), true);
                }
                /**
                 * "user_id" => (int)$Q->user,
                 * "stock_id" => (int)$redeem_record->stock_id,
                 * "claim_time" => $time_now,
                 * "processed_by" => "RESTAURANT",
                 * "address_id" => (int)$stock_count_detail->location_id,
                 * "trace_id" => $redeem_record->trace_id
                 */
                //  $data->message = messagebox::successMessage(77007, $data);
                $this->transaction_result_success = $data;
                do_action("on_reward_claim", $data);
            } catch (ErrorException $e) {
                throw $e;
            } catch (Exception $e) {
                throw $e;
            }
        }

        public function cms_obtain_simple($Q)
        {
            try {
                $data = api_cms_server::crosscms("redeemobtain_vendor_scan", array(
                    "user" => $this->user_object->ID,
                    "step" => $Q->step,
                    "qr" => isset($Q->qr) ? $Q->qr : "",
                    "trace_id" => isset($Q->trace_id) ? $Q->trace_id : ""
                ), false);
                //   $data->message = messagebox::successMessage(77008, $data);
                $this->transaction_result_success = $data;
                do_action("on_reward_claim", $data);
            } catch (ErrorException $e) {
                throw $e;
            } catch (Exception $e) {
                throw $e;
            }
        }

        public function cms_obtain_complex($Q)
        {
            try {
                $data = api_cms_server::crosscms("redeemobtain_vendor_scan_two_step", array(
                    "mac_id" => $Q->mac,
                    "step" => $Q->step,
                    "qr" => isset($Q->qr) ? $Q->qr : "",
                    "trace_id" => isset($Q->trace_id) ? $Q->trace_id : ""
                ), false);
                // $data->message = messagebox::successMessage(77009, $data);
                $this->transaction_result_success = $data;
                if (intval($Q->step) == 2) {
                    do_action("on_reward_claim", $data);
                }
            } catch (ErrorException $e) {
                throw $e;
            } catch (Exception $e) {
                throw $e;
            }
        }
    }
}