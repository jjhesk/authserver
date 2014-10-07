/**
 * Created by Hesk on 14年10月6日.
 */
var setting_ob = setting_ob || {};

jQuery(function ($) {

    var api_domain = "http://devlogin.vcoinapp.com/api/",
        widget_id = "vcoin_user_coin_review",
        $widget = $("#" + widget_id),
        api_request = api_domain + "cms/get_coin_by_user/",
        update_val_from_ajax_el = $(".wallet .value", $widget),

        loading = new WidgetDashBoardLoader(widget_id),
        ajax = new JAXAPIsupport(api_request, {}, {},
            function (that, json) {
                update_val_from_ajax_el.html(json.coin);
            }, function (that, message) {
                update_val_from_ajax_el.html(message);
            });
    ajax.add_loader(loading);
    ajax.init();

});