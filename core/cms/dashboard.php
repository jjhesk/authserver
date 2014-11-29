<?php
/**
 * dashboard customization and fixing unwanted items
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年9月19日
 * Time: 下午3:37
 */
if (!class_exists('dashboard')):
    class dashboard
    {

        protected $meta_list, $user, $user_role;

        function __construct($settings = array())
        {
            global $current_user;
            $this->user = $current_user;
            $this->user_role = $this->user->roles[0];


            $this->meta_list = array(
                array(
                    "key" => "vcoin_plan_review",
                    "title" => __("My Plan", HKM_LANGUAGE_PACK),
                    "role" => "developer",
                    "template" => "dashboard_item_plan",
                    "css" => array("account_status"),
                    "js" => "dashboard_account",
                ),
                array(
                    "key" => "vcoin_user_coin_review",
                    "title" => __("My Coins", HKM_LANGUAGE_PACK),
                    "role" => "appuser",
                    "template" => "dashboard_vcoin_wallet",
                    "template_key" => array("app_coins"),
                    "css" => array("coinanim"),
                    "js" => "dashboard_mycoin_profile",
                ),
                array(
                    "key" => "prize_coupon_draw_results",
                    "title" => __("Reward Board Announcement", HKM_LANGUAGE_PACK),
                    "role" => "appuser",
                    "template" => "dashboard_score_board",
                    "template_key" => array("score_board"),
                    //"css" => array("coinanim"),
                    // "js" => "dashboard_mycoin_profile",
                ),
            );
            $this->meta_list = wp_parse_args($settings, $this->meta_list);
            add_action('wp_dashboard_setup', array(&$this, "load_dashboard"));
            /** this is the part to remove the unwanted items **/
            add_action('admin_bar_menu', array($this, 'remove_wp_preset_buttons'), 999);
            add_action('admin_init', array($this, 'remove_dashboard'));
            add_filter('admin_footer_text', array($this, 'remove_footer_admin'));
            add_action('admin_menu', array($this, 'wps_hide_update_notice'));
			add_action('admin_head', array($this, 'remove_wpml_img'));
			add_action( 'wp_before_admin_bar_render', array($this, 'remove_admin_bar_home_link'));
			if ($this->user->roles[0] == "appuser") {
            add_filter('contextual_help', array($this, 'remove_help_tab'),11,3);
			add_filter('screen_options_show_screen', '__return_false');
			}
        }

        public function __destruct()
        {
            $this->meta_list = NULL;
            $this->user = NULL;
        }

        public function wps_hide_update_notice()
        {
            if (!current_user_can('manage_options')) {
                remove_action('admin_notices', 'update_nag', 3);
                // remove_action('user_admin_notices', 'update_nag', 3);
                remove_action('admin_notices', 'maintenance_nag');
            }
        }

        public function remove_footer_admin()
        {
            echo '<span id="footer-thankyou" class="developed by hkm"></span>';
        }

        public function remove_dashboard()
        {
            remove_meta_box('dashboard_primary', 'dashboard', 'core');
            remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
            remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
            remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        }

        public function remove_wp_preset_buttons($wp_admin_bar)
        {
            /**
             * my-account – link to your account (avatars disabled)
             * my-account-with-avatar – link to your account (avatars enabled)
             * my-blogs – the “My Sites” menu if the user has more than one site
             * get-shortlink – provides a Shortlink to that page
             * edit – link to the Edit/Write-Post page
             * new-content – link to the “Add New” dropdown list
             * comments – link to the “Comments” dropdown
             * appearance – link to the “Appearance” dropdown
             * updates – the “Updates” dropdown
             */
            $wp_admin_bar->remove_node('wp-logo');
            $wp_admin_bar->remove_node('comments');
            if ($this->user->roles[0] != "administrator") {
                $wp_admin_bar->remove_node('edit');
                $wp_admin_bar->remove_node('new-content');
            } else {
                $wp_admin_bar->remove_node('edit');
            }
        }

        public function load_dashboard()
        {
            if ($this->user->roles[0] == "developer") {
                $user_meta = new dashboardMeta($this->user);
                $this->meta_list_custom(0, "script_localize",
                    array(
                        "setting_ob",
                        array(
                            "company_name" => $user_meta->get_current_user_meta("company_name"),
                            "app_coins" => $user_meta->get_current_user_meta("app_coins")
                        ))
                );
            }

            foreach ($this->meta_list as $list) {
                $admin_engine = new admindashboard(
                    $list["key"],
                    $list["title"],
                    $list["role"],
                    isset($list["js"]) ? $list["js"] : array(),
                    isset($list["css"]) ? $list["css"] : array(),
                    isset($list["script_localize"]) ? $list["script_localize"] : array()
                );
                if (isset($list["template"])) $admin_engine->setTemplate($list["template"]);
                if (isset($list["template_key"])) $admin_engine->setData($this->user, $list["template_key"]);
                $admin_engine->init();
                $admin_engine = NULL;
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
		
		public function remove_help_tab($old_help, $screen_id, $screen)
		{
		$screen->remove_help_tabs();
		return $old_help;
		}
		
		public function remove_wpml_img()
		{
		echo '<style>.icl_als_iclflag{display: none;} #wpml_als_help_link {display: none;}</style>';
		}
		
		public function remove_admin_bar_home_link() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('site-name');
		$wp_admin_bar->remove_menu('view-site');
		}
    }
endif;