<!--
 * Project  : dCard
 * File     : details.blade.php
 * Author   : Abu Bakar Siddique
 * Email    : absiddique.live@gmail.com
 * Date     : 9/29/15 - 5:29 PM
 -->

@extends("layout.master")

@section("content")
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Admin Details</h3>
        </div>
        <div class="box-body">
            <div class="box-group">
                <label class="col-sm-2">First Name : </label>
                <p class="text-left">{{ $admin->first_name }}</p>
            </div>
            <div class="box-group">
                <label class="col-sm-2">Last Name : </label>
                <p class="text-left">{{ $admin->last_name }}</p>
            </div>
            <div class="box-group">
                <label class="col-sm-2">Email : </label>
                <p class="text-left">{{ $admin->email }}</p>
            </div>
        </div>
    </div>
@endsection