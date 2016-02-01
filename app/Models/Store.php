<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Store extends Model {
    protected $table      = "stores";
    protected $primaryKey = "store_id";
    protected $fillable   = [
        "invite_code",
        "yelp_id",
        "store_name",
        "category_id",
        "store_state",
        "store_city",
        "store_zip",
        "store_country",
        "phone",
        "lat",
        "lon",
        "percent_off",
        "amount_off",
        "on_spent",
        "participator",
    ];

    public $timestamps = FALSE;

    public function members() {
        return $this->hasMany('App\Models\Member', "store_id", "store_id");
    }

    public function category() {
        return $this->hasMany('App\Models\Category', 'id', 'category_id');
    }

    public function vote() {
        return $this->hasMany('App\Models\Vote', 'store_id', 'store_id');
    }

    static function getString($length = 8) {
        $string = Str::random($length);
        $exist  = Store::where("invite_code", "=", $string)->first();

        if ($exist) {
            Store::getString($length);
        }

        return $string;
    }
}
