<?php
//    File: assign_perm_to_role.php
//
//Revision:2014090101
//
//
// Description
//
// public bool Rbac->assign(mixed $Role, mixed $Permission)
//
// Assign a role to a permission.
// Alias for Rbac->{Entity}->assign().
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
// Permission
//    Accepts one of the following:
//
//        int ID
//        string Title
//        string Path
//
// Return Values
//
//   Returns true if successful, false if unsuccessful.
//

require_once '/var/www/Classes/PhpRbac/src/PhpRbac/Rbac.php';

$ROLE = "";
$PERM = "";

if( isset($_REQUEST['role']) ) {
	$ROLE = $_REQUEST['role'];
}
if( isset($_REQUEST['perm']) ) {
	$PERM = $_REQUEST['perm'];
}

if( $ROLE == "" OR $PERM == "" ) {
	echo "FAIL: Missing parameter(s).";
	exit(-1);
}

$rbac = new PhpRbac\Rbac();

// assign a role to a permission.
if($rbac->assign($ROLE, $PERM)) {
	echo "OK.";
}
else {
	echo "FAIL.";
}

exit();
