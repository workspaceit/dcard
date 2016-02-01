<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GCM extends Model {
    protected $table    = "gcm";
    protected $fillable = ["device_id", "member_id"];

    public $timestamps = FALSE;
}
