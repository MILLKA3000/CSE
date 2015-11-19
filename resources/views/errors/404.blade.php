<!DOCTYPE html>
<html>
    <head>
        <title>Be right back.</title>

        <link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <h1 style="font-size: 600%">CSE - 404</h1>
                <div class="title"><a href="/" style="color: #000000;text-decoration: none;">>>> Be right back <<<</a><br>After 5 sec, you redirect to the home page</div>
            </div>
        </div>
    </body>
</html>

<script>
    setTimeout(function () {
        window.location.href = "/";
    }, 5000);
</script>
