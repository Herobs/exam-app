<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    public $admin;

    public function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->guest()) {
            return redirect()->guest('/admin/login');
        }

        $this->admin = Auth::guard('admin')->user();

        return $next($request);
    }
}
