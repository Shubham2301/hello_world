<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    //
    
    public static function defaultTemplate($type, $stage){
    	return self::where('network_id', NULL)
    		->where('type' , config('constants.message_type.'.$type))
			->where('stage' , config('constants.message_stage.'.$stage))
            ->first(['message']);
    }

    public static function getTemplate($type, $stage, $networkID){
    	$message = self::where('network_id', $networkID)
    		->where('type' , config('constants.message_type.'.$type))
			->where('stage' , config('constants.message_stage.'.$stage))
            ->first(['message']);
        
        if ($message){
        	return $message;
        } 

        return self::defaultTemplate($type, $stage);
    }
}
