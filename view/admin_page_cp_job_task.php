<!--<div id="example" class="k-content"></div>
<div id="grid"></div>
<div class="clear"></div>
<strong><u>News and Messages</u></strong>
<br>
<b>28/3/13</b> All CPs are expected to take the Monthly Exams before the last day of every month-->
<table id="table_job_task" class="display" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th></th>
        <th><?php _e('Job ID', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Project ID', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Job Status', HKM_LANGUAGE_PACK); ?></th>
    </tr>
    </thead>

    <tfoot>
    <tr>
        <th></th>
        <th><?php _e('Job ID', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Project ID', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Job Status', HKM_LANGUAGE_PACK); ?></th>
    </tr>
    </tfoot>
</table>
<script id="action_bar_buttons" type="text/x-handlebars-template">
    <input id="action_viewtask-{{lid}}" type="button" class="button" value="<?php _e('View', HKM_LANGUAGE_PACK); ?>" onclick="ViewJob({{lid}});"/>
</script>
