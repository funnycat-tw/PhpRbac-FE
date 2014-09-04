<?php
//    File: unassign_roles_have_permission.php
//
//Revision:2014090201
//
//
// Description
//
// public int Rbac->Permissions->unassignRoles(int $ID)
// 
// Unassign all Roless belonging to a Permission.
//
// Parameters
//
// ID
//    Accepts the int ID of the Permission in question.
//
// Return Values
//
//    Returns int number of assignments deleted.
//
//

require_once '/var/www/Classes/PhpRbac/src/PhpRbac/Rbac.php';

$PERM    = "";
$PERM_ID = "";

if( isset($_REQUEST['perm']) ) {
	$PERM = $_REQUEST['perm'];
}

if( isset($_REQUEST['perm_id']) ) {
	$PERM_ID = $_REQUEST['perm_id'];
}

if( $PERM == "" AND $PERM_ID == "" ) {
	echo "FAIL: Missing parameter(s).";
	exit(-1);
}

$rbac = new PhpRbac\Rbac();

if( $PERM_ID == "" ) {
	$PERM_ID = $rbac->Permissions->titleId($PERM);
}

$num_deleted = $rbac->Roles->unassignPermissions($PERM_ID);
// TODO: return value has no error indicator? (not defined in the API)

echo "OK. $num_deleted assignments have been deleted.";

exit();
