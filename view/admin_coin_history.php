<table id="coin_history" class="app_reg_table" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th><?php _e("Transaction ID", HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e("Time", HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e("Amount", HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e("Reference Code", HKM_LANGUAGE_PACK); ?></th>
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
<style>input.regular-text.small {
        width: 85px;
    }
</style>
<script id="editor_controller_bar" type="text/x-handlebars-template">
    <button class="btn button" id="check_today" value="spot"><?php _e("Today", HKM_LANGUAGE_PACK); ?></button>
    <button class="btn button" id="check_week" value="week"><?php _e("This Week", HKM_LANGUAGE_PACK); ?></button>
    <button class="btn button" id="check_month" value="month"><?php _e("This Month", HKM_LANGUAGE_PACK); ?></button>
    <button class="btn button" id="check_3month" value="3mo"><?php _e("Past Three Month", HKM_LANGUAGE_PACK); ?></button>
    <button class="btn button" id="check_6month" value="6mo"><?php _e("Past Six Month", HKM_LANGUAGE_PACK); ?></button>
    <button class="btn button" id="check_yr" value="year"><?php _e("This Year", HKM_LANGUAGE_PACK); ?></button>
    <button class="btn button" id="check_custom" value="custom"><?php _e("Custom", HKM_LANGUAGE_PACK); ?></button>
</script>

<script id="editor_datepicker" type="text/x-handlebars-template">
    <p>
        <button class="btn button" id="back_to_choice"><?php _e("Back to choices", HKM_LANGUAGE_PACK); ?></button>
        <?php _e("Start Day:", HKM_LANGUAGE_PACK); ?><input type="text" class="regular-text small" id="datepicker_start">
        <?php _e("End Day:", HKM_LANGUAGE_PACK); ?><input type="text" class="regular-text small" id="datepicker_end">
        <button class="btn button" id="confirm"><?php _e("Confirm", HKM_LANGUAGE_PACK); ?></button>
    </p>
</script>