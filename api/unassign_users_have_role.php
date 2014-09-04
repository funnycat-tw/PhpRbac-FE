<?php
//    File: unassign_users_have_role.php
//
//Revision:2014090201
//
//
// Description
//
// public int Rbac->Roles->unassignUsers(int $ID)
// 
// Unassign all Users that have a certain Role.
//
// Parameters
//
// ID
//    Accepts the int ID of a Role
//
// Return Values
//
//    Returns int number of assignments deleted.
//
//

require_once '/var/www/Classes/PhpRbac/src/PhpRbac/Rbac.php';

$ROLE    = "";
$ROLE_ID = "";

if( isset($_REQUEST['role']) ) {
	$ROLE = $_REQUEST['role'];
}

if( isset($_REQUEST['role_id']) ) {
	$ROLE_ID = $_REQUEST['role_id'];
}

if( $ROLE == "" AND $ROLE_ID == "" ) {
	echo "FAIL: Missing parameter(s).";
	exit(-1);
}

$rbac = new PhpRbac\Rbac();

if( $ROLE_ID == "" ) {
	$ROLE_ID = $rbac->Roles->titleId($ROLE);
}

$num_deleted = $rbac->Roles->unassignUsers($ROLE_ID);
// TODO: return value has no error indicator? (not defined in the API)

echo "OK. $num_deleted assignments have been deleted.";

exit();
