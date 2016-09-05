<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    //
    
    public static function defaultTemplate(){
    	return '';
    }

    public static function getTemplate($type, $stage, $networkID, $templatePart = 'message'){
	$message = self::where('network_id', $networkID)
    		->where('type' , config('patient_engagement.type.'.$type))
			->where('stage' , config('patient_engagement.stage.'.$stage))
            ->first(['message', 'subject']);
	if ($message){
            if($templatePart == 'message') {
                return $message->message;
            } else if ($templatePart == 'subject') {
                return $message->subject;
            }
        }
        return self::defaultTemplate();
    }

    public static function prepareMessage($attr, $template){
        foreach($attr as $key => $value){
            $template = str_replace('{'.strtoupper($key).'}', $value, $template);
        }
        return $template;
    }

    public static function getMessageTypes(){     
        $types = array_flip(config('patient_engagement.type'));
        foreach ($types as $key => $value) {
               $types[$key] = ucfirst($value);
        } 
        return $types;
    }
}
