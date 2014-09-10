<?php
//    File: assign_role_to_perm.php
//
//Revision:2014091001
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

$P_ID  = "";
$TITLE = "";

if( isset($_REQUEST['p_id']) ) {
	$P_ID = $_REQUEST['p_id'];
}
if( isset($_REQUEST['title']) ) {
	$TITLE = $_REQUEST['title'];
}

if( $P_ID == "" OR $TITLE == "" ) {
	echo "FAIL: Missing parameter(s).";
	exit(-1);
}

$rbac = new PhpRbac\Rbac();

$RESULT = "";
$PERM_ROLE_LIST = array();
$UNASSOCIATED_LIST = array();
// assign a role to a permission.
if($rbac->assign($TITLE, $P_ID)) {
	$RESULT = "OK.";
	$P_ID = preg_match("/^[0-9]+$/", $P_ID) == 1 ? $P_ID : $rbac->Permissions->titleID($P_ID);
	
	$PERM_ROLE_LIST = $rbac->Permissions->roles($P_ID, FALSE);
	$ALL_ROLE_LIST = $rbac->Roles->descendants($rbac->Roles->returnID("/"));
	$UNASSOCIATED_LIST = get_unassociated($ALL_ROLE_LIST, $PERM_ROLE_LIST);
}
else {
	$RESULT = "FAIL.";
}

echo isset($_REQUEST['json']) ? json_encode( array ( "Result" => $RESULT, "List" => $PERM_ROLE_LIST, "unList" => $UNASSOCIATED_LIST) ) : $RESULT;

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
