<div class="col">
    <div class="card box-shadow">
        <img class="g-image" src="{{ $img->url }}" alt="SBGC image {{ $img->id }}">
        <div class="fb-like" data-href="{{ route('home.imageDetail', ['id' => $img->id]) }}" data-width="" data-layout="standard" data-action="like" data-size="small" data-share="true"></div>
    </div>
</div>