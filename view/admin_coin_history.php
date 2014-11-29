<table id="coin_history" class="app_reg_table" cellspacing="0" width="100%">
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
<style>input.regular-text.small {
        width: 85px;
    }
</style>
<script id="editor_controller_bar" type="text/x-handlebars-template">
    <button class="btn button" id="check_today" value="spot">Today</button>
    <button class="btn button" id="check_week" value="week">This Week</button>
    <button class="btn button" id="check_month" value="month">This Month</button>
    <button class="btn button" id="check_3month" value="3mo">Past Three Month</button>
    <button class="btn button" id="check_6month" value="6mo">Past Six Month</button>
    <button class="btn button" id="check_yr" value="year">This Year</button>
    <button class="btn button" id="check_custom" value="custom">Custom</button>
</script>

<script id="editor_datepicker" type="text/x-handlebars-template">
    <p>
        <button class="btn button" id="back_to_choice">Back to choices</button>
        Start Day:<input type="text" class="regular-text small" id="datepicker_start">
        End Day:<input type="text" class="regular-text small" id="datepicker_end">
        <button class="btn button" id="confirm">Confirm</button>
    </p>
</script>