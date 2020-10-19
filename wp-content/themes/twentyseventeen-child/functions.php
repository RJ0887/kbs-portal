<?php

$parent_style = 'twentyseventeen-style';

// add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
// function my_theme_enqueue_styles() {
    // wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

// }

function my_theme_enqueue_styles() {

    $parent_style = 'parent-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

/**
 * Register more menu for navigation bar.
 */
function register_my_menus() {
  register_nav_menus(
    array(
      'homepg-menu-admin' => __( 'Homepage Menu admin' ),
      'homepg-menu-puser' => __( 'Homepage Menu puser' ),
      'homepg-menu-all' => __( 'Homepage Menu all' ),
      'manage-menu-admin' => __( 'Manage Menu admin' ),
      'manage-menu-puser' => __( 'Manage Menu puser' ),
      'manage-menu-gen' => __( 'Manage Menu gen' ),
      'learn-menu' => __( 'Learn Menu' )
    )
  );
}
add_action( 'init', 'register_my_menus' );
?>