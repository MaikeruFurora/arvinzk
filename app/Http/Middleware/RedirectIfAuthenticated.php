<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if (auth()->user()->active) {
                    if (!empty(auth()->user()->user_type_id)) {
                        $userTypeName = strtolower(auth()->user()->user_type->name);
                        $route = "app.$userTypeName.home";
                        try {
                            // Try generating the route
                            return redirect()->route($route);
                        } catch (\Exception $e) {
                            // Route does not exist
                            Auth::guard('web')->logout();
                            return back()->with(['msg' => 'The user type route does not exist, please contact your administrator', 'action' => 'warning']);
                        }
                    } else {
                        Auth::guard('web')->logout();
                        return back()->with(['msg' => 'User type is not assigned, please contact your administrator', 'action' => 'warning']);
                    }
                } else {
                    Auth::guard('web')->logout();
                    return back()->with(['msg' => 'Your account is not active', 'action' => 'warning']);
                }
                
            }
        }

        return $next($request);
    }
}
