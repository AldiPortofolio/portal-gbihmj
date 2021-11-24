<?php

namespace digipos\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;
use Session;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
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
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e){
        $exception = FlattenException::create($e);
        $statusCode = $exception->getStatusCode($exception);
        //default
        if ($statusCode === 404 or $statusCode === 500 and app()->environment() == 'production') {
            return response()->view('errors.' . $statusCode, [], $statusCode);
        }

        //custom
        // if ($statusCode === 404 or $statusCode === 500 and app()->environment() == 'production') {
        //     if($statusCode == 404){
        //         $this->data['statusCode'] = $statusCode;
        //         // return view('front.pages.index',$this->data);

        //         return redirect('/custom_err');
        //     }else{
        //         return response()->view('errors.' . $statusCode, [], $statusCode);
        //     }
        // }
        return parent::render($request, $e);
    }
}
