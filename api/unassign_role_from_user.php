<?php
//    File: unassign_role_from_user.php
//
//Revision:2014090101
//
//
//
// Description
//
// public bool Rbac->Users->unassign(mixed $Role, int $UserID = null)
// 
// Unassigns a Role from an User.
//
// Parameters
//
// Role
//    Accepts one of the following:
//
//        int ID
//        string Title
//        string Path
//
// UserID
//    Accepts an int UserID, provided from external User Management System. 
//    Use 0 for Guest. 
//
// Return Values
//
//    Returns true if successful, false if unsuccessful.
//
//

require_once '/var/www/Classes/PhpRbac/src/PhpRbac/Rbac.php';

$ROLE = "";
$ID   = "";

if( isset($_REQUEST['role']) ) {
	$ROLE = $_REQUEST['role'];
}

if( isset($_REQUEST['id']) ) {
	$ID = $_REQUEST['id'];
}

if( $ROLE == "" OR $ID == "" ) {
	echo "FAIL: Missing parameter(s).";
	exit(-1);
}

$rbac = new PhpRbac\Rbac();

if($rbac->Users->unassign($ROLE, $ID)) {
	echo "OK.";
}
else {
	echo "FAIL.";
}

exit();
