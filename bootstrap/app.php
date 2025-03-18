<?php
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'employeeMiddleware' => \App\Http\Middleware\EmployeeMiddleware::class,
            'adminMiddleware' => \App\Http\Middleware\AdminMiddleware::class,
            'SupervisorMiddleware' => \App\Http\Middleware\SupervisorMiddleware::class,
            'hrMiddleware' => \App\Http\Middleware\hrMiddleware::class,
            'auth.redirect' => \App\Http\Middleware\RedirectIfNotAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->booting(function (Application $app) {
        $app->make(Schedule::class)->command('holidays:fetch')->monthly();
        $app->make(Schedule::class)->command('leave:reset-yearly')->yearly();
    })
    ->create();
