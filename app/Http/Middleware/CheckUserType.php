<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $type): Response
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized: Please log in.');
        }

        $user = auth()->user();
        $usertype = $user->usertype;

        // Define allowed user types based on the required type
        $allowedTypes = match ($type) {
//            'founder' => ['founder'],                // Only Founder
            'superadmin' => ['founder', 'superadmin'], // Founder and Superadmin
            'admin' => ['founder', 'superadmin', 'admin'], // Founder, Superadmin, and Admin
            'employee' => ['founder', 'superadmin', 'admin', 'employee'],
            default => [],
        };

        if (!in_array($usertype, $allowedTypes)) {
            abort(403, 'Unauthorized: Insufficient privileges for this route.');
        }

        return $next($request);
    }
}
