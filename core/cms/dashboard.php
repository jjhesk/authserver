<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年9月19日
 * Time: 下午3:37
 */
if (!class_exists('dashboard')):
    class dashboard
    {

        protected $meta_list;

        function __construct($settings = array())
        {
            //todo: ryo please do not initiate code on load with curl request
            // $user_meta = new dashboardMeta();

            $this->meta_list = array(

                /*                array(
                                    "key" => "yahoo",
                                    "title" => __("Yahoo", HKM_LANGUAGE_PACK),
                                    "role" => "appuser",
                                    "template" => "admin_login"
                                ),

                                array(
                                    "key" => "yahoo_c",
                                    "title" => __("Board", HKM_LANGUAGE_PACK),
                                    "role" => "appuser",
                                    "template" => "admin_login"
                                ),*/

                array(
                    "key" => "vcoin_plan_review",
                    "title" => __("My Plan", HKM_LANGUAGE_PACK),
                    "role" => "developer",
                    "template" => "dashboard_item_plan",
                    "css" => array("account_status"),
                    "js" => "dashboard_account",
                ),
            );
            $this->meta_list = wp_parse_args($settings, $this->meta_list);
            add_action('wp_dashboard_setup', array(&$this, "load_dashboard"));
        }

        public function load_dashboard()
        {
            global $current_user;

            if ($current_user->roles[0] == "developer") {
                $user_meta = new dashboardMeta($current_user);
                $this->meta_list_custom(
                    0,
                    "script_localize",
                    array(
                        "setting_ob",
                        array("company_name" => $user_meta->get_current_user_meta("company_name"),
                            "available_coins" => $user_meta->get_current_user_meta("available_coins"))));
            }

            foreach ($this->meta_list as $list) {
                new admindashboard(
                    $list["key"],
                    $list["title"],
                    $list["template"],
                    $list["role"],
                    isset($list["js"]) ? $list["js"] : array(),
                    isset($list["css"]) ? $list["css"] : array(),
                    isset($list["script_localize"]) ? $list["script_localize"] : array()
                );
            }
        }

        /**
         * @param $array_key
         * @param $field
         * @param $val
         * custom the dashboard meta_list here
         */
        public function meta_list_custom($array_key, $field, $val)
        {
            $this->meta_list[$array_key][$field] = $val;
        }
    }
endif;