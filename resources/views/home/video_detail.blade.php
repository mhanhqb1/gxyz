@extends('layout.new_layout')

@section('content')
<style>
#aaaa {
    position:relative;
    padding-bottom:56.25%;
    padding-top:30px;
    height:0;
    overflow:hidden;
}
#aaaa iframe, #aaaa object, #aaaa embed {
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
}
</style>
<script src="https://content.jwplatform.com/libraries/Jq6HIbgz.js"></script>
<link href="{{ asset('css/player.css') }}" rel="stylesheet"/>
<div class="row">
    <div class="col blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            {{ $video->title }}
        </h3>
        <div class="row mb-2">
            <div class="col video-stream-player">
                <div id='aaaa'>
                    <video id="my-video-player" class="video-js vjs-default-skin vjs-fluid"></video>
                </div>
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
                const playerInstance = jwplayer("my-video-player").setup({
                    playlist: [{
                        title: '{{ $video->title }}',
                        sources: [
                            {
                                "file": res.data,
                                "type": "video/mp4"
                            }
                        ],
                        image: '{{ $video->image }}'
                    }],
                    logo: {
                        file: "",
                        "link": "{{ route('home.index') }}",
                        "hide": "false",
                        "position": "top-right"
                    },
                    // "advertising": {
                    //     "client": "vast",
                    //     "schedule": ['.$ads.']
                    //     }
                    // }
                });
            } else {
                let html = '<iframe src="https://www.youtube.com/embed/{{ $video->source_id }}" frameborder="0" allowfullscreen></iframe>';
                $('#aaaa').html(html);
            }
        });

    });
</script>
@endsection
