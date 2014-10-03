/**
 * Created by Hesk on 14年9月8日.
 */
jQuery(function ($) {
    (function (d, wrapper, interactions, t) {
        $(d).bind("gform_post_render", function (event, form_id, current_page) {

            var field_body = $("#gform_page_" + form_id + "_" + current_page + ".gform_page ul.gform_fields");
            var field_negviation = $("#gform_page_" + form_id + "_" + current_page + ".gform_page .gform_page_footer");


        });
    }(document, ".gform_wrapper", "click tap touch", 1000));
    $("#field_1_7").hide();
    $("#field_1_1").hide();
});