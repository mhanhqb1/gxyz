<?php
$topImage = 'imgs/1.jpg';
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name='robots' content='noindex, nofollow' />

        <title>Cover Template for Bootstrap</title>

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">

        <!-- Custom styles for this template -->
        <link href="{{ asset('/css/admin.css') }}" rel="stylesheet">
    </head>

    <body>
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
                    <a class="p-2 text-muted text-center" href="{{ url('/checkImages') }}">CheckImages</a>
                    <a class="p-2 text-muted text-center" href="{{ url('/checkVideos') }}">checkVideos</a>
                    <a class="p-2 text-muted text-center" href="{{ url('/addSource') }}">addSource</a>
                    <a class="p-2 text-muted text-center" href="{{ url('/s-migrate') }}">Migrate</a>
                    <a class="p-2 text-muted text-center" href="{{ url('/s-config-cache') }}">Clear cache</a>
                    <a class="p-2 text-muted text-center" href="{{ url('/s-optimize') }}">Optimize</a>
                </nav>
            </div>
        </div>

        <main role="main" class="container">
            @yield('content')
        </main><!-- /.container -->

        <script>
            var BASE_URL = "{{ url('') }}";
            var CSRF_TOKEN = "{{ csrf_token() }}";
        </script>
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('/js/admin.js') }}"></script>
    </body>
</html>
