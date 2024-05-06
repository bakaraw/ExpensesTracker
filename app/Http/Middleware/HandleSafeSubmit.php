<?php

namespace App\Http\Middleware;

use App\SafeSubmit\SafeSubmit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class HandleSafeSubmit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $safe_submit = app(SafeSubmit::class);


        if ($request->{$safe_submit->tokenKey()} !== $safe_submit->token()) {
            if ($intended = $safe_submit->getIntended()) {
                $safe_submit->forgetIntended();
                return redirect($intended);
            }
            abort(419);
        }

        $safe_submit->regenerateToken();
        return $next($request);
    }
}
