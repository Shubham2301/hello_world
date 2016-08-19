<?php

namespace myocuhub\Helpers;

/**
* Generic helper functions
*/
class Helper
{
    use AppHelper, DemographicsHelper, MessagesHelper, DateTimeHelper, MandrillHelper, ArrayHelper, ImageHelper;

    public function __construct()
    {
    }
}
