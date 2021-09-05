@extends('layout.admin')

@section('content')
<div class="row">
    <div class="col blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Images
        </h3>
        <form action="{{ route('admin.checkPosts') }}" method="GET" class="margin-bot-20">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="limit">Limit</label>
                    <input type="number" class="form-control" name="limit" placeholder="Limit" value="{{ isset($params['limit']) ? $params['limit'] : '' }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="status">Status</label>
                    <input type="number" class="form-control" name="status" placeholder="Status" value="{{ isset($params['status']) ? $params['status'] : '' }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="type">Type</label>
                    <select class="form-control" name="type">
                        <option value="">-</option>
                        <option value="0" {{ isset($params['type']) && $params['type'] == 0 ? "selected='selected'" : "" }}>Image</option>
                        <option value="1"  {{ isset($params['type']) && $params['type'] == 1 ? "selected='selected'" : "" }}>Video</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="is_hot">is_hot</label>
                    <input type="number" class="form-control" name="is_hot" placeholder="is_hot" value="{{ isset($params['is_hot']) ? $params['is_hot'] : '' }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="is_18">is_18</label>
                    <input type="number" class="form-control" name="is_18" placeholder="is_18" value="{{ isset($params['is_18']) ? $params['is_18'] : '' }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="source_type">Source Types</label>
                    <select class="form-control" name="source_type">
                        <option value="">-</option>
                        <?php foreach ($sourceTypes as $t): ?>
                        <option value="{{ $t }}" {{ !empty($params['source_type']) && $params['source_type'] == $t ? "selected='selected'" : "" }}>{{ $t }}</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="master_source">Master Sources</label>
                    <select class="form-control" name="master_source">
                        <option value="">-</option>
                        <?php foreach ($masterSources as $t): ?>
                        <option value="{{ $t->id }}" {{ !empty($params['master_source']) && $params['master_source'] == $t ? "selected='selected'" : "" }}>{{ $t->name }}</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <div class="row margin-bot-20 img-btns">
            <div class="col">
                <button class="btn btn-primary post-btn-check" data-param="status" data-val="1">Show</button>
                <button class="btn btn-danger post-btn-check" data-param="status" data-val="0">Hide</button>
                <button class="btn btn-info post-btn-check" data-param="is_hot" data-val="1">Hot</button>
                <button class="btn btn-info post-btn-check" data-param="is_hot" data-val="0">Hot-</button>
                <button class="btn btn-warning post-btn-check" data-param="is_18" data-val="1">18+</button>
                <button class="btn btn-warning post-btn-check" data-param="is_18" data-val="0">18-</button>
                <button class="btn btn-danger post-btn-check" data-param="status" data-val="-1">Delete</button>
                <button class="btn btn-danger post-btn-check" data-param="status" data-val="-2">Focus Delete</button>
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
                    <th>Image</th>
                    <th>ID</th>
                    <th>Type</th>
                    <th>ST</th>
                    <th>MS ID</th>
                    <th>Is Hot</th>
                    <th>Is 18+</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input img-check" name="imgCheck[]" value="{{ $post->id }}" id="checkId-{{ $post->id }}">
                            </div>
                        </td>
                        <td><label for="checkId-{{ $post->id }}"><img src="{{ $post->image }}" width="200px"/></label></td>
                        <td><a href="{{ route(!empty($post->type) ? 'home.videoDetail' : 'home.postDetail', ['id' => $post->id, 'slug' => !empty($post->slug) ? $post->slug : 'sexy-girl-'.$post->id]) }}" target="_blank">{{ $post->id.' - '.$post->title }}</a></td>
                        <td>{{ $post->type }}</td>
                        <td>{{ $post->source_type }}</td>
                        <td>{{ $post->master_source_id }}</td>
                        <td>{{ $post->is_hot }}</td>
                        <td>{{ $post->is_18 }}</td>
                        <td>{{ $post->status }}</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div><!-- /.row -->
@endsection
