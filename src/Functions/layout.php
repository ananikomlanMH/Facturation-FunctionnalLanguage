<?php

function _head(array $style, string $title = "G-FACTURATION", string $lang = "fr"): string
{

    $style_link = '';
    foreach ($style as $link) {
        $style_link .= <<<STYLE
            <link rel="stylesheet" href="$link"/>
        STYLE;

    }
    return <<<HTML

    <!doctype html>
    <html lang="$lang">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>$title</title>
        <link rel="shortcut icon" type="image/png" href="/assets/images/logos/favicon.png"/>
        $style_link
    </head>
    
    HTML;
}

function _body(string $footer = '', string $nav = '', string $content = ''): string
{

    $notification = _getNotification();

    return <<<HTML

<body>
<!--  Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
     data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
        <!-- Sidebar scroll-->
        <div>
            <div class="brand-logo d-flex align-items-center justify-content-between">
                <a href="" class="text-nowrap logo-img">
                   <img src="/assets/images/logos/logo.png" width="210" alt=""/>
                </a>
                <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                    <i class="ti ti-x fs-8"></i>
                </div>
            </div>
            <!-- Sidebar navigation-->
            $nav
            <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
        <!--  Header Start -->
        <header class="app-header">
            <nav class="navbar navbar-expand-lg navbar-light">
                <ul class="navbar-nav">
                    <li class="nav-item d-block d-xl-none">
                        <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                            <i class="ti ti-bell-ringing"></i>
                            <div class="notification bg-primary rounded-circle"></div>
                        </a>
                    </li>
                </ul>
                <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                    <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">

                        <li class="nav-item dropdown">
                            <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">
                                <img src="../assets/images/profile/user-1.jpg" alt="" width="35" height="35"
                                     class="rounded-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                                 aria-labelledby="drop2">
                                <div class="message-body">
                                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                        <i class="ti ti-user fs-6"></i>
                                        <p class="mb-0 fs-3">Mon Profile</p>
                                    </a>
                                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                        <i class="ti ti-mail fs-6"></i>
                                        <p class="mb-0 fs-3">Mon Compte</p>
                                    </a>
                                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                        <i class="ti ti-list-check fs-6"></i>
                                        <p class="mb-0 fs-3">Mes Tâches</p>
                                    </a>
                                    <a href="{{ route('login') }}"
                                       class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!--  Header End -->
        <div class="container-fluid">
            $notification
            $content
        </div>

    </div>
</div>

$footer
</body>
</html>
HTML;
}

function _nav(array $links): string
{

    $nav = '';
    foreach ($links as $key => $link) {
        $sub_nav = '';
        foreach ($link as $item) {
            $title = $item['title'];
            $_link = '?page=' . $item['link'];

            $active = str_contains(($_GET['page'] ?? ''), $item['link']) ? 'active' : '';

            $sub_nav .= <<< HTML
                <a class="sidebar-link $active" href="$_link" aria-expanded="false">
                    <span>
                      <i class="ti ti-layout-dashboard"></i>
                    </span>
                    <span class="hide-menu">$title</span>
                </a>
HTML;

        }
        $nav .= <<<HTML
            <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">$key</span>
            </li>
            <li class="sidebar-item">
                $sub_nav
            </li>
HTML;

    }
    return <<<HTML
            <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                <ul id="sidebarnav">
                  $nav  
                </ul>
            </nav>
HTML;

}

function _footer(array $js): string
{
    $js_link = '';
    foreach ($js as $link) {
        $js_link .= <<<JS
        <script src="$link"></script>
JS;
    }
    return $js_link;
}

function _init_layout(string $content): void
{
    $head = _head([
        '/assets/css/styles.css',
        '/assets/css/addStyle.css',
        '/assets/css/jquery-confirm.min.min.css',
        '/assets/css/tom-select.min.css',
    ], "G-FACTURATION", "fr");

    $body = _body(_footer([
        "/assets/libs/jquery/dist/jquery.min.js",
        "/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js",
        "/assets/js/sidebarmenu.js",
        "/assets/js/app.min.js",
        "/assets/js/facture.js",
        "/assets/libs/apexcharts/dist/apexcharts.min.js",
        "/assets/libs/simplebar/dist/simplebar.js",
        "/assets/js/dashboard.js",
        "/assets/js/jquery-confirm.min.js",
        "/assets/js/tom-select.complete.js",
        "/assets/js/add.js",
    ]), _nav([
        'Home' => [
            [
                'title' => 'Dashboard',
                'link' => 'dashboard',
            ]
        ],
        'FACTURE' => [
            [
                'title' => 'Factures',
                'link' => 'factures',
            ]
        ],
        'DEPENSE' => [
            [
                'title' => 'Depense',
                'link' => 'depense',
            ]
        ],
        'Configurations' => [
            [
                'title' => 'Clients',
                'link' => 'clients',
            ],
            [
                'title' => 'Produits',
                'link' => 'produits',
            ]
        ]
    ]), $content);

    echo $head . $body;
}

function _getNotification(): string
{
    $message = '';
    $message_type = null;

    if (!empty($_GET['notify_message'])) {
        $message = $_GET['notify_message'];
        $message_type = $_GET['type'];
        unset($_GET['notify_message'], $_GET['type']);
    } else {
        return $message;
    }

    return <<<HTML
        <div class="alert alert-$message_type alert-dismissible fade show" role="alert">
            $message
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
HTML;

}