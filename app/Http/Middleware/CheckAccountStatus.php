<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'participant') {
            if ($user->account_status === 'pending') {
                Auth::logout();
                return redirect()->route('login')->with('error_status', 'pending');
            } elseif ($user->account_status === 'rejected') {
                Auth::logout();
                return redirect()->route('login')->with('error_status', 'rejected');
            }
        }

        return $next($request);
    }
}
