<?php

namespace App\Helpers;
use App\Tools;
use App\Helpers\MessageBuilder;
use App\Models\Info;

class MessageChooser 
{

    public static function getMessage($type, $action, $user)
    {
        $messageBuilder = new MessageBuilder([
            "event.reference",
            "event.start_time",
            "event.end_time",
            "event.date",
            "localization.address",
            "localization.city_code",
            "localization.city_name",
            "localization.info",
        ], "{{", "}}");

        $localization = json_decode(Info::find('apartment_localization')->value, true);
        $server = $info["server"];

        $msg = json_decode(file_get_contents(base_path()."/ressources/docs/msg.json"), true);

        try
        {
            $values = [
                "event.date" => Carbon::parse($event["start_date"])->format('d/m/Y'),
                "event.start_time" => Carbon::parse($event["start_date"])->format('H:i'),
                "event.end_time" => Carbon::parse($event["start_date"])->format('H:i'),
                "localization.address" => $localization["address"],
                "localization.city_code" => $localization["city_code"],
                "localization.city_name" => $localization["city_name"],
                "localization.info" => $localization["info"],
            ];

            switch($type)
            {
                case "registration":
                    {
                        return $messageBuilder->build($values, $msg["registration"]);
                    }
                    break;
            }
        }catch
    }

}