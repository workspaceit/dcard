<?php

Route::get('/', function () {
    if (Auth::user()) {
        $member   = Auth::user();
        $store    = @$member->store;
        $category = @$store->category;

        if ($store) {
            $store->participator = ($store->participator == 1) ? TRUE : FALSE;
        }

        array_forget($member, ["access_token", "store"]);
        array_forget($store, ["category_id"]);

        return $data = [
            "member"         => $member,
            "store"          => $store,
            "responseStatus" => [
                "msg"    => "Login Successfully",
                "status" => TRUE,
            ],
        ];
    }

    return $data = [
        "member"         => NULL,
        "responseStatus" => [
            "msg"    => "You are not login",
            "status" => FALSE,
        ],
    ];
});

Route::get("logout", function () {
    Auth::logout();

    return $data = [
        "member"         => NULL,
        "responseStatus" => [
            "msg"    => "You are logged out Successfully",
            "status" => TRUE,
        ],
    ];
});

Route::post("registration/facebook", 'Auth\AuthController@postRegister');
Route::post("login/authentication", 'Auth\AuthController@postLogin');

Route::post("member/add", 'MemberController@store');
Route::post("member/get", 'MemberController@getMerchantDetails');
Route::get("member/get/{code}", 'MemberController@getMerchantDetailsByCode');
Route::post("member/update", 'MemberController@update');
Route::post("member/delete", 'MemberController@destroy');
Route::post("member/update/preference", 'MemberController@updatePreference');
Route::post("member/get/preference", 'MemberController@getPreference');

Route::post("merchant/add", 'MemberController@setMerchant');
Route::post("merchant/delete", 'MemberController@deleteMarchant');
//Route::post("merchant/details", 'MemberController@setMerchant');

Route::post("employee/add", 'MemberController@setEmployee');
Route::get("employee/get", 'MemberController@getMemberListByStoreId');
Route::post("employee/delete", 'MemberController@deleteEmployee');

Route::post("store/add", 'StoreController@store');
//Route::get("store/list", 'StoreController@index');
Route::get("store/details", 'StoreController@show');
Route::get("store/details/{id}", 'StoreController@show');
Route::post("store/add/yelp", 'StoreController@create');
Route::post("store/discount", 'StoreController@updateDiscount');
Route::post("store/update/category/{id}", 'StoreController@updateCategory');
Route::post("store/change/status", 'StoreController@changeStatus');

Route::post("search/store", 'StoreController@search');

/* Ony access able by merchant */
Route::post("scan/delete/last", 'ScanController@deleteLastScan');
/* Ony access able by merchant */

Route::post("scan/add", 'StoreController@scanStore');

Route::post("store/update", 'StoreController@updatePrice');

Route::get("yelp", 'YelpController@index');
Route::get("category", 'StoreController@getCategory');
Route::controller('vote', 'VoteController');

/* Admin Route start */
Route::controller('admin', 'LoginController');
Route::controller('app', 'AdminController');
Route::controller('csv', 'CSVController');
Route::get("store/code/{id}", 'AdminController@sendStoreCodeForm');
Route::post("store/code/{id}", 'AdminController@sendStoreCode');
Route::get("store/category", 'AdminController@categoryList');
Route::get("store/category/new", 'AdminController@category');
Route::post("store/category/new", 'AdminController@storeCategory');
Route::get("store/category/{id}/edit", 'AdminController@editCategory');
Route::post("store/category/{id}/edit", 'AdminController@updateCategory');

Route::get("getCategory", 'StoreController@getCategoryFromYelp');
/* Admin Route end */

/* Push to GCM start */
Route::post("device/register", "PushMassageController@registerAndroidDevice");
Route::get("push/android", "PushMassageController@pushRequest");
/* Push to GCM end */
