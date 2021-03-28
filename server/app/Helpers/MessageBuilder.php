<?php

namespace App\Helpers;
use App\Tools;

class MessageBuilder 
{
    protected $allowed_var;
    protected $start_bracket_pattern;
    protected $end_bracket_pattern;

    public function __construct($allowed_var, $start_bracket_pattern="{{", $end_bracket_pattern="}}")
    {
        $this->allowed_var = $allowed_var;
        $this->start_bracket_pattern = $start_bracket_pattern;
        $this->end_bracket_pattern = $end_bracket_pattern;
    }

    private function getVarName($str)
    {
        $str = str_replace($this->start_bracket_pattern, "", $str);
        $str = str_replace($this->end_bracket_pattern, "", $str);
        $str = trim($str);

        return $str;
    }

    private function parse($text)
    {
        $var_array = array();
        preg_match_all("/(\\" . $this->start_bracket_pattern . ".+?\\" . $this->end_bracket_pattern . ")/i", $text, $var_array);

        $vars = $var_array[0];
        $output = [];
        $set = [];

        if(isset($vars))
        {
            foreach($vars as $var)
            {
                $var_name = $this->getVarName($var);

                if(isset($set[$var_name]))
                    continue;
                
                $output[] = $var_name;
                $set[$var_name] = true;
            }

            return $output;
        }
        
        return $output;
    }

    private  function populateVar($text, $var_name, $value)
    {
        return str_replace($this->start_bracket_pattern . $var_name . $this->end_bracket_pattern, $value, $text);
    }

    public function build($values, $text)
    {
        if(!isset($text))
            return "";
        
        $vars = $this->parse($text);
        foreach($vars as $var)
        {
            if(!in_array($var, $this->allowed_var))
            {
                $text = $this->populateVar($text, $var, "xxxx");
                continue;
            }
            
            if(!isset($values[$var]))
            {
                $text = $this->populateVar($text, $var, "????");
                continue;
            }

            $text = $this->populateVar($text, $var, $values[$var]);
        }

        return $text;
    }

}