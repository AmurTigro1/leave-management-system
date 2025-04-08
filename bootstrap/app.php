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
            'employee' => \App\Http\Middleware\EmployeeMiddleware::class,
            'supervisor' => \App\Http\Middleware\SupervisorMiddleware::class,
            'hr' => \App\Http\Middleware\hrMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'auth' => \App\Http\Middleware\AuthMiddleware::class,
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        $middleware->append([
            \App\Http\Middleware\CheckCocExpiration::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->booting(function (Application $app) {
        $schedule = $app->make(Schedule::class);

        // $schedule->command('holidays:fetch')->monthly();
        // $schedule->command('leave:reset-yearly')->yearly();
        $schedule->command('leave:forfeit-unused-mandatory')->yearlyOn(12, 31, '23:59');
        $schedule->command('leave:update-balance')->monthly();
        $schedule->command('leave:deduct')->monthlyOn(1, '00:00');
    })
    
    ->create();
