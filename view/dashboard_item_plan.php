<script id="account_status_template" type="text/x-handlebars-template">
    <article class="price-plan">
    <header>
        <div class="title">
            <h1>Account Status</h1>

                <span class="description">{{company_name}}</span>

        </div>
    </header>
    <ul class="details">
        <li>You have {{app_pending_count}} apps pending for review.</li>
        <li>You have {{app_listed_count}} apps listed on the vcoin app.</li>
        <li>You have {{developer_coins}} available to assigned.</li>
    </ul>
    <div class="call-to-action">
        <a href="#" title="Select Plan">Add Plan</a>
        <a href="#" title="add coin">Top Up Coins</a>
    </div>
</article>
</script>