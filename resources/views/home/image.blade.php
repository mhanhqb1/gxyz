@extends('layout.main')

@section('content')
<div class="row">
    <div class="col blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Images
        </h3>
        <div class="row mb-2">
            <?php if (!empty($images)): ?>
            <?php foreach ($images as $img): ?>
                @include('layout.image', ['img' => $img])
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col home-btn">
                <a href="{{ url('/images').'?page='.($params['page'] + 1) }}" class="btn btn-outline-primary">View more</a>
            </div>
        </div>
    </div>

</div><!-- /.row -->
@endsection