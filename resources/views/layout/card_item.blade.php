<?php
$url = route('home.imageDetail', ['id' => $item->id]);
$image = $item->url;
$title = !empty($item->name) ? $item->name : 'Sexy Girl '.$item->id;
if (!empty($is_video)) {
    $image = $item->image;
    $url = route('home.videoDetail', ['id' => $item->id]);
}
?>

<article class="card">
    <a href="{{ $url }}">
        @if (!empty($is_video))
        <div class="g-youtube"></div>
        @endif
        <img src="{{ $image }}" alt="{{ $item->name }}" title="{{ $item->name }}"/>
        <div class="card-info">
            <h3>{{ $title }}</h3>
            <span>
                <ion-icon name="flame-outline"></ion-icon>
                Sexy Girl
            </span>
            <!-- <span>
                <ion-icon name="flame-outline"></ion-icon>
                {{ date('Y-m-d', strtotime($item->updated_at)) }}
            </span> -->
        </div>
    </a>
</article>
