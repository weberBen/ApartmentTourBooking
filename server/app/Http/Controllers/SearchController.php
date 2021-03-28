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
use App\Models\Info;

class SearchController extends Controller
{
    
    public function search(Request $request)
    {
        error_log("\n\n------------");
        $user = \Auth::user();
        if($user->right_level!=User::$RIGHT_LEVELS["admin"] && $user->right_level!=User::$RIGHT_LEVELS["manager"])
        {
            return response()->json(["error" => "you have not enough privildge to access this ressource"]);
        }

        $output = [];
        
        
        $search_query = $request->search_query;

        $phone_query = Tools::phoneToFormat($search_query);

        if($phone_query!=null)
        {
            $output = User::where('phone', '=', $phone_query)->get(['id'])->pluck('id')->toArray();
        }else
        {
            $output = Planning::where('reference', '=', $search_query)->get('id_user')->pluck('id_user')->toArray();;
        }

        return response()->json(["data" => $output]);

    }

    public function getEvent(Request $request)
    {
        $user = \Auth::user();
        if($user->right_level!=User::$RIGHT_LEVELS["admin"])
            return response()->json(["error" => "you have not enough right to access that ressource"]); 

        $ref = $request->reference;
        $event = Planning::with(['user'])->where('reference', $ref)->first();
        if(!isset($event))
        {
            return response()->json(["error" => "invalid event ref", "error_type" => "invalid_ref"]);
        }


        $data = [
            "timezone" => Info::find('timezone')->value,
            "event" => $event,
        ];

        return response()->json(["data" => $data]);
    }

}