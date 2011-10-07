<?php


/**
* Navigation menus
*
* @since HTMLgraphic
*/
function register_my_menus() {
	register_nav_menus(
		array(
				'primary-menu' => __( 'Primary Menu' ),
				'home-menu' => __( 'Home Menu' )
		)
	);
}
add_action( 'init', 'register_my_menus' ); 



/**
* Bread crumbs
*
* @since HTMLgraphic
*/
function hg_bread_crumbs() {  
	do_action('hg_bread_crumbs');  
}  


function load_yoast_breadcrumb() {
  if ( function_exists('yoast_breadcrumb') ) {
	  echo yoast_breadcrumb("","",false);
  }
}
add_action( 'hg_bread_crumbs', 'load_yoast_breadcrumb' ); 


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
add_action('wp_head', 'load_better_jquery_script', 1);



?>