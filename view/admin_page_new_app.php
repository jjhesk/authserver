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
        <td><img src="{{logo}}"/></td>
        <td>
            {{appName}}<br>by {{developer}} <br><span class="small">{{packageID}}</span>
        </td>
    </tr>
</script>

<table class="table" cellspacing="10">
    <tr>
        <td>Platform</td>
        <td><?php
            echo ui_handler::ui_select_creation(array(
                "ios" => "iOS",
                "android" => "Android"
            ), "platform", "select", "platform");
            ?></td>
    </tr>

    <tr>
        <td>Search Enable</td>
        <td>
            <input class="hidden_field_switcher" type="hidden" name="allow_search" value="1">

            <div class="container">
                <label class="switch">
                    <input type="checkbox" class="switch-input" checked="">
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                </label>
            </div>
        </td>
    </tr>

    <tr>
        <td>Search App</td>
        <td><input type="hidden" id="search_app" name="search_app"/></td>
    </tr>
    <tr>
        <td>Application Name</td>
        <td><input type="text" id="app_name" name="app_name" required="true"/></td>
    </tr>
    <tr>
        <td>Store ID</td>
        <td><input readonly name="store_id" id="store_id" type="text" required="true"/></td>
    </tr>
    <tr>
        <td>Icon</td>
        <td><input readonly name="icon_url" id="icon_url" type="text" required="true"/></td>
    </tr>
    <tr>
        <td>Description</td>
        <td><textarea disabled id="desc_area" rows="4" cols="50" required="true"></textarea></td>
    </tr>
    <tr>
        <td>Assign the total amount for this application with coin</td>
        <td><input disabled name="add_vcoin" id="add_vcoin" type="number"/><span>The available coins are <b
                    id="vcoin_count"></b> <b
                    id="extra_coin_msg"></b></span></td>
    </tr>
    <tr>
        <td>Give to each inital downloader with coin</td>
        <td><input disabled name="cost" id="cost" type="number"/><span>Cannot be bigger than the total amount of the coin.</span>
        </td>
    </tr>
    <tr>
        <td>Registration</td>
        <td>
            <input type="button" class="button" disabled id="registerthisapp" value="Add Registration">
        </td>
    </tr>
    <tr>
        <td>APP ID</td>
        <td id="app_id"></td>
    </tr>
    <tr>
        <td>APP Secret</td>
        <td id="secret"></td>
    </tr>
</table>