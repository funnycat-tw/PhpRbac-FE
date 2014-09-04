<?php
require_once '/var/www/Classes/PhpRbac/src/PhpRbac/Rbac.php';


$rbac = new Rbac();

// Create a Permission
$perm_id = $rbac->Permissions->add('delete_posts', 'Can delete forum posts');

// Create a Role
$role_id = $rbac->Roles->add('forum_moderator', 'User can moderate forums');

$perm_descriptions = array(
    'Can delete users',
    'Can edit user profiles',
    'Can view users'
);

// With Path .../.../...
$rbac->Permissions->addPath('/delete_users/edit_users/view_users', $perm_descriptions);

$role_descriptions = array(
    'Forum Administrator',
    'Forum Moderator',
    'Registered Forum Member'
);

$rbac->Roles->addPath('/admin/forum_moderator/forum_member', $role_descriptions);

// Creating Role/Permission Associations 
//$rbac->Permissions->assign($role_id, $perm_id);
//$rbac->Roles->assign($role_id, $perm_id);
$rbac->assign($role_id, $perm_id);

// Creating User/ROle Associations
$rbac->Users->assign($role_id, _MOCK_get_user_id());

// Editing Existing Entities
// Get Entity Id's
$perm_id = $rbac->Permissions->returnId('delete_posts');
$role_id = $rbac->Roles->returnId('forum_moderator');

// Edit Entities
$rbac->Permissions->edit($perm_id, 'delete_own_posts', 'Can delete posts they create');
$rbac->Roles->edit($role_id, 'forum_spam_moderator', 'User is responsible for spam moderation');

// Removing Existing Permissions and Roles
// Get Permission Id
$perm_id = $rbac->Permissions->returnId('delete_posts');

// Remove single Permission
$rbac->Permissions->remove($perm_id);

// Remove Permission and all descendants
$rbac->Permissions->remove($perm_id, true);

// Removing Roles
// Get Permission Id
$role_id = $rbac->Roles->returnId('forum_moderator');

// Remove single Role
$rbac->Roles->remove($role_id);

// Remove Role and all descendants
$rbac->Roles->remove($role_id, true);

// Unassigning Role/Permission Associations
// Unassign a single Permission/Role assignment
// Unassign a single Permission/Role assignment using Titles.
// The following are equivalent statements.
$rbac->Permissions->unassign('forum_moderator', 'delete_posts');
$rbac->Roles->unassign('forum_moderator', 'delete_posts');

// Unassign all Permissions assigned to a Role:

// Get Role Id
$role_id = $rbac->Roles->returnId('forum_moderator');

// Unassign all Permissions assigned to a Role
$rbac->Roles->unassignPermissions($role_id);

Unassign all Permission/Role assignments related to Permission:

// Get Permission Id
$perm_id = $rbac->Permissions->returnId('delete_posts');

// Unassign all Permission/Role assignments related to Permission
$rbac->Permissions->unassignRoles($perm_id);


// Unassigning User/Role Associations
// Unassign a Role belonging to a User

// Unassign 'forum_user' Role assigned to a User using the Role's Path
$rbac->Users->unassign('/admin/forum_moderator/forum_user', _MOCK_get_user_id());

// Checking for a User/Roles and Permissions
// Make sure a User has a Role:

// Get Role Id
$role_id = $rbac->Roles->returnId('forum_moderator');

// Make sure User has 'forum_user' Role
$rbac->Users->hasRole($role_id, _MOCK_get_user_id());

// Checks whether a User has a Permission or not:

// Check to see if User has 'delete_posts' Permission
$rbac->check('delete_posts', _MOCK_get_user_id());

Enforce a Permission on a User:

// Will return a 403 HTTP status code and an 'Access Denied' message if User does not have Role
$rbac->enforce('forum_moderator', _MOCK_get_user_id());


function _MOCK_get_user_id() {
	return 991;
} // _MOCK_get_user_id
?>
