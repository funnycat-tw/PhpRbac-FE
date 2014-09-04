<?php
//    File: reset.php
//
//Revision:2014090101
//
//
//
//  Warning: This method removes all Roles, Permissions and Assignments from the database. Usually used for testing purposes.
//
// Description
//
// public bool Rbac->reset(bool $Ensure = false)
//
// Remove all roles, permissions and assignments.
//
// Parameters
//
// Ensure
//    This is a required boolean parameter. If true is not passed an \Exception will be thrown. 
//
// Return Values
//
//   Returns true if a all roles, permissions and assignments have been reset to default values, false if otherwise.
//
//   If $ensure does not equal true, an \Exception will be thrown.
//

require_once '/var/www/Classes/PhpRbac/src/PhpRbac/Rbac.php';

$rbac = new PhpRbac\Rbac();

$rbac->reset(isset($_REQUEST['doit']) ? true : false);		// just written here, without 'TRUE'. it won't reset the rbac database

header("Location: ../mgmt.php");
exit();
