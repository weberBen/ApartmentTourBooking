<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Str;

class Info extends Model
{
    protected $table = 'info';
    protected $guarded = ['name','created_at','updated_at'];

    protected $primaryKey = 'name';
    
    public $incrementing = false;
}
