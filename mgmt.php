<?php
//
//    File: mgmt.php
//	    Rbac management system (PhpRbac Frontend)
//
//Revision:2014090501
//

require_once dirname(__FILE__) . '/conf/config.inc.php';

$TITLE  = __FE_TITLE__;
$HEADER = __FE_HEADER__;
$FOOTER = __FE_FOOTER__;

function _get_user_list() {
	// temp mocked
	return array("user1" => 1, "user2" => 2, "user3" => 3, "admin" => 99);
} // _get_user_list

function _get_role_list() {
	$rbac = new PhpRbac\Rbac();

	return $rbac->Roles->descendants($rbac->Roles->returnId("/"));
} // _get_role_list

function _get_role_children($r_id) {
	$rbac = new PhpRbac\Rbac();

	return $rbac->Roles->descendants($r_id);
} // _get_role_list

function _get_perm_list() {
	$rbac = new PhpRbac\Rbac();

	return $rbac->Permissions->descendants($rbac->Permissions->returnId("/"));
} // _get_perm_list

function _menu() {
	$menu = "";

	$users = _get_user_list();
	$roles = _get_role_list();
	$perms = _get_perm_list();

	if( isset($_REQUEST['dump']) ) {
		$menu = "<pre>" . print_r($users, TRUE) . print_r($roles, TRUE) . print_r($perms, TRUE) . "</pre>";
		foreach($roles as $rk => $rv) {
			$r_id    = $rv['ID'];
			$r_title = $rv['Title'];
			$r_descr = $rv['Description'];
			$menu .= "children of $r_title($r_id)<br>" . "<pre>" . print_r(_get_role_children($r_id), TRUE) . "</pre>";
		}
	}
	else {
		// gen: selection menu of users (default no selected)
		$sel_menu_users = <<< SEL_MENU
<select id='uname' name='uname' size='4'>
<option value='-1'>-- User Name --</option>
SEL_MENU;
		foreach($users as $u_name => $u_id) {
			$sel_menu_users .= "<option value='$u_id'>$u_name($u_id)</option>";
		}

		$sel_menu_users .= <<< SEL_MENU
</select>
SEL_MENU;
		// gen: selection menu of roles (default no selected)
		$sel_menu_roles = <<< SEL_MENU
<select id='rname' name='rname' size='4' onClick='javascript: role_selected();'>
<option value='-1'>-- Role Name --</option>
SEL_MENU;
		foreach($roles as $rk => $rv) {
			$r_id    = $rv['ID'];
			$r_title = $rv['Title'];
			$r_descr = $rv['Description'];
			$sel_menu_roles .= "<option value='$r_id'>$r_title($r_descr)</option>";	
		}

		$sel_menu_roles .= <<< SEL_MENU
</select>
SEL_MENU;
		// gen: selection menu of perms (default no selected)
		$sel_menu_perms = <<< SEL_MENU
<select id='pname' name='pname' size='4' onClick='javascript: perm_selected();'>
<option value='-1'>-- Perm Name --</option>
SEL_MENU;
		foreach($perms as $pk => $pv) {
			$p_id    = $pv['ID'];
			$p_title = $pv['Title'];
			$p_descr = $pv['Description'];
			$sel_menu_perms .= "<option value='$p_id'>$p_title($p_descr)</option>";	
		}

		$sel_menu_perms .= <<< SEL_MENU
</select>
SEL_MENU;

		$menu = <<< EOT
<!-- user table -->
Manage Users:<br />
<table id='user_tbl'>
<tr>
<td>
<form id='user_form'>
$sel_menu_users
</form>
</td>
<td>
<form id='user_role_form'><select name='user_role'><option value='-1'>-- User & Role --</option></select></form>
</td>
<td>
<form id='user_perm_form'><select name='user_perm'><option value='-1'>-- User & Perm --</option></select></form>
</td>
</tr>
<tr>
<td align='right' colspan='3'>
<div onClick='alert("delete user?");'>[del]</div>
</td>
</tr>
<tr>
<td align='right'>
title: <input type='text' value='' id='new_user'>
</td>
<td align='right'>
desc: <input type='text' value='' id='new_user_desc'>
</td>
<td align='right'>
<div onClick='alert("add new user?");' style='cursor: pointer;'>[add]</div>
</td>
</tr>
</table>
<hr>
<!-- role table -->
Manage Roles:<br />
<table id='role_tbl'>
<tr>
<td>
<form id='role_form'>
$sel_menu_roles
</form>
</td>
<td>
<form id='role_user_form'><select name='role_user'><option value='-1'>-- Role & User --</option></select></form>
</td>
<td>
<form id='role_perm_form'><select name='role_perm'><option value='-1'>-- Role & Perm --</option></select></form>
</td>
</tr>
<!-- show edit data when role selected -->
<tr><td>Role ID Choosed</td><td colspan='2' id='r_id_for_edit'></td></tr>
<tr>
<td align='right'>title: <input type='text' id='r_title_for_edit'></td><td align='right'>descr: <input type='text' id='r_descr_for_edit'></td>
<td align='right'>
<div onClick='javascript: modify_role();' style='cursor: pointer;'>[edit]<img src='./img/loading.gif' id='modify_role_is_sending' style='display: none;'></div>
<div onClick='javascript: delete_role();' style='cursor: pointer;'>[del]<img src='./img/loading.gif' id='delete_role_is_sending' style='display: none;'></div>
</td>
</tr>
<tr>
<td align='right'>
title: <input type='text' value='' id='new_role'>
</td>
<td align='right'>
descr: <input type='text' value='' id='new_role_descr'>
</td>
<td align='right'>
<div onClick='javascript:add_new_role();' style='cursor: pointer;'>[add]<img src='./img/loading.gif' id='add_new_role_is_sending' style='display: none;'></div>
</td>
</tr>
<tr>
<td align='right'>
path/: <input type='text' value='' id='new_role_path'>
</td>
<td align='right'>
descr: <input type='text' value='' id='new_role_path_descr'>
</td>
<td align='right'>
<div onClick='javascript: add_new_role_path();' style='cursor: pointer;'>[add]<img src='./img/loading.gif' id='add_new_role_path_is_sending' style='display:none;'></div>
</td>
</tr>
<tr>
<td id='role_msg' align='left' colspan='3'></td>
</tr>
</table>
<hr>
<!-- perm table -->
Manage Permissions:<br />
<table id='perm_tbl'>
<tr>
<td>
<form id='perm_form'>
$sel_menu_perms
</form>
</td>
<td>
<form id='perm_role_form'><select name='perm_role'><option value='-1'>-- Perm & Role --</option></select></form>
</td>
<td>
<form id='perm_user_form'><select name='perm_user'><option value='-1'>-- Perm & User --</option></select></form>
</td>
</tr>
<!-- show edit data when permission selected -->
<tr><td>Permission ID Choosed</td><td colspan='2' id='p_id_for_edit'></td></tr>
<tr>
<td align='right'>title: <input type='text' id='p_title_for_edit'></td><td align='right'>descr: <input type='text' id='p_descr_for_edit'></td>
<td align='right'>
<div onClick='javascript: modify_perm();' style='cursor: pointer;'>[edit]<img src='./img/loading.gif' id='modify_perm_is_sending' style='display: none;'></div>
<div onClick='javascript: delete_perm();' style='cursor: pointer;'>[del]<img src='./img/loading.gif' id='delete_perm_is_sending' style='display: none;'></div>
</td>
</tr>
<tr>
<td align='right'>
title: <input type='text' value='' id='new_perm'>
</td>
<td align='right'>
descr: <input type='text' value='' id='new_perm_descr'>
</td>
<td align='right'>
<div onClick='javascript: add_new_perm();' style='cursor: pointer;'>[add]<img src='./img/loading.gif' id='add_new_perm_is_sending' style='display: none;'></div>
</td>
</tr>
<tr>
<td align='right'>
path/: <input type='text' value='' id='new_perm_path'>
</td>
<td align='right'>
descr: <input type='text' value='' id='new_perm_path_descr'>
</td>
<td align='right'>
<div onClick='javascript: add_new_perm_path();' style='cursor: pointer;'>[add]<img src='./img/loading.gif' id='add_new_perm_path_is_sending' style='display: none;'></div>
</td>
</tr>
<tr>
<td id='perm_msg' align='left' colspan='3'></td>
</tr>
</table>
<hr>
EOT;
	}
	return $menu;
} // _menu

function _header($content) {
	$header =  <<< EOT
<div id='footer'><h2>$content</h2></div>
EOT;

	return $header;
} // _header

function _footer($content) {
	$footer =  <<< EOT
<div id='footer'>$content</div>
EOT;

	return $footer;
} // _footer
?>
<html>
<head>
<meta charset='utf-8'>
<title><?php echo $TITLE; ?></title>
<script src='./js/lib/jquery.min.js'></script>
<script src='./js/mgmt.js'></script>
</head>
<body>
<?php echo _header($HEADER); ?>
<?php echo _menu(); ?>
<?php echo _footer($FOOTER); ?>
</body>
</html>
