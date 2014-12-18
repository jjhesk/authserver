<script id="coin_history_template" type="text/x-handlebars-template">
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
</script>

<script id="coin_balance_template" type="text/x-handlebars-template">
    <table class="form-table" id="coin_balance_table">
        <tr>
            <th>Actions</th>
            <td>
                <button class="button" id="coin_bal_operation">Operate Beta Coin Balance</button>
            </td>
        </tr>
        <tr class="new_coin_request_box hidden" id="v_coin_operation_panel">
            <th>Balance Operation</th>
            <td>
                <p>VCoin: <span id="balance_field" class="balance"></span>
                    Last Update Time: <span id="last_update" class="balance"></span></p>
                <button class="button" id="refresh_bal">R</button>
                <input id="coin_input_val" class="small-text field" type="number" value=""/>
                <button id="execute_coin_operation" class="button add_coin_balance" data-id="">Operate VCoin server
                </button>
            </td>
        </tr>
    </table>
</script>
<style>
    input.regular-text.small {
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

<script id="coin_management_template" type="text/x-handlebars-template">
    <button style="margin-left: 20px" class="btn button button-primary" id="coin_management">Coin Management</button>
</script>

<script id="editor_back_btn" type="text/x-handlebars-template">
    <button class="btn button button-primary" id="back">Back</button>
</script>