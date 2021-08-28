@extends('layout.new_layout')

@section('content')
<div class="section-title">
    <h1>{{ $pageTitle }}</h1>
</div>
<section class="lastest-posts">
    <?php foreach ($images as $k => $v) : ?>
        @include('layout.card_item', ['item' => $v])
    <?php endforeach; ?>
</section>
<div class="pagination">
    <div class="col home-btn">
        <a href="{{ route('home.images').'?page='.($params['page'] + 1) }}" class="btn btn-viewmore">View more</a>
    </div>
</div>
@endsection
