<?php

namespace App\Http\Middleware;

use App\SafeSubmit\SafeSubmit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class GenerateSafeSubmitToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $safe_submit = app(SafeSubmit::class);
        if ($this->isReading($request)) {
            $safe_submit->regenerateToken();
        }
        Log::debug("Reading token: " . $safe_submit->token());
        return $next($request);
    }

    protected function isReading($request)
    {
        return in_array($request->method, ['HEAD', 'GET', 'OPTION']);
    }
}
