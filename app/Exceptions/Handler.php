<?php

namespace myocuhub\Exceptions;

use Auth;
use Exception;
use Mail;
use myocuhub\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        if(!$e instanceof NotFoundHttpException) {
            // preserving the Exception object, required for calling the partent::report() method
            $message = $e;
            if(Auth::user()){
                $message = 'Exception for User ID: ' . Auth::user()->id . ' with email id: ' . Auth::user()->email . ' <br> ' . $message;
            }
 
            $maillogs = env('MAIL_ERRORLOG', false);
            
            if($maillogs) {
                Mail::raw($message, function ($m) {
                        $m->from('support@ocuhub.com', 'Ocuhub');
                        $m->to(env('MAIL_ERRORLOG_TO', 'applicationerror@ocuhub.com'), 'Application Error')->subject('Exception generated in the system');
                });
            }
        }
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        return parent::render($request, $e);
    }
}
