<?php
$admin_settings = TitanFramework::getInstance('vcoinset');
$usd = $admin_settings->getOption("app_coin_new_cost");
$hkd = $admin_settings->getOption("app_coin_new_cost_hk");
$coin = $admin_settings->getOption("app_coin_new_dev");

?>
<article class="price-plan">
    <header>

        <div class="title">
            <h1><?php _e('VCoin Distributor Agent Plan', HKM_LANGUAGE_PACK); ?></h1>
        </div>

        <div class="pricing">
            <div>
                <span class="currency"><?php _e('US$', HKM_LANGUAGE_PACK); ?></span>
                <span class="price"><?php echo $usd; ?></span>
                <span class="interval"><?php _e('/year', HKM_LANGUAGE_PACK); ?></span>
                <span style="font-size: 25px;font-weight: bold;"><?php _e('&nbsp;&nbsp;OR&nbsp;&nbsp;', HKM_LANGUAGE_PACK); ?></span>
                <span class="currency"><?php _e('HK$', HKM_LANGUAGE_PACK); ?></span>
                <span class="price"><?php echo intval($hkd); ?></span>
                <span class="interval"><?php _e('/year', HKM_LANGUAGE_PACK); ?></span>
            </div>
        </div>

    </header>

    <ul class="details">
        <li><?php _e('Distribution of', HKM_LANGUAGE_PACK); ?> <?php echo $coin; ?> <?php _e('VCoins', HKM_LANGUAGE_PACK); ?></li>
        <li><?php _e('Feature on VCoin App listing', HKM_LANGUAGE_PACK); ?></li>
        <li><?php _e('API provided', HKM_LANGUAGE_PACK); ?></li>
        <li><?php _e('Consultation with technical team', HKM_LANGUAGE_PACK); ?></li>
        <li><?php _e('VCoin Distribution Solutions Provided', HKM_LANGUAGE_PACK); ?></li>
    </ul>

    <div class="call-to-action">
        <a href="#" title="Select Plan"><?php _e('Select Plan', HKM_LANGUAGE_PACK); ?></a>
    </div>
</article>