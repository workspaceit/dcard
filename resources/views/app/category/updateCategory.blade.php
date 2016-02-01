<!--
 * Project  : dCard
 * File     : updateCategory.blade.php
 * Author   : Abu Bakar Siddique
 * Email    : absiddique.live@gmail.com
 * Date     : 9/8/15 - 3:45 PM
-->

@extends("layout.master")

@section("content")
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Add Category</h3>
        </div>
        {!! Form::model($category, ['url' => "store/category/" . $category->id . "/edit", 'method' => 'post', "class" => "form-horizontal"]) !!}
        <div class="box-body">
            @include("app.category.form")
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Update</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection