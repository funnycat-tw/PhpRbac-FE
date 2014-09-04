<?php
//    File: add_user.php
//
//Revision:2014090101
//
//NOTE: not official API of PhpRbac
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

// Create an User
$user_id = $rbac->Users->add($TITLE, $DESCR);

echo "USER_ID=$user_id";
exit();
