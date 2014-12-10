<table id="admin_page_app_reg" class="app_reg_table" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th><?php _e('Edit', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('ID', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('User', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('User Name', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Status', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Store ID', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('App Key', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('App Secret', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Platform', HKM_LANGUAGE_PACK); ?></th>
    </tr>
    </thead>

    <tfoot>
    <tr>
        <th><?php _e('Edit', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('ID', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('User', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('User Name', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Status', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Store ID', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('App Key', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('App Secret', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Platform', HKM_LANGUAGE_PACK); ?></th>
    </tr>
    </tfoot>
</table>
<table id="coin_history" class="app_reg_table hidden" cellspacing="0" width="100%">
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

<script id="editor_back_btn" type="text/x-handlebars-template">
    <button class="btn button button-primary" id="back"><?php _e('Back', HKM_LANGUAGE_PACK); ?></button>
</script>

<script id="editor_controller_bar" type="text/x-handlebars-template">
    <button class="btn button" id="check_today" value="spot"><?php _e('Today', HKM_LANGUAGE_PACK); ?></button>
    <button class="btn button" id="check_week" value="week"><?php _e('This Week', HKM_LANGUAGE_PACK); ?></button>
    <button class="btn button" id="check_month" value="month"><?php _e('This Month', HKM_LANGUAGE_PACK); ?></button>
    <button class="btn button" id="check_3month" value="3mo"><?php _e('Past Three Month', HKM_LANGUAGE_PACK); ?></button>
    <button class="btn button" id="check_6month" value="6mo"><?php _e('Past Six Month', HKM_LANGUAGE_PACK); ?></button>
    <button class="btn button" id="check_yr" value="year"><?php _e('This Year', HKM_LANGUAGE_PACK); ?></button>
    <button class="btn button" id="check_custom" value="custom"><?php _e('Custom', HKM_LANGUAGE_PACK); ?></button>
</script>

<script id="editor_datepicker" type="text/x-handlebars-template">
    <p>
        <button class="btn button" id="back_to_choice"><?php _e('Back to choice', HKM_LANGUAGE_PACK); ?></button>
        <?php _e('Start Day:', HKM_LANGUAGE_PACK); ?><input type="text" id="datepicker_start">
        <?php _e('End Day:', HKM_LANGUAGE_PACK); ?><input type="text" id="datepicker_end">
        <button class="btn button" id="confirm"><?php _e('Confirm', HKM_LANGUAGE_PACK); ?></button>
    </p>
</script>

<script id="editor_btns" type="text/x-handlebars-template">
    <button class="btn button" id="status_all"><?php _e('All', HKM_LANGUAGE_PACK); ?></button>
    <button class="btn button" id="status_dead"><?php _e('Dead', HKM_LANGUAGE_PACK); ?></button>
    <button class="btn button" id="status_launched"><?php _e('Launched', HKM_LANGUAGE_PACK); ?></button>
    <button class="btn button" id="status_pending"><?php _e('Pending', HKM_LANGUAGE_PACK); ?></button>
    <button class="btn button" id="status_beta"><?php _e('Beta', HKM_LANGUAGE_PACK); ?></button>
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
            <th><?php _e('Decision', HKM_LANGUAGE_PACK); ?></th>
            <td>
                <button class="button action_disable_application" data-id="{{post_id}}"><?php _e('Disable Application', HKM_LANGUAGE_PACK); ?></button>
                <button class="button action_view_coin_history" data-uuid="{{vcoin_account}}" data-id="{{post_id}}"><?php _e('View Coin History', HKM_LANGUAGE_PACK); ?>
                </button>
                <button class="button action_edit_app" data-id="{{post_id}}"><?php _e('Edit App', HKM_LANGUAGE_PACK); ?></button>
            </td>
        </tr>
    </table>
</script>
<script id="editor_childrow_beta" type="text/x-handlebars-template">
    <table class="form-table">
        <tr>
            <th><?php _e('Application Title', HKM_LANGUAGE_PACK); ?></th>
            <td>{{app_title}}</td>
        </tr>
        <tr>
            <th><?php _e('Decision', HKM_LANGUAGE_PACK); ?></th>
            <td>
                <button class="button action_launch_application" data-id="{{ID}}"><?php _e('Launch Application', HKM_LANGUAGE_PACK); ?></button>
                <button class="button action_view_coin_history" data-uuid="{{vcoin_account}}" data-id="{{post_id}}"><?php _e('View Coin History', HKM_LANGUAGE_PACK); ?>
                </button>
                <button class="button action_request_new_coin_limit" data-id="{{ID}}"><?php _e('Request Coin Limit', HKM_LANGUAGE_PACK); ?></button>
                <button class="button action_edit_app" data-id="{{post_id}}"><?php _e('Edit App', HKM_LANGUAGE_PACK); ?></button>
            </td>
        </tr>
        <tr class="hidden new_coin_request_box">
            <th><?php _e('Request new beta coin', HKM_LANGUAGE_PACK); ?></th>
            <td>
                <button class="button confirm_request_new_coin" data-id="{{ID}}"><?php _e('Confirm Coin Request', HKM_LANGUAGE_PACK); ?></button>
            </td>
        </tr>
    </table>
</script>
<script id="editor_childrow_dead" type="text/x-handlebars-template">
    <table class="form-table">
        <tr>
            <th><?php _e('Application Title', HKM_LANGUAGE_PACK); ?></th>
            <td>{{app_title}}</td>
        </tr>
        <tr>
            <th><?php _e('Decision', HKM_LANGUAGE_PACK); ?></th>
            <td>
                <button class="button action_view_coin_history" data-uuid="{{vcoin_account}}" data-id="{{post_id}}"><?php _e('View Coin History', HKM_LANGUAGE_PACK); ?></button>
                <button class="button action_remove_application" data-id="{{post_id}}"><?php _e('Remove Application', HKM_LANGUAGE_PACK); ?></button>
                <button class="button action_enable_application" data-id="{{post_id}}"><?php _e('Enable Application', HKM_LANGUAGE_PACK); ?></button>
                </button>
            </td>
        </tr>
    </table>
</script>
<script id="editor_childrow_pending" type="text/x-handlebars-template">
    <table class="form-table">
        <tr>
            <th><?php _e('Application Title', HKM_LANGUAGE_PACK); ?></th>
            <td>{{app_title}}</td>
        </tr>
        <tr>
            <th><?php _e('Decision', HKM_LANGUAGE_PACK); ?></th>
            <td>
                <button class="button action_withdraw_application" data-id="{{ID}}"><?php _e('Cancel Submission', HKM_LANGUAGE_PACK); ?></button>
                </button>
            </td>
        </tr>
    </table>
</script>
<script id="editor_control_admin" type="text/x-handlebars-template">
    <table class="form-table">
        <tr>
            <th><?php _e('Decision', HKM_LANGUAGE_PACK); ?></th>
            <td>
                <button class="button action_approve_application" data-id="{{ID}}"><?php _e('Approve this Submission', HKM_LANGUAGE_PACK); ?></button>
                <button class="button action_reject_application" data-id="{{post_id}}"><?php _e('Disapprove this Submission', HKM_LANGUAGE_PACK); ?>
                </button>
            </td>
        </tr>
        <tr class="hidden reason_display">
            <th><?php _e('Common Reasons', HKM_LANGUAGE_PACK); ?></th>
            <td><select name="commonreason" id="commonreason">
                <option value="r1"><?php _e('Reasons 1', HKM_LANGUAGE_PACK); ?></option>
                <option value="r2"><?php _e('Reasons 2', HKM_LANGUAGE_PACK); ?></option>
                <option value="r3"><?php _e('Reasons 3', HKM_LANGUAGE_PACK); ?></option>
            </select></td>
        </tr>
        <tr class="hidden reason_display">
            <th><?php _e('Other Reasons', HKM_LANGUAGE_PACK); ?></th>
            <td><textarea name="otherreason" id="otherreason"></textarea></td>
        </tr>
        <tr class="hidden reason_display">
            <th></th>
            <td>
                <button class="button action_reject_confirm_application" data-id="{{ID}}"><?php _e('Confirm to disapprove this Submission', HKM_LANGUAGE_PACK); ?>
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
            <th><?php _e('Application Title', HKM_LANGUAGE_PACK); ?></th>
            <td>{{app_title}}</td>
        </tr>
        <tr>
            <th><?php _e('Description', HKM_LANGUAGE_PACK); ?></th>
            <td>{{description}}</td>
        </tr>
        <tr>
            <th><?php _e('Icon', HKM_LANGUAGE_PACK); ?></th>
            <td><img src="{{icon}}" width="100px" height="100px"/></td>
        </tr>
        <tr>
            <th><?php _e('Promotion Screenshots', HKM_LANGUAGE_PACK); ?></th>
            <td>
                <div class="slickerslider"></div>
            </td>
        </tr>
        <tr>
            <th><?php _e('Deposit', HKM_LANGUAGE_PACK); ?></th>
            <td>{{deposit}}</td>
        </tr>
        <tr>
            <th><?php _e('Coin Dispense Each Download', HKM_LANGUAGE_PACK); ?></th>
            <td>{{payout}}</td>
        </tr>
    </table>
    {{control_admin}}
</script>
<script id="editor_childrow_admin_beta" type="text/x-handlebars-template">
    <table class="form-table">
        <tr>
            <th><?php _e('Application Title', HKM_LANGUAGE_PACK); ?></th>
            <td>{{app_title}}</td>
        </tr>
        <tr>
            <th><?php _e('Actions', HKM_LANGUAGE_PACK); ?></th>
            <td>
                <button class="button action_view_coin_history" data-uuid="{{vcoin_account}}" data-id="{{post_id}}"><?php _e('View Coin History', HKM_LANGUAGE_PACK); ?>
                </button>
                <button class="button action_reveal_operation_panel" data-id="{{ID}}"><?php _e('Operate Beta Coin Balance', HKM_LANGUAGE_PACK); ?></button>
            </td>
        </tr>
        <tr class="hidden new_coin_request_box" id="v_coin_operation_panel">
            <th><?php _e('Balance Operation', HKM_LANGUAGE_PACK); ?></th>
            <td>
                <p><?php _e('VCoin:', HKM_LANGUAGE_PACK); ?> <span id="balance_field" class="balance"></span> Deposit: <span id="balance_field_deposit"
                                                                                          class="balance"></span></p>
                <button class="button" id="refresh_bal"><?php _e('R', HKM_LANGUAGE_PACK); ?></button>
                <input class="small-text field" type="number" value=""/>
                <button class="button add_coin_balance" data-id="{{ID}}"><?php _e('Operate VCoin server', HKM_LANGUAGE_PACK); ?></button>
            </td>
        </tr>
    </table>
</script>
<script id="editor_admin_beta" type="text/x-handlebars-template">
    <table class="form-table">
        <tr>
            <th><?php _e('Application Title', HKM_LANGUAGE_PACK); ?></th>
            <td>{{app_title}}</td>
        </tr>
        <tr>
            <th>Actions</th>
            <td>
                <button class="button action_view_coin_history" data-uuid="{{vcoin_account}}" data-id="{{post_id}}"><?php _e('View Coin History', HKM_LANGUAGE_PACK); ?>
                </button>
            </td>
        </tr>
    </table>
</script>

<table id="edit_app_table" class="form-table hidden" cellspacing="10">
    <tr>
        <th><?php _e('Application Name', HKM_LANGUAGE_PACK); ?></th>
        <td><input type="text" id="app_name" name="app_name" required="true" class="regular-text"/></td>
    </tr>
    <tr>
        <th><?php _e('Store ID', HKM_LANGUAGE_PACK); ?></th>
        <td><input name="store_id" id="store_id" type="text" required="true" class="regular-text"/>
        </td>
    </tr>
    <tr>
        <th><?php _e('Icon', HKM_LANGUAGE_PACK); ?></th>
        <td>
            <div>
                <input name="icon_url" id="icon_url" type="text" required="true" class="regular-text add_image_url"/>
                <a class="button button-large remodal-bg browse_image_button" href="#modal"><?php _e('Browse', HKM_LANGUAGE_PACK); ?></a>

                <div class="remodal" data-remodal-id="modal">
                    <div id="image_holder"></div>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th><?php _e('Description', HKM_LANGUAGE_PACK); ?></th>
        <td><textarea id="desc_area" rows="4" cols="50" required="true"></textarea></td>
    </tr>
    <tr>
        <th><?php _e('Add Extra Image', HKM_LANGUAGE_PACK); ?></th>
        <td id="image_row">
        </td>
    </tr>
    <tr>
        <th></th>
        <td>
            <input type="button" class="button button-primary back_to_log" value="<?php _e('Back', HKM_LANGUAGE_PACK); ?>">
            <input type="button" class="button button-primary" id="update_app" value="<?php _e('Update', HKM_LANGUAGE_PACK); ?>">
        </td>
    </tr>
</table>

<script id="image_edit_row" type="text/x-handlebars-template">
    <div>
        <td>
            <input type="text" class="regular-text add_image_url" value="{{image_url}}"/>
            <button class="button add_image_url_button">+</button>
            <button class="button remove_image_url_button">-</button>
            <a class="button button-large remodal-bg browse_image_button" href="#modal"><?php _e('Browse', HKM_LANGUAGE_PACK); ?></a>
        </td>
    </div>
</script>