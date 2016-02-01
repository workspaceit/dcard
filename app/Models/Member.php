<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;

class Member extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable, CanResetPassword;

    protected $table      = "members";
    protected $primaryKey = "id";
    protected $fillable   = [
        'member_code',
        'email',
        'first_name',
        'last_name',
        'user_type',
        'store_id',
        'email_flag',
        'search_flag',
        'zip_code',
        'fb_id',
        'access_token',
        'create_date',
    ];
    protected $hidden     = ['password', 'remember_token'];

    public $timestamps = FALSE;

    public function store() {
        return $this->hasOne('App\Models\Store', "store_id", "store_id");
    }

    public function playStoreKey() {
        return $this->hasOne('App\Models\GCM', "member_id", "id");
    }

    public static function getMemberCode($len = 10) {
        $x   = $len - 1;
        $min = pow(10, $x);
        $max = pow(10, $x + 1) - 1;

        $memberCode = mt_rand($min, $max);

        $member = Member::where("member_code", "=", $memberCode)->first();

        if ($member) {
            Member::getMemberCode($x);
        }

        return $memberCode;
    }
}
