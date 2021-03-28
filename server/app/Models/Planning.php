<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Str;

class Planning extends Model
{
    protected $table = 'planning';
    protected $guarded = ['id','created_at','updated_at'];



    public static $STATES = [
        "abscence" => -2,
        "cancelled" => -1,
        "not_allocated" => 0,
        "waiting_validation" => 1,
        "validated" => 2,
        "done" => 3,
        "waiting_cancellation" => 4,
    ];



    public static $CANCELLABLED_STATES = [
        "not_allocated", "waiting_validation", "validated"
    ];



    public static $UPDATEBLE_STATES = [
        "validated"
    ];

    public static $CHOOSABLE_UPDATED_STATE = [
        "done", "abscence"
    ];


    
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'id_user');
    }

    public function userState()
    {
        return $this->hasOne('App\User', 'id', 'id_user_state');
    }

    public static function getUniqueRef()
    {
        $ref = null;
        do
        {
            $ref = Str::random(5);
            $res = \DB::table("planning")->where('reference', '=', $ref)->first();
        }while($res!=null);

        return $ref;
    }

    public static function addNew($start_date, $end_date)
    {
        self::create([
            "start_date" => $start_date,
            "end_date" => $end_date,
            "state" => Planning::$STATES["not_allocated"],
            "reference" =>  Planning::getUniqueRef(),
            "start_timestamp" => $start_date->timestamp,
            "end_timestamp" => $end_date->timestamp,
        ]);
    }


}
