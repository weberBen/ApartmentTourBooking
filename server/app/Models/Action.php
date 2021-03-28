<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Str;

class Action extends Model
{
    protected $table = 'actions';
    protected $guarded = ['id','created_at','updated_at'];

    public static $STATES = [
        "waiting_validation" => 0,
        "validated" => 1,
        "cancelled" => 2,
    ];

    public static $TYPES = [
        "book" => 1,
        "unbook" => 2,
        "state_update" => 3,
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'id_user');
    }

    public function userState()
    {
        return $this->hasOne('App\User', 'id', 'id_user_state');
    }
}
