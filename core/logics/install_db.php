<?php

/**
 * Created by PhpStorm.
 * User: hesk
 * Date: 10/10/14
 * Time: 12:10 AM
 */
class install_db
{
    private $db;
    private $api_tables;

    function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->api_tables = array(
            'app_log' => $wpdb->prefix . 'app_log',
            'action_reward' => $wpdb->prefix . 'action_reward',
            'oauth_api_consumers' => $wpdb->prefix . 'oauth_api_consumers',
            'merchants' => $wpdb->prefix . 'merchants',
            'app_login_token_banks' => $wpdb->prefix . 'app_login_token_banks',
            'post_app_registration' => $wpdb->prefix . 'post_app_registration',
            'post_app_download' => $wpdb->prefix . 'post_app_download',
            'campaign_people' => $wpdb->prefix . 'campaign_people'
        );
    }

    /**
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

    public function fake_drop_table()
    {

    }

    public static function reg_hook($file_path)
    {
        $install_check = new self();
        $install_check->registration_plugin_hooks($file_path);
        $install_check = NULL;
    }

    public static function install_db_manually()
    {
        $k = new self();
        $k->create_tables();
        $k = NULL;
    }

    /**
     * drop tables
     */
    public function drop_tables()
    {
        foreach ($this->api_tables as $key => $table) {
            $this->db->query("DROP TABLE IF EXISTS {$table};");
        }
    }

    /**
     * tutorial to getting the table code on the console.
     * install console debug bar
     * go to debug on the top right hand corner
     * go click on the console tab
     * choose the SQL tab
     * type in..
     *
     * show create table vapp_app_login_token_banks
     * show create table vapp_app_app_log
     * show create table vapp_app_action_reward
     * show create table vapp_oauth_api_consumers
     * ...
     *
     * copy and paste the code from there to here
     *
     * remove ` character
     *
     * create tables
     */
    public function create_tables()
    {
        $charset_collate = '';
        if ($this->db->has_cap('collation')) {
            $charset_collate .= 'ENGINE=InnoDB AUTO_INCREMENT=727 ';
            if (!empty($this->db->charset))
                $charset_collate = 'DEFAULT CHARACTER SET ' . $this->db->charset;
            if (!empty($this->db->collate))
                $charset_collate .= ' COLLATE ' . $this->db->collate;
        }


        $this->db->query(
            "CREATE TABLE IF NOT EXISTS {$this->api_tables['app_log']} (
			 ID bigint(20) NOT NULL AUTO_INCREMENT,
			 user bigint(20) NOT NULL,
			 comments longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			 time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			 event_code bigint(20) NOT NULL,
			 error_code bigint(20) NOT NULL,
			 PRIMARY KEY (ID)
			) $charset_collate;"
        );

        $this->db->query(
            "CREATE TABLE IF NOT EXISTS {$this->api_tables['post_app_registration']} (
                 ID bigint(20) NOT NULL AUTO_INCREMENT,
                 post_id bigint(20) NOT NULL,
                 devuser bigint(20) NOT NULL,
                 devname varchar(60) NOT NULL,
                 icon varchar(300) NOT NULL,
                 vcoin_account varchar(50) NOT NULL,
                 status enum('pending','beta','launched','dead','removed','denied') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
                 app_title varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                 app_key varchar(100) NOT NULL, app_secret varchar(100) NOT NULL,
                 store_id varchar(200) NOT NULL,
                 description longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                 platform enum('ios','android','other') NOT NULL DEFAULT 'other',
                 deposit int(11) NOT NULL DEFAULT '0',
                 payout int(11) NOT NULL DEFAULT '0',
                 image_urls longtext NOT NULL,
                 regtime timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                 PRIMARY KEY (ID)
			) $charset_collate;"
        );

        $this->db->query(
            "CREATE TABLE IF NOT EXISTS {$this->api_tables['action_reward']} (
			ID bigint(20) NOT NULL AUTO_INCREMENT,
			user bigint(20) NOT NULL,
			action bigint(20) NOT NULL,
			reference varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			triggered timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			rewarded int(1) NOT NULL,
			mission_type int(5) NOT NULL,
			object_id bigint(20) NOT NULL,
			PRIMARY KEY (ID),
			KEY ID (ID)
			) $charset_collate;"
        );

        $this->db->query(
            "CREATE TABLE IF NOT EXISTS {$this->api_tables['post_app_download']} (
			 ID bigint(20) NOT NULL,
             download_user bigint(20) NOT NULL,
             app_key varchar(30) NOT NULL,
             triggered tinyint(4) NOT NULL,
             time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
			) $charset_collate;"
        );

        $this->db->query(
            "CREATE TABLE IF NOT EXISTS {$this->api_tables['oauth_api_consumers']} (
			 id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			 post_id bigint(20) NOT NULL,
			 user bigint(20) NOT NULL COMMENT 'developer user id',
			 icon varchar(200) NOT NULL,
			 vcoin_account varchar(36) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			 status enum('alive','dead','pending','removed','denied') NOT NULL,
			 name tinytext NOT NULL COMMENT 'store_id', oauthkey tinytext NOT NULL,
			 secret tinytext NOT NULL,
			 description longtext NOT NULL,
			 platform enum('ios','android') NOT NULL,
			 registration_data varchar(200) NOT NULL COMMENT 'the pending meta data',
			 PRIMARY KEY (id)
			) $charset_collate;"
        );

        $this->db->query(
            "CREATE TABLE IF NOT EXISTS {$this->api_tables['merchants']} (
            ID bigint(20) NOT NULL AUTO_INCREMENT,
            vendor_id bigint(20) NOT NULL COMMENT 'developer user ID',
            item_id bigint(20) NOT NULL,
            vcoin_account varchar(50) COLLATE utf8_bin NOT NULL,
            nature enum('COUPON','REWARD') COLLATE utf8_bin NOT NULL,
            PRIMARY KEY (ID),
            KEY user (vendor_id)
			) $charset_collate;"
        );

        $this->db->query(
            "CREATE TABLE IF NOT EXISTS {$this->api_tables['app_login_token_banks']} (
            ID bigint(20) NOT NULL AUTO_INCREMENT,
            consumerid bigint(20) NOT NULL DEFAULT '-1',
            user bigint(20) NOT NULL,
            token varchar(40) COLLATE utf8_bin NOT NULL,
            exp int(11) NOT NULL,
            create_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            KEY user (user),
            KEY ID (ID)
			) $charset_collate;"
        );

        $this->db->query(
            "CREATE TABLE IF NOT EXISTS {$this->api_tables['campaign_people']} (
             ID bigint(20) NOT NULL AUTO_INCREMENT,
             campagin_id bigint(20) NOT NULL,
             user_id bigint(20) NOT NULL,
             message text COLLATE utf8_unicode_ci NOT NULL,
             backers bigint(20) NOT NULL DEFAULT '0',
             flaged int(11) NOT NULL DEFAULT '-1',
             reward_gained int(11) NOT NULL DEFAULT '-1',
             reward_id int(11) NOT NULL DEFAULT '-1',
             coupon_id int(11) NOT NULL DEFAULT '-1',
             order int(11) NOT NULL,
             time_start timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
             PRIMARY KEY (ID),
             KEY ID (ID)
            ) $charset_collate;"
        );

        $this->db->query(
            "CREATE TABLE IF NOT EXISTS {$this->api_tables['campaign_relationship']} (
             ID bigint(20) NOT NULL AUTO_INCREMENT,
             backer_id bigint(20) NOT NULL,
             user_id bigint(20) NOT NULL,
             camp_id bigint(20) NOT NULL,
             PRIMARY KEY (ID)
            ) $charset_collate;"
        );

    }

} 