<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年9月26日
 * Time: 上午11:19
 */

get_header('email_respnse_name');
//if ($logo = get_option('dp_login_logo')) {
?>
    <div class="notice"><img class="aligncenter" src=""/>
        <span class="aligncenter">{{message}}</span>
    </div>
<?php
//}
get_footer('email_respnse_name');

