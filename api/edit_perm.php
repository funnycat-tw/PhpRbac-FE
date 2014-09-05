<?php
//
//        File: edit_perm.php
//
// Description
//
// public bool Rbac->{Entity}->edit(int $ID, string $NewTitle = null, string $NewDescription = null)
//
// Edits an Entity, changing the Title and/or Description. Maintains ID.
// Parameters
//
//  ID
//     Accepts the int ID of the Entity you would like to change. 
//
// NewTitle
//     Accepts a new string Title. 
//
// NewDescription
//     Accepts a new string Description. 
//
// Return Values
//
// Returns true if successful, false if the Entity does not exist.
//
//
//Revision:2014090502
//

require_once '/var/www/Classes/PhpRbac/src/PhpRbac/Rbac.php';

$P_ID  = "";
$TITLE = "";
$DESCR = "";

if( isset($_REQUEST['p_id']) ) {
	$P_ID = $_REQUEST['p_id'];
}
if( isset($_REQUEST['title']) ) {
	$TITLE = $_REQUEST['title'];
}
if( isset($_REQUEST['descr']) ) {
	$DESCR = $_REQUEST['descr'];
}

if( $P_ID == "" OR $TITLE == "" ) {	// p_id, title must be given
	echo "FAIL: Missing parameter(s).";
	exit(-1);
}

$rbac = new PhpRbac\Rbac();

// Edit a Permission
$RESULT = "";
$NEW_LIST = array();
if( $rbac->Permissions->edit($P_ID, $TITLE, $DESCR) )  {
	$RESULT = "OK.";
	$NEW_LIST = $rbac->Permissions->descendants($rbac->Permissions->returnId("/"));
}
else {
	$RESULT = "FAIL.";
}

echo isset($_REQUEST['json']) ? json_encode( array( "Result" => $RESULT, "List" => $NEW_LIST ) ) : $RESULT;

exit();
?>
