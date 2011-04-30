<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
        <style type="text/css">      
            body, html
            {
                font-family: Segoe UI Light, sans-serif;
                margin: 40px;
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
        </style>
    </head>
    <body>

        <div>
            <img src="/images/403.gif" align="middle"/>
            <div style="display:inline-block;">
                <h2>Oops!</h2>
                <p><?php echo $message ?></p>
                <p><a href="javascript:history.go(-1);">Go Back</a></p>
            </div>
        </div>
        
    </body>
</html>
