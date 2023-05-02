<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use App\Trait\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{

    use ApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if($e instanceof ModelNotFoundException){
            return $this->errorResponse('404', $e->getMessage());
        }
        if($e instanceof MethodNotAllowedHttpException){
            return $this->errorResponse('404', $e->getMessage());
        }

        if($e instanceof Exception){
            return $this->errorResponse('500', $e->getMessage());
        }

        if(config('app.debug'))
        {
            return Parent::render($request,$e);
        }
        return $this->errorResponse('500', $e->getMessage());
    }
}
