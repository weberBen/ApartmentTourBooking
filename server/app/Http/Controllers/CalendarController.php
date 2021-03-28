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

class CalendarController extends Controller
{

    private static function getCalendarFrontInfo($event, $type, $traductions)
    {
        foreach(Planning::$STATES as $key => $val)
        {
            if($val==$event->state)
            {
                return $traductions[$type]["values"][$key];
            }
        }

        return $traductions["default"];
    }

    public function addNotAllocatedEvents(Request $request)
    {
        $period_start = $request->period_start;
        $period_end = $request->period_end;
        $period_length_min = intval($request->period_length_min);
        $period_delay_min = intval($request->period_delay_min);
        
        if(!isset($period_start) || !isset($period_end) || !isset($period_delay_min) || !isset($period_length_min))
        {
            return response()->json(["error" => "invalid/missing parameters"]); 
        }

        $dt_format = "Y/m/d H:i";
        $reverse_event_states = Tools::reverseDict(Planning::$STATES);

        $invalid_events = [];

        $start = Carbon::createFromFormat($dt_format, $period_start);
        $end = Carbon::createFromFormat($dt_format, $period_end);

        if($end<$start->copy()->addMinutes($period_length_min))
        {
            return response()->json(["error" => "invalid period start and end  dates for the given lentgh"]); 
        }

        while($start->copy()->addMinutes($period_delay_min)<=$end)
        {
            $tmp_start = $start->copy();
            $tmp_start_tsp = $tmp_start->timestamp;
            $tmp_end = $start->copy()->addMinutes($period_length_min);
            $tmp_end_tsp = $tmp_end->timestamp;

            $res = Planning::whereNotIn('state', [Planning::$STATES['done'], Planning::$STATES['cancelled'], Planning::$STATES['abscence']])
                ->where([
                    ['end_timestamp', '>', $tmp_start_tsp],
                    ['end_timestamp', '<=', $tmp_end_tsp]
                ])->orWhere([
                    ['start_timestamp', '<', $tmp_start_tsp],
                    ['end_timestamp', '>', $tmp_end_tsp]
                ])->orWhere([
                    ['start_timestamp', '<', $tmp_end_tsp],
                    ['start_timestamp', '>=', $tmp_start_tsp]
                ])->orWhere([
                    ['start_timestamp', '=', $tmp_start_tsp],
                    ['end_timestamp', '=', $tmp_end_tsp]
                ])->first();
            
            if(isset($res))
            {
                $invalid_events[] = [
                    "id" => uniqid(),
                    "input_event" => [
                        "start_date" => $tmp_start->format($dt_format),
                        "end_date"   => $tmp_end->format($dt_format),
                        "start_timestamp" => $tmp_start_tsp,
                        "end_timestamp" => $tmp_end_tsp
                    ],
                    "overlapped_event" => [
                        "id" => $res->id,
                        "reference" => $res->reference,
                        "state" => $res->state,
                        "state_name" => $reverse_event_states[$res->state],
                        "created_at" => $res->created_at,
                        "updated_at" => $res->updated_at,
                        "start_date" => Carbon::parse($res->start_date)->format($dt_format),
                        "end_date" => Carbon::parse($res->end_date)->format($dt_format),
                        "start_timestamp" => $res->start_timestamp,
                        "end_timestamp" => $res->end_timestamp,
                    ]
                ];
            }else
            {
                Planning::addNew($tmp_start, $tmp_end);
            }

            $start = $tmp_end->addMinutes($period_delay_min);
        }

        if(count($invalid_events)>0)
        {
            return response()->json(["error" => "overlapped events", "invalid_events" => $invalid_events]); 
        }


        return response()->json(["msg" => "ok"]); 
    }
    
    public function getEvents(Request $request)
    {
        $language = $request->language;
        $start = $request->start;
        $end = $request->end;

        $start_date = Carbon::parse($start)->setTimezone('UTC');
        $end_date = Carbon::parse($end)->setTimezone('UTC');

        $array_calendar = [];
        $query = \DB::table("planning")->where('start_date', '>=', $start_date)->where('end_date', '<=', $end_date);

        $can_add_events = true;

        $user = Auth::user();
        $right_level = $user->right_level;
        if($right_level==User::$RIGHT_LEVELS["admin"])
        {
            //do nothing

        }else if($right_level==User::$RIGHT_LEVELS["manager"])
        {
            $query = $query->whereNotIn('state', [Planning::$STATES["not_allocated"], Planning::$STATES["waiting_validation"]]);
        }else
        {
            $start_date = Carbon::now()->setTimezone('UTC');
            
            $can_add_events = Tools::canUserAddCalendarEvent($user->id);
            $query = $query->whereIn('state', [Planning::$STATES["not_allocated"]]);
        }

        if(!$can_add_events)
        {
            return response()->json([]);
        }

        $is_manager_group = Tools::isManagerGroup($user);

        $calendar_language = Tools::getTraductions($language)["calendar"];
        $timezone = Info::find('timezone')->value;

        $res = $query->get();
        foreach($res as $item)
        {
            $event = array(
                "id"                =>  $item->id,
                "resourceId"        =>  $item->id,
                "start"             =>  Carbon::parse($item->start_date)->timestamp*1000,
                "end"               =>  Carbon::parse($item->end_date)->timestamp*1000,
                "user_id"           =>  $item->id_user,
                "info"              =>  $item->info,
                "start_date"        =>  $item->start_date,
                "end_date"          =>  $item->end_date,
                "timezone"          =>  $timezone,
            );

            if($is_manager_group)
            {
                $event = array_merge($event, [
                    "reference" => $item->reference
                ]);
            }
            

            array_push($array_calendar, array_merge($event, self::getCalendarFrontInfo($item, "states", $calendar_language)));
        }

        return response()->json($array_calendar);
    }

    

    public function getUserEvents(Request $request)
    {
        $language = $request->language;

        $user = \Auth::user();
        $id_current_user = $user->id;

        $id_user = $request->id_user;
        if(is_array($id_user) && count($id_user)==1)
            $id_user = $id_user[0];
        else if(!isset($id_user))
            $id_user = $id_current_user;
        
        $all_users = false;

        $start_date = $request->start_date;
        $start_date = $start_date?Carbon::parse($start_date):null;

        $end_date = $request->end_date;
        $end_date = $end_date?Carbon::parse($end_date):null;

        $can_add_events = Tools::canUserAddCalendarEvent($id_user);
        $can_see_calendar = true;

        $planning_columns = ['id', 'start_date','end_date', 'id_action', 'id_user_state', 'reason_state', 'date_state', 'state', 'id_user', 'created_at', 'updated_at', 'reference', 'start_timestamp', 'end_timestamp'];
        $user_columns = ['id', 'name', 'phone', 'right_level', 'created_at', 'updated_at'];
        $action_columns = ['id', 'state', 'type', 'id_user_state', 'id_user', 'reason_state', 'date_state', 'created_at', 'updated_at', 'public_data'];

        if($user->right_level==User::$RIGHT_LEVELS["admin"] || $user->right_level==User::$RIGHT_LEVELS["manager"])
        {
            $planning_columns = array_merge($planning_columns, ['info', 'late']);
            $user_columns = array_merge($user_columns, ['rank', 'interest', 'info']);

            if($request->all_users=="true")
            {
                $all_users = true;
            }
        }else
        {
            $can_see_calendar = $can_add_events;
            
            if(is_array($id_user) || $id_user!=$id_current_user)
            {
                return response()->json(["error" => "you have not enough priviledges to retrive data of other user"]);
            }
        }

        $with = ['userState'];
        if($all_users)
            $with[] = 'user';
        $query = Planning::with($with);
        if(!$all_users)
            $query = $query->where('id_user', $id_user);
        else if(is_array($id_user))
            $query = $query->whereIn('id_user', $id_user);
        $query = $query->whereNotIn('state', [Planning::$STATES["not_allocated"]])->orderBy('start_timestamp', 'DESC')->orderBy('updated_at', 'DESC');
        if(isset($start_date))
        {
            $query = $query->where('created_at', '>=', $start_date);
        }
        if(isset($end_date))
        {
            $query = $query->where('created_at', '<=', $end_date);
        }
        $events = $query->get($planning_columns);



        $with = ['userState'];
        if($all_users)
            $with[] = 'user';
        $query = Action::with($with);
        if(!$all_users)
            $query = $query->where('id_user', $id_user);
        else if(is_array($id_user))
            $query = $query->whereIn('id_user', $id_user);
        $query = $query->whereNotIn('state', [])->orderBy('created_at', 'DESC');
        if(isset($start_date))
        {
            $query = $query->where('created_at', '>=', $start_date);
        }
        if(isset($end_date))
        {
            $query = $query->where('created_at', '<=', $end_date);
        }
        $actions = $query->get($action_columns);


        $user = null;
        if(!$all_users)
            $user = User::where('id', $id_user)->first($user_columns);

        $traductions = Tools::getTraductions($language);

        return response()->json(["data" => 
            [
                "can_add_events" => $can_add_events,
                "can_see_calendar" => $can_see_calendar,
                "actions" => [
                    "data" => $actions,
                    "states" => Action::$STATES,
                    "reverse_states" => Tools::reverseDict(Action::$STATES),
                    "front_values" => $traductions["actions"],
                    "types"  => Action::$TYPES,
                ],
                "events" => [
                    "data" => $events,
                    "timezone" => Info::find('timezone')->value,
                    "states" => Planning::$STATES,
                    "reverse_states" => Tools::reverseDict(Planning::$STATES),
                    "front_values" => $traductions["calendar"],
                    "cancellabled_states" => Planning::$CANCELLABLED_STATES,
                ],
                "user"  => [
                    'data' => $user,
                    'right_levels' => User::$RIGHT_LEVELS,
                ]
            ]
        ]);
    }

}