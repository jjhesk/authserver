<?php

/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年12月15日
 * Time: 上午11:57
 */
abstract class triggerbase
{
    protected $db, $table, $output_result, $customer, $settings, $transaction_trace_id;

    public function __construct()
    {
        global $wpdb, $current_user;
        $this->db = $wpdb;
        $this->customer = $current_user;
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

} 