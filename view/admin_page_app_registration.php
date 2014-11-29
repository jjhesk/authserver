<table id="admin_page_app_reg" class="app_reg_table" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Edit</th>
        <th>ID</th>
        <th>User</th>
        <th>User Name</th>
        <th>Status</th>
        <th>Store ID</th>
        <th>App Key</th>
        <th>App Secret</th>
        <th>Platform</th>
    </tr>
    </thead>

    <tfoot>
    <tr>
        <th>Edit</th>
        <th>ID</th>
        <th>User</th>
        <th>User Name</th>
        <th>Status</th>
        <th>Store ID</th>
        <th>App Key</th>
        <th>App Secret</th>
        <th>Platform</th>
    </tr>
    </tfoot>
</table>
<table id="coin_history" class="app_reg_table hidden" cellspacing="0" width="100%">
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

<script id="editor_back_btn" type="text/x-handlebars-template">
    <button class="btn button button-primary" id="back">Back</button>
</script>

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
        <button class="btn button" id="back_to_choice">Back to choice</button>
        Start Day:<input type="text" id="datepicker_start">
        End Day:<input type="text" id="datepicker_end">
        <button class="btn button" id="confirm">Confirm</button>
    </p>
</script>

<script id="editor_btns" type="text/x-handlebars-template">
    <button class="btn button" id="status_all">All</button>
    <button class="btn button" id="status_dead">Dead</button>
    <button class="btn button" id="status_launched">Launched</button>
    <button class="btn button" id="status_pending">Pending</button>
    <button class="btn button" id="status_beta">Beta</button>
</script>
<script id="editor_details_template" type="text/x-handlebars-template">
    <button class="button view_actions" data-id="{{ID}}">+</button>
</script>
<script id="editor_childrow_launched" type="text/x-handlebars-template">
    <table class="form-table">
        <tr>
            <th>Application Title</th>
            <td>{{app_title}}</td>
        </tr>
        <tr>
            <th>Decision</th>
            <td>
                <button class="button action_disable_application" data-id="{{post_id}}">Disable Application</button>
                <button class="button action_view_coin_history" data-uuid="{{vcoin_account}}" data-id="{{post_id}}">View
                    Coin History
                </button>
                <button class="button action_edit_app" data-id="{{post_id}}">Edit App</button>
            </td>
        </tr>
    </table>
</script>
<script id="editor_childrow_beta" type="text/x-handlebars-template">
    <table class="form-table">
        <tr>
            <th>Application Title</th>
            <td>{{app_title}}</td>
        </tr>
        <tr>
            <th>Decision</th>
            <td>
                <button class="button action_launch_application" data-id="{{ID}}">Launch Application</button>
                <button class="button action_view_coin_history" data-uuid="{{vcoin_account}}" data-id="{{post_id}}">View
                    Coin History
                </button>
                <button class="button action_request_new_coin_limit" data-id="{{ID}}">Request Coin Limit</button>
                <button class="button action_edit_app" data-id="{{post_id}}">Edit App</button>
            </td>
        </tr>
        <tr class="hidden new_coin_request_box">
            <th>Request new beta coin</th>
            <td>
                <button class="button confirm_request_new_coin" data-id="{{ID}}">Confirm Coin Request</button>
            </td>
        </tr>
    </table>
</script>
<script id="editor_childrow_dead" type="text/x-handlebars-template">
    <table class="form-table">
        <tr>
            <th>Application Title</th>
            <td>{{app_title}}</td>
        </tr>
        <tr>
            <th>Decision</th>
            <td>
                <button class="button action_view_coin_history" data-uuid="{{vcoin_account}}" data-id="{{post_id}}">View
                    Coin History
                </button>
                <button class="button action_remove_application" data-id="{{post_id}}">Remove Application</button>
                <button class="button action_enable_application" data-id="{{post_id}}">Enable Application</button>
                </button>
            </td>
        </tr>
    </table>
</script>
<script id="editor_childrow_pending" type="text/x-handlebars-template">
    <table class="form-table">
        <tr>
            <th>Application Title</th>
            <td>{{app_title}}</td>
        </tr>
        <tr>
            <th>Decision</th>
            <td>
                <button class="button action_withdraw_application" data-id="{{ID}}">Cancel Submission</button>
                </button>
            </td>
        </tr>
    </table>
</script>
<script id="editor_control_admin" type="text/x-handlebars-template">
    <table class="form-table">
        <tr>
            <th>Decision</th>
            <td>
                <button class="button action_approve_application" data-id="{{ID}}">Approve this Submission</button>
                <button class="button action_reject_application" data-id="{{post_id}}">Disapprove this Submission
                </button>
            </td>
        </tr>
        <tr class="hidden reason_display">
            <th>Common Reasons</th>
            <td><select name="commonreason" id="commonreason">
                <option value="r1">Reasons 1</option>
                <option value="r2">Reasons 2</option>
                <option value="r3">Reasons 3</option>
            </select></td>
        </tr>
        <tr class="hidden reason_display">
            <th>Other Reasons</th>
            <td><textarea name="otherreason" id="otherreason"></textarea></td>
        </tr>
        <tr class="hidden reason_display">
            <th></th>
            <td>
                <button class="button action_reject_confirm_application" data-id="{{ID}}">Confirm to disapprove this
                    Submission
                </button>
            </td>
        </tr>
        <tr class="loading_bar">
            <th></th>
            <td id="clloader"></td>
        </tr>
    </table>
</script>
<script id="editor_childrow_admin_pending" type="text/x-handlebars-template">
    <table class="form-table">
        <tr>
            <th>Application Title</th>
            <td>{{app_title}}</td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{{description}}</td>
        </tr>
        <tr>
            <th>Icon</th>
            <td><img src="{{icon}}" width="100px" height="100px"/></td>
        </tr>
        <tr>
            <th>Promotion Screenshots</th>
            <td>
                <div class="slickerslider"></div>
            </td>
        </tr>
        <tr>
            <th>Deposit</th>
            <td>{{deposit}}</td>
        </tr>
        <tr>
            <th>Coin Dispense Each Download</th>
            <td>{{payout}}</td>
        </tr>
    </table>
    {{control_admin}}
</script>
<script id="editor_childrow_admin_beta" type="text/x-handlebars-template">
    <table class="form-table">
        <tr>
            <th>Application Title</th>
            <td>{{app_title}}</td>
        </tr>
        <tr>
            <th>Actions</th>
            <td>
                <button class="button action_view_coin_history" data-uuid="{{vcoin_account}}" data-id="{{post_id}}">View
                    Coin History
                </button>
                <button class="button action_reveal_operation_panel" data-id="{{ID}}">Operate Beta Coin Balance</button>
            </td>
        </tr>
        <tr class="hidden new_coin_request_box" id="v_coin_operation_panel">
            <th>Balance Operation</th>
            <td>
                <p>VCoin: <span id="balance_field" class="balance"></span> Deposit: <span id="balance_field_deposit"
                                                                                          class="balance"></span></p>
                <button class="button" id="refresh_bal">R</button>
                <input class="small-text field" type="number" value=""/>
                <button class="button add_coin_balance" data-id="{{ID}}">Operate VCoin server</button>
            </td>
        </tr>
    </table>
</script>
<script id="editor_admin_beta" type="text/x-handlebars-template">
    <table class="form-table">
        <tr>
            <th>Application Title</th>
            <td>{{app_title}}</td>
        </tr>
        <tr>
            <th>Actions</th>
            <td>
                <button class="button action_view_coin_history" data-uuid="{{vcoin_account}}" data-id="{{post_id}}">View
                    Coin History
                </button>
            </td>
        </tr>
    </table>
</script>

<table id="edit_app_table" class="form-table hidden" cellspacing="10">
    <tr>
        <th>Application Name</th>
        <td><input type="text" id="app_name" name="app_name" required="true" class="regular-text"/></td>
    </tr>
    <tr>
        <th>Store ID</th>
        <td><input name="store_id" id="store_id" type="text" required="true" class="regular-text"/>
        </td>
    </tr>
    <tr>
        <th>Icon</th>
        <td>
            <div>
                <input name="icon_url" id="icon_url" type="text" required="true" class="regular-text add_image_url"/>
                <a class="button button-large remodal-bg browse_image_button" href="#modal">Browse</a>

                <div class="remodal" data-remodal-id="modal">
                    <div id="image_holder"></div>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th>Description</th>
        <td><textarea id="desc_area" rows="4" cols="50" required="true"></textarea></td>
    </tr>
    <tr>
        <th>Add Extra Image</th>
        <td id="image_row">
        </td>
    </tr>
    <tr>
        <th></th>
        <td>
            <input type="button" class="button button-primary back_to_log" value="Back">
            <input type="button" class="button button-primary" id="update_app" value="Update">
        </td>
    </tr>
</table>

<script id="image_edit_row" type="text/x-handlebars-template">
    <div>
        <td>
            <input type="text" class="regular-text add_image_url" value="{{image_url}}"/>
            <button class="button add_image_url_button">+</button>
            <button class="button remove_image_url_button">-</button>
            <a class="button button-large remodal-bg browse_image_button" href="#modal">Browse</a>
        </td>
    </div>
</script>