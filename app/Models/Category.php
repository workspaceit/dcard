<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $table    = "categories";
    protected $fillable = ["name", "slug", "parent", "created_by"];

    public $timestamps = FALSE;

    public function store() {
        return $this->belongsTo('App\Models\Store');
    }

    public function createdBy(){
        return $this->hasOne('App\Models\AdminLogin',"id","created_by");
    }
}
