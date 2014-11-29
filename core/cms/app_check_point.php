<?php
defined("ABSPATH") || exit;
if (!class_exists("app_check_point")) {
    /**
     * Created by PhpStorm.
     * User: Hesk
     * Date: 14年8月15日
     * Time: 下午3:31
     */
    class app_check_point extends cmsBase
    {
        private $vcoin_panel_support;

        public function __construct()
        {
            register_post_type(HKM_ACTION, array(
                "labels" => $this->add_tab(),
                "description" => __("Manage the ablums in the backend."),
                "public" => true,
                "publicly_queryable" => true,
                "show_ui" => true,
                "show_in_menu" => true,
                "query_var" => false,
                //  "rewrite" => array("slug" => "gift", "with_front" => false),
                "capability_type" => "post",
                "has_archive" => true,
                "hierarchical" => false,
                "menu_position" => 6,
                "supports" => array("title", "author"),
                "menu_icon" => INNO_IMAGE_DIR . "system_log_icon.png",
                "hierarchical" => false,
            ));
            add_action('rwmb_trigger_host_cfg_after_save_post', array(__CLASS__, "trigger_host_cfg_savebox"), 10, 1);
            add_filter("rwmb_meta_boxes", array(__CLASS__, "addRWMetabox"), 10, 1);
            //  add_action('rwmb_post_gift_meta_after_save_post', array(__CLASS__, "save_post_gift_meta"), 10, 1);
            $this->addAdminSupportMetabox();

            add_filter('manage_edit-' . HKM_ACTION . '_columns', array(__CLASS__, "add_new_columns"), 10, 1);
            add_action('manage_' . HKM_ACTION . '_posts_custom_column', array(__CLASS__, "manage_column"), 10, 2);
        }


        public static function manage_column($column_name, $id)
        {
            global $wpdb;
            switch ($column_name) {
                case 'type':
                    echo get_post_meta($id, "occurrence", true);
                    break;
                case 'id':
                    echo $id;
                    break;

                default:
                    break;
            } // end switch
        }

        public static function add_new_columns($new_columns)
        {
            $new_columns['cb'] = '<input type="checkbox" />';
            $new_columns['id'] = __('ID');
            $new_columns['title'] = _x('Mission Name', 'column name');

            $new_columns['type'] = __('Trigger');
            // $new_columns['author'] = __('Author');
            $new_columns['categories'] = __('Categories');
            $new_columns['tags'] = __('Tags');
            $new_columns['date'] = _x('Date', 'column name');
            unset($new_columns['author']);
            unset($new_columns['date']);
            return $new_columns;
        }

        function __destruct()
        {
            $this->vcoin_panel_support = NULL;
        }

        public static function trigger_host_cfg_savebox($post_id)
        {
            global $current_user;
            try {
                //  if (!isset($_POST['stock_configuration_complete'])) return;

                self::withUpdateFieldN($post_id, 'action_key', $post_id);
                //  update_post_meta($post_id, 'action_key', $post_id);

                // inno_log_db::log_vcoin_third_party_app_transaction($current_user->ID, 322, "this is it");
            } catch (Exception $e) {
                inno_log_db::log_vcoin_third_party_app_transaction($current_user->ID, $e->getCode(), $e->getMessage());
            }
        }

        protected function add_tab()
        {
            $labels = array(
                "name" => _x("Mission", "post type general name"),
                "singular_name" => _x("Check Point", "post type singular name"),
                "add_new" => _x("追加", HKM_LANGUAGE_PACK),
                "add_new_item" => __("追加", HKM_LANGUAGE_PACK),
                "edit_item" => __("修改", HKM_LANGUAGE_PACK),
                "new_item" => __("追加", HKM_LANGUAGE_PACK),
                "all_items" => __("所有", HKM_LANGUAGE_PACK),
                "view_item" => __("看覽", HKM_LANGUAGE_PACK),
                "search_items" => __("搜查", HKM_LANGUAGE_PACK),
                "not_found" => __("沒有發現", HKM_LANGUAGE_PACK),
                "not_found_in_trash" => __("在垃圾中沒有發現 - Mission", HKM_LANGUAGE_PACK),
                "parent_item_colon" => "",
                "menu_name" => __("Mission", HKM_LANGUAGE_PACK)
            );
            return $labels;
        }

        private static function template_description($lang)
        {
            $text = "";
            $description = "Description %s with templates enabled <ul>
	           <li>{{times}} - example: 3/5</li>
	           <li>{{interval_mark}} - example: 42342 to 342342</li>
	           <li>{{today}} - example: 19/9/2014</li>
	           <li>{{outof}} - example: 3 out of 5</li>
               </ul>";
            if ($lang == 'en') {
                $text = __("English", HKM_LANGUAGE_PACK);
            }
            if ($lang == 'zh') {
                $text = __("Chinese", HKM_LANGUAGE_PACK);
            }
            if ($lang == 'ja') {
                $text = __("Japanese", HKM_LANGUAGE_PACK);
            }
            $lang = NULL;
            return sprintf($description, $text);
        }

        public static function addRWMetabox($meta_boxes)
        {
            $meta_boxes[] = array(
                'pages' => array(HKM_ACTION),
                //This is the id applied to the meta box
                'id' => 'trigger_host_cfg',
                //This is the title that appears on the meta box container
                'title' => __('App Configuration', HKM_LANGUAGE_PACK),
                //This defines the part of the page where the edit screen section should be shown
                'context' => 'normal',
                //This sets the priority within the context where the boxes should show
                'priority' => 'high',
                //Here we define all the fields we want in the meta box
                'fields' => array(
                    array(
                        'name' => __('Action Key ID', HKM_LANGUAGE_PACK),
                        'id' => "action_key",
                        'type' => 'text',
                        'desc' => 'Action Key to Trigger',
                    ),
                    array(
                        'name' => __('Occurrence', HKM_LANGUAGE_PACK),
                        'id' => "occurrence",
                        'type' => 'select',
                        'options' => array(
                            "-1" => "Unselect",
                            "once" => "Once",
                            "repeat_simple" => "Repeatable(Simple)",
                            "repeat_continuous" => "Repeatable(Continuous)",
                        ),
                    ),
                    array(
                        'name' => __('Interval', HKM_LANGUAGE_PACK),
                        'id' => "cycle_reward",
                        'type' => 'select',
                        'options' => array(
                            "-1" => "No Choice",
                            "1" => "Hourly",
                            "2" => "2 Hours",
                            "3" => "3 Hours",
                            "4" => "4 Hours",
                            "6" => "6 Hours",
                            "10" => "10 Hours",
                            "12" => "12 Hours",
                            "24" => "Daily",
                            "48" => "Two Days",
                            "72" => "Three Days",
                            "168" => "Weekly",
                            "336" => "Two Weeks",
                            "720" => "one month (720 hours)",
                        )
                    ),
                    array(
                        'name' => __('Frequency', HKM_LANGUAGE_PACK),
                        'id' => "delta_f",
                        'type' => 'number',
                        'desc' => 'Action Key to Trigger',
                    ),
                    array(
                        'name' => __('VCoin Payer', HKM_LANGUAGE_PACK),
                        'id' => "sdk_app_key",
                        'type' => 'select',
                        'options' => array(
                            "-1" => "Unselect",
                            "imusictech" => "iMusicTech",
                            "developer" => "App Developer Account",
                            "merchant" => "Merchant",
                        ),
                        'desc' => 'The payer ID from the vcoin server',
                    ),
                    array(
                        'name' => __('Reward VCoins', HKM_LANGUAGE_PACK),
                        'id' => "reward_coins",
                        'type' => 'number',
                        'desc' => 'The number of rewards to be vcoin.',
                    ),
                    array(
                        'name' => __('Requires object_id', HKM_LANGUAGE_PACK),
                        'id' => "requires_obj_id",
                        'type' => 'select',
                        'options' => array(
                            "0" => "No",
                            "1" => "Yes"
                        ),
                        'desc' => 'This will require the object id from the query input. For example, if the reward video is watched then the object_id is required'
                    ),

                    array(
                        'name' => 'display mission type',
                        'id' => 'display_type',
                        'type' => 'select',
                        'options' => array(
                            "n" => "please select the mission type",
                            "daily" => "Daily Mission",
                            "weekly" => "Weekly Mission",
                            "one-time" => "One-Time Mission",
                            "continuous" => "Continuous Mission",
                        ),
                    )
                )
            );

            $meta_boxes[] = array(
                'pages' => array(HKM_ACTION),
                //This is the id applied to the meta box
                'id' => 'mission_message_box',
                //This is the title that appears on the meta box container
                'title' => __('Message and Notification Templates', HKM_LANGUAGE_PACK),
                //This defines the part of the page where the edit screen section should be shown
                'context' => 'normal',
                //This sets the priority within the context where the boxes should show
                'priority' => 'high',
                //Here we define all the fields we want in the meta box
                'fields' => array(

                    array(
                        'type' => 'heading',
                        'name' => __('Description for Unfinished Messages', 'meta-box'),
                        'id' => 'fake_id', // Not used but needed for plugin
                    ),
                    array(
                        'name' => __('Description English', HKM_LANGUAGE_PACK),
                        'id' => "description_en",
                        'type' => 'text',
                        'desc' => self::template_description('en')
                    ),
                    array(
                        'name' => __('Description Chinese', HKM_LANGUAGE_PACK),
                        'id' => "description_cn",
                        'type' => 'text',
                        'desc' => self::template_description('zh')
                    ),
                    array(
                        'name' => __('Description Japanese', HKM_LANGUAGE_PACK),
                        'id' => "description_ja",
                        'type' => 'text',
                        'desc' => self::template_description('ja')
                    ),

                    array(
                        'type' => 'heading',
                        'name' => __('Description for Finished Messages', 'meta-box'),
                        'id' => 'fake_id', // Not used but needed for plugin
                    ),

                    array(
                        'name' => __('Done Message in English', HKM_LANGUAGE_PACK),
                        'id' => "finished_en",
                        'type' => 'text',
                        'desc' => self::template_description('en')
                    ),
                    array(
                        'name' => __('Done Message in Chinese', HKM_LANGUAGE_PACK),
                        'id' => "finished_cn",
                        'type' => 'text',
                        'desc' => self::template_description('zh')
                    ),
                    array(
                        'name' => __('Done Message in Japanese', HKM_LANGUAGE_PACK),
                        'id' => "finished_ja",
                        'type' => 'text',
                        'desc' => self::template_description('ja')
                    ),
                    array(
                        'type' => 'heading',
                        'name' => __('Drafting the Idea', 'meta-box'),
                        'id' => 'fake_id', // Not used but needed for plugin
                    ),
                    array(
                        'name' => __('Simple note for the purpose', HKM_LANGUAGE_PACK),
                        'id' => "detail_content_idea",
                        'type' => 'textarea',
                    ),
                )
            );
            $meta_boxes[] = array(
                'pages' => array(HKM_ACTION),
                //This is the id applied to the meta box
                'id' => 'mission_image_box',
                //This is the title that appears on the meta box container
                'title' => __('Mission Ad', HKM_LANGUAGE_PACK),
                //This defines the part of the page where the edit screen section should be shown
                'context' => 'normal',
                //This sets the priority within the context where the boxes should show
                'priority' => 'high',
                //Here we define all the fields we want in the meta box
                'fields' => array(
                    array(
                        'name' => __('Mission Banner Horizontal', HKM_LANGUAGE_PACK),
                        'desc' => 'The horizontal banner for the mission <strong style="color:red">Suggested dimensions: 640px × 30px</strong>',
                        'id' => 'mission_ad',
                        'type' => 'image_advanced',
                        'max_file_uploads' => 1,
                    ),

                )
            );
            return $meta_boxes;
        }

        protected function addAdminSupportMetabox()
        {
            $this->vcoin_panel_support = new adminsupport(HKM_ACTION);
            $this->vcoin_panel_support->add_title_input_place_holder(__("Enter the App Name Max 10 characters", HKM_LANGUAGE_PACK));
            $this->vcoin_panel_support->change_publish_button_label(__("Register", HKM_LANGUAGE_PACK));
            $this->vcoin_panel_support->add_script_name('both', 'page_admin_checkpoint');
            $this->vcoin_panel_support->add_style('adminsupportcss');
            $this->vcoin_panel_support->add_metabox(
                "data_check_mission",
                __("Check Point Mission", HKM_LANGUAGE_PACK),
                get_oc_template('admin_mission_table'));
            $this->vcoin_panel_support->add_metabox(
                "check_point_data_chart",
                __("Check Point Chart", HKM_LANGUAGE_PACK),
                get_oc_template('admin_checkpoint_chart'));


        }


    }
}