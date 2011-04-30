<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
        <style type="text/css">      
            body, html
            {
                font-family: Segoe UI Light, sans-serif;
                margin: 0;
                padding: 0;
                color: #000;
            }
            h1
            {
                font-weight: normal;
                font-size: 1.2em;
                margin-left: 0;                
                text-shadow: #eee 1px 1px 1px;
            }
            h2
            {
                font-variant:small-caps;
                font-size: 1.0em;
            }
            h2.subtitle
            {
                margin-top: -16px;
                text-shadow: #eee 1px 1px 1px;
            }
            .wrapper 
            {
                width: 760px;
                margin: 0 auto;
            }
            .header
            {
                margin: auto;
                height: 80px;
                padding: 20px 40px;
                /* background: #a7a09a; */
                background-color: #cccccc;
                border-top: 1px solid #eeeeee;
                border-bottom: 1px solid #2b2b2b;
            }
            .main
            {
                clear: both;
                float: left;
                background-color: #d9d9d9;
                padding: 0px 40px 40px;
                width: 680px;
                border-top: 1px solid #eeeeee;
                border-bottom: 2px solid #999999;
            }
            ul.nav
            {
                padding-left: 0px;
                list-style-position: outside;
                list-style-type: none;
                margin-left: 0px;
                padding-left: 0px;
            }
            ul.nav li
            {
                display: inline;
            }
            label
            {
                float: left;
                width: 150px;
            }
            div.list
            {
                clear: both;
            }
            p.list-number
            {
                float: left;
                width: 50px;
                height: 50px;
                text-align: center;
                vertical-align: middle;
                font: 16pt Times;
                background-color: #fff;
                margin: 10px;
                padding: 10px;
                line-height: 50px;
            }
            div.list-description
            {
                width: 540px;
                float: left;
                clear: right;
            }
            div.list-description p
            {
                margin-top: 0px;
                padding-top: 0px;
            }
            hr
            {
                clear: both;
            }
            .error
            {
                color: #ff0000;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="header">
                <h1>WebSecurity Admin Panel</h1>
                <h2 class="subtitle">A Unofficial Authentication Framework for CodeIgniter</h2>
                
                <ul class="nav">
                <?php if (isset($is_root_user) && $is_root_user): ?>
                    <li><a href="<?php echo  site_url('/websecurity_test/') ?>">Test Page</a></li>
                    <li><a href="<?php echo  site_url('/websecurity/users') ?>">Users</a></li>
                    <li><a href="<?php echo  site_url('/websecurity/roles') ?>">Roles</a></li>
                    <li><a href="<?php echo  site_url('/websecurity/setup_openid') ?>">OpenID</a></li>
                    <li><a href="<?php echo  site_url('/websecurity/quickstart') ?>">Quick Start</a></li>
                    <li><a href="<?php echo  site_url('/websecurity/logout_root_user') ?>">Log Out</a></li>
                <?php endif; // is_root_user ?>
                    <li style="float:right">Help</li>
                </ul>
                
            </div>
            <div class="main">

                
                <?php
                $this->load->view($main);
                ?>
                
            </div>
        </div>
    </body>
</html>
