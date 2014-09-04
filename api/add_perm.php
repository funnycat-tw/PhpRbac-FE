<?php
//    File: add_perm.php
//
//Revision:2014090101
//
//
// Description
//
// public int Rbac->{Entity}->add(string $Title, string $Description, int $ParentID = null)
//
// Adds a new Role or Permission. 
// 
// Parameters
// 
// Title
//     Accepts string Title of the new entry. 
//
// Description
//    Accepts string Description of the new entry. 
//
// ParentID
//    Optional int ID of the parent Entity in the hierarchy. 
//
// Return Values
//
//    Returns int ID of the new entry.
//

require_once '/var/www/Classes/PhpRbac/src/PhpRbac/Rbac.php';

$TITLE = "";
$DESCR = "";

if( isset($_REQUEST['title']) ) {
	$TITLE = $_REQUEST['title'];
}
if( isset($_REQUEST['descr']) ) {
	$DESCR = $_REQUEST['descr'];
}

if( $TITLE == "" OR $DESCR == "" ) {
	echo "FAIL: Missing parameter(s).";
	exit(-1);
}

$rbac = new PhpRbac\Rbac();

// Create a Permission
$perm_id = $rbac->Permissions->add($TITLE, $DESCR);

if( isset($_REQUEST['json']) ) {
	echo json_encode( array(
             "ID"          => $perm_id,
             "Title"       => $TITLE,
             "Description" =>  $DESCR,
	     )
	);
}
else {
	echo "PERM_ID=$perm_id";
}
exit();
