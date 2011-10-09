<?php
/**
 * Comments.class.php
 *
 * A Comments class to keep track of user that Comments with the website.
 * This class extends the base User class. It's independent of its parent.
 *
 * Developed by HTMLgraphic Designs
 * Copyright: 2010 HTMLgraphic Designs, LLC.
 * 
 *
 * v1.2 2010-3-16 
 		Updated database column names to match the user_signup table
 
 * v1.1 2009-10-14 
 		Edited the file to work with object class eUser.
		Updated database columns names
 */
 
class Comments extends eUser {
	public $HearAboutUs;
	public $HearAboutUs_S;
	
	/**
	 * Comments string.
	 *
	 * @var string 
	 */
	public $Comments;
	
	public $Time;
	public $Ip;
	
	/**
	 * Receive updates?
	 *
	 * @var string
	 */
	public $Brochure;
	public $News;
	
	/**
	 * Add a new comment to the Comments table in the database.
	 *
	 * @return int
	 */
	function add_Comments() {
		global $Conf;
		
//		$birthday = $this->Birthday['Y'].'-'.$this->Birthday['F'].'-01';
//		$anniversary = $this->Anniversary['Y'].'-'.$this->Anniversary['F'].'-01';
		if ($this->State < '253') {
			$country = '13';
		} else {
			$country = '92';
		}
		
		$fields = Array("`useremail`" 		=> "'{$this->Email}'",
						"`userFirstname`" 	=> "'{$this->Firstname}'",
						"`userLastname`" 	=> "'{$this->Lastname}'",
						"`userAddr1`" 		=> "'{$this->Address1}'",
						"`userCity`" 		=> "'{$this->City}'",
						"`userState`" 		=> "'{$this->State}'",
						"`userCountry`" 	=> "'{$country}'",
						"`userZip`" 		=> "'{$this->ZIP}'",
						"`userPhone`" 		=> "'{$this->PhoneNumber}'",
						"`brochure`"		=> "'{$this->Brochure}'",
						"`news`"			=> "'{$this->News}'",
						"`hearaboutus`" 	=> "'{$this->HearAboutUs2}'",
						"`vehicle`" 		=> "'{$this->Vehicle}'",
						"`comments`" 		=> "'{$this->Comments}'",
						"`date_added`" 		=> "NOW()",
						"`last_ip`" 		=> "'{$this->Ip}'");

		return myDBC::insert_Record($Conf["data"]->Comments, $fields);
	}
}
?>