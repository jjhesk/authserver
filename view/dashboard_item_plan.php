<script id="account_status_template" type="text/x-handlebars-template">
    <article class="price-plan">
        <header>
            <div class="title">
                <h1><?php _e('Account Status', HKM_LANGUAGE_PACK); ?></h1>
                <span class="description">{{company_name}}</span>
            </div>
        </header>
        <ul class="details">
            <li><?php _e('You have', HKM_LANGUAGE_PACK); ?> {{app_pending_count}} <?php _e('apps pending for review.', HKM_LANGUAGE_PACK); ?></li>
            <li><?php _e('You have', HKM_LANGUAGE_PACK); ?> {{app_listed_count}} <?php _e('apps listed on the vcoin app.', HKM_LANGUAGE_PACK); ?></li>
            <li><?php _e('You have', HKM_LANGUAGE_PACK); ?> {{app_coins}} <?php _e('available to assigned.', HKM_LANGUAGE_PACK); ?></li>
        </ul>
        <div class="call-to-action">
            <a href="#" title="Select Plan"><?php _e('Add Plan', HKM_LANGUAGE_PACK); ?></a>
            <a href="#" title="add coin"><?php _e('Top Up Coins', HKM_LANGUAGE_PACK); ?></a>
        </div>
    </article>
</script>