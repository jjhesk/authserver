/**
 * Created by ryo on 14年9月2日.
 */
var approve = approve || {},
    reject = reject || {}, action_view_doc = action_view_doc || {};
var setting_ob = setting_ob || {};
jQuery(function ($) {
    (function (d, interaction, table) {
        var $table = $(table),
            domain = "http://devlogin.vcoinapp.com/";
        $table.dataTable({
            processing: true,
            ajax: domain + "api/systemlog/app_reg_log/",
            columns: [
                { data: "id" },
                { data: "user" },
                { data: "status",
                    render: function (data, type, full, meta) {
                        return data;
                    }},
                { data: "name" },
                { data: "oauthkey" },
                { data: "secret" },
                { data: "description" },
                { data: "platform" }
            ],
            "initComplete": function (settings, json) {
                $table.css({
                    'text-align': 'center',
                    'font-size': '14px'
                });

                if (setting_ob.role == "developer"){
                    $table.DataTable().column(1).visible(false);
                    $table.DataTable().column(6).visible(false);
                }
            }
        });


    }(document, "click tap touch", "#admin_page_app_reg"));
});

