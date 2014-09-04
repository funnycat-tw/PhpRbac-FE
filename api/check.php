<?php
//    File: check.php
//
//Revision:2014090101
//
//
// Description
//
// public bool Rbac->check(mixed $Permission, int $UserID = null)
//
// Checks whether a user has a permission or not.
// Parameters
//
// Permission
//    Accepts one of the following:
//
//        int ID
//        string Title
//        string Path
//
// UserID
//    User ID of a user. Must be an int. 
//
// Return Values
//
//    Returns true if a user has a permission, false if otherwise.
//

require_once '/var/www/Classes/PhpRbac/src/PhpRbac/Rbac.php';

$ID   = "";
$PERM = "";

if( isset($_REQUEST['id']) ) {
	$ID = $_REQUEST['id'];
}
if( isset($_REQUEST['perm']) ) {
	$PERM = $_REQUEST['perm'];
}

if( $ID == "" OR $PERM == "" ) {
	echo "FAIL: Missing parameter(s).";
	exit(-1);
}

$rbac = new PhpRbac\Rbac();

// Checks whether a user has a permission or not.
if($rbac->check($PERM, $ID)) {
	echo "YES.";
}
else {
	echo "NO.";
}

exit();
