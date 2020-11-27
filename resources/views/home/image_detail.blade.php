@extends('layout.main')

@section('content')
<div class="row">
    <div class="col blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Image {{ $id }}
        </h3>
        <div class="row">
            <div class="col">
                @include('layout.ads')
            </div>
        </div>
        <div class="row mb-2">
            <div class="col">
                <div class="card box-shadow">
                    <img class="g-image" src="{{ $image->url }}" alt="Sexy girl {{ $id }}">
                    <div class="fb-like" data-href="{{ route('home.imageDetail', ['id' => $id]) }}" data-width="" data-layout="button" data-action="like" data-size="small" data-share="true"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <h3 class="pb-3 mb-4 font-italic border-bottom">
                Related Images
            </h3>
            <?php foreach ($related as $v): ?>
            @include('layout.image', ['img' => $v])
            <?php endforeach; ?>
        </div>
        <div class="row">
            <div class="col home-btn">
                <a href="{{ url('/images') }}" class="btn btn-outline-primary">View more</a>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="fb-comments" data-width="100%" data-href="{{ route('home.imageDetail', ['id' => $id]) }}" data-numposts="10"></div>
            </div>
        </div>
    </div>

</div><!-- /.row -->
@endsection