/**
 * Created by ryo on 14年9月25日.
 */

var setting_ob = setting_ob || {};

jQuery(function ($) {
    var vendor_location_template_output = Handlebars.compile($("#account_status_template").html());

    var api_domain = "http://devlogin.vcoinapp.com/api/";

    var set_content = {company_name: setting_ob.company_name, app_coins: setting_ob.app_coins};
    JAXAPIsupport(api_domain + "cms/dashboard_request_pending_post_num/", {}, set_content, function (that, json) {

        var pending_number = {app_pending_count: Number(json)};
        $.extend(set_content, pending_number);

        JAXAPIsupport(api_domain + "cms/dashboard_request_listed_post_num/", {}, set_content, function (that, json) {

            var listed_number = {app_listed_count: Number(json)};
            $.extend(set_content, listed_number);

            var content = vendor_location_template_output(set_content);

            var $template_content = $("<div></div>");

            $template_content.html(content);

            $("#vcoin_plan_review").append($template_content);
        });

    });

});