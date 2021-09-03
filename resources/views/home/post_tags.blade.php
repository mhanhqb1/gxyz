@extends('layout.new_layout')

@section('content')
<div class="section-title">
    <h1>{{ $pageTitle }}</h1>
</div>
<section class="lastest-posts">
    <div class="post-detail_left">
        <?php foreach ($posts as $k => $v) : ?>
            @include('layout.card_item', ['item' => $v])
        <?php endforeach; ?>
        <div class="pagination">
            <div class="col home-btn">
                <a href="{{ route('home.postTags', ['tag' => urlencode($tag)]).'?page='.($params['page'] + 1) }}" class="btn btn-viewmore">View more</a>
            </div>
        </div>
    </div>
    <div class="post-detail_right">
        @include('layout.right_column')
    </div>
</section>

@endsection
