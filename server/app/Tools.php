<?php

namespace App;

use App\User;
use App\Models\Action;
use App\Models\Planning;
use Brick\PhoneNumber\PhoneNumber;
use Brick\PhoneNumber\PhoneNumberParseException;
use Brick\PhoneNumber\PhoneNumberFormat;

class Tools
{
    public static function phoneToFormat($phone, $country_code=null)
    {
        try 
        {
            if(isset($country_code))
            {
                if($country_code=="fr")
                {
                    if($phone[0]=="3")
                    {
                        $phone = "+" . $phone;
                    }
                }
                $number = PhoneNumber::parse($phone, strtoupper($country_code));
                $phone = $number->format(PhoneNumberFormat::INTERNATIONAL);
            }else
            {
                if($phone[0]!="+")
                {
                    $phone = "+" . $phone;
                }
                $number = PhoneNumber::parse($phone);
                $phone = $number->format(PhoneNumberFormat::INTERNATIONAL);
            }
        }
        catch (PhoneNumberParseException $e) 
        {
            return null;
        }catch(\Throwable $e)
        {
            return null;
        }

        return $phone;
    }

    public static function reverseDict($array)
    {
        $output = [];
        foreach($array as $key => $val)
        {
            $output[$val] = $key;
        }

        return $output;
    }

    public static function canUserAddCalendarEvent($id_user)
    {
        $res = Planning::where('id_user', $id_user)->whereIn('state', [Planning::$STATES["waiting_validation"], Planning::$STATES["validated"], Planning::$STATES["waiting_cancellation"]])->first();

        return $res==null;
    }

    public static function isManagerGroup($user)
    {
        if($user->right_level==User::$RIGHT_LEVELS["admin"] || $user->right_level==User::$RIGHT_LEVELS["manager"])
            return true;
        
        return false;
    }
    

    public static function getMsgModels($language)
    {
        $path = base_path() . "/resources/docs/msg/". $language . ".json";
        if(!file_exists($path))
            throw new \ErrorException("language value is not set and/or is not supported (supplied value : " . ($language?$language:"null") . ")");

        $data = json_decode(file_get_contents($path), true);

        return $data;
    }

    public static function getTraductions($language)
    {
        $data = json_decode(file_get_contents(base_path() . "/resources/docs/traductions.json"), true);

        if(!isset($data[$language]))
            throw new \ErrorException("language value is not set and/or is not supported (supplied value : " . ($language?$language:"null") . ")");
        
        return $data[$language];
    }

    public static function getAvailableLanguages()
    {
        $data = json_decode(file_get_contents(base_path() . "/resources/docs/languages.json"), true);

        return $data;
    }

    public static function getAssociationArray($key_value_array, $key_array)
    {
        $output = [];
        $reverse_output = [];
        $pluck_output = [];
        $reverse_pluck_output = [];

        foreach($key_array as $key)
        {
            $value = $key_value_array[$key];
            $output[$key] = $value;
            $reverse_output[$value] = $key;
            $pluck_output[] = $key;
            $reverse_pluck_output[] = $value;
        }

        return  [
            "output" => $output,
            "reverse_output" => $reverse_output,
            "pluck_output" => $pluck_output,
            "reverse_pluck_output" => $reverse_pluck_output,
        ];
    }

}
