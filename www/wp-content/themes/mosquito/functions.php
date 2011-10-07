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
 * Display breadcrumbs
 *
 */
function the_breadcrumb() {
  echo '<ul id="crumbs">';
  if (!is_home()) {
    echo '<li><a href="';
    echo get_option('home');
    echo '">';
    echo 'Home';
    echo "</a></li>";
    if (is_category() || is_single()) {
      echo '<li>';
      the_category(' </li><li> ');
      if (is_single()) {
        echo "</li><li>";
        the_title();
        echo '</li>';
      }
    }
    elseif (is_page()) {
      echo '<li>';
      echo the_title();
      echo '</li>';
    }
  }
  elseif (is_tag()) {
    single_tag_title();
  }
  elseif (is_day()) {
    echo"<li>Archive for ";
    the_time('F jS, Y');
    echo'</li>';
  }
  elseif (is_month()) {
    echo"<li>Archive for ";
    the_time('F, Y');
    echo'</li>';
  }
  elseif (is_year()) {
    echo"<li>Archive for ";
    the_time('Y');
    echo'</li>';
  }
  elseif (is_author()) {
    echo"<li>Author Archive";
    echo'</li>';
  }
  elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
    echo "<li>Blog Archives";
    echo'</li>';
  }
  elseif (is_search()) {
    echo"<li>Search Results";
    echo'</li>';
  }
  echo '</ul>';
}

/**
 * Use a cache jQuery script snippet to improve load performance
 *
 * @since HTMLgraphic
 */
function load_jquery_script_cdn() {
  wp_deregister_script('jquery');
  echo "<script src='//ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js'></script>";
  echo "<script>!window.jQuery && document.write(unescape('%3Cscript src=\"/wp-includes/js/jquery/jquery.js\"%3E%3C/script%3E'))</script>";
}

/**
 * Cache the BBQ script (Back Button & Query Library) for dealing with hashes in the URL
 *
 * @since HTMLgraphic
 */
function load_bbq_jquery_script() {
  wp_register_script('jquery-ba-bbq', get_stylesheet_directory_uri() . '/js/jquery.ba-bbq.min.js');
  wp_enqueue_script('jquery-ba-bbq');
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
add_action('wp_enqueue_scripts', 'load_jquery_script_cdn', 1);
add_action('wp_enqueue_scripts', 'load_bbq_jquery_script');
add_action('wp_enqueue_scripts', 'load_scrollTo_jquery_script');
add_action('wp_enqueue_scripts', 'load_localscroll_jquery_script');
add_action('wp_enqueue_scripts', 'load_serialScroll_jquery_script');
?>
