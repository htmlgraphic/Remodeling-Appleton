<?php
/**
 * Object extension of Security for managing security protocols,
 * access, audits, logging and security reporting.
 *
 * @author HTMLgraphic Designs
 * @version 2.0
 * @copyright 2009
 */
class xSecurity extends Security {	
	/**
	 * Construct a new security model in the site.
	 * for access, auditing, logging & reporting. Assign
	 * access levels based on user access level passed along
	 * through the constructor.
	 */
	public function __construct($access_level) {
		global $User;
		
		// Call parent constructor
		parent::__construct($access_level);
	}
}
?>