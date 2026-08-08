<?php

namespace App\Http\Middleware;

use App\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AuthGates
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!app()->runningInConsole() && $user) {
            $permissionsArray = Cache::remember('auth_gates_permissions', now()->addHours(24), function () {
                $roles            = Role::with('permissions')->get();
                $permissionsArray = [];

                foreach ($roles as $role) {
                    foreach ($role->permissions as $permissions) {
                        $permissionsArray[$permissions->title][] = $role->id;
                    }
                }

                return $permissionsArray;
            });

            foreach ($permissionsArray as $title => $roles) {
                Gate::define($title, function (\App\User $user) use ($roles) {
                    return count(array_intersect($user->roles->pluck('id')->toArray(), $roles)) > 0;
                });
            }
        }

        return $next($request);
    }
}
