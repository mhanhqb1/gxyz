<?php
$page = !empty($params['page']) ? $params['page'] + 1 : 2;
?>
<div class="col">
    <div class="card box-shadow">
        <a href="{{ url('/images?page=').$page }}" class="btn btn-danger btn-image">View more</a>
        <img class="g-image" src="{{ $img->url }}" alt="SBGC image {{ $img->id }}">
        <div class="fb-like" data-href="{{ route('home.imageDetail', ['id' => $img->id]) }}" data-width="" data-layout="button" data-action="like" data-size="small" data-share="true"></div>
    </div>
</div>