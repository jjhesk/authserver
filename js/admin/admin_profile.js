/**
 * Created by ryo on 14年9月22日.
 */

jQuery(function ($) {

    $.each($(".hidden_field_switcher"), function (h) {
        new Switcher($(this));
    });

    new CoinHistory("coin_history");
});