<h1>WebSecurity module tests</h1>
        
<div style="border: 1px #333 solid; padding: 20px;">
    <h2>Current User</h2>
    <?php if ($this->websecuritylib->HasUserId): ?>
        UserId <?php echo $this->websecuritylib->CurrentUserId ?><br />
        UserName <?php echo $this->websecuritylib->CurrentUserName ?> <br />                
    <?php else: ?>
        Not Logged In
    <?php endif; ?>
    
</div>
<?php if (isset($message)):?>
    <div style="background-color:Yellow">
        <?php echo $message; ?>
    </div>
<?php endif;?>
<div>
    <p><a href="<?php echo site_url("/websecurity_test/")?>">Home</a></p>
    <p><a href="<?php echo site_url("/websecurity_test/droptables")?>">Drop Tables</a></p>
    <p><a href="<?php echo site_url("/websecurity_test/createtables") ?>">Create Tables</a></p>
    <p><a href="<?php echo site_url("/websecurity_test/createuser") ?>">Create User</a></p>            
    <p><a href="<?php echo site_url("/websecurity_test/createrole") ?>">Create Role</a></p>            
    <p><a href="<?php echo site_url("/websecurity_test/assignrole") ?>">Assign Role</a></p>            
    <p><a href="<?php echo site_url("/websecurity_test/login") ?>">Login (bob/bob1)</a></p>
    <p><a href="<?php echo site_url("/websecurity_test/") ?>">Check that login worked by inspecting the login box.</a></p>
    <p><a href="<?php echo site_url("/websecurity_test/requireroles?roleName=Guest") ?>"?>RequireRoles (bob/bob1) Guest</a></p>
    <p><a href="<?php echo site_url("/websecurity_test/requireroles?roleName=Administrator") ?>"?>RequireRoles (bob/bob1) Administator (should fail)</a></p>
    <p><a href="<?php echo site_url("/websecurity_test/logout") ?>">Logout bob</a></p>
    <p><a href="<?php echo site_url("/websecurity_test/login_openid") ?>">Login OpenID</a></p>    
</div>
