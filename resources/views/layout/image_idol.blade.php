<?php
$class = !empty($small) ? 'col-3 col-image-small' : 'col-6 col-xs-6 col-sm-3';
?>
<div class="{{ $class }} col-image">
    <a href="{{ route('home.idolDetail', ['id' => $img->id]) }}">
        <div class="card box-shadow" style="background-image: url('{{ $img->image }}');">
            <!--<div class="fb-like" data-href="{{ route('home.idolDetail', ['id' => $img->id]) }}" data-width="" data-layout="button" data-action="like" data-size="small" data-share="true"></div>-->
        </div>
        <p class="text-center">Idol - {{ $img->id }}</p>
    </a>
</div>