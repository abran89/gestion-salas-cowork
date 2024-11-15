<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use Throwable;


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

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof QueryException) {

            $errorDetails = [
                'message' => 'Hubo un problema al intentar conectar con la base de datos.',
                'error' => $exception->getMessage(),
                'code' => $exception->getCode()
            ];

            return response()->view('errors.database', compact('errorDetails'), 500);

        }

        return parent::render($request, $exception);
    }
}
