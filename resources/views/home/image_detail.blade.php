@extends('layout.main')

@section('content')
<div class="row">
    <div class="col blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Image {{ $id }}
        </h3>
        <div class="row mb-2">
            @include('layout.image', ['img' => $image])
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