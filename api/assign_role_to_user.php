<?php
//    File: assign_role_to_user.php
//
//Revision:2014090101
//
//
//
// Description
//
// public bool Rbac->Users->assign(mixed $Role, int $UserID = null)
// 
// Assigns a role to a user
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
//    Returns true if the assignment was created successfuly.
//
//    Returns false if the assignment already exists.
//
//    Throws \RbacUserNotProvidedException Exception if UserID is not provided.
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

// Assign a role to an user
try {
	if($rbac->Users->assign($ROLE, $ID)) {
		echo "OK.";
	}
	else {
		echo "WARN: Already exists.";
	}
} catch (Exception $e) {
	echo "EXCEPTION: " . $e->getMessage();
}

exit();
