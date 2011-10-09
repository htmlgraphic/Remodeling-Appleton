<?php
// Custom functions

$day_short = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun", "Cancelled");
$day_long = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday", "Cancelled");
$month_short = array(null, 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
$alpha_list = array("#", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

# SHARED WORDPRESS FUNCTIONS
// These functions should match functions created within the WordPress system. The logic
// of the system is starting to reach limitations so this functions file should continue to grow
// If you edit any functions make sure to update them in the WordPress functions file.
// Default heading if user is viewing general pages

function header_user_status($banner=false)
{
  global $User;
  echo '<div id="header">';
  if (is_object($User) && $User->isValidated())
  {
    // If the user has a first name display it in the header
    $greeting = ($User->FirstName) ? 'Hello ' . $User->FirstName . '!' : 'Hello! ';
    echo '<div id="login"><p>' . $greeting . ' </p><a href="/account/logout.php?logout=true">Sign out</a></div>';
  }
  else
  {
    echo '<div id="login"><p>Already a member? </p> <a href="/login/">Log In</a> <a href="/join/" class="h">Join</a></div>';
  }
  echo '<div id="branding">';
  echo '<a href="https://www.passinggreen.com/" title="Passing Green Referral Network" rel="home"><img src="/wp-content/themes/passinggreen/images/logo.png" alt="Passing Green Referral Network" /></a></div>';
//  if ($banner == 'elite_network')
//  {
//    echo '<div id="branding_text">"Elite network of recommended businesses"</div>';
//  }
  echo '<div class="clear"></div>';
  echo '</div><!-- #header -->';
}

// Account profile heading
function header_account_status()
{
  global $User;
  echo '<div id="header">';
  echo '<div id="login">';
  echo '<a href="/account/logout.php?logout=true" class="logout">Sign out</a> <a href="/settings/">Settings</a>';

//  if (profile_level($User->ID) >= 2)
//  {
//    echo '<p><a href="/account/profile/">Profile</a></p>';
//  }

  if (is_object($User) && $User->isValidated())
  {
    // If the user has a first name display it in the header
    if (($User->City) && ($User->State) && ($User->FirstName))
    {
      $state = $User->get_province_by_id($User->State);
      $state = $state->symbol;

      $greeting = $User->FirstName . " in " . ucwords($User->City) . ", " . $state;

      echo '<p>' . $greeting . ' </p>';
    }
    else
    {
      $greeting = ($User->FirstName) ? 'Hello ' . $User->FirstName . '!' : 'Hello! ';
      echo '<p>' . ucwords($greeting) . ' </p>';
    }
  }
  echo '</div>';

  echo '<div id="branding">';
  echo '<a href="https://www.passinggreen.com/" title="Passing Green Referral Network" rel="home"><img src="/wp-content/themes/passinggreen/images/logo.png" alt="Passing Green Referral Network" /></a>';
  echo '</div>';
  echo '<div class="clear"></div>';
  echo '</div><!-- #header -->';
}

// Navigation while logged in to account
function account_navigation($page=false)
{
  global $User;
  ?>
  <div id="nav">
    <div>
      <ul>
        <li><a href="/account/guide/"<?= currentPage('guide', $page); ?>>Get Started</a></li>
        <?php if ((user_referrals($User->ID) > 0) || (member_referrals($User->ID) > 0))
        { ?>
          <li><a href="/account/activity/"<?= currentPage('activity', $page); ?>>My Referrals</a></li>
        <?php } ?>
        <li><a href="/referral/search/"<?= currentPage('search', $page); ?>>Pass Referral</a></li>
        <li><a href="/friends/"<?= currentPage('friends', $page); ?>>Invite</a></li>
      </ul>
      <form action="/referral/search/" method="POST" id="searchbox">
        <?php
        if ($_GET['s'])
        {
          $sq = 'value="' . $_GET['s'] . '"';
        }
        else
        {
          $sq = 'placeholder="Search places..." value="Search places..." onfocus="this.value=\'\'"';
        }
        ?>
        <input type="search" results="5" autosave="unique" name="s" size="25" <?php echo $sq; ?> id="searchEntry" />
        <button> Search </button>
      </form>
    </div>
  </div>
  <?php
}

// Check if the user is logged in. If they are not they should be redirected to the login page.
function check_authenticated($skip_redirect = false)
{
  global $User;
  $revalidateUserWithCookie = false;
  // check to see if a cookie is set for persist login
  if (isset($_COOKIE['save_login_user']) && isset($_COOKIE['save_login_hash']))
  {
    $revalidateUserWithCookie = true;
  }
  if ($revalidateUserWithCookie)
  {
    if (isset($_COOKIE['save_login_user']) && isset($_COOKIE['save_login_hash']))
    {
      $User->username = $_COOKIE['save_login_user'];
      $User->password = $_COOKIE['save_login_hash'];
      if ($User->validate_login2())
      {
        $User->loadUserInformation($User->ID);
      }
    }
  }
  if ($User->validated != 1)
  {
    if ($skip_redirect)
    {
      return false;
    }
    else
    {
      header('Location: https://www.passinggreen.com/login/?error=timeout');
      exit();
    }
  }
  return true;
}

function load_HG_forms()
{

  // PEAR is a framework and distribution system for reusable PHP components.
  // More info: http://pear.php.net
  // HTML_QuickForm Includes
  require_once 'HTML/QuickForm.php';
  require_once 'HTML/QuickForm/Renderer/Tableless.php';
  // HTML_QuickForm Includes
  require_once 'HTML/QuickForm2.php';
  require_once 'HTML/QuickForm2/Renderer.php';
}

# SHARED WORDPRESS FUNCTIONS - END
// Highlight the navigation when a specfic page is viewed.

function currentPage($url = false, $page = null)
{
  if ($url == $page)
  {
    $on = ' class="selected"';
    return $on;
  }
}

function initiate_get_satisfaction()
{
  ?>
  <script>
    var is_ssl = ("https:" == document.location.protocol);
    var asset_host = is_ssl ? "https://s3.amazonaws.com/getsatisfaction.com/" : "http://s3.amazonaws.com/getsatisfaction.com/";
    document.write(unescape("%3Cscript src='" + asset_host + "javascripts/feedback-v2.js' type='text/javascript'%3E%3C/script%3E"));
  </script>
  <script>
    var feedback_widget_options = {};
    feedback_widget_options.display = "overlay";
    feedback_widget_options.company = "passinggreen";
    feedback_widget_options.placement = "right";
    feedback_widget_options.color = "#222";
    feedback_widget_options.style = "idea";
    var feedback_widget = new GSFN.feedback_widget(feedback_widget_options);
  </script>
  <?php
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = true, $atts = array())
{
  $url = 'https://secure.gravatar.com/avatar/';
  $url .= md5(strtolower(trim($email)));
  $url .= "?s=$s&d=$d&r=$r";
  if ($img)
  {
    $url = '<img src="' . $url . '"';
    foreach ($atts as $key => $val)
      $url .= ' ' . $key . '="' . $val . '"';
    $url .= ' />';
  }
  return $url;
}

/**
 * Check if there is a name in the email so it will be correctly encoded when sending.
 * Name wrapped in quotes helps to stop the boggy man.
 */
function quote_email($name, $email)
{
  if ($name <> '' && $name != ' ')
  { // Don't let the space fool you.
    return '"' . ($name) . '" <' . $email . '>';
  }
  else
  {
    return $email;
  }
}

/**
 * Check if there is a name in the email so it will be correctly encoded when sending.
 * Name wrapped in quotes helps to stop the boggy man.
 */
function quote_email_arr($name, $email)
{
  if ($name <> '' && $name != ' ')
  { // Don't let the space fool you.
    return array($email => $name);
  }
  else
  {
    return array($email);
  }
}

/**
 * Count the number of notes given on a referral
 *
 */
function note_count($id)
{
  global $dbconn;
  $q = "SELECT count(`referralID`) as noteCount
		FROM `referralNotes`
		WHERE `ReferralID` = '$id'";

  $r = mysqli_query($dbconn, $q);
  $result = mysqli_fetch_object($r);
  return $result->noteCount;
}

/**
 * Count the number of notes given on a referral
 *
 */
function check_browser($browser, $version=false)
{

  if ($browser == 'msg')
  {
    echo '<div id="notice" class="message"><p>Oops, your browser is a little too old and not supported. <br />Please upgrade using one of the links below.</p><p><a href="http://www.mozilla.com/en-US/firefox/">Firefox</a>, <a href="http://www.google.com/chrome/">Google</a>, <a href="http://www.microsoft.com/windows/internet-explorer/">Internet Explorer</a>, <a href="http://www.apple.com/safari/">Safari</a></p></div>';
  }
  else
  {

    if (($browser == 'IE') && ($version <= '7'))
    {
      return true;
    }
  }

  return false;
}

/**
 * Grab information form the audit table based on email address
 *
 */
function audit_log($email)
{
  global $dbconn;

  if (!preg_match(EMAIL_FORMAT, $email) || empty($email))
  {

    return false;
  }
  else
  {

    $q = "SELECT * FROM audit
				WHERE username = '{$email}' ORDER BY id DESC LIMIT 1";
    $r = mysqli_fetch_object(mysqli_query($dbconn, $q));

    return $r;
  }
}

/* Remove a Query String Key=>Value */

function remove_querystring_var($url, $key)
{
  return preg_replace('/(?:&|(\?))' . $key . '=[^&]*(?(1)&|)?/i', "$1", $url);
}

/**
 * Return all the referrals a business has listed under their profile
 *
 */
function member_referrals($id=false, $type=false)
{
  global $dbconn, $User;

  if ($id)
  {

    // Count ALL referrals for a specific vendor
    $q = "SELECT count(`referrals`.`AutoID`) as referrals
			  FROM `referrals`
			  WHERE VendorID = '$id'";

    if ($type)
    {
      // Count ALL referrals for a specific vendor with a specific type
      $q = "SELECT count(`referrals`.`AutoID`) as referrals
				  FROM `referrals`
				  WHERE VendorID = '$id' AND `referrals`.`status` = '{$type}'";
    }
  }
  else
  {

    $q = "SELECT count(`referrals`.`AutoID`) as referrals
			  FROM `referrals`
			  WHERE `referrals`.`status` = '{$type}'";
  }

  $result = mysqli_fetch_object(mysqli_query($dbconn, $q));


  if (!$result->referrals)
  {
    $count = 0; // If a user does not have any referrals under their account return zero
  }
  else
  {
    $count = $result->referrals;
  }

  return $count; // return the total amount of referrals
}

/**
 * Check if all the initial steps have been completed. If they have then this function is used
 * to skip the guided new user process
 *
 */
function guided_process()
{
  global $dbconn, $User;

  if ((!$User->Phone) || (!$User->FirstName) || (!$User->LastName))
  {
    // Check if the user has their basic profile information
    return false;
  }

  if (user_referrals($User->ID) == 0)
  {
    // User has NOT passed a referral
    return false;
  }

  if (count_invite($User->ID) == 0)
  {
    // User has NOT passed a referral
    return false;
  }

  return true;
}

/**
 * Check if all the initial steps have been completed. If they have then this function is used
 * to skip the guided new user process
 *
 */
function profile_status($level)
{
  global $User;

  if ($level == "user")
  {
    if (($User->Phone) && ($User->FirstName) && ($User->LastName))
    {
      return true; // Users profile is complete
    }
  }

  if ($level == "member")
  {
    if (($User->Phone) && ($User->FirstName) && ($User->LastName) && ($User->Company) && ($User->AltPhone) && ($User->profileBio))
    {
      return true; // Members profile is complete
    }
  }

  return false;
}

/**
 *  Count all the referrals a user has listed under their profile
 *
 */
function user_referrals($id, $type='all')
{
  global $dbconn, $User;

  if ($type == 'all')
  {
    $q = "SELECT count(`referrals`.`AutoID`) as referrals
			FROM `referrals`
			WHERE UserID = '$id' OR VendorID = '$id'";
  }
  else if ($type == 'pending')
  {
    // Count all the referral that are mark as pending
    $q = "SELECT count(`referrals`.`AutoID`) as referrals
			FROM `referrals`
			WHERE UserID = '$id' AND `referrals`.`status` =  'pending'";
  }
  else if ($type == 'completed')
  {
    // Count all the referral that are mark as completed
    $q = "SELECT count(`referrals`.`AutoID`) as referrals
			FROM `referrals`
			WHERE UserID = '$id' AND `referrals`.`status` =  'completed'";
  }
  else if ($type == 'passed')
  {
    // Count all the referral a user passed
    $q = "SELECT count(`referrals`.`AutoID`) as referrals
			FROM `referrals`
			WHERE UserID = '$id'";
  }

  $r = mysqli_query($dbconn, $q);
  $result = mysqli_fetch_object($r);

  if (!$result->referrals)
  {
    $count = 0; // If a user does not have any referrals under their account return zero
  }
  else
  {
    $count = $result->referrals;
  }

  return $count; // return the total amount of referrals
}

/**
 *  Count all the invites a user has sent out
 *
 */
function count_invite($id, $type='all')
{
  global $dbconn, $User;

  if ($type == 'all')
  {
    $q = "SELECT count(`invites`.`AutoID`) as invites
			FROM `invites`
			WHERE UserID = '$User->ID'";
  }

  $r = mysqli_query($dbconn, $q);
  $result = mysqli_fetch_object($r);

  if (!$result->invites)
  {
    $count = 0; // If a user does not have any invites under their account return zero
  }
  else
  {
    $count = $result->invites;
  }

  return $count; // return the total amount of invites from a user
}

/**
 * Database check for incomplete business profile. This function will return the count of all incomplete
 * member profiles and if a ID is passed it will indicate if it is complete.
 *
 */
function business_inactive($id=false)
{
  global $dbconn;

  // need to know if a single member has a complete profile
  if ($id)
  {
    $sql = " AND `user_signup`.`AutoID` = {$id}";
  }

  $q = "SELECT count(`AutoID`) as inactive
				FROM user_signup
				WHERE ((userCompany = '')
					OR (userAltPhone = '')
					OR (userBio = '')
					OR (userFirstname = '')
					OR (userLastname = '')) AND `level` = 'member' AND `is_enabled` = 'yes' {$sql}";

  $rs = mysqli_fetch_object(mysqli_query($dbconn, $q));

  return $rs->inactive;
}

/**
 * Create the javascript include file for typekit
 *
 */
function load_typekit()
{

  $id = 'dnv5dqk';

  if ($_SERVER['HTTPS'])
  {
    $protocal = 'https';
  }
  else
  {
    $protocal = 'http';
  }

  echo '<script type="text/javascript" src="' . $protocal . '://use.typekit.com/' . $id . '.js"></script>';
  echo "\r\n" . '<script type="text/javascript">try{Typekit.load();}catch(e){}</script>';
  echo "\r\n" . '<style type="text/css">';
  echo "\r\n" . 'h1 {visibility: hidden;}';
  echo "\r\n" . '.wf-active h1 {visibility: visible;}';
  echo "\r\n" . '</style>';
}

/**
 * Check to make sure user has correct access to when
 * viewing note information under a referral.
 *
 * Compare UserID tied to the referral notes to the
 * UserID in the active session viewing the notes.
 *
 */
function note_security($rid, $uid)
{
  global $dbconn;

  $q = "SELECT `referrals`.`UserID`, `referrals`.`VendorID`
				FROM `referrals`
				WHERE `AutoID` = '{$rid}'";

  $r = mysqli_query($dbconn, $q);
  while ($result = mysqli_fetch_object($r))
  {

    if ($result->UserID == $uid || $result->VendorID == $uid)
      return true;
  }
  return false;
}

/**
 * List all the members associated with a referral a little better format than note_security()
 *
 */
function referral_members($rid)
{
  global $dbconn;

  $q = "SELECT `referralNotes`.`UserID`, `referrals`.`VendorID`
				FROM `referralNotes`
				Inner Join `referrals` ON `referrals`.`AutoID` = `referralNotes`.`ReferralID`
				WHERE `ReferralID` = '{$rid}'";

  $r = mysqli_query($dbconn, $q);
  while ($result = mysqli_fetch_object($r))
  {
    $recipients[] = $result->UserID;
    $recipients[] = $result->VendorID;
  }

  $unique_recipients = array_unique($recipients);

  return array_merge(array(), $unique_recipients);
}

/**
 *  Check the format of a url entered.
 *
 */
function valid_url($url)
{

  $url_check = '/^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i';

  if (preg_match($url_check, $url))
  {
    // URL looks to be in the correct format
    $web_address['v'] = true;
    $web_address['a'] = $url;
  }
  else
  {
    // URL looks incorrect

    if (!preg_match('/^(http|https):\/\//', $url))
    {
      $url = 'http://' . $url; // return http:// to help the user if they don't know what it is. But only list one.
    }

    if (preg_match($url_check, $url))
    {
      // URL looks to be in the correct format
      $web_address['v'] = true;
      $web_address['a'] = $url;
    }
    else
    {
      // URL looks incorrect
      $web_address['v'] = false;
      $web_address['a'] = $url;
    }
  }
  return $web_address;
}

/**
 * Validate the dollar amount input
 *
 */
function validate_float($number)
{
  if (ereg('^[0-9]+\.[0-9]{2}$', $number))
    return true;
  else
    return false;
}

/**
 * Make the date look pretty
 *
 */
function friendlyDate($date)
{
  $timestamp = strtotime($date);
  return date("M j, g:iA", $timestamp);
}

/**
 * Explain the amount of days left in a very readable format
 *
 */
function daysRemaining($date)
{

  $difference = time() - $date;
  $periods = array("sec", "min", "hour", "day", "week", "month", "years", "decade");
  $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

  if ($difference > 0)
  { // this was in the past
    //$ending = "ago";
  }
  else
  { // this was in the future
    $difference = -$difference;
    //$ending = "to go";
  }
  for ($j = 0; $difference >= $lengths[$j]; $j++)
    $difference /= $lengths[$j];
  $difference = round($difference);
  if ($difference != 1)
    $periods[$j].= "s";
  //$text = "$difference $periods[$j] $ending";
  $text = "$difference $periods[$j]";

  return $text;
}

/**
 * Grab information to display on the search results
 *
 */
function fetchCompaniesAsJSON($q)
{
  global $dbconn, $User;
  $r = mysqli_query($dbconn, $q);
  $companies = array();
  $i = 1;
  while ($rs = $r->fetch_object())
  {
    $company = new stdClass();
    $company->id = $rs->AutoID;
    $company->userFirstname = $rs->userFirstname;
    $company->userLastname = $rs->userLastname;
    $company->userCompany = $rs->userCompany;
    $company->userBio = $rs->userBio;
    $company->userBio_short = short_excerpt($rs->userBio, $company->id); // bio, userID
    $company->passingAllowed = false;
    $profileCategories = business_category($rs->AutoID);
    // Grab the categories the company falls under
    foreach ($profileCategories as $profileCategory)
    {
      if ($profileCategory != 0)
        $company->profileCategories[] = business_category_list($profileCategory);
    }
    if (($User->FirstName != '') && ($User->LastName != '') && ($User->Phone != ''))
    {
      if ($User->ID != $company->id) // don't display the option to pass a referral if the user of the company is logged in.
      {
        $company->passingAllowed = true;
      }
    }
    $companies[$i] = $company; // Google and IE order the list by the array key, id is based on the order in which it is returned.
    $i++;
  }
  return json_encode($companies);
}

/**
 * Customize the excerpt content from a post on the archieve of search pages.
 *
 *
 * @since HTMLgraphic
 */
// Usauge: short_excerpt($content, get_the_ID());

function short_excerpt($content, $id)
{

  $length = 8;


  if ($length >= count(preg_split('/[\s]+/', strip_tags($content))))
  {
    // Skip if text is already within limit
  }
  else
  {
    // Split on whitespace and start counting (for real)
    $words = explode(' ', $content, $length + 1);
    if (count($words) > $excerpt_length) :
      array_pop($words);
      array_push($words, '[+]');
      $content = '<span class="dots">' . implode(' ', $words) . '</span>';
    endif;
  }

  return $content;
}

/**
 * Query for the business category listing.
 *
 */
function business_category_list($id=false)
{
  global $dbconn;

  if ($id)
    $grab = " WHERE `AutoID` = '{$id}'";

  $q = "SELECT *
			FROM `business_categories`
			{$grab}
			ORDER BY `business_categories`.`category` ASC";

  $result = mysqli_query($dbconn, $q);
  if ($id)
  {
    // Return the specific category
    $r = mysqli_fetch_object($result);
    return $r->category;
  }
  else
  {
    // Return an array list for business profiles
    $category[0] = '--';
    while ($r = mysqli_fetch_object($result))
    {
      $category[$r->AutoID] = $r->category;
    }
    return $category;
  }
}

/**
 * Query for the business category listing.
 *
 */
function business_category($id=false)
{
  global $dbconn;

  if ($id)
    $grab = " WHERE `uID` = {$id}";

  $q = "SELECT `business_category_index`.`AutoID`, `business_category_index`.`level`, `business_category_index`.`cID`, `business_categories`.`category`
		FROM `business_category_index`
			Inner Join `business_categories` ON `business_category_index`.`cID` = `business_categories`.`AutoID`
			{$grab}
		ORDER BY `business_category_index`.`AutoID` DESC LIMIT 2";

  $result = mysqli_query($dbconn, $q);
  if ($id)
  {
    // Return the specific category
    while ($r = mysqli_fetch_object($result))
    {
      $categories[$r->level] = $r->cID;
    }

    return $categories;
  }
  else
  {
    return false;
  }
}

/**
 * Count the amount of users in the system OR return an array
 *
 */
function total_accounts($loop=false, $active='yes', $level=false, $start_date=false, $end_date=false)
{
  global $dbconn;

  if (($start_date) && ($end_date))
    $date = "AND `user_signup`.`date_added` >= '{$start_date}'  AND `user_signup`.`date_modified` <= '{$end_date}'";

  if ($loop)
  {

    if ($active)
      $show = "`is_enabled` = '{$active}'";

    if ($level)
    {
      $level = "AND `level` = {$level}'"; // Empty out $level unless one is given
    }
    else
    {
      $level = "AND `level` != 'superadmin' AND `level` != 'admin'"; // Empty out $level unless one is given
    }


    $q = "SELECT *
					  FROM user_signup
					  WHERE {$show} {$level} {$date}";

    // Build an array of results to query the database for additional information
    $result = mysqli_query($dbconn, $q);
    while ($r = mysqli_fetch_object($result))
    {
      $users[$r->AutoID] = array('id' => $r->AutoID);
    }

    return $users;
  }
  else
  {
    // count the total users and members
    if ($active)
      $show = "`is_enabled` = '{$active}' AND";

    $q = "SELECT COUNT(*) AS cnt FROM `user_signup` WHERE {$show} `level` != 'superadmin' AND `level` != 'admin' {$date}";

    return mysqli_fetch_object(mysqli_query($dbconn, $q))->cnt;
  }
}

/**
 * Query for a list of active members whom have their profile completely filled out.
 * Return array id, companyName
 */
function member_list($active=false, $start_date=false, $end_date=false)
{
  global $dbconn;

  if ($active)
    $show = "AND `is_enabled` = '{$active}'";

  if (($start_date) && ($end_date))
    $date = "AND `user_signup`.`date_added` >= '{$start_date}'  AND `user_signup`.`date_modified` <= '{$end_date}'";


  $q = "SELECT *
			FROM user_signup
			WHERE level = 'member' {$show} {$date} ORDER BY userCompany";

  $result = mysqli_query($dbconn, $q);
  while ($r = mysqli_fetch_object($result))
  {
    $vendors[$r->AutoID] = array('companyName' => $r->userCompany, 'id' => $r->AutoID);
  }

  return $vendors;
}

/**
 * Query for a list of active users whom have their profile completely filled out.
 * Return array id, companyName
 */
function user_list($active=false, $start_date=false, $end_date=false)
{
  global $dbconn;

  if ($active)
    $show = "AND `is_enabled` = '{$active}'";

  if (($start_date) && ($end_date))
    $date = "AND `user_signup`.`date_added` >= '{$start_date}'  AND `user_signup`.`date_modified` <= '{$end_date}'";


  $q = "SELECT *
  			FROM user_signup
			WHERE level = 'user' {$show} {$date}";

  $result = mysqli_query($dbconn, $q);
  while ($r = mysqli_fetch_object($result))
  {
    $users[$r->AutoID] = array('id' => $r->AutoID);
  }

  return $users;
}

/**
 * When a business updates their category information the older categories should be removed.
 * This script will run through the "business_category_index" table and remove categories that
 * already have two set.
 *
 */
function clean_category_index($id)
{
  global $dbconn;

  $q = "SELECT * FROM `business_category_index` WHERE `business_category_index`.`uID` =  '{$id}' ORDER BY AutoID DESC";
  $r = mysqli_query($dbconn, $q);

  $i = 0;
  while ($rs = mysqli_fetch_object($r))
  {
    $i++;
    // echo $rs->AutoID ."\n"; // ID if category index

    if ($i > 2)
    {
      //echo 'del '. $m['id'] .' '. $rs->AutoID ."\n";
      mysqli_query($dbconn, "DELETE FROM `business_category_index` WHERE `AutoID` = '{$rs->AutoID}';");
      $cnt = mysqli_affected_rows($dbconn);

      if ($cnt > 0)
        echo 'Removed ' . $rs->AutoID . "\n";
      else
        echo 'Error while removing ' . $id . "\n";
    }
  }
}

/**
 * Get notes or status on a specific referrals, careful using this a note must exist to join correctly.
 *
 */
function referral_notes($id, $loop=false)
{
  global $dbconn;

  $q = "SELECT `referrals`.`AutoID` AS `AID`,
		  `referrals`.`UserID`,
		  `referrals`.`VendorID`,
		  `referrals`.`status`,
		  `referrals`.`dateAdded`,
		  `referrals`.`dateCompleted`,
		  `referrals`.`saleAmount`,
		  `referrals`.`referralData`,
		  `referrals`.`need`,
		  `referralNotes`.`AutoID`,
		  `referralNotes`.`UserID` AS `UID`,
		  `referralNotes`.`ReferralID`,
		  `referralNotes`.`notes`,
		  `referralNotes`.`dateAdded`,
		  `referralNotes`.`dispute`,
		  `user_signup`.`userCompany`,
		  `user_signup`.`userLastname`,
		  `user_signup`.`userFirstname`,
		  `user_signup`.`useremail`,
		  `user_signup`.`userPhone`,
		  `user_signup`.`userAltPhone`
		  FROM
		  `referrals`
		  Inner Join `referralNotes` ON `referrals`.`AutoID` = `referralNotes`.`ReferralID`
		  Inner Join `user_signup` ON `referrals`.`VendorID` = `user_signup`.`AutoID`
		  WHERE
		  `referrals`.`AutoID` =  '{$id}'
		  ORDER BY
		  `referralNotes`.`AutoID` ASC";

  if ($loop)
  {
    // A loop is needed so just return the query
    return $q;
  }
  else
  {
    //
    $r = mysqli_query($dbconn, $q);
    $result = mysqli_fetch_object($r);
    return $result;
  }
}

/**
 *
 * Find the amount of referrals based on the type between the range of days return an array or the size of the array (count)
 *
 */
function referral_range($begin=false, $end=false, $template=false, $type='pending')
{
  global $dbconn;

  if (($begin) || ($end))
  {

    $q = "SELECT referrals.AutoID, referrals.VendorID
				  FROM referrals
				  WHERE status = '{$type}'
				  AND dateAdded > DATE_SUB(NOW(), INTERVAL {$end} DAY) AND dateAdded < DATE_SUB(NOW(), INTERVAL {$begin} DAY)";

    $r = mysqli_query($dbconn, $q);
    while ($rs = mysqli_fetch_object($r))
    {
      $pending_referrals[] = array('rid' => $rs->AutoID, 'vid' => $rs->VendorID, 'template' => $template);
    }
  }

  // If a template is passed an array will be constructed
  if ($template)
  {
    return $pending_referrals;
  }
  else
  {
    return sizeof($pending_referrals);
  }
}

/**
 * Takes the list of contacts and
 * sends a text to each contact
 */
function sms_notification($contacts)
{
  // include the PHP TwilioRest library
  require "twilio.php";

  // twilio REST API version
  $ApiVersion = "2010-04-01";

  // Set Account Info
  $AccountSid = "ACf63d3cb5902475595edc2779af263041";
  $AuthToken = "952d9c152078c41ca5ccfd78845e02bf";

  // instantiate a new Twilio Rest Client
  $client = new TwilioRestClient($AccountSid, $AuthToken);

  // Send Each Contact a Text Message
  foreach ($contacts as $contact)
  {
    // Send a new outgoinging SMS by POSTing to the SMS resource */
    $response = $client->request("/$ApiVersion/Accounts/$AccountSid/SMS/Messages", "POST", array(
                "To" => $contact['num'],
                "From" => "323-419-0097",
                "Body" => $contact['body']
            ));

    if ($response->IsError)
    {
      return "Error: {$response->ErrorMessage}";
    }
    else
    {
      return "Sent message: " . $contact['body'] . "";
    }
  }
}

/**
 * Get the company / vendor information
 *
 */
function profile_details($id)
{
  global $dbconn;

  $q = "SELECT * FROM `user_signup`
		WHERE `user_signup`.`AutoID` = '{$id}' AND is_enabled='yes'";

  $r = mysqli_fetch_object(mysqli_query($dbconn, $q));

  return $r;
}

/**
 * Get the referral detail information
 *
 */
function referral_details($id, $loop=false)
{
  global $dbconn;

  $q = "SELECT
		  `referrals`.`AutoID` AS `AID`,
		  `referrals`.`UserID`,
		  `referrals`.`VendorID`,
		  `referrals`.`status`,
		  `referrals`.`dateAdded`,
		  `referrals`.`dateCompleted`,
		  `referrals`.`saleAmount`,
		  `referrals`.`referralData`,
		  `referrals`.`need`,
		  `user_signup`.`userCompany`,
		  `user_signup`.`userLastname`,
		  `user_signup`.`userFirstname`,
		  `user_signup`.`useremail`,
		  `user_signup`.`userPhone`,
		  `user_signup`.`userAltPhone`
		  FROM
		  `referrals`
		  Inner Join `user_signup` ON `referrals`.`VendorID` = `user_signup`.`AutoID`
		  WHERE `referrals`.`AutoID` = '{$id}'";

  if ($loop)
  {
    // A loop is needed so just return the query
    return $q;
  }
  else
  {
    //
    $r = mysqli_query($dbconn, $q);
    $result = mysqli_fetch_object($r);
    return $result;
  }
}

/**
 * Allow beginning strings to start with zerp. Useful for credit cards.
 *
 */
function leading_zero($aNumber, $intPart, $floatPart=NULL, $dec_point=NULL, $thousands_sep=NULL)
{
  $formattedNumber = $aNumber;
  if (!is_null($floatPart))
    $formattedNumber = number_format($formattedNumber, $floatPart, $dec_point, $thousands_sep);

  $formattedNumber = str_repeat("0", ($intPart + -1 - floor(log10($formattedNumber)))) . $formattedNumber;
  return $formattedNumber;
}

/**
 * Reduce length of content
 *
 */
function str_stop($string, $max_length)
{
  if (strlen($string) > $max_length)
  {
    $string = substr($string, 0, $max_length);
    $pos = strrpos($string, " ");

    if ($pos === false)
      return substr($string, 0, $max_length) . "...";

    return substr($string, 0, $pos) . "...";
  }
  else
    return $string;
}

/**
 * Shorten a string and adds ... at the end.
 *
 */
function short_name($str, $limit)
{
  // Make sure a small or negative limit doesn't cause a negative length for substr().
  if ($limit < 3)
  {
    $limit = 3;
  }

  // Now truncate the string if it is over the limit.
  if (strlen($str) > $limit)
  {
    return '<abbr title="' . $str . '">' . substr($str, 0, $limit - 3) . '...' . '</abbr>';
  }
  else
  {
    return $str;
  }
}

function state_provinceDropdown($value, $name, $errornum, $default = null)
{
  // STATE and PROVINCE DROP DOWN
  $userstate = '<select name="' . $name . '"';
  if ($errornum)
    $userstate .= 'class="ERROR"'; else
    $userstate .= 'class="REQUIRED"';
  $userstate .= '><option value="">Please Select</option>';

  foreach (eUser::user_provinces() as $province)
  {
    $userstate .= '<option value="' . $province->autoid . '"';
    if ($province->autoid == $value)
      $userstate .= ' selected="selected"';
    $userstate .= '>' . $province->name . '</option>';
  };
  $userstate .= '</select>';

  return $userstate;
}

function country_provinceDropdown($value, $name, $errornum, $default = null)
{
  // COUNTRY DROP DOWN
  $usercountry = '<select name="usercountry"';
  if ($errornum)
    $usercounty .= 'class="ERROR"'; else
    $usercountry .= 'class="REQUIRED"';
  $usercountry .= '><option value="">Please Select</option>';

  foreach (eUser::user_countries() as $countries)
  {
    $usercountry .= '<option value="' . $countries->autoid . '"';
    if ($countries->autoid == $value)
      $usercountry .= ' selected="selected"';
    $usercountry .= '>' . $countries->country . '</option>';
  };
  $usercountry .= '</select>';

  return $usercountry;
}

// Admin side functions to display the nav information for informational lists
function admin_nav_selected($id, $get)
{
  if ($get == $id)
    return ' current';
}

function admin_nav($f)
{
  global $dbconn;

  $links = '<div class="link_menu' . admin_nav_selected("", $_GET["f"]) . '"><a href="#" onclick="ssb(0);">SEARCH</a></div>';

  $all_count = mysqli_fetch_object(mysqli_query($dbconn, "SELECT COUNT(*) AS cnt FROM `user_signup`"))->cnt;
  $links .= '<div class="link_menu' . admin_nav_selected("2", $_GET["f"]) . '"><a href="/admin/users/?f=2">All ( ' . $all_count . ' )</a></div>';

  $contact_count = mysqli_fetch_object(mysqli_query($dbconn, "SELECT COUNT(*) AS cnt FROM `user_signup` WHERE FIND_IN_SET('contact', `siteAreas`) > 0"))->cnt;
  $links .= '<div class="link_menu' . admin_nav_selected("1", $_GET["f"]) . '"><a href="/admin/users/?f=1">Contacts ( ' . $contact_count . ' )</a></div>';

  return $links;
}

function admin_user_flag()
{
  // Additional filtering:
  switch ($_REQUEST["f"])
  {
    case 1:
      myDBC::andWhere("FIND_IN_SET('contact', `siteAreas`) > 0");
      break;
    case 3:
      myDBC::andWhere("FIND_IN_SET('application', `siteAreas`) > 0");
      break;
    case 4:
      myDBC::andWhere("FIND_IN_SET('event', `siteAreas`) > 0");
      break;
    case 5:
      myDBC::andWhere("FIND_IN_SET('member', `siteAreas`) > 0");
      break;
    case 0:
      myDBC::andWhere("FIND_IN_SET('', `siteAreas`) > 0"); //default load nothing
      break;

    default:
  }
}

// Return an array of state names and id values from database
function user_provinces()
{
  global $dbconn;

  $q = "SELECT autoid,name FROM `provinces` ORDER BY `provinces`.`id` DESC,`provinces`.`name` ASC";

  if ($r = mysqli_query($dbconn, $q))
  {
    /* fetch object array */
    while (list($autoid, $name) = mysqli_fetch_array($r, MYSQLI_NUM))
    {
      $states[$autoid] = $name;
    }
  }
  return $states;
}

/**
 * Get information about a Province.
 *
 * @param int $province_id
 * @return object
 */
function get_province_by_id($province_id)
{
  global $Conf, $dbconn;
  if ($province_id)
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
function get_country_by_id($country_id)
{
  global $Conf, $dbconn;
  myDBC::new_andWhere();
  myDBC::andWhere("`AutoID` = $country_id");
  $q = "SELECT * FROM " . $Conf["data"]->CountryTable . myDBC::get_andWhere() . " LIMIT 1";
  $r = mysqli_query($dbconn, $q);
  $row = mysqli_fetch_object($r);
  return $row;
}

// Check if the user is a User or a Member return a integer value based off level
function profile_level($id)
{
  global $dbconn;

  $q = "SELECT `level` FROM `user_signup` WHERE AutoID = '{$id}'";
  $r = mysqli_query($dbconn, $q);
  $result = mysqli_fetch_object($r);

  switch ($result->level)
  {
    case "denied":
      return 0;
      break;
    case "user":
      return 1;
      break;
    case "member":
      return 2;
      break;
    case "admin":
      return 3;
      break;
    case "superadmin":
      return 4;
      break;
    case "developer":
      return 5;
      break;
    default:
  }

  return $result->level;
}

/**
 *
 * @Create dropdown of years
 *
 * @param int $start_year
 *
 * @param int $end_year
 *
 * @param string $id The name and id of the select object
 *
 * @param int $selected
 *
 * @return string
 *
 */
function createYears($start_year, $end_year, $id='year_select', $selected=null)
{

  /*   * * the current year ** */
  $selected = is_null($selected) ? date('Y') : $selected;

  /*   * * range of years ** */
  $r = range($start_year, $end_year);

  /*   * * create the select ** */
  $select = '<select class=\"FIELD\" name="' . $id . '" id="' . $id . '">';
  foreach ($r as $year)
  {
    $select .= "<option value=\"$year\"";
    $select .= ( $year == $selected) ? ' selected="selected"' : '';
    $select .= ">$year</option>\n";
  }
  $select .= '</select>';
  return $select;
}

/*
 *
 * @Create dropdown list of months
 *
 * @param string $id The name and id of the select object
 *
 * @param int $selected
 *
 * @return string
 *
 */

function createMonths($id='month_select', $selected=null)
{
  /*   * * array of months ** */
  $months = array(
      1 => 'January',
      2 => 'February',
      3 => 'March',
      4 => 'April',
      5 => 'May',
      6 => 'June',
      7 => 'July',
      8 => 'August',
      9 => 'September',
      10 => 'October',
      11 => 'November',
      12 => 'December');

  /*   * * current month ** */
  $selected = is_null($selected) ? date('m') : $selected;

  $select = '<select class=\"FIELD\" name="' . $id . '" id="' . $id . '">' . "\n";
  foreach ($months as $key => $mon)
  {
    $select .= "<option value=\"$key\"";
    $select .= ( $key == $selected) ? ' selected="selected"' : '';
    $select .= ">$mon</option>\n";
  }
  $select .= '</select>';
  return $select;
}

/**
 *
 * @Create dropdown list of days
 *
 * @param string $id The name and id of the select object
 *
 * @param int $selected
 *
 * @return string
 *
 */
function createDays($id='day_select', $selected=null)
{
  /*   * * range of days ** */
  $r = range(1, 31);

  /*   * * current day ** */
  $selected = is_null($selected) ? date('d') : $selected;

  $select = "<select class=\"FIELD\" name=\"$id\" id=\"$id\">\n";
  foreach ($r as $day)
  {
    $select .= "<option value=\"$day\"";
    $select .= ( $day == $selected) ? ' selected="selected"' : '';
    $select .= ">$day</option>\n";
  }
  $select .= '</select>';
  return $select;
}

/**
 *
 * @create dropdown list of hours
 *
 * @param string $id The name and id of the select object
 *
 * @param int $selected
 *
 * @return string
 *
 */
function createHours($id='hours_select', $selected=null)
{
  /*   * * range of hours ** */
  $r = range(1, 12);

  /*   * * current hour ** */
  $selected = is_null($selected) ? date('h') : $selected;

  $select = "<select class=\"FIELD\" name=\"$id\" id=\"$id\">\n";
  foreach ($r as $hour)
  {
    $select .= "<option value=\"$hour\"";
    $select .= ( $hour == $selected) ? ' selected="selected"' : '';
    $select .= ">$hour</option>\n";
  }
  $select .= '</select>';
  return $select;
}

/**
 *
 * @create dropdown list of minutes
 *
 * @param string $id The name and id of the select object
 *
 * @param int $selected
 *
 * @return string
 *
 */
function createMinutes($id='minute_select', $selected=null)
{
  /*   * * array of mins ** */
  $minutes = array(00, 15, 30, 45);

  $selected = in_array($selected, $minutes) ? $selected : 0;

  $select = "<select class=\"FIELD\" name=\"$id\" id=\"$id\">\n";
  foreach ($minutes as $min)
  {
    $select .= "<option value=\"$min\"";
    $select .= ( $min == $selected) ? ' selected="selected"' : '';
    $select .= ">" . str_pad($min, 2, '0') . "</option>\n";
  }
  $select .= '</select>';
  return $select;
}

/**
 *
 * @create a dropdown list of AM or PM
 *
 * @param string $id The name and id of the select object
 *
 * @param string $selected
 *
 * @return string
 *
 */
function createAmPm($id='select_ampm', $selected=null)
{
  $r = array('AM', 'PM');

  /*   * * set the select minute ** */
  $selected = is_null($selected) ? date('A') : strtoupper($selected);

  $select = "<select class=\"FIELD\" name=\"$id\" id=\"$id\">\n";
  foreach ($r as $ampm)
  {
    $select .= "<option value=\"$ampm\"";
    $select .= ( $ampm == $selected) ? ' selected="selected"' : '';
    $select .= ">$ampm</option>\n";
  }
  $select .= '</select>';
  return $select;
}

function mailTo_SendGrid($subject, $from='noreply@passinggreen.com', $to, $html, $text, $hdr)
{
  global $hdr;

  // Your SendGrid account credentials
  $username = 'hosting@hgmail.com';
  $password = 'magicemail1';

  // Create new swift connection and authenticate
  $transport = Swift_SmtpTransport::newInstance('smtp.sendgrid.net', 465, 'ssl');
  $transport->setUsername($username);
  $transport->setPassword($password);
  $swift = Swift_Mailer::newInstance($transport);

  // Create a message (subject)
  $message = new Swift_Message($subject);

  // add SMTPAPI header to the message
  $headers = $message->getHeaders();
  $headers->addTextHeader('X-SMTPAPI', $hdr->asJSON());


  // attach the body of the email
  $message->setFrom($from);
  $message->setBody($html, 'text/html');
  $message->setTo($to);
  $message->addPart($text, 'text/plain');


  // send out the emails
  //return $swift->send($message, $failures);

  if ($recipients = $swift->send($message, $failures))
  {
    // This will let us know how many users received this message
    // If we specify the names in the X-SMTPAPI header, then this will always be 1.
    //echo 'Message blasted '.$recipients.' users'; // This will break the jSON signup process if turned on.
  }
  else
  {
    // something went wrong =(
    print_r($failures);
  }
}

function mailTo($subject, $from, $to, $bcc=false, $html, $text, $headers, $reply = true)
{

  $boundary = md5(uniqid(time()));

  foreach ($to as $value => $recipient)
  {

    // To send HTML mail, the Content-type header must be set
    $headers .= 'From: ' . $from . "\r\n";
    $headers .= ( ($bcc) ? 'Bcc: testing@htmlgraphic.com' . "\n" : '');
    $headers .= 'Reply-To: ' . (($reply) ? $from : 'NO REPLY<' . substr_replace($from, "noreply", 0, strpos($from, '@'))) . "\n";
    $headers .= 'Return-Path: ' . $from . "\n";
    $headers .= 'MIME-Version: 1.0' . "\n";
    $headers .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '"' . "\n\n";
    $headers .= "This is a multi-part message in MIME format.\nIf you are reading this, consider upgrading your e-mail client to a MIME-compatible client.\n";
    $headers .= '--' . $boundary . "\n";
    $headers .= 'Content-Type: text/plain; charset=utf-8' . "\n";
    //$headers .= 'Content-Transfer-Encoding: 7bit'. "\n\n";
    $headers .= strip_html_message($text) . "\n";
    $headers .= '--' . $boundary . "\n";
    $headers .= 'Content-Type: text/html; charset=utf-8' . "\n";
    //$headers .= 'Content-Transfer-Encoding: 7bit'. "\n\n";
    $headers .= $html . "\n";
    $headers .= '--' . $boundary . "--\n";

    mail($recipient, $subject, '', $headers);
  }
}

/**
 * Remove HTML tags, including invisible text such as style and
 * script code, and embedded objects.  Add line breaks around
 * block-level tags to prevent word joining after tag removal.
 */
function strip_html_tags($text)
{
  $text = preg_replace(
          array(
      // Remove invisible content
      '@<head[^>]*?>.*?</head>@siu',
      '@<style[^>]*?>.*?</style>@siu',
      '@<script[^>]*?.*?</script>@siu',
      '@<object[^>]*?.*?</object>@siu',
      '@<embed[^>]*?.*?</embed>@siu',
      '@<applet[^>]*?.*?</applet>@siu',
      '@<noframes[^>]*?.*?</noframes>@siu',
      '@<noscript[^>]*?.*?</noscript>@siu',
      '@<noembed[^>]*?.*?</noembed>@siu',
      // Add line breaks before and after blocks
      '@</?((address)|(blockquote)|(center)|(del))@iu',
      '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
      '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
      '@</?((table)|(th)|(td)|(caption))@iu',
      '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
      '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
      '@</?((frameset)|(frame)|(iframe))@iu',
          ), array(
      ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
      "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
      "\n\$0", "\n\$0",
          ), $text);
  return strip_tags($text);
}

function strip_html_message($message)
{
  preg_match('@<meta\s+http-equiv="Content-Type"\s+content="([\w/]+)(;\s+charset=([^\s"]+))?@i', $message, $matches);
  $encoding = $matches[3];

  /* Convert to UTF-8 before doing anything else */
  $utf8_text = iconv($encoding, "utf-8", $message);

  /* Strip HTML tags and invisible text */
  $utf8_text = strip_html_tags($utf8_text);

  /* Decode HTML entities */
  $utf8_text = html_entity_decode($utf8_text, ENT_QUOTES, "UTF-8");

  return $utf8_text;
}

/**
 * Display a notice created from a process script or another action.
 * A temporary session is created and then unset after the message is displayed.
 */
function display_notifications($status, $message)
{

  if (isset($status))
  {
    if ($status == "SUCCESS")
    {
      echo '<div id="x_notice"><div class="Status_SUCCESS">';
      echo $message;
      echo '</div> </div>';
    }
    elseif ($status == "FAILURE")
    {
      echo '<div id="x_notice"><div class="Status_FAILURE">';
      echo $message;
      echo '</div></div>';
    }

    unset($_SESSION["strStatus"]);
    unset($_SESSION["strMessage"]);
  }
}

/*
  2-Way Encryption Scheme
  Peter Hanneman | SecureCube

  Version: v1.1 (1/26/2010)
  Abstract: Ultra secure 2-way encryption for Linux & Unix.
  Disclaimer: This algorithm has been tested against several types of partial knowledge, statistical and brute force attacks; however, I do not claim that this is an unbreakable encryption scheme and as such do not recommend using this in mission critical applications.
  Bug Reports: Thanks to your feedback over the past 6 months I've been able to continue improving the algorithm, if you have any suggestions, questions or concerns feel free to shoot me an email.
  Contact: hannemanp@gmail.com
 */

//Generates a 320 bit private key: The SHA-1 hash function is preformed on the UNIX epoch and an additional random 750 to 1000 bits of entropy generated by the /dev/urandom algorithm.
function genPrivateKey()
{
  $privateKey = sha1(mktime() . shell_exec('head -c ' . mt_rand(750, 1000) . ' < /dev/urandom'));
  return $privateKey;
}

//Generates a 256 bit public key: The MD5 hash function is performed on the UNIX epoch and an additional random amount of entropy from the /urandom function.
function genPublicKey()
{
  $publicKey = md5(mktime() . shell_exec('head -c ' . mt_rand(250, 350) . ' < /dev/urandom'));
  return $publicKey;
}

//Returns an encrypted cipherstream provided plaintext and a private key.
function encrypt($plainText, $privateKey)
{
  $publicKey = genPublicKey();
  $textArray = str_split($plainText);

  $shiftKeyArray = array();
  for ($i = 0; $i < ceil(sizeof($textArray) / 40); $i++)
    array_push($shiftKeyArray, sha1($privateKey . $i . $publicKey));

  $cipherTextArray = array();
  for ($i = 0; $i < sizeof($textArray); $i++)
  {
    $cipherChar = ord($textArray[$i]) + ord($shiftKeyArray[$i]);
    $cipherChar -= floor($cipherChar / 255) * 255;
    $cipherTextArray[$i] = dechex($cipherChar);
  }

  unset($textarray);
  unset($shiftKeyArray);
  unset($cipherChar);

  $cipherStream = implode("", $cipherTextArray) . ":" . $publicKey;

  unset($publicKey);
  unset($cipherTextArray);

  return $cipherStream;
}

//Returns plaintext given the cipherstream and the same private key used to make it.
function decrypt($cipherStream, $privateKey)
{
  $cipherStreamArray = explode(":", $cipherStream);
  unset($cipherStream);
  $cipherText = $cipherStreamArray[0];
  $publicKey = $cipherStreamArray[1];
  unset($cipherStreamArray);

  $cipherTextArray = array();
  for ($i = 0; $i < strlen($cipherText); $i+=2)
    array_push($cipherTextArray, substr($cipherText, $i, 2));
  unset($cipherText);

  $shiftKeyArray = array();
  for ($i = 0; $i < ceil(sizeof($cipherTextArray) / 40); $i++)
    array_push($shiftKeyArray, sha1($privateKey . $i . $publicKey));
  unset($privateKey);
  unset($publicKey);

  $plainChar = null;
  $plainTextArray = array();
  for ($i = 0; $i < sizeof($cipherTextArray); $i++)
  {
    $plainChar = hexdec($cipherTextArray[$i]) - ord($shiftKeyArray[$i]);
    $plainChar -= floor($plainChar / 255) * 255;
    $plainTextArray[$i] = chr($plainChar);
  }

  unset($cipherTextArray);
  unset($shiftKeyArray);
  unset($plainChar);

  $plainText = implode("", $plainTextArray);
  return $plainText;
}

/**
 * Update data in the user_signup table
 *
 * @return int
 */
function capture_cc($ID, $data, $key, $type=false)
{
  global $dbconn;


  if ($type == 'update')
  {
    // Move the existing credit card data into a new column
    $q = "UPDATE `user_signup` SET `cc` = '" . $data . "' WHERE AutoID='{$ID}'";
    mysqli_query($dbconn, $q);

    // Log the key for encrpytion in the key table
    $q = "UPDATE `keys` SET `value` = '" . $key . "'  WHERE value='{$ID}'";
    mysqli_query($dbconn, $q);
  }
  else
  {
    // Log the key for encrpytion in the key table
    $q = "INSERT INTO `keys` ( `value`, `UserID`, `dateAdded` ) VALUES ( '" . $key . "', '" . $ID . "', NOW() );";
    mysqli_query($dbconn, $q);
  }
}

/**
 * Check to see if the user entered valid credit card information.
 *
 * @return int
 */
function validate_stored_payment($cc)
{

//		if ($cc['status'] == "APPROVED") {
//			echo "OK.";
//		} else {
//			echo "FAIL.";
//		}

  if ($cc['name'] <> '')
  {
    //echo "OK.";
  }
  else
  {
    return false;
  }


  if ($cc['ccNum'] <> '')
  {
    //echo "OK.";
  }
  else
  {
    return false;
  }

  if ($cc['YY'] > date("y"))
  {
    // The stored credit card year is greater than the current year.
    //echo "OK.";
  }
  else
  {
    // Check if the month matches OR is greater than the current date.
    if ($cc['MM'] > date("m"))
    {
      //echo "OK.";
    }
    else
    {
      return false;
    }
  }

  if ($cc['ccCODE'] <> '')
  {
    //echo "OK.";
  }
  else
  {
    return false;
  }

  return true;
}

/**
 * Decrypt encrypted data
 *
 * @return int
 */
function decrypt_data($ID, $key=false)
{
  global $dbconn;

  $q = "SELECT `keys`.`value`, `user_signup`.`cc`, `keys`.`UserID`
			FROM `user_signup`
			Inner Join `keys` ON `user_signup`.`AutoID` = `keys`.`UserID`
			WHERE `user_signup`.`AutoID` = '$ID'
			ORDER BY `keys`.`dateAdded` DESC";

  $r = mysqli_query($dbconn, $q);
  $rs = mysqli_fetch_object($r);

  if ($key)
  {

    return $rs->value; // Return the stored encrypted key
  }
  else
  {

    $recover = decrypt($rs->cc, $rs->value); // Return the plaintext information from the decrypt function

    if ($recover)
    {
      return unserialize($recover);
    }
    else
    {
      return false;
    }
  }
}

/**
 * Add the messages to the queue system for delivery.
 *
 * @return int
 */
# TEMPLATE TYPES
// 0 - placeholder
// 1 - unverified account
// 2 - incomplete profile
// 3 - 24 hours pending referral
// 4 - 7 day pending referral
// 5 - 30 days
// 6 - 15 days
// 7 - 45 days
// 8 - 60 days
// 9 - 90 days
// 10 - Welcome email sent user
// 11 - new user account creation sent to admin

function log_message_queue($ID, $rid, $template, $type=false)
{
  global $dbconn;

  if ($type)
  {
    // Log a notice for the user
    $q = "INSERT INTO `message_queue` (`UserID`, `MessageID`, `dateAdded`) VALUES ('" . $ID . "', '" . $template . "', NOW());";
    mysqli_query($dbconn, $q);
  }
  else
  {
    // Log a notice for the referral
    $q = "INSERT INTO `message_queue` (`ReferralID`, `UserID`, `MessageID`, `dateAdded`) VALUES ('" . $rid . "', '" . $ID . "', '" . $template . "', NOW());";
    mysqli_query($dbconn, $q);
  }
}

/**
 * Profile / Settings navigation
 *
 * @return int
 */
function profile_nav($page)
{
  global $User;

  echo '<ul id="profile_nav">';
  echo '<li><a href="/settings/"' . currentPage('settings', $page) . '>Basic Info</a></li>';
  echo '<li><a href="/settings/personal"' . currentPage('personal', $page) . '>Personal Address</a></li>';
  if (profile_level($User->ID) >= 2)
  {
    echo '<li><a href="/settings/business"' . currentPage('business', $page) . '>Business Profile</a></li>';
  }
  echo '<li><a href="/c/settings/balance"' . currentPage('balance', $page) . '>Balance</a></li>';
  //echo '<li><a href="/settings/notifications"'. currentPage('notifications', $page) .'>Notifications</a></li>';
  if (profile_level($User->ID) >= 2)
  {
    echo '<li><a href="/settings/payment"' . currentPage('payment', $page) . '>Payment Info</a></li>';
  }
  //echo '<li><a href="/settings/linked_accounts"' . currentPage('linked_accounts', $page) . '>Linked Accounts</a></li>';
  echo '<li><a href="/c/settings/linked"' . currentPage('linked', $page) . '>Linked Accounts</a></li>';
  echo '</ul>';
}

/**
 * Redirection for profile settings after save.
 *
 * @return int
 */
function profile_redirection($location, $status)
{
  // Additional filtering:c
  switch ($location)
  {
    case 'basic':
      header("Location: https://www.passinggreen.com/settings/?status=" . $status);
      break;
    case 'personal':
      header("Location: https://www.passinggreen.com/settings/personal/?status=" . $status);
      break;
    case 'business':
      header("Location: https://www.passinggreen.com/settings/business/?status=" . $status);
      break;
    case 'balance':
      header("Location: https://www.passinggreen.com/settings/balance/?status=" . $status);
      break;
    case 'notifications':
      header("Location: https://www.passinggreen.com/settings/notifications/?status=" . $status);
      break;
    case 'payment':
      header("Location: https://www.passinggreen.com/settings/payment/?status=" . $status);
      break;
    case 'guide':
      header("Location: https://www.passinggreen.com/account/guide/?status=" . $status);
      break;

    default:
  }
}

/**
 * Create a CSS class based on stage the referral is in.
 *
 */
function profileLogColor($i)
{
  switch ($i)
  {
    case 5:
      return 'pl_process';
      break;
    case 6:
      return 'pl_test';
      break;
    default:
      return 'pl_default';
  }
}

/**
 * Count the amount of message that have been sent under a template and user.
 *
 */
function message_queue_count($id, $tid)
{
  global $dbconn;

  $q = "SELECT count(*) as cnt FROM message_queue
			WHERE UserID = '{$id}' AND MessageID = '{$tid}'";

  $r = mysqli_fetch_object(mysqli_query($dbconn, $q));

  return $r->cnt;
}

/**
 * Return variable for the message queue email delivery.
 *
 * @return $s = subject, $t = filename
 */
function message_queue_template($templateID)
{
  switch ($templateID)
  {
    case 1:
      return array("s" => "[Passing Green] Confirm Account", "t" => "registration_confirm_individual"); // 2 - Incomplete profile
      break;
    case 2:
      return array("s" => "NOTICE: Business Profile Incomplete", "t" => "reminders/incomplete_business_profile"); // 2 - Incomplete profile
      break;
    case 3:
      return array("s" => "[Passing Green] Referral Reminder", "t" => "reminders/referral_reminder_business-24hrs"); // 3 - 24 hours pending referral
      break;
    case 4:
      return array("s" => "[Passing Green] Referral Pending - 7 Days", "t" => "reminders/referral_reminder_business-7ds"); // 4 - 7 day pending referral
      break;
    case 5:
      return array("s" => "[Passing Green] Referral Pending - 30 Days", "t" => "reminders/referral_reminder_business-30ds"); // 5 - 30 day pending referral
      break;
    case 6:
      return array("s" => "[Passing Green] Referral Pending - 15 Days", "t" => "reminders/referral_reminder_business-15ds"); // 4 - 7 day pending referral
      break;
    case 7:
      return array("s" => "[Passing Green] Pending Referral - 45 Days", "t" => "reminders/referral_reminder_business-45ds"); // 4 - 7 day pending referral
      break;
    case 8:
      return array("s" => "[Passing Green] Pending Referral - 60 Days", "t" => "reminders/referral_reminder_business-60ds"); // 4 - 7 day pending referral
      break;
    case 9:
      return array("s" => "[Passing Green] Please Review Referral", "t" => "reminders/referral_reminder_business-90ds"); // 4 - 7 day pending referral
      break;
    case 10:
      return array("s" => "Welcome to Passing Green", "t" => "registration_welcome_v1"); // 4 - 7 day pending referral
      break;
    case 11:
      return array("s" => "New Registration", "t" => "registration_confirm_admin"); // 4 - 7 day pending referral
      break;
  }
}

function twitter_oauth_setOAuth()
{
  if (isset($_GET['oauth_token'], $_SESSION['twitter']['oauth_request_token_secret']))
  {
    $oauth = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $_GET['oauth_token'], $_SESSION['twitter']['oauth_request_token_secret']);
    $accessToken = $oauth->getAccessToken();
    //$oauth = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $accessToken['oauth_token'], $accessToken['oauth_token_secret']);
    $_SESSION['twitter']['oauth_token'] = $accessToken['oauth_token'];
    $_SESSION['twitter']['oauth_token_secret'] = $accessToken['oauth_token_secret'];
    if (!isset($accessToken['user_id']))
    {
      throw new Exception('Authentication failed.');
    }
  }
  else
  {
    $oauth = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);
    $temporaryCredentials = $oauth->getRequestToken('https://www.passinggreen.com/settings/linked_accounts/connect_twitter.php');
    $authorizeUrl = $oauth->getAuthorizeURL($temporaryCredentials);
    $_SESSION['twitter']['oauth_request_token'] = $temporaryCredentials['oauth_token'];
    $_SESSION['twitter']['oauth_request_token_secret'] = $temporaryCredentials['oauth_token_secret'];
    save_HG_sessions();
    header('Location: ' . $authorizeUrl);
    exit();
  }
}

function twitter_oauth_getUserInfo($token, $secret)
{
  $oauth = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $token, $secret);
  $buf = $oauth->get('account/verify_credentials');
  if (isset($buf->error))
  {
    return false;
  }
  return $buf;
}

function twitter_oauth_getFriends($token, $secret)
{
  $oauth = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $token, $secret);
  $credentials = $oauth->get('account/verify_credentials');
  $twitterUserFriendsIds = $oauth->get('friends/ids', array(
              'user_id' => $credentials->id,
              'screen_name' => $credentials->screen_name)
  );
  $twitterUserFriendsIds = array_chunk($twitterUserFriendsIds, 100);
  $twitterUserFriends = array();
  foreach ($twitterUserFriendsIds as $twitterUserFriendIdChunk)
  {
    $twitterUserFriendChunk = array();
    $twitterUserFriendChunk = $oauth->get('users/lookup', array(
                'user_id' => @implode(',', $twitterUserFriendIdChunk),
                'skip_status' => true)
    );
    if (count($twitterUserFriendChunk))
    {
      $twitterUserFriends = array_merge($twitterUserFriends, $twitterUserFriendChunk);
    }
  }
  return $twitterUserFriends;
}

function findOAuthAccountLink($userID, $oauthProvider)
{
  global $dbconn;
  $queryFindExisting = "SELECT * FROM `oauth_users` WHERE `user_id` = '" . $userID . "' AND `oauth_provider` = '" . $oauthProvider . "'";
  $resultFindExisting = $dbconn->query($queryFindExisting);
  if ($resultFindExisting->num_rows == 1)
  {
    // we have an existing link with this user ID
    $rowFindExisting = $resultFindExisting->fetch_object();
    return $rowFindExisting;
  }
  else
  {
    return false;
  }
}
?>