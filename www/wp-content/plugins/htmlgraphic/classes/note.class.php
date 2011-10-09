<?php

/**
 * note.class.php
 *
 * A note class to keep track of notes that are given on the website.
 * This class extends the base User class. It's independent of its parent.
 *
 * Developed by HTMLgraphic Designs
 * Copyright: 2010 HTMLgraphic Designs, LLC.
 * 
 * v0.1 2010-11-26
  Created the initial file to save notes / comments passed on referrals
 */
class Note extends eUser
{

  public $ReferralID;
  public $Notes;
  public $Status;
  public $dateAdded;

  /**
   * Add a new user to the database. This user is just able to give referrals.
   *
   * @return int
   */
  function new_Note($id)
  {
    global $Conf, $User;

    $this->dateAdded = date('Y-m-d H:i:s');
    $fields = Array(
        "`ReferralID`" => "'{$id}'",
        "`UserID`" => "'{$User->ID}'",
        "`notes`" => "'{$this->Notes}'",
        "`dateAdded`" => "'{$this->dateAdded}'"
    );

    // dispute_level | 1= admin, 2= referrer, 3= customer
    return myDBC::insert_Record($Conf['data']->ReferralNotes, $fields);
  }

}

?>