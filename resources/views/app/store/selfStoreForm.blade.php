<!--
 * Project  : dCard
 * File     : selfStoreForm.blade.php
 * Author   : Abu Bakar Siddique
 * Email    : absiddique.live@gmail.com
 * Date     : 9/29/15 - 5:52 PM
 -->
@extends("layout.master")

@section("content")
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Add Self Store</h3>
        </div>
        {!! Form::open(['url' => url("app/new-store/self"), 'method' => 'post', "class" => "form-horizontal"]) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('store_name', NULL, ['class' => 'form-control', "placeholder" => "Store Name"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('category', 'Category', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::select('category_id', $category , null , ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('state', 'State', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('store_state', NULL, ['class' => 'form-control', "placeholder" => "State name"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('city', 'City', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('store_city', NULL, ['class' => 'form-control', "placeholder" => "City name"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('zip', 'Zip', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('store_zip', NULL, ['class' => 'form-control', "placeholder" => "Zip code"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('country', 'Country', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('store_country', NULL, ['class' => 'form-control', "placeholder" => "Country Name"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('participator', 'participator', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::select('participator', $participator , null , ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Submit</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection