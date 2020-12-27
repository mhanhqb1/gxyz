@extends('layout.main')

@section('content')
<div class="row">
    <div class="col blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            {{ $video->title }}
        </h3>
        <div class="row mb-2">
            <div class="col video-stream-player">
                <link href="https://vjs.zencdn.net/7.10.2/video-js.css" rel="stylesheet" />
                <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
                <!-- <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script> -->
                <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;">
                    <video id="my-video-player" class="video-js vjs-default-skin vjs-fluid" style="width:100%;height:100%;position:absolute;left:0px;top:0px;overflow:hidden"></video>
                </div>
                <script>
                    window["248541naxhcu093105btzawb"] = {
                      zoneId: 1807350,
                      domain: "//nuevonoelmid.com",
                      options: {
                        insteadOfSelectors: ["#my-video-player"],
                        insteadOfPlayers: ["other"]
                      }
                    }
                </script>
        <script src="https://nuevonoelmid.com/zbs.kek.js"></script>
                <script src="https://vjs.zencdn.net/7.10.2/video.min.js"></script>
            </div>
        </div>
        <div class="row">
            <div class="col">
                @include('layout.ads_adsterra')
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
<script>
    $(document).ready(function(){
        var videoId = "{{ $video->source_id }}";
        $.ajax({
            url: "{{ route('home.getVideoStream') }}",
            method: 'POST',
            data: {
                video_id: {{ $video->id }},
                _token: "{{ csrf_token() }}"
            }
        }).done(function (response) {
            var res = JSON.parse(response);
            if (res.status == "OK") {
                var videoPlayer = videojs('my-video-player', {
                    autoplay: false,
                    controls: true,
                    preload: 'auto',
                    poster: '{{ $video->image }}',
                    sources: [{
                        type: "video/mp4",
                        src: res.data
                    }]
                });
            } else {
                alert('Video not found');
            }
        });
        
    });
</script>
@endsection