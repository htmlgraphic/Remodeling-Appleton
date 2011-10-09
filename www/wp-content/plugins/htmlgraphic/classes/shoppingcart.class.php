<?php
/**
 * shoppingcart.class.php 
 *
 * A shopping cart class to keep track of online
 * e-Commerce transactions.
 *
 * Developed by HTMLgraphic Designs
 * Copyright: 2010 HTMLgraphic Designs, LLC.
 *
 */
class ShoppingCart {
	public $items;
	public $SalesTax;
	public $Shipping;
	public $Last_Order_ID;
	public $CartID;
	protected $UID;
	protected $IP;
	private $Ordered;
	
	/**
	 * CONSTRUCTOR
	 *
	 * @return boolean
	 */
	function __construct() {
		@list(
			$this->UID,
			$this->CartID,
			$this->IP
		) = func_get_args();
		if (empty($this->CartID)) $this->CartID = mt_rand();
		if (empty($this->IP)) $this->IP = $_SERVER['REMOTE_ADDR'];
		return true;
	}
	
	/**
	 * Get all available provinces that we can Ship to.
	 *
	 * @return array
	 */ 
	function shipping_provinces() { 
		global $Conf, $dbconn;
		$rows = Array();
		$q = "SELECT DISTINCT name,AutoID FROM ".$Conf["data"]->ProvinceTable." ORDER BY `provinces`.`AutoiD` ASC, `provinces`.`id` ASC";
		$r = mysqli_query($dbconn, $q);
		while ($row = mysqli_fetch_object($r)) $rows[] = $row;
		return $rows;
	}
	
	/**
	 * Get information about a Province.
	 *
	 * @param int $province_id
	 * @return object
	 */
	function get_province_by_id($province_id) {
		global $Conf, $dbconn;
		if ($province_id) {
			myDBC::new_andWhere();
			myDBC::andWhere("`autoid` = $province_id");
			$tA = $Conf["data"]->ProvinceTable;
			$q = "SELECT name,symbol FROM {$tA}".myDBC::get_andWhere()." LIMIT 1";
			$r = mysqli_query($dbconn, $q);
			$row = mysqli_fetch_object($r);
			return $row; 
		} else {
			return false;
		}
	}
	
	/**
	 * Get all available countries that we can Ship to.
	 *
	 * @return array
	 */
	function shipping_countries() {
		global $Conf, $dbconn;
		myDBC::new_andWhere();
		myDBC::andWhere("`show` = 'yes'");
		$rows = Array();
		$q = "SELECT DISTINCT country,AutoID FROM ".$Conf["data"]->CountryTable.myDBC::get_andWhere()." ORDER BY country ASC";
		$r = mysqli_query($dbconn, $q);
		while ($row = mysqli_fetch_object($r)) $rows[] = $row;
		return $rows;
	}
	
	/**
	 * Get information about a Country.
	 *
	 * @param int $country_id
	 * @return object
	 */
	function get_country_by_id($country_id) {
		global $Conf, $dbconn;
		myDBC::new_andWhere();
		myDBC::andWhere("`AutoID` = $country_id");
		$q = "SELECT * FROM ".$Conf["data"]->CountryTable.myDBC::get_andWhere()." LIMIT 1";
		$r = mysqli_query($dbconn, $q);
		$row = mysqli_fetch_object($r);
		return $row;
	}

	/**
	 * Add a new item to the cart.
	 *
	 * @param int $id
	 * @param int $qty
	 * @param float $saleprice
	 * @return int
	 */
	function add_item($id, $qty, $saleprice = 0.00) {
		global $Conf, $dbconn;
		
		myDBC::new_andWhere();
		myDBC::andWhere("`AutoID` = $id");
		$q = "SELECT * FROM ".$Conf["data"]->ProductsTable.myDBC::get_andWhere()." LIMIT 1";

		$r = mysqli_query($dbconn, $q);
		$item = mysqli_fetch_object($r);


		# We already have an item in our cart here.
		# Don't add a new one, update quantity of old one instead.
		if (isset($this->items[$id])) return $this->update_item($id, $qty, true);
		
		# Verify sale price.
		if ($saleprice == 0.00 || empty($saleprice)) $saleprice = ($item->MSRP * $qty);
		
		$this->items[$id] =
			new ShoppingCartItem(
				$qty,
				$item->MSRP,
				$saleprice,
				$id,
				$item->stockName
			);

		return $this->add_db_item($id, $saleprice);
	}
	
	/**
	 * Add Order entry into the Orders table (for future tracking).
	 *
	 * @param int $id
	 * @param int $order_id
	 * @param float $saleprice
	 * @return int
	 */		
	function add_db_item($id, $saleprice = 0.00) {
		global $Conf;
			
		# Verify sale price.
		if ($saleprice == 0.00 || empty($saleprice)) $saleprice = ($this->items[$id]->quantity * $this->items[$id]->price);
		
		$fields = Array(
			"`UID`" => ($this->UID > 0) ? "'{$this->UID}'" : "NULL",
			"`CartID`" => "'{$this->CartID}'",
			"`Price`" => "'{$this->items[$id]->price}'",
			"`PriceSold`" => "'$saleprice'",
			"`DateTime`" => "NOW()",
			"`IP`" => "'{$this->IP}'",
			"`Quantity`" => "'{$this->items[$id]->quantity}'",
			"`ProductID`" => "'$id'"
			);
		
		return myDBC::insert_Record($Conf["data"]->OrdersTable, $fields);
	}

		
	/**
	 * Run the checkout process.
	 * This function will assemble all of the products, and their quantities
	 * from the user's cart and create a grand total. A report is generated,
	 * along with any credit card information to be encrypted and an
	 * e-mail is sent to the intented merchant recipient.
	 *
	 */
	function cart_checkout() {

	}
	
	/**
	 * Empty the user's current shopping cart.
	 *
	 * @return boolean
	 */
	function empty_cart() {
		global $Conf;
		unset($this->items);
		unset($this->Ordered);
		unset($this->SalesTax);
//		unset($User->CartID);
		
		# update User table to remove CartID association
		myDBC::new_andWhere();
		myDBC::andWhere("`CartID` = {$this->CartID}");
		$fields = Array("`CartID`" => mt_rand());
		
		return myDBC::update_Record($Conf["data"]->UserTable, $fields, myDBC::get_andWhere());
		

		# go through and empty out related entries in the Orders table
		myDBC::new_andWhere();
		myDBC::andWhere("`CartID` = {$this->CartID}");
		return myDBC::delete_Record($Conf['data']->OrdersTable, myDBC::get_andWhere());
	}
	
	/**
	 * Remove an item from the cart and Order table.
	 *
	 * @param int $product_id
	 */
	function remove_item($id) {
		global $Conf;
		if (array_key_exists($id, $this->items)) {
			myDBC::new_andWhere();
			myDBC::andWhere("`ProductID` = $id");
			myDBC::andWhere("`CartID` = {$this->CartID}");
			unset($this->items[$id]);
			myDBC::delete_Record($Conf["data"]->OrdersTable, myDBC::get_andWhere());
		}
	}
		
	/**
	 * Return an array of items in the cart as objects.
	 *
	 * @return array
	 */
	function show_cart() {
		if (count($this->items) > 0) return $this->items;
		else return Array();
	}
	
	/**
	 * Show ID of current shopping cart.
	 *
	 * @return int
	 */
	function show_cart_id() {
		return $this->CartID;
	}
	
	/**
	 * Add's commas to strings 
	 */
	function commafy($_) {
       return strrev((string)preg_replace( '/(\d{3})(?=\d)(?!\d*\.)/', '$1,' , strrev( $_ ) ) );
	}
	
	/**
	 * Return how many items are in the current shopping cart.
	 * Note: this includes quantities of those items as well.
	 *
	 * @param boolean $WithOptions
	 * @param boolean $WithColors
	 * @return int
	 */
	function show_cart_count($WithOptions = true, $WithColors = true) {
		$count = 0;
		
		if (!empty($this->items))
			foreach ($this->items as $item)
				$count += $item->quantity;
					
		return $this->commafy($count);
	}
	
	/**
	 * Return the total cost of merchandise in the current shopping cart.
	 *
	 * @param boolean $WithOptions
	 * @param boolean $WithColors
	 * @return string
	 */
	function show_cart_cost($WithOptions = true, $WithColors = true, $WithTax = true) {
		$cost = 0.00;
				
		if (!empty($this->items))
			foreach ($this->items as $item)
				$cost += ($item->price * $item->quantity);
				
		// If there is sales tax assigned, add it to total cost
		if ($this->SalesTax > 0.00 && $WithTax == true)
			$cost += $this->SalesTax;

			$cost += $_SESSION["discount_total"];
			
			$cost += $this->Shipping;
		
		return $cost;
	}
	
	
	/**
	 * Return the total cost of merchandise in the current shopping cart. HEADER ONLY ***************************************
	 *
	 * @param boolean $WithOptions
	 * @return string
	 */
	function show_carth_cost($WithOptions = true, $WithColors = true) {
		$cost = 0.00;
		
		if (!empty($this->items))
			foreach ($this->items as $item)
				$cost += ($item->price * $item->quantity);
		
		// If there is salestax assigned, add it to the total cost.
		//if ($this->SalesTax > 0.00) $cost += $this->SalesTax;
		
		$cost = sprintf("%.2f", $cost);
		
		$cost2 = $cost;
		if(strlen($cost) > 13) {
			$cost = substr($cost, 0, 10) . "...";
			$cost = '<a style="text-decoration: none;" href="/cart/" title="'.$cost2.'">'.$cost.'</a>';
		}
		return $this->commafy($cost);
	}

	function update_shipping($force = false) {
		global $user;
		
		// Set additional shipping costs for orders below minimal amount
		// Add $13.00 flat shipping plus $3 for each additional item.
		if ($force == true) {
			$total_items = $this->show_cart_count();
			if ($total_items == 1) {
				// Only one item
				$this->Shipping = 13.00;		
			} else {
				// More than one item
				$this->Shipping = 13.00 + (($total_items -1) * 3);
			}			
		} else {
			$this->Shipping = 0.00;
		}
	}

	function update_sales_tax($force = false) {
		global $user;
		
		// If the customer is in Wisconsin, include sales tax in the equation.
		if ($force == true) {
			// Force sales tax application
			$this->SalesTax = 0.05 * $this->show_cart_cost(true, true, false);
		}
		elseif ($user->State == DEF_STATE_WI) {
			// If user is logged in and has an assigned state
			$this->SalesTax = 0.05 * $this->show_cart_cost(true, true, false);
		}
		else {
			$this->SalesTax = 0.00;
		}
	}
	
	/**
	 * Update item in the shopping cart.
	 * Note: Setting $rel to true means relative to current value.
	 * For example: 50 items are in the car, $qty = -5 with
	 * 			$rel set to true, new items is 45 items. Otherwise
	 *			quantity is set to the value of $qty.
	 *
	 * @param int $id
	 * @param int $qty
	 * @param boolean $rel
	 * @return int
	 */
	function update_item($id, $qty = 0, $rel = false) {
		global $Conf;
		if (array_key_exists($id, $this->items)) {
			myDBC::new_andWhere();
			myDBC::andWhere("`ProductID` = $id");
			myDBC::andWhere("`CartID` = {$this->CartID}");
			
			if ($qty == 0) {
				unset($this->items[$id]);
				return myDBC::delete_Record($Conf["data"]->OrdersTable, myDBC::get_andWhere());
			}
			elseif ($rel === true) {
				$this->items[$id]->quantity += $qty;
				$this->items[$id]->saleprice =
							($this->items[$id]->quantity * $this->items[$id]->price);
				$fields = Array("`Quantity`" => "Quantity + $qty",
								"`Price`" => "'{$this->items[$id]->price}'",
								"`PriceSold`" => "'{$this->items[$id]->saleprice}'",
								"`DateTime`" => "NOW()"
								);
				return myDBC::update_Record($Conf["data"]->OrdersTable, $fields, myDBC::get_andWhere());
			}
			else {
				$this->items[$id]->quantity = $qty;
				$this->items[$id]->saleprice =
							($this->items[$id]->quantity * $this->items[$id]->price);
				$fields = Array("`Quantity`" => "$qty",
								"`Price`" => "'{$this->items[$id]->price}'",
								"`PriceSold`" => "'{$this->items[$id]->saleprice}'",
								"`DateTime`" => "NOW()"
								);
				return myDBC::update_Record($Conf["data"]->OrdersTable, $fields, myDBC::get_andWhere());
			}
		}
		else return false;
	}
	
	/**
	 * Update an option in the shopping cart.
	 * $rel works similarly to update_item() above.
	 *
	 * @param int $option_id
	 * @param int $qty
	 * @param boolean $rel
	 * @return int
	 */

	function update_option($option_id, $qty = 0, $rel = false) {
		global $Conf;
		if (array_key_exists($option_id, $this->options)) {
			myDBC::new_andWhere();
			myDBC::andWhere("`OptionID` = $option_id");
			myDBC::andWhere("`CartID` = {$this->CartID}");
			
			if ($qty == 0) {
				unset($this->options[$option_id]);
				return myDBC::delete_Record($Conf["data"]->OrdersTable, myDBC::get_andWhere());
			}
			elseif ($rel === true) {
				$this->options[$option_id]->quantity += $qty;
				$this->options[$option_id]->saleprice =
							($this->options[$option_id]->quantity * $this->options[$option_id]->price);
				$fields = Array("`Quantity`" => "Quantity + $qty",
								"`Price`" => "'{$this->options[$option_id]->price}'",
								"`PriceSold`" => "'{$this->options[$option_id]->saleprice}'",
								"`DateTime`" => "NOW()"
								);
				return myDBC::update_Record($Conf["data"]->OrdersTable, $fields, myDBC::get_andWhere());
			}
			else {
				$this->options[$option_id]->quantity = $qty;
				$this->options[$option_id]->saleprice =
							($this->options[$option_id]->quantity * $this->options[$option_id]->price);
				$fields = Array("`Quantity`" => "$qty",
								"`Price`" => "'{$this->options[$option_id]->price}'",
								"`PriceSold`" => "'{$this->options[$option_id]->saleprice}'",
								"`DateTime`" => "NOW()"
								);
				return myDBC::update_Record($Conf["data"]->OrdersTable, $fields, myDBC::get_andWhere());
			}
		}
		else return false;
	}
	
	
	/**
	 * Associates a UID with the shopping cart.
	 * This links a user to the currently active shopping cart.
	 * For example, a user may have done some shopping before
	 * logging in, so associate the existing cart with the user account.
	 *
	 * @param int,boolean $UID
	 */
	function associate_cart($UID = false) {
		global $Conf;
		if ($UID === false) {
			unset($this->UID);
			myDBC::new_andWhere();
			myDBC::andWhere("`CartID` = {$this->CartID}");
			$fields = Array("`UID`" => "NULL");
			myDBC::update_Record($Conf["data"]->OrdersTable, $fields, myDBC::get_andWhere());
		}
		else {
			$this->UID = $UID;
			myDBC::new_andWhere();
			myDBC::andWhere("`CartID` = {$this->CartID}");
			$fields = Array("`UID`" => "'{$UID}'");
			myDBC::update_Record($Conf["data"]->OrdersTable, $fields, myDBC::get_andWhere());
		}
	}
	
	/**
	 * Retrieve a shopping cart from the database Orders table.
	 * Loads this cart into the current shopping cart.
	 * -- Useful for retrieving a user's current cart
	 *    if they come back with a new SESSION ID
	 *
	 * @param int $CartID
	 */
	function load_cart($CartID) {
		global $Conf, $Products, $dbconn;
		
		myDBC::new_andWhere();
		myDBC::andWhere("`UID` = {$this->UID}");
		myDBC::andWhere("`CartID` = $CartID");
		$query = "SELECT * FROM ".$Conf["data"]->OrdersTable.myDBC::get_andWhere();
		$result = mysqli_query($dbconn, $query);
		if (mysqli_num_rows($result) > 0) $this->CartID = $CartID;
		while ($row = mysqli_fetch_object($result)) {
			$this->items[$row->AutoID] =
				new ShoppingCartItem(
					$row->Quantity,
					$row->Price,
					$row->PriceSold,
					$row->ProductID,
					$Products->Product_Details($row->ProductID)->stockName
				);
		}
	}
	
	/**
	 * Take all items and save information in database Orders table.
	 *
	 * @return int
	 */
	function save_cart() {
		global $Conf;
		myDBC::new_andWhere();
		myDBC::andWhere("`AutoID` = '{$this->UID}'");
		$fields = Array("`CartID`" => "'{$this->CartID}'");
		return myDBC::update_Record($Conf["data"]->UserTable, $fields, myDBC::get_andWhere());
	}
	
	/**
	 * Verify that all items in the cart are available.
	 * If items aren't available, they're automatically removed.
	 * Boolean true is returned if all items are available, false
	 * if some had to be removed.
	 * -- This function should be used right before ::cart_checkout();
	 *
	 * @return boolean
	 */
	function validate_cart() {
		global $Conf;
		return true;
	}

	/**
	 * Set the cart to "Ordered". The entire cart gets empties
	 * if the user leaves their myaccount section.
	 *
	 * @param Ordered boolean
	 * @return boolean
	 */
	function setOrdered($Ordered = false) {
		if ($Ordered == true) $this->Ordered = true;
		else $this->Ordered = false;
		return true;
	}
	
	/**
	 * Has this cart already been ordered?
	 *
	 * @return boolean
	 */
	function isOrdered() {
		if ($this->Ordered == true) return true;
		else return false;
	}
		
	/**
	 * Is the user's shopping cart empty?
	 *
	 * @return boolean
	 */
	function isCartEmpty() {
		if ($this->show_cart_count() == 0) return true;
		else return false;
	}
}

/**
 * An object that keeps track of details for a
 * particular item in a user's shopping cart.
 *
 */
class ShoppingCartItem {
	public $quantity;
	public $price;
	public $saleprice;
	public $ProductID;
	public $Title;
	/**
	 * CONSTRUCTOR
	 *
	 * @return boolean
	 */
	function __construct() {
		@list(
			$this->quantity,
			$this->price,
			$this->saleprice,
			$this->ProductID,
			$this->Title,
		) = func_get_args();
		return true;
	}
}


/**
 * A class of functions to handle Orders in the database.
 * This class stores no relevant information, it is a static
 * class.
 *
 */
class Orders {	
	/**
	 * Retrieves all past orders (and current) from the Orders table.
	 * A range may be specified in the format: YYYY-MM-DD|YYYY-MM-DD
	 *
	 * @param string $range
	 * @return array
	 */
	static function get_Orders($range = "") {
		global $Conf;
		$rows = Array();
		if (!empty($range)) {
			myDBC::new_andWhere();
			$range = explode("|", $range); $range[0] = strtotime($range[0]); $range[1] = strtotime($range[1]);
			# Do we have a valid range to work with?
			if ($range[0] <= $range[1]) {
				myDBC::andWhere("UNIX_TIMESTAMP(DATE(`orderDate`)) >= {$range[0]} ");
				myDBC::andWhere("UNIX_TIMESTAMP(DATE(`orderDate`)) <= {$range[1]} ");
				$query = "SELECT * FROM {$Conf["data"]->ShipTable} ".myDBC::get_andWhere()." ORDER BY `orderDate` DESC";
			}
		}
		else $query = "SELECT * FROM {$Conf["data"]->ShipTable} ORDER BY `orderDate` DESC";
		myDBC::stdQuery($query, $result);

		while ($row = mysqli_fetch_object($result)) $rows[] = $row;
		return $rows;
	}
	
	/**
	 * Get detailed information for a past order in the Orders table.
	 *
	 * @param int $OrderID
	 * @return object
	 */
	static function get_Order($OrderID) {
		global $Conf, $dbconn;
		myDBC::new_andWhere();
		myDBC::andWhere("`AutoID` = '$OrderID'");
		$q = "SELECT * FROM {$Conf["data"]->ShipTable}".myDBC::get_andWhere();
		$r = mysqli_query($dbconn, $q);
		$row = mysqli_fetch_object($r);
		return $row;
	}
	
	/**
	 * Get detailed Orders from Orders table based on CartID. Return array.
	 *
	 * @param int $CartID
	 * @return array
	 */
	static function get_DetailedOrders($CartID) {
		global $Conf, $dbconn;
		$rows = Array();
		myDBC::new_andWhere();
		myDBC::andWhere("`CartID` = '$CartID'");
		$q = "SELECT * FROM {$Conf["data"]->OrdersTable} ".myDBC::get_andWhere()." ORDER BY `DateTime` DESC";
		$r = mysqli_query($dbconn, $q);
		while ($row = mysqli_fetch_object($r)) $rows[] = $row;
		return $rows;
	}
	

	
	/**
	 * Get how much a Shopping cart costed based on Orders table.
	 *
	 * @param int $CartID
	 * @param boolean $itemprices
	 * @return float
	 */
	static function get_CartCost($CartID, $itemprices = false) {
		$cost = 0.00;
		$rows = self::get_DetailedOrders($CartID);
		foreach ($rows as $row) $cost += (float) ($itemprices) ? $row->Price : $row->PriceSold;
		return (float)$cost;
	}
	
	/** 
	 * Get the order's total tax amount (for taxation purposes).
	 *
	 * @param int $CartID
	 * @return float
	 */
	static function get_OrderTax($CartID) {
		global $Conf, $dbconn;
		myDBC::new_andWhere();
		myDBC::andWhere("`CartID` = '$CartID'");
		$q = "SELECT `orderTax` FROM {$Conf["data"]->ShipTable} ".myDBC::get_andWhere()." LIMIT 1";
		$r = mysqli_query($dbconn, $q);
		$row = mysqli_fetch_object($r);
		return $row->orderTax;
	}
	
	/**  
	 * Get the order's total order amount.
	 *
	 * @param int $CartID
	 * @return float
	 */
	static function get_OrderTotal($CartID) {
		global $Conf;
		myDBC::new_andWhere();
		myDBC::andWhere("`CartID` = $CartID");
		$query = "SELECT `orderTotal` FROM {$Conf["data"]->ShipTable} ".myDBC::get_andWhere()." LIMIT 1";
		myDBC::stdQuery($query, $result);
		$row = mysqli_fetch_object($result);
		return $row->orderTotal;
	}

	/** 
	 * Get the order's discount amount.
	 *
	 * @param int $CartID
	 * @return float
	 */
	static function get_OrderDiscount($CartID) {
		global $Conf;
		myDBC::new_andWhere();
		myDBC::andWhere("`CartID` = $CartID");
		$query = "SELECT `promoDiscount` FROM {$Conf["data"]->ShipTable} ".myDBC::get_andWhere()." LIMIT 1";
		myDBC::stdQuery($query, $result);
		$row = mysqli_fetch_object($result);
		return $row->promoDiscount;
	}
	
	/**
	 * Get how many items a shopping cart had in it based on Orders table.
	 *
	 * @param int $CartID
	 * @return int
	 */
	static function get_CartQuantity($CartID) {
		$quantity = 0;
		$rows = self::get_DetailedOrders($CartID);
		foreach ($rows as $row) $quantity += (int) $row->Quantity;
		return (int)$quantity;
	}
	
	/**
	 * What kind of Order is this item.
	 * ITEM, OPTION, or COLOR?
	 *
	 * @param int $product_id
	 * @param int $option_id
	 * @param int $color_id
	 * @return string
	 */
	static function get_OrderType($product_id = 0, $option_id = 0, $color_id = 0) {
		if ($product_id > 0) return "ITEM";
		elseif ($option_id > 0) return "OPTION";
		elseif ($color_id > 0) return "COLOR";
	}
	
	/**
	 * Is the UID associated with the Orders table a valid registered UID?
	 *
	 * @param int $CartID
	 * @return boolean
	 */
	static function is_Registered($CartID) {
		$DetailedOrders = self::get_DetailedOrders($CartID);
		if (count($DetailedOrders) >= 1) {
			if ($DetailedOrders[0]->UID > 0) return true;
			else return false;
		}
	}
	
	/**
	 * This allows for updates to the status for a given ID 
	 *
	 */
	public function Update_Status($ship_id, $status) {
		global $Conf, $dbconn;
		$rows = Array();
		$query = "UPDATE {$Conf["data"]->ShipTable} SET {$Conf["data"]->ShipTable}.statusID = '$status' WHERE {$Conf["data"]->ShipTable}.AutoID = '$ship_id'";		
		$result = mysqli_query($dbconn, $query);
		while ($row = mysqli_fetch_object($result)) $rows[] = $row;
		return $rows;
	}
	
	/**
	 * This retrieves the status for a given ID 
	 *
	 */
	public function Get_Status($ship_id) {
		global $Conf, $dbconn;
		$rows = Array();
		$query = "SELECT {$Conf["data"]->ShipTable}.statusID FROM {$Conf["data"]->ShipTable} WHERE {$Conf["data"]->ShipTable}.AutoID = '$ship_id'";		
		$result = mysqli_query($dbconn, $query);
		while ($row = mysqli_fetch_object($result)) $rows[] = $row;
		return $rows;
	}
	
}
?>