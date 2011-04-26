<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
        <style>
        body
        {
            font-family: Segoe UI Light, sans-serif;
            margin: 40px;
        }
        label
        {
            float: left;
            width: 90px;
        }
        div
        {
            margin-bottom: 10px;
        }
        </style>

        <!-- Simple OpenID Selector -->
        <link type="text/css" rel="stylesheet" href="/css/openid.css" />
        <script type="text/javascript" src="/js/jquery-1.2.6.min.js"></script>
        <script type="text/javascript" src="/js/openid-jquery.js"></script>
        <script type="text/javascript" src="/js/openid-en.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                openid.init('openid_identifier');
                //openid.setDemoMode(true); //Stops form submission for client javascript-only test purposes
            });
        </script>
        
    </head>
    <body>
        <form action="<?= site_url('websecurity/login') ?>" method="post">
            <h2>Log In to CodeIgniter Site</h2>
            
            <?php if (!empty($message)): ?>
            <p class="error"><?php echo($message) ?></p>
            <?php endif; ?>
        
            <div>
                <label for="UserName">User Name</label>
                <input id="UserName" type="text" name="username" />
            </div>

            <div>
                <label for="Password">Password</label>
                <input id="Password" type="password" name="password" />
            </div>
            
            <div>
                <input type="checkbox" name="remember_me" />
                Remember me
            </div>
        
            <input type="hidden" name="return_to" value="<?= isset($return_to)?$return_to: '' ?>" />
            <input type="submit" name="Login" value="Login" />
        
        </form>

        <p>or</p>
        
        <!-- Simple OpenID Selector -->
        <form action="<?= site_url('/websecurity/openid').(isset($return_to)?'?return_to='.htmlentities($return_to):'') ?>" method="get" id="openid_form">
            <input type="hidden" name="action" value="verify" />
            <fieldset>
                <legend>Sign-in or Create New Account</legend>
                <div id="openid_choice">
                    <p>Please click your account provider:</p>
                    <div id="openid_btns"></div>
                </div>
                <div id="openid_input_area">
                    <input id="openid_identifier" name="openid_identifier" type="text" value="http://" />
                    <input id="openid_submit" type="submit" value="Sign-In"/>
                </div>
                <noscript>
                    <p>OpenID is service that allows you to log-on to many different websites using a single indentity.
                    Find out <a href="http://openid.net/what/">more about OpenID</a> and <a href="http://openid.net/get/">how to get an OpenID enabled account</a>.</p>
                </noscript>
            </fieldset>
        </form>
        <!-- /Simple OpenID Selector -->

    </body>
</html>
