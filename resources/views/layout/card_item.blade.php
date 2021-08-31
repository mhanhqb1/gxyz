<?php
$url = route('home.imageDetail', ['id' => $item->id]);
$image = $item->url;
$title = !empty($item->name) ? $item->name : 'Sexy Girl '.$item->id;
$is_video = !empty($item->type) && $item->type == 1 ? 1 : 0;
if (!empty($is_video)) {
    $image = $item->image;
    $title = !empty($item->title) ? $item->title : 'Sexy Girl Video '.$item->id;
    $url = route('home.videoDetail', ['id' => $item->id, 'slug' => !empty($item->slug) ? $item->slug : 'sexy-girl-'.$item->id]);
}
?>

<article class="card">
    <a href="{{ $url }}" title="{{ $title }}">
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
