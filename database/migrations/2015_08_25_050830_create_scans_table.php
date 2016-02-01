<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateScansTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create('scans', function (Blueprint $table) {
            $table->bigIncrements("scan_id");
            $table->bigInteger("customer_id")->unsigned();
            $table->bigInteger("scanner_id")->unsigned();
            $table->bigInteger("store_id")->unsigned();
            $table->double("transaction_price", 10, 4);
            $table->double("saving", 10, 4);
            $table->double("paid", 10, 4);
            $table->double("cumulative_paid", 10, 4);
            $table->double("cumulative_saving", 10, 4);
            $table->unsignedBigInteger("cumulative_count");
            $table->timestamp("created_date")->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::drop('scans');
    }

}
