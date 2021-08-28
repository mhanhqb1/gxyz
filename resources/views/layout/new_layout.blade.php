<?php
$topImage = !empty($pageImage) ? $pageImage : url('/') . '/imgs/1.jpg';
$jumbotronImage = url('/') . '/imgs/1.jpg';
$_siteName = 'SexyGirls69.xyz';
$_siteTitle = !empty($pageTitle) ? $pageTitle : 'Sexy Girl Collection - Hot girls, Sexy girls, Girls in bikini';
$_siteDescription = 'See the best looking girl pics, hot girls, cute girls, bikini girls, college girls, hot celebrities and more!';
$_currentUrl = url()->current();
$routeName = \Request::route()->getName();
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ $_siteDescription }}">
    <meta name="author" content="{{ $_siteName }}">

    <title>{{ $_siteTitle }}</title>

    <link rel="image_src" href="{{ $topImage }}" />
    <link rel="canonical" href="{{ $_currentUrl }}" />
    <meta property="og:site_name" content="{{ $_siteName }}">
    <meta property="og:image" content="{{ $topImage }}">
    <meta property="og:description" content="{{ $_siteDescription }}">
    <meta property="og:url" content="{{ url()->full() }}">
    <meta property="og:title" content="{{ $_siteTitle }}">
    <meta property="og:type" content="article">
    <meta name="twitter:title" content="{{ $_siteTitle }}">
    <meta name="twitter:description" content="{{ $_siteDescription }}">

    <!-- Custom styles for this template -->
    <link href="{{ asset('/css/new_style.css').'?'.time() }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <header>
            <nav class="nav">
                <ul>
                    <li class="nav-list {{ in_array($routeName, ['home.index']) ? 'active' : '' }}">
                        <a href="{{ route('home.index') }}" title="{{ $_siteName }}">
                            <span class="nav-list_icon">
                                <ion-icon name="home-outline"></ion-icon>
                            </span>
                            <span class="nav-list_title">Home</span>
                        </a>
                    </li>
                    <li class="nav-list {{ in_array($routeName, ['home.images']) ? 'active' : '' }}">
                        <a href="{{ route('home.images') }}" title="Hot Girl Images">
                            <span class="nav-list_icon">
                                <ion-icon name="flame-outline"></ion-icon>
                            </span>
                            <span class="nav-list_title">Hot Images</span>
                        </a>
                    </li>
                    <li class="nav-list {{ in_array($routeName, ['home.images18']) ? 'active' : '' }}">
                        <a href="{{ route('home.images18') }}" title="Sexy Girl Images">
                            <span class="nav-list_icon">
                                <ion-icon name="image-outline"></ion-icon>
                            </span>
                            <span class="nav-list_title">Sexy Images</span>
                        </a>
                    </li>
                    <li class="nav-list {{ in_array($routeName, ['home.videos']) ? 'active' : '' }}">
                        <a href="{{ route('home.videos') }}" title="Hot Girl Video">
                            <span class="nav-list_icon">
                                <ion-icon name="caret-forward-circle-outline"></ion-icon>
                            </span>
                            <span class="nav-list_title">Hot Videos</span>
                        </a>
                    </li>
                    <li class="nav-list {{ in_array($routeName, ['home.18videos']) ? 'active' : '' }}">
                        <a href="{{ route('home.18videos') }}" title="Sexy Girl Videos">
                            <span class="nav-list_icon">
                                <ion-icon name="videocam-outline"></ion-icon>
                            </span>
                            <span class="nav-list_title">Sexy Videos</span>
                        </a>
                    </li>
                    <!-- <li class="nav-list">
                        <a href="#">
                            <span class="nav-list_icon">
                                <ion-icon name="happy-outline"></ion-icon>
                            </span>
                            <span class="nav-list_title">Funny Videos</span>
                        </a>
                    </li> -->
                </ul>
            </nav>
            <div class="topbar">
                <a href="{{ url('') }}" class="logo">
                    {{ $_siteName }}
                </a>
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
            </div>
        </header>
        <div class="main">
            @yield('content')
            <footer class="footer">
                <p>© 2020 <a href="{{ url('') }}" title="{{ $_siteName }}">{{ $_siteName }}</a>. All right reserved.</p>
            </footer>
        </div>
    </div>

    <script>
        let toggle = document.querySelector('.toggle');
        let main = document.querySelector('.main');
        let nav = document.querySelector('.nav');
        let topbar = document.querySelector('.topbar');
        toggle.onclick = function() {
            toggle.classList.toggle('active');
            main.classList.toggle('active');
            nav.classList.toggle('active');
            topbar.classList.toggle('active');
        }
    </script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>
