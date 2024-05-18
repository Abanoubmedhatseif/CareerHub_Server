<?php

use App\Http\Middleware\ApplicationCandidateOrEmployerOrAdmin;
use App\Http\Middleware\OnlyAdmin;
use App\Http\Middleware\OnlyApplicationCandidate;
use App\Http\Middleware\OnlyApplicationEmployer;
use App\Http\Middleware\OnlyCandidate;
use App\Http\Middleware\OnlyEmployer;
use App\Http\Middleware\OnlyJobPostOwner;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->validateCsrfTokens(except: [
            // 'stripe/*',
            // 'http://example.com/foo/bar',
            // 'http://example.com/foo/*',
            'http://localhost:8000/*',

        ]);
        $middleware->alias([
            'onlyAdmin' => OnlyAdmin::class,
            'onlyEmployer' => OnlyEmployer::class,
            'onlyCandidate' => OnlyCandidate::class,
            'onlyJobPostOwner' => OnlyJobPostOwner::class,
            'onlyApplicationEmployer' => OnlyApplicationEmployer::class,
            'onlyApplicationCandidate' => OnlyApplicationCandidate::class,
            'applicationCandidateOrEmployerOrAdmin' => ApplicationCandidateOrEmployerOrAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
