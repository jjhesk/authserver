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

<table class="table">
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
        <td>Name</td>
        <td><input type="hidden" id="app_name" name="app_name"/>
        </td>
    </tr>
    <tr>
        <td>Store ID</td>
        <td><input name="store_id" id="store_id" type="text"/></td>
    </tr>
    <tr>
        <td>Icon</td>
        <td><input name="icon_url" id="icon_url" type="text"/></td>
    </tr>
    <tr>
        <td>Description</td>
        <td><textarea id="desc_area" rows="4" cols="50"></textarea></td>
    </tr>
    <tr>
        <td>Assign VCoin</td>
        <td><input name="add_vcoin" id="add_vcoin" type="number"/><span>The available coins are <b id="vcoin_count"></b> <b
                    id="extra_coin_msg"></b></span></td>
    </tr>
    <tr>
        <td>APP ID</td>
        <td id="app_id"></td>
    </tr>
    <tr>
        <td>APP Secret</td>
        <td id="secret"></td>
    </tr>
    <tr>
        <td>Registration</td>
        <td>
            <button id="registerthisapp">Add Registration</button>
        </td>
    </tr>
</table>