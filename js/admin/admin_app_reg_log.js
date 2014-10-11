/**
 * Created by ryo on 14年9月2日.
 */
var approve = approve || {},
    reject = reject || {}, action_view_doc = action_view_doc || {};
var setting_ob = setting_ob || {};
jQuery(function ($) {
    (function (d, interaction, table) {
        var $table = $(table),
            domain = location.protocol + "//devlogin.vcoinapp.com/",
            actionsTemplate = Handlebars.compile($("#editor_details_template").html());

        var return_data = {
            successcb: function (that, data) {
                that.addClass("disabled");
                console.log("===== disabled data =====");
                console.log(data);
            }
        }
        var init_tiny_panel = function ($tr) {
            console.log("init_tiny_panel");
            var $button_withdraw = $('.action_withdraw_application', $tr),
                $button_remove = $('.action_remove_application', $tr),
                $button_disable = $('.action_disable_application', $tr);
            //               loader = new AJAXLoader(tr, "big", "app_reg");
            $button_disable.off(interaction);
            $button_remove.off(interaction);
            $button_disable.off(interaction);
            $button_disable.on(interaction, function (e) {
                var d = $(this), id = d.attr("data-id");
            });
            $button_remove.on(interaction, function (e) {
                var d = $(this), id = d.attr("data-id");
            });
            $button_withdraw.on(interaction, function (e) {
                console.log("withdraw");
                var d = $(this), id = d.attr("data-id");
                // var enter = new JAXAPIsupport(domain + "api/cms/remove_pending_app", {"post_id": id}, d, return_data.successcb);
                //  enter.add_loader(loader);
                // enter.init();
            });
            console.log("end init_tiny_panel");
        }
        var init_buttons = function (oSettings) {
            var $button = $('td.details_editor .view_actions', table);
            $button.off("cancel_reveal");
            $button.off(interaction);
            $button.on("cancel_reveal", function (e, param_id) {
                e.preventDefault();
                var tr = $(this).closest('tr'), row = $table.dataTable().api().row(tr), id = Number($(this).attr("data-id"));
                // console.log("id and param id... " + id + ":" + param_id);
                if (row.child.isShown() && id != param_id) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
            });
            $button.on(interaction, function (e) {
                e.preventDefault();
                var tr = $(this).closest('tr'), row = $table.dataTable().api().row(tr), data = row.data();
                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                    console.log("1 bar now");
                } else {
                    var comp = Handlebars.compile($("#editor_childrow_" + data.status).html());
                    // Open this row
                    row.child(comp(data)).show(10,function(){
                        init_tiny_panel(tr);
                    });
                    tr.addClass('shown');
                }
                $('td.details_editor .view_actions', table).trigger("cancel_reveal", [data.id]);
            });

        }
        $table.dataTable({
            processing: true,
            ajax: domain + "api/systemlog/app_reg_log/",
            columns: [
                {
                    class: "details_editor",
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return actionsTemplate(full);
                    }
                },
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
                if (setting_ob.role == "developer") {
                    $table.DataTable().column(2).visible(false);
                    $table.DataTable().column(7).visible(false);
                }
            },
            "fnDrawCallback": init_buttons

        });


    }(document, "click tap touch", "#admin_page_app_reg"));
});

