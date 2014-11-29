<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年2月10日
 * Time: 上午9:44
 */
defined('ABSPATH') || exit;

if (!class_exists('email_confirmation_verify')) {
    class email_confirmation_verify extends gformBase
    {
        private $email, $hash, $rgID, $format;
        protected $message_internal, $query;

        public function __construct($Q)
        {
            $this->request_pnv($Q);
        }

        /**
         * stable version...
         *
         * @param $Q
         * @throws Exception
         */
        private function request_pnv($Q)
        {
            if (!isset($Q->e))
                throw new Exception("email is missing", 1001);
            if (!isset($Q->h))
                throw new Exception("hash is missing", 1002);
            if (!isset($Q->r))
                throw new Exception("rgID is missing", 1003);
            if (!isset($Q->d))
                throw new Exception("format is missing", 1004);

            $this->email = $Q->e;
            $this->hash = $Q->h;
            $this->rgID = $Q->r;
            $this->format = $Q->d;


            $this->message_internal = "demo only for now";
            $this->input_var_completed_action();

        }

        /**
         * this may incur bugs in the SSL connect
         *
         * @param $Q
         * @throws Exception
         */
        private function request_string($Q)
        {

            /** the email of the new user account **/
            if (isset($Q->vdata)) {
                $this->query = explode(";", $Q->vdata);

                $this->email = $this->query[0];
                $this->hash = $this->query[1];
                $this->rgID = $this->query[2];
                $this->format = $this->query[3];

                if (!isset($this->email))
                    throw new Exception("email is missing", 1001);
                if (!isset($this->hash))
                    throw new Exception("hash is missing", 1002);
                if (!isset($this->rgID))
                    throw new Exception("rgID is missing", 1003);
                if (!isset($this->format))
                    throw new Exception("format is missing", 1004);
            } else throw new Exception("vdata is missing", 1005);

            $this->message_internal = "demo only for now";
            $this->input_var_completed_action();
            //  $this->input_var_completed_action();
            //  $r = gfUserRegistration::isEmailVerified($this->hash, $this->rgID);
        }

        public function get_result()
        {
            if ($this->format == "web_mobile") {
                $this->getresult_html_table_web();
            } elseif ($this->format == "json") {
                $this->getresult_json();
            } else {

                $this->message_internal = "invalid format setting" .
                    print_r($this->query, true);
                $this->getresult_html_table_web();
            }
        }

        private function getresult_html_mobile_web()
        {
            echo get_oc_template_mustache("verify_email_template", array(
                "message" => $this->message_internal
            ));
            exit;
        }

        private function getresult_html_table_web()
        {
            echo get_oc_template_mustache("verify_email_template", array(
                "message" => $this->message_internal
            ));
            exit;
        }

        private function getresult_json()
        {
            // return $this->message_internal;
            api_handler::outSuccessDataWeSoft($this->message_internal);
        }

        private function input_var_completed_action()
        {
            try {
                $check_hash = parent::gf_get_entry_value(GF_FORM_USER_REG, $this->rgID, gf_field_email_token);
                $check_pending_list_email = parent::gf_get_entry_value(GF_FORM_USER_REG, $this->rgID, gf_field_email);
                $check_pending_list_username = parent::gf_get_entry_value(GF_FORM_USER_REG, $this->rgID, gf_field_login_name);
                $check_pending_list_password = parent::gf_get_entry_value(GF_FORM_USER_REG, $this->rgID, gf_field_password);
                $check_pending_list_company_name = parent::gf_get_entry_value(GF_FORM_USER_REG, $this->rgID, gf_field_company);
                $role = parent::gf_get_entry_value(GF_FORM_USER_REG, $this->rgID, gf_field_role);
                if (!isset($check_hash)) throw new Exception("check issue", 1010);
                if (!isset($check_pending_list_email)) throw new Exception("check_pending_list_email issue", 1011);
                if (!isset($check_pending_list_username)) throw new Exception("check_pending_list_username issue", 1012);
                if (!isset($check_pending_list_password)) throw new Exception("check_pending_list_password issue", 1013);
                if (!isset($check_pending_list_company_name)) $check_pending_list_company_name = "new company";
                //throw new Exception("check_pending_list_company_name issue", 1014);
                if (!isset($role)) throw new Exception("role hash issue", 1015);
                $this->message_internal = "checking...";
                if ($check_hash == $this->hash && !email_exists($check_pending_list_email)) {
                    //$user_id = wp_create_user($check_pending_list_username, $check_pending_list_password, $check_pending_list_email);

                    $new_user = new userRegister();
                    $new_user->newUser($check_pending_list_username, $check_pending_list_email, $role, array(
                        "company_name" => $check_pending_list_company_name
                    ), $check_pending_list_password);

                    $this->message_internal = _x("Verify success. Please login your app and start getting fun!", "email");
                    //  $this->render("Verify success. Please login your app and start getting fun!");
                } else {
                    $this->message_internal = _x("Email verify is not success. As you have registered this Username before.", "email");
                    //    $this->render("Email verify is not success. As you have registered this Username before.");
                }
            } catch (Exception $e) {
                //  $this->render($e->getMessage());
                $this->message_internal = $e->getMessage();
            }

        }

    }
}