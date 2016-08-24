<?php

namespace myocuhub\Exceptions;

use Auth;
use Exception;
use Mail;

class FreshDeskHandler
{

    protected $dontReport = [
        'Illuminate\\Foundation\\Validation\\ValidationException',
        'Illuminate\\Session\\TokenMismatchException',
        'Symfony\\Component\\HttpKernel\\Exception\\NotFoundHttpException',
    ];

    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    public function report()
    {

        if ($this->canReport() && env('MAIL_ERRORLOG', false)) {
            $message = $this->exception;
            if (Auth::user()) {
                $message = 'Exception for User ID: ' . Auth::user()->id . ' with email id: ' . Auth::user()->email . ' <br> ' . $message;
            }

            Mail::raw($message, function ($m) {
                $subject = 'Exception generated in the system';
                $m->from(config('constants.support.email_id'), config('constants.support.email_name'));
                $m->to(env('MAIL_ERRORLOG_TO', config('constants.support.application_error')), 'Application Error')->subject($subject);
            });
        }

        return;
    }

    public function canReport()
    {
        foreach ($this->dontReport as $class) {
            if (is_a($this->exception, $class)) {
                return false;
            }
        }
        return true;
    }

}
