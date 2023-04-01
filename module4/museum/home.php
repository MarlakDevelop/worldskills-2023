<?php

get_header();
?>
    <!-- featured
    ================================================== -->
    <section class="s-featured">
        <?php
        echo do_shortcode('[smartslider3 slider="1"]');
        ?>
    </section> <!-- end s-featured -->

    <!-- s-content
    ================================================== -->
    <section class="s-content">
        <?= get_theme_mod('museum-about', ''); ?>
        <div class="row entries-wrap wide">
            <div class="entries">
                <?php
                $sticky = get_option('sticky_posts');
                // check if there are any
                if (!empty($sticky)) {
                    // optional: sort the newest IDs first
                    rsort($sticky);
                    // override the query
                    $args = [
                        'post__in' => $sticky,
                        'posts_per_page' => -1
                    ];
                    query_posts($args);
                    // the loop
                    while (have_posts()) {
                        the_post();
                        get_template_part('template_parts/post_card', get_post_format());
                    }
                }
                ?>
            </div> <!-- end entries -->
        </div> <!-- end entries-wrap -->
    </section> <!-- end s-content -->

    <!-- s-content
        ================================================== -->
    <section class="s-content s-content--top-padding s-content--narrow">
        <div class="row">
            <div class="col-full s-content__main">
                <h4>Записаться</h4>
                <?php echo do_shortcode('[contact-form-7 id="30" title="Обратная связь"]') ?>
            </div> <!-- s-content__main -->
        </div> <!-- end row -->
    </section> <!-- end s-content -->

<?php get_footer(); ?>