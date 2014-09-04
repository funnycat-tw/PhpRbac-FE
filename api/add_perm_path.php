<?php
//    File: add_perm_path.php
//
//Revision:2014090301
//
//
// Description
//
// public bool Rbac->{Entity}->addPath(string $Path, array $Descriptions = null)
//
// Adds a Path and all its components.
// Will not replace or create siblings if a component exists.
//
// Parameters
// 
// Path
//     Accepts a string Path. Must begin with a / (forward slash).
//
// Return Values
//     Returns int Number of Entities created (0 if none created). 
//

require_once '/var/www/Classes/PhpRbac/src/PhpRbac/Rbac.php';

$PATH = "";
$DESCR = "";

if( isset($_REQUEST['path']) ) {
	$PATH = $_REQUEST['path'];
}
if( isset($_REQUEST['descr']) ) {
	$DESCR = $_REQUEST['descr'];
}

if( $PATH == "" OR $DESCR == "" ) {
	echo "FAIL: Missing parameter(s).";
	exit(-1);
}

$rbac = new PhpRbac\Rbac();

$ARRAY_DESCR = explode("/", $DESCR);
array_shift($ARRAY_DESCR);		// omit first one

// Create a path of Permissions
$num_created = $rbac->Permissions->addPath($PATH, $ARRAY_DESCR);
$RESULT = "OK.";
$MSG = "$num_created new entities have been created.";
$NEW_LIST = array();
$NEW_LIST = $rbac->Permissions->descendants($rbac->Permissions->returnId("/"));

if( isset($_REQUEST['json']) ) {
	echo json_encode( array(
		"Result" => $RESULT,
		"Msg"    => $MSG,
		"List"   => $NEW_LIST,
		)
	);
}
else {
	echo "OK. $num_created new entities have been created.";
}

exit();

