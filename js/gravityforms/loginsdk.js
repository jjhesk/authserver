/**
 * user registration form
 * Created by Hesk on 14年9月8日.
 */
jQuery(function ($) {
    (function (d, helper, interactions, t) {
        var domain = window.location.origin;
        var lang = helper.getParamVal("lang"),
            _e = {
                cn: {
                    field_1_3: "email",
                    field_1_2: "Your name",
                    field_1_4: "password",
                    confirm_password_field: "confirm password",
                    label_5_1: "I agree to the terms and service <a href=\"" + domain + "/tc/\">read here</a>",
                    submit: "create account",
                    notification_check_confirm_password: "password confirmed",
                    notification_check_confirm_password_not: "password not confirmed"
                },
                ja: {
                    field_1_3: "email",
                    field_1_2: "email",
                    field_1_4: "email",
                    confirm_password_field: "email",
                    label_5_1: "email",
                    submit: "submit",
                    notification_check_confirm_password: "password confirmed",
                    notification_check_confirm_password_not: "password not confirmed"
                },
                default: {
                    field_1_3: "email",
                    field_1_2: "Your name",
                    field_1_4: "password",
                    confirm_password_field: "confirm password",
                    label_5_1: "I agree to the terms and service <a href=\"" + domain + "/tc/\">read here</a>",
                    submit: "create account",
                    notification_check_confirm_password: "password confirmed",
                    notification_check_confirm_password_not: "password not confirmed"
                }
            }

        function translate() {
            var $body = $(".gform_body"), $footer = $(".gform_footer");
            if (lang == "cn") {
                fieldwork($body, $footer, _e.cn);
            }
            if (lang == "ja") {
                fieldwork($body, $footer, _e.ja);
            }
        }

        function get_confirm(bool) {
            var k = {};
            if (lang == "cn") {
                k = _e.cn;
            } else if (lang == "ja") {
                k = _e.ja;
            } else {
                k = _e.default;
            }
            if (bool) {
                return k.notification_check_confirm_password;
            } else {
                return k.notification_check_confirm_password_not;
            }
        }

        //<label class="gfield_label" for="input_1_3">Email<span class="gfield_required">*</span></label>
        function fieldwork($body, $footer, object) {
            $.each(object, function (key, val) {
                var str = object[key],
                    submit_b = key == "submit",
                    ref = /field/g,
                    re = /label/g,
                    found_field = new String(key).match(ref),
                    found_label = new String(key).match(re);
                if (submit_b) {
                    var $locSub = $("input[type=submit]", $footer);
                    $locSub.val(str);
                } else if (found_label) {
                    var label = $("#" + key);
                    label.html(new String(str));
                } else if (found_field) {
                    var $loc = $("#" + key + ">label");
                    $loc.html(new String(str));
                } else {

                }
            });
        }

        function validation($confirm, e) {
            var $location = $(".gfield_description.validation_message", $confirm.closest(".gfield"));
            if (e) {
                $location.addClass("success");
            } else {
                $location.removeClass("success");
            }
            $location.html(get_confirm(e));
        }

        function init() {

            var sub_btn = $("#gform_submit_button_1"),
                $password = $("#input_1_4"),


                template = '<li id="confirm_password_field" class="gfield gfield_contains_required"><label class="gfield_label" for="confirm_password">Confirm Password<span class="gfield_required">*</span></label><div class="ginput_container"><input name="confirm_password" id="confirm_password" type="password" value="" class="large" tabindex="4"></div><div class="gfield_description validation_message"></div></li>',

                onclickevent = sub_btn.attr("onclick"),
                failureclick = "return false;"
                ;

            $password.closest(".gfield").after(template);
            var $confirm = $("#confirm_password");
            $confirm.on("keyup.check", function (e) {
                if (e.which == 13) {
                    e.preventDefault();
                }
                var enabled = $password.val() == $confirm.val() && new String($password.val()).length > 0;
                if (enabled) {
                    sub_btn.removeClass("disabled").attr("onclick", onclickevent);
                } else {
                    sub_btn.addClass("disabled").attr("onclick", failureclick);
                }
                validation($confirm, enabled);

                return true;
            });

            $confirm.val("");
            $password.val("");
            sub_btn.addClass("disabled").attr("onclick", failureclick);
            translate();
        }

        $(d).bind("gform_post_render", function (event, form_id, current_page) {
            var field_body = $("#gform_page_" + form_id + "_" + current_page + ".gform_page ul.gform_fields"),
                field_negviation = $("#gform_page_" + form_id + "_" + current_page + ".gform_page .gform_page_footer"),
                sub_btn = $("#gform_submit_button_1");
            init();
        });
        init();
    }(document, URL_Helper, "click tap touch", 1000));
});