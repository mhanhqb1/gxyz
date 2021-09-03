@extends('layout.new_layout')

@section('content')
<section class="post-detail post-image-detail">
    <div class="post-detail_left">
        <nav class="breadcrumb">
            <a href="{{ route('home.index') }}" title="SexyGirls69.xyz">Home page</a>
            <span>></span>
            <?php if (!empty($post->is_18)): ?>
                <a href="{{ route('home.images18') }}" title="Sexy girl videos">Sexy girl images</a>
            <?php else: ?>
                <a href="{{ route('home.images') }}" title="Hot girl videos">Hot girl images</a>
            <?php endif; ?>
        </nav>
        <h1 class="post-title">
            {{ $post->title }}
        </h1>
        <div class="post-main-image">
            <a href="{{ route('home.imageView', ['img' => $post->image]) }}" title="{{ $post->title }}" target="_blank">
                <img src="{{ $post->image }}" alt="{{ $post->title }}"/>
            </a>
        </div>
        @if (!empty($postImages))
        <div class="post-thumb-images">
            @foreach ($postImages as $v)
            <a href="{{ route('home.imageView', ['img' => $v]) }}" title="{{ $post->title }}" target="_blank">
                <img src="{{ $v }}" alt="{{ $post->title }}"/>
            </a>
            @endforeach
        </div>
        @endif
        <div class="section-title">
            <h2>You might like</h2>
        </div>
        <div class="lastest-posts">
            <?php foreach ($related as $k => $v) : ?>
                @include('layout.card_item', ['item' => $v])
            <?php endforeach; ?>
        </div>
    </div>
    <div class="post-detail_right">
        @include('layout.right_column')
    </div>
</section>
@endsection
