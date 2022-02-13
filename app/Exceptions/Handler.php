<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        ValidationException::class,
        ModelNotFoundException::class,
        AuthenticationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
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
        Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): handling exception');

        $code = $status = 404;
        $message = class_basename($e).': '.$e->getMessage();

        /** @var ValidationException $e */
        if ($e instanceof ValidationException) {
            $code = 422;
            $message = 'Přijatá data nejsou validní. Prosím zkontrolujte váš formulář.';
            $status = $e->status;

            return responder()->error($code, $message)
                ->data(['errors' => $e->errors()])
                ->respond($status);
        }

        /** @var AuthenticationException $e */
        if ($e instanceof AuthenticationException) {
            $code = $status = 403;
        }
        Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): exception data', [
            'code' => $code,
            'message' => $message,
            'status' => $status,
        ]);


        if ($request->wantsJson() || $request->expectsJson()) {
            Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): json');
            return responder()->error($code, $message)->respond($status)->setStatusCode($status);
        } else {
            Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): not json');
            return parent::render($request, $e);
        }
    }
}
