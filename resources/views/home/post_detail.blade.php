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
        @if (!empty($related))
        <ul class="related-posts">
            @foreach ($related as $k => $p)
                <?php if ($k > 3){ break; } ?>
                @include('layout.related_item', ['item' => $p])
            @endforeach
        </ul>
        @endif
        <div class="post-main-image">
            <a href="{{ route('home.imageView', ['img' => $post->image]) }}" title="{{ $post->title }}" target="_blank">
                <img src="{{ $post->image }}" alt="{{ $post->title }}"/>
            </a>
        </div>
        @if (!empty($post->tags))
        <div class="post-description">
            {{ $post->tags }}
        </div>
        <div class="post-tags post-detail-tags">
            <?php $tags = explode(',', $post->tags); ?>
            @foreach($tags as $t)
            <a href="{{ route('home.postTags', ['tag' => urlencode(trim($t))]) }}" title="Sexy girl - {{ trim($t) }}">
                {{ trim($t) }}
            </a>
            @endforeach
        </div>
        @endif
        @if (!empty($postImages))
        <div class="post-thumb-images" style="margin-top: 24px;">
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
            <?php foreach ($related as $k => $v) :
                if ($k <= 3) {
                    continue;
                }
            ?>
                @include('layout.card_item', ['item' => $v])
            <?php endforeach; ?>
        </div>

        <div class="section-title">
            <h2>Lastest Posts</h2>
        </div>
        <div class="lastest-posts">
            <?php foreach ($lastestPosts as $k => $v) : ?>
                @include('layout.card_item', ['item' => $v])
            <?php endforeach; ?>
        </div>
    </div>
    <div class="post-detail_right">
        @include('layout.right_column')
    </div>
</section>
@endsection
