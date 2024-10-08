<?php

namespace App\Exceptions;

// use Illuminate\Auth\AuthenticationException;
use Throwable;
use App\Exceptions\ModelNotDefine;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
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
        //
    }

    public function render($request, Throwable $e)
    {
        if($e instanceof AuthorizationException)
        {
            if($request->expectsJson()){
                return response()->json(['errors' => [
                    "message" => "Bạn không có quyền thực hiện hành động này."
                ]], 403);
            }
        }

        if($e instanceof ModelNotFoundException && $request->expectsJson())
        {
            return response()->json(['errors' => [
                "message" => "Dữ liệu không tìm thấy 404."
            ]], 404);
        }

        
        /**
         * BeeSupper added
         */
        if($e instanceof ModelNotDefine && $request->expectsJson())
        {
            return response()->json(['errors' => [
                "message" => "Model khong duoc dinh nghia ."
            ]], 500);
        }

        return parent::render($request, $e);
    }
}
