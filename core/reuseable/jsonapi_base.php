<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 7/1/2015
 * Time: 12:55
 */
namespace core\reusable;
abstract class jsonapi_base
{


    /**
     * do not edit this
     */
    public function __construct()
    {
        $this->deploy_api();
    }


    /**
     * change the api name accordingly
     *
     */
    protected function deploy_api()
    {
        if ($this->is_da_plugin_active()) {
            $this->add_json_controllers();
            $this->deploy_add_filter();
        } else {
            $error = "Json-API is not activated please make sure that plugin is activated. Download it at http://wordpress.org/plugins/json-api/other_notes/";
            echo $error;
        }
    }

    private function deploy_add_filter()
    {
        add_filter('json_api_controllers', array($this, 'deploy_controllers'), 11, 1);
    }

    /**
     * to retrieve the json api path directly from the class
     * @return mixed
     */
    abstract public static function get_json_cal_api_path();

    /**
     * return array
     * @return mixed
     */
    abstract protected function get_controller_list();

    /**
     * return string
     * @return mixed
     */
    abstract public function add_json_controllers();

    /**
     * do not edit this
     */
    public function __destruct()
    {

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
     * do not edit this
     * @param $controllers
     * @return array
     */
    public function deploy_controllers($controllers)
    {
        $controllers = array_merge($controllers, $this->get_controller_list());
        \inno_log_db::log_vcoin_error(-1, 19901, print_r($controllers, true));
        return $controllers;
    }

}