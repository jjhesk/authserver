<?php
defined("ABSPATH") || exit;
if (!class_exists("actionBaseWatcher")) {
    /**
     *
     *
     *
     *
     * Created by PhpStorm.
     * User: Hesk
     * Date: 14年8月27日
     * Time: 下午12:40
     */
    class actionBaseWatcher
    {
        protected $action_item;
        protected $table;
        protected $initial_result;
        protected $time_start, $time_end;
        protected $f, $cycle_hours, $cycle_hours_in_seconds, $app_key;
        protected $reward_coins, $reward_action_id;
        protected $transaction_reference;
        protected $main_api_query;
        protected $occurrence;
        protected $reward_status;
        protected $requires_obj_id;
        protected $_obj_id;

        public function __construct($query)
        {
            global $wpdb;

            $this->main_api_query = $query;
            $this->table = $wpdb->prefix . "action_reward";
        }

        private function get_rules()
        {
            $this->reward_action_id = $this->main_api_query->aid;
            $this->cycle_hours = intval(get_post_meta($this->reward_action_id, "cycle_reward", true));
            $this->cycle_hours_in_seconds = ($this->cycle_hours) * 3600;
            $this->f = intval(get_post_meta($this->reward_action_id, "delta_f", true));
            $this->app_key = get_post_meta($this->reward_action_id, "sdk_app_key", true);
            $this->occurrence = get_post_meta($this->reward_action_id, "occurrence", true);
            $this->reward_coins = intval(get_post_meta($this->reward_action_id, "reward_coins", true));
            $this->requires_obj_id = intval(get_post_meta($this->reward_action_id, "requires_obj_id", true));

            return $this;
        }


        /**
         * @return array
         */
        private function get_time_range()
        {
            $d = new DateTime();
            $present_timestamp = $d->getTimestamp();

            /*while ($start_time < ($present_timestamp - $cycle_hours_in_seconds))
                $start_time += $cycle_hours_in_seconds;*/

            $start_time = $present_timestamp - ($present_timestamp % $this->cycle_hours_in_seconds);

            return array($start_time, $present_timestamp);
        }

        /**
         *
         * @param WP_User $user
         * @internal param $reward_action_id
         * @return $this
         */
        private function apply_rules(WP_User $user)
        {
            //$rules = apply_filters("mission_extra_rules_sql", "");

            inno_log_db::log_vcoin_third_party_app_transaction($user->ID,
                10200,
                "rules: f:" . $this->f . " reward id: " . $this->reward_action_id);

            if ($this->reward_status == 1) {
                $this->trigger_reward();
            }
            return $this;
        }

        /**
         * requires
         * 1. validated user vcoin account ID
         * 2. validated merchant account ID
         * 3. validated stock account vcoin ID
         *
         * developer - gain coins from the download apps and open the sdk app with login to gain coin
         * merchant  - requires additiona stock_id
         * @throws Exception
         */
        private function trigger_reward()
        {
            global $app_merchan, $current_user;

            if (!isset($app_merchan))
                throw new Exception("Not initiated", 1021);

            if ($this->app_key == "imusictech")
                $credit_account_id = IMUSIC_UUID;
            else if ($this->app_key == "developer") {
                $credit_account_id = $app_merchan->getVcoinId();
                $this->_obj_id = $app_merchan->getPostID();
            } else if ($this->app_key == "merchant") {
                //check if the query contains stock_id
                if (!isset($this->main_api_query->stock_id)) throw new Exception("stock_id is not defined", 1024);
                $this->_obj_id = $this->main_api_query->stock_id;
                $merchant = new Merchant();
                $credit_account_id = $merchant->get_merchant_vcoin_id($this->main_api_query->stock_id);
            } else {
                throw new Exception("reward coin nature is not defined, No Vcoin Account", 1022);
            }

            if ($credit_account_id == "")
                throw new Exception("No Vcoin Account", 1023);

            $debit_user_vcoin_account_id = userBase::getVal($current_user->ID, "uuid_key");
            if ($debit_user_vcoin_account_id == "") throw new Exception("user vcoin account is missing.", 1020);

            /**
             * carry out the vcion transaction in here now
             */
            $coin_operation = new vcoinBase();
            $coin_operation
                ->setAmount($this->reward_coins)
                ->setReceive($debit_user_vcoin_account_id)
                ->setSender($credit_account_id)
                ->setTransactionReference("mission_trigger_" . $this->reward_action_id)
                ->CommitTransaction();

            $this->transaction_reference = $coin_operation->get_tranaction_reference();
            if ($this->transaction_reference != "") {
                inno_log_db::log_vcoin_third_party_app_transaction($current_user->ID, 10205, "move coin success:" . $this->transaction_reference);
            }

        }

        /**
         * @param WP_User $user
         * @return bool
         * return true if is rewarded
         */
        private function check_reward_status(WP_User $user)
        {
            global $wpdb;

            $table = $wpdb->prefix . "action_reward";
            $rules = "";

            if ($this->occurrence == "once") {
                $query = $wpdb->prepare("SELECT
            COUNT(*) FROM $table WHERE
            user=%d AND action=%d $rules",
                    $user->ID, $this->reward_action_id);
            } else {
                list($time_start, $time_end) = $this->get_time_range();
                if (isset($this->_obj_id)) {
                    $query = $wpdb->prepare("SELECT
            COUNT(*) FROM $table WHERE
            user=%d AND action=%d $rules AND
            UNIX_TIMESTAMP(triggered) BETWEEN (%d) and (%d) AND object_id=%d",
                        $user->ID, $this->reward_action_id, $time_start, $time_end, intval($this->_obj_id));
                } else {
                    $query = $wpdb->prepare("SELECT
            COUNT(*) FROM $table WHERE
            user=%d AND action=%d $rules AND
            UNIX_TIMESTAMP(triggered) BETWEEN (%d) and (%d)",
                        $user->ID, $this->reward_action_id, $time_start, $time_end);
                }
            }

            $number = intval($wpdb->get_var($query));

            if ($this->occurrence == "repeat_continuous") {
                if ($number == 0) {

                    $query = $wpdb->prepare("SELECT MAX(ID) FROM $this->table WHERE
                 rewarded=%d AND user=%d AND action=%d", 1, $user->ID, $this->reward_action_id);

                    $latest_rewarded_id = $wpdb->get_var($query);

                    $query = $wpdb->prepare("SELECT UNIX_TIMESTAMP(triggered) FROM $this->table WHERE ID>
                %d AND user=%d AND action=%d ORDER BY ID DESC",
                        $latest_rewarded_id, $user->ID, $this->reward_action_id);

                    $results = $wpdb->get_results($query);

                    $interval_start_time = $time_start - $this->cycle_hours_in_seconds;
                    $interval_end_time = $time_start;
                    $count_continuous = 0;

                    if (sizeof($results) > 0) {
                        foreach ($results as $row) {
                            foreach ($row as $timestamp) {
                                /*                            inno_log_db::log_vcoin_third_party_app_transaction(-1, 44444,
                                                                print_r("interval_start: " . $interval_start_time .
                                                                    " , " . $timestamp . " , "
                                                                    . "interval_end: " . $interval_end_time, true));*/
                                if ($timestamp > $interval_start_time && $timestamp <= $interval_end_time) {
                                    $count_continuous++;
                                    $interval_start_time -= $this->cycle_hours_in_seconds;
                                    $interval_end_time -= $this->cycle_hours_in_seconds;
                                } else break 2;
                            }
                        }
                        if ($count_continuous === ($this->f) - 1) {
                            $this->reward_status = 1;
                        }
                    }
                    return false;
                } else return true;
            } else {
                if ($number === $this->f) {
                    return true;
                } else if ($number === ($this->f) - 1) {
                    $this->reward_status = 1;
                    return false;
                } else return false;
            }
        }


        /**
         * the main gateway to track and apply rule set for each requested user and sdk
         * @param WP_User $user
         * @return bool
         * @throws Exception
         */
        public function record(WP_User $user)
        {
            global $wpdb;

            if (!isset($this->main_api_query->aid)) throw new Exception("missing action id", 1001);
            $this->reward_action_id = $this->main_api_query->aid;

            $this->get_rules();
            if (get_post_type($this->reward_action_id) != HKM_ACTION) throw new Exception("the request action Point does not exist", 1011);
            if (get_post_status($this->reward_action_id) != 'publish') throw new Exception("The action point is not ready", 1012);
            if ($this->app_key == -1) throw new Exception("SDK App Key is not selected", 1013);
            if ($this->occurrence == -1) throw new Exception("Occurrence is not selected", 1015);
            if ($this->f < 2) throw new Exception("Frequency should be more than 1 for both and
         repeatable simple and repeatable continuous", 1016);

            $table = $wpdb->prefix . "action_reward";
            $current_time = current_time('mysql', 0);

            $check_reward_status = $this->check_reward_status($user);

            if (!$check_reward_status) {
                $data = array(
                    "user" => $user->ID,
                    "action" => $this->reward_action_id,
                    "reference" => $this->app_key,
                    "triggered" => $current_time,
                    "rewarded" => isset($this->reward_status) ? $this->reward_status : 0,
                    "mission_type" => $this->f,
                    "object_id" => isset($this->_obj_id) ? $this->_obj_id : -1,
                );
                $result = $wpdb->insert($table, $data);
                if (!$result) throw new Exception("insert not working", 1010);
            } else {
                if ($this->occurrence == "repeat_continuous")
                    throw new Exception("repeated trigger is not allowed within an interval", 1015);
                else throw new Exception("reward have been gained", 1014);
            }


            $this->apply_rules($user, $this->reward_action_id);

            return true;
        }

        public function reward_result()
        {
            if ($this->transaction_reference != "") {
                return array(
                    "coin" => $this->reward_coins,
                    "trace_id" => $this->transaction_reference
                );
            } else throw new Exception("no reward to gain and the action is recorded.", 1010);
        }

        public function run_test()
        {
            global $wpdb, $current_user;
            $reward_action_id = 43;
            $wpdb->get_results("TRUNCATE TABLE vapp_action_reward");
            for ($i = 0; $i < 10; $i++) {
                $table = $wpdb->prefix . "action_reward";
                $data = array(
                    "user" => $current_user->ID,
                    "action" => $reward_action_id,
                    "reference" => "core"
                );
                $result = $wpdb->insert($table, $data);
                $this
                    ->get_rules($reward_action_id)
                    ->apply_rules($current_user, $reward_action_id);
            }
            return true;
        }

        /**
         * input: user Id
         *  output : a list of completed action that has been done in the history
         *  by date desc
         *  1 - unlimited
         *
         *
         * action name | done time
         *
         * @param $reward_action_id
         * @return mixed
         */
        public function get_action_list($reward_action_id)
        {
            global $wpdb;
            $table = $wpdb->prefix . "action_reward";
            $dl = $wpdb->prepare("SELECT * FROM $table WHERE action=%d",
                $reward_action_id);
            return $wpdb->get_results($dl);
        }


        public function get_checkpoint_data()
        {
            global $wpdb;

            if (!isset($this->main_api_query->aid)) throw new Exception("missing action id", 1001);
            $this->get_rules();

            $query = $wpdb->prepare("SELECT UNIX_TIMESTAMP(triggered) FROM $this->table
            WHERE action=%d AND rewarded=%d ", $this->reward_action_id, 1);

            $chart_data = $wpdb->get_results($query);
            $chart_data_label = array();

            if ($this->occurrence == "once") {
                $this->cycle_hours_in_seconds = 86400;
                list($time_start, $time_end) = $this->get_time_range();

                $interval_start_time = $time_start - 86400;
                $interval_end_time = $time_start;

                if (sizeof($chart_data) > 0) {
                    for ($i = 0, $interval_count_rewarded = 0; $i < 7; $i++, $interval_start_time -= 86400, $interval_end_time -= 86400, $interval_count_rewarded = 0) {
                        foreach ($chart_data as $row) {
                            foreach ($row as $timestamp) {
                                if ($timestamp > $interval_start_time && $timestamp <= $interval_end_time) {
                                    inno_log_db::log_vcoin_third_party_app_transaction(-1, 55323,
                                        print_r("start_time:" . $interval_start_time . " ( " . $i . " ) " . " : " . $timestamp . "end_time: " . $interval_end_time, true));
                                    $interval_count_rewarded++;
                                }
                            }
                        }
                        $timestamp_label = gmdate("Y-m-d", $interval_start_time);
                        $chart_data_label[$i][$timestamp_label] = $interval_count_rewarded;
                    }
                }
            }
            return $chart_data_label;
        }
    }
}