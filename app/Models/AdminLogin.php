<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLogin extends Model {
    protected $table    = "admin_logins";
    protected $fillable = ['first_name', 'last_name', 'email'];

    public $timestamps = FALSE;
}
