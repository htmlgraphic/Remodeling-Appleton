<?php
/**
 * register.class.php
 *
 * A register class to keep track of user that register with the website.
 * This class extends the base User class. It's independent of its parent.
 *
 * Developed by HTMLgraphic Designs
 * Copyright: 2010 HTMLgraphic Designs, LLC.
 * 
 * v1.2 2010-9-23 
 		Edited the file to work with object class eUser.
		Updated database columns names
 */
 
class Register extends eUser {
	
	public $Agent;
	public $Ip;
		
	/**
	 * Add a new user to the database. This user is just able to give referrals.
	 *
	 * @return int
	 */
	function add_user() {
		global $Conf;
		
		$fields = Array("`useremail`" 		=> "'{$this->Email}'",
						"`passwd`" 			=> "'{$this->password}'",
						"`level`" 			=> "'user'",
						"`is_enabled`"		=> "'yes'",
						"`date_added`" 		=> "NOW()");
		
		return myDBC::insert_Record($Conf['data']->UserTable, $fields);
	}
	
	
	
	/**
	 * Add a new user to the database. This user is just able to give and receive referrals.
	 *
	 * @return int
	 */
	function add_member() {
		global $Conf, $order, $User;

		// Process and store payment information
		$privateKey = genPrivateKey(); //Generate a private key - This will made into a static value after generating it once
		$data = (array('status' => $this->ccStatus, 'details' => $this->ccDetails, 'error' => $this->ccError, 'tcode' => $this->ccTCode, 'name' => $order->name, 'ccType' => $order->cardtype, 'ccNum' => $order->cardnumber, 'MM' => $order->cardexpmonth, 'YY' => $order->cardexpyear, 'ccCODE' => $order->cvmvalue));
		$data = encrypt(serialize($data), $privateKey); //Catches the ciphertext from the encrypt function	
		
		// Convert the string value users enter and update it to and numeric string, autoid
		$state = $User->get_province_by_name(strtoupper($order->state));		
		
		$fields = Array("`userFirstname`" 	=> "'{$this->Firstname}'",
						"`userLastname`" 	=> "'{$this->Lastname}'",
						"`useremail`" 		=> "'{$this->Email}'",
						"`passwd`" 			=> "'{$this->password}'",
						"`shipAddr1`" 			=> "'{$order->address1}'",
						"`shipCity`" 			=> "'{$order->city}'",
						"`shipState`" 			=> "'{$state->autoid}'",
						"`shipZip`" 			=> "'{$order->zip}'",
						"`cc`" 				=> "'{$data}'",
						"`level`" 			=> "'member'",
						"`is_enabled`"		=> "'yes'",
						"`date_added`" 		=> "NOW()",
						"`paymentType`" 	=> "'Credit Card'");

		myDBC::insert_Record($Conf['data']->UserTable, $fields);

		$ID = myDBC::last_insert_id();
		
		capture_cc($ID, '', $privateKey); // Create private key

	}
	
	
	
	/**
	 * Add a new user to the database. This user is just able to give and receive referrals.
	 *
	 * @return int
	 */
	function update_member() {
		global $Conf, $order, $User;
				
		// Process and store payment information
		$privateKey = decrypt_data($this->ID,true); // Return the stored encrypted key
		if (!$privateKey) { // No key exists create a new one.
			$privateKey = genPrivateKey(); //Generate a private key - This will made into a static value after generating it once
		}
		
		
		$data = (array('status' => $this->ccStatus, 'details' => $this->ccDetails, 'error' => $this->ccError, 'tcode' => $this->ccTCode, 'name' => $order->name, 'ccType' => $order->cardtype, 'ccNum' => $order->cardnumber, 'MM' => $order->cardexpmonth, 'YY' => $order->cardexpyear, 'ccCODE' => $order->cvmvalue));
		$data = encrypt(serialize($data), $privateKey); //Catches the ciphertext from the encrypt function
		
		// Convert the string value users enter and update it to and numeric string, autoid
		$state = $User->get_province_by_name(strtoupper($order->state));	
		
		$tA = $Conf["data"]->UserTable;
		myDBC::new_andWhere();
		myDBC::andWhere("$tA.AutoID = '$this->ID'");
		
		$fields = Array("`shipAddr1`" 		=> "'{$order->address1}'",
						"`shipCity`" 		=> "'{$order->city}'",
						"`shipState`" 		=> "'{$state->autoid}'",
						"`shipZip`" 		=> "'{$order->zip}'",
						"`cc`" 				=> "'{$data}'",
						"`level`" 			=> "'member'",
						"`is_enabled`"		=> "'yes'",
						"`date_added`" 		=> "NOW()",
						"`paymentType`" 	=> "'Credit Card'");
		
		capture_cc($this->ID, '', $privateKey);
		
		myDBC::update_Record($tA, $fields, myDBC::get_andWhere(), " LIMIT 1");
		
		
	}

}
?>
