<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Root Password
|--------------------------------------------------------------------------
|
| Password to access the WebSecurity module.
|
*/
$config['root_password']	= '$2a$15$owRpBhm8EGZFQwb3XNjJ6Oyjt9/87C0MOAn.wy31C7GzRq7YF1ySK';

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

?>