<!--<div id="example" class="k-content"></div>
<div id="grid"></div>
<div class="clear"></div>
<strong><u>News and Messages</u></strong>
<br>
<b>28/3/13</b> All CPs are expected to take the Monthly Exams before the last day of every month-->
<table id="table_job_application" class="display" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th></th>
        <th><?php _e('Job ID', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Release Time', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Location', HKM_LANGUAGE_PACK); ?></th>
    </tr>
    </thead>

    <tfoot>
    <tr>
        <th></th>
        <th><?php _e('Job ID', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Release Time', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Location', HKM_LANGUAGE_PACK); ?></th>
    </tr>
    </tfoot>
</table>
<script id="action_bar_buttons" type="text/x-handlebars-template">
    <div id="control-{{job_id}}">
        {{tag}}
    </div>
</script>

