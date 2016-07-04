<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    //
    
    public static function defaultTemplate(){
    	return '';
    }

    public static function getTemplate($type, $stage, $networkID){
    	$message = self::where('network_id', $networkID)
    		->where('type' , config('patient_engagement.type.'.$type))
			->where('stage' , config('patient_engagement.stage.'.$stage))
            ->first(['message']);
        
        if ($message){
        	return $message->message;
        } 

        return self::defaultTemplate();
    }
}
