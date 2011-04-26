WebSecurity library for CodeIgniter 2.0
=========================================

Finally, a supported authentication and authorization 
module for CodeIgniter that doesn't make you break
out in sweat. It evens creates the database tables
for you.

Overview
---------

Your art website requires a ArtUsers table which contains

<table>
    <tr><th>Column</th><th>Type</th></tr>
    <tr><td>UserId</td><td>integer</td></tr>
    <tr><td>UserName</td><td>varchar(50)</td></tr>
    <tr><td>Email</td><td>varchar(50)</td></tr>
    <tr><td>TypeOfPainting</td><td>varchar(50)</td></tr>
    <tr><td>City</td><td>varchar(50)</td></tr>
</table>
    

Modify application/controllers/websecurity_test.php
in the function setupProfile(), the

```
function setupProfile()
{
        
  return array("ArtUsers", "UserId", "UserName",
               array("Email", "TypeOfPainting", "City"));
}
```

Then go to http://localhost/websecurity/setup, and 
your database tables would be set up.
