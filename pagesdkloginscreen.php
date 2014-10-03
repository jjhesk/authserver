<?php
/*
 Template Name: SDK Login Screen
*/
get_header("email_response_name"); ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <?php while (have_posts()) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; // end of the loop. ?>
        </main>
        <!-- #main -->
    </div><!-- #primary -->
<?php get_footer(); ?>