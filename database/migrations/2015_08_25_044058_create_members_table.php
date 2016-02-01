<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMembersTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create('members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("member_code");
            $table->string("email")->unique();
            $table->string("first_name");
            $table->string("last_name");
            $table->tinyInteger("user_type")->default(3)->comment = "1 = merchant , 2 = employee, 3 = customer";
            $table->string("store_id")->default(0);
            $table->boolean("email_flag");
            $table->string("search_flag");
            $table->string("zip_code");
            $table->string("fb_id");
            $table->string("access_token");
            $table->rememberToken();
            $table->timestamp("create_date")->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::drop('members');
    }

}
