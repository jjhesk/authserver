<?php
/**
 * Created by PhpStorm.
 * User: ryo
 * Date: 14年8月11日
 * Time: 下午5:20
 */

class init_table {

    private $prefix;

    public function __construct() {
        global $wpdb;
        $this->prefix = $wpdb->prefix;

        $this->table_app_bank();
        $this->table_app_log();
        $this->table_login_token_banks();
    }

    private function table_app_bank() {
        global $wpdb;
        $name = $this->prefix."app_bank";
        $sql = "CREATE TABLE IF NOT EXISTS `$name` ( `ID` bigint(20) NOT NULL AUTO_INCREMENT, `user` bigint(20) NOT NULL COMMENT 'developer user ID', `secret` varchar(36) NOT NULL, `token` varchar(36) NOT NULL, `app` enum('ios','android','unset') NOT NULL, `app_id` varchar(100) NOT NULL, PRIMARY KEY (`ID`), KEY `user` (`user`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        $wpdb->query($sql);
    }

    private function table_app_log() {
        global $wpdb;
        $name = $this->prefix."app_log";
        $sql = "CREATE TABLE IF NOT EXISTS `$name` ( `ID` bigint(20) NOT NULL AUTO_INCREMENT, `user` bigint(20) NOT NULL, `comments` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, `event_code` bigint(20) NOT NULL, `error_code` bigint(20) NOT NULL, PRIMARY KEY (`ID`) ) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1";
        $wpdb->query($sql);
    }
    private function table_login_token_banks () {
        global $wpdb;
        $name = $this->prefix."login_token_banks";
        $sql = "CREATE TABLE IF NOT EXISTS `$name` ( `ID` bigint(20) NOT NULL AUTO_INCREMENT, `user` bigint(20) NOT NULL, `token` varchar(40) COLLATE utf8_bin NOT NULL, `exp` int(11) NOT NULL, `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, KEY `user` (`user`), KEY `ID` (`ID`) ) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
        $wpdb->query($sql);
    }
} 