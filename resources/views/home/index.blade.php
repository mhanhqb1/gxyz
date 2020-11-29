@extends('layout.main')

@section('content')
<div class="row">
    <div class="col blog-main">
        <div class="row mb-2">
            <?php if (!empty($images)): ?>
            <?php foreach ($images as $k => $img): ?>
                @include('layout.image', ['img' => $img])
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col">
                @include('layout.ads_adsterra')
            </div>
        </div>
        <div class="row">
            <div class="col home-btn">
                <a href="{{ url('/images?page=2') }}" class="btn btn-outline-primary">View more</a>
            </div>
        </div>
    </div>
</div><!-- /.row -->
<div class="row">
    <div class="col blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Top Videos
        </h3>
        <div class="row mb-2">
            <?php if (!empty($videos)): ?>
            <?php foreach ($videos as $v): ?>
                @include('layout.video', ['video' => $v])
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col home-btn">
                <a href="{{ url('/videos') }}" class="btn btn-outline-primary">View more</a>
            </div>
        </div>
    </div>
</div>
@endsection