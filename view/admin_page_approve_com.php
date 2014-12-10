<table id="table_incoming_companoes" class="display" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th></th>
        <th><?php _e('BR No.', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Company Name', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Contact Name', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Contact Email', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Reps', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Remark', HKM_LANGUAGE_PACK); ?></th>
    </tr>
    </thead>

    <tfoot>
    <tr>
        <th></th>
        <th><?php _e('BR No.', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Company Name', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Contact Name', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Contact Email', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Reps', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Remark', HKM_LANGUAGE_PACK); ?></th>
    </tr>
    </tfoot>
</table>
<script id="action_bar_buttons" type="text/x-handlebars-template">
    <input id="action_approve-{{lid}}" type="button" class="button" value="<?php _e('Approve', HKM_LANGUAGE_PACK); ?>" onclick="approve({{lid}});"/>
    <input id="action_ignore-{{lid}}" type="button" class="button" value="<?php _e('Deny', HKM_LANGUAGE_PACK); ?>" onclick="reject({{lid}});"/>
    <input id="action_view_doc-{{lid}}" type="button" class="button" value="<?php _e('View BR Doc', HKM_LANGUAGE_PACK); ?>" onclick="viewdoc({{lid}});"/>
</script>
<script id="view_pdf_company_br" type="text/x-handlebars-template">
    <span>{{brno}}</span>
<!--    <a id="action_view_pdf-{{lid}}"
           class="button"
           value="pdf BR Doc"
           href="{{copy_br}}">view Doc</a>-->
</script>