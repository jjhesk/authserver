<?php

/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年12月15日
 * Time: 上午11:56
 */
class app_video extends triggerbase
{

    public function __construct()
    {
        parent::__construct();
        $this->table = $this->db->prefix . "post_app_object";
    }

    private function claim($post_id, $user_id)
    {

        try {
            $L = $this->db->prepare("SELECT * FROM $this->table WHERE post_id=%s AND download_user=%d AND user=%d", (int)$post_id, (int)$user_id);
            $R = $this->db->get_row($L);
            if ($R) {
                $this->trigger_reward($R);
                $this->db->update(
                    $this->table,
                    array("triggered" => 1),
                    array("ID" => $R->ID),
                    array('%d'), array('%d')
                );
            } else {
                throw new Exception("you have already got the video reward", 1554);
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    private function trigger_reward($R)
    {
        try {

            $obj = api_cms_server::crosscms("reward_transaction_metadata", array(
                "post_id" => $R->post_id
            ), false);


            if ($R) {
                $coin = new vcoinBase();
                $coin->setSender($obj->uuid);
                $coin->setCoinGainer($this->customer);
                $coin->setAmount($obj->amount);
                $coin->setTransactionReference("video watch payout ID:" . $R->post_id);
                $coin->CommitTransaction();

                $this->transaction_trace_id = $coin->get_tranaction_reference();

                $this->output_result = array(
                    "trace_id" => $coin->get_tranaction_reference(),
                    "video_id" => (int)$R->post_id,
                    "payout" => (int)$R->amount
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

    public function just_watched($Q)
    {
        if (!isset($Q->post_id)) throw new Exception("post_id is missing.", 1712);

        $L = $this->db->prepare("SELECT * FROM $this->table WHERE post_id=%d AND user=%d", (int)$Q->post_id, (int)$this->customer->ID);
        $R = $this->db->get_row($L);


        if (!$R) {
            $data_insert = array(
                "user" => $this->customer->ID,
                "post_id" => $Q->post_id,
                "triggered" => 0
            );
            $this->db->insert($this->table, $data_insert);
            $this->output_result = $data_insert;
            // give another
            $this->claim($Q->post_id, $this->customer->ID);

        } else {
            throw new Exception("You have already watched", 1552);
        }


    }
}