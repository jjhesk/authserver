<?php
$admin_settings = TitanFramework::getInstance('vcoinset');
$usd = $admin_settings->getOption("app_coin_new_cost");
$hkd = $admin_settings->getOption("app_coin_new_cost_hk");
$coin = $admin_settings->getOption("app_coin_new_dev");

?>
<article class="price-plan">
    <header>

        <div class="title">
            <h1>VCoin Distributor Agent Plan</h1>
        </div>

        <div class="pricing">
            <div>
                <span class="currency">US$</span>
                <span class="price"><?php echo $usd; ?></span>
                <span class="interval">/year</span>
                <span style="font-size: 25px;font-weight: bold;">&nbsp;&nbsp;OR&nbsp;&nbsp;</span>
                <span class="currency">HK$</span>
                <span class="price"><?php echo intval($hkd); ?></span>
                <span class="interval">/year</span>
            </div>
        </div>

    </header>

    <ul class="details">
        <li>Distribution of <?php echo $coin; ?> VCoins</li>
        <li>Feature on VCoin App listing</li>
        <li>API provided</li>
        <li>Consultation with technical team</li>
        <li>VCoin Distribution Solutions Provided</li>
    </ul>

    <div class="call-to-action">
        <a href="#" title="Select Plan">Select Plan</a>
    </div>
</article>