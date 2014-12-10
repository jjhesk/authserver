<table id="user_page_claim_list" class="user_page_claim_list" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th><?php _e('Status', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Product', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('E-coupon', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Registered', HKM_LANGUAGE_PACK); ?></th>
    </tr>
    </thead>

    <tfoot>
    <tr>
        <th><?php _e('Status', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Product', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('E-coupon', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Registered', HKM_LANGUAGE_PACK); ?></th>
    </tr>
    </tfoot>
</table>
<table id="user_page_claim_list2" class="user_page_claim_list hidden" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th><?php _e('Transaction ID', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Time', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Amount', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Reference Code', HKM_LANGUAGE_PACK); ?></th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th><?php _e('Transaction ID', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Time', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Amount', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Reference Code', HKM_LANGUAGE_PACK); ?></th>
    </tr>
    </tfoot>
</table>
<script id="user_page_claim_list_button_v1" type="text/x-handlebars-template">
    <button id="button_hash" class="registration button" data-id="{{post_id}}"><?php _e('Reference Code', HKM_LANGUAGE_PACK); ?>Register</button>
</script>
<div id="user_page_claim_form" class="hidden">
    <?php //gravity_form(5, false, true, false, '', true); ?>
    <div class="gform_body">
        <ul id="gform_fields_5" class="gform_fields top_label description_below">
            <li id="field_5_5" class="gfield  gfield_contains_required"><label class="gfield_label" for="input_5_5">ID
                    <?php _e('Name', HKM_LANGUAGE_PACK); ?><span
                        class="gfield_required">*</span></label>

                <div class="ginput_container"><input name="input_5" id="input_5_5" type="text" value="" class="medium"
                                                     tabindex="1"></div>
            </li>
            <li id="field_5_1" class="gfield  gfield_contains_required"><label class="gfield_label"
                                                                               for="input_5_1"><?php _e('Hong Kong ID', HKM_LANGUAGE_PACK); ?><span
                        class="gfield_required">*</span></label>

                <div class="ginput_container">
                    <input name="input_1" id="input_5_1" type="text" value="" class="medium" tabindex="2"></div>
            </li>
            <li id="field_5_3" class="gfield"><label class="gfield_label" for="input_5_3"><?php _e('Phone', HKM_LANGUAGE_PACK); ?></label>

                <div class="ginput_container"><input name="input_3" id="input_5_3" type="text" value="" class="medium"
                                                     tabindex="3"></div>
            </li>
            <li id="field_5_4" class="gfield"><label class="gfield_label" for="input_5_4"><?php _e('e-coupon code', HKM_LANGUAGE_PACK); ?></label>

                <div class="ginput_container"><input name="input_4" id="input_5_4" type="text" value="" class="medium"
                                                     tabindex="4"></div>
            </li>
            <li id="field_5_6" class="gfield gform_hidden">
                <input name="input_6" id="input_5_6" type="hidden" class="gform_hidden" value=""></li>
        </ul>
    </div>
    <div class="gform_footer top_label">
        <button class="button btn-primary" id="submit"><?php _e('Submit', HKM_LANGUAGE_PACK); ?></button>
        <button class="button btn-primary" id="back"><?php _e('Reference Code', HKM_LANGUAGE_PACK); ?>back</button>
    </div>
</div>
