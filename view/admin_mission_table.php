<table id="mission_table" class="display" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th><?php _e('ID', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('User', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Time', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Formats', HKM_LANGUAGE_PACK); ?></th>
    </tr>
    </thead>

    <tfoot>
    <tr>
        <th><?php _e('ID', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('User', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Time', HKM_LANGUAGE_PACK); ?></th>
        <th><?php _e('Formats', HKM_LANGUAGE_PACK); ?></th>
    </tr>
    </tfoot>
</table>
<script id="action_bar_col_format" type="text/x-handlebars-template">
    <input id="format_1_{{r_id}}"  type="button" class="button" value="PDF" onclick="DOWNLOAD_FILE_IN_(\"PDF\", {{r_id}});"/>
    <input id="format_2_{{r_id}}"  type="button" class="button" value="DOCX" onclick="DOWNLOAD_FILE_IN_(\"PDF\", {{r_id}});"/>
    <input id="format_3_{{r_id}}"  type="button" class="button" value="XML" onclick="DOWNLOAD_FILE_IN_(\"PDF\", {{r_id}});"/>
    <input id="format_4_{{r_id}}"  type="button" class="button" value="JSON" onclick="DOWNLOAD_FILE_IN_(\"PDF\", {{r_id}});"/>
</script>
