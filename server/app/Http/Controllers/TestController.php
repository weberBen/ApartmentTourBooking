<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Models\Planning;
use App\Models\Action;
use App\Tools;
use App\Helpers\MessageBuilder;
use App\Helpers\MessageChooser;
use App\Models\Info;

class testController extends Controller
{
    
    public function test1(Request $request)
    {
        return "ok";
    }

}