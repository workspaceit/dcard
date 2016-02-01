<!--
 * Project  : dCard
 * File     : edit.blade.php
 * Author   : Abu Bakar Siddique
 * Email    : absiddique.live@gmail.com
 * Date     : 9/29/15 - 5:17 PM
 -->

@extends("layout.master")

@section("content")
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Update Admin Info</h3>
        </div>
        {!! Form::model($admin, ['url' => 'app/edit-admin/' . $admin->id, 'method' => 'post', "class" => "form-horizontal"]) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('first_name', 'First name', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('first_name', NULL, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('last_name', 'Last Name', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('last_name', NULL, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('email', 'Email', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('email', NULL, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Update</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection