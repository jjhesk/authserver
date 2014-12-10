<script id="t_ios" type="text/x-handlebars-template">
    <tr class="selection_row">
        <td><img src="{{artworkUrl60}}"/></td>
        <td>
            {{trackName}}<br>by {{artistName}} <br><span class="small">{{bundleId}}</span>
        </td>
    </tr>
</script>
<script id="t_android" type="text/x-handlebars-template">
    <tr class="selection_row">
        <td>
            {{title}}<br>by {{developer}} <br><span class="small">{{itemID}}</span>
        </td>
    </tr>
</script>
<script id="t_images" type="text/x-handlebars-template">
    <div><img data-lazy="{{url}}" alt=""/></div>
</script>
<table class="form-table" cellspacing="10">
    <tr>
        <th><?php _e('Platform', HKM_LANGUAGE_PACK); ?></th>
        <td><?php
            echo ui_handler::ui_select_creation(array(
                "ios" => "iOS",
                "android" => "Android"
            ), "platform", "select", "platform");
            ?></td>
    </tr>

    <tr id="row_search_enable" class="hidden">
        <th><?php _e('Search Enable', HKM_LANGUAGE_PACK); ?></th>
        <td>
            <input class="hidden_field_switcher" type="hidden" name="allow_search" value="1">

            <div class="container">
                <label class="switch">
                    <input type="checkbox" class="switch-input" checked="">
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                </label>
            </div>
            <span class="description">
                <?php _e('Not live: The app has not been submitted or approved yet.', HKM_LANGUAGE_PACK); ?>
                <?php _e('Live: The app has been approved, and is currently listed in the App Store.', HKM_LANGUAGE_PACK); ?>
            </span>
        </td>
    </tr>

    <tr id="ios_row_search_app" class="hidden">
        <th><?php _e('Search App', HKM_LANGUAGE_PACK); ?></th>
        <td><input type="hidden" id="search_app" name="search_app"/></td>
    </tr>
    <tr id="android_row_search_app" class="hidden">
        <th><?php _e('Search App', HKM_LANGUAGE_PACK); ?></th>
        <td><input type="hidden" id="android_search_app" name="android_search_app"/></td>
    </tr>

    <tr>
        <th><?php _e('Application Name', HKM_LANGUAGE_PACK); ?></th>
        <td><input readonly type="text" id="app_name" name="app_name" required="true" class="regular-text"/></td>
    </tr>
    <tr>
        <th><?php _e('Store ID', HKM_LANGUAGE_PACK); ?></th>
        <td><input readonly name="store_id" id="store_id" type="text" required="true" class="regular-text"/></td>
    </tr>
    <tr>
        <th><?php _e('Icon', HKM_LANGUAGE_PACK); ?></th>
        <td><input readonly name="icon_url" id="icon_url" type="text" required="true" class="regular-text"/></td>
    </tr>
    <tr>
        <th><?php _e('Description', HKM_LANGUAGE_PACK); ?></th>
        <td><textarea disabled id="desc_area" rows="4" cols="50" required="true"></textarea></td>
    </tr>

    <tr>
        <th><?php _e('Screen Shots', HKM_LANGUAGE_PACK); ?></th>
        <td>
            <div class="slickerslider"></div>
        </td>
    </tr>
    <tr>
        <th><?php _e('Add Extra Image', HKM_LANGUAGE_PACK); ?></th>
        <td>
            <input type="text" class="regular-text" id="add_image_url"/>
            <button class="button" id="add_image_url_button">Add Image Url</button><button class="button" id="remove_image_sh"><?php _e('Remove', HKM_LANGUAGE_PACK); ?></button>
        </td>
    </tr>
    <?php
    $settings = TitanFramework::getInstance("vcoinset");
    $beta_coin = (int)$settings->getOption("app_coin_beta");
    ?>
    <tr>
        <th><?php _e('Assign the total amount for this application', HKM_LANGUAGE_PACK); ?></th>
        <td><input disabled name="add_vcoin" id="add_vcoin" type="number"/>
            <span><?php _e('The available coins are', HKM_LANGUAGE_PACK); ?> <b id="vcoin_count"></b>
                <b style="color:red; font-weight: bold;" id="extra_coin_msg"></b>. <?php _e('Please also notice that the first', HKM_LANGUAGE_PACK); ?>
                <b style="color:red; font-weight: bold;" id="beta_thread_hold"><?= $beta_coin; ?></b> <?php _e('vcoin will be assigned into the Beta status testing.', HKM_LANGUAGE_PACK); ?></span>
        </td>
    </tr>
    <tr>
        <th><?php _e('Remove', HKM_LANGUAGE_PACK); ?>Give to each inital downloader with coin</th>
        <td><input disabled name="cost" id="cost" type="number"/><span id="cost_msg"><?php _e('Cannot be bigger than the total amount of the coin.', HKM_LANGUAGE_PACK); ?></span>
        </td>
    </tr>
    <tr>
        <th><?php _e('Registration', HKM_LANGUAGE_PACK); ?></th>
        <td>
            <input type="button" class="button button-primary" disabled id="registerthisapp" value="<?php _e('Add Registration', HKM_LANGUAGE_PACK); ?>">
        </td>
    </tr>
    <tr>
        <th><?php _e('APP KEY', HKM_LANGUAGE_PACK); ?></th>
        <td id="app_id"></td>
    </tr>
    <tr>
        <th><?php _e('APP Secret', HKM_LANGUAGE_PACK); ?></th>
        <td id="secret"></td>
    </tr>
</table>