<table id="table_incoming_cp" class="display" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th></th>
        <th>Applicant Name</th>
        <th>CP Approval No.</th>
        <th>Contact Email</th>
        <th>Contact Phone</th>
    </tr>
    </thead>

    <tfoot>
    <tr>
        <th></th>
        <th>Applicant Name</th>
        <th>CP Approval No.</th>
        <th>Contact Email</th>
        <th>Contact Phone</th>
    </tr>
    </tfoot>
</table>
<script id="action_bar_buttons" type="text/x-handlebars-template">
    <input id="action_approve-{{lid}}" type="button" class="button" value="Approve" onclick="approve({{lid}});"/>
    <input id="action_ignore-{{lid}}" type="button" class="button" value="Deny" onclick="reject({{lid}});"/></br>
    {{buttonlist}}
</script>