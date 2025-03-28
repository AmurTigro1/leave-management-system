<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\CocLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class CheckCocExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, $next)
    {
        // Check and expire old COC logs
        $expiredLogs = CocLog::where('expires_at', '<=', now())
                            ->where('is_expired', false)
                            ->with('user')
                            ->get();
    
        foreach ($expiredLogs as $log) {
            DB::transaction(function () use ($log) {
                $log->user->decrement('overtime_balance', $log->coc_earned);
                $log->update(['is_expired' => true]);
            });
        }
    
        return $next($request);
    }
}
