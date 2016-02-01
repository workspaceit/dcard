<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string("name");
            $table->string("slug");
            $table->unsignedInteger("parent")->default(0);
            $table->unsignedBigInteger("created_by");
            $table->timestamp("created_date")->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::drop('categories');
    }

}
