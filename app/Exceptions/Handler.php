<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\JsonResponse;

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
        parent::report($e);
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
        $parentRender = parent::render($request, $e);

        // if parent returns a JsonResponse 
        // for example in case of a ValidationException 
        if ($parentRender instanceof JsonResponse)
        {
            return $parentRender;
        }

        $status = $parentRender->status();
        $response = [ 'errors' => [ array('message' => $e->getMessage()) ] ];

        if (is_a($e, 'Google_Service_Exception')) {
            if ($e->getCode() >= 400) {
                $status = $e->getCode();
            }
            $response = [ 'errors' => $e->getErrors() ];
        }

        if (is_a($e, 'UnexpectedValueException')) {
            $status = 401;
        }

        return new JsonResponse($response, $status);    
    }
}
