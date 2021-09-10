<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@if($page == 1 && empty($tags))
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ $dateNow->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('home.videos') }}</loc>
        <lastmod>{{ $dateNow->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('home.18videos') }}</loc>
        <lastmod>{{ $dateNow->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('home.images') }}</loc>
        <lastmod>{{ $dateNow->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
@endif
@if(!empty($posts) && !$posts->isEmpty())
    @foreach ($posts as $post)
        <url>
            <loc>{{ route(!empty($post->type) ? 'home.videoDetail' : 'home.postDetail', ['slug' => $post->slug, 'id' => $post->id]) }}</loc>
            <lastmod>{{ $post->created_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>always</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
@endif
@if(!empty($tags) && !$tags->isEmpty())
    @foreach ($tags as $tag)
        <url>
            <loc>{{ route('home.postTags', ['tag' => urlencode(trim($tag->name))]) }}</loc>
            <lastmod>{{ $tag->created_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>always</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
@endif
</urlset>
