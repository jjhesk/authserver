<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年9月25日
 * Time: 下午4:43
 */
defined("ABSPATH") || exit;
if (!class_exists('settings_vcoin')) {
    class settings_vcoin
    {
        private $defaultSettings = array(
            'name' => '', // Name of the menu item
            'title' => '', // Title displayed on the top of the admin panel
            'parent' => null, // id of parent, if blank, then this is a top level menu
            'id' => '', // Unique ID of the menu item
            'capability' => 'manage_options', // User role
            'icon' => 'dashicons-admin-generic', // Menu icon for top level menus only http://melchoyce.github.io/dashicons/
            'position' => null, // Menu position. Can be used for both top and sub level menus
            'use_form' => true, // If false, options will not be wrapped in a form
        );
        protected $titan;
        private $adminPanel, $tab;

        public function __construct()
        {
      //      add_action('tf_create_options', array($this, 'createMyOptions'));
           $this->createMyOptions();
        }

        public function createMyOptions()
        {
            // Initialize Titan & options here
            $titan = TitanFramework::getInstance('vcoinsetting');
            $this->init($titan);
        }

        public function save_thme_setting_data()
        {
            $kvTextOption = $this->titan->getAllOptions();
        }


        private function tab()
        {
            $this->tab = $this->adminPanel->createTab(array(
                'name' => 'Social',
            ));
            $this->tab->createOption(array(
                'name' => 'twitter english',
                'type' => 'text',
                'id' => 'twitter_en',
            ));

            $this->tab->createOption(
                array(
                    'name' => 'twitter japanese',
                    'type' => 'text',
                    'id' => 'twitter_ja',

                ));

            $this->tab->createOption(array(
                'name' => 'twitter chinese',
                'type' => 'text',
                'id' => 'twitter_cn',

            ));

            $this->tab->createOption(array(
                'name' => 'twitter others',
                'type' => 'text',
                'id' => 'twitter_extra',

            ));
            $this->tab->createOption(
                array(
                    'name' => 'facebook english',
                    'type' => 'text',
                    'id' => 'fb_en',
                ));
            $this->tab->createOption(
                array(
                    'name' => 'facebook japanese',
                    'type' => 'text',
                    'id' => 'fb_ja',
                ));
            $this->tab->createOption(array(
                'name' => 'facebook chinese',
                'type' => 'text',
                'id' => 'fb_cn',
            ));
            $this->tab->createOption(array(
                'name' => 'facebook others',
                'type' => 'text',
                'id' => 'fb_extra',
            ));


            $this->tab->createOption(
                array(
                    'name' => 'g+ english',
                    'type' => 'text',
                    'id' => 'gplus_en', 'desc' => 'demo .. '
                ));
            $this->tab->createOption(
                array(
                    'name' => 'g+ japanese',
                    'type' => 'text',
                    'id' => 'gplus_ja', 'desc' => 'demo .. '
                ));
            $this->tab->createOption(array(
                'name' => 'g+ chinese',
                'type' => 'text',
                'id' => 'gplus_cn', 'desc' => 'demo .. '
            ));
            $this->tab->createOption(array(
                'name' => 'g+ others',
                'type' => 'text',
                'id' => 'gplus_extra',
                'desc' => 'demo .. '
            ));

            $this->tab->createOption(array(
                'type' => 'save'
            ));


        }

        private function init(TitanFramework $instance)
        {
            $this->titan = $instance;
            $this->adminPanel = $instance->createAdminPanel(array(
                'name' => 'Admin Vcoin Settings',
            ));
            $this->tab();
            $this->adminPanel->createOption(array(
                'type' => 'save'
            ));
            // add_action('wp_head', array($this, 'save_thme_setting_data'));
        }
    }


}