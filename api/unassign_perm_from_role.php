<?php
//    File: unassign_perm_from_role.php
//
//Revision:2014091002
//
//
// Description
//
// public bool Rbac->{Entity}->unassign(mixed $Role, mixed $Permission)
//
// Unassigns a Role-Permission relation.
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

$R_ID  = "";
$TITLE = "";

if( isset($_REQUEST['r_id']) ) {
	$R_ID = $_REQUEST['r_id'];
}
if( isset($_REQUEST['title']) ) {
	$title = $_REQUEST['title'];
}

if( $R_ID == "" OR $TITLE == "" ) {
	echo "FAIL: Missing parameter(s).";
	exit(-1);
}

$rbac = new PhpRbac\Rbac();

$RESULT = "";
$ROLE_PERM_LIST = array();
$UNASSOCIATED_LIST = array();
// unassign a role-permission relation
if($rbac->Permissions->unassign($R_ID, $TITLE)) {	// cannot use $rbac->Roles->unassign(), no such API
	$RESULT = "OK.";
	
	$R_ID = preg_match("/^[0-9]+$/", $R_ID) == 1 ? $R_ID : $rbac->Roles->titleID($R_ID);

	$ROLE_PERM_LIST = $rbac->Roles->permissions($R_ID, FALSE);
	$ALL_PERM_LIST = $rbac->Permissions->descendants($rbac->Permissions->returnID("/"));
	$UNASSOCIATED_LIST = get_unassociated($ALL_PERM_LIST, $ROLE_PERM_LIST);
}
else {
	$RESULT = "FAIL.";
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
