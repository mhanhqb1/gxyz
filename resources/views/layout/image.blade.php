<?php
$page = !empty($params['page']) ? $params['page'] + 1 : 2;
?>
<div class="col-6 col-xs-6 col-sm-3 col-image">
    <a href="{{ route('home.imageDetail', ['id' => $img->id]) }}">
        <div class="card box-shadow" style="background-image: url('{{ $img->url }}');">
            <div class="fb-like" data-href="{{ route('home.imageDetail', ['id' => $img->id]) }}" data-width="" data-layout="button" data-action="like" data-size="small" data-share="true"></div>
        </div>
    </a>
</div>