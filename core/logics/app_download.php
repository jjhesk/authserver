<?php

/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年11月13日
 * Time: 上午10:58
 */
class app_download
{
    private $db, $table, $output_result, $customer, $settings;

    public function __construct()
    {
        global $wpdb, $current_user;
        $this->db = $wpdb;
        $this->customer = $current_user;
        $this->table = $this->db->prefix . "post_app_download";
        $this->regtable = $this->db->prefix . "post_app_registration";
        $this->settings = TitanFramework::getInstance('vcoinset');

    }

    public function __destruct()
    {
        $this->settings = NULL;
        $this->db = NULL;
    }

    public function get_result()
    {
        return $this->output_result;
    }

    public function download($Q)
    {
        if (!isset($Q->appkey)) throw new Exception("appkey is missing", 10010);
        if ($Q->appkey != APPKEY_VCOINAPP) throw new Exception("appkey for vcoinapp not matched", 10011);
        if (!isset($Q->down_app_key)) throw new Exception("down_app_key is missing", 10012);

        $L = $this->db->prepare("SELECT * FROM $this->table WHERE app_key=%s AND download_user=%d", $Q->down_app_key, $this->customer->ID);
        $R = $this->db->get_row($L);
        if (!$R) {
            $data_insert = array(
                "download_user" => $this->customer->ID,
                "app_key" => $Q->down_app_key,
                "triggered" => 0
            );
            $this->db->insert($this->table_new, $data_insert);
            $this->output_result = $data_insert;
        } else {
            throw new Exception("you have already downloaded", 1552);
        }
    }

    public function start_sdk_app_v2($app_key)
    {
        if ($app_key == APPKEY_VCOINAPP) throw new Exception("sdk appkey is not verified", 1553);
        $L = $this->db->prepare("SELECT * FROM $this->table WHERE app_key=%s AND download_user=%d AND triggered=%d", $app_key, $this->customer->ID, 0);
        $R = $this->db->get_row($L);
        if ($R) {
            $this->db->update(
                $this->table_new,
                array("triggered" => 1),
                array("ID" => $R->ID),
                array('%d'), array('%d')
            );
            $this->trigger_reward($R->app_key);
        } else {
            throw new Exception("you have already got the reward", 1554);
        }
    }

    public function start_sdk_app($Q)
    {
        if (!isset($Q->appkey)) throw new Exception("appkey is missing", 10010);
        if ($Q->appkey == APPKEY_VCOINAPP)throw new Exception("sdk appkey is not verified", 1553);

        $L = $this->db->prepare("SELECT * FROM $this->table WHERE app_key=%s AND download_user=%d AND triggered=%d", $Q->appkey, $this->customer->ID, 0);
        $R = $this->db->get_row($L);
        if ($R) {
            $this->db->update(
                $this->table_new,
                array("triggered" => 1),
                array("ID" => $R->ID),
                array('%d'), array('%d')
            );
            $this->trigger_reward($R->app_key);
        } else {
            throw new Exception("you have already got the reward", 1554);
        }
    }

    private function trigger_reward($key)
    {

        $R = $this->db->get_row($this->db->prepare("SELECT * FROM $this->regtable WHERE app_key=%s", $key));
        try {
            if ($R) {

                $coin_operation = new vcoinBase();

                $coin_operation

                    ->setAmount((int)$R->payout)
                    ->setCoinGainer($this->customer)
                    ->setSender($R->vcoin_account)
                    ->setTransactionReference("first_DL:" . $R->app_key)
                    ->CommitTransaction();

                $this->output_result = array(
                    "trace_id" => $coin_operation->get_tranaction_reference()
                );
                if ($this->settings->getOption("push_dl")) {
                    $must_ache = new Mustache_Engine;
                    $out = $must_ache->render($this->settings->getOption("push_dl_message"), array(
                        "coin" => (int)$R->payout
                    ));
                    api_cms_server::push_user($this->customer->ID, $out);
                }
            } else {
                throw new Exception("you have already got the reward", 1554);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

} 