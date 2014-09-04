<?php
//    File: remove_perm.php
//
//Revision:2014090201
//
//
// Description
//
// public bool Rbac->Permissions->remove(int $ID, bool $Recursive = false)
//
// Remove Permissions from system.
// 
// Parameters
// 
// ID
//    The int ID of the Permission.
//
// Recursive
//    If set to true, all descendants of the Permission will also be removed.
//
// Return Values
//
//    Returns true if successful, false if unsuccessful.
//

require_once '/var/www/Classes/PhpRbac/src/PhpRbac/Rbac.php';

$TITLE = "";
$P_ID  = "";
$RECUR = "";

if( isset($_REQUEST['title']) ) {
	$TITLE = $_REQUEST['title'];
}
if( isset($_REQUEST['p_id']) ) {
	$P_ID = $_REQUEST['p_id'];
}
if( isset($_REQUEST['recur']) ) {
	$RECUR = $_REQUEST['recur'];
}

if( $TITLE == "" AND $P_ID == "" ) {
	echo "FAIL: Missing parameter(s).";
	exit(-1);
}

$rbac = new PhpRbac\Rbac();

// remove permission
$RESULT = "";
$NEW_LIST = array();
if( $rbac->Permissions->remove($P_ID == "" ? $rbac->Permissions->titleId($TITLE) : $P_ID, $RECUR == "" ? TRUE : FALSE) ) {	// don't use returnId, not work when $TITLE has '/'
	$RESULT = "OK.";
	$NEW_LIST = $rbac->Permissions->descendants($rbac->Permissions->returnId("/"));
}
else {
	$RESULT = "FAIL.";
}

echo isset($_REQUEST['json']) ? json_encode( array( "Result" => $RESULT, "List" => $NEW_LIST ) ) : $RESULT;

exit();
