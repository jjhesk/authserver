<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年7月31日
 * Time: 上午11:45
 */
if (!function_exists('plugin_is_active')) {
    function plugin_is_active($plugin_path)
    {
        $return_var = in_array($plugin_path, apply_filters('active_plugins', get_option('active_plugins')));
        return $return_var;
    }
}
if (!function_exists('get_oc_template')) {
    function get_oc_template($filename)
    {
        /** only used for plugin module **/
        $path = AUTH_SERVER_PATH . '/view/' . $filename . '.php';
        //  $path = locate_template('view/' . $filename . '.php', false);
        ob_start();
        // if (!empty($path))
        if (file_exists($path))
            load_template($path);
        else
            echo "no path defined::" . $path . " :filename::" . $filename;
        return ob_get_clean();
    }
}
if (!function_exists('get_oc_template_mustache') && class_exists("Mustache_Engine")) {
    function get_oc_template_mustache($filename, $context = array())
    {
        /** only available in theme **/
        // $path = locate_template(AUTH_SERVER_PATH . 'view/' . $filename . '.php', false);
        /** only used for plugin module **/
        $path = AUTH_SERVER_PATH . '/view/' . $filename . '.php';
        ob_start();
        /** only available in theme **/
        //  if (!empty($path)) {
        /** only used for plugin module **/
        if (file_exists($path)) {
            load_template($path);
            $field_message = ob_get_clean();
        } else {
            echo "no path defined::{" . $path . "}:filename::" . $filename;
            return ob_get_clean();
        }
        $must_ache = new Mustache_Engine;
        return $must_ache->render($field_message, $context);
    }
}
add_action('phpmailer_init', 'wpse8170_phpmailer_init');
function wpse8170_phpmailer_init(PHPMailer $phpmailer)
{
    $phpmailer->FromName = 'New H Innoactor';
    $phpmailer->From = 'no-reply@innoactor.com';
    $phpmailer->Hostname = 'innoactor.com';
    $phpmailer->Host = 'smtp-mail.outlook.com';
    $phpmailer->Port = 587; // could be different
    $phpmailer->Sender = 'admin@innoactor.com'; //reply to -email Sets the Sender email (Return-Path) of the message
    //=================================================
    $phpmailer->Username = 'admin@innoactor.com'; // if required
    $phpmailer->Password = '35832186'; // if required
    $phpmailer->SMTPAuth = true; // if required
    $phpmailer->SMTPSecure = 'tls'; // enable if required, 'tls' is another possible value
    $phpmailer->Debugoutput = 'error_log';
    $phpmailer->IsSMTP();
}
