<?php
//    File: unassign_perm_and_role.php
//
//Revision:2014090102
//
//
// Description
//
// public bool Rbac->{Entity}->unassign(mixed $Role, mixed $Permission)
//
// Unassigns a Role-Permission relation.
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

// unassign a role-permission relation
if($rbac->Permissions->unassign($ROLE, $PERM)) {	// or $rbac->Roles->unassign(), what a mess...
	echo "OK.";
}
else {
	echo "FAIL.";
}

exit();
