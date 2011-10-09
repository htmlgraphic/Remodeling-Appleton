<?php

/**
 * This defines a model for user logins and authentication.
 *
 * @author HTMLgraphic Designs
 * @copyright 2009 HTMLgraphic Designs, LLC.
 * @version 2.1.1
 * @date 2010-2-11
 */
class eUser
{

  /**
   * Username used to login with.
   *
   * @var string
   */
  public $username;
  /**
   * Password used to login with.
   *
   * @var string
   */
  public $password;
  /**
   * User's last name.
   *
   * @var string
   */
  public $LastName;
  /**
   * User's first name.
   *
   * @var string
   */
  public $FirstName;
  /**
   * User account information
   *
   * @var string
   */
  public $Company;
  public $Address1;
  public $Address2;
  public $City;
  public $State;
  public $Country;
  public $ZIP;
  /**
   * Business account information
   *
   * @var string
   */
  public $Web;
  public $profileBio;
  public $CompanyType;
  /**
   * User's Birthday
   *
   * @var string
   */
  public $Birthday;
  /**
   * User's Anniversary
   *
   * @var string
   */
  public $Anniversary;
  /**
   * User's phone number.
   *
   * @var string
   */
  public $Phone;
  public $AltPhone;
  /**
   * User's e-mail address.
   *
   * @var string
   */
  public $Email;
  /**
   * User's confirmation e-mail address.
   *
   * @var string
   */
  public $Email2;
  /**
   * User's access level.
   *
   * @var string
   */
  public $level;
  public $siteAreas;
  /**
   * Is this account enabled (can be logged in to?)
   *
   * @var boolean
   */
  public $is_enabled;
  /**
   * If this login is the 1st one for the user.
   *
   * @var boolean
   */
  public $is_FirstLogin = false;
  /**
   * Date when the user last logged in.
   *
   * @var string
   */
  public $Last_Login;
  /**
   * User ip address
   *
   * @var string
   */
  public $IP;
  /**
   * User browser type fullname
   *
   * @var string
   */
  public $Browser_Agent;
  /**
   * Timestamp of recent form submission
   *
   * @var string
   */
  public $DateTime;
  /**
   * How did you hear about us?
   *
   * @var string
   */
  public $HearAboutUs;
  /**
   * Specify how you heard about us.
   *
   * @var string
   */
  public $HearAboutUs_s;
  /**
   * User submission comments or questions.
   *
   * @var string
   */
  public $Comments;
  /**
   * Used to authorize the user in to the system.
   * If there is an authcode it must be verified in order for
   * this user to log in anywhere on the site!
   *
   * @var string
   */
  public $authcode;
  /**
   * ID number associated in the database with this user.
   *
   * @var int
   */
  public $ID;
  /**
   * Company ID number associated with this user.
   *
   * @var int
   */
  public $CompanyID;
  /**
   * Randomly generated Client ID to back-reference the login.
   *
   * @var int
   */
  private $CartID;
  /**
   * Current index of user's session status log.
   *
   * @var int
   */
  public $SHIPFirstName;
  public $SHIPLastName;
  public $SHIPAddress1;
  public $SHIPAddress2;
  public $SHIPCity;
  public $SHIPState;
  public $SHIPZip;
  public $SHIPCountry;
  public $SHIPPhone;
  public $SHIPAltPhone;
  public $SHIPComments;
  public $BILLFirstName;
  public $BILLLastName;
  public $BILLAddress1;
  public $BILLAddress2;
  public $BILLCity;
  public $BILLState;
  public $BILLZip;
  public $BILLCountry;
  public $BILLPhone;
  public $BILLComments;
  public $BILLCardType;
  public $BILLCardNumber;
  public $BILLCardCVM;
  public $BILLCardExpMo;
  public $BILLCardExpYr;
  public $error;
  /**
   * Complete Shipping and Billing information
   *
   * @var int
   */
  public $StatusLogLoc = 0;
  /**
   * Messages in user's session status log.
   *
   * @var array
   */
  public $StatusLogMsg = array();
  /**
   * Types of statuses in user's session status log.
   *
   * @var array
   */
  public $StatusLogType = array();
  /**
   * Description of statuses in user's session status log.
   *
   * @var array
   */
  public $StatusLogDesc = array();
  /**
   * Misc. variables set in a user's session status log.
   *
   * @var array
   */
  public $StatusLogVars = array();
  /**
   * Are we a validated user?
   *
   * @var boolean
   */
  public $validated = false;
  /**
   * Is the password in the password variable a crypt password, or is it
   * a plain-text password? (Used when we need to save password, whether to
   * overwrite the old ones or not).
   *
   * @var boolean
   */
  public $isCryptedPW = true;

  /**
   * Construct new instance of this class and randomly generate
   * a client ID number.
   *
   */
  function __construct($user_id = null)
  {
    if (is_numeric($user_id) && $user_id > 0)
    {
      // Load information about this user from the database.
      $this->loadUserInformation($user_id);
    }
  }

  /**
   * Retrieve the latest status message and all details associated
   * with it as an object.
   *
   * @return object
   */
  function get_LastStatus()
  {
    if ($this->StatusLogLoc >= 0)
    {
      if (!empty($this->StatusLogMsg[$this->StatusLogLoc]))
        $y = true;
      $response->Msg = $this->StatusLogMsg[$this->StatusLogLoc];
      $response->Type = $this->StatusLogType[$this->StatusLogLoc];
      $response->Desc = $this->StatusLogDesc[$this->StatusLogLoc];
      $response->Vars = $this->StatusLogVars[$this->StatusLogLoc];

      if ($y)
        $this->StatusLogLoc++;
      return $response;
    }
    return false;
  }

  /**
   * Check to see if the a email address already exists in the system
   *
   * @param string $username
   * @return boolean
   */
  function check_email($username)
  {
    global $Conf, $dbconn;
    $q = "SELECT COUNT(useremail) as `usercount` FROM `" . $Conf["data"]->UserTable . "` WHERE useremail='" . $username . "' LIMIT 1";
    $r = mysqli_query($dbconn, $q);
    $row = mysqli_fetch_object($r);
    return $row->usercount;
  }

  /**
   * Register a new customer to the site.
   *
   * @return int
   */
  function register_user()
  {
    global $Conf;
    $fields = Array("`is_enabled`" => "'yes'",
        "`useremail`" => "'{$this->username}'",
        "`passwd`" => "'{$this->password}'",
        "`userFirstname`" => "'{$this->FirstName}'",
        "`userLastname`" => "'{$this->LastName}'",
        "`userCompany`" => "'{$this->Company}'",
        "`userAddr1`" => "'{$this->Address1}'",
        "`userAddr2`" => "'{$this->Address2}'",
        "`userCity`" => "'{$this->City}'",
        "`userState`" => "'{$this->State}'",
        "`userZip`" => "'{$this->ZIP}'",
        "`userCountry`" => "'{$this->Country}'",
        "`userPhone`" => "'{$this->Phone}'",
        "`userAltPhone`" => "'{$this->AltPhone}'",
        "`shipFirstname`" => "'{$this->SHIPFirstName}'",
        "`shipLastname`" => "'{$this->SHIPLastName}'",
        "`shipAddr1`" => "'{$this->SHIPAddress1}'",
        "`shipAddr2`" => "'{$this->SHIPAddress2}'",
        "`shipCity`" => "'{$this->SHIPCity}'",
        "`shipState`" => "'{$this->SHIPState}'",
        "`shipZip`" => "'{$this->SHIPZip}'",
        "`shipCountry`" => "'{$this->SHIPCountry}'",
        "`shipPhone`" => "'{$this->SHIPPhone}'",
        "`shipAltPhone`" => "'{$this->SHIPAltPhone}'",
        "`shipComments`" => "'{$this->SHIPComments}'",
        "`level`" => "'user'",
        "`siteAreas`" => "'user'");
    return myDBC::insert_Record($Conf['data']->UserTable, $fields);
  }

  /**
   * Copy user details to the Shipping table after an order has been processed.
   *
   * @return int
   */
  function copy_user()
  {
    global $Conf, $cart, $order;

    $ccNum = substr($order->cardnumber, (strlen($order->cardnumber) - 4), 4);
    $fields = Array("`CartID`" => "'{$this->CartID}'",
        "`billEmail`" => "'{$this->username}'",
        "`billFirstname`" => "'{$this->BILLFirstName}'",
        "`billLastname`" => "'{$this->BILLLastName}'",
        "`billCompany`" => "'{$this->BILLCompany}'",
        "`billAddr1`" => "'{$this->BILLAddress1}'",
        "`billAddr2`" => "'{$this->BILLAddress2}'",
        "`billCity`" => "'{$this->BILLCity}'",
        "`billState`" => "'{$this->BILLState}'",
        "`billZip`" => "'{$this->BILLZip}'",
        "`billCountry`" => "'{$this->BILLCountry}'",
        "`billPhone`" => "'{$this->BILLPhone}'",
        "`billAltPhone`" => "'{$this->BILLAltPhone}'",
        "`shipFirstname`" => "'{$this->SHIPFirstName}'",
        "`shipLastname`" => "'{$this->SHIPLastName}'",
        "`shipAddr1`" => "'{$this->SHIPAddress1}'",
        "`shipAddr2`" => "'{$this->SHIPAddress2}'",
        "`shipCity`" => "'{$this->SHIPCity}'",
        "`shipState`" => "'{$this->SHIPState}'",
        "`shipZip`" => "'{$this->SHIPZip}'",
        "`shipCountry`" => "'{$this->SHIPCountry}'",
        "`shipPhone`" => "'{$this->SHIPPhone}'",
        "`shipAltPhone`" => "'{$this->SHIPAltPhone}'",
        "`shipComments`" => "'{$this->SHIPComments}'",
        "`orderShipping`" => "'{$cart->Shipping}'",
        "`orderTax`" => "'{$order->tax}'",
        "`orderTotal`" => "'{$order->chargetotal}'",
        "`orderDate`" => "NOW()",
        "`promoCode`" => "'{$this->promoCode}'",
        "`promoDiscount`" => "'{$order->discount}'",
        "`referredBy`" => "''",
        "`paymentType`" => "'{$order->cardtype}'");
    myDBC::insert_Record($Conf['data']->ShipTable, $fields);
    return myDBC::last_insert_id();
  }

  /**
   * Update BUSINESS PROFILE information in the database.
   *
   * @return int
   */
  function update_profile()
  {

    global $Conf, $dbconn;

    if ($this->isValidated())
    {
      myDBC::new_andWhere();
      myDBC::andWhere("`AutoID` = {$this->ID}");
      $fields = Array("`userCompany`" => "'{$this->Company}'",
          "`userAltPhone`" => "'{$this->AltPhone}'",
          "`web`" => "'{$this->Web}'",
          "`userBio`" => "'{$this->profileBio}'",
          "`userAddr1`" => "'{$this->Address1}'",
          "`userAddr2`" => "'{$this->Address2}'",
          "`userCity`" => "'{$this->City}'",
          "`userState`" => "'{$this->State}'",
          "`userZip`" => "'{$this->ZIP}'"
      );

      foreach ($this->CompanyType as &$cID)
      {
        $c++;
        if ($cID != 0)
        {
          // Log the category type in the category database table.
          $q = "INSERT INTO `business_category_index` ( `uID`, `cID`, `level` ) VALUES ( '" . $this->ID . "', '" . $cID . "', '" . $c . "' );";
          $dbconn->query($q);
        }
      }

      // Clean 'business_category_index' table remove unneeded categories
      clean_category_index($this->ID);


      return myDBC::update_Record($Conf['data']->UserTable, $fields, myDBC::get_andWhere());
    }
    else
      return false;
  }

  /**
   * Update USER SETTINGS in the database.
   *
   * @return int
   */
  function update_settings()
  {

    global $Conf;
    if ($this->isValidated())
    {
      myDBC::new_andWhere();
      myDBC::andWhere("`AutoID` = {$this->ID}");
      $fields = Array("`userFirstname`" => "'{$this->FirstName}'",
          "`userLastname`" => "'{$this->LastName}'",
          "`useremail`" => "'{$this->Email}'",
          "`userPhone`" => "'{$this->Phone}'",
          "`sms`" => "'{$this->sms}'"
      );
      return myDBC::update_Record($Conf['data']->UserTable, $fields, myDBC::get_andWhere());
    }
    else
      return false;
  }

  /**
   * Update USER MAILING ADDRESS in the database.
   *
   * @return int
   */
  function update_personal()
  {

    global $Conf;
    if ($this->isValidated())
    {
      myDBC::new_andWhere();
      myDBC::andWhere("`AutoID` = {$this->ID}");
      $fields = Array("`shipAddr1`" => "'{$this->SHIPAddress1}'",
          "`shipAddr2`" => "'{$this->SHIPAddress2}'",
          "`shipCity`" => "'{$this->SHIPCity}'",
          "`shipState`" => "'{$this->SHIPState}'",
          "`shipZip`" => "'{$this->SHIPZip}'"
      );
      return myDBC::update_Record($Conf['data']->UserTable, $fields, myDBC::get_andWhere());
    }
    else
      return false;
  }

  /**
   * Invalidate a user's login and destroy this class instance.
   *
   */
  function sign_out()
  {
    global $User;

    $this->ID = NULL;
    $this->validated = false;
    $this->admin = false;
    $this->guest = true;

    // Reset and sales taxes set
    unset($_SESSION["hdnWI"]);
    if (is_object($cart))
      $cart->SalesTax = 0.00;

    unset($this);
  }

  /**
   * Assign a new AuthCode to the user account for password retrieval purposes.
   * Returned the generated AuthCode.
   *
   * @param string $username
   * @return string
   */
  function set_authcode($username)
  {
    global $dbconn;

    $AuthCode = md5((string) mt_rand());
    // Log the key for encrpytion in the key table
    $q = "UPDATE `user_signup` SET `authCode` = '" . $AuthCode . "'  WHERE useremail='{$username}'";
    mysqli_query($dbconn, $q);
    return $AuthCode;
  }

  /**
   * Check to see if the proper validation code was given for the user,
   * and that there's an authcode actually set in the user account.
   *
   * @param string $username
   * @param string $authcode
   * @return boolean
   */
  function validate_authcode($username, $authcode)
  {
    global $Conf;

    $username = trim($username);
    $authcode = trim($authcode);

    if (!$this->isValidated())
    {
      myDBC::new_andWhere();
      myDBC::andWhere("`useremail` = '$username'");
      $query = "SELECT authCode FROM {$Conf["data"]->UserTable}" . myDBC::get_andWhere() . " LIMIT 1";
      myDBC::stdQuery($query, $r);
      $row = mysqli_fetch_object($r);
      if (!strcmp($row->authCode, $authcode) && !empty($row->authCode))
        return true;
      else
        return false;
    }
    else
      return false;
  }

  /**
   * Check to see if the proper validation code was given for the user,
   * and that there's an authcode actually set in the user account. This new
   * method users the customers AutoID
   *
   * @param string $username
   * @param string $authcode
   * @return boolean
   */
  function validate_authcode2($id, $authcode)
  {
    global $Conf, $dbconn;

    $id = trim($id);
    $authcode = trim($authcode);

    $tA = $Conf["data"]->UserTable;
    $q = "SELECT * FROM `{$tA}` WHERE AutoID='{$id}' LIMIT 1";
    $r = mysqli_fetch_object(mysqli_query($dbconn, $q));

    if (!strcmp($r->authCode, $authcode) && !empty($r->authCode))
    {

      // Reset MailChimp sync status. 1 = SYNC'd | 0 = Ready to sync
      $q = "UPDATE `user_signup` SET `mailChimp` = '0'  WHERE `AutoID` = '{$id}' LIMIT 1";
      mysqli_query($dbconn, $q);

      // Remove message queue for account verification process
      $q = "DELETE FROM `message_queue` WHERE `UserID` = '{$id}' AND `MessageID` = '1'";
      mysqli_query($dbconn, $q);


      return true;
    }
    else
    {
      return false;
    }
  }

  /**
   * Wipe out the old validation code.
   * Only use this if validated successfully...
   *
   * @param string $username
   * @return int
   */
  function clear_authcode($username)
  {
    global $dbconn;

    $username = trim($username);

    $AuthCode = md5((string) mt_rand());
    // Reset old authcode to nothing
    $q = "UPDATE `user_signup` SET `authCode` = ''  WHERE `useremail` = '{$username}' LIMIT 1";
    mysqli_query($dbconn, $q);

    return $AuthCode;
  }

  /**
   * Wipe out the old validation code.
   * Only use this if validated successfully... based on user AutoID
   *
   * @param string $username
   * @return int
   */
  function clear_authcode2($id)
  {
    global $dbconn;

    $id = trim($id);

    $AuthCode = md5((string) mt_rand());
    // Reset old authcode to nothing
    $q = "UPDATE `user_signup` SET `authCode` = ''  WHERE `AutoID` = '{$id}' LIMIT 1";
    mysqli_query($dbconn, $q);

    return $AuthCode;
  }

  /**
   * Change user password to something else, given by $user_id.
   *
   * @param int $user_id
   * @param string $new_password
   */
  public static function changeUserPassword($username, $new_password)
  {
    global $Conf;

    $tA = $Conf["data"]->UserTable;
    myDBC::new_andWhere();
    myDBC::andWhere("`useremail` = '$username'");
    $fields = array("`passwd`" => "'" . sha1($new_password) . "'");
    myDBC::update_Record($tA, $fields, myDBC::get_andWhere(), " LIMIT 1");
  }

  /**
   * Set a new status message entry and store it into
   * the user's session status log.
   *
   * @param string $msg
   * @param string $type
   * @param string $desc
   * @param mixed $vars
   */
  function set_LastStatus($msg, $type, $desc, $vars)
  {
    $this->StatusLogMsg[$this->StatusLogLoc] = $msg;
    $this->StatusLogType[$this->StatusLogLoc] = $type;
    $this->StatusLogDesc[$this->StatusLogLoc] = $desc;
    $this->StatusLogVars[$this->StatusLogLoc] = $vars;
  }

  /**
   * Validate the user against the Registered User table.
   * If the Override flag is set, password doesn't matter, you win.
   *
   * @param boolean $Override
   * @return boolean
   */
  public function validate_login2($Override = false)
  {
    global $Conf, $dbconn;

    // If either pass or user are empty, don't even bother validating...
    if (empty($this->username) || empty($this->password))
    {
      $this->validated = false;
      return false;
    }


    $q = "SELECT * FROM `" . $Conf["data"]->UserTable . "` WHERE useremail='" . $this->username . "' LIMIT 1";
    $result = mysqli_query($dbconn, $q);
    $r = mysqli_fetch_object($result);

    if (($this->password == $r->passwd) || $Override)
    {
      # login success
      Security::auditLoginSuccess($this->username); // (Username)
      $this->ID = $r->AutoID;
      $this->validated = true;
      $this->guest = false;
      # SET VARIABLES User and Business information
      $this->CartID = $r->CartID;
      $this->FirstName = $r->userFirstname;
      $this->LastName = $r->userLastname;
      $this->AltPhone = $r->userAltPhone; // User Phone Number

      $this->Company = $r->userCompany;
      $this->Phone = $r->userPhone; // Business Phone Number
      return $this->validated;
    }
    else
    {
      # login failed
      Security::auditLoginFailure($this->username); // (Username)
      $this->validated = false;
      $this->guest = true;
      return $this->validated;
    }
  }

  /**
   * Validate username and password combination against the database to
   * check if the login username and password are valid. If the company
   * this username is in is not authorized or enabled, the user
   * authentication will fail.
   * Passing $cryptocmp will compare memory crypts and db crypts.
   * Passing $revalidate will not cause the last login to be touched.
   *
   * @param boolean $cryptocmp
   * @param boolean $revalidate
   * @return boolean
   */
  public function validate_login($cryptocmp = false, $revalidate = false)
  {
    global $Conf, $User, $dbconn;

    // If either pass or user are empty, don't even bother validating...
    if (empty($this->username) || empty($this->password))
    {
      if (!empty($this->username))
      {
        Security::auditLoginFailure($this->username); // (Username)
      }
      $this->validated = false;
      return false;
    }

    // Grab details about the user attempting to be validated
    $tA = $Conf["data"]->UserTable;
    myDBC::new_andWhere();
    myDBC::andWhere("`useremail`='{$this->username}'");
    myDBC::andWhere("(FIND_IN_SET('member', `siteAreas`) > 0 OR FIND_IN_SET('admin', `siteAreas`) > 0)");
    $q = "SELECT * FROM $tA" . myDBC::get_andWhere() . " LIMIT 1";

    $rs = mysqli_query($dbconn, $q);
    $r = mysqli_fetch_object($rs);

    if ($r->authcode != "" || !empty($r->authcode))
    {
      $this->validated = false;
      $User->error[203] = true;
      $User->error[0] = true;
    }
    elseif ($r->is_enabled != 'yes')
    {
      $this->validated = false;
      $User->error[203] = true;
      $User->error[0] = true;
    }
    // Compare using a cryptomatch (do crypts match each other exact?)
    elseif ($cryptocmp == true)
    {
      if (strcmp($this->password, $r->passwd) == 0)
      {
        $this->loadUserInformation($r->AutoID);
        if ($revalidate == false)
        {
          //self::touchLoginTime($this->ID);
          Security::auditLoginSuccess($this->username); // (Username)
        }
        $this->validated = true;
      }
      else
      {
        // There really isn't a need to log an attempt with no username or password. So it has been commited out.
        //Security::auditLoginFailure($this->useremail);
        $this->validated = false;
        $User->error[203] = true;
        $User->error[0] = true;
      }
    }
    // Standard login by crypting plaintext to match against crypted text.
    elseif (sha1($this->password) == $r->passwd)
    {
      $this->loadUserInformation($r->AutoID);
      if ($revalidate == false)
      {
        //self::touchLoginTime($this->ID);
        Security::auditLoginSuccess($this->username); // (Username)
      }
      $this->validated = true;
    }
    // If all checks have failed, our login has failed as well and audit a failure...
    else
    {
      Security::auditLoginFailure($this->username); // (Username)
      $this->validated = false;
      $User->error[203] = true;
      $User->error[0] = true;
      //$_SESSION['log_error0']=1;
    }

    // Audit login failure...
    //if ($this->validated==false)
    //	Security::auditLoginFailure($this->username); // (Username)
    // Return if we are validated or not...
    return $this->validated;
  }

  /**
   * Load information about this user from the database and in to
   * their log-in / current instance.
   *
   * @param int $user_id
   * @return boolean
   */
  public function loadUserInformation($user_id)
  {
    global $Conf, $dbconn;

    $tA = $Conf["data"]->UserTable;

    $q = "SELECT * FROM `{$tA}` WHERE AutoID='{$user_id}' LIMIT 1";
    $r = mysqli_fetch_object(mysqli_query($dbconn, $q));

    // Grab all the updated information and store it in a session array this way information can be pulled if a form is not completed correctly.

    if (is_object($r))
    {
      $this->ID = $r->AutoID;
      $this->username = $r->useremail;
      $this->password = $r->passwd;
      $this->authcode = $r->authcode;

      $this->email = $r->useremail;
      $this->FirstName = $r->userFirstname;
      $this->LastName = $r->userLastname;

      // Personal Address Information
      $this->SHIPAddress1 = $r->shipAddr1;
      $this->SHIPAddress2 = $r->shipAddr2;
      $this->SHIPCity = $r->shipCity;
      $this->SHIPState = $r->shipState;
      $this->SHIPZip = $r->shipZip;

      // Business Address Information
      $this->Phone = $r->userPhone; // Business Phone Number
      $this->Address1 = $r->userAddr1;
      $this->Address2 = $r->userAddr2;
      $this->City = $r->userCity;
      $this->State = $r->userState;
      $this->Country = $r->userCountry;
      $this->ZIP = $r->userZip;

      // Business Profile Information
      $this->AltPhone = $r->userAltPhone; // Business Phone Number
      $this->Web = $r->web;
      $this->Company = $r->userCompany;
      $this->profileBio = $r->userBio;

      $this->isCryptedPW = true;
      $this->level = $r->level; // User access level
      $this->siteAreas = @explode(',', $r->siteAreas);
      $this->is_enabled = ($r->is_enabled == 'yes') ? true : false;
    }
    return true;
  }

  /**
   * Save Information about user to database.
   *
   * @return unknown
   */
  public function saveUserInformation()
  {
    global $Conf;

    $user_id = $this->ID;

    $r = mysqli_result;
    $tA = $Conf["data"]->UserTable;
    myDBC::new_andWhere();
    myDBC::andWhere("$tA.AutoID = '$user_id'");
    $fields = array(
        "`useremail`" => "'{$this->username}'",
        "`userfirstname`" => "'{$this->Firstname}'",
        "`userlastname`" => "'{$this->Lastname}'",
        "`phone_number`" => "'{$this->PhoneNumber}'",
    );
    // Are we entering a new crypt for a plaintext password?
    if ($this->isCryptedPW == false)
    {
      $fields["`password`"] = "'" . sha1($this->password) . "'";
    }

    // Check to see if the record exists.
    // If it does, simply update otherwise insert new.
    $q = "SELECT * FROM $tA " . myDBC::get_andWhere() . " LIMIT 1";
    myDBC::stdQuery($q, $r);

    if ($r->num_rows == 1)
    {
      myDBC::update_Record($tA, $fields, myDBC::get_andWhere(), " LIMIT 1");
    }
    else
    {
      myDBC::insert_Record($tA, $fields);
    }

    return true;
  }

  /**
   * Permanently remove a user from the database.
   *
   * @return boolean
   */
//	public function deleteUser() {
//		global $Conf;
//
//		$user_id = $this->ID;
//
//		$tA = $Conf["data"]->UserTable;
//		myDBC::new_andWhere();
//		myDBC::andWhere("$tA.AutoID = '$user_id'");
//		myDBC::delete_Record($tA, myDBC::get_andWhere(), "LIMIT 1");
//		return true;
//	}

  /**
   * Returns all information about a user looked up by a Username given.
   *
   * @param string $username
   * @return object
   */
  public static function getUserByUsername($username)
  {
    global $Conf;

    $username = $GLOBALS['database']->real_escape_string($username);
    $r = mysqli_result;
    $tA = $Conf["data"]->UserTable;
    myDBC::new_andWhere();
    myDBC::andWhere("`useremail` LIKE '$username'");
    $q = "SELECT * FROM $tA" . myDBC::get_andWhere() . " LIMIT 1";
    myDBC::stdQuery($q, $r);
    if ($r->num_rows == 1)
    {
      return $r->fetch_object();
    }
    return NULL;
  }

  /**
   * Update the login time when a user is validated. - DEPRECIATED
   *
   * @param int $user_id
   */
  private static function touchLoginTime($user_id)
  {
    global $Conf;

    $tA = $Conf["data"]->UserTable;
    myDBC::new_andWhere();
    myDBC::andWhere("`AutoID` = '$user_id'");
    $fields = array("`last_login`" => "NOW()");
    myDBC::update_Record($tA, $fields, myDBC::get_andWhere(), " LIMIT 1");
  }

  /**
   * Are we a validated user?
   *
   * @return boolean
   */
  public function isValidated()
  {
    if ($this->validated)
      return true;
    else
      return false;
  }

  /**
   * Are we an administrative user?
   *
   * @return boolean
   */
  public function isAdmin()
  {
    if ($this->admin)
      return true;
    else
      return false;
  }

  public static function userExists($user_id)
  {
    global $Conf;

    $r = mysqli_result;
    $tA = $Conf["data"]->UserTable;
    myDBC::new_andWhere();
    myDBC::andWhere("`AutoID` = '$user_id'");
    myDBC::andWhere("FIND_IN_SET('member',`siteAreas`) > 0");
    $q = "SELECT `AutoID` FROM `$tA` " . myDBC::get_andWhere() . " LIMIT 1";
    myDBC::stdQuery($q, $r);

    if ($r->num_rows == 1)
    {
      $r = $r->fetch_object();
      return $r->AutoID;
    }
    else
      return false;
  }

  /**
   * Load all users as an array of users represented as objects.
   *
   * @param int $company_id
   * @param boolean $dormant
   * @return array
   */
  public static function getAllUsers()
  {
    global $Conf;

    $r = mysqli_result;
    $users = array();
    $tA = $Conf["data"]->UserTable;
    myDBC::new_andWhere();
    $q = "SELECT * FROM $tA" . myDBC::get_andWhere();
    myDBC::stdQuery($q, $r);
    while ($r = $r->fetch_object())
      $users[] = $r;
    return $users;
  }

  /**
   * Get all available provinces that we can Ship to.
   *
   * @return array
   */
  public function user_provinces()
  {
    global $Conf, $dbconn;

    $rows = Array();
    $tA = $Conf["data"]->ProvinceTable;
    $q = "SELECT autoid,name FROM {$tA} ORDER BY `provinces`.`autoid` ASC, `provinces`.`id` ASC";
    $r = mysqli_query($dbconn, $q);
    while ($row = mysqli_fetch_object($r))
      $rows[] = $row;
    return $rows;
  }

  /**
   * Get all available countries that we can Ship to.
   *
   * @return array
   */
  public function user_countries()
  {
    global $Conf, $dbconn;
    myDBC::new_andWhere();
    myDBC::andWhere("`show` = 'yes'");
    $rows = Array();
    $tA = $Conf["data"]->CountryTable;
    $q = "SELECT * FROM {$tA} " . myDBC::get_andWhere() . " ORDER BY country ASC";
    $r = mysqli_query($dbconn, $q);
    while ($row = mysqli_fetch_object($r))
      $rows[] = $row;
    return $rows;
  }

  /**
   * Get information about a Province or State using the state symbol reference
   *
   * @param int $province_id
   * @return object
   */
  public function get_province_by_name($province_name)
  {
    global $Conf, $dbconn;
    if (!is_numeric($province_name))
    {
      myDBC::new_andWhere();
      myDBC::andWhere("`symbol` = '$province_name'");
      $tA = $Conf["data"]->ProvinceTable;
      $q = "SELECT autoid FROM {$tA}" . myDBC::get_andWhere() . " LIMIT 1";
      echo $q;
      $r = mysqli_query($dbconn, $q);
      $row = mysqli_fetch_object($r);
      return $row;
    }
    else
    {
      return false;
    }
  }

  /**
   * Get information about a Province or State using the numeric ID
   *
   * @param int $province_id
   * @return object
   */
  public function get_province_by_id($province_id)
  {
    global $Conf, $dbconn;
    if (is_numeric($province_id))
    {
      myDBC::new_andWhere();
      myDBC::andWhere("`autoid` = $province_id");
      $tA = $Conf["data"]->ProvinceTable;
      $q = "SELECT name,symbol FROM {$tA}" . myDBC::get_andWhere() . " LIMIT 1";
      $r = mysqli_query($dbconn, $q);
      $row = mysqli_fetch_object($r);
      return $row;
    }
    else
    {
      return false;
    }
  }

  /**
   * Get information about a Country.
   *
   * @param int $country_id
   * @return object
   */
  public function get_country_by_id($country_id)
  {
    global $Conf, $dbconn;
    if (is_numeric($country_id))
    {
      myDBC::new_andWhere();
      myDBC::andWhere("`autoid` = $country_id");
      $tA = $Conf["data"]->CountryTable;
      $q = "SELECT country,symbol FROM {$tA}" . myDBC::get_andWhere() . " LIMIT 1";
      $r = mysqli_query($dbconn, $q);
      $row = mysqli_fetch_object($r);
      return $row;
    }
    else
    {
      return false;
    }
  }

  /**
   * Retrieve all information about a specific item.
   *
   * @param int $model_id
   * @return array
   */
  public function user_comments($id)
  {
    global $Conf, $dbconn;

    myDBC::new_andWhere();
    myDBC::andWhere("`AutoID` = '$id';");
    $tA = $Conf["data"]->UserTable;
    $q = "SELECT userfirstname, userlastname, comments FROM {$tA}" . myDBC::get_andWhere();
    $r = mysqli_query($dbconn, $q);
    while ($row = mysqli_fetch_object($r))
      $rows[] = $row;
    return $rows;
  }

}

?>