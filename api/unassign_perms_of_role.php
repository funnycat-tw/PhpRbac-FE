<?php
//    File: unassign_perms_of_role.php
//
//Revision:2014090102
//
//
// Description
//
// public int Rbac->Roles->unassignPermissions(int $ID)
// 
// Unassign all Permissions belonging to a Role.
//
// Parameters
//
// ID
//    Accepts the int ID of the Role in question.
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

$num_deleted = $rbac->Roles->unassignPermissions($ROLE_ID);
// TODO: return value has no error indicator? (not defined in the API)

echo "OK. $num_deleted assignments have been deleted.";

exit();
