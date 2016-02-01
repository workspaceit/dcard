@extends("layout.master")

@section("content")
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Search Store in Yelp</h3>
        </div>
        {!! Form::open(['url' => url("app/search-store"), 'method' => 'post', "class" => "form-horizontal"]) !!}
            <div class="box-body">
                <div class="form-group">
                    {!! Form::label('term', 'Keyword', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('term', NULL, ['class' => 'form-control', "placeholder" => "Search Keyword"]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('location', 'Location', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('location', NULL, ['class' => 'form-control', "placeholder" => "Location"]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('limit', 'Limit', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('limit', NULL, ['class' => 'form-control', "placeholder" => "Number of result return (Max 20)"]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('offset', 'Offset', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('offset', NULL, ['class' => 'form-control', "placeholder" => "Next start Number"]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('radius', 'Radius', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('radius', NULL, ['class' => 'form-control', "placeholder" => "max 40000 meters"]) !!}
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">Search</button>
            </div>
        {!! Form::close() !!}
    </div>
@endsection