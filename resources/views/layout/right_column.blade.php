<?php
$tags = App\Models\PostTag::limit(25)->orderBy('count', 'desc')->where('status', 1)->get();
?>
<div class="widget">
    <div class="widget-title">
        <h2>Popular Tags</h2>
    </div>
    <div class="widget-content post-tags">
        @foreach($tags as $t)
        <a href="{{ route('home.postTags', ['tag' => urlencode($t->name)]) }}" title="Sexy girl - {{ $t->name }}">
            {{ $t->name }}
        </a>
        @endforeach
    </div>
</div>
