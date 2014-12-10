<script id="score_board" type="text/x-handlebars-template">
    <div class="article">
        <div class="item row">
            <div class="col-xs-3">
                <p class="source"><?php _e('AW Commercial Aviation', HKM_LANGUAGE_PACK); ?></p>
            </div>
            <div class="col-xs-6">
                <p class="title"><?php _e('CSeries Supplier Scramble', HKM_LANGUAGE_PACK); ?></p>
            </div>
            <div class="col-xs-3">
                <p class="pubdate"><?php _e('Mar 22', HKM_LANGUAGE_PACK); ?></p>
            </div>
        </div>
        <div class="description row">
            <div class="col-xs-3">&nbsp;</div>
            <div class="col-xs-6">
                <h1><?php _e('CSeries Supplier Scramble', HKM_LANGUAGE_PACK); ?></h1>

                <p><?php _e('Three months before the planned first flight of its CSeries, Bombardier is grappling with supplier
                    issues crucial to meeting its production cost...', HKM_LANGUAGE_PACK); ?></p>
            </div>
            <div class="col-xs-3">&nbsp;</div>
        </div>
    </div>
</script>
<div class="articles container">

</div>
<style>
    .articles.container p {
        margin: 0;
    }

    .articles.container .row {
        margin: 0;
    }

    .articles.container .articles {
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .articles.container .article {
        color: #222;
        background: rgba(255, 255, 255, .9);
        border-spacing: 2px;
        border-color: gray;
        font-family: arial, sans-serif;
        border-bottom: 1px #e5e5e5 solid;
    }

    .articles.container .current .item {
        background: rgba(206, 220, 206, .9);
    }

    .articles.container .item {
        cursor: pointer;
        padding-top: 7px;
        padding-bottom: 7px;

    }

    .articles.container .item .source {
        margin-left: 20px;
    }

    .articles.container .item .title {
        font-weight: bold;
    }

    .articles.container .item .pubdate {
        margin-right: 20px;
    }

    .articles.container .item .pubdate {
        text-align: right;
    }

    .articles.container .description {
        display: none;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .articles.container .description h1 {
        margin-top: 0px;
        font-size: 23px;
    }
</style>