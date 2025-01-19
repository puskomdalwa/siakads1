<?php
namespace App\Http\Middleware;

use Closure;
use App\Http\Services\ServiceAuth;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // return $next($request);
        $logout = ServiceAuth::logout($request);
        if ($logout) {
            return $logout;
        }
        $roles = $this->CekRoute($request->route());

        if ($request->user()->hasRole($roles) || !$roles) {
            return $next($request);
        }
        return redirect('home'); //abort(503, 'Anda tidak memiliki hak akses');
    }

    private function CekRoute($route)
    {
        $actions = $route->getAction();
        return isset($actions['roles']) ? $actions['roles'] : null;
    }
}
