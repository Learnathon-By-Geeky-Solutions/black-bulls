<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Spatie\Permission\Exceptions\UnauthorizedException;

class Handler extends ExceptionHandler
{
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

    public function render($request, Throwable $exception){
        
        if($exception instanceof AuthenticationException){
            return response()->json([
                'is_success' => false,
                'message' => 'Unauthenticated: Please Login to access this resource'
            ], 401);
        }

        if($exception instanceof UnauthorizedException){
            return response()->json([
                'is_sucess' => false,
                'message' => 'Unauthorized: You do not have permission to access this resource'
            ], 401);
        }

        return parent::render($request, $exception);
    }
}
