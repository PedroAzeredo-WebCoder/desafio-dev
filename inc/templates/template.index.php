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

<body class="vertical-layout vertical-menu-modern navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="">
    <!-- BEGIN: Header-->
    [%include_topbar%]
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item me-auto d-flex align-items-center">
                    <!-- <a class="navbar-brand" href="index.php"> -->
                    <h2 class="brand-text mb-0" style="margin-left: 0.5rem;">Menu</h2>
                    <!-- </a> -->
                </li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4 fa-solid fa-xmark"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4 text-primary fa-solid fa-circle-dot"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        [%include_sidebar%]
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">[%title_page%]</h2>
                            <div class="breadcrumb-wrapper">
                                [%breadcrumb%]
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- BEGIN: Cards -->
                [%include_content%]
                <!--/ END: Cards -->
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- Footer -->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0">
            <span class="float-md-start d-block d-md-inline-block mt-25">
                COPYRIGHT &copy; 2022 Desenvolvido por <a class="ms-25" href="https://www.linkedin.com/in/pedro-azeredo-dev/" target="_blank">Pedro Azeredo</a></a>
            </span>
        </p>
    </footer>
    <!-- Footer -->
    <script src="./app-assets/js/app.js"></script>

    [%js%]
    [%sweetalert%]
</body>

</html>