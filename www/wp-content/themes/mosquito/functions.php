<?php

/**
 * Navigation menus
 *
 * @since HTMLgraphic
 */
function register_my_menus() {
  register_nav_menus(
          array(
              'primary-menu' => __('Primary Menu'),
              'home-menu' => __('Home Menu')
          )
  );
}

/**
 * Bread crumbs
 *
 * @since HTMLgraphic
 */
function hg_bread_crumbs() {
  do_action('hg_bread_crumbs');
}

function load_yoast_breadcrumb() {
  if (function_exists('yoast_breadcrumb')) {
    echo yoast_breadcrumb("", "", false);
  }
}

add_action('hg_bread_crumbs', 'load_yoast_breadcrumb');

/**
 * Use a cache jQuery script snippet to improve load performance
 *
 * @since HTMLgraphic
 */
function load_better_jquery_script() {
  wp_deregister_script('jquery');
  echo "<script src='//ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js'></script>";
  echo "<script>!window.jQuery && document.write(unescape('%3Cscript src=\"/wp-includes/js/jquery/jquery.js\"%3E%3C/script%3E'))</script>";
}

function load_bbq_jquery_script() {
  wp_register_script('jquery-bbq', get_stylesheet_directory_uri() . '/js/jquery.ba-bbq.min.js');
  wp_enqueue_script('jquery-bbq');
}

function load_scrollTo_jquery_script() {
  wp_register_script('jquery-scrollTo', get_stylesheet_directory_uri() . '/js/jquery.scrollTo-1.3.3.min.js');
  wp_enqueue_script('jquery-scrollTo');
}

function load_localscroll_jquery_script() {
  wp_register_script('jquery-localscroll', get_stylesheet_directory_uri() . '/js/jquery.localscroll-1.2.6-min.js');
  wp_enqueue_script('jquery-localscroll');
}

function load_serialScroll_jquery_script() {
  wp_register_script('jquery-serialScroll', get_stylesheet_directory_uri() . '/js/jquery.serialScroll-1.2.1.min.js');
  wp_enqueue_script('jquery-serialScroll');
}

//
// action bindings
//
add_action('init', 'register_my_menus');
add_action('wp_head', 'load_better_jquery_script', 1);
add_action('wp_enqueue_scripts', 'load_bbq_jquery_script');
add_action('wp_enqueue_scripts', 'load_scrollTo_jquery_script');
add_action('wp_enqueue_scripts', 'load_localscroll_jquery_script');
add_action('wp_enqueue_scripts', 'load_serialScroll_jquery_script');
?>
