<h1>Quick Start</h1>

<?php if (isset($root_password_saved) && $root_password_saved): ?>
<hr width="100%" />
<p style="margin-left:20px; margin-right:20px;"><b>
    If your password is weak, you may wish to set the <code>root_password</code>
    in <code>application/config/websecurity.php</code> to '' when you are finished.
    Doing so will disable access to the admin screens.</b>
</p>        
<hr width="100%" />
<?php endif; //root_password_saved ?>

<?php if (!isset($is_root_user) || !$is_root_user): ?>
    <?php if (!isset($root_password_saved) || !$root_password_saved): ?>
                <p>
                    This is the first time you accessed the security screen.
                    Please enter a password to secure it. This will be the root user.
                </p>
        <?php if (isset($password_error)): ?>
                <p class="error">
        <?php echo($password_error); ?>
                </p>
        <?php endif;?>
                <p>                    
                    <form action="<?php echo site_url('/websecurity/createrootpassword'); ?>" method="post">
                        <label for="password">Password</label><input id="password" name="password" type="password" /><br/>
                        <label for="password2">Confirm password</label><input id="password2" name="password2" type="password" /><br />
                        <input type="submit" value="Create Password" />                    
                    </form>
                </p>
                
        <?php if (isset($hash)):?>                   
                <p> Copy and paste the following into the <code>application/config/websecurity.php</code>.
                    <div><?php echo($hash); ?></div>                    
                </p>
                <p>Then proceed to login: <a href="<?= site_url('/websecurity/quickstart'); ?>">Login page</a></p>
        <?php endif;?>

    <?php else: //root_password_saved ?>
                
        <form action="<?= site_url('/websecurity/login_root_user'); ?>" method="post">                
        <label for="password">Password</label>
        <input id="password" name="password" type="password" /><br/>
        <input type="submit" name="LoginRootUser" value="Login" />                    
        </form>
        
    <?php endif; //root_password_saved ?>
                    
                <hr width="100%" />                
<?php else: // is_root_user ?>

        <div class="list">
            <p class="list-number">
                1
            </p>
            <div class="list-description">
                <h2>Set up database tables</h2>
                
                <?php if (!$database_tables_exist): ?>
                <p class="error">
                    <i>Database tables not set up.</i><br />
                </p>
                <?php else:?>
                <p><b>Database tables has been set up.</b></p>
                <?php endif; //database_tables_exist ?>
                
                <p>
                    Review the following list. <strong>(THIS STEP CAN BE PERFORMED ONCE ONLY)</strong>.
                </p>
                <p>
                    Create table <b>Users</b> with columns:<br/>
                        <b><?= $this->config->item('useridcolumn', 'websecurity') ?></b>,
                        <b><?= $this->config->item('usernamecolumn', 'websecurity') ?></b>,
                        <b><?php echo(join('</b>, <b>', $this->config->item('userprofiles', 'websecurity'))) ?></b>;
                    <br />
                </p>
                <p>You can add/remove profile items by modifying<br /> <code>application/config/websecurity.php</code></p>
                <p>
                    <form action="<?= site_url('/websecurity/create_database_tables'); ?>" method="post">
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
                <h2>(Optional) Create web site roles</h2>
                <!-- Uses GET because it is easier to audit -->
                <p><form action="<?php echo(site_url('/websecurity/create_role')); ?>" method="get">
                    Click <input type="submit" value="Add this role" /> 
                    <input type="text" name="roleName" value="Administrator" /> to add
                    a role.
                    </form>
                </p>
            </div>        
        </div>
        
        <hr width="100%" />
        <div class="list">
            <p class="list-number">
                3
            </p>
            <div class="list-description">
                <h2>Review and Test the Registration Screen</h2>
                <p>Link to the <a href="<?php echo(site_url('/websecurity/register')) ?>">Registration Screen</a></p>
                <p>You can customize the registration form at 
                    <code>application/views/register.php</code>
                    and <code>application/controllers/websecurity.php (function register)</code>.
                </p>
            </div>        
        </div>
        
        <hr width="100%" />
        <div class="list">
            <p class="list-number">
                4
            </p>
            <div class="list-description">
                <h2>Review and Test the Login/Logout Screen</h2>
                <?php if (!$this->websecuritylib->IsAuthenticated): ?>
                <p>Link to the <a href="./login">Login Screen</a></p>
                <?php else: // IsAuthenticated ?>
                <p>You are logged in. Please <a href="./logout">log out</a> first.</p>
                <?php endif; // IsAuthenticated ?>
                <p>You can customize the login form at 
                    <code>application/views/login.php</code>
                    and <code>application/controllers/websecurity.php (function login)</code>.
                </p>
            </div>        
        </div>
        
        <div class="list" />
            
<?php endif; // is_root_user ?>             