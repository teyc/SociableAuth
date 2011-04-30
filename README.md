WebSecurity library for CodeIgniter 2.0
=========================================

Finally, a supported authentication and authorization 
module for CodeIgniter that doesn't make you break
out in sweat. It evens creates the database tables
for you.

Overview
---------

After installing SociableAuth, you can configure
the authentication module by going to the admin screen.

http::/localhost/index.php/websecurity/admin

Follow the steps indicated.

Key goals
----------

Strong password storage using key strengthening.
Doesn't add any magical frameworks.
Works well with OpenID.
Works with your own User tables.

Example:
------------

Your art website requires a ArtUsers table which contains

<table>
    <tr><th>Column</th><th>Type</th></tr>
    <tr><td>UserId</td><td>integer</td></tr>
    <tr><td>UserName</td><td>varchar(50)</td></tr>
    <tr><td>Email</td><td>varchar(50)</td></tr>
    <tr><td>TypeOfPainting</td><td>varchar(50)</td></tr>
    <tr><td>City</td><td>varchar(50)</td></tr>
</table>
    

Modify application/config/websecurity.php


```php
$config['usertablename']    = 'ArtUsers';
$config['useridcolumn']     = 'UserId';
$config['usernamecolumn']   = 'UserName';
$config['userprofiles']     = array('Email', 'TypeOfPainting', 'City');
```

Then go to http://localhost/websecurity/admin, and click on "Create Database Tables"
your database tables would be set up.


Securing pages
-------------------

You can secure pages by checking whether user is authenticated.

```
$this->load->library('websecuritylib');
if (!$this->websecuritylib->IsAuthenticated)
{
    $this->load->helper('url');
    redirect('websecurity/login');
    return;
}
```

Securing pages by roles
-------------------------

Roles are stored in the webpages_Roles table. To save you the hassle,
add a role by using the admin screen.

Once done, you can secure a page using RequireRole
```
$this->load->library('websecuritylib');
if (!$this->websecuritylib->RequireRoles('Administrators'))
{
    echo('You have to be an administrator');
}
```
