<?php
//    File: check_rule_has_perm.php
//
//Revision:2014091001
//
// Description
// 
// public bool Rbac->Roles->hasPermission(int $Role, integer $Permission)
// 
// Checks to see if a Role has a Permission or not.
// Parameters
// 
// Role
//     Accepts the int ID of a Role 
// 
// Permission
//     Accepts the int ID of a Permission 
// 
// Return Values
// 
// Returns true if the specified Role has the specified Permission, false if otherwise.
// 

require_once '/var/www/Classes/PhpRbac/src/PhpRbac/Rbac.php';

$rbac = new PhpRbac\Rbac();

$R_ID = "";
$P_ID = "";
$ROLE = isset($argv[1]) ? $argv[1] : "";
$PERM = isset($argv[2]) ? $argv[2] : "";

if( isset($_REQUEST['r_id']) ) {
	$R_ID = $_REQUEST['r_id'];
}
if( isset($_REQUEST['p_id']) ) {
	$P_ID = $_REQUEST['p_id'];
}
if( isset($_REQUEST['role']) ) {
	$ROLE = $_REQUEST['role'];
}
if( isset($_REQUEST['perm']) ) {
	$PERM = $_REQUEST['perm'];
}
$R_ID = ($R_ID == "" AND $ROLE != "") ? $rbac->Roles->titleID($ROLE) : $R_ID;
$P_ID = ($P_ID == "" AND $PERM != "") ? $rbac->Permissions->titleID($PERM) : $P_ID;

if( $R_ID == "" AND $P_ID == "" ) {
	echo "FAIL: Missing parameter(s).";
	exit(-1);
}

// Checks whether a role id has permission id
$RESULT = $rbac->Roles->hasPermission($R_ID, $P_ID) ? "YES." : "NO.";

echo (isset($_REQUEST['json']) OR isset($argv[3])) ? json_encode( array( "Result" => $RESULT ) ) : $RESULT;

exit();
?>
