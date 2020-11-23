@extends('layout.main')

@section('content')
<div class="row">
    <div class="col blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            {{ $video->title }}
        </h3>
        <div class="row mb-2">
            <div class="col">
                <iframe width="100%" height="450px" src="https://www.youtube.com/embed/<?php echo $video->youtube_id; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col">
                <div class="fb-like" data-href="{{ route('home.videoDetail', ['id' => $id]) }}" data-width="" data-layout="standard" data-action="like" data-size="small" data-share="true"></div>
            </div>
        </div>
        <div class="row">
            <div class="col home-btn">
                <a href="{{ url('/videos') }}" class="btn btn-outline-primary">View more</a>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="fb-comments" data-width="100%" data-href="{{ route('home.videoDetail', ['id' => $id]) }}" data-numposts="10"></div>
            </div>
        </div>
    </div>

</div><!-- /.row -->
<?php if (!empty(config('app.ads_yllix')['pub_id'])): ?>
<!--<script type="text/javascript" src="https://buleor.com/mobile_redir.php?section=General&pub={{ config('app.ads_yllix')['pub_id'] }}&ga=a"></script>-->
<?php endif; ?>
@endsection