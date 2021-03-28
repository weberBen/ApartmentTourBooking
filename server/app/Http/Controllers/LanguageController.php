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

class LanguageController extends Controller
{
    
    public function getMsg(Request $request)
    {
        $language = $request->language;
        
        $data = Tools::getMsgModels($language);

        return response()->json(["data" => $data]);

    }

}