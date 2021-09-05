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
                <div class="form-group col-md-12">
                    <label for="custom_tags">Custom Tags</label>
                    <input type="text" class="form-control" name="custom_tags" value="">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="is_owner">Is Owner</label>
                    <select class="form-control" name="is_owner">
                        <option value="">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
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


    <div class="col-12 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            List source
        </h3>
        <form action="{{ url('/addSource') }}" method="GET" class="margin-bot-20">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="limit">Limit</label>
                    <input type="number" class="form-control" name="limit" placeholder="Limit" value="{{ isset($params['limit']) ? $params['limit'] : '' }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="status">Status</label>
                    <input type="number" class="form-control" name="status" placeholder="Status" value="{{ isset($params['status']) ? $params['status'] : '' }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="type">Type</label>
                    <select class="form-control" name="type">
                        <option value="">--</option>
                        <?php foreach ($types as $t): ?>
                        <option value="{{ $t }}" {{ !empty($params['type']) && $params['type'] == $t ? "selected='selected'" : '' }}>{{ $t }}</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="source_type">Source Type</label>
                    <select class="form-control" name="source_type">
                    <option value="">--</option>
                        <?php foreach ($sourceTypes as $t): ?>
                        <option value="{{ $t }}" {{ !empty($params['source_type']) && $params['source_type'] == $t ? "selected='selected'" : '' }}>{{ $t }}</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <div class="row margin-bot-20 img-btns">
            <div class="col">
                <button class="btn btn-primary source-btn-check" data-param="status" data-val="1">Show</button>
                <button class="btn btn-danger source-btn-check" data-param="status" data-val="0">Hide</button>
                <button class="btn btn-danger source-btn-check" data-param="status" data-val="-1">Delete</button>
            </div>
        </div>
        <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="checkAll">
                            <label class="form-check-label" for="checkAll">All</label>
                        </div>
                    </th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Source Type</th>
                    <th>Params</th>
                    <th>Tags</th>
                    <th>Loop</th>
                    <th>Crawl At</th>
                    <th>Owner</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $v): ?>
                    <tr>
                        <td>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input img-check" name="imgCheck[]" value="{{ $v->id }}" id="checkId-{{ $v->id }}">
                            </div>
                        </td>
                        <td>{{ $v->id }}</td>
                        <td>{{ $v->name }}</td>
                        <td>{{ $v->type }}</td>
                        <td>{{ $v->source_type }}</td>
                        <td>{{ $v->source_params }}</td>
                        <td>{{ $v->custom_tags }}</td>
                        <td>{{ $v->loop }}</td>
                        <td>{{ $v->crawl_at }}</td>
                        <td>{{ $v->is_owner }}</td>
                        <td>{{ $v->status }}</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div><!-- /.row -->
@endsection
