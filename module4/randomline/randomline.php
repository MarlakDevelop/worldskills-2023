<?php
/*
Plugin Name: RandomLine
*/

add_action('admin_menu', 'add_admin_menu');
function add_admin_menu() {
    add_options_page(
        'Настройки Random Line',
        'Random Line',
        'manage_options',
        'random_line_slug',
        'random_line_options_page_output'
    );
}

function random_line_options_page_output(){
    ?>
    <div class="wrap">
        <h2><?php echo get_admin_page_title() ?></h2>

        <form action="options.php" method="POST">
            <?php
            settings_fields('option_group');     // скрытые защитные поля
            do_settings_sections('random_line_slug'); // секции с настройками (опциями). У нас она всего одна 'section_id'
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Регистрируем настройки.
 * Настройки будут храниться в массиве, а не одна настройка = одна опция.
 */
add_action('admin_init', 'plugin_settings');
function plugin_settings(){
    register_setting( 'option_group', 'random_line_selected_post', 'sanitize_callback' );
    add_settings_section( 'general', 'Основные настройки Random Line', '', 'random_line_slug' );
    add_settings_field('random_line_fields', 'Пост', 'fill_fields', 'random_line_slug', 'general' );
}

## Заполняем опцию 1
function fill_fields(){
    $val = get_option('random_line_selected_post');
    print_r($val);
    $posts = get_posts();

    ?>
    <select name="random_line_selected_post">
        <?php foreach($posts as $post):?>
            <option <?php if($post->ID == $val):?>selected<?php endif;?> value="<?=$post->ID?>"><?=$post->post_title?></option>
        <?php endforeach;?>
    </select>
    <?php

}

## Очистка данных
function sanitize_callback( $options ){
    return $options;
}

add_action('wp_enqueue_scripts', 'random_line');
function random_line() {
    $post_id = get_option('random_line_selected_post');
    $post = get_post($post_id);
    $sentences = preg_split('/[.?!]/', trim(wp_strip_all_tags($post->post_content)));
    $sentence = $sentences[rand(0, count($sentences) - 1)];
    $time = strlen($sentence) * 70
    ?>
        <style>
            .random-line {
                z-index: 300;
                position: fixed;
                height: 30px;
                bottom: -30px;
                left: 0;
                right: 0;
                transition: bottom 1s linear;
                background-color: #111111;
            }
            .random-line.active {
                bottom: 0;
            }
            .random-line .random-line-content {
                z-index: 301;
                position: fixed;
                height: 30px;
                bottom: 0;
                left: 100%;
                line-height: 30px;
                color: #FFFFFF;
                transition: left <?= $time ?>ms linear;
                white-space: nowrap;
            }
        </style>
        <div id="randomLine" class="random-line">
            <div class="random-line-content">
                <?= $sentence ?>
            </div>
        </div>
        <script>
            function rlSetup() {
                const options = {
                    root: document.querySelector('#randomLine'),
                    text: document.querySelector('#randomLine .random-line-content')
                }
                const pageHeight = document.body.offsetHeight
                const randomY = Math.floor(Math.random() * (pageHeight - window.innerHeight))
                const scrollCB = () => {
                    if (window.scrollY > randomY) {
                        rlStart(options)
                        window.removeEventListener('scroll', scrollCB)
                    }
                }
                window.addEventListener('scroll', scrollCB)
            }

            function rlStart(options) {
                options.root.classList.add('active')
                setTimeout(() => {
                    options.text.style.left = `-${options.text.scrollWidth}px`
                    setTimeout(() => {
                        options.root.classList.remove('active')
                    }, <?= $time ?>)
                }, 1000)
            }

            document.addEventListener('DOMContentLoaded', rlSetup)
        </script>
    <?php
}