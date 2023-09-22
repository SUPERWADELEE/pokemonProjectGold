<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
    public function register()
{
    $this->renderable(function (NotFoundHttpException $e, $request) {
        // 匹配 /api/{model_name}/{model_id} 這種路徑的異常
    
        if (preg_match('/^\/api\/(\w+)\/\w+/', $request->getPathInfo(), $matches)) {
            $modelName = $matches[1]; // 获取模型名称
            return response()->json(["message" => "The $modelName data not found"], 404);
        }
        

        // 其他路徑的異常
        return response()->json(["message" => "The page not found"], 404);
    });
}



    public function render($request, Throwable $exception)
    {
        // if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
        //     return response()->json(["message" => $exception->getMessage()], 404);
        // }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json(['error' => 'Method not allowed'], 405);
        }
    

        return parent::render($request, $exception);
    }
}
