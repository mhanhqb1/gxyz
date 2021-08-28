@extends('layout.new_layout')

@section('content')
<section class="top-posts">
    <div class="top-posts_left">
        @include('layout.card_item', ['item' => $idols[0]])
    </div>
    <div class="top-posts_right">
        <?php foreach ($idols as $k => $v) : ?>
            <?php
            if ($k == 0) {
                continue;
            }
            if ($k > 4) {
                break;
            }
            ?>
            @include('layout.card_item', ['item' => $v])
        <?php endforeach; ?>
    </div>
</section>

<div class="section-title">
    <h2>Hot Images</h2>
    <a href="{{ route('home.images18') }}" title="View more">View more</a>
</div>
<section class="lastest-posts">
    <?php foreach ($idols as $k => $v) : ?>
        <?php if ($k <= 3) { continue; } ?>
        @include('layout.card_item', ['item' => $v])
    <?php endforeach; ?>
</section>

<div class="section-title">
    <h2>Top Videos</h2>
    <a href="{{ route('home.18videos') }}" title="View more">View more</a>
</div>
<section class="lastest-posts">
    <?php foreach ($idols as $k => $v) : ?>
        @include('layout.card_item', ['item' => $v, 'is_video' => 1])
    <?php endforeach; ?>
</section>
@endsection
