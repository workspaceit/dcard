@extends("layout.master")

@section("content")
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Search Store in Yelp</h3>
        </div>
        {!! Form::open(['url' => url("store/code/" . $code), 'method' => 'post', "class" => "form-horizontal"]) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('subject', 'Subject', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('subject', NULL, ['class' => 'form-control', "placeholder" => "Subject of Email"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('email', 'Email', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('email', NULL, ['class' => 'form-control', "placeholder" => "Email of Employee"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('code', 'Invite Code', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::textarea('code', "Hi,\n\n\n\n\nYour Invitation Code : ".$code, ['class' => 'form-control', "placeholder" => "Location"]) !!}
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Send</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection