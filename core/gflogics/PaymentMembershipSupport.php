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

        public function __construct()
        {
            add_action("pmpro_create_membership", array($this, "after_pro_paid_membership_user_registration"), 5, 2);
        }

        public function after_pro_paid_membership_user_registration($level_id, $user_id)
        {
            $user = new WP_User($user_id);
            $user->remove_role('subscriber');

            //this is the member role - app developer
            //  inno_log_db::log_vcoin_login(-1, 95951, "add now account now" . $user_id . " :level id " . $level_id);
            if (intval($level_id) === self::APP_DEVELOPER) {
                $user->add_role('developer');

                $admin_settings = TitanFramework::getInstance('vcoinset');
                //    if (!isset($admin_settings)) return false;
                $coin = intval($admin_settings->getOption("app_coin_new_dev"));
                if ($coin == 0) $coin = 10;

                // inno_log_db::log_vcoin_login(-1, 95951, "add now account now" . $user_id . " :coin" . $coin);

                userBase::AddUserMeta($user_id, array(
                    "app_coins" => $coin
                ));

                unset($coin);
                unset($admin_settings);
                unset($user);
                // return true;
            } else if (intval($level_id) === self::APP_DEVELOPER_TRAIL) {

            }
        }
    }
}