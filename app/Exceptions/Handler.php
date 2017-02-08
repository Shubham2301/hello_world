<?php

namespace myocuhub\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use myocuhub\Events\ExceptionCaught;

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
        if ($this->shouldReport($e)) {
            $user = Auth::user();
            if ($e instanceof TokenMismatchException && $user) {
                event(new ExceptionCaught([
                    'action' => 'Token mismatch for user = ' . $user->id ,
                    'description' => $e->getMessage(),
                    'filename' => $e->getFile()
                    ]));
            } else {
                event(new ExceptionCaught([
                    'action' => basename(get_class($e)).' at line '.$e->getLine().' in '.basename($e->getFile()),
                    'description' => $e->getMessage(),
                    'filename' => $e->getFile()
                    ]));
            }
            
            (new FreshDeskHandler($e))->report();
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
