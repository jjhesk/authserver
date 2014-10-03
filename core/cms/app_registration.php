<?php
defined("ABSPATH") || exit;
if (!class_exists("app_registration")) {
    /**
     * Created by PhpStorm.
     * User: Hesk
     * Date: 14年8月18日
     * Time: 下午4:54
     */
    class app_registration
    {
        private $system_log;

        public function __construct()
        {
            global $system_script_manager, $current_user;
            $this->system_log = new adminapp(
                array(
                    'type' => 'main',
                    'icon' => INNO_IMAGE_DIR . "system_log_icon.png",
                    'position' => 12,
                    'parent_id' => 'appreg',
                    'cap' => 'app_registration',
                    'title' => __('App Registration', HKM_LANGUAGE_PACK),
                    'name' => __('App Reg', HKM_LANGUAGE_PACK),
                    'cb' => array(__CLASS__, 'render_admin_page'),
                    'script' => 'page_admin_app_reg_log',
                    'style' => array('adminsupportcss', 'datatable', 'dashicons'),
                    //--- get_environoment_config
                    'script_localize' =>
                        array(
                            "setting_ob",
                            array(
                                "url" => DOMAIN_API,
                                "role" => $current_user->roles[0],
                            )
                        )
                    //----  get
                    /*   'script_localize' =>
                           array("setting_ob",
                               $system_script_manager->get_environoment_config()
                           )*/
                    //array(
                    //     "tableurl" => site_url("/api/appaccess/") . "get_my_jobs_market",
                    //)
                    //optional
                    //  'script_localize' => array("jb_tablesource", array(
                    //     "tableurl" => site_url("/api/appaccess/") . "get_my_jobs_market",
                    //)
                    //end optional
                )
            //  )
            );
            $this->add_new_app();
        }

        private function add_new_app()
        {
            $this->system_log->add_sub(array(
                    'title' => __('new App', HKM_LANGUAGE_PACK),
                    'name' => __('New App', HKM_LANGUAGE_PACK),
                    'sub_id' => 'reg_new_app',
                    'cb' => array(__CLASS__, 'render_admin_add_new'),
                    'script_screen_id' => 'app-reg_page_reg_new_app',
                    //  'script' => 'joblisttb',
                    //   'style' => 'kendo_default',
                    'script' => 'admin_app_reg',
                    'style' => array('adminsupportcss', 'datatable', 'dashicons'),
//optional
                    /*'script_localize' => array("jb_tablesource", array(
                        "tableurl" => site_url("/api/appaccess/") . "get_my_jobs_progress",
                    )*/
//end optional
                )
            );
            $this->system_log->add_sub(array(
                    'title' => __('my plan', HKM_LANGUAGE_PACK),
                    'name' => __('Subscription Plan', HKM_LANGUAGE_PACK),
                    'sub_id' => 'subscription',
                    'cb' => array(__CLASS__, 'render_admin_sub_plan'),
                    'script_screen_id' => 'app-reg_page_subscription',
                    //  'script' => 'joblisttb',
                    //   'style' => 'kendo_default',
                    'script' => 'admin_app_reg',
                    'style' => array('subscription_plan'),
//optional
                    /*'script_localize' => array("jb_tablesource", array(
                        "tableurl" => site_url("/api/appaccess/") . "get_my_jobs_progress",
                    )*/
//end optional
                )
            );
        }

        public static function render_admin_add_new()
        {
            echo get_oc_template("admin_page_new_app");
        }

        public static function render_admin_sub_plan()
        {
            echo get_oc_template("admin_sub_plan");
        }

        public static function render_admin_page()
        {
            echo get_oc_template("admin_page_app_registration");
        }
    }
}