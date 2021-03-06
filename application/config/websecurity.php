<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Root Password
|--------------------------------------------------------------------------
|
| Password to access the WebSecurity module.
|
*/
$config['root_password']	= '';

/*
|--------------------------------------------------------------------------
| User Table Name
|--------------------------------------------------------------------------
|
| Table to store user data
|
*/
$config['usertablename']    = 'Users';

/*
|--------------------------------------------------------------------------
| UserId Column Name
|--------------------------------------------------------------------------
|
| Column where UserId (integer) is kept.
|
*/

$config['useridcolumn']    = 'UserId';

/*
|--------------------------------------------------------------------------
| UserName Column Name
|--------------------------------------------------------------------------
|
| Column where UserName (string) is kept.
|
*/

$config['usernamecolumn']    = 'UserName';

/*
|--------------------------------------------------------------------------
| UserProfiles
|--------------------------------------------------------------------------
|
| Array of optional profile data on the User table.
|
*/

$config['userprofiles']    = array('Email', 'FirstName', 'LastName', 'Twitter');

/*
|--------------------------------------------------------------------------
| Enable OpenID
|--------------------------------------------------------------------------
|
| Permit OpenID authentication
|
*/

$config['enable_openid']   = FALSE;

/*
|--------------------------------------------------------------------------
| Enable Twitter
|--------------------------------------------------------------------------
|
| Permit OpenID authentication
|
*/

$config['enable_twitter']   = FALSE;

/*
|--------------------------------------------------------------------------
| Enable Facebook
|--------------------------------------------------------------------------
|
| Permit OpenID authentication
|
*/

$config['enable_facebook']   = FALSE;

?>
