<article class="col-block">
    <div class="item-entry" data-aos="zoom-in">
        <div class="item-entry__thumb">
            <a href="<?php the_permalink(); ?>" class="item-entry__thumb-link">
                <img src="<?php the_field('image'); ?>" style="width: 100%">
            </a>
        </div>
        <div class="item-entry__text">
            <div class="item-entry__cat">
                <?php the_category(' ', 'multiple'); ?>
            </div>
            <h1 class="item-entry__title"><a href="<?php the_permalink(); ?>"><?php the_field('short_title') ?></a></h1>
            <div class="item-entry__date">
                <a href="<?php the_permalink(); ?>"><?php the_field('year'); ?></a>
            </div>
        </div>
    </div> <!-- item-entry -->
</article> <!-- end article -->