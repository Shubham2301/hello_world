<?php

namespace myocuhub\Helpers;

trait MessagesHelper{

	public static function messageTypes(){     
        return self::invertConfig('patient_engagement.type');
    }

}