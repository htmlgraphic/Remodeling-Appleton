<?php
/* 
Plugin Name: HTMLgraphic Components
Plugin URI: http://www.htmlgraphic.com
Version: 0.3.3
Date: 2010-11-06
Author: HTMLgraphic
Author URI: http://www.htmlgraphic.com
Author Email: jason@hgmail.com
Description: This plugin removes many of the bashboard widgets that are not needed on the admin side. A HTMLgraphic News feed has been added to answer many common WordPress questions. Click Options to edit plugin settings. Google's Asynchronous Tracking Code and issue a notice if a new version is available.
*/

/*
Copyright (C) 2010 HTMLgraphic Designs, LLC - (designs@htmlgraphic.com)

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


// Hide the version information of WordPress 
remove_action('wp_head','wp_generator');

// More information: http://www.htmlgraphic.com/wordpress/wlwmanfest-disable/
remove_action('wp_head','wlwmanifest_link');

// Remove the default scripts from the WordPress Blog
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

//remove_action('wp_head','rel_canonical');
remove_action('wp_head','adjacent_posts_rel_link_wp_head');



/**
 * Display the XHTML author that is generated on the wp_head hook.
 *
 */
function hg_meta_update() {
	echo '<meta name="author" content="Created by HTMLgraphic Designs" />'."\n";
}
	add_action('wp_head','hg_meta_update', 0);





/**
 *
 * Customize the admin side to remove widgets that are not needed.
 *
 */
function my_custom_dashboard_widgets() {
    // Globalize the metaboxes array, this holds all the widgets for wp-admin
     global $wp_meta_boxes;

    // Remove the incomming links widget
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);    

    // Remove right now
    //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    //unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    
    wp_add_dashboard_widget('custom_help_widget', 'Help and Support', 'custom_dashboard_help');
   
}
// Hook into the 'wp_dashboard_setup' action to register our function
add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets' );



function custom_dashboard_help() {
    echo '<p>Welcome to your custom theme! Need help? Contact the developer <a href="http://www.htmlgraphic.com/contact/">here</a>.<a href="%E2%80%9Dhttp://www.htmlgraphic.com/contact/%E2%80%9D"></a></p>';
}


/**
 *
 * Custom Feed (HTMLgraphic Client News) Widget
 *
 */
function my_add_dashboard_widget() {
 wp_add_dashboard_widget( 'dashboard_custom_feed', 'HTMLgraphic - WordPress Tips', 'my_dashboard_custom_feed_output' );
}
  
function my_dashboard_custom_feed_output() {
 echo '<div class="rss-widget">';
 wp_widget_rss_output( array(
    'url' => 'http://www.htmlgraphic.com/tag/client-news/feed/',
    'title' => 'HTMLgraphic - WordPress Tips',
	'link' => 'http://www.htmlgraphic.com/tag/client-news/',
    'items' => 5,
    'show_summary' => 1,
    'show_author' => 0,
    'show_date' => 1 
   ));
 echo "</div>"; 
}

$devOptions = get_option('HTMLgraphicPluginAdminOptions');
// Check for database value to hide or show HTMLgraphic News
if ($devOptions['HG_News'] == "true") {
	add_action( 'wp_dashboard_setup', 'my_add_dashboard_widget' );
}




/**
 *
 * Remove the HTML attributes below the comments form
 *
 */
function comment_form_note_disable() {
	add_filter('comment_form_defaults','custom_comment_form_defaults');
}
add_action('after_setup_theme','comment_form_note_disable');

function custom_comment_form_defaults($default) {
	unset($default['comment_notes_after']);
	return $default;
}














/**
 *
 * WP Admin side plugin options specific to the needs of HTMLgraphic Designs
 *
 */
function HG_init() {
	//add_action('admin_menu', 'HG_config_page');
	add_filter('plugin_action_links', 'HG_actions', 10, 2 );
	//add_action('wp_footer', 'peb_footer');
}
add_action('init', 'HG_init');

function HG_actions($links, $file){
	$this_plugin = plugin_basename(__FILE__);
	
	if ( $file == $this_plugin ){
		$settings_link = '<a href="options-general.php?page=HG">' . __('Options') . '</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}










	




if (!class_exists("HTMLgraphicPlugin")) {
	class HTMLgraphicPlugin {
		var $adminOptionsName = "HTMLgraphicPluginAdminOptions";
		function HTMLgraphicPlugin() { //constructor
		
		}
	  
		function init() {
			$this->getAdminOptions();
		}	
	  
		function getAdminOptions() {
			// Default values
			$HG_AdminOptions = array('GA_Analytics' => 'false',
				'GA_AnalyticsKey' => '',
				'Woopra_Analytics' => 'false',
				'HG_News' => 'true');			  
			$devOptions = get_option($this->adminOptionsName);
			if (!empty($devOptions)) {
				foreach ($devOptions as $key => $option)
					$HG_AdminOptions[$key] = $option;
			}

			update_option($this->adminOptionsName, $HG_AdminOptions);
			return $HG_AdminOptions;
		}

		  //Prints out the admin page
		  function printAdminPage() {

					  $devOptions = $this->getAdminOptions();
	
					  if (isset($_POST['update_HTMLgraphic'])) {
						  if (isset($_POST['GA_Analytics'])) {
							  $devOptions['GA_Analytics'] = $_POST['GA_Analytics'];
						  }
						  if (isset($_POST['GA_AnalyticsKey'])) {
							  $devOptions['GA_AnalyticsKey'] = $_POST['GA_AnalyticsKey'];
						  }
						  if (isset($_POST['Woopra_Analytics'])) {
							  $devOptions['Woopra_Analytics'] = $_POST['Woopra_Analytics'];
						  }
						  if (isset($_POST['HG_News'])) {
							  $devOptions['HG_News'] = $_POST['HG_News'];
						  }
					  	  update_option($this->adminOptionsName, $devOptions);
					  
					  ?>
						<div class="updated"><p><strong><?php _e("Settings Updated.", "update_HTMLgraphic");?></strong></p></div>
				      <?php
					  } 
					  
					  ?>
<div class="wrap">
<h2>HTMLgraphic Options</h2>
<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<?php wp_nonce_field('update-options'); ?>
<?php //settings_fields( 'HG_settings-group' ); ?>

<table class="form-table">
<tr valign="top">
<th scope="row">Google Analytics</th>
<td><label for="GA_Analytics_yes"><input type="radio" id="GA_Analytics_yes" name="GA_Analytics" value="true" <?php if ($devOptions['GA_Analytics'] == "true") { _e('checked="checked"', "DevloungePluginSeries"); }?> /> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="GA_Analytics_no"><input type="radio" id="GA_Analytics_no" name="GA_Analytics" value="false" <?php if ($devOptions['GA_Analytics'] == "false") { _e('checked="checked"', "HTMLgraphicPlugin"); }?>/> No</label></td>
</tr>
<?php if ($devOptions['GA_Analytics'] == "true") { ?>
<tr>
<td>Latest (asynchronous) version. </td>
<td>Google Analytics Key: <input type="text" name="GA_AnalyticsKey" value="<?=$devOptions['GA_AnalyticsKey']; ?>" /></td>
</tr>
<? } ?>
<tr valign="top">
<th scope="row">Woopra</th>
<td><label for="Woopra_Analytics_yes"><input type="radio" id="Woopra_Analytics_yes" name="Woopra_Analytics" value="true" <?php if ($devOptions['Woopra_Analytics'] == "true") { _e('checked="checked"', "DevloungePluginSeries"); }?> /> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="Woopra_Analytics_no"><input type="radio" id="Woopra_Analytics_no" name="Woopra_Analytics" value="false" <?php if ($devOptions['Woopra_Analytics'] == "false") { _e('checked="checked"', "HTMLgraphicPlugin"); }?>/> No</label></td>
</tr>
<?php if ($devOptions['Woopra_Analytics'] == "true") { ?>
<tr>
<th>&nbsp;</th>
<td>No key is needed but you will have to setup the domain at <a href="http://www.woopra.com">woopra.com</a></td>
</tr>
<? } ?>
<tr valign="top">
<th scope="row">Display Dashboard News</th>
<td><label for="HG_News_yes"><input type="radio" id="HG_News_yes" name="HG_News" value="true" <?php if ($devOptions['HG_News'] == "true") { _e('checked="checked"', "DevloungePluginSeries"); }?> /> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="HG_News_no"><input type="radio" id="HG_News_no" name="HG_News" value="false" <?php if ($devOptions['HG_News'] == "false") { _e('checked="checked"', "HTMLgraphicPlugin"); }?>/> No</label></td>
</tr>
</table>

<div class="submit">
	<input type="submit" name="update_HTMLgraphic" value="<?php _e('Update Settings', 'HTMLgraphicPlugin') ?>" />
</div>
</form>
</div>
		<?php
	}//End function printAdminPage()


		  /**
		   *
		   * Google Analyics and Woopra user tracking scripts
		   *
		   */
		  function HG_Analytics() {
		  
		  $devOptions = $this->getAdminOptions();

		// Determine whether we're working on a local server
		// or on the real server:
		
/*
		// IS_LIVE should be set in the PHP.ini file
		if (get_cfg_var('IS_LIVE') == 1) {
		  define('IS_LIVE', true);
		} else {
		  define('IS_LIVE', false);
		}
*/

		// Determine location of files and the URL of the site:
		// Allow for development on different servers.
  
		if (!IS_LIVE) {
			if (($devOptions['Woopra_Analytics'] == "true") || ($devOptions['GA_Analytics'] == "true")) {
				echo "<!--// Analytics tracking code would be here but your on the INTERNAL network so... NO TRACKING -->";
			}
		} else { 



if ($devOptions['Woopra_Analytics'] == "true") { ?>
<script type="text/javascript">
var woo_settings = {idle_timeout:'300000', domain:'passinggreen.com'};
<?php if ($User->username) { ?>
var woo_visitor={name:'<?=$User->FirstName?> - <?=$User->ID?>', email:'<?=$User->username?>'};
<?php } ?>
var woo_actions=[{'type':'pageview','url':window.location.pathname+window.location.search,'title':document.title}];
(function(){
var wsc = document.createElement('script');
wsc.src = document.location.protocol+'//static.woopra.com/js/woopra.js';
wsc.type = 'text/javascript';
wsc.async = true;
var ssc = document.getElementsByTagName('script')[0];
ssc.parentNode.insertBefore(wsc, ssc);
})();
</script>
<?php } // END Woopra?>
        
<?php if ($devOptions['GA_Analytics'] == "true") { ?>
<script type="text/javascript">
// HTMLgraphic Plugin - Google Asynchronous Tracking Code 
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?=$devOptions['GA_AnalyticsKey'];?>']);
  _gaq.push(['_trackPageview']);

  (function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<?php } // END Google Analytics
		}
	} // End function HG_Analytics



	}
	
}	//End Class HTMLgraphicPlugin



if (class_exists("HTMLgraphicPlugin")) {
	$dl_pluginSeries = new HTMLgraphicPlugin();
}



//Initialize the Admin Panel
if (!function_exists("HTMLgraphicPlugin_AP")) {
	
	function HTMLgraphicPlugin_AP() {
	global $dl_pluginSeries;
	if (!isset($dl_pluginSeries)) {
		return;
	}
		if (function_exists('add_options_page')) {
		add_options_page('HTMLgraphic Options', 'HTMLgraphic', 9, 'HG', array(&$dl_pluginSeries, 'printAdminPage'));
		}
	}
	
}







//Actions and Filters	
if (isset($dl_pluginSeries)) {
	//Actions
	add_action('admin_menu', 'HTMLgraphicPlugin_AP');
	add_action('wp_footer', array(&$dl_pluginSeries, 'HG_Analytics'), 1);
}






  /**
   *
   * Override default file permissions for installing plugins
   *
   */
	if(is_admin()) {
		add_filter('filesystem_method', create_function('$a', 'return "direct";' ));
		define( 'FS_CHMOD_DIR', 0751 );
	}
?>