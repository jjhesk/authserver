<table id="table_incoming_cr" class="display" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th></th>
        <th><?php _e('Applicant Name', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Contact Phone', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Contact Email', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Company', HKM_LANGUAGE_PACK); ?></th>
    </tr>
    </thead>

    <tfoot>
    <tr>
        <th></th>
        <th></th>
        <th><?php _e('Applicant Name', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Contact Phone', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Contact Email', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Company', HKM_LANGUAGE_PACK); ?></th>
    </tr>
    </tfoot>
</table>
<script id="action_bar_buttons" type="text/x-handlebars-template">
    <input id="action_approve-{{lid}}" type="button" class="button" value="<?php _e('Approve', HKM_LANGUAGE_PACK); ?>" onclick="approve({{lid}});"/>
    <input id="action_ignore-{{lid}}" type="button" class="button" value="<?php _e('Deny', HKM_LANGUAGE_PACK); ?>" onclick="reject({{lid}});"/>
</script>