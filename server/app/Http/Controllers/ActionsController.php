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
use App\Models\Info;

class ActionsController extends Controller
{
    
    private static function buildEventData($event, $timezone)
    {
        $data = [
            "id_planning" => $event->id,
            "modified_fields" => [
                "id_user" => null,
                "state" => Planning::$STATES["not_allocated"],
                "id_user_state" => null,
                "reason_state" => null,
                "date_state" => null,
                "timestamp_state" => null,
                "id_action" => null,
            ],
        ];

        $public_data = [
            "calendar_event" => [
                "reference" => $event->reference,
                "id" => $event->id,
                "start_date" => $event->start_date,
                "end_date" => $event->end_date,
                "start_timestamp" => $event->start_timestamp,
                "end_timestamp" => $event->end_timestamp,
                "timezone" => $timezone,
            ],
            "history" => [],
        ];

        return [
            "data" => $data,
            "public_data" => $public_data,
        ];
    }

    private static function addHistoryEntry($public_data, $id_user, $state, $reason_state, $timezone)
    {
        $now = Carbon::now();
        $public_data["history"][] = [
            "state" => $state,
            "id_user_state" => $id_user,
            "reason_state" => $reason_state,
            "date_state" => $now,
            "timestamp_state" => $now->timestamp,
            "timezone" => $timezone,
        ];

        return $public_data;
    }

    public function book(Request $request)
    {
        $id_event = $request->id_calendar_event;
        $id_user = \Auth::user()->id;

        $timezone = Info::find('timezone')->value;

        if(!Tools::canUserAddCalendarEvent($id_user))
        {
            return response()->json(["error" => "you already have booked an active event", "error_type" => "already_active_book"]);
        }

        try
        {
            $pdo = \DB::connection()->getPdo();
            $pdo->exec('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE');
            
            $res = \DB::transaction(function() use($id_event, $id_user, $timezone) {

                $row = Planning::lockForUpdate()->find($id_event);
                if(!isset($row))
                    return ["error"=>"invalid event id"];
                 
                if($row->state!=Planning::$STATES["not_allocated"])
                    return ["error"=>"cannot book an allocated event", "error_type" => "already_allocated"];

                $res = self::buildEventData($row, $timezone);
                $data =$res["data"];
                $public_data = $res["public_data"];

                $public_data = self::addHistoryEntry($public_data, $id_user, Action::$STATES["waiting_validation"], "demande de visiste", $timezone);
    
                $action = Action::create([
                    "id_user" => $id_user,
                    "type" => Action::$TYPES["book"],
                    "data" => json_encode($data),
                    "public_data" => json_encode($public_data),
                    "reason" => "",
                ]);
                
                $row->id_user = $id_user;
                $row->state = Planning::$STATES["waiting_validation"];
                $row->id_action = $action->id;
                $row->save();
                
                return ["data" => $action];
                
            });
            if(isset($res) && isset($res["error"]))
                return response()->json($res);
            
            $id_action = $res["data"]->id;
            return response()->json(["msg" => "ok", "id_action" => $id_action]);

        }catch(\Throwable $e)
        {
            return response()->json(["error" => "an error occurs", "exception" => [
                'msg' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTrace(),
            ]]);
        }
    }

    public function unbook(Request $request)
    {
        $id_planning = $request->id_calendar_event;
        $user = \Auth::user();
        $reason = $request->reason;

        $event = Planning::find($id_planning);
        if(!isset($event))
        {
            return response()->json(["error"=>"invalid event id"]);
        }
        if($event->id_user!=$user->id)
        {
            $event_user = User::find($event->id_user);
            if(isset($event_user) && $event_user->right_level!=User::$RIGHT_LEVELS["admin"])
            {
                return response()->json(["error"=>"the current event does not belong to you"]);
            }
        }
        
        try
        {
            $timezone = Info::find('timezone')->value;

            $pdo = \DB::connection()->getPdo();
            $pdo->exec('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE');
            
            $res = \DB::transaction(function() use($event, $user, $reason, $timezone) {

                $row = Action::lockForUpdate()->find($event->id_action);
                if(!isset($row))
                    return ["error" => "invalid action id : " . $event->id_action];
                
                $id_user = $row->id_user;
                $data = json_decode($row->data, true);
                $public_data = json_decode($row->public_data, true);
                
                if($row->state==Action::$STATES["waiting_validation"])
                {
                    $id_user_state = $id_user;
                    $reason_state = $reason;

                    $res = self::undo($row, $data, $id_user_state, $reason_state);
                        if(isset($res["error"]))
                            return $res;

                    $now = Carbon::now();
                    $public_data = self::addHistoryEntry($public_data, $id_user_state, Action::$STATES["cancelled"], $reason_state, $timezone);

                    $row->state = Action::$STATES["cancelled"];
                    $row->id_user_state = $id_user_state;
                    $row->reason_state = $reason_state;
                    $row->date_state = $now;
                    $row->timestamp_state = $now->timestamp;
                    $row->public_data = json_encode($public_data);
                    $row->save();

                    return ["data" => $row];
                    
                }else
                {
                    $found = false;
                    foreach(Planning::$CANCELLABLED_STATES as $state_name)
                    {
                        if(Planning::$STATES[$state_name]==$event->state)
                        {
                            $found = true;
                            break;
                        }
                    }

                    if(!$found)
                    {
                        return ["error" => "the associated event (id=" . $event->id . ") is not cancallabled"];
                    }

                    /*if($user->right_level==User::$RIGHT_LEVELS["admin"])
                    {
                        /*$id_user_state = $user->id;
                        $reason_state = $reason;
                        $data = json_decode($row->data, true);

                        $cp_row = unserialize(serialize($row));
                        $cp_row->type = Action::$TYPES["unbook"];

                        $res = self::do($cp_row, $data, $id_user_state, $reason_state);
                        if(isset($res["error"]))
                            return $res;
                        
                        $now = Carbon::now();
                        $public_data = self::addHistoryEntry($public_data, $id_user_state, Action::$STATES["cancelled"], $reason_state, $timezone);

                        $now = carbon::now();
                        $row->state = Action::$STATES["cancelled"];
                        $row->id_user_state = $id_user_state;
                        $row->reason_state = $reason_state;
                        $row->date_state = $now;
                        $row->timestamp_state = $now->timestamp;
                        $row->public_data = json_encode($public_data);
                        $row->save();
                        
                        return ["data" => $row];

                    }*/
                    
                    $reason_state = $reason;

                    $data = [
                        "id_planning" => $event->id,
                        "modified_fields" => [],
                        "previous_planning_state_before_cancellation" => $event->state,
                    ];

                    $now = Carbon::now();
                    $tmp_public_data = json_decode(json_encode($public_data), true);
                    $tmp_public_data["history"] = [];
                    $tmp_public_data = self::addHistoryEntry($tmp_public_data, $row->id_user, Action::$STATES["waiting_validation"], $reason_state, $timezone);

                    $action = Action::create([
                        "id_user" => $row->id_user,
                        "type" => Action::$TYPES["unbook"],
                        "data" => json_encode($data),
                        "public_data" => json_encode($tmp_public_data),
                        "state" => Action::$STATES["waiting_validation"],
                    ]);
                    
                    $event->id_user_state = $row->id_user;
                    $event->reason_state = $reason_state;
                    $event->date_state = $now;
                    $event->timestamp_state = $now->timestamp;
                    $event->state = Planning::$STATES["waiting_cancellation"];
                    $event->save();

                    return ["data" => $action];
                    
                }
                
            });
            if(isset($res) && isset($res["error"]))
                return response()->json($res);
            
            $id_action = $res["data"]->id;
            return response()->json(["msg" => "ok", "action_id" => $id_action, "async_process" => ($event->id_action!=$id_action)]);

        }catch(\Throwable $e)
        {
            return response()->json(["error" => "an error occurs", "exception" => [
                'msg' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTrace(),
            ]]);
        }
        
    }

    private static function do($action, $data, $id_user_state, $reason_state)
    {
        switch($action->type)
        {
            case Action::$TYPES["book"]:
                {
                    $id_event = $data["id_planning"];
                    $event = Planning::find($id_event);
                    if(!isset($event))
                        return ["error" => "invalid calendar event id"];
                    
                    $now = Carbon::now();
                    $event->state = Planning::$STATES["validated"];
                    $event->id_user_state = $id_user_state;
                    $event->reason_state = $reason_state;
                    $event->date_state = $now;
                    $event->timestamp_state = $now->timestamp;
                    $event->info = null;
                    $event->save();
                }
                break;
            case Action::$TYPES["unbook"]:
                {
                    $id_event = $data["id_planning"];
                    $event = Planning::find($id_event);
                    if(!isset($event))
                        return ["error" => "invalid calendar event id"];
                    
                    $now = Carbon::now();
                    $event->state = Planning::$STATES["cancelled"];
                    $event->id_user_state = $id_user_state;
                    $event->reason_state = $reason_state;
                    $event->date_state = $now;
                    $event->timestamp_state = $now->timestamp;
                    $event->save();

                    Planning::create([
                        "start_date" => $event->start_date,
                        "end_date" => $event->end_date,
                        "start_timestamp" => Carbon::parse($event->start_date)->timestamp,
                        "end_timestamp" => Carbon::parse($event->end_date)->timestamp,
                        "state" => Planning::$STATES["not_allocated"],
                        "reference" =>  Planning::getUniqueRef(),
                    ]);

                }
                break;
            default:
                break;
        }

        return [];
    }

    private static function undo($action, $data, $id_user_state, $reason_state)
    {
        switch($action->type)
        {
            case Action::$TYPES["book"]:
                {
                    $id_event = $data["id_planning"];
                    $event = Planning::find($id_event);
                    if(!isset($event))
                        return ["error" => "invalid calendar event id"];
                    
                    foreach($data["modified_fields"] as $field_name => $val)
                    {
                        $event->{$field_name} = $val;
                    }
                    $event->info = null;
                    $event->save();
                }
                break;
            case Action::$TYPES["unbook"]:
                {
                    $id_event = $data["id_planning"];
                    $event = Planning::find($id_event);
                    if(!isset($event))
                        return ["error" => "invalid calendar event id"];
                    
                
                    $event->state = $data["previous_planning_state_before_cancellation"];
                    $event->save();
                }
                break;
            default:
                break;
        }

        return [];
    }

    private static function getMsgToSend($action, $action_event, $reason, $language)
    {
        $messageBuilder = new MessageBuilder([
            "event.reference",
            "event.start_time",
            "event.end_time",
            "event.start_date",
            "event.end_date",
            "event.date",
            "localization.address",
            "localization.city_code",
            "localization.city_name",
            "localization.info",
            "action.reason",
            "timezone",
        ], "{{", "}}");
        
        $localization = json_decode(Info::find('apartment_localization')->value, true);
        $server_adress = \URL::to('/');

        $public_data = json_decode($action->public_data, true);
        $event = $public_data["calendar_event"];

        $values = [
            "event.date" => Carbon::parse($event["start_date"])->format('d/m/Y'),
            "event.start_time" => Carbon::parse($event["start_date"])->format('H:i'),
            "event.end_time" => Carbon::parse($event["end_date"])->format('H:i'),
            "event.start_date" => Carbon::parse($event["start_date"])->format('d/m/Y H:i'),
            "event.end_date" => Carbon::parse($event["end_date"])->format('d/M/Y H:i'),
            "event.reference" => $event["reference"],
            "localization.address" => $localization["address"],
            "localization.city_code" => $localization["city_code"],
            "localization.city_name" => $localization["city_name"],
            "localization.info" => $localization["info"],
            "action.reason" => $reason,
            "timezone" => Info::find('timezone')->value,
        ];

        $reverse_types = Tools::reverseDict(Action::$TYPES);
        $msg_type = $reverse_types[$action->type] . "." . $action_event;

        $msg_list = Tools::getMsgModels($language);

        $msg = null;
        switch($msg_type)
        {
            case "book.validate":
                {
                    $msg = $messageBuilder->build($values, $msg_list["action"]["book"]["validate"]);
                }
                break;
            case "book.cancel":
                {
                    $msg = $messageBuilder->build($values, $msg_list["action"]["book"]["cancel"]);
                }
                break;
            case "unbook.validate":
                {
                    $msg = $messageBuilder->build($values, $msg_list["action"]["unbook"]["validate"]);
                }
                break;

            default:
                {
                    $msg = $msg_list["action"]["default"];
                };
                break;
        }

        return $msg . $msg_list["appendix_msg"];
    }

    public function updateActionState(Request $request)
    {
        $state = $request->state;
        $id_action = $request->id_action;
        $user = \Auth::user();
        $id_user = $user->id;
        $reason = $request->reason;
        $language = $request->language;

        $timezone = Info::find('timezone')->value;

        if($user->right_level!=User::$RIGHT_LEVELS["admin"])
        {
            return response()->json(["error" => "not enough priviledge to access this ressource"]);
        }

        try
        {
            $pdo = \DB::connection()->getPdo();
            $pdo->exec('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE');
            
            $res = \DB::transaction(function() use($state, $id_action, $id_user, $reason, $language, $timezone) {
                $row = Action::lockForUpdate()->find($id_action);
                if(!isset($row))
                    return ["error"=>"invalid action id : " . $id_action];
                
                if($row->state!=Action::$STATES["waiting_validation"])
                    return ["error" => "action has already been validated"];

                $data = json_decode($row->data, true);
                $public_data = json_decode($row->public_data, true);
                $id_user_state = $id_user;
                $reason_state = $reason;

                if($state=="validate")
                {
                    $mgs_to_send = self::getMsgToSend($row, $state, $reason, $language);
                    $state = Action::$STATES["validated"];

                    $res = self::do($row, $data, $row->id_user, $row->reason_state);
                    if(isset($res["error"]))
                        return $res;

                }else if($state=="cancel")
                {
                    $mgs_to_send = self::getMsgToSend($row, $state, $reason, $language);
                    $state = Action::$STATES["cancelled"];

                    $res = self::undo($row, $data, $row->id_user, $row->reason_state);
                    if(isset($res["error"]))
                        return $res;

                }else
                {
                    return ["error" => "unhendled state : " + $state];
                }

                $now = Carbon::now();
                $public_data = self::addHistoryEntry($public_data, $id_user_state, $state, $reason_state, $timezone);
                
                $now = Carbon::now();
                $row->state = $state;
                $row->id_user_state = $id_user_state;
                $row->reason_state = $reason_state;
                $row->date_state = $now;
                $row->timestamp_state = $now->timestamp;
                $row->public_data = json_encode($public_data);
                $row->save();

                return ["data" => $row, "msg_to_send" => $mgs_to_send];
                
            });
            if(isset($res) && isset($res["error"]))
                return response()->json($res);
            
            $action = $res["data"];
            $mgs_to_send = $res["msg_to_send"];
            $id_action = $action->id;

            return response()->json(["msg" => "ok", "id_action" => $id_action, "msg_to_send" => $mgs_to_send]);

        }catch(\Throwable $e)
        {
            return response()->json(["error" => "an error occurs", "exception" => [
                'msg' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTrace(),
            ]]);
        }

    }

    public function waitingActions(Request $request)
    {
        $user = \Auth::user();

        if($user->right_level!=User::$RIGHT_LEVELS["admin"])
        {
            return response()->json(["error" => "you have not enough right to access this ressource"]);
        }


        $res = Action::with(['user'])->whereIn('state', [Action::$STATES["waiting_validation"]])->orderByRaw('DATE(updated_at) ASC')->get();

        return response()->json(["data" => [
            "values" => $res,
            "types" => ACTION::$TYPES,
            "reverse_types" => Tools::reverseDict(Action::$TYPES),
        ]]);

    }

    public function updateEvent(Request $request)
    {
        $user = \Auth::user();

        if($user->right_level!=User::$RIGHT_LEVELS["admin"])
        {
            return response()->json(["error" => "you have not enough right to access this ressource"]);
        }

        $event_ref = $request->reference;
        $state = intval($request->state);
        $interest = intval($request->interest);
        $rank = intval($request->rank);
        $info = $request->info;
        $late = intval($request->late);
        $reason_state = $request->reason;

        $event = Planning::where('reference', $event_ref)->first();
        if(!isset($event))
            return response()->json(["error" => "invalid event ref", "error_type" => "invalid_ref"]);
        
        $reverse_states = Tools::reverseDict(Planning::$STATES);
        if(!isset($reverse_states[$state]))
        {
            return response()->json(["error" => "invalid event state value", "error_type" => "invalid_ref"]);
        }
        if($event->state!=$state)
        {
            $reverse_updatable_states = Tools::getAssociationArray(PLanning::$STATES, Planning::$UPDATEBLE_STATES)["reverse_output"];
            if(!array_key_exists($event->state, $reverse_updatable_states))
            {
                return response()->json(["error" => "cannot update event with state : <<" . Tools::reverseDict(Planning::$STATES)[$event->state] . ">>", "error_type" => "no_updatable_state"]);
            }

            $choosable_states = Tools::getAssociationArray(PLanning::$STATES, Planning::$CHOOSABLE_UPDATED_STATE)["reverse_output"];
            if(!array_key_exists($state, $choosable_states))
            {
                return response()->json(["error" => "State : <<" . Tools::reverseDict(Planning::$STATES)[$event->state] . ">> is not a valid state for an update", "error_type" => "invalid_updated_state"]);
            }
        }

        $now = Carbon::now();
        $id_user_state = $user->id;
        $timezone = Info::find('timezone')->value;


        $res = self::buildEventData($event, $timezone);
        $data =$res["data"];
        $public_data = $res["public_data"];

        $public_data = self::addHistoryEntry($public_data, $id_user_state, Action::$STATES["validated"], $reason_state, $timezone);

        $action = Action::create([
            "id_user" => $event->id_user,
            "type" => Action::$TYPES["state_update"],
            "data" => json_encode($data),
            "public_data" => json_encode($public_data),
            "reason" => "",
            "state" => Action::$STATES["validated"],
        ]);

        $event->state = $state;
        $event->late = $late;
        $event->id_user_state = $id_user_state;
        $event->reason_state = $reason_state;
        $event->date_state = $now;
        $event->timestamp_state = $now->timestamp;


        $event_user = User::find($event->id_user);
        $event_user->rank = $rank;
        $event_user->interest = $interest;
        $event_user->info = $info;


        $action->save();
        $event->save();
        $event_user->save();

        return response()->json(["msg" => "ok"]);

    }

}