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
class Referral extends eUser
{

  public $VendorID;
  public $Fullname;
  public $Phone;
  public $referralData;
  public $Request;
  public $ID;
  public $Type;
  public $NoEmail;
  public $Notes;

  /**
   * Add a new referral to the database.
   *
   * @return int
   */
  function new_referral()
  {
    global $Conf, $User, $dbconn;
    // referralData is Full Name, Phone, and Email stored in an array.
    $fields = Array("`UserID`" => "'{$User->ID}'",
        "`VendorID`" => "'{$this->VendorID}'",
        "`status`" => "'pending'",
        "`dateAdded`" => "NOW()",
        "`referralData`" => "'{$this->referralData}'",
        "`need`" => "'{$this->Request}'"
    );
    myDBC::insert_Record($Conf['data']->Referrals, $fields);
    // Get that most recent referral ID and link the referral notes database.
    $q = "SELECT `AutoID` FROM {$Conf['data']->Referrals} ORDER BY AutoID DESC";
    $r = $dbconn->query($q);
    $result = mysqli_fetch_object($r);
    return $result->AutoID;
  }

  function new_referralNote($id)
  {
    global $Conf, $User;
    $fields = array(
        "`ReferralID`" => "'{$id}'",
        "`UserID`" => "'{$User->ID}'",
        "`notes`" => "'{$this->Notes}'",
        "`dateAdded`" => "NOW()"
    );
    // dispute_level | 1= admin, 2= referrer, 3= customer
    return myDBC::insert_Record($Conf['data']->ReferralNotes, $fields);
  }

  // Change the status of the referral
  function change_status($rid, $status)
  {
    global $dbconn, $User;
    $q = "UPDATE `referrals` SET status='$status',dateCompleted=NOW() WHERE AutoID = '$rid' AND VendorID = '$User->ID'";
    mysqli_query($dbconn, $q);
  }

  // Change the status of the referral
  function complete_referral($rid, $amount)
  {
    global $dbconn, $User;
    $q = "UPDATE `referrals` SET status = 'completed', dateCompleted = NOW(), saleAmount = '$amount' WHERE AutoID = '$rid' AND VendorID = '$User->ID'";
    mysqli_query($dbconn, $q);
  }

  function process_transactions($uid, $vid)
  {
    global $Conf, $referral;
    // transaction data will enter the funds into the creators account and the Passing Green admin.
    $fields = array(
        "`user_id`" => "'{$uid}'",
        "`referral_id`" => "'{$referral->ID}'",
        "`amount`" => "'{$referral->Commission}'",
        "`created`" => "NOW()",
    );
    myDBC::insert_Record($Conf['data']->Transactions, $fields);
    $fields = array(
        "`user_id`" => "2809",
        "`referral_id`" => "'{$referral->ID}'",
        "`amount`" => "'{$referral->Commission}'",
        "`created`" => "NOW()",
    );
    myDBC::insert_Record($Conf['data']->Transactions, $fields);
    $fields = array(
        "`user_id`" => "'{$vid}'",
        "`referral_id`" => "'{$referral->ID}'",
        "`amount`" => "'{$referral->Fee}'",
        '`is_billable`' => "'1'",
        "`created`" => "NOW()",
    );
    myDBC::insert_Record($Conf['data']->Transactions, $fields);
  }

}

?>