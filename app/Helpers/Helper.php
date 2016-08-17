<?php

namespace myocuhub\Helpers;

/**
* Generic helper functions
*/
class Helper
{
    use AppHelper, DemographicsHelper, MessagesHelper, DateTimeHelper, MandrillHelper, ArrayHelper;

    public function __construct()
    {
    }
}
