<?php
//
//        File: get_permissions_of_role.php
//
// Rbac->Roles->permissions()
// Description
//
// public mixed Rbac->Roles->permissions(int $Role, bool $OnlyIDs = true)
//
// Returns all Permissions assigned to a Role.
// Parameters
// 
// Role
//     The int ID of the Role you would like to inspect. 

// OnlyIDs
//     If set to true, result is a 1D array of Permission ID's. 
//     If set to false the result is a 2d array that includes the ID, Title and Description of Permissions assigned to the Role. 
// 
// Return Values
// 
// If the parameter $OnlyIDs is set to true, result is a 1D array of Permission ID's.
// 
// If the parameter $OnlyIDs is set to false the result is a 2d array that includes the ID, Title and Description of Permissions assigned to the Role.
// 
// Returns null if unsuccessful.
// 
//Revision:2014090901
//

require_once '/var/www/Classes/PhpRbac/src/PhpRbac/Rbac.php';

$R_ID  = "";
$TITLE = "";

if( isset($_REQUEST['r_id']) ) {
	$R_ID = $_REQUEST['r_id'];
}
if( isset($_REQUEST['title']) ) {
	$TITLE = $_REQUEST['title'];
}

$rbac = new PhpRbac\Rbac();

if( $R_ID == "" AND $TITLE == "" ) {
	echo "FAIL: Missing parameter(s).";
	exit(-1);
}
else {
	if( $R_ID == "" ) {
		$R_ID = $rbac->Roles->titleID($TITLE);
	}
}

// get permissions of the Role
$RESULT = "";
$ROLE_PERM_LIST = $rbac->Roles->permissions($R_ID, FALSE);		// use $OnlyIDs = FALSE to get 2D result including title,descr of the permissions
$UNASSOCIATED_LIST = array();
if( FALSE && is_null($ROLE_PERM_LIST) ) {
	$RESULT = "FAIL.";
}
else {
	$ROLE_PERM_LIST = is_null($ROLE_PERM_LIST) ? array() : $ROLE_PERM_LIST;
	$ALL_PERM_LIST = $rbac->Permissions->descendants($rbac->Permissions->returnId("/"));
	$UNASSOCIATED_LIST = get_unassociated($ALL_PERM_LIST, $ROLE_PERM_LIST);
	$RESULT = "OK.";
}

echo isset($_REQUEST['json']) ? json_encode( array( "Result" => $RESULT, "List" => $ROLE_PERM_LIST, "unList" => $UNASSOCIATED_LIST ) ) : $RESULT;

exit();

function get_unassociated($all, $assoc) {
	$result_array = array();

	foreach($all as $k => $v) {
		$found = FALSE;
		foreach($assoc as $ka => $va) {
			if( $assoc[$ka]['Title'] == $k ) {
				$found = TRUE;
				break;
			}
		}
		if( !$found ) {
			$result_array[$k] = $v;
		}
	}
	return $result_array;
} // get_unassociated
?>
