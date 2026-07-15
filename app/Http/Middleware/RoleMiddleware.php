<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->role !== $role) {
            // Arahkan ke dashboard masing-masing jika tersesat
            if ($request->user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Peserta tidak boleh mengakses halaman admin.');
            } else {
                return redirect()->route('participant.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
            }
        }

        return $next($request);
    }
}
