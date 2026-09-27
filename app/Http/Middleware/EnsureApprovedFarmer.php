<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApprovedFarmer
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isApprovedFarmer()) {
            return redirect()
                ->route('farmer.profile')
                ->with('error', 'Your farmer account must be approved before you can use farmer operations.');
        }

        return $next($request);
    }
}