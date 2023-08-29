<?php

namespace App\Exceptions;

use App\Traits\apiResponse;
use Error;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    use apiResponse;
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if($e instanceof ModelNotFoundException)
        {
            return $this->errorResponse($e->getMessage(),404);
        }
        if($e instanceof NotFoundHttpException)
        {
            return $this->errorResponse($e->getMessage(),404);
        }
        if($e instanceof MethodNotAllowedException)
        {
            return $this->errorResponse($e->getMessage(),404);
        }
        if($e instanceof Error)
        {
            return $this->errorResponse($e->getMessage(),500);
        }
        if($e instanceof Exception)
        {
            return $this->errorResponse($e->getMessage(),404);
        }
        return $this->errorResponse($e->getMessage(),500);
    }
}
