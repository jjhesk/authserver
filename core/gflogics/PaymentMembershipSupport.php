<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年10月8日
 * Time: 下午12:42
 */
if (!class_exists("PaymentMembershipSupport")) {
    class PaymentMembershipSupport
    {
        const APP_DEVELOPER = 1,
            APP_DEVELOPER_TRAIL = 3;
        protected $values_role = array();
        private $admin_setting;

        public function __construct()
        {
            $this->admin_settings = TitanFramework::getInstance('vcoinset');
            add_action("pmpro_create_membership", array($this, "after_pro_paid_membership_user_registration"), 5, 2);
        }

        private function add_coin($by_field_defined, $user_id)
        {
            $coin = intval($this->admin_settings->getOption($by_field_defined));
            if ($coin == 0) $coin = 10;
            userBase::AddUserMeta($user_id, array(
                "app_coins" => $coin
            ));
            unset($coin);
        }

        public function after_pro_paid_membership_user_registration($level_id, $user_id)
        {
            $user = new WP_User($user_id);
            $user->remove_role('subscriber');

            //this is the member role - app developer
            //  inno_log_db::log_vcoin_login(-1, 95951, "add now account now" . $user_id . " :level id " . $level_id);
            if (intval($level_id) === self::APP_DEVELOPER) {
                $user->add_role('developer');
                $this->add_coin("app_coin_new_dev", $user_id);
            } else if (intval($level_id) === self::APP_DEVELOPER_TRAIL) {
                $user->add_role('developer');
                $this->add_coin("app_coin_new_trail_dev", $user_id);
            }

            unset($this->admin_settings);
            unset($user);
        }
    }
}