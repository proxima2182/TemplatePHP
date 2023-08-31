<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width"/>
    <title>Template</title>

    <link rel="stylesheet" type="text/css" href="/asset/font/fonts.css"/>
    <link rel="stylesheet" type="text/css" href="/asset/css/default.css"/>
    <link rel="stylesheet" type="text/css" href="/asset/css/admin/style.css"/>
    <style>
        body {
            text-align: center;
            min-height: 400px;
            min-width: 300px;
        }

        .error-wrap {
            margin-top: -100px;
            display: inline-block;
            vertical-align: middle;
            text-align: center;
            line-height: normal;
        }

        .error-wrap .code {
            color: #ddd;
            font-weight: 600;
            font-size: 60px;
            padding-bottom: 5px;
        }

        .error-wrap .code-wrap {
            position: relative;
        }

        .error-wrap .code-wrap:after {
            content: "";
            display: inline-block;
            width: 120px;
            height: 120px;
            border: 1px solid #ddd;
            rotate: 45deg;
            position: absolute;
            margin-top: -60px;
            margin-left: -60px;
            top: 50%;
            left: 50%;
        }

        .error-wrap .text-wrap {
            margin-top: 60px;
        }

        .error-wrap .text-wrap p {
            max-width: 450px;
            color: #888;
            font-size: 16px;
            font-weight: 200;
            white-space: pre-wrap;
            line-height: normal;
        }

        .error-wrap .text-wrap .title {
            color: #666;
            font-size: 20px;
            /*font-weight: 400;*/
        }

        .error-wrap .button {
            margin-top: 40px;
            padding: 10px 20px;
            border: 1px solid #888;
            color: #888;
        }

        .error-wrap .button:hover {
            background: #efefef;
        }
    </style>

    <script type="text/javascript" src="/asset/js/library/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="/asset/js/library/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript">
        function resizeWindow() {
            $('body').css({
                'line-height': `${window.innerHeight}px`,
            })
        }

        $(document).ready(function () {
            resizeWindow();
            addEventListener("resize", (event) => {
                resizeWindow();
            });
        });
    </script>
</head>
<body>
<div class="error-wrap">
    <div class="code-wrap">
        <p class="code"><?= $code ?></p>
    </div>
    <div class="text-wrap">
        <p class="title"><?= $title ?></p>
        <p class="message"><?= $message ?></p>
    </div>

    <a href="/" class="button white">go back to web page</a>
</div>
</body>

