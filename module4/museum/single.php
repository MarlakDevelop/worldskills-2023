<?php
get_header();
?>
<section class="s-content s-content--top-padding s-content--narrow">

    <article class="row entry format-standard">

        <div class="entry__media col-full">
            <div class="entry__post-thumb">
                <img src="<?php the_field('image'); ?>"
                     sizes="(max-width: 2000px) 100vw, 2000px" alt="" style="width: 100%">
            </div>
        </div>

        <div class="entry__header col-full">
            <h1 class="entry__header-title display-1">
                <?php the_title(); ?>
            </h1>
            <ul class="entry__header-meta">
                <li class="date"><?php the_field('year'); ?></li>
                <li class="byline"><?php the_field('short_title'); ?></li>
            </ul>
        </div>

        <div class="col-full">

            <?php the_content(); ?>

            <div class="entry__taxonomies">
                <div class="entry__cat">
                    <h5>Категории: </h5>
                    <span class="entry__tax-list">
                        <?php the_category(', ', 'multiple'); ?>
                    </span>
                </div> <!-- end entry__cat -->

                <div class="entry__tags">
                    <h5>Ссылки: </h5>
                    <span class="entry__tax-list entry__tax-list--pill">
                        <?php
                            $links = preg_split('/\s+/', get_field('links'));
                            foreach ($links as $link) {
                                ?><a href="<?= $link ?>"><?= $link ?></a><?php
                            }
                        ?>
                    </span>
                </div> <!-- end entry__tags -->
            </div> <!-- end s-content__taxonomies -->

            <div class="row">
                <div class="col-1-3">
                    <?= do_shortcode('[qrpage]') ?>
                </div>

                <div class="col-2-3">
                    <h5 class="entry__author-name">
                        <span>Posted by</span>
                        <a href="#0">Jonathan Doe</a>
                    </h5>
                </div>
            </div>
        </div> <!-- s-entry__main -->
    </article> <!-- end entry/article -->
</section> <!-- end s-content -->
<?php
get_footer();
?>
