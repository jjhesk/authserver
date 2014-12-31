<?php
/*
  Controller name: Email
  Controller description: Backend API for verification process mechanism provided specifically for IOS and Android. <br>Detail please refer to our Google Drive documentation. <br>Author: Heskemo
 */
if (!class_exists('JSON_API_Email_Controller')) {
    class JSON_API_Email_Controller
    {
        /**
         * API Name: api get sliders
         */
        public static function verify()
        {

            global $json_api;
            try {
                $email = new email_confirmation_verify($json_api->query);
                $email->get_result();
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        public static function autousrv2()
        {
            try {
                userBase::core_new();
            } catch (Exception $e) {
                api_handler::outFail($e->getCode(), $e->getMessage());
            }
        }

        public static function unitTest()
        {
            global $json_api;
            $n1 = $json_api->query->n1;
            $n2 = $json_api->query->n2;
            $op = $json_api->query->op;
            $status = 'failure';
            if ($op == 'addition') {
                $result = $n1 + $n2;
                $status = 'success';
            } else {
                $op = 'no input';
                $result = '';
            }

            return array('status' => $status, 'operation' => $op, 'result' => $result);
        }
    }
}