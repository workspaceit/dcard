<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGCMsTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create('gcm', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->unsignedBigInteger("member_id");
            $table->string("device_id");
            $table->timestamp("register_date")->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::drop('gcm');
    }

}
