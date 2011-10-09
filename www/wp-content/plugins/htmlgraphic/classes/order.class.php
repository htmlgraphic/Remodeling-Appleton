<?php
/** 
 * order.class.php
 *
 * A user class to keep track of online, new,
 * and registered users.
 *
 * Developed by HTMLgraphic Designs
 * Copyright: 2010 HTMLgraphic Designs, LLC.
 * 
 */  
class Order {
	public $oid;
	public $cardtype;
	public $cardnumber;
	public $cardexpmonth;
	public $cardexpyear;
	public $tax;
	public $discount;
	public $chargetotal;
		
	public $ordertype;
	public $cvmvalue;
	public $cvmindicator;
	public $host;
	public $keyfile;
	public $configfile;
	public $port;
	public $details;
	public $approval;
	public $debugging;
	public $name;
	public $addrnum;
	
	public $ip;
	public $items;
	public $weight;
	public $email;
	public $address1;
	public $address2;
	public $city;
	public $state;
	public $zip;
	public $country;
}
?>