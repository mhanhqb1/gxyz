<?php
$streamUrl = "http://sexygirls69.xyz/getVideoStream";
// $streamUrl = 'http://127.0.0.1:8000/getVideoStream';
if (strpos($_SERVER['HTTP_HOST'], 'www') !== false) {
    $streamUrl = "http://www.sexygirls69.xyz/getVideoStream";
}
?>

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
<section class="post-detail">
    <div class="post-detail_left">
        <nav class="breadcrumb">
            <a href="{{ route('home.index') }}" title="SexyGirls69.xyz">Home page</a>
            <span>></span>
            <?php if (!empty($video->is_18)): ?>
                <a href="{{ route('home.18videos') }}" title="Sexy girl videos">Sexy girl videos</a>
            <?php else: ?>
                <a href="{{ route('home.videos') }}" title="Hot girl videos">Hot girl videos</a>
            <?php endif; ?>
        </nav>
        <h1 class="post-title">
            {{ $video->title }}
        </h1>
        <div class="video-stream-player">
            <div id='aaaa'>
                <video id="my-video-player" class="video-js vjs-default-skin vjs-fluid"></video>
            </div>
            <div id='bbb' style="margin-top: 20px;"></div>
        </div>
        <!-- <div class="post-description">
            {{ $video->description }}
        </div> -->
        <div class="section-title">
            <h2>Related Videos</h2>
        </div>
        <div class="lastest-posts">
            <?php foreach ($related as $k => $v) : ?>
                @include('layout.card_item', ['item' => $v, 'is_video' => 1])
            <?php endforeach; ?>
        </div>
    </div>
    <div class="post-detail_right">

    </div>

</section>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
    $(document).ready(function(){
        var videoId = "{{ $video->source_id }}";
        $.ajax({
            url: "{{ $streamUrl }}",
            method: 'POST',
            data: {
                video_id: {{ $video->id }},
                _token: "{{ csrf_token() }}"
            }
        }).done(function (response) {
            var res = JSON.parse(response);
            if (res.status == "OK") {
                if (typeof res.source != 'undefined') {
                    var bb = "<a href='"+res.data+"' target='_blank'>Server Video 2</a>";
                    $('#bbb').html(bb);
                    let html = '<iframe src="'+res.data.replace('https:', '').replace('http:', '')+'" frameborder="0" allowfullscreen></iframe>';
                    $('#bbb').html(html);
                    // newwindow = window.open('https://v.imgccc.com','popup','width=800,height=600');
                    // newwindow.location.href = res.data;
                    // console.log(1);
                    // newwindow.location.reload();
                }
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
