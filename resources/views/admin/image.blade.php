@extends('layout.admin')

@section('content')
<div class="row">
    <div class="col blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Images
        </h3>
        <form action="{{ url('/checkImages') }}" method="GET" class="margin-bot-20">
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
                <button class="btn btn-primary img-btn-check" data-param="status" data-val="1">Show</button>
                <button class="btn btn-danger img-btn-check" data-param="status" data-val="0">Hide</button>
                <button class="btn btn-info img-btn-check" data-param="is_hot" data-val="1">Hot</button>
                <button class="btn btn-info img-btn-check" data-param="is_hot" data-val="0">Hot-</button>
                <button class="btn btn-warning img-btn-check" data-param="is_18" data-val="1">18+</button>
                <button class="btn btn-warning img-btn-check" data-param="is_18" data-val="0">18-</button>
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
                <?php foreach ($images as $img): ?>
                    <tr>
                        <td>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input img-check" name="imgCheck[]" value="{{ $img->id }}" id="checkId-{{ $img->id }}">
                            </div>
                        </td>
                        <td><img src="{{ $img->url }}" width="200px"/></td>
                        <td>{{ $img->id }}</td>
                        <td>{{ $img->is_hot }}</td>
                        <td>{{ $img->is_18 }}</td>
                        <td>{{ $img->status }}</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div><!-- /.row -->
@endsection