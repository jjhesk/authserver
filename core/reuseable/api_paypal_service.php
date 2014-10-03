<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年9月24日
 * Time: 下午4:25
 */
defined('ABSPATH') || exit;
if (!class_exists('api_paypal_service')) {
    class api_paypal_service
    {
        protected $endpoint = "https://api.sandbox.paypal.com";
        protected $credential;
        protected $token_obtained;
        private $transaction_data;

        public function __construct()
        {
            $this->setEndPoint();
            $this->transaction_data = array(
                "intent" => "sale",
                "payer" => array(
                    "payment_method" => "paypal"
                ),
                "redirect_urls" => array(
                    "return_url" => "http://example.com/your_redirect_url.html",
                    "cancel_url" => "http://example.com/your_redirect_url.html",
                )
            );
        }
        /**
         * https://developer.paypal.com/webapps/developer/docs/integration/direct/rest-experience-overview/
         */
        /**
         * @param string $server
         *
         * {
         * "intent":"sale",
         * "redirect_urls":{
         * "return_url":"http://example.com/your_redirect_url.html",
         * "cancel_url":"http://example.com/your_cancel_url.html"
         * },
         * "payer":{
         * "payment_method":"paypal"
         * },
         * "transactions":[
         * {
         * "amount":{
         * "total":"7.47",
         * "currency":"USD"
         * }
         * }
         * ]
         * }'
         *
         */
        /**
         * @param $amount
         * @throws Exception
         */
        public function setHKD($amount)
        {
            if (intval($amount) == 0) throw new Exception("invalid transaction amount", 1175);
            $this->transaction_data["amount"]["total"] = $amount;
            $this->transaction_data["amount"]["currency"] = "HKD";
        }

        /**
         * @param $amount
         * @throws Exception
         */
        public function setUSD($amount)
        {
            if (intval($amount) == 0) throw new Exception("invalid transaction amount", 1175);
            $this->transaction_data["amount"]["total"] = $amount;
            $this->transaction_data["amount"]["currency"] = "USD";
        }

        /**
         * @param string $server
         * @throws Exception
         */
        public function setEndPoint($server = "sandbox")
        {
            if ($server == "sandbox") {
                $this->credential = 'AZgumBBDX0h7CF0GTT0KdkRh1FgySYt66KrtCbfCKBwEIOvGoPG_P-5ihjWm:EOrSSxCQnIC4Y8_dzg3tbLtMajSZSFZKWn1LetqCUyiz52vxA8BoQU_CPvq0';
                $this->endpoint = "https://api.sandbox.paypal.com";
            } elseif ($server == "live") {
                $this->credential = 'AST5BxCmD8I7NP6_IJyJhytLH33svnc6rCCngL_BRPuchqfIuGyq-w6xKjpg:EPedLBBQDK_JH9CTBce3Y64y7-LCtgWJ1t35KS-HZ6SaUZjDWhsFbXP9uWO4';
                $this->endpoint = "https://api.paypal.com";
            } else
                throw new Exception("invalid settings for endpoint", 1173);
        }

        public function Commit()
        {
            $this->access_token();
        }

        private function api_transaction($package)
        {
            //except using the wesoft data output format
            $d = api_handler::curl_post($this->endpoint . "/v1/payments/payment/",
                array(), array(
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_POSTFIELDS => json_encode($this->transaction_data),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $package->access_token,
                    ),

                ));
            $this->token_obtained = json_decode($d);
        }

        private function access_token()
        {

            //except using the wesoft data output format
            $d = api_handler::curl_post($this->endpoint . "/v1/oauth2/token/",
                array(
                    "grant_type" => "client_credentials"
                ), array(
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_USERPWD => $this->credential,
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json',
                        'Accept-Language: en_US',
                    ),
                ));
            $this->token_obtained = json_decode($d);
            $this->api_transaction($this->token_obtained);
        }

    }
}