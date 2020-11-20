<?php
$topImage = 'images/1.jpg';
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Cover Template for Bootstrap</title>

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


        <!-- Custom styles for this template -->
        <link href="css/custom.css" rel="stylesheet">
    </head>

    <body>
        <div class="container">
            <header class="blog-header py-3">
                <div class="row flex-nowrap justify-content-between align-items-center">
                    <div class="col text-center">
                        <a class="blog-header-logo text-dark" href="#">Large</a>
                    </div>
                </div>
            </header>

            <div class="nav-scroller py-1 mb-2">
                <nav class="nav d-flex justify-content-between">
                    <a class="p-2 text-muted text-center" href="#">Home</a>
                    <a class="p-2 text-muted text-center" href="#">Images</a>
                    <a class="p-2 text-muted text-center" href="#">Videos</a>
                    <a class="p-2 text-muted text-center" href="#">Movies</a>
                    <a class="p-2 text-muted text-center" href="#">Hot</a>
                    <a class="p-2 text-muted text-center" href="#">Most view</a>
                </nav>
            </div>

            <div class="jumbotron p-3 p-md-5 text-white rounded" style="background-image: url('{{ $topImage }}');">
                <div class="col-md-6 px-0">
                    <h1 class="display-4 font-italic">Saaa <br/>beautiful <br/>giil <br/>collection</h1>
                </div>
            </div>
        </div>

        <main role="main" class="container">
            <div class="row">
                <div class="col blog-main">
                    <h3 class="pb-3 mb-4 font-italic border-bottom">
                        Top images
                    </h3>
                    <div class="row mb-2">
                        <?php for ($i=1; $i <= 8; $i++): ?>
                        <div class="col">
                            <div class="card box-shadow">
                                <img class="g-image" src="images/{{ $i }}.jpg" alt="Card image cap">
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>

            </div><!-- /.row -->

        </main><!-- /.container -->

        <footer class="blog-footer">
            <p>Blog template built for <a href="https://getbootstrap.com/">Bootstrap</a> by <a href="https://twitter.com/mdo">@mdo</a>.</p>
            <p>
                <a href="#">Back to top</a>
            </p>
        </footer>


        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>
