<?php
/**
 * referral.class.php
 *
 * A referral class to keep track of referrals that are given on the website.
 * This class extends the base User class. It's independent of its parent.
 *
 * Developed by HTMLgraphic Designs
 * Copyright: 2010 HTMLgraphic Designs, LLC.
 * 
 * v1.2 2010-9-23 
 		Edited the file to work with object class eUser.
		Updated database columns names
 */
 
class Invite extends eUser {
	
	public $Email;
	public $message;
	public $ID;
	
	/**
	 * Add a new user to the database. This user is just able to give referrals.
	 *
	 * @return int
	 */
	function new_invite() {
		global $Conf, $User;
		
		
		
		// referralData is Full Name, Phone, and Email stored in an array.
		$fields = Array("`UserID`" 			=> "'{$User->ID}'",
						"`Email`" 			=> "'{$this->Email}'",
						"`message`" 		=> "'{$this->Message}'",
						"`dateAdded`" 		=> "NOW()"
						);
		
		myDBC::insert_Record($Conf['data']->Invites, $fields);
						
	}
	


}
?>