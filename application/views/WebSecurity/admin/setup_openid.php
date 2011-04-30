<h1>Set Up OpenID</h1>

<div class="list">
    <p class="list-number">
        1
    </p>
    <div class="list-description">
        <h2>Set up OpenID database tables</h2>
        <?php if ($database_tables_exist): ?>
        <p><b>Database tables created.</b></p>
        <?php else: //database_tables_exist ?>
        <p class="error">
            <i>Database tables not set up.</i><br />
        </p>
        <?php endif; //database_tables_exist ?>
        <p>
            Create table <b>websecurity_OpenIdUsers</b> with columns:<br/> 
                <b>OpenIdUserId</b>,
                <b>UserId</b>,
                <b>OpenIdUrl</b>,
        </p>
        <p>
            <form action="<?php echo site_url('/websecurity/create_openid_tables') ?>" method="post">
            Click <input type="submit" value="Set up database tables" />                             
            </form>
        </p>
    </div>
</div>

<hr width="100%" />                
<div class="list">
    <p class="list-number">
        2
    </p>
    <div class="list-description">
        <h2>Enable/Disable OpenID</h2>
        <p>
            <i>OpenID is currently disabled.</i><br />
        </p>
        <p>
            Click to <input type="submit" value="Enable OpenID" />                             
        </p>
    </div>
</div>

<hr width="100%" />                
<div class="list">
    <p class="list-number">
        3
    </p>
    <div class="list-description">
        <h2>Review and Test OpenId Sign On</h2>
        <p>
            Go to the <a href="<?php echo site_url('/websecurity/login'); ?>">Login screen</a> and click on an OpenID provider icon 
            (such as Facebook, Google or Hotmail).
        </p>
        <p>
            For your first test, associate your OpenID with an existing account.
        </p>
        <p>
            For your second test, create a new account.
        </p>
    </div>
</div>
