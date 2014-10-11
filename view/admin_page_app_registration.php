<table id="admin_page_app_reg" class="app_reg_table" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Edit</th>
        <th>ID</th>
        <th>User</th>
        <th>Status</th>
        <th>Store ID</th>
        <th>App Key</th>
        <th>App Secret</th>
        <th>Description</th>
        <th>Platform</th>
    </tr>
    </thead>

    <tfoot>
    <tr>
        <th>Edit</th>
        <th>ID</th>
        <th>User</th>
        <th>Status</th>
        <th>Store ID</th>
        <th>App Key</th>
        <th>App Secret</th>
        <th>Description</th>
        <th>Platform</th>
    </tr>
    </tfoot>
</table>
<script id="editor_details_template" type="text/x-handlebars-template">
    <button class="button view_actions" data-id="{{id}}">More</button>
</script>
<script id="editor_childrow_alive" type="text/x-handlebars-template">
    <button class="button action_disable_application" data-id="{{post_id}}">Disable Application</button>
</script>
<script id="editor_childrow_dead" type="text/x-handlebars-template">
    <button class="button action_remove_application" data-id="{{post_id}}">Remove Application</button>
</script>
<script id="editor_childrow_pending" type="text/x-handlebars-template">
    <button class="button action_withdraw_application" data-id="{{post_id}}">Cancel Submission</button>
</script>
<script id="editor_childrow_____nothing_todo" type="text/x-handlebars-template">
    <button class="button action_withdraw_application" data-id="{{id}}">Cancel Submission</button>
    <button class="button action_remove_application" data-id="{{id}}">Remove Application</button>
    <button class="button action_enable_application" data-id="{{id}}">Enable Application</button>
    <button class="button action_disable_application" data-id="{{id}}">Disable Application</button>
</script>