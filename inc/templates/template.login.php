<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="copyright" content="© 2022 [%author%]" />
    <meta name="robots" content="all" />
    <meta name="robots" content="max-image-preview:standard" />
    <meta name="revisit-after" content="7 days" />
    <meta name="description" content="[%description%]">
    <meta name="author" content="[%author%]">
    <meta name="theme-color" />
    <meta name="msapplication-navbutton-color" />
    <meta name="msapplication-TileColor" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="msapplication-TileImage" content="[%icon%]">

    <link rel="icon" href="[%icon%]" sizes="32x32">
    <link rel="apple-touch-icon" href="[%icon%]">

    <title>[%title%]</title>

    <link rel="stylesheet" type="text/css" href="./app-assets/css/app.css">

    [%css%]
</head>

<body class="loginPage">
    <main role="main">
        <!-- Login Content -->
        <div class="container">
            <div class="row vh-100 justify-content-center align-items-center">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <img src="./app-assets/img/logo.svg" class="card-img-top" alt="Logo [%title%]" title="[%title%]">
                            [%include_content%]
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Login Content -->
    </main>

    <script src="./app-assets/js/app.js"></script>

    [%js%]
    [%sweetalert%]
</body>

</html>