<?php
  // Session management logged to database v1.1 - HTMLgraphic Designs


  // Define the open_session() function:
  // This function takes no arguments.
  // This function should open the database connection.
  function open_session() {
  
	  global $dbconn;
	  
	  // Connect to the database.
	  $dbconn = mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die ('Cannot connect to the database.');
	  
	  return true;
  
  } // End of open_session() function.
   
  // Define the close_session() function:
  // This function takes no arguments.
  // This function closes the database connection.
  function close_session() {
  
	  global $dbconn;
	  
	  return mysqli_close($dbconn);
	  
  } // End of close_session() function.
  
  // Define the read_session() function:
  // This function takes one argument: the session ID.
  // This function retrieves the session data.
  function read_session($sid) {
  
	  global $dbconn, $session_table;
  
	  // Query the database:
	  $q = sprintf('SELECT `data` FROM '.$session_table.' WHERE id="%s"', mysqli_real_escape_string($dbconn, $sid)); 
	  $r = mysqli_query($dbconn, $q);
	  
	  // Retrieve the results:
	  if (mysqli_num_rows($r) == 1) {
	  
		  list($data) = mysqli_fetch_array($r, MYSQLI_NUM);
		  
		  // Return the data:
		  return $data;
  
	  } else { // Return an empty string.
		  return '';
	  }
	  
  } // End of read_session() function.
  
  // Define the write_session() function:
  // This function takes two arguments: 
  // the session ID and the session data.
  function write_session($sid, $data) {
  
	  global $dbconn, $session_table;
   
	  // Store in the database:
	  $q = sprintf('REPLACE INTO '.$session_table.' (id, data) VALUES ("%s", "%s")', mysqli_real_escape_string($dbconn, $sid), mysqli_real_escape_string($dbconn, $data));
	  $r = mysqli_query($dbconn, $q);

	  return mysqli_affected_rows($dbconn);
  		
  } // End of write_session() function.
  
  // Define the destroy_session() function:
  // This function takes one argument: the session ID.
  function destroy_session($sid) {
  
	  global $dbconn, $session_table;
  
	  // Delete from the database:
	  $q = sprintf('DELETE FROM '.$session_table.' WHERE id="%s"', mysqli_real_escape_string($dbconn, $sid)); 
	  $r = mysqli_query($dbconn, $q);
	  
	  // Clear the $_SESSION array:
	  $_SESSION = array();
  
	  return mysqli_affected_rows($dbconn);
  
  } // End of destroy_session() function.
  
  // Define the clean_session() function:
  // This function takes one argument: a value in seconds.
  function clean_session($expire) {
  
	  global $dbconn, $session_table;
  
	  // Delete old '.$session_table.':
	  $q = sprintf('DELETE FROM '.$session_table.' WHERE DATE_ADD(last_accessed, INTERVAL %d SECOND) < NOW()', (int) $expire); 
	  $r = mysqli_query($dbconn, $q);
  
	  return mysqli_affected_rows($dbconn);
  
  } // End of clean_session() function.
  
  
  function process_HG_sessions() {

	// Declare the functions to use:
	session_set_save_handler('open_session', 'close_session', 'read_session', 'write_session', 'destroy_session', 'clean_session');
	
	// Make whatever other changes to the session settings.

	/** Begin session */
	session_start(); 

  }

  function save_HG_sessions() {
	  global $order, $cart, $Security, $User, $referral, $register, $invite;
		// Save sessions that might be needed to process and session form field data. Other sessions 
		// and classes are saved in the processing files. Processing files are typically what forms
		// post to.
		
		// You will need to add an object to this file if a process script needs to save a session.
		// If a object is not listed below the posted data will not be saved in the PHP session database.
		  
	  /** Call save functions */
	  if (is_object($cart)) $cart->save_cart();
		  
	  /** Serialize and save the order information */
	  if (is_object($order)) {$_SESSION["Order"] = serialize($order);}
	  
	  /** Serialize and save the shopping cart */
	  if (is_object($cart)) {$_SESSION["cart"] = serialize($cart);}
	  
	  /** Serialize Security Object */
	  if (is_object($Security)) {$_SESSION["Security"] = serialize($Security);}
	  
	  /** Serialize User Object */
	  if (is_object($User)) {$_SESSION["eUser"] = serialize($User);}
  
	  /** Serialize Referral Object */
  	  if (is_object($referral)) {$_SESSION["Referral"] = serialize($referral);}

	  /** Serialize Invite Object */
  	  if (is_object($invite)) {$_SESSION["Invite"] = serialize($invite);}

	  /** Serialize Register Object */
  	  if (is_object($register)) {$_SESSION["Register"] = serialize($register);}
       
       if (is_object($_SESSION['twitterUser'])) {
         $_SESSION['twitterUser'] = serialize($_SESSION['twitterUser']);
       }
	  
	  /** ------------------ */
	  session_write_close(); // Close the DB data storage session 
	  /** ------------------ */
  }
  
  # ************************************ #
  # ***** END OF SESSION FUNCTIONS ***** #
  # ************************************ #

?>