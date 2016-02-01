<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVotesTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create('votes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("store_id")->unsigned();
            $table->bigInteger("customer_id")->unsigned();
            $table->timestamp("create_date")->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::drop('votes');
    }

}
