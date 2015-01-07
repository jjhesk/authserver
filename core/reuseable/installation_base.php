<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 7/1/2015
 * Time: 11:34
 */
namespace core\reusable;
abstract class installation_base
{
    protected $db;
    protected $api_tables;


    /**
     * DO NOT EDIT
     * register the plugin hooks for the table actions
     * @param $file_location
     */
    public function registration_plugin_hooks($file_location)
    {
        if (function_exists('register_activation_hook'))
            register_activation_hook($file_location, array($this, 'create_tables'));
        if (function_exists('register_deactivation_hook'))
            register_deactivation_hook($file_location, array($this, 'fake_drop_table'));
    }


    /**
     * DO NOT EDIT
     * @param array $arr
     */
    protected function construct_api_table_list($arr = array())
    {
        foreach ($arr as $table_name) {
            $this->api_tables[$table_name] = $this->db->prefix . $table_name;
        }
    }

    /**
     * when you need to call the installation of the database manually
     * @return mixed
     */
    abstract public static function install_db_manually();

    /**
     * call function from the initiation form the core
     * @param $file_path
     * @return mixed
     */
    abstract public static function reg_hook($file_path);

    /**
     * drop tables when this plugin is deactivated
     * @return mixed
     */
    abstract protected function drop_tables();

    /**
     * if you do not want to drop the tables when the plugin is deactivated
     * @return mixed
     */
    abstract protected function fake_drop_table();

    /**
     * trigger of the initiation of the table to be created.
     * @return mixed
     */
    abstract public function create_tables();
}