<?php

namespace myocuhub\Helpers;
use DateTime;

trait DateTimeHelper{

	private $format;

	function __construct()
	{
		$this->_setFormat(config('constants.date_format'));
	}

    public function validateDate($date, $format = null)
    {
    	$format = ($format == null) ?: $this->getFormat();
        $dateInFormat = DateTime::createFromFormat($format, $date);
        return $dateInFormat && $dateInFormat->format($format) == $date;
    }

    public function getFormat()
    {
        return $this->format;
    }

    private function _setFormat($format)
    {
        $this->format = $format;
        return $this;
    }
}
