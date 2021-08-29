@extends('layout.new_layout')

@section('content')
<div class="section-title">
    <h1>{{ $pageTitle }}</h1>
</div>
<section class="lastest-posts">
    <?php foreach ($data as $k => $v) : ?>
        @include('layout.card_item', ['item' => $v, 'is_video' => 1])
    <?php endforeach; ?>
</section>
<div class="pagination">
    <div class="col home-btn">
        <a href="{{ route($route).'?page='.($params['page'] + 1) }}" class="btn btn-viewmore">View more</a>
    </div>
</div>
@endsection
