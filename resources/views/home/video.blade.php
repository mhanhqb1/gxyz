@extends('layout.main')

@section('content')
<div class="row">
    <div class="col blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Videos
        </h3>
        <div class="row mb-2">
            <?php if (!empty($data)): ?>
            <?php foreach ($data as $v): ?>
                @include('layout.video', ['video' => $v])
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col">
                {{ $data->links() }}
            </div>
        </div>
    </div>

</div><!-- /.row -->
@endsection