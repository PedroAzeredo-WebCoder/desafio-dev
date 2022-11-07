<!DOCTYPE html>
<html lang="pt-BR" data-lt-installed="true">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=6, viewport-fit=cover" />
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

<body>
    <main role="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2 bg-gray">
                    [%include_sidebar%]
                </div>
                <div class="col-lg-10">
                    [%include_topbar%]

                    <div class="container">
                        <div class="card">
                            <h2 class="card-title mb-0">[%title_page%]</h2>
                            [%breadcrumb%]
                        </div>

                        [%include_content%]
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <span>
            COPYRIGHT &copy; 2022 Desenvolvido por <a href="https://www.linkedin.com/in/pedro-azeredo-dev/" target="_blank">Pedro Azeredo</a>
        </span>
    </footer>

    <script src="./app-assets/js/app.js"></script>

    [%js%]
    [%sweetalert%]
</body>

</html>