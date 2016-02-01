<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model {
    protected $table    = 'votes';
    protected $fillable = ['store_id', 'customer_id'];

    public $timestamps = FALSE;
}
