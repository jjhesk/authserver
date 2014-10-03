<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年9月24日
 * Time: 上午9:48
 */
defined('ABSPATH') || exit;
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年8月8日
 * Time: 下午4:14
 */
if (!class_exists('addcoin')) {
    class addcoin
    {

        protected $gain_to, $amount, $uuid;

        /**
         * vcoin action base account
         */
        public function __construct()
        {

        }

        public function setUserReceiver(WP_User $point_to_user)
        {
            $this->gain_to = $point_to_user;
            return $this;
        }

        public function setUUIDReceiver($str_uuid)
        {
            $this->uuid = $str_uuid;
            return $this;
        }

        /**
         * @param $amount
         * @return $this
         */
        public function setAmount($amount)
        {
            $this->amount = $amount;
            return $this;
        }

        /**
         * @throws Exception
         */
        public function CommitTransaction()
        {
            if (!isset($this->uuid) && !isset($this->gain_to)) throw new Exception("need to set either uuid or user item", 1093);
            if (!isset($this->uuid) && isset($this->gain_to))
                $this->uuid = userBase::getVal($this->gain_to->ID, "uuid_key");
            if ($this->uuid == "") throw new Exception("user uuid for vcoin server is not set", 1020);
            if (!isset($this->amount)) throw new Exception("coin amount not set", 1095);
            // According VCoin Server APIs v1.4.pdf
            // 2.7
            $json = api_handler::curl_post(VCOIN_SERVER . "/api/coin/addcoin",
                array(
                    "accountid" => $this->uuid,
                    "count" => $this->amount
                ));
            $res = json_decode($json);
            unset($uuid);
            unset($json);
            if (intval($res->result) === 0)
                return true;
            else throw new Exception($res->msg, intval($res->result));
            //======================================================================
        }
    }
}