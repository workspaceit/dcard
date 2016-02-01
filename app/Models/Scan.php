<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model {
    protected $table      = "scans";
    protected $primaryKey = "scan_id";
    protected $fillable   = [
        "customer_id",
        "scanner_id",
        "store_id",
        "transaction_price",
        "saving",
        "paid",
        "cumulative_paid",
        "cumulative_saving",
        "cumulative_count",
    ];

    public $timestamps = FALSE;
}
