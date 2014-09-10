<?php
//
//        File: get_roles_of_perm.php
//
// Rbac->Permissions->roles()
// Description
//
// public mixed Rbac->Permissions->Roles(mixed $Permisson, bool $OnlyIDs = true)
//
// Returns all Roles assigned to a Permission.
// Parameters
// 
// Permission
//     Accepts one of the following:
//     int ID
//     string Title
//     string Path

// OnlyIDs
//     If set to true, result is a 1D array of IDs
//     If set to false the result is a 2d array that includes the ID, Title and Description of Roles assigned to the Permission (original API document has no such description). 
// 
// Return Values
//     Returns a 1D or 2D array depending on the parameter $OnlyIDs.
// 
//     Returns null if no Roles are assigned to specified Permission.
// 
//Revision:2014091001
//

require_once '/var/www/Classes/PhpRbac/src/PhpRbac/Rbac.php';


$P_ID  = "";
$TITLE = "";

if( isset($_REQUEST['p_id']) ) {
	$P_ID = $_REQUEST['p_id'];
}
if( isset($_REQUEST['title']) ) {
	$TITLE = $_REQUEST['title'];
}

if( $P_ID == "" AND $TITLE == "" ) {
	echo "FAIL: Missing parameter(s).";
	exit(-1);
}

$rbac = new PhpRbac\Rbac();
$P_ID = $P_ID == "" ? $P_ID = $rbac->Permissions->titleID($TITLE) : $P_ID;

// get permissions of the Role
$RESULT = "";
$PERM_ROLE_LIST = $rbac->Permissions->roles($P_ID, FALSE);	// use $OnlyIDs = FALSE to get 2D result including title,descr of the permissions
$UNASSOCIATED_LIST = array();
if( FALSE && is_null($PERM_ROLE_LIST) ) {
	$RESULT = "FAIL.";
}
else {
	$PERM_ROLE_LIST = is_null($PERM_ROLE_LIST) ? array() : $PERM_ROLE_LIST;
	$ALL_ROLE_LIST = $rbac->Roles->descendants($rbac->Roles->returnId("/"));
	$UNASSOCIATED_LIST = get_unassociated($ALL_ROLE_LIST, $PERM_ROLE_LIST);
	$RESULT = "OK.";
}

echo isset($_REQUEST['json']) ? json_encode( array( "Result" => $RESULT, "List" => $PERM_ROLE_LIST, "unList" => $UNASSOCIATED_LIST ) ) : $RESULT;

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
