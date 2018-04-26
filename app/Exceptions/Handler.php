<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{    
    protected $dontReport = [
        //
    ];
    
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];
    
    public function report(Exception $exception)
    {
        parent::report($exception);
    }
    
    public function render($request, Exception $exception)
    {
        if (app()->environment('production')) {
            $statusCode = 400;
            $title = '죄송합니다.';
            $description = '에러가 발생했습니다.';

            if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException
                or $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    $statusCode = 404;
                    $description = $exception->getMessage() ?: '요청하신 페이지가 없습니다.';                
            }
            return response(view('errors.notice', [
                'title' => $title,
                'description' => $description,
            ]), $statusCode);
        }

        return parent::render($request, $exception);
    }
}
