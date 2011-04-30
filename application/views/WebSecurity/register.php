<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
        <style type="text/css">
            body
            {
                font-family: Segoe UI Light, sans-serif;
                margin: 40px;
            }
            label
            {
                display: block;
            }
            input[type='text'], input[type='password']
            {
                font-size: x-large;
            }
            div
            {
                margin-bottom: 10px;
            }
            h1, h2
            {
                margin-top: 0px; 
                padding-top: 0px;
            }
            h1
            {    
                margin-bottom: 0px;
            }
        </style>
    </head>
    <body>
        
        <h1>Create your account</h1>
        <h2>CodeIgniter Site account creation</h2>
        
        <?php echo form_open('websecurity/register') ?>
        
        <div>
            <label for="username">User Name</label>
            <input type="text" name="username" id="username" value="<?php echo set_value('username')?>" size="20" />
        </div>
        
        <div>
            <label for="password"  style="display:run-in; margin-right: 210px;">Password</label>
            <label for="password2">Confirm</label>
            <input type="password" name="password" id="password"   size="20" style="clear:none;" />
            <input type="password" name="password2" id="password"  size="20" />
        </div>
        
        <div>
            <label for="email">Email Address</label>
            <input type="text" name="email" id="email" />
        </div>

        <div>
            <input type="checkbox" name="tos" /><label for="tos" style="display: inline;">I agree to abide by the <a href="">Terms of Use</a> of this site.</label>
        </div>

        <div>
            <?php echo($cap['image']) ?>
            <label for="captcha">Help us fight spam by entering the text above</label>
            <input type="text" name="captcha" />            
        </div>
        <input type="submit" name="signin" value="Sign Up" />
        
        </form>
        
    </body>
</html>
