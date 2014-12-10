<div class="grid adminsupport-wrapper cpstatus">
    <table id="report_template_list_tb" class="display" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th></th>
            <th><?php _e('Name', HKM_LANGUAGE_PACK); ?></th>
            <th><?php _e('Cate.', HKM_LANGUAGE_PACK); ?></th>
            <th><?php _e('ID', HKM_LANGUAGE_PACK); ?></th>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <th></th>
            <th><?php _e('Name', HKM_LANGUAGE_PACK); ?></th>
            <th><?php _e('Cate.', HKM_LANGUAGE_PACK); ?></th>
            <th><?php _e('ID', HKM_LANGUAGE_PACK); ?></th>
        </tr>
        </tfoot>
    </table>
    <script id="action_bar_buttons" type="text/x-handlebars-template">
        <input id="action_addpage-{{id}}"
               type="button"
               onclick="template_controller.click_function_add_page({{id}});"
               class="button addpagebutton"
               value="<?php _e('Add Page', HKM_LANGUAGE_PACK); ?>"/>
    </script>
    <script id="button_image" type="text/x-handlebars-template">
        <a id="basemap-{{attachmentid}}"
           class="button" target="_BLANK" href="{{pointer_url}}">{{attachmentid}}</a>
    </script>
</div>
<div class="rwmb-field rwmb-text-wrapper">
    <div class="rwmb-label">
        <label for="drawmap"><?php _e('draw maps', HKM_LANGUAGE_PACK); ?></label></div>
    <div class="rwmb-input drawmapbuttons">

    </div>
</div>
<div class="rwmb-field rwmb-text-wrapper">
    <div class="rwmb-label">
        <label for="site-photo"><?php _e('site photos', HKM_LANGUAGE_PACK); ?></label></div>
    <div class="rwmb-input sitephotobuttons">

    </div>
</div>