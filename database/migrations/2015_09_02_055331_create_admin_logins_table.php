<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminLoginsTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create('admin_logins', function (Blueprint $table) {
            $table->increments('id');
            $table->string("first_name");
            $table->string("last_name");
            $table->string("email", 30);
            $table->string("password", 60);
            $table->timestamp("created_date");
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::drop('admin_logins');
    }

}
