<?php
add_action( 'init', 'museum_init' );
function museum_init(){
}

add_action('customize_register', 'museum_admin_theme_customization' );
function museum_admin_theme_customization($customizer){
    $customizer->add_section(
        'museum-data',
        array(
            'title' => 'Данные о музее',
            'priority' => 11,
        )
    );
    $customizer->add_setting(
        'museum-address',
        array('default' => '')
    );
    $customizer->add_control(
        'museum-address',
        array(
            'type' => 'text',
            'label' => 'Адрес',
            'section' => 'museum-data',
        )
    );

    $customizer->add_setting(
        'museum-about',
        array('default' => '')
    );
    $customizer->add_control(
        new WP_Customize_Code_Editor_Control(
            $customizer,
            'museum-about',
            array(
                'label' => 'О музее',
                'section' => 'museum-data',
                'settings' => 'museum-about'
            )
        )
    );
}

add_action('after_theme_setup', 'museum_setup');
function museum_setup() {
    add_theme_support('menus');
    add_theme_support('title-tag');
    register_nav_menu('nav', 'Меню');
}

add_action('wp_enqueue_scripts', 'set_museum_assets');
function set_museum_assets() {
    wp_deregister_script('jquery');

    wp_enqueue_style('base', get_template_directory_uri() . '/css/base.css', [], '1.0');
    wp_enqueue_style('vendor', get_template_directory_uri() . '/css/vendor.css', [], '1.0');
    wp_enqueue_style('main', get_template_directory_uri() . '/css/main.css', [], '1.0');

    wp_register_script('jquery', get_template_directory_uri() . '/js/jquery-3.2.1.min.js', [], '1.0', true);
    wp_enqueue_script('modernizr', get_template_directory_uri() . '/js/modernizr.js', [], '1.0');
    wp_enqueue_script('jquery');
    wp_enqueue_script('plugins', get_template_directory_uri() . '/js/plugins.js', [], '1.0', true);
    wp_enqueue_script('main', get_template_directory_uri() . '/js/main.js', [], '1.0', true);
}