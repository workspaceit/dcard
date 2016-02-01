<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStoresTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('store_id');
            $table->string("invite_code");
            $table->string("store_name");
            $table->unsignedInteger("category_id");
            $table->string("yelp_id")->nullable();
            $table->string("store_state");
            $table->string("store_city");
            $table->string("store_zip");
            $table->string("store_country");
            $table->double("percent_off", 10, 4);
            $table->double("amount_off", 10, 4);
            $table->double("on_spent", 10, 4);
            $table->boolean("participator");
            $table->double("lon");
            $table->double("lat");
            $table->timestamp("create_date")->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::drop('stores');
    }

}
