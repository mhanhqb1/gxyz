<div class="col">
    <div class="card box-shadow" onclick="window.location.href = '{{ route('home.videoDetail', ['id' => $video->id]) }}';" style="position: relative">
        <div class="g-youtube"></div>
        <img class="g-image" src="{{ $video->image }}" alt="SBGC video {{ $video->id }}">
        <span class="s-video-title">Click to play video - {{ $video->title }}</span>
        <div class="fb-like" data-href="{{ route('home.videoDetail', ['id' => $video->id]) }}" data-width="" data-layout="standard" data-action="like" data-size="small" data-share="true"></div>
    </div>
</div>