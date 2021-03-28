<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Str;

class UserLoginUrl extends Model
{
    protected $table = 'user_login_urls';
    protected $guarded = ['id','created_at','updated_at'];

    protected $primaryKey = 'id';
    
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) Str::uuid();
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}
