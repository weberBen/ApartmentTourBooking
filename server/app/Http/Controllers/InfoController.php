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

class InfoController extends Controller
{
    
    public function getAll(Request $request)
    {
        $data = Info::all()->keyBy('name')->toArray();
        $data["available_languages"] = Tools::getAvailableLanguages();

        return response()->json(["data" => $data]);

    }

    public function events(Request $request)
    {
        $user = \Auth::user();
        if($user->right_level!=User::$RIGHT_LEVELS["admin"])
            return response()->json(["error" => "you have not enough right to access that ressource"]); 

        $language = $request->language;

        $data = [
            "traductions" => Tools::getTraductions($language)["calendar"],
            "timezone" => Info::find('timezone')->value,
            "states" => Planning::$STATES,
            "reverse_states" => Tools::reverseDict(Planning::$STATES),
            "cancellabled_states" => Planning::$CANCELLABLED_STATES,
            "updatable_states" => Planning::$UPDATEBLE_STATES,
            "choosable_states" => Planning::$CHOOSABLE_UPDATED_STATE,
        ];

        return response()->json(["data" => $data]);

    }

}