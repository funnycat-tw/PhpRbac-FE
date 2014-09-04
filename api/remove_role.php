<?php
//    File: remove_role.php
//
//Revision:2014090201
//
//
// Description
//
// public bool Rbac->Roles->remove(int $ID, bool $Recursive = false)
//
// Remove Permissions from system.
// 
// Parameters
// 
// ID
//    The int ID of the Role.
// 
// Recursive
//    If set to true, all descendants of the Role will also be removed.
//
// Return Values
//
//    Returns true if successful, false if unsuccessful.
//

require_once '/var/www/Classes/PhpRbac/src/PhpRbac/Rbac.php';

$TITLE = "";
$R_ID  = "";
$RECUR = "";

if( isset($_REQUEST['title']) ) {
	$TITLE = $_REQUEST['title'];
}
if( isset($_REQUEST['r_id']) ) {
	$R_ID = $_REQUEST['r_id'];
}
if( isset($_REQUEST['recur']) ) {
	$RECUR = $_REQUEST['recur'];
}

if( $TITLE == "" AND $R_ID == "" ) {
	echo "FAIL: Missing parameter(s).";
	exit(-1);
}

$rbac = new PhpRbac\Rbac();

// remove role
$RESULT = "";
$NEW_LIST = array();
if( $rbac->Roles->remove($R_ID == "" ? $rbac->Roles->titleId($TITLE) : $R_ID, $RECUR == "" ? TRUE : FALSE) ) {	// don't use returnId, not work when $TITLE has '/'
	$RESULT = "OK.";
	$NEW_LIST = $rbac->Roles->descendants($rbac->Roles->returnId("/"));
}
else {
	$RESULT = "FAIL.";
}

echo isset($_REQUEST['json']) ? json_encode( array( "Result" => $RESULT, "List" => $NEW_LIST ) ) : $RESULT;

exit();
