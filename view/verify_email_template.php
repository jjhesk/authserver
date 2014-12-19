<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年9月26日
 * Time: 上午11:19
 */

get_header('email_respnse_name');
$style_uri = get_template_directory_uri();
//if ($logo = get_option('dp_login_logo')) {
?>
<style>html{overflow:hidden; background: url("<? echo $style_uri; ?>/vcoin/event_bg.png") no-repeat scroll  center center #393045 !important;}</style>
    <div id="confirmmsgafterregister" class="confirmmsgafterregister verifyemailmsg">{{message}}</div>
<?php
//}
get_footer('email_respnse_name');

