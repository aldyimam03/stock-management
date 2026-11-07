<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! Auth::check()) {
            return redirect()->route('filament.admin.pages.login');
        }

        $user = Auth::user();

        if ($user->role !== $role) {
            abort(403, 'Unauthorized - Anda tidak memiliki akses ke panel ini.');
        }

        return $next($request);
    }
}