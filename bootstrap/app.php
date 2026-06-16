<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Spatie permission middleware aliases + ERP API guards for RBAC and auditing.
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'api.permission' => \App\Http\Middleware\EnsureApiPermission::class,
            'api.audit' => \App\Http\Middleware\AuditApiActions::class,
        ]);

        // Authorize BEFORE route-model binding so a denied user gets 403 and
        // never triggers a 404 that would leak whether a record exists.
        $middleware->prependToPriorityList(
            before: \Illuminate\Routing\Middleware\SubstituteBindings::class,
            prepend: \App\Http\Middleware\EnsureApiPermission::class,
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Render a consistent JSON envelope for API requests.
        $exceptions->shouldRenderJsonWhen(function (Request $request, \Throwable $e) {
            return $request->is('api/*') || $request->expectsJson();
        });

        $exceptions->render(function (\Throwable $e, Request $request) {
            if (! ($request->is('api/*') || $request->expectsJson())) {
                return null;
            }

            return match (true) {
                $e instanceof ValidationException => response()->json([
                    'success' => false,
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], 422),
                $e instanceof AuthenticationException => response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                    'errors' => null,
                ], 401),
                $e instanceof AuthorizationException => response()->json([
                    'success' => false,
                    'message' => 'This action is unauthorized.',
                    'errors' => null,
                ], 403),
                $e instanceof ModelNotFoundException, $e instanceof NotFoundHttpException => response()->json([
                    'success' => false,
                    'message' => 'Resource not found.',
                    'errors' => null,
                ], 404),
                $e instanceof HttpExceptionInterface => response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'HTTP error.',
                    'errors' => null,
                ], $e->getStatusCode()),
                default => null, // fall through to default handler (debug-aware)
            };
        });
    })->create();
