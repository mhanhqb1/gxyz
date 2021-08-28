@extends('layout.new_layout')

@section('content')
<div class="section-title">
    <h2>Top Videos</h2>
</div>
<section class="lastest-posts">
    <?php foreach ($data as $k => $v) : ?>
        @include('layout.card_item', ['item' => $v])
    <?php endforeach; ?>
</section>
<div class="pagination">
    <div class="col home-btn">
        <a href="{{ route('home.videos').'?page='.($params['page'] + 1) }}" class="btn btn-viewmore">View more</a>
    </div>
</div>
@endsection
