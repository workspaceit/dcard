<!--
 * Project  : dCard
 * File     : dashboard.blade.php
 * Author   : Abu Bakar Siddique
 * Email    : absiddique.live@gmail.com
 * Date     : 9/8/15 - 5:41 PM
-->
@extends("layout.master")

@section("content")
    <section class="content">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total User</span>
                        <span class="info-box-number">{{ @$user }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Customer</span>
                        <span class="info-box-number">{{ @$customer }}</span>
                    </div>
                </div>
            </div>

            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="ion ion-ios-cart-outline"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Employee</span>
                        <span class="info-box-number">{{ @$employee }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green-active"><i class="fa fa-user-secret"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Merchant</span>
                        <span class="info-box-number">{{ @$merchant }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection