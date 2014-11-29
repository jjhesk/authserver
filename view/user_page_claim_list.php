<table id="user_page_claim_list" class="user_page_claim_list" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th><?php _e("Status", HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e("Product"); ?></th>
        <th><?php _e("E-Coupon"); ?></th>
        <th><?php _e("Register"); ?></th>
    </tr>
    </thead>

    <tfoot>
    <tr>
        <th><?php _e("Status"); ?></th>
        <th><?php _e("Product"); ?></th>
        <th><?php _e("E-Coupon"); ?></th>
        <th><?php _e("Register"); ?></th>
    </tr>
    </tfoot>
</table>
<table id="user_page_claim_list2" class="user_page_claim_list hidden" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Transaction ID</th>
        <th>Time</th>
        <th>Amount</th>
        <th>Reference Code</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>Transaction ID</th>
        <th>Time</th>
        <th>Amount</th>
        <th>Reference Code</th>
    </tr>
    </tfoot>
</table>
<script id="user_page_claim_list_button_v1" type="text/x-handlebars-template">
    <button id="button_hash" class="registration button" data-id="{{post_id}}">Register</button>
</script>
<div id="user_page_claim_form" class="hidden">
    <?php //gravity_form(5, false, true, false, '', true); ?>
    <div class="gform_body">
        <ul id="gform_fields_5" class="gform_fields top_label description_below">
            <li id="field_5_5" class="gfield  gfield_contains_required"><label class="gfield_label" for="input_5_5">ID
                    Name<span
                        class="gfield_required">*</span></label>

                <div class="ginput_container"><input name="input_5" id="input_5_5" type="text" value="" class="medium"
                                                     tabindex="1"></div>
            </li>
            <li id="field_5_1" class="gfield  gfield_contains_required"><label class="gfield_label"
                                                                               for="input_5_1">Hong Kong ID<span
                        class="gfield_required">*</span></label>

                <div class="ginput_container">
                    <input name="input_1" id="input_5_1" type="text" value="" class="medium" tabindex="2"></div>
            </li>
            <li id="field_5_3" class="gfield"><label class="gfield_label" for="input_5_3">Phone</label>

                <div class="ginput_container"><input name="input_3" id="input_5_3" type="text" value="" class="medium"
                                                     tabindex="3"></div>
            </li>
            <li id="field_5_4" class="gfield"><label class="gfield_label" for="input_5_4">e-coupon code</label>

                <div class="ginput_container"><input name="input_4" id="input_5_4" type="text" value="" class="medium"
                                                     tabindex="4"></div>
            </li>
            <li id="field_5_6" class="gfield gform_hidden">
                <input name="input_6" id="input_5_6" type="hidden" class="gform_hidden" value=""></li>
        </ul>
    </div>
    <div class="gform_footer top_label">
        <button class="button btn-primary" id="submit">Submit</button>
        <button class="button btn-primary" id="back">back</button>
    </div>
</div>
