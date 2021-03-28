<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

use App\Models\UserLoginUrl;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable;

    public static $RIGHT_LEVELS = [
        "none" => -1,
        "admin" => 0,
        "manager" => 1,
    ];

    private static $REVERSE_RIGHT_LEVEL = null;

    protected static function boot(){
        parent::boot();
        self::created(function ($model){
            UserLoginUrl::create([
                'id_user' => $model->id,
            ]);
        });
    }

    public static function getRightLevelName($right_level)
    {
        if(self::$REVERSE_RIGHT_LEVEL==null)
        {
            $output = [];
            foreach(self::$RIGHT_LEVELS as $right_level_name => $right_level_value)
            {
                $output[$right_level_value] = $right_level_name;
            }
            
            self::$REVERSE_RIGHT_LEVEL = unserialize(serialize($output));
        }

        if(isset(self::$REVERSE_RIGHT_LEVEL[$right_level]))
            return self::$REVERSE_RIGHT_LEVEL[$right_level];
        else
            return null;

    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'password', 'phone_verified_at', 'right_level'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    
    /**
     * Override the mail body for reset password notification mail.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\MailResetPasswordNotification($token));
    }

    public function calendarEvents()
    {
        return $this->hasMany('App\Models\Planning', 'id', 'id_user');
    }

    public function AsyncEvents()
    {
        return $this->hasMany('App\Models\AsyncEvent', 'id', 'id_user');
    }
    
}
