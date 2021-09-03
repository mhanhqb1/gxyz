<?php
$url = route('home.postDetail', ['id' => $item->id, 'slug' => !empty($item->slug) ? $item->slug : 'sexy-girl-'.$item->id]);
$image = $item->image;
$title = !empty($item->title) ? $item->title : 'Sexy Girl '.$item->id;
$is_video = !empty($item->type) && $item->type == 1 ? 1 : 0;
if (!empty($is_video)) {
    $url = route('home.videoDetail', ['id' => $item->id, 'slug' => !empty($item->slug) ? $item->slug : 'sexy-girl-'.$item->id]);
}
?>

<li>
    <a href="{{ $url }}" title="{{ $title }}">
        <img src="{{ $image }}" alt="{{ $title }}"/>
        {{ $title }}
    </a>
</li>
