@extends('layout.admin')

@section('content')
<div class="row">
    <div class="col blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Images
        </h3>
        <form action="{{ url('/checkVideos') }}" method="GET" class="margin-bot-20">
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
                    <label for="is_hot">is_hot</label>
                    <input type="number" class="form-control" name="is_hot" placeholder="is_hot" value="{{ isset($params['is_hot']) ? $params['is_hot'] : '' }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="is_18">is_18</label>
                    <input type="number" class="form-control" name="is_18" placeholder="is_18" value="{{ isset($params['is_18']) ? $params['is_18'] : '' }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <div class="row margin-bot-20 img-btns">
            <div class="col">
                <button class="btn btn-primary video-btn-check" data-param="status" data-val="1">Show</button>
                <button class="btn btn-danger video-btn-check" data-param="status" data-val="0">Hide</button>
                <button class="btn btn-info video-btn-check" data-param="is_hot" data-val="1">Hot</button>
                <button class="btn btn-info video-btn-check" data-param="is_hot" data-val="0">Hot-</button>
                <button class="btn btn-warning video-btn-check" data-param="is_18" data-val="1">18+</button>
                <button class="btn btn-warning video-btn-check" data-param="is_18" data-val="0">18-</button>
                <button class="btn btn-danger video-btn-check" data-param="status" data-val="-1">Delete</button>
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
                    <th>Is Hot</th>
                    <th>Is 18+</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($videos as $video): ?>
                    <tr>
                        <td>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input img-check" name="imgCheck[]" value="{{ $video->id }}" id="checkId-{{ $video->id }}">
                            </div>
                        </td>
                        <td><label for="checkId-{{ $video->id }}"><img src="{{ $video->image }}" width="200px"/></label></td>
                        <td><a href="{{ route('home.videoDetail', ['id' => $video->id]) }}" target="_blank">{{ $video->title }}</a></td>
                        <td>{{ $video->is_hot }}</td>
                        <td>{{ $video->is_18 }}</td>
                        <td>{{ $video->status }}</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div><!-- /.row -->
@endsection