<?php
//    File: assign_perm_to_role.php
//
//Revision:2014090901
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

$ROLE = "";
$PERM = "";
$R_ID = "";

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

$RESULT = "";
$ROLE_PERM_LIST = array();
$UNASSOCIATED_LIST = array();
// assign a role to a permission.
if($rbac->assign($ROLE, $PERM)) {
	$RESULT = "OK.";
	$R_ID = preg_match("/^[0-9]+$/", $ROLE) == 1 ? $ROLE : $rbac->Roles->titleID($ROLE);
	
	$ROLE_PERM_LIST = $rbac->Roles->permissions($R_ID, FALSE);
	$ALL_PERM_LIST = $rbac->Permissions->descendants($rbac->Permissions->returnID("/"));
	$UNASSOCIATED_LIST = get_unassociated($ALL_PERM_LIST, $ROLE_PERM_LIST);
}
else {
	$RESULT = "FAIL.";
}

echo isset($_REQUEST['json']) ? json_encode( array ( "Result" => $RESULT, "List" => $ROLE_PERM_LIST, "unList" => $UNASSOCIATED_LIST) ) : $RESULT;

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
