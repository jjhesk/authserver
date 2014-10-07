/**
 * Created by Hesk on 14年8月18日.
 */

var search_api = {};
jQuery(function ($) {
    (function (d, interaction) {

        search_api = function (name_component, platform) {
            this.$platform_config = platform;
            this.platform_option = new String();
            this.store_id = store_id;
            this.textdesc = textdesc;
            this.icon_url = icon_id;
            this.name_component = name_component;

            this.init();
        };

        search_api.prototype = {
            init: function () {
                var d = this;

                //d.display_loading_gif(d);
                d.init_select_module();

                $(".bigdrop").css({
                    'max_height': 500
                });
                $("ul.select2-results").css({
                    'max-height': 450
                });
                ;

            },
            init_select_module: function () {
                var d = this;
                d.select2module = $("#" + d.name_component).select2({
                    fragmentPlatform: function () {
                        d.platform_option = $("option:selected", d.$platform_config).val();
                        if (d.platform_option == "ios")
                            return Handlebars.compile($("#t_ios").html());
                        else
                            return Handlebars.compile($("#t_android").html());
                    },
                    placeholder: "Search for an app",
                    minimumInputLength: 2,
                    id: function (obj) {
                        return {id: obj._id};
                    },
                    ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
                        url: function () {
                            d.platform_option = $("option:selected", d.$platform_config).val();
                            if (d.platform_option == "ios")
                                return "https://itunes.apple.com/search/";
                            else return "http://api.playstoreapi.com/v1.1/apps/" + $("#s2id_autogen1_search").val();
                        },
                        dataType: 'jsonp',
                        data: function (query, page) {
                            var d = this;
                            d.platform_option = $("option:selected", d.$platform_config).val();
                            if (d.platform_option == 'ios') {
                                return {
                                    term: query, // search term
                                    entity: "software",
                                    limit: 30
                                };
                            }
                            else if (d.platform_option == "android") {
                                return {
                                    key: "61de9e3b4507f0a7194036d99dd6034f"
                                };
                            }
                        },
                        results: function (data, page) {
                            // parse the results into the format expected by Select2.
                            // since we are using custom formatting functions we do not need to alter remote JSON data
                            console.log(data);
                            return {results: data.results};
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
                d.platform_option = $("option:selected", d.$platform_config).val();

                if (d.platform_option == "ios") {
                    d.store_id.val(JSON.stringify(e.choice.trackId));
                    d.icon_url.val(JSON.stringify(e.choice.artworkUrl60).replace(/"/g, ""));
                    d.textdesc.val(JSON.stringify(e.choice.description).replace(/"/g, "").replace(/\\n/g, " "));
                }
                else {
                    d.store_id.val(JSON.stringify(e.choice.appName).replace(/"/g, ""));
                    d.icon_url.val(JSON.stringify(e.choice.logo).replace(/"/g, ""));
                    d.textdesc.val(JSON.stringify(e.choice.description).replace(/"/g, "").replace(/\\n/g, " "));
                }
            },
            parseFormat: function (item) {
                var d = this, markup = "<table class='result_table_listing'>", fragment = d.fragmentPlatform();
                d.platform_option = $("option:selected", d.$platform_config).val();

                if (d.platform_option == "ios") {
                    if (item.trackName !== undefined && item.trackId !== undefined) {
                        markup += fragment(item);
                    }
                }
                else {
                    if (item.appName !== undefined) {
                        markup += fragment(item);
                    }
                }
                markup += "</table>";
                return markup;
            },
            parseFormatSelection: function (item) {
                console.log();
                d.platform_option = $("option:selected", d.$platform_config).val();

                $("#app_name").val(item.bundleId);

                if (d.platform_option == "ios")
                    return item.bundleId;
                else return item.package_name;

            }
        };

        var platform = $("#platform"),
            search = $("#app_name"),
            button_reg = $("#registerthisapp"),
            store_id = $("#store_id"),
            app_id = $("#app_id"),
            icon_id = $("#icon_url"),
            secret = $("#secret"),
            textdesc = $("#desc_area"),
            vcoin_amount = $("#vcoin_count"),
            vcoin_field = $("#add_vcoin"),
            vcoin_msg = $("#extra_coin_msg"),
            searchbar = new search_api("app_name", platform);
        var available_coin = 0, updating_coin = false;

        update_coin();

        search.on("change", function (e) {
            console.log(1241);
        });

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

        vcoin_field.on("change", function (e) {
            if (vcoin_field.val() > available_coin) {
                vcoin_msg.html("exceed available amount");
            } else {
                vcoin_msg.html("");
            }

        });

        button_reg.on("click", function (e) {
            if (vcoin_msg.html() == "") {
                new AJAXloader("#registerthisapp", "normal");
                $.post("http://devlogin.vcoinapp.com/api/cms/register/", {
                    store_id: store_id.val(),
                    platform: $("option:selected", platform).val(),
                    textdesc: textdesc.val(),
                    icon: icon_id.val(),
                    appname: search.val()
                }).done(function (data) {
                    console.log(data);
                    if (data.result == "failure") {
                        alert("failure from store ID.")
                    } else {
                        if (data.result > 0) {
                            alert(data.msg);
                        } else {
                            secret.html(data.obtain.secret);
                            app_id.html(data.obtain.appid);

                            button_reg.html("success and go back");
                            button_reg.off("click");

                            button_reg.on("click", function (e) {
                                window.location = location.protocol + "//devlogin.vcoinapp.com/wp-admin/admin.php?page=appreg";
                            });
                        }
                    }
                    // icon_id.val(data.obtain.key);
                });
            }
            else {
                alert("You have assigned Vcoin exceed your available amount. Please assign again.");
            }
        });

    }(document, "click tap touch"));
})
/*
 ;
 var jsonpcallbackk = function (data) {
 console.log(data);
 }*/
