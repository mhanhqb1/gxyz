<?php
$topImage = !empty($pageImage) ? $pageImage : url('/').'/imgs/1.jpg';
$jumbotronImage = url('/').'/imgs/1.jpg';
$_siteName = 'SexyGirlCollection.Com';
$_siteTitle = !empty($pageTitle) ? $pageTitle : 'Sexy Girl Collection';
$_siteDescription = 'Sexy Girl, Hot Girl, Cute Girl, Beautiful Girl';
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="$_siteDescription">
        <meta name="author" content="{{ $_siteName }}">

        <title>{{ $_siteTitle }}</title>

        <meta property="og:site_name" content="{{ $_siteName }}">
        <meta property="og:image" content="{{ $topImage }}">
        <meta property="og:description" content="{{ $_siteDescription }}">
        <meta property="og:url" content="{{ url()->full() }}">
        <meta property="og:title" content="{{ $_siteTitle }}">
        <meta property="og:type" content="article">
        <meta name="twitter:title" content="{{ $_siteTitle }}">
        <meta name="twitter:description" content="{{ $_siteDescription }}">
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="{{ asset('/css/bootstrap4.css') }}">

        <!-- Custom styles for this template -->
        <link href="{{ asset('/css/custom.css').'?'.time() }}" rel="stylesheet">
        
        <?php if (!empty(config('services.google')['ga_key'])): ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google')['ga_key'] }}"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', "<?php echo config('services.google')['ga_key']; ?>");
        </script>
        <?php endif; ?>
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

            <div class="jumbotron p-3 p-md-5 text-white rounded" style="background-image: url('{{ $jumbotronImage }}');">
                <div class="col-md-6 px-0">
                    <h1 class="display-4 font-italic">Sexy <br/>Beautiful <br/>Girl <br/>Collection</h1>
                </div>
            </div>
        </div>

        <main role="main" class="container">
            <?php if (!empty(config('app.ads_yllix')['pub_id'])): ?>
                <script type="text/javascript" src="https://sailif.com/bnr.php?section=General&pub={{ config('app.ads_yllix')['pub_id'] }}&format={{ config('app.ads_yllix')['format'] }}&ga=a"></script>
                <noscript><a href="https://yllix.com/publishers/{{ config('app.ads_yllix')['pub_id'] }}" target="_blank"><img src="//ylx-aff.advertica-cdn.com/pub/{{ config('app.ads_yllix')['format'] }}.png" style="border:none;margin:0;padding:0;vertical-align:baseline;" alt="ylliX - Online Advertising Network" /></a></noscript>
            <?php endif; ?>
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
        <script src="{{ asset('/js/jquery-slim.js') }}"></script>
        <script src="{{ asset('/js/popper.js') }}"></script>
        <script src="{{ asset('/js/bootstrap4.js') }}"></script>
        
        <?php if (!empty(config('app.ads_yllix')['pub_id'])): ?>
        <script type="text/javascript" src="https://buleor.com/tun.php?section=General&pt=8&pub={{ config('app.ads_yllix')['pub_id'] }}&ga=a"></script>
        <script type="text/javascript" src="https://buleor.com/pun.php?section=General&pt=6&pub={{ config('app.ads_yllix')['pub_id'] }}&ga=a"></script>
        <!--<script src="https://cdn-server.top/p/wl.js?pub={{ config('app.ads_yllix')['pub_id'] }}&ga=a"></script>-->
        <?php endif;?>
    </body>
</html>
