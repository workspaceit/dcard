<!--
 * Project  : dCard
 * File     : storeDetails.blade.php
 * Author   : Abu Bakar Siddique
 * Email    : absiddique.live@gmail.com
 * Date     : 9/29/15 - 2:25 PM
 -->

@extends("layout.master")

@section("content")
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Store : {{ $store->store_name }}</h3>
        </div>
        <div class="box-body">
            <div class="box-group">
                <label class="col-sm-2">Invite Code : </label>
                <p class="text-left">{{ $store->invite_code }}</p>
            </div>
            <div class="box-group">
                <label class="col-sm-2">Category : </label>
                <p class="text-left">{{ $store->category->name }}</p>
            </div>
            <div class="box-group">
                <label class="col-sm-2">Yelp Link : </label>
                <p class="text-left">
                @if($store->yelp_id != 0)
                    <a href="http://www.yelp.com/biz/{{ $store->yelp_id }}">{{ $store->store_name }}</a>
                @else
                    N/A
                @endif
                </p>
            </div>
            <div class="box-group">
                <label class="col-sm-2">State : </label>
                <p class="text-left">{{ $store->store_state}}</p>
            </div>
            <div class="box-group">
                <label class="col-sm-2">City : </label>
                <p class="text-left">{{ $store->store_city}}</p>
            </div>
            <div class="box-group">
                <label class="col-sm-2">Zip : </label>
                <p class="text-left">{{ $store->store_zip}}</p>
            </div>
            <div class="box-group">
                <label class="col-sm-2">Country : </label>
                <p class="text-left">{{ $store->store_country}}</p>
            </div>
            <div class="box-group">
                <label class="col-sm-2">Participator : </label>
                <p class="text-left">
                    @if($store->participator == 1)
                        Enrolled
                    @else
                        Dispute
                    @endif
                </p>
            </div>
        </div>
    </div>
@endsection