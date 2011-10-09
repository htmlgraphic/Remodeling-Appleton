<?php
/* 
Plugin Name: HTMLgraphic Sessions
Plugin URI: http://www.htmlgraphic.com
Version: 0.2
Author: Jason Gegere
Author URI: http://www.htmlgraphic.com
Author Email: jason@hgmail.com
Description: This plugin sets up database sessions that will be used to track sessions from post data submitted via forms.
*/

/*
Copyright (C) 2010 HTMLgraphic Designs, LLC, htmlgraphic.com (designs@htmlgraphic.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

global $session_table, $table_prefix, $plugin_dir;
$session_table = $table_prefix . 'hg_sessions';

$plugin_dir = plugin_dir_path(__FILE__);

//Include the files including the Install, Update and Delete Functions
require_once($plugin_dir . '/sessions-plugin-setup.php');
require_once($plugin_dir . '/sessions-process.php');
require_once($plugin_dir . '/includes/functions.php');

//When activating the plugin, we must call the install_HG_sessions() function.
register_activation_hook(__FILE__,'install_HG_sessions');



function load_HG_objects() {

	global $comment, $plugin_dir, $User, $order, $comment, $register, $referral, $invite;
	
	// Process Sessions
	process_HG_sessions(); 
	
	// -- Site-Specific Libs --
	//require_once(ABSPATH . '/lib/security.class.php');


	// -- Site-Specific Classes --
	//require_once($plugin_dir . '/classes/products.class.php');
	
	// Intialize object for the user and sign in process
	require_once($plugin_dir . '/classes/euser.class.php');
	if (@!is_object(unserialize($_SESSION["eUser"]))) $User = new eUser();
	else $User = unserialize($_SESSION["eUser"]); 

	// Verify order object status
	require_once($plugin_dir . '/classes/order.class.php');
	if (@!is_object(unserialize($_SESSION["Order"]))) $order = new Order();
	else $order = unserialize($_SESSION["Order"]);

	// Verify shopping cart object status
	//require_once($plugin_dir . '/classes/shoppingcart.class.php');
	//if (@!is_object(unserialize($_SESSION["cart"]))) $cart = new ShoppingCart();
	//else $cart = unserialize($_SESSION["cart"]);

	// Intialize object for the contact process
	require_once($plugin_dir . '/classes/comments.class.php');
	if (@!is_object(unserialize($_SESSION["comment"]))) $comment = new Comments();
	else $comment = unserialize($_SESSION["comment"]);

	// Intialize object for user registration process
	require_once($plugin_dir . '/classes/register.class.php');
	if (@!is_object(unserialize($_SESSION["Register"]))) $register = new Register();
	else $register = unserialize($_SESSION["Register"]);

	// Intialize object for the referral process
	require_once($plugin_dir . '/classes/referral.class.php');
	if (@!is_object(unserialize($_SESSION["Referral"]))) $referral = new Referral();
	else $referral = unserialize($_SESSION["Referral"]);
	
	// Intialize object for the note process
	require_once($plugin_dir . '/classes/note.class.php');
	if (@!is_object(unserialize($_SESSION["Note"]))) $note = new Note();
	else $note = unserialize($_SESSION["Note"]);
	
	require_once($plugin_dir . '/classes/invite.class.php');
	if (@!is_object(unserialize($_SESSION["Invite"]))) $invite = new Invite();
	else $invite = unserialize($_SESSION["Invite"]);

}
add_action('wp_head', 'load_HG_objects');




function write_HG_sessions() {
	// Close the DB data storage session 
	session_write_close();
}
add_action('wp_footer', 'write_HG_sessions');




//Add the Menu Item, which will load the HG_init() function
function HG_session_init() {
	add_action('admin_menu', 'HG_session_config_page');
	add_filter('plugin_action_links', 'HG_session_actions', 10, 2 );
	//add_action('wp_footer', 'peb_footer');
}
add_action('init', 'HG_session_init');


function HG_session_config_page() {
	if ( function_exists('add_options_page') )
		add_options_page('HG Options', 'Manage Sessions', 8, 'manage_sessions', 'HG_session_conf');
}

function HG_session_actions($links, $file){
	$this_plugin = plugin_basename(__FILE__);
	
	if ( $file == $this_plugin ){
		$settings_link = '<a href="options-general.php?page=manage_sessions">' . __('Settings') . '</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}


function HG_session_conf() {
?>
<div class="wrap">
<h2>HTMLgraphic - Manage Sessions</h2>
<form method="post" action="options.php">
<p>Additional features coming soon.</p>
<?php wp_nonce_field('update-options'); ?>

</form>
</div>
<?php } ?>