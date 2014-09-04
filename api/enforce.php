<?php
//    File: enforce.php
//
//Revision:2014090101
//
//
// Description
//
// public mixed Rbac->enforce(mixed $Permission, int $UserID = null)
//
// Enforces a permission on a user. 
//
// Parameters
//
// Permission
//    Accepts one of the following:
//
//        int ID
//        string Title
//        string Path
//
// UserID
//    User ID of a user. Must be an int. 
//
// Return Values
//
//    Returns true if the user has the permission.
//    
//    If the user does not have the permission two things happen:
//    A 403 HTTP status code header will be sent to the web client.
//    Script execution will terminate with a 'Forbidden: You do not have permission to access this resource.' message.
//

require_once '/var/www/Classes/PhpRbac/src/PhpRbac/Rbac.php';

$ID   = "";
$PERM = "";

if( isset($_REQUEST['id']) ) {
	$ID = $_REQUEST['id'];
}
if( isset($_REQUEST['perm']) ) {
	$PERM = $_REQUEST['perm'];
}

if( $ID == "" OR $PERM == "" ) {
	echo "FAIL: Missing parameter(s).";
	exit(-1);
}

$rbac = new PhpRbac\Rbac();

// Checks whether a user has a permission or not.
$rbac->enforce($PERM, $ID);

exit();
