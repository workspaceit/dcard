<!--
 * Project  : dCard
 * File     : storeEditForm.blade.php
 * Author   : Abu Bakar Siddique
 * Email    : absiddique.live@gmail.com
 * Date     : 9/29/15 - 1:32 PM
-->

@extends("layout.master")

@section("content")
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Update Store : {{ $store->store_name }}</h3>
        </div>
        {!! Form::model($store, ['url' => 'app/edit-store/'.$store->store_id, 'method' => 'post', "class" => "form-horizontal"]) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('name', 'Store name', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('store_name', NULL, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('category', 'Category', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::select('category_id', $category , $store->category_id , ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('state', 'State', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('store_state', NULL, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('city', 'City', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('store_city', NULL, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('zip', 'Zip', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('store_zip', NULL, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('country', 'Country', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('store_country', NULL, ['class' => 'form-control', "placeholder" => "max 40000 meters"]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('participator', 'Enrolled', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::select('participator', $participator , $store->participator, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Update</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection