<?php

namespace myocuhub\Http\Controllers\Reports;

use Illuminate\Http\Request;
use myocuhub\Facades\Helper;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Http\Requests;

class ReportController extends Controller
{
    protected $start_date;
    protected $end_date;

    protected function setStartDate($start_date)
    {
        $this->start_date = Helper::formatDate($start_date, config('constants.report_date_format.start_date_time'));
    }

    protected function getStartDate()
    {
        return $this->start_date;
    }

    protected function setEndDate($end_date)
    {
        $this->end_date = Helper::formatDate($end_date, config('constants.report_date_format.end_date_time'));
    }

    protected function getEndDate()
    {
        return $this->end_date;
    }
}
