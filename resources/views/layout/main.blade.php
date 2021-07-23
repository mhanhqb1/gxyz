<?php
$topImage = !empty($pageImage) ? $pageImage : url('/') . '/imgs/1.jpg';
$jumbotronImage = url('/') . '/imgs/1.jpg';
$_siteName = 'SexyGirlCollection.Com';
$_siteTitle = !empty($pageTitle) ? $pageTitle : 'Sexy Girl Collection - Hot girls, Sexy girls, Girls in bikini';
$_siteDescription = 'See the best looking girl pics, hot girls, cute girls, bikini girls, college girls, hot celebrities and more!';
$_currentUrl = url()->current();
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
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <!-- Custom styles for this template -->
        <link href="{{ asset('/css/custom.css').'?'.time() }}" rel="stylesheet">

        <?php if (!empty(config('services.google')['ga_key'])): ?>
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

        <?php if (!empty(config('services.facebook')['pixel_id'])): ?>
            <!-- Facebook Pixel Code -->
            <script>
                !function (f, b, e, v, n, t, s)
                {
                    if (f.fbq)
                        return;
                    n = f.fbq = function () {
                        n.callMethod ?
                                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                    };
                    if (!f._fbq)
                        f._fbq = n;
                    n.push = n;
                    n.loaded = !0;
                    n.version = '2.0';
                    n.queue = [];
                    t = b.createElement(e);
                    t.async = !0;
                    t.src = v;
                    s = b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t, s)
                }(window, document, 'script',
                        'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', "{{ config('services.facebook')['pixel_id'] }}");
                fbq('track', 'PageView');
            </script>
            <noscript>
                <img height="1" width="1" src="https://www.facebook.com/tr?id={{ config('services.facebook')['pixel_id'] }}&ev=PageView&noscript=1"/>
            </noscript>
            <!-- End Facebook Pixel Code -->
        <?php endif; ?>
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
</head>

<body>
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v9.0&appId=245530479242476&autoLogAppEvents=1" nonce="tRcnz9VF"></script>

    <div class="container">
        <header class="blog-header py-3">
            <div class="row flex-nowrap justify-content-between align-items-center">
                <div class="col text-center text-bold">
                    <a class="blog-header-logo text-dark" href="{{ url('/') }}">SBGC</a>
                </div>
            </div>
        </header>

        <div class="nav-scroller py-1 mb-2">
            <nav class="nav d-flex justify-content-between">
                <a class="p-2 text-muted text-center" href="{{ url('/') }}">Home</a>
                <a class="p-2 text-muted text-center" href="{{ url('/images') }}">Images</a>
                <a class="p-2 text-muted text-center" href="{{ url('/videos') }}">Videos</a>
                <a class="p-2 text-muted text-center" href="{{ url('/movies') }}">Movies</a>
                <!--                    <a class="p-2 text-muted text-center" href="{{ url('/18images') }}">18+ Images</a>
                                    <a class="p-2 text-muted text-center" href="{{ url('/18movies') }}">18+ Movies</a>-->
            </nav>
        </div>

<!--        <div class="jumbotron p-3 p-md-5 text-white rounded" style="background-image: url('{{ $jumbotronImage }}');">
            <div class="col-md-6 px-0">
                <h1 class="display-4 font-italic">Sexy <br/>Beautiful <br/>Girl <br/>Collection</h1>
            </div>
        </div>-->
    </div>

    <main role="main" class="container">
        @include('layout.ads_top')
        @yield('content')
    </main><!-- /.container -->

    <footer class="blog-footer">
        <p>© 2020 <a href="{{ url('') }}">SexyGirlCollection.Com</a>. All right reserved.</p>
        <p>
            <a href="#">Back to top</a>
        </p>
        <!--            <div class="adsHelp">
                        <span>Please help me subscribe and click ads on the top</span>
                        <img src="{{ asset('imgs/thanks.gif') }}"/>
                    </div>-->
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="{{ asset('/js/custom.js').'?'.time() }}"></script>
    
    <script>
        $(document).ready(function(){
           
        });
    </script>
    @include('layout.ads_footer')
</body>
</html>
