<?php
/*
Template Name: Страница блога
*/

get_header();
?>
<!-- s-content
    ================================================== -->
<section class="s-content s-content--top-padding">
    <div class="row entries-wrap add-top-padding wide">
        <div class="entries">
            <?php
            global $wp_query;
            $query_vars = $wp_query->query_vars;
            $args = [
                'posts_per_page' => $query_vars['posts_per_page'],
                'post_type' => 'post',
                'paged' => $query_vars['paged'],
                'ignore_sticky_posts' => true,
            ];
            query_posts($args);
            while (have_posts()) {
                the_post();
                get_template_part('template_parts/post_card', get_post_format());
            }
            ?>
        </div> <!-- end entries -->
    </div> <!-- end entries-wrap -->

    <div class="row pagination-wrap">
        <div class="col-full" data-aos="fade-up">
                <?php the_posts_pagination([
                    'show_all'     => false, // показаны все страницы участвующие в пагинации
                    'end_size'     => 1,     // количество страниц на концах
                    'mid_size'     => 1,     // количество страниц вокруг текущей
                    'prev_next'    => true,  // выводить ли боковые ссылки "предыдущая/следующая страница".
                    'prev_text'    => __('« Previous'),
                    'next_text'    => __('Next »'),
                    'add_args'     => false, // Массив аргументов (переменных запроса), которые нужно добавить к ссылкам.
                    'add_fragment' => '',     // Текст который добавиться ко всем ссылкам.
                    'screen_reader_text' => __( 'Posts navigation' ),
                    'class'        => 'pagination', // CSS класс, добавлено в 5.5.0.
                ]); ?>
        </div>
    </div>

</section> <!-- end s-content -->
<?php
get_footer();
?>
