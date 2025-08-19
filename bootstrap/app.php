<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Middleware\ForceJsonAccept;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
         $middleware->statefulApi();
         $middleware->api(prepend: [
            ForceJsonAccept::class,
        ]);
    })
    ->withBroadcasting(
        __DIR__.'/../routes/channels.php',
        ['prefix' => 'api', 'middleware' => ['api', 'auth:sanctum']],
    )
    ->withExceptions(function (Exceptions $exceptions) {
            $exceptions->render(function (HttpException $e, Request $request) {
                if (
                        $request->expectsJson() &&
                        $e->getStatusCode() === 403 &&
                        $e->getMessage() === 'Your email address is not verified.'
                    ){
                        return response()->json(['status' => true, 'message' => 'Your email address is not verified.'], status: 403);
                    }
            });
            $exceptions->render(function (HttpException $e, Request $request) {
                if ($request->expectsJson()) {
                    return match ($e->getStatusCode()) {
                        401 => response()->json(['status' => false, 'message' => 'Unauthorized', 'data' => []], 401),
                        403 => response()->json(['status' => false, 'message' => 'Forbidden', 'data' => []], 403),
                        404 => response()->json(['status' => false, 'message' => 'Not Found', 'data' => []], 404),
                    };
                }
            });
    })->create();
