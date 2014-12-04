<?php

/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年12月3日
 * Time: 下午8:24
 */
class CampaignBase
{
    protected $db, $contestants, $backers;

    /**
     *
     */
    public function  __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->contestants = $wpdb->prefix . "campaign_people";
        $this->backers = $wpdb->prefix . "campaign_relationship";
        $this->coin_engine = new vcoinBase();
    }

    public function __destruct()
    {

    }

    /**
     * @param $backer_id
     * @param $contestant_id
     * @param $camp_id
     * @return int
     */
    public function backer_exist($backer_id, $contestant_id, $camp_id)
    {
        $L = $this->db->prepare("SELECT COUNT(*) FROM $this->backers WHERE backer_id=%d AND camp_id=%d AND user_id=%d",
            (int)$backer_id, (int)$camp_id, (int)$contestant_id);
        $f = $this->db->get_var($L);
        return (int)$f;
    }

    /**
     * @param $contestant_id
     * @param $camp_id
     * @return bool
     */
    public function join_contestant_exist($contestant_id, $camp_id)
    {
        $L = $this->db->prepare("SELECT COUNT(*) FROM $this->contestants WHERE user_id=%d AND campagin_id=%d",
            (int)$contestant_id, (int)$camp_id);
        return intval($this->db->get_var($L)) > 0;
    }

    /**
     * @param $camp_id
     * @return int
     */
    public function get_total_backers_in_campaign($camp_id)
    {
        $L = $this->db->prepare("SELECT COUNT(*) FROM $this->backers WHERE  camp_id=%d ",
            (int)$camp_id);
        $f = $this->db->get_var($L);
        return (int)$f;
    }

    /**
     * @param $total
     * @param $contestant_id
     * @param $camp_id
     */
    private function update_contestant_backers($total, $contestant_id, $camp_id)
    {
        $this->db->update(
            $this->contestants,
            //data fields
            array(
                'backers' => (int)$total,
            ),
            //where condition
            array(
                'user_id' => (int)$contestant_id,
                'campagin_id' => (int)$camp_id
            ),
            array(
                '%d'
            ),
            array(
                '%d',
                '%d'
            )
        );
    }

    /**
     * @param $backer_id
     * @param $contestant_id
     * @param $camp_id
     * @return array
     * @throws Exception
     */
    public function back_now($backer_id, $contestant_id, $camp_id)
    {
        $cost = 1;
        $user_now = new WP_User($backer_id);
        $getCoin = userBase::update_app_user_coin($user_now);

        if ($getCoin < $cost)
            throw new Exception("You dont have enough coins", 1756);
        if ($this->backer_exist($backer_id, $contestant_id, $camp_id) > 0)
            throw new Exception("You have backed", 1750);

        $data = array(
            "backer_id" => $backer_id,
            "camp_id" => $camp_id,
            "user_id" => $contestant_id
        );

        $this->db->insert($this->backers, $data);
        $sender = userBase::getAppUserVcoinUUID($user_now);
        $this->coin_engine->setAmount($cost);
        $this->coin_engine->setSender($sender);
        $this->coin_engine->setReceive(IMUSIC_UUID);
        $this->coin_engine->setTransactionReference("campaign backing");
        $this->coin_engine->CommitTransaction();
        // userBase::update_app_user_coin($user_now);
        $total = $this->get_backers($contestant_id, $camp_id);
        $this->update_contestant_backers($total, $contestant_id, $camp_id);
        update_post_meta($camp_id, 'total_votes', $this->get_total_backers_in_campaign($camp_id));
        return array(
            $this->coin_engine->get_tranaction_reference(),
            $sender,
            $total
        );


    }


    /**
     * @param $contestant_id
     * @param $camp_id
     * @return int
     */
    protected function get_backers($contestant_id, $camp_id)
    {
        $L = $this->db->prepare("SELECT COUNT(*) FROM $this->backers WHERE camp_id=%d AND user_id=%d",
            (int)$camp_id, (int)$contestant_id);
        $f = $this->db->get_var($L);
        return (int)$f;
    }

    /**
     * @param $contestant_id
     * @param $camp_id
     * @param $message
     * @return int
     */
    public function join_contest($contestant_id, $camp_id, $message)
    {
        $data = array(
            "campagin_id" => $camp_id,
            "user_id" => $contestant_id,
            "message" => $message
        );
        $this->db->insert($this->contestants, $data);
        /*   $L = $this->db->prepare("SELECT COUNT(*) FROM $this->backers WHERE backer_id=%d AND camp_id=%d AND user_id=%d",
               (int)$backer_id, (int)$camp_id, (int)$contestant_id);
           $f = $this->db->get_var($L);*/
    }

    public function get_campaign_contestants($camp_id)
    {
        $L = $this->db->prepare("SELECT * FROM $this->contestants WHERE campagin_id=%d", (int)$camp_id);
        return $this->db->get_results($L);
    }
}