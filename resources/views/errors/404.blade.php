<?php
$topImage = !empty($pageImage) ? $pageImage : url('/') . '/imgs/1.jpg';
$jumbotronImage = url('/') . '/imgs/1.jpg';
$_siteName = 'SexyGirls69.xyz';
$_siteTitle = !empty($pageTitle) ? $pageTitle : '404 Page';
$_siteDescription = 'See the best looking girl pics, sexy girl, hot girls, cute girls, bikini girls, college girls, hot celebrities and more!';
$_siteKeywords = 'sexy girl, hot girl, bikini girl, hot girl sexy video, hot sexy girl, sexy girl xxx';
$_currentUrl = url()->current();
$routeName = '404';
$videos = App\Models\Video::inRandomOrder()->where('is_18',1)->limit(4)->get();
$images = App\Models\Image::inRandomOrder()->where('is_18', 1)->limit(4)->get();
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ $_siteDescription }}">
    <meta name="author" content="{{ $_siteName }}">
    <meta name='robots' content='index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' />

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

    <?php if (!empty(config('services.google')['ga_key'])) : ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google')['ga_key'] }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', "<?php echo config('services.google')['ga_key']; ?>");
        </script>
    <?php endif; ?>
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
            <section class="page-404">
                <h1>404</h1>
                <p>Oops, the page you're looking for does not exist</p>
            </section>
            <div class="pagination">
                <div class="col home-btn">
                    <a href="{{ route('home.index') }}" class="btn btn-viewmore">Home page</a>
                </div>
            </div>
            <div class="section-title">
                <h2>Hot Images</h2>
                <a href="{{ route('home.images18') }}" title="View more">View more</a>
            </div>
            <section class="lastest-posts">
                <?php foreach ($images as $k => $v) : ?>
                    @include('layout.card_item', ['item' => $v])
                <?php endforeach; ?>
            </section>

            <div class="section-title">
                <h2>Hot Videos</h2>
                <a href="{{ route('home.18videos') }}" title="View more">View more</a>
            </div>
            <section class="lastest-posts">
                <?php foreach ($videos as $k => $v) : ?>
                    @include('layout.card_item', ['item' => $v, 'is_video' => 1])
                <?php endforeach; ?>
            </section>
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

