<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年12月3日
 * Time: 下午8:36
 */
defined("ABSPATH") || exit;
if (!class_exists("vcoinapp_campaig")) {
    class vcoinapp_campaig extends cmsBase
    {
        private $vcoin_panel_support;

        public function __construct()
        {
            register_post_type(HKM_GAME_CAMPAIGN, array(
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
                "menu_position" => 8,
                "supports" => array("title", "author"),
                "menu_icon" => INNO_IMAGE_DIR . "system_log_icon.png",
                "hierarchical" => false,
            ));
            // add_action('rwmb_trigger_host_cfg_after_save_post', array(__CLASS__, "trigger_host_cfg_savebox"), 10, 1);
            add_filter("rwmb_meta_boxes", array(__CLASS__, "addRWMetabox"), 10, 1);
            //  add_action('rwmb_post_gift_meta_after_save_post', array(__CLASS__, "save_post_gift_meta"), 10, 1);
            $this->addAdminSupportMetabox();
            add_filter('manage_edit-' . HKM_GAME_CAMPAIGN . '_columns', array(__CLASS__, "add_new_columns"), 10, 1);
            add_action('manage_' . HKM_GAME_CAMPAIGN . '_posts_custom_column', array(__CLASS__, "manage_column"), 10, 2);
        }

        function __destruct()
        {
            $this->vcoin_panel_support = NULL;
        }

        protected function addAdminSupportMetabox()
        {
        }

        protected function add_tab()
        {
            $labels = array(
                "name" => _x("CAMPAIGN", "post type general name"),
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
                "menu_name" => __("CAMPAIGN", HKM_LANGUAGE_PACK)
            );
            return $labels;
        }

        public static function add_new_columns($new_columns)
        {
            $new_columns['cb'] = '<input type="checkbox" />';
            //  $new_columns['id'] = __('ID');
            //  $new_columns['title'] = _x('Mission Name', 'column name');

            // $new_columns['type'] = __('Trigger');
            // $new_columns['author'] = __('Author');
            $new_columns['categories'] = __('Categories');
            $new_columns['start_date'] = __('Start Date');
            $new_columns['end_date'] = __('End Date');
            $new_columns['total_votes'] = __('Total Votes');
            $new_columns['tags'] = __('Tags');
            $new_columns['date'] = _x('Date', 'column name');
            unset($new_columns['author']);
            unset($new_columns['date']);
            return $new_columns;
        }

        public static function manage_column($column_name, $id)
        {
            global $wpdb;
            switch ($column_name) {
                case 'start_date':
                    echo get_post_meta($id, "start_date", true);
                    break;
                case 'end_date':
                    echo get_post_meta($id, "end_date", true);
                    break;
                case 'total_votes':
                    echo get_post_meta($id, "total_votes", true);
                    break;
                case 'id':
                    echo $id;
                    break;

                default:
                    break;
            } // end switch
        }

        public static function addRWMetabox($meta_boxes)
        {
            $meta_boxes[] = array(
                'pages' => array(HKM_GAME_CAMPAIGN),
                //This is the id applied to the meta box
                'id' => 'campaign_wp_box',
                //This is the title that appears on the meta box container
                'title' => __('Campaign Configurations', HKM_LANGUAGE_PACK),
                //This defines the part of the page where the edit screen section should be shown
                'context' => 'normal',
                //This sets the priority within the context where the boxes should show
                'priority' => 'high',
                //Here we define all the fields we want in the meta box
                'fields' => array(
                    array(
                        'name' => __('Start Date', HKM_LANGUAGE_PACK),
                        'id' => "start_date",
                        'type' => 'date',
                        'options' => array(),
                    ),
                    array(
                        'name' => __('Due Date', HKM_LANGUAGE_PACK),
                        'id' => "end_date",
                        'type' => 'date',
                        'options' => array(),
                    ),
                    array(
                        'name' => __('Total Votes', HKM_LANGUAGE_PACK),
                        'id' => "total_votes",
                        'type' => 'number'
                    ),
                    array(
                        'name' => __('Active Campaign', HKM_LANGUAGE_PACK),
                        'id' => "campaign_active",
                        'type' => 'number'
                    ),
                    array(
                        'name' => __('Context', HKM_LANGUAGE_PACK),
                        'id' => "content_desc",
                        'type' => 'text',
                    ),

                )
            );

            $meta_boxes[] = array(
                'pages' => array(HKM_GAME_CAMPAIGN),
                //This is the id applied to the meta box
                'id' => 'campaign_i_box',
                //This is the title that appears on the meta box container
                'title' => __('campaign Ad', HKM_LANGUAGE_PACK),
                //This defines the part of the page where the edit screen section should be shown
                'context' => 'normal',
                //This sets the priority within the context where the boxes should show
                'priority' => 'high',
                //Here we define all the fields we want in the meta box
                'fields' => array(
                    array(
                        'name' => __('campaign Banner Horizontal', HKM_LANGUAGE_PACK),
                        'desc' => 'The horizontal banner for the mission <strong style="color:red">Suggested dimensions: 640px × 30px</strong>',
                        'id' => 'campaign_horizontal',
                        'type' => 'image_advanced',
                        'max_file_uploads' => 1,
                    ),
                    array(
                        'name' => __('campaign Banner Vertical', HKM_LANGUAGE_PACK),
                        'desc' => 'The horizontal banner for the mission <strong style="color:red">Suggested dimensions: 640px × 30px</strong>',
                        'id' => 'campaign_vertical',
                        'type' => 'image_advanced',
                        'max_file_uploads' => 1,
                    ),
                )
            );
            return $meta_boxes;
        }
    }
}