<?php
/**
 * Created by PhpStorm.
 * User: ryo
 * Date: 14年9月25日
 * Time: 下午5:03
 */

defined("ABSPATH") || exit;
if (!class_exists("dashboardMeta")) {

    class dashboardMeta
    {
        public function __construct(WP_User $user)
        {
            $this->user = $user;
        }

        public function get_pending_post_num()
        {
            $total_apps = api_cms_server::crosscms("get_developer_related_app_ids", array(
                "id" => $this->user->ID
            ), array(CURLOPT_TIMEOUT => 10));

            $all_pending_apps = api_cms_server::crosscms("get_app_post_id", array(
                "post_status" => "pending"
            ), array(CURLOPT_TIMEOUT => 10));

            $developer_app_related_ids = array();
            foreach ($total_apps as $key => $value) {
                foreach ($value as $field_key => $app_id) {
                    $developer_app_related_ids[] = $app_id;
                }
            }

            $listed_app_ids = array();
            foreach ($all_pending_apps as $key => $value) {
                foreach ($value as $field_key => $app_id) {
                    $listed_app_ids[] = $app_id;
                }
            }
            $count_listed_app = 0;
            foreach ($developer_app_related_ids as $dev_key => $app_id) {
                foreach ($listed_app_ids as $pending_key => $pending_id) {
                    if ($app_id == $pending_id) {
                        $count_listed_app++;
                        break;
                    }
                }
            }
            return $count_listed_app;
        }

        public function get_listed_post_num()
        {
            $total_apps = api_cms_server::crosscms("get_developer_related_app_ids", array(
                "id" => $this->user->ID
            ), array(CURLOPT_TIMEOUT => 10));

            $all_listed_apps = api_cms_server::crosscms("get_app_post_id", array(
                "post_status" => "publish"
            ), array(CURLOPT_TIMEOUT => 10));

            $developer_app_related_ids = array();
            foreach ($total_apps as $key => $value) {
                foreach ($value as $field_key => $app_id) {
                    $developer_app_related_ids[] = $app_id;
                }
            }

            $listed_app_ids = array();
            foreach ($all_listed_apps as $key => $value) {
                foreach ($value as $field_key => $app_id) {
                    $listed_app_ids[] = $app_id;
                }
            }
            $count_listed_app = 0;
            foreach ($developer_app_related_ids as $dev_key => $app_id) {
                foreach ($listed_app_ids as $pending_key => $pending_id) {
                    if ($app_id == $pending_id) {
                        $count_listed_app++;
                        break;
                    }
                }
            }
            return $count_listed_app;
        }

        public function get_current_user_meta($field_name)
        {
            return get_user_meta($this->user->ID, $field_name, true);
        }
    }
}