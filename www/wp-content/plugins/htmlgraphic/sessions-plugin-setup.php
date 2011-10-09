<?php
/* HTMLgraphic - Sessions Database Creation Code */
function install_HG_sessions()
	{
		//NB Always set wpdb globally!
		global $wpdb, $table_prefix;
		
		//Set Table Name		
		$session_table = $table_prefix . "hg_sessions";
		
		//Check whether or not the table already exists
		if(!check_session_table_existance($session_table)) :
		
			// Create the main Table, don't forget the ( ` ) - MySQL Reference @ http://www.w3schools.com/Sql/sql_create_table.asp
			$session_table = "CREATE TABLE `".$session_table."` (
				`id` varchar(32) NOT NULL default '',
				`data` text,
				`last_accessed` timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
			
			//Run the and validate that it was successful
			if(mysql_query($session_table) === true) :
				$table_created = 1;
			endif;
			
			// Messages reporting status after the plugin has been enabled.
			if(!$table_created) :
				//echo "<p class=\"error\">The database table was not created, please make sure your database allows table creation.</p>";
			else :
				//echo "<p class=\"success\">The database table was successfully created.</p>";
			endif;	
		else:
			//echo "<p class=\"success\">The database table already exists.</p>";
		endif;
	}

function check_session_table_existance($new_table) {
	//NB Always set wpdb globally!
	global $wpdb;
	$table_name = $new_table;
	if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {
			return false;
	}
	return true;
}

?>