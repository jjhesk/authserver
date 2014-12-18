<?php

/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年10月22日
 * Time: 上午10:22
 */
class app_transaction_history
{
    private $system_log;
    private $_script_manager;

    public function __construct()
    {
        global $system_script_manager, $current_user;
        $this->_script_manager = $system_script_manager;
        try {

            $uuid = userBase::getAppUserVcoinUUID($current_user);


            $this->system_log = new adminapp(
                array(
                    'role' => 'appuser',
                    'type' => 'main',
                    'icon' => INNO_IMAGE_DIR . "system_log_icon.png",
                    'position' => 5,
                    'parent_id' => 'transactionh',
                    'cap' => 'read',
                    'title' => __('Coin History', HKM_LANGUAGE_PACK),
                    'name' => __('Coin History', HKM_LANGUAGE_PACK),
                    'cb' => array(__CLASS__, 'render_admin_page'),
                    'script' => 'page_history_transaction',
                    'style' => array('adminsupportcss', 'datatable', 'dashicons',
                        'smoothness', 'datepicker_ui', 'datepicker_structure', 'datepicker_theme'),
                    //--- get_environoment_config
                    'script_localize' =>
                        array(
                            "setting_ob",
                            array(
                                "url" => DOMAIN_API,
                                "role" => $current_user->roles[0],
                                "user_uuid" => $uuid
                            )
                        )
                )
            //  )
            );

            unset($uuid);
            if ($this->system_log) {
                $this->add_new_app();
            }

        } catch (Exception $e) {
            // inno_log_db::log_vcoin_login($current_user->ID, $e->getCode() + 100000, "app_transaction_history::adminapp::" . $e->getMessage());
        }
    }

    /**
     * @param $uuid
     * @param $Q
     * @throws Exception
     * @internal param $feature
     * @internal param $start
     * @internal param $end
     * @internal param $index
     * @return mixed
     */
    public static function get_history_api($uuid, $Q, $current_user)
    {
        try {

            if (!isset($Q->feature)) throw new Exception("missing feature for vcoin history", 10006);
            if (isset($Q->app_uuid)) $uuid = $Q->app_uuid; else $uuid = userBase::getAppUserVcoinUUID($current_user);

            if (!isset($uuid) || $uuid == "") throw new Exception("get history uuid is missing. Check settings on user profile", 10008);
            if (!isset($Q->start)) $start = ""; else $start = $Q->start;
            if (!isset($Q->end)) $end = ""; else $end = $Q->end;
            if (!isset($Q->index)) $index = -1; else $index = $Q->index;
            if (!isset($Q->sort)) $sort = "time"; else $sort = $Q->sort;
            if (!isset($Q->order)) $order = "ase"; else $order = $Q->order;
            if (!isset($Q->transid)) $trans_id = ""; else $trans_id = $Q->transid;
            if (!isset($Q->needbalance)) $bal = "no"; else $bal = "yes";
            $param = array(
                "accountid" => $uuid,
                "feature" => $Q->feature,
                "start" => $start,
                "end" => $end,
                "index" => $index,
                "sort" => $sort,
                "order" => $order,
                "transid" => $trans_id,
                "needbalance" => $bal
            );
            //  if ($Q->feature == "custom") {
            /* $param = wp_parse_args($param, array(
                 "start" => $start,
                 "end" => $end,
                 "index" => $index
             ));;*/
            //   }
            inno_log_db::log_vcoin_login(-1, 20112, print_r($param, true));
            $json = api_handler::curl_get(VCOIN_SERVER . "/api/account/coinhistoryex", $param, array(CURLOPT_TIMEOUT => 20));
            $R = json_decode($json);
            unset($json);

            if (intval($R->result) > 0) {
                throw new Exception($R->msg, (int)$R->result);
            } else {
                if (isset($R->Message)) {
                    inno_log_db::log_vcoin_third_party_app_transaction(-1, 20211, print_r($param, true));
                    throw new Exception($R->Message, 5202);
                } else
                    return $R->data;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    private static function data_history_vcoin($data)
    {
        /**
         * going thru the vcoin server API to get transaction request
         * todo: after the holiday on the ticket issued on 0010883
         */
        foreach ($data as $history) {

        }
        return apply_filter("data_history_vcoin", $data);
    }

    private function add_new_app()
    {

    }


    public static function render_admin_page()
    {
        echo get_oc_template("admin_coin_history");
    }
} 