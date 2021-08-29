@extends('layout.admin')

@section('content')
<div class="row">
    <div class="col blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Add source
        </h3>
        <form action="{{ url('/saveSource') }}" method="POST" class="margin-bot-20">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="Type">Type</label>
                    <select class="form-control" name="type">
                        <?php foreach ($types as $t): ?>
                        <option value="{{ $t }}">{{ $t }}</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="source_type">Source Type</label>
                    <select class="form-control" name="source_type">
                        <?php foreach ($sourceTypes as $t): ?>
                        <option value="{{ $t }}">{{ $t }}</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="loop">Loop</label>
                    <select class="form-control" name="loop">
                        <option value="">No loop</option>
                        <?php foreach ($loops as $t): ?>
                        <option value="{{ $t }}">{{ $t }}</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" value="">
                </div>
                <div class="form-group">
                    <label for="source_params">Params</label>
                    <input type="text" class="form-control" name="source_params" value="">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

</div><!-- /.row -->
@endsection
