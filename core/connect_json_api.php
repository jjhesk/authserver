<?php
/**
 * Created by PhpStorm. 2013 @ imusictech
 * developed by Heskeyo Kam
 * User: Hesk
 * Date: 13年12月4日
 * Time: 下午3:16
 * Prevent loading this file directly
 * source: http://wordpress.org/plugins/json-api/other_notes/
 *
 * 5.2. Developing JSON API controllers
 * Creating a controller
 * To start a new JSON API controller, create a file called hello.php inside wp-content/plugins/json-api/controllers. Add the following class definition:
 *
 * <-php
 *
 * class JSON_API_Hello_Controller {
 *
 * public function hello_world() {
 * return array(
 * "message" => "Hello, world"
 * );
 * }
 *
 * }
 *
 * ->
 *
 *
 */
defined('ABSPATH') || exit;
if (!class_exists('connect_json_api')) {
    class connect_json_api
    {
        public function __construct()
        {
            $this->start_api_interface();
        }

        public static function init()
        {
            new self();
        }

        public static function getpath()
        {
            return CORE_PATH . "api/";
        }

        private $list_functions;

        private function start_api_interface()
        {
           // global $json_module_init_path;
            if ($this->is_da_plugin_active()) {
                $this->list_functions = array(
                    'personal',
                    'authen',
                    'email',
                    'systemlog',
                    'vcoin',
                    'cms',
                    'mission',
                    'redemption'
                );
                add_filter('json_api_email_controller_path', function () {
                    return connect_json_api::getpath() . 'email.php';
                });
                add_filter('json_api_systemlog_controller_path', function () {
                    return connect_json_api::getpath() . 'systemlog.php';
                });
                add_filter('json_api_vcoin_controller_path', function () {
                    return connect_json_api::getpath() . 'vcoin.php';
                });
                add_filter('json_api_cms_controller_path', function () {
                    return connect_json_api::getpath() . 'cms.php';
                });
                add_filter('json_api_personal_controller_path', function () {
                    return connect_json_api::getpath() . 'personal.php';
                });
                add_filter('json_api_mission_controller_path', function () {
                    return connect_json_api::getpath() . 'mission.php';
                });
                add_filter('json_api_redemption_controller_path', function () {
                    return connect_json_api::getpath() . 'redemption.php';
                });
                add_filter('json_api_authen_controller_path', function () {
                    return connect_json_api::getpath() . 'authen.php';
                });
                add_filter('json_api_controllers', array($this, 'add_json_controllers'), 10, 1);
            } else {
                $error = "Json-API is not activated please make sure that plugin is activated. Download it at http://wordpress.org/plugins/json-api/other_notes/";
                echo $error;
            }
        }

        /**
         * @param $controllers
         * @return array
         */
        public function add_json_controller_new($controllers)
        {
            $controllers = array_merge($controllers, $this->list_functions);
            return $controllers;
        }

        /**
         * @param $controllers
         * @internal param $existing_controllers
         * @return array
         */
        public function add_json_controllers($controllers)
        {
            //$additional_controllers = array('channels', 'slide', 'email', 'staff', 'redemption', 'innopost');
            $controllers = array_merge($controllers, $this->list_functions);
            return $controllers;
        }

        /**
         * @return mixed
         */
        private function is_da_plugin_active()
        {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
            return is_plugin_active('json-api/json-api.php');
        }

        /**
         * not in use first
         */
        public function add_to_core_functions()
        {
            // Disable get_author_index method (e.g., for security reasons)
            add_action('json_api-core-get_channel_from_core', 'my_disable_author_index');
            function my_disable_author_index()
            {
                // Stop execution
                exit;
            }
        }


    }
}


