@extends("layout.master")

@section("content")
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Add Category</h3>
        </div>
        {!! Form::open(['url' => 'store/category/new', 'method' => 'post', "class" => "form-horizontal"]) !!}
        <div class="box-body">
            @include("app.category.form")
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Submit</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection