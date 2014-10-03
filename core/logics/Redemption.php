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
         * @param $json_api_query
         * @return bool
         * @throws Exception
         */
        public function redeem_coupon($json_api_query)
        {
            global $current_user, $app_merchan, $wpdb;
            try {
                $uuid = get_user_meta($current_user->ID, "uuid_key", true);
                if ($uuid == "") throw new Exception("user uuid is not set", 1088);

                $params = array(
                    "user_id" => $current_user->ID,
                    "consumer_id" => $uuid,
                    "couponid" => $json_api_query->couponid,
                );
                inno_log_db::log_vcoin_third_party_app_transaction(-1, 839284, print_r($params, true));
                $data = api_cms_server::crosscms("redeemcoupon", $params, false, false);
                $this->transaction_result_success = $data;
            } catch (Exception $e) {
                throw $e;
            }
        }

        /**
         * redemption for rewards
         * @param $json_api_query
         * @return bool
         * @throws Exception
         */
        public function submission($json_api_query)
        {
            global $current_user;
            try {

                $uuid = get_user_meta($current_user->ID, "uuid_key", true);
                if ($uuid == "") throw new Exception("user uuid is not set", 1088);
                $params = array(
                    "user_id" => $current_user->ID,
                    "user_uuid" => $uuid,
                    "product_id" => $json_api_query->product_id,
                    "checkdoubles" => $json_api_query->checkdoubles,
                    "offer_expiry_date" => $json_api_query->offer_expiry_date,
                    "price" => $json_api_query->price,
                    "address_id" => $json_api_query->address_id,
                    "distribution" => $json_api_query->distribution,
                    "extension_id" => $json_api_query->extension_id,

                );
                inno_log_db::log_vcoin_third_party_app_transaction(-1, 839284, print_r($params, true));
                $data = api_cms_server::crosscms("redeemrewardsubmission", $params, false, false);
                /**
                 * record transaction
                 */
                /*
                  $this->transaction_result_success = array(
                        "user" => $current_user->ID,
                        "amount" => $money,
                        "qr_a" => $q1,
                        "qr_b" => $q2,
                        "trace_id" => $reference,
                        "handle" => intval($cms_data->redemption_procedure)
                  );
                */
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
         * @param $json_api_query
         * @throws Exception
         */
        public function listing($json_api_query)
        {
            global $current_user;
            $conf = (array)$json_api_query;
            $conf["user_id"] = $current_user->ID;
            unset($conf["appkey"]);
            unset($conf["token"]);
            $return = api_cms_server::crosscms("get_claim_history_rewards", $conf, true);
            return $return;
        }

        public function pickup($json_api_query)
        {

            global $current_user, $app_merchan, $wpdb;
            $transaction_table = $wpdb->prefix . "post_redemption";
            $user = $current_user->ID;
            if (!isset($json_api_query->redemption_procedure)) throw new Exception("query redemption procedure is missing.", 1001);
            if (intval($json_api_query->redemption_procedure) === 91) {
                //do the works for the traditional redemption pick up processes
                if (!isset($json_api_query->step)) throw new Exception("process step is missing.", 1002);
                if (intval($json_api_query->step) == 1) {
                    if (!isset($json_api_query->qr)) throw new Exception("QR code is missing.", 1003);
                    $qr = $json_api_query->qr;
                    $sql = "SELECT * FROM $transaction_table WHERE user=" . $user . " AND (qr_a='" . $qr . "' OR qr_b='" . $qr . "') ";
                    $results = $wpdb->get_row($sql);
                    if (!$results) throw new Exception("This redemption product is not available to you, please try to check out our redemption products first.", 1013);
                    //got the redemption row now
                    $this->transaction_result_success = $results;
                    //end for the first step
                } else if (intval($json_api_query->step) == 2) {
                    if (!isset($json_api_query->trace_id)) throw new Exception("the trace ID is missing.", 1011);
                    $get_first_row = $wpdb->prepare("SELECT * FROM $transaction_table WHERE user=%d AND trace_id=%s", $user, $json_api_query->trace_id);
                    $results = $wpdb->get_row($get_first_row);
                    if (!$results) throw new Exception("redemption failure", 1012);
                    if (intval($results->obtained) == 1) throw new Exception("This redemption has been claimed", 1013);
                    if (Date_Difference::western_time_past_event($results->offer_expiry_date) == 2) throw new Exception("The offer is expired", 1014);
                    //todo: need to build the feature for location trap
                    if ($results->distribution == "DECEN") {
                        //need to verify the location ID
                        $address = $results->address;
                        if (!isset($json_api_query->handle_mac_address)) throw new Exception("the mac address is missing.", 1015);


                    }
                    $this->transaction_result_success = array(
                        "message" => "successfully redeemed."
                    );
                    //end for the second step
                }
            } else if (intval($json_api_query->redemption_procedure) === 92) {
                //do the restaurant flow
                if (!isset($json_api_query->qr)) throw new Exception("the QR code is missing.", 1031);
                if (!isset($json_api_query->note)) throw new Exception("extra note code is missing.", 1032);

                /**
                 * To retrieve the qr data from the cms server
                 */
                $raw = api_handler::curl_get(CMS_SERVER . "/api/stock/get_stock_id_by_qr",
                    array(
                        "qr" => $json_api_query->qr
                    )
                );
                $raw = json_decode($raw);
                $signal = $raw->result == 'success';
                if (!$signal) throw new Exception($raw->msg, $signal);
                $cms_data = $raw->obtain;
                $stock_id = $cms_data->stock_id;

                unset($raw);
                /**
                 * send the note to the device
                 */


                /**
                 * got the stock ID and the count ID
                 */

                $get_first_row = $wpdb->prepare("SELECT * FROM $transaction_table WHERE user=%d AND obtained=0 AND stock_id=%d",
                    $user, intval($stock_id));

                $results = $wpdb->get_row($get_first_row);
                if (!$results) throw new Exception("This redemption product is not available to you, please try to check out our redemption products first.", 1037);


                //update existing row
                $done = $wpdb->update($transaction_table,
                    array(
                        "obtained" => 1,
                        "action_taken_by" => "RESTAURANT",
                        "claim_time" => current_time('timestamp'),
                    ),
                    array("ID" => $results->ID));
                //send data to the push notification


            }
        }
    }
}