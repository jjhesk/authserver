/**
 * Created by Hesk on 14年8月18日.
 */

var search_api = {};
jQuery(function ($) {

    search_api = function (name_component) {
        this.store_id = store_id;
        this.textdesc = textdesc;
        this.icon_url = icon_id;
        this.name_component = name_component;
        this.app_name = appnamefield;

        this.init();
    };

    search_api.prototype = {
        init: function () {
            var d = this;

            d.init_select_module();
            $(".bigdrop").css({
                'max_height': 500
            });
            $("ul.select2-results").css({
                'max-height': 450
            });

        },
        init_select_module: function () {
            var d = this;
            d.select2module = $("#" + d.name_component).select2({
                fragmentPlatform: Handlebars.compile($("#t_ios").html()),
                placeholder: "Search for an app",
                minimumInputLength: 2,
                id: function (obj) {
                    return {id: obj._id};
                },
                ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
                    url: "https://itunes.apple.com/search/",
                    dataType: 'jsonp',
                    data: function (query, page) {
                        return {
                            term: query, // search term
                            entity: "software",
                            limit: 30
                        };
                    },
                    results: function (data, page) {
                        // parse the results into the format expected by Select2.
                        // since we are using custom formatting functions we do not need to alter remote JSON data
                        console.log(data);
                        return {results: data.results}
                    }
                },
                initSelection: function (element, callback) {
                    // the input tag has a value attribute preloaded that points to a preselected movie's id
                    // this function resolves that id attribute to an object that select2 can render
                    // using its formatResult renderer - that way the movie name is shown preselected
                    //  console.log(element);
                    var data = {id: element.val(), text: element.val()};
                    callback(data);
                    console.log(data);
                },
                selectOnBlur: true,
                formatResult: d.parseFormat, // omitted for brevity, see the source of this page
                formatSelection: d.parseFormatSelection,  // omitted for brevity, see the source of this page
                dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                closeOnSelect: true,
                multiple: false,
                escapeMarkup: function (m) {
                    return m;
                } // we do not want to escape markup since we are displaying html in results
            });
            d.select2module.on("select2-selecting", {that: d}, d.fill_in_app_data);
        },
        fill_in_app_data: function (e) {
            var d = e.data.that;
            d.app_name.val(JSON.stringify(e.choice.trackName).replace(/"/g, ""));
            d.store_id.val(JSON.stringify(e.choice.trackId));
            d.icon_url.val(JSON.stringify(e.choice.artworkUrl60).replace(/"/g, ""));
            d.textdesc.val(JSON.stringify(e.choice.description).replace(/"/g, "").replace(/\\n/g, " ").replace(/\\/g, ""));
        },
        parseFormat: function (item) {
            var d = this, markup = "<table class='result_table_listing'>", fragment = d.fragmentPlatform;
            if (item.trackName !== undefined && item.trackId !== undefined)
                markup += fragment(item);
            markup += "</table>";
            return markup;
        },
        parseFormatSelection: function (item) {
            $("#search_app").val(item.bundleId);
            return item.bundleId;
        }
    };

    var platform = $("#platform"),

        button_reg = $("#registerthisapp"),
        store_id = $("#store_id"),
        app_id = $("#app_id"),
        icon_id = $("#icon_url"),
        secret = $("#secret"),
        textdesc = $("#desc_area"),
        vcoin_amount = $("#vcoin_count"),
        vcoin_field = $("#add_vcoin"),
        vcoin_msg = $("#extra_coin_msg"),
        switch_input = $(".switch-input"),
        appnamefield = $("#app_name"),
        search = $("#search_app"),
        searchbar = new search_api("search_app"),
        row_search_app = $("#row_search_app"),
        row_search_enable = $("#row_search_enable"),
        cost = $("#cost"),
        cost_msg = $("#cost_msg"),
        available_coin = 0,
        updating_coin = false;

    update_coin();
    attach_check_event(appnamefield);
    attach_check_event(store_id);
    attach_check_event(icon_id);
    attach_check_event(textdesc);
    attach_check_event(vcoin_field);
    attach_check_event(cost);
    attach_check_event(platform);

    platform.on("change", function (e) {
        clear_input_val();
        if ($("option:selected", platform).val() == "ios") {
            row_search_enable.css("display", "");
            row_search_app.css("display", "");
            input_control(true);
        }
        else {
            row_search_enable.css("display", "none");
            row_search_app.css("display", "none");
            input_control(false);
        }
    });
    switch_input.on("change", function (e) {
        if (switch_input.is(':checked')) {
            row_search_app.css("display", "");
            input_control(true);
        }
        else {
            row_search_app.css("display", "none");
            input_control(false);
        }
    });
    search.on("change", function (e) {
        if ($("#select2-chosen-1").html() != "") {
            input_control(false);
        } else {
            input_control(true);
        }
    });

    function clear_input_val() {
        store_id.val("");
        icon_id.val("");
        textdesc.val("");
        appnamefield.val("");
        vcoin_field.val("");
        cost.val("");
        $("#select2-chosen-1").html("");
    }

    function input_control(boolean) {
        MetaBoxSupport.InputControlSingle(store_id, boolean);
        MetaBoxSupport.InputControlSingle(icon_id, boolean);
        MetaBoxSupport.InputControlSingle(textdesc, boolean);
        MetaBoxSupport.InputControlSingle(vcoin_field, boolean);
        MetaBoxSupport.InputControlSingle(cost, boolean);
        MetaBoxSupport.InputControlSingle(appnamefield, boolean);
    }

    function update_coin() {
        if (!updating_coin) {
            updating_coin = true;
            $.post("http://devlogin.vcoinapp.com/api/cms/available_coins/", {
                store_id: store_id.val()
            }).done(function (data) {
                if (data.result == "failure") {
                    alert("request failure.")
                } else {
                    if (data.result > 0) {
                        alert(data.msg);
                    } else {
                        available_coin = parseInt(data.obtain);
                        vcoin_amount.html(available_coin);
                    }
                }
                updating_coin = false;
            });
        }
    }

    function check_field_completion() {
        return (appnamefield.val() != "" && store_id.val() != "" && icon_id.val() != "" && textdesc.val() != ""
            && (Number(vcoin_field.val()) > 0) && (Number(cost.val()) >= 0 && cost.val() != ""))
    }

    function attach_check_event(field_obj) {
        if (field_obj === vcoin_field) {
            field_obj.on("change", function (e) {
                if (check_field_completion()) {
                    MetaBoxSupport.InputControlSingle(button_reg, false);
                } else MetaBoxSupport.InputControlSingle(button_reg, true);
                if (vcoin_field.val() > available_coin) {
                    vcoin_msg.html("exceed available amount");
                } else {
                    vcoin_msg.html("");
                }
            });
        }
        else if (field_obj === cost) {
            field_obj.on("change", function (e) {
                console.log("cost:" + cost.val());
                console.log("available_coin:" + available_coin);
                if (check_field_completion()) {
                    MetaBoxSupport.InputControlSingle(button_reg, false);
                } else MetaBoxSupport.InputControlSingle(button_reg, true);
                if (cost.val() > available_coin) {
                    cost_msg.html("Exceed available amount. Please assign again.");
                    cost_msg.css("color", "red");
                } else {
                    cost_msg.html("Cannot be bigger than the total amount of the coin.");
                    cost_msg.css("color", "black");
                }
            });
        }
        else if (field_obj === platform) {
            field_obj.on("change", function (e) {
                if (check_field_completion()) {
                    MetaBoxSupport.InputControlSingle(button_reg, false);
                } else MetaBoxSupport.InputControlSingle(button_reg, true);
            });
        }
        else {
            field_obj.on("keyup", function (e) {
                if (check_field_completion()) {
                    MetaBoxSupport.InputControlSingle(button_reg, false);
                } else MetaBoxSupport.InputControlSingle(button_reg, true);
            });
        }
    }

    button_reg.on("click", function (e) {
        if (Number(vcoin_field.val()) > 0 && vcoin_msg.html() == "" && available_coin > Number(cost.val())) {
            var api_domain = "http://devlogin.vcoinapp.com/api/";
            var widget_id = "registerthisapp",
                api_request = api_domain + "cms/register/";

            var loader_component = new AJAXLoader(widget_id, "normal", "app_reg");
            var ajax = new JAXAPIsupport(api_request, {
                store_id: store_id.val(),
                platform: $("option:selected", platform).val(),
                textdesc: textdesc.val(),
                icon: icon_id.val(),
                appname: appnamefield.val(),
                total_vcoin: vcoin_field.val(),
                single_vcoin: cost.val()
            }, {}, function (that, json) {
                if (json.result > 0) {
                    alert(json.msg);
                } else {
                    secret.html(json.secret);
                    app_id.html(json.key);

                    button_reg.val("success and go back");
                    button_reg.off("click");

                    button_reg.on("click", function (e) {
                        window.location = location.protocol + "//devlogin.vcoinapp.com/wp-admin/admin.php?page=appreg";
                    });
                }
            }, function (that, json_msg) {
                alert(json_msg);
            });
            ajax.add_loader(loader_component);
            ajax.init();
        } else alert("Please assign valid number of coins.")

    });
})
